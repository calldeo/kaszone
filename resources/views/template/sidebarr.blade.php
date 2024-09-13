<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">
<div class="deznav">
    <div class="deznav-scroll">
        <div class="main-profile">
            <div class="image-bx">
                <img src="{{ auth()->user()->poto ? asset('storage/' . auth()->user()->poto) : asset('dash/images/mamo.jpeg') }}" width="20" alt="">
            </div>
            <h5 class="name"><span class="font-w400">Hello,</span>{{auth()->user()->name}}</h5>
            <p class="email"><a href="javascript:void(0);" class="cf_email">{{auth()->user()->email}}</a></p>
        </div>
        
        <ul class="metismenu" id="menu">
            {{-- Daftar menu berdasarkan peran pengguna --}}
            @can('Home')
            <li class="nav-label first"></li>
            <li><a  href="/home" aria-expanded="false">
                    <i class="flaticon-144-layout"></i>
                    <span class="nav-text">Home</span>
                </a>
               
            </li>
            @endcan

            @can('Bendahara')
            <li class="{{ request()->is('user*') || request()->is('add_user') ? 'mm-active active-no-child' : '' }}">
                <a href="/user" aria-expanded="{{ request()->is('user*') || request()->is('add_user') ? 'true' : 'false' }}" class="{{ request()->is('user*') || request()->is('add_user') ? 'mm-active' : '' }}">
                    <i class="bi bi-people"></i>
                    <span class="nav-text">Data User</span>
                </a>
            </li>
            @endcan

            @can('Kategori')
            <li class="{{ request()->is('kategori*') || request()->is('add_kategori') ? 'mm-active active-no-child' : '' }}">
                <a href="/kategori" aria-expanded="{{ request()->is('kategori*') || request()->is('add_kategori') ? 'true' : 'false' }}" class="{{ request()->is('kategori*') || request()->is('add_kategori') ? 'mm-active' : '' }}">
                    <i class="bi bi-grid"></i>
                    <span class="nav-text">Kategori</span>
                </a>
            </li>
            @endcan

            @can('Data Pemasukan')
            <li class="{{ request()->is('pemasukan*') || request()->is('add_pemasukan') ? 'mm-active active-no-child' : '' }}">
                <a href="/pemasukan" aria-expanded="{{ request()->is('pemasukan*') || request()->is('add_pemasukan') ? 'true' : 'false' }}" class="{{ request()->is('pemasukan*') || request()->is('add_pemasukan') ? 'mm-active' : '' }}">
                    <i class="bi bi-file-earmark-arrow-down"></i>
                    <span class="nav-text">Data Pemasukan</span>
                </a>
            </li>
            @endcan

            @can('Data Pengeluaran')
            <li class="{{ request()->is('pengeluaran*') || request()->is('add_pengeluaran') ? 'mm-active active-no-child' : '' }}">
                <a href="/pengeluaran" aria-expanded="{{ request()->is('pengeluaran*') || request()->is('add_pengeluaran') ? 'true' : 'false' }}" class="{{ request()->is('pengeluaran*') || request()->is('add_pengeluaran') ? 'mm-active' : '' }}">
                    <i class="bi bi-file-earmark-arrow-up"></i>
                    <span class="nav-text">Data Pengeluaran</span>
                </a>
            </li>
            @endcan

            @can('Role')
            <li class="{{ request()->is('role*') || request()->is('add_role') ? 'mm-active active-no-child' : '' }}">
                <a href="/role" aria-expanded="{{ request()->is('role*') || request()->is('add_role') ? 'true' : 'false' }}" class="{{ request()->is('role*') || request()->is('add_role') ? 'mm-active' : '' }}">
                    <i class="bi bi-gear"></i>
                    <span class="nav-text">Role</span>
                </a>
            </li>
            @endcan
        </ul>
    </div>
</div>
