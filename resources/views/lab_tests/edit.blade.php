<x-app-layout title="Update Lab Test">

    <div class="row justify-content-center">
        <div class="col-lg-7">

            <h3 class="mb-4">Update — {{ $labTest->test_name }}</h3>

            <div class="card mb-3">
                <div class="card-body">
                    <dl class="row mb-0 small">
                        <dt class="col-4">Patient</dt>
                        <dd class="col-8">{{ $labTest->patient->name }} ({{ $labTest->patient->patient_number }})</dd>
                        <dt class="col-4">Requested by</dt>
                        <dd class="col-8">Dr. {{ $labTest->doctor->name }}</dd>
                    </dl>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('lab-tests.update', $labTest) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <x-input-label for="status" value="Status" />
                            <select id="status" name="status" class="form-select" required>
                                <option value="pending" {{ old('status', $labTest->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="in_progress" {{ old('status', $labTest->status) === 'in_progress' ? 'selected' : '' }}>In progress</option>
                                <option value="completed" {{ old('status', $labTest->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" />
                        </div>

                        <div class="mb-3">
                            <x-input-label for="result" value="Result" />
                            <textarea id="result" name="result" rows="5" class="form-control" placeholder="Enter the test result/findings here...">{{ old('result', $labTest->result) }}</textarea>
                            <div class="form-text">Required when status is set to "Completed". The requesting doctor is notified instantly once saved.</div>
                            <x-input-error :messages="$errors->get('result')" />
                        </div>

                        <div class="d-flex gap-2">
                            <x-primary-button>Save</x-primary-button>
                            <a href="{{ route('lab-tests.show', $labTest) }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

</x-app-layout>
