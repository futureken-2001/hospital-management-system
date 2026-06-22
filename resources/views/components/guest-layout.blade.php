<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    

<title>{{ config('app.name', 'ALI MEDICAL SERVICE') }}</title>


    @vite(['resources/css/app.scss', 'resources/js/app.js'])
</head>
<body class="bg-primary d-flex align-items-center justify-content-center" style="min-height: 100vh;">

    <div class="w-100" style="max-width: 420px;">
        <div class="text-center text-white mb-4">
          <h1 class="fw-bold d-flex align-items-center justify-content-center gap-2">
    <img src="{{ asset('images/logo.jpeg') }}" alt="{{ config('app.name') }}" height="48" class="rounded">
    {{ config('app.name') }}
</h1> 
            <p class="text-white-50 mb-0">Internal staff access only</p>
        </div>

        <div class="card shadow-lg border-0">
            <div class="card-body p-4">

                @if (session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{ $slot }}
            </div>
        </div>
    </div>

</body>
</html>
