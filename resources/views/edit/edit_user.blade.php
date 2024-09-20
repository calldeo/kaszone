<!DOCTYPE html>
<html lang="en">

<head>
    @include('template.headerr')
    <title>PityCash | {{ auth()->user()->level }} | Edit</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
    @include('template.topbarr')
    @include('template.sidebarr')

    <div class="content-body">
        <div class="container-fluid">
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <h4>Hi, Welcome Back!</h4>
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

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Edit Data User</h4>
                        </div>
                        <div class="card-body">
                            <div class="basic-form">
                                <form action="/guruu/{{ $guruu->id }}" method="POST" enctype="multipart/form-data">
                                    @method('put')
                                    @csrf
                                    <div class="form-group">
                                        <label class="text-label">Name *</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"> <i class="fa fa-user"></i> </span>
                                            </div>
                                            <input type="text" class="form-control" name="name" value="{{ $guruu->name }}" placeholder="Enter a name.." required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="text-label">Email *</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"> <i class="fa fa-envelope"></i> </span>
                                            </div>
                                            <input type="email" class="form-control" name="email" value="{{ $guruu->email }}" placeholder="Enter an email.." required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="text-label">Password *</label>
                                        <div class="input-group transparent-append">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"> <i class="fa fa-lock"></i> </span>
                                            </div>
                                            <input type="password" class="form-control" name="password" placeholder="Choose a safe one..">
                                            <div class="input-group-append show-pass">
                                                <span class="input-group-text"> 
                                                    <i class="fa fa-eye-slash"></i>
                                                    <i class="fa fa-eye"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <label class="text-label text-danger mt-3">* Jika tidak ada perubahan, tidak perlu diisi</label>
                                    </div>
                                    <div class="form-group">
                                        <label>Gender *</label>
                                        <select class="form-control default-select" name="kelamin" required>
                                            <option value="">-- Select Gender --</option>
                                            <option value="laki-laki" {{ old('kelamin', $guruu->kelamin) == 'laki-laki' ? 'selected' : '' }}>Laki-Laki</option>
                                            <option value="perempuan" {{ old('kelamin', $guruu->kelamin) == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="text-label">Address *</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"> <i class="fa fa-map-marker-alt"></i> </span>
                                            </div>
                                            <input type="text" class="form-control" name="alamat" value="{{ $guruu->alamat }}" placeholder="Enter an address.." required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="text-label">Select Role *</label>
                                        @foreach($roles as $role)
                                        <div class="mb-2">
                                            <input 
                                                id="checkbox-{{ $role->name }}" 
                                                type="checkbox" 
                                                name="roles[]"  
                                                value="{{ $role->name }}"
                                                {{ in_array($role->name, old('roles', $guruu->roles->pluck('name')->toArray())) ? 'checked' : '' }}
                                            >
                                            <label for="checkbox-{{ $role->name }}" class="ml-2">{{ $role->name }}</label>
                                        </div>
                                        @endforeach
                                        @error('roles')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <a href="/user" class="btn btn-danger btn-cancel">Cancel</a>
                                    <button type="submit" class="btn btn-primary btn-submit">Update</button>
                                    
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('template.scripts')
</body>
</html>
