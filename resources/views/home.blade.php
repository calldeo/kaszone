<!DOCTYPE html>
<html lang="en">

<head>
    @include('template.headerr')
    <title>E-vote | Dashboard</title>
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
                        <h4>Hi, welcome back!</h4>
                        <p class="mb-0">Dashboard</p>
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                    </ol>
                </div>
            </div>

            <!-- Card untuk Menampilkan Data Keuangan -->
            <div class="row">
                <!-- Card Total Pemasukan -->
                <div class="col-lg-4 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="media">
                                <span class="mr-3"><i class="fas fa-wallet"></i></span>
                                <div class="media-body">
                                    <h5 class="mb-1">Total Pemasukan</h5>
                                    <h3 class="card-text">Rp {{ number_format($totalPemasukan, 2, ',', '.') }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Total Pengeluaran -->
                <div class="col-lg-4 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="media">
                                <span class="mr-3"><i class="fas fa-shopping-cart"></i></span>
                                <div class="media-body">
                                    <h5 class="mb-1">Total Pengeluaran</h5>
                                    <h3 class="card-text">Rp {{ number_format($totalPengeluaran, 2, ',', '.') }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Saldo -->
                <div class="col-lg-4 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="media">
                                <span class="mr-3"><i class="fas fa-money-bill-wave"></i></span>
                                <div class="media-body">
                                    <h5 class="mb-1">Saldo</h5>
                                    <h3 class="card-text">Rp {{ number_format($saldo, 2, ',', '.') }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Card Section -->

        </div>
    </div>

    <div class="footer">
        <div class="copyright">
            <p>Copyright © Designed & Developed by <a href="/home" target="_blank">SYNC</a> 2024</p>
        </div>
    </div>

    @include('template.scripts')
</body>

</html>
