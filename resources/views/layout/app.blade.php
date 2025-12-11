<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard Inventaris')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        /* ... (Semua style dari jawaban sebelumnya untuk sidebar toggle) ... */
        body {
            font-size: .875rem;
        }

        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            padding: 48px 0 0;
            box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
            overflow-y: auto;
            transition: margin 0.3s ease-in-out;
        }

        main {
            transition: all 0.3s ease-in-out;
        }

        .sidebar .nav-link {
            font-weight: 500;
            color: #333;
        }

        .sidebar .nav-link .bi {
            margin-right: 8px;
        }

        .sidebar .nav-link.active {
            color: #0d6efd;
        }

        .navbar-brand {
            padding-top: .75rem;
            padding-bottom: .75rem;
            font-size: 1rem;
        }

        .navbar-toggler-desktop {
            display: none;
            color: rgba(255, 255, 255, 0.55);
            border: none;
        }

        .navbar-toggler-desktop:hover {
            color: rgba(255, 255, 255, .75);
        }

        @media (min-width: 768px) {
            .navbar-toggler-desktop {
                display: block;
                margin-left: 10px;
            }

            .navbar-toggler-mobile {
                display: none;
            }
        }

        #main-wrapper.sidebar-collapsed #sidebarMenu {
            margin-left: -100%;
        }

        #main-wrapper.sidebar-collapsed main {
            width: 100%;
            margin-left: 0;
        }

        /* [BARU] Style untuk user dropdown di navbar */
        .navbar .nav-item .nav-link {
            color: rgba(255, 255, 255, .55);
        }

        .navbar .nav-item .nav-link:hover {
            color: rgba(255, 255, 255, .75);
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
        <button class="navbar-toggler-desktop" type="button" id="sidebarToggle">
            <i class="bi bi-list fs-4"></i>
        </button>

        <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="{{ route('dashboard') }}">
            <img src="https://via.placeholder.com/30x24/FFFFFF/000000?text=LOGO" alt="Logo" width="30" height="24"
                class="d-inline-block align-text-top me-2" style="background-color: white; border-radius: 4px;">
            Proyek Inventaris
        </a>

        <button class="navbar-toggler navbar-toggler-mobile position-absolute d-md-none collapsed" type="button"
            data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="navbar-nav ms-auto">
            <div class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarUserDropdown" role="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle me-1"></i>
                    @auth {{ Auth::user()->username }}
                    @endauth
                </a>
                <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end" aria-labelledby="navbarUserDropdown">
                    <li>
                        <a class="dropdown-item" href="#"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </nav>

    <div class="container-fluid" id="main-wrapper">
        <div class="row">
            @include('layout.sidebar')
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">@yield('page_title', 'Dashboard')</h1>
                </div>

                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // JS untuk Toggle Sidebar (dari jawaban sebelumnya)
        document.addEventListener("DOMContentLoaded", function () {
            if (localStorage.getItem('sidebar-collapsed') === 'true') {
                document.getElementById('main-wrapper').classList.add('sidebar-collapsed');
            }
            document.getElementById('sidebarToggle').addEventListener('click', function () {
                let wrapper = document.getElementById('main-wrapper');
                wrapper.classList.toggle('sidebar-collapsed');
                localStorage.setItem('sidebar-collapsed', wrapper.classList.contains('sidebar-collapsed'));
            });
        });
    </script>
</body>

</html>