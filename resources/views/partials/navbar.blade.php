<nav class="app-header navbar navbar-expand bg-white shadow-sm">

    <div class="container-fluid">

        {{-- Sidebar Toggle --}}
        <ul class="navbar-nav">

            <li class="nav-item">

                <a class="nav-link"
                    data-lte-toggle="sidebar"
                    href="#">

                    <i class="bi bi-list fs-4"></i>

                </a>

            </li>

        </ul>



        {{-- User Menu --}}
        <ul class="navbar-nav ms-auto">

            <li class="nav-item dropdown">

                <a class="nav-link dropdown-toggle"
                    href="#"
                    data-bs-toggle="dropdown">

                    <i class="bi bi-person-circle me-1"></i>

                    {{ Auth::user()->name ?? 'Administrator' }}

                </a>

                <ul class="dropdown-menu dropdown-menu-end">

                    <li>

                        <span class="dropdown-item-text">

                            <strong>
                                {{ Auth::user()->name ?? 'Administrator' }}
                            </strong>

                            <br>

                            <small class="text-muted">

                                {{ Auth::user()->email ?? 'admin@pst.com' }}

                            </small>

                        </span>

                    </li>

                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>

                        <form action="{{ route('logout') }}"
                            method="POST">

                            @csrf

                            <button type="submit"
                                class="dropdown-item text-danger">

                                <i class="bi bi-box-arrow-right"></i>

                                Logout

                            </button>

                        </form>

                    </li>

                </ul>

            </li>

        </ul>

    </div>

</nav>