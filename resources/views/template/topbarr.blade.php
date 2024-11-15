<div id="preloader">
    <div class="sk-three-bounce">
        <div class="sk-child sk-bounce1"></div>
        <div class="sk-child sk-bounce2"></div>
        <div class="sk-child sk-bounce3"></div>
    </div>
</div>
<!--***
    Preloader end
****-->

<!--****
    Main wrapper start
*****-->
<div id="main-wrapper">

    <!--****
        Nav header start
    *****-->
    <a href="/home" class="brand-logo" style="display: block; width: 200px; height: 50px;">
        <svg class="logo-abbr" width="100%" height="100%" viewBox="0 0 200 50" fill="none" xmlns="http://www.w3.org/2000/svg">
            <!-- Gambar Logo -->
            <image class="svg-logo-rect" width="200" height="50" href="dashboards/dist/images/pitycash2.svg" />
            <!-- Path Logo -->
            <path class="svg-logo-path" d="M17.5158 25.8619L19.8088 25.2475L14.8746 11.1774C14.5189 9.84988 15.8701 9.0998 16.8205 9.75055L33.0924 22.2055C33.7045 22.5589 33.8512 24.0717 32.6444 24.3951L30.3514 25.0095L35.2856 39.0796C35.6973 40.1334 34.4431 41.2455 33.3397 40.5064L17.0678 28.0515C16.2057 27.2477 16.5504 26.1205 17.5158 25.8619ZM18.685 14.2955L22.2224 24.6007L29.4633 22.6605L18.685 14.2955ZM31.4751 35.9615L27.8171 25.6886L20.5762 27.6288L31.4751 35.9615Z" fill="white"></path>
        </svg>
    </a>
    <div class="nav-header">
        <a href="/home" class="brand-logo">
           
            <img class="brand-title" src="{{ asset('dashboards/dist/images/pitycash2.svg') }}" alt="Brand Title" width="100" height="40">


              
                <path class="svg-logo-path" d="M17.5158 25.8619L19.8088 25.2475L14.8746 11.1774C14.5189 9.84988 15.8701 9.0998 16.8205 9.75055L33.0924 22.2055C33.7045 22.5589 33.8512 24.0717 32.6444 24.3951L30.3514 25.0095L35.2856 39.0796C35.6973 40.1334 34.4431 41.2455 33.3397 40.5064L17.0678 28.0515C16.2057 27.2477 16.5504 26.1205 17.5158 25.8619ZM18.685 14.2955L22.2224 24.6007L29.4633 22.6605L18.685 14.2955ZM31.4751 35.9615L27.8171 25.6886L20.5762 27.6288L31.4751 35.9615Z" fill="white"></path>
            </img>
          
        </a>
    
        <div class="nav-control">
            <div class="hamburger">
                <span class="line"></span><span class="line"></span><span class="line"></span>
            </div>
        </div>
    </div>
    
    <!--****
        Nav header end
    *****-->
    
    <!--****
        Chat box start
    *****-->

    <!--****
        Chat box End
    *****-->
    
    <!--****
        Header start
    *****-->
    <div class="header">
        <div class="header-content">
            <nav class="navbar navbar-expand">
                <div class="collapse navbar-collapse justify-content-between">
                    <div class="header-left">
                        {{-- <div class="input-group search-area right d-lg-inline-flex d-none">
                            <input type="text" class="form-control" placeholder="Find something here...">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <a href="javascript:void(0)">
                                        <i class="flaticon-381-search-2"></i>
                                    </a>
                                </span>
                            </div>
                        </div> --}}
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <div class="media align-items-center">
                                    <i class="fas fa-wallet mr-2"></i> <!-- Ikon dompet -->
                                    <div class="media-body">
                                        <h5 class="mb-0
                                            @if($saldo <= $minimalSaldo)
                                                text-danger
                                            @endif">
                                            Saldo: Rp{{ number_format($saldo, 0, ',', '.') }}
                                        </h5>
                                        @if($saldo <= $minimalSaldo)
                                            <p class="text-warning mb-0">Peringatan: Saldo Anda sudah di batas minimal (Rp{{ number_format($minimalSaldo, 0, ',', '.') }})</p>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        </li>
                        
                    </div>
                    <ul class="navbar-nav header-right main-notification">
                        <li class="nav-item dropdown notification_dropdown">
                            {{-- <a class="nav-link bell dz-theme-mode" href="#">
                                <i id="icon-light" class="fa fa-sun-o"></i>
                                <i id="icon-dark" class="fa fa-moon-o"></i>
                            </a> --}}
                        </li>
                        <li class="nav-item dropdown notification_dropdown">
                            <a class="nav-link bell dz-fullscreen" >
                                <svg id="icon-full" viewbox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3" style="stroke-dasharray: 37, 57; stroke-dashoffset: 0;"></path></svg>
                                <svg id="icon-minimize" width="20" height="20" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-minimize"><path d="M8 3v3a2 2 0 0 1-2 2H3m18 0h-3a2 2 0 0 1-2-2V3m0 18v-3a2 2 0 0 1 2-2h3M3 16h3a2 2 0 0 1 2 2v3" style="stroke-dasharray: 37, 57; stroke-dashoffset: 0;"></path></svg>
                            </a>
                        </li>
                    
                      <li class="nav-item dropdown header-profile">
                            <a class="nav-link" href="#" role="button" data-toggle="dropdown">
                                <img src="{{ auth()->user()->poto ? asset('storage/' . auth()->user()->poto) : asset('dash/images/mamo.jpeg') }}" width="20" alt="">
                                <div class="header-info">
                                    <span>{{ auth()->user()->name }}</span> 
                                 <small>
                                    @if(auth()->user()->roles->count() > 1)
                                        {{ session('activeRole') }}
                                    @else
                                        {{ auth()->user()->roles->first()->name }}
                                    @endif
                                </small>


                                
                                </div>
                            </a>
                            
                           <div class="dropdown-menu dropdown-menu-right">
   <!-- Link Profil -->
<a href="/profile/edit" class="dropdown-item ai-icon">
    <svg id="icon-user1" xmlns="http://www.w3.org/2000/svg" class="text-primary" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
        <circle cx="12" cy="7" r="4"></circle>
    </svg>
    <span class="ml-2">Profile</span>
</a>

<!-- Link Logout -->
<a href="/logout" class="dropdown-item ai-icon" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
    <svg id="icon-logout" xmlns="http://www.w3.org/2000/svg" class="text-danger" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
        <polyline points="16 17 21 12 16 7"></polyline>
        <line x1="21" y1="12" x2="9" y2="12"></line>
    </svg>
    <span class="ml-2">Logout</span>
</a>

<!-- Logout Form -->
<form id="logout-form" action="/logout" method="POST" style="display: none;">
    @csrf
</form>
    <!-- Cek jika user memiliki lebih dari satu role -->
    @if(auth()->user()->roles->count() > 1)
        <!-- Role Switch Form -->
        <form id="roleSwitchForm" action="{{ route('switchRole') }}" method="POST" class="px-2 py-1">
            @csrf
            <input type="hidden" name="role" id="roleInput" value="{{ session('activeRole') }}">
            
            @foreach(auth()->user()->roles as $role)
                <button type="button" id="{{ $role->name }}Button" onclick="switchRole('{{ $role->name }}')" class="dropdown-item btn btn-sm text-left {{ session('activeRole') === $role->name ? 'btn-primary text-white' : 'btn-secondary' }}">
                    Ganti ke {{ $role->name }}
                </button>
            @endforeach
        </form>

        </div>
        <script>
        function switchRole(role) {
            // Update value of hidden input
            document.getElementById('roleInput').value = role;

            // Update button classes based on selected role
            @foreach(auth()->user()->roles as $role)
                if (role === '{{ $role->name }}') {
                    document.getElementById('{{ $role->name }}Button').classList.remove('btn-secondary');
                    document.getElementById('{{ $role->name }}Button').classList.add('btn-primary', 'text-white');
                } else {
                    document.getElementById('{{ $role->name }}Button').classList.remove('btn-primary', 'text-white');
                    document.getElementById('{{ $role->name }}Button').classList.add('btn-secondary');
                }
            @endforeach

            // Submit the form
            document.getElementById('roleSwitchForm').submit();
        }
        </script>
    @endif                 
                                
                            

                        </li>
                    </ul>
                </div>
            </nav>
            {{-- <div class="sub-header">
                <div class="d-flex align-items-center flex-wrap mr-auto">
                    <h5 class="dashboard_bar">Dashboard</h5>
                </div>
                
            </div> --}}
        </div>
    </div>