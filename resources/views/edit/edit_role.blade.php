<!DOCTYPE html>

<html lang="en">

<head>
    @include('template.headerr')
    <title>E-vote | {{auth()->user()->level}} | Edit</title>
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
                <!-- Add Project -->
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
                        <div class="welcome-text">
                            <h4>Hi, welcome back!</h4>
                            <p class="mb-0">Bendahara</p>
                        </div>
                    </div>
                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0)">Form</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0)">Edit Bendahara</a></li>
                        </ol>
                    </div>
                </div>
                <!-- row -->
                <div class="row">
                
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Edit Data Bendahara</h4>
                            </div>
                            <div class="card-body">
                                <div class="basic-form">
                                    <form action="/role/{{ $role->id }}" method="POST" enctype="multipart/form-data">
                                        @method('put')
                                        @csrf
                                   <div class="form-group">
                                <label class="text-label">Name *</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"> <i class="fa fa-user"></i> </span>
                                    </div>
                                    <!-- Input hidden untuk nama -->
                                    <input type="hidden" class="form-control" name="name" value="{{ $role->name }}">
                                    <!-- Text display untuk menunjukkan nama saja, tanpa bisa di-edit -->
                                    <input type="text" class="form-control" value="{{ $role->name }}" placeholder="Enter a name.." readonly>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="text-label">Guard Name *</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"> <i class="fa fa-user"></i> </span>
                                    </div>
                                    <!-- Input hidden untuk guard_name -->
                                    <input type="hidden" class="form-control" name="guard_name" value="{{ $role->guard_name }}">
                                    <!-- Text display untuk menunjukkan guard_name saja, tanpa bisa di-edit -->
                                    <input type="text" class="form-control" value="{{ $role->guard_name }}" placeholder="Enter a email.." readonly>
                                </div>
                             </div>
                                <div class="form-group">
                                    <label class="text-label">Permissions *</label>
                                    <div class="row">
                                        @foreach($permissions as $permission)
                                            <div class="col-md-4 col-sm-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->id }}" 
                                                    {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
                                                    <label class="form-check-label">{{ $permission->name }}</label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                  

                                        <button type="submit" class="btn mr-2 btn-primary">Submit</button>
                                       <button type="submit" class="btn btn-light" onclick="redirectToRole()">Cancel</button>
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
           Support ticket button start
        *************-->

        <!--************
           Support ticket button end
        *************-->

        
    </div>
    <!--************
        Main wrapper end
    *************-->

    <!--************
        Scripts
    *************-->
    <!-- Required vendors -->
 @include('template.scripts')
</body>
</html>