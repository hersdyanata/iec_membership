<div class="navbar navbar-expand-xl navbar-dark">
    <div class="text-center d-xl-none w-100">
        <button type="button" class="navbar-toggler dropdown-toggle" data-toggle="collapse" data-target="#navbar-demo1-mobile">
            <i class="icon-unfold mr-2"></i>
            Menu
        </button>
    </div>
    <div class="navbar-collapse collapse" id="navbar-demo1-mobile">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a href="{{ url('/') }}" class="navbar-nav-link {{ (Request::segment(1) === null) ? "active" : "" }}">
                    <i class="icon-home2 mr-2"></i> Beranda
                </a>
            </li>
        </ul>

        <span class="navbar-text ml-xl-auto"></span>

        <ul class="navbar-nav ml-xl-3">

            @if (Route::has('login'))
                @auth
                    <li class="nav-item">
                        <a href="{{ url('/dashboard') }}" class="navbar-nav-link">
                            <i class="icon-git-merge mr-2"></i> Dashboard
                        </a>
                    </li>

                    <a class="navbar-nav-link navbar-nav-link-toggler" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                        <i class="icon-switch2"></i>
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                @else
                    <li class="nav-item">
                        <a href="{{ route('login') }}" class="navbar-nav-link {{ (Request::segment(1) === 'login') ? "active" : "" }}">
                            <i class="icon-enter2 mr-2"></i> Login
                        </a>
                    </li>


                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a href="{{ route('register') }}" class="navbar-nav-link {{ (Request::segment(1) === 'register') ? "active" : "" }}">
                                <i class="icon-user-plus mr-2"></i> Buat Akun
                            </a>
                        </li>
                    @endif
                @endauth
            @endif
        </ul>
    </div>
</div>