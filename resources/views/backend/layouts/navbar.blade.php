<style>
    .btn-soft {
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        transition: .2s;
    }

    .btn-soft:hover {
        background: #f3f4f6;
    }

    .user-btn {
        padding: 6px 10px;
        border-radius: 10px;
        transition: .2s;
    }

    .user-btn:hover {
        background: #f3f4f6;
    }

    li.nav-item.dropdown {
        background: #d6d6d6;
        border-radius: 5px;
    }
</style>

<nav class="app-header navbar navbar-expand bg-white border-bottom px-3 shadow-sm">

    <div class="container-fluid">

        {{-- LEFT --}}
        <ul class="navbar-nav align-items-center">

            <li class="nav-item">
                <a class="nav-link btn-soft" data-lte-toggle="sidebar">
                    <i class="bi bi-list fs-5"></i>
                </a>
            </li>

            <li class="nav-item d-none d-md-block ms-2">
                <a href="{{ route('dashboard') }}" class="nav-link fw-semibold text-dark">
                    Dashboard
                </a>
            </li>

        </ul>

        {{-- RIGHT --}}
        <ul class="navbar-nav ms-auto align-items-center gap-2">

            {{-- FULLSCREEN --}}
            <li class="nav-item">
                <a class="nav-link btn-soft" data-lte-toggle="fullscreen">
                    <i class="bi bi-arrows-fullscreen"></i>
                </a>
            </li>

            {{-- USER --}}
            <li class="nav-item dropdown">

                <a class="nav-link d-flex align-items-center gap-2 dropdown-toggle user-btn" data-bs-toggle="dropdown">

                    <img src="{{ Auth::user()->image && file_exists(public_path(Auth::user()->image))
                        ? asset(Auth::user()->image)
                        : asset('backend/assets/img/user_image.png') }}"
                        width="36" height="36" class="rounded-circle border" style="object-fit: cover;">

                    <span class="fw-semibold d-none d-md-inline text-dark">
                        {{ Auth::user()->name }}
                    </span>

                </a>

                <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 p-2"
                    style="min-width: 260px; border-radius: 14px;">

                    {{-- USER HEADER --}}
                    <li class="px-3 py-3 d-flex align-items-center gap-3">
                        <img src="{{ Auth::user()->image && file_exists(public_path(Auth::user()->image)) ? asset(Auth::user()->image) : asset('backend/assets/img/user_image.png') }}"
                            class="rounded-circle" width="42" height="42" style="object-fit: cover;">

                        <div class="lh-sm">
                            <div class="fw-semibold text-dark">{{ Auth::user()->name }}</div>
                            <small class="text-muted">{{ Auth::user()->email ?? '' }}</small>
                        </div>
                    </li>

                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    {{-- PROFILE --}}
                    <li>
                        <a class="dropdown-item d-flex align-items-center gap-2 py-2"
                            href="{{ route('user-profiles.edit', Auth::user()->id) }}">
                            <i class="bi bi-person-circle"></i>
                            My Profile
                        </a>
                    </li>

                    {{-- SETTINGS (optional future) --}}
                    <li>
                        <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="#">
                            <i class="bi bi-gear"></i>
                            Settings
                        </a>
                    </li>

                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    {{-- LOGOUT --}}
                    <li class="px-2 pb-2">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="btn btn-danger w-100 rounded-pill">
                                Sign out
                            </button>
                        </form>
                    </li>

                </ul>
            </li>

        </ul>

    </div>
</nav>
