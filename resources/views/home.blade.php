<!DOCTYPE html>
<html lang="en">

<head>
    @include('template.headerr')
    <title>KasZone | {{auth()->user()->level}} |  Dashboard </title>
    <style>
        .card {
            border: 1px solid #e1e1e1;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
            background-color: #ffffff;
            text-align: center;
        }

        .card-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .card-value {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
        }
    </style>
</head>

<body>

    <!-- Preloader start -->
    @include('template.topbarr')
    <!-- Header end -->

    <!-- Sidebar start -->
    @include('template.sidebarr')
    <!-- Sidebar end -->

    <!-- Content body start -->
    <div class="content-body">
        <div class="container-fluid">
            <!-- Add Project -->
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <h4>Hi, welcome back!</h4>
                        <p class="mb-0">Dashboard</p>
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Table</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Dashboard</a></li>
                    </ol>
                </div>
            </div>

            <!-- Card for Total Income -->
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="card">
                        <div class="card-title">Saldo</div>
                        <div class="card-value">Rp{{ number_format($totalPemasukan, 0, ',', '.') }}</div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card">
                        <div class="card-title">Total Pemasukan</div>
                        <div class="card-value">Rp{{ number_format($totalPemasukan, 0, ',', '.') }}</div>
                    </div>
                </div>
                <!-- You can add more cards here -->
            </div>

        </div>
    </div>
    <!-- Content body end -->

    <!-- Main wrapper end -->

    <!-- Scripts -->
    <!-- Required vendors -->
    @include('template.scripts')

    <!-- Sweet Alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>


</body>

</html>
