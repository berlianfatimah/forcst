 <!-- partial:partials/_navbar.html -->
 <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
      <a class="navbar-brand brand-logo mr-5" href="{{ url('/') }}"><img src="{{ asset('images/forecast.png') }}" class="mr-2" alt="logo"/></a>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
      <ul class="navbar-nav navbar-nav-right">
        
        <li class="nav-item nav-profile dropdown">
          <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
            <img src="images/ber.png" alt="profile"/>
          </a>
          <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
            {{-- <a class="dropdown-item">
              <i class="ti-settings text-success"></i>
              Settings
            </a> --}}
            <form action="{{ route('logout') }}"  method="post">
              @csrf
              <button type="submit" class="dropdown-item"> <i class="ti-power-off text-success"></i>Logout</button>
            </form>
            {{-- <a class="dropdown-item">
              <i class="ti-power-off text-success"></i>
              Logout
            </a> --}}
          </div>
        </li>
      </ul>
      <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
        <span class="icon-menu"></span>
      </button>
    </div>
  </nav>