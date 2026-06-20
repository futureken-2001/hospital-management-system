<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use App\Models\Patient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PatientController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Patient::class);

        $patients = Patient::query()
            ->search($request->query('q'))
            ->registeredOn($request->query('date'))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('patients.index', [
            'patients' => $patients,
            'q' => $request->query('q'),
            'date' => $request->query('date'),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', Patient::class);

        return view('patients.create');
    }

    public function store(StorePatientRequest $request): RedirectResponse
    {
        $patient = Patient::create($request->validated());

        return redirect()
            ->route('patients.show', $patient)
            ->with('status', "Patient registered successfully with number {$patient->patient_number}.");
    }

    public function show(Patient $patient): View
    {
        $this->authorize('view', $patient);

        $patient->load(['appointments.doctor', 'labTests.doctor', 'labTests.labTechnician', 'auditLogs.user']);

        return view('patients.show', compact('patient'));
    }

    public function edit(Patient $patient): View
    {
        $this->authorize('update', $patient);

        return view('patients.edit', compact('patient'));
    }

    public function update(UpdatePatientRequest $request, Patient $patient): RedirectResponse
    {
        $patient->update($request->validated());

        return redirect()
            ->route('patients.show', $patient)
            ->with('status', 'Patient record updated.');
    }

    public function destroy(Patient $patient): RedirectResponse
    {
        $this->authorize('delete', $patient);

        $patient->delete();

        return redirect()
            ->route('patients.index')
            ->with('status', 'Patient record deleted.');
    }

    /**
     * Printable patient identity card (name, number, age, residence,
     * phone, QR-free simple layout) opened in a new tab and printed
     * via the browser print dialog (see resources/views/print/patient_card.blade.php).
     */
    public function printCard(Patient $patient): View
    {
        $this->authorize('view', $patient);

        return view('print.patient_card', compact('patient'));
    }
}
