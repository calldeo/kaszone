<!DOCTYPE html>

<html lang="en">

<head>
    @include('template.headerr')
    <title>PityCash | {{auth()->user()->level}} | Add</title>

</head>
<body>

    
     @include('template.topbarr')
        
       @include('template.sidebarr')
        
        <div class="content-body">
            <div class="container-fluid">
                
                <div class="modal fade" id="addProjectSidebar">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Create Project</h5>
                                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form>
                                    <div class="form-group">
                                        <label class="text-black font-w500">Project Name</label>
                                        <input type="text" class="form-control" name="project_name">
                                    </div>
                                    <div class="form-group">
                                        <label class="text-black font-w500">Deadline</label>
                                        <input type="date" class="form-control" name="deadline">
                                    </div>
                                    <div class="form-group">
                                        <label class="text-black font-w500">Client Name</label>
                                        <input type="text" class="form-control" name="client_name">
                                    </div>
                                    <div class="form-group">
                                        <button type="button" class="btn btn-primary">CREATE</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row page-titles mx-0">
                    <div class="col-sm-6 p-md-0">
                        
                    </div>
                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0)">Form</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0)">Kategori</a></li>
                        </ol>
                    </div>
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show">
                                <strong>Error!</strong> {{ session('error') }}
                                <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span></button>
                            </div>
                            @endif
                </div>
                
                <div class="row">
                
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Add</h4>
                            </div>
                            <div class="card-body">
                                <div class="basic-form">
                                    <form class="form-valide-with-icon" action="/kategori/store" method="post" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group">
                                            <label class="text-label">Nama *</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"> <i class="fa fa-user"></i> </span>
                                                </div>
                                                <input type="text" class="form-control" id="val-username1" name="name" placeholder="Enter a name.." value="{{old('name')}}"required>
                                            </div>
                                            @error('name')
                                            <span class="mt-4 text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                           <div class="form-group">
                                            <label class="text-label">Deskripsi *</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"> <i class="fa fa-user"></i> </span>
                                                </div>
                                                <input type="text" class="form-control" id="val-username1" name="description" placeholder="Enter a description.." value="{{old('description')}}"required>
                                            </div>
                                            @error('description')
                                            <span class="mt-4 text-danger">{{$message}}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label class="text-label">Jenis Kategori *</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"> <i class="fa fa-list"></i> </span>
                                                </div>
                                                <select class="form-control" name="jenis_kategori" required>
                                                    <option value="" disabled selected>Select Category type</option>
                                                    <option value="1">Pemasukan</option>
                                                    <option value="2">Pengeluaran</option>
                                                </select>
                                            </div>
                                            @error('jenis_kategori')
                                            <span class="mt-4 text-danger">{{$message}}</span>
                                            @enderror
                                        </div>

                                         <button type="submit" class="btn btn-danger btn-cancel" onclick="redirectToKategori()">Cancel</button>
                                    
                                        <button type="submit" class="btn mr-2 btn-primary btn-submit" >Submit</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
        <div class="footer">
            <div class="copyright">
                <p>Copyright © Designed &amp; Developed by <a href="/home" target="_blank">SYNC</a> 2024</p>
            </div>
        </div>
        

        
    </div>

 @include('template.scripts')
</body>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script> 


</html>