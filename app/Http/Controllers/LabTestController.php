<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLabTestRequest;
use App\Http\Requests\UpdateLabTestRequest;
use App\Models\LabTest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LabTestController extends Controller
{
    /**
     * Real-time list for lab_technicians (pending/in_progress first)
     * and history view for doctors/super_admin.
     */
    public function index(Request $request): View
    {
        $this->authorize('viewAny', LabTest::class);

        $user = $request->user();

        $query = LabTest::with(['patient', 'doctor', 'labTechnician']);

        if ($user->isDoctor()) {
            $query->where('doctor_id', $user->id);
        }

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        $labTests = $query->orderByDesc('requested_at')->paginate(15)->withQueryString();

        return view('lab_tests.index', compact('labTests'));
    }

    public function create(Request $request): View
    {
        $this->authorize('create', LabTest::class);

        $patientId = $request->query('patient_id');

        return view('lab_tests.create', compact('patientId'));
    }

    /**
     * Doctor orders a test. status starts at 'pending'; every
     * lab_technician is notified instantly (database + broadcast),
     * which removes the need for a paper request slip.
     */
    public function store(StoreLabTestRequest $request): RedirectResponse
    {
        $labTest = LabTest::create([
            'patient_id' => $request->validated('patient_id'),
            'doctor_id' => Auth::id(),
            'test_name' => $request->validated('test_name'),
            'status' => 'pending',
        ]);

        return redirect()
            ->route('lab-tests.show', $labTest)
            ->with('status', 'Lab test requested. The lab has been notified.');
    }

    public function show(LabTest $labTest): View
    {
        $this->authorize('view', $labTest);

        return view('lab_tests.show', compact('labTest'));
    }

    public function edit(LabTest $labTest): View
    {
        $this->authorize('updateResult', $labTest);

        return view('lab_tests.edit', compact('labTest'));
    }

    /**
     * Lab technician updates status and/or saves the result. When
     * status flips to 'completed', completed_at is stamped and
     * LabTestObserver notifies the requesting doctor.
     */
    public function update(UpdateLabTestRequest $request, LabTest $labTest): RedirectResponse
    {
        $data = $request->validated();

        if ($data['status'] === 'completed') {
            $data['completed_at'] = now();
            $data['lab_technician_id'] = $data['lab_technician_id'] ?? Auth::id();
        } elseif ($data['status'] === 'in_progress' && ! $labTest->lab_technician_id) {
            $data['lab_technician_id'] = Auth::id();
        }

        $labTest->update($data);

        return redirect()
            ->route('lab-tests.show', $labTest)
            ->with('status', 'Lab test updated.');
    }

    /**
     * Printable lab request form for the lab desk, in case a
     * physical copy is still wanted alongside the digital flow.
     */
    public function printRequest(LabTest $labTest): View
    {
        $this->authorize('view', $labTest);

        return view('print.lab_request', compact('labTest'));
    }
}
