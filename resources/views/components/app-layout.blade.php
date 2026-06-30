<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'ALI MEDICAL SERVICES') }}</title>

    @vite(['resources/css/app.scss', 'resources/js/app.js'])
</head>
<body class="bg-light" @auth data-user-id="{{ auth()->id() }}" data-user-role="{{ auth()->user()->role }}" @endauth>

@auth
   
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand fw-semibold d-flex align-items-center" href="{{ route('dashboard') }}">
                <img src="{{ asset('images/logo.jpeg') }}" alt="{{ config('app.name') }}" height="32" class="me-2 rounded">
                {{ config('app.name') }}
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>
                    </li>

                    @if(in_array(auth()->user()->role, ['receptionist', 'super_admin', 'doctor', 'lab_technician']))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('patients.*') ? 'active' : '' }}" href="{{ route('patients.index') }}">Patients</a>
                        </li>
                    @endif

                    @if(in_array(auth()->user()->role, ['receptionist', 'super_admin', 'doctor']))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('appointments.*') ? 'active' : '' }}" href="{{ route('appointments.index') }}">Queue</a>
                        </li>
                    @endif

                    @if(in_array(auth()->user()->role, ['doctor', 'super_admin', 'lab_technician']))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('lab-tests.*') ? 'active' : '' }}" href="{{ route('lab-tests.index') }}">Lab Tests</a>
                        </li>
                    @endif

                    @if(auth()->user()->isSuperAdmin())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">Staff</a>
                        </li>
                    @endif
                </ul>

                <ul class="navbar-nav align-items-lg-center">
                    <li class="nav-item dropdown me-2">
                        <a class="nav-link dropdown-toggle position-relative" href="#" id="notifBell" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            🔔
                            <span id="notifBadge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger d-none">
                                0
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end p-2" id="notifList" style="min-width: 320px; max-height: 400px; overflow-y: auto;" aria-labelledby="notifBell">
                            <li class="dropdown-item text-muted small" id="notifEmpty">No notifications yet.</li>
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ auth()->user()->name }}
                            <span class="badge bg-light text-primary text-uppercase ms-1">{{ str_replace('_', ' ', auth()->user()->role) }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">My Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Log out</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
</nav>
@endauth

<main class="py-4">
    <div class="container-fluid">

        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{ $slot }}
    </div>
</main>

<!-- Real-time / popup notification toast -->
<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1080;">
    <div id="liveToast" class="toast align-items-center text-bg-primary border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body" id="liveToastBody"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

@stack('scripts')
</body>
</html>
