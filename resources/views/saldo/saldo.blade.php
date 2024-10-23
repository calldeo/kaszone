<!DOCTYPE html>
<html lang="id">

<head>
    @include('template.headerr')
    <title>PityCash | {{auth()->user()->level}} | Saldo</title>
    <style>
        .card {
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
        }
        .table-responsive {
            border-radius: 10px;
            overflow: hidden;
        }
        .table {
            margin-bottom: 0;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .btn-sm {
            border-radius: 20px;
        }
    </style>
</head>

<body class="bg-light">

    @include('template.topbarr')
    @include('template.sidebarr')

    <div class="content-body">
        <div class="container-fluid py-5">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h4 class="card-title mb-0 text-white">Informasi Saldo</h4>
                        </div>
                        <div class="card-body">
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    {{ session('success') }}
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                </div>
                            @endif
                            @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    {{ session('error') }}
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                </div>
                            @endif
                            @if(session('update_success'))
                                <div class="alert alert-warning alert-dismissible fade show">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    {{ session('update_success') }}
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                </div>
                            @endif
                            
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Saldo Saat Ini</th>
                                            <th>Minimal Saldo</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="font-weight-bold text-success">Rp{{ number_format($saldo, 0, ',', '.') }}</td>
                                            <td class="font-weight-bold text-danger">Rp{{ number_format($minimalSaldo, 0, ',', '.') }}</td>
                                            <td>
                                                <a href="{{ route('edit.minimal.saldo') }}" class="btn btn-primary btn-sm">
                                                    <i class="fa fa-edit"></i> Edit
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer mt-auto py-3 bg-white">
        <div class="container text-center">
            <span class="text-muted">Copyright © Designed &amp; Developed by <a href="/home" target="_blank">SYNC</a> 2024</span>
        </div>
    </footer>

    @include('template.scripts')

</body>

</html>