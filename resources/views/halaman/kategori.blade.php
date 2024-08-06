<!DOCTYPE html>
<html lang="en">

<head>
    @include('template.headerr')
    <title>KasZone | {{ auth()->user()->level }} | Categories</title>
</head>

<body>
    @include('template.topbarr')
    @include('template.sidebarr')

    <div class="content-body">
        <div class="container-fluid">
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <h4>Hi, welcome back!</h4>
                        <p class="mb-0">Data Categories</p>
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Table</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Categories</a></li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Data Categories</h4>
                            <div class="text-right">
                                <div class="input-group search-area right d-lg-inline-flex d-none">
                                    <form id="searchForm">
                                        <input id="searchInput" type="text" class="form-control" placeholder="Cari kategori..." name="query">
                                    </form>
                                </div>
                                <button type="button" class="btn btn-warning ml-2" title="Import" data-toggle="modal" data-target="#importModal">
                                    <i class="fa fa-upload"></i>
                                </button>

                                <!-- Import Modal -->
                                {{-- <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="importModalLabel">Import Categories</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('categories.import') }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="form-group">
                                                        <label for="file">Select Excel File</label>
                                                        <input type="file" class="form-control-file" id="file" name="file" required>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary">Import</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}

                                <a href="/add_kategori" class="btn btn-success" title="Add">
                                    <i class="fa fa-plus"></i>
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                <strong>Success!</strong> {{ session('success') }}
                                <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span></button>
                            </div>
                            @endif
                            @if(session('update_success'))
                            <div class="alert alert-warning alert-dismissible fade show">
                                <strong>Success!</strong> {{ session('update_success') }}
                                <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span></button>
                            </div>
                            @endif
                            <div class="table-responsive" id="categoryTable">
                                <table class="table table-responsive-md">
                                    <thead>
                                        <tr>
                                            <th style="width:50px;">
                                                <div class="custom-control custom-checkbox checkbox-info check-lg mr-3">
                                                    <input type="checkbox" class="custom-control-input" id="checkAll" required="">
                                                    <label class="custom-control-label" for="checkAll"></label>
                                                </div>
                                            </th>
                                            <th><strong>ID</strong></th>
                                            <th><strong>Name</strong></th>
                                            <th><strong>Description</strong></th>
                                            <th style="text-align: center;"><strong>Options</strong></th>
                                        </tr>
                                    </thead>
                                    <tbody id="categoryTableBody">
                                        @foreach($categories as $category)
                                        <tr>
                                            <td>
                                                <div class="custom-control custom-checkbox checkbox-info check-lg mr-3">
                                                    <input type="checkbox" class="custom-control-input" id="customCheckBox{{ $category->id }}" required="">
                                                    <label class="custom-control-label" for="customCheckBox{{ $category->id }}"></label>
                                                </div>
                                            </td>
                                            <td><h6>{{ $category->id }}</h6></td>
                                            <td>{{ $category->name }}</td>
                                            <td>{{ $category->description }}</td>
                                            <td class="text-align: left;">
                                                <div class="d-flex justify-content-center">
                                                    <form id="editForm_{{ $category->id }}" action="" method="GET">
                                                        <button type="submit" class="btn btn-warning shadow btn-xs sharp"><i class="fa fa-pencil"></i></button>
                                                    </form>
                                                    <div class="mx-1"></div>
                                                    <form id="deleteForm_{{ $category->id }}" action="" method="POST" class="delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-danger shadow btn-xs sharp delete-btn" data-id="{{ $category->id }}"><i class="fa fa-trash"></i></button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-end">
                                {{ $categories->links() }}
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

    @include('template.scripts')

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let searchInput = document.getElementById('searchInput');

            searchInput.addEventListener('input', function() {
                let searchValue = searchInput.value;

                fetch('/categories/search?search=' + encodeURIComponent(searchValue))
                    .then(response => response.json())
                    .then(data => {
                        updateTable(data);
                    })
                    .catch(error => console.error('Error:', error));
            });

            function updateTable(data) {
                let categoryTableBody = document.getElementById('categoryTableBody');
                categoryTableBody.innerHTML = '';

                data.forEach(category => {
                    let row = `
                        <tr>
                            <td>
                                <div class="custom-control custom-checkbox checkbox-info check-lg mr-3">
                                    <input type="checkbox" class="custom-control-input" id="customCheckBox_${category.id}" required="">
                                    <label class="custom-control-label" for="customCheckBox_${category.id}"></label>
                                </div>
                            </td>
                            <td><h6>${category.id}</h6></td>
                            <td>${category.name}</td>
                            <td>${category.description}</td>
                            <td class="text-align: left;">
                                <div class="d-flex justify-content-center">
                                    <form id="editForm_${category.id}" action="/categories/${category.id}/edit" method="GET">
                                        <button type="submit" class="btn btn-warning shadow btn-xs sharp"><i class="fa fa-pencil"></i></button>
                                    </form>
                                    <div class="mx-1"></div>
                                    <form id="deleteForm_${category.id}" action="/categories/${category.id}/delete" method="POST" class="delete-form">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <button type="submit" class="btn btn-danger shadow btn-xs sharp delete-btn" data-id="${category.id}"><i class="fa fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    `;
                    categoryTableBody.insertAdjacentHTML('beforeend', row);
                });
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        $(document).ready(function() {
            $(document).on('click', '.delete-btn', function() {
                var id = $(this).data('id');
                Swal.fire({
                    title: 'Apakah anda yakin hapus data ini?',
                    text: "Data akan dihapus secara permanen",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Iya, hapus data!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#deleteForm_' + id).submit();
                        Swal.fire(
                            'Data dihapus!',
                            'Data berhasil dihapus',
                            'success'
                        )
                    }
                });
            });
        });
    </script>
</body>

</html>
