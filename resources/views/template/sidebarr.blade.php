<div class="deznav">
	<div class="deznav-scroll">
		<div class="main-profile">
			<div class="image-bx">
				<img src="{{ auth()->user()->poto ? asset('storage/' . auth()->user()->poto) : asset('dash/images/mamo.jpeg') }}" width="20" alt="">
				{{-- <a href="javascript:void(0);"><i class="fa fa-cog" aria-hidden="true"></i></a> --}}
			</div>
			<h5 class="name"><span class="font-w400">Hello,</span>{{auth()->user()->name}}</h5>
			<p class="email"><a href="" class="cf_email" data-cfemail="95f8f4e7e4e0f0efefefefd5f8f4fcf9bbf6faf8">{{auth()->user()->email}}</a></p>
		</div>
		
		<ul class="metismenu" id="menu">
			{{-- @if (auth()->user()->level=="bendahara"|| auth()->user()->level == "admin"|| auth()->user()->level == "siswa") --}}
			@can('home')
			<li class="nav-label first"></li>
			<li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
					<i class="flaticon-144-layout"></i>
					<span class="nav-text" >Home</span>
				</a>
				<ul aria-expanded="false">
					<li><a href="/home">Dashboard</a></li>
					
				</ul>
			</li>
			@endcan
			@can('admin')
			<li class="nav-label first">Main Menu</li>
			<li><a  href="/admin" aria-expanded="false">
					<i class="flaticon-044-file"></i>
					<span class="nav-text">Data Admin</span>
				</a>
			   
			</li>
			@endcan
			@can('bendahara')
			  <li><a  href="/bendahara" aria-expanded="false">
					<i class="flaticon-044-file"></i>
					<span class="nav-text">Data Bendahara</span>
				</a>
			   
			</li>
			@endcan
			@can('kategori')
			<li><a  href="/kategori" aria-expanded="false">
					<i class="flaticon-044-file"></i>
					<span class="nav-text">Kategori</span>
				</a>
			   
			</li>
			@endcan
			@can('datapemasukan')
                <li><a href="/pemasukan" aria-expanded="false">
                        <i class="flaticon-044-file"></i>
                        <span class="nav-text">Data Pemasukan</span>
                    </a>
                </li>
			@endcan
			@can('datapengeluaran')
                <li><a href="/pengeluaran" aria-expanded="false">
                        <i class="flaticon-044-file"></i>
                        <span class="nav-text">Data Pengeluaran</span>
                    </a>
                </li>
			@endcan
           
		
		</ul>
		<div class="copyright">
			<p><strong>E-Vote </strong> © 2024 All Rights Reserved</p>
			<p class="fs-12">Made with <span class="heart"></span> by SYNC</p>
		</div>
	</div>
</div>