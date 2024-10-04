<!DOCTYPE html>
<html lang="en">

<head>
    @include('template.headerr')
    <title>PityCash | {{auth()->user()->level}} | Add Role</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

</head>

<body>
    <!--*******
        Preloader start
    ********-->
    @include('template.topbarr')
    <!--************
        Header end ti-comment-alt
    *************-->

    <!--************
        Sidebar start
    *************-->
    @include('template.sidebarr')
    <!--************
        Sidebar end
    *************-->

    <!--************
        Content body start
    *************-->
    <div class="content-body">
        <div class="container-fluid">
            <!-- row -->
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                  
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Form</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Add Role</a></li>
                    </ol>
                </div>
            </div>

            <!-- Error Message -->
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <strong>Error!</strong> {{ session('error') }}
                    <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span></button>
                </div>
            @endif

            <!-- Add Pemasukan Form -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Add Role</h4>
                        </div>
                        <div class="card-body">
                            <div class="basic-form">
                                <form class="form-valide-with-icon" action="/role/store" method="post">
                                   @csrf
                                <div class="form-group">
                                    <label class="text-label">Nama *</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"> <i class="fa fa-user"></i> </span>
                                        </div>
                                        <input type="text" class="form-control" name="name" placeholder="Enter name.." value="{{ old('name') }}" required>
                                    </div>
                                    @error('name')
                                    <span class="mt-2 text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                 <div class="form-group">
                                    <label class="text-label">Guard Nama *</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"> <i class="fa fa-user"></i> </span>
                                        </div>
                                        <input type="text" class="form-control" name="guard_name" placeholder="Enter guard.." value="{{ old('guard_name') }}" required>
                                    </div>
                                    @error('guard_name')
                                    <span class="mt-2 text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                
                                    <button type="button" class="btn btn-danger btn-cancel" onclick="window.location.href='/pemasukan'">Cancel</button>

                                    <button type="submit" class="btn mr-2 btn-primary btn-submit">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--************
        Content body end
    *************-->

    <!--************
        Footer start
    *************-->
    <div class="footer">
        <div class="copyright">
            <p>Copyright © Designed &amp; Developed by <a href="/home" target="_blank">SYNC</a> 2024</p>
        </div>
    </div>
    <!--************
        Footer end
    *************-->

    <!--************
        Scripts
    *************-->
    <!-- Required vendors -->
    @include('template.scripts')
</body>

</html>
