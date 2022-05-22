      <!-- partial:partials/_sidebar.html -->
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item">
            <a class="nav-link" href="{{ url('/') }}">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Dashboard</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ url('/province') }}">
              <i class="icon-map menu-icon"></i>
              <span class="menu-title">Data Provinsi</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ url('/actual') }}">
              <i class="icon-layout menu-icon"></i>
              <span class="menu-title">Data Aktual</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ url('/forecasting') }}">
              <i class="icon-bar-graph menu-icon"></i>
              <span class="menu-title">Forecasting</span>
            </a>
          </li>
        </ul>
      </nav>