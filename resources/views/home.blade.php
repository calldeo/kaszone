<!DOCTYPE html>
<html lang="en">

<head>
    @include('template.headerr')
    <title>PityCash | Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
    <style>
        .card {
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s ease-in-out;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .card-body {
            padding: 1.5rem;
        }
        .icon-circle {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-right: 15px;
        }
        .pemasukan-icon {
            background-color: rgba(54, 162, 235, 0.2);
            color: #36A2EB;
        }
        .pengeluaran-icon {
            background-color: rgba(255, 99, 132, 0.2);
            color: #FF6384;
        }
        .saldo-icon {
            background-color: rgba(75, 192, 192, 0.2);
            color: #4BC0C0;
        }
        .welcome-text {
            background: linear-gradient(45deg, #101010, #060606);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: bold;
        }
    </style>
</head>

<body>
    @include('template.topbarr')
    @include('template.sidebarr')

    <div class="content-body" style="margin-top: -60px;">
        <div class="container-fluid">
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <h4>Selamat Datang Kembali!</h4>
                        <p class="mb-0">Dashboard Keuangan Anda</p>
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="icon-circle pemasukan-icon">
                                    <i class="fas fa-wallet"></i>
                                </div>
                                <div>
                                    <h5 class="mb-1">Total Pemasukan</h5>
                                    <h3 class="card-text">Rp {{ number_format($totalPemasukan, 2, ',', '.') }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="icon-circle pengeluaran-icon">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <div>
                                    <h5 class="mb-1">Total Pengeluaran</h5>
                                    <h3 class="card-text">Rp {{ number_format($totalPengeluaran, 2, ',', '.') }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="icon-circle saldo-icon">
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>
                                <div>
                                    <h5 class="mb-1">Saldo</h5>
                                    <h3 class="card-text">Rp {{ number_format($saldo, 2, ',', '.') }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Grafik Pemasukan & Pengeluaran</h5>
                            <div class="mb-3">
                                <select id="yearSelect" class="form-control">
                                </select>
                            </div>
                            <canvas id="financialChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="footer">
        <div class="copyright">
            <p>Copyright © Designed & Developed by <a href="/home" target="_blank">SYNC</a> 2024</p>
        </div>
    </div>

    @include('template.scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>

   <script>
    let financialChart;

    $(function() {
        const currentYear = new Date().getFullYear();
        const select = document.getElementById('yearSelect');
        for (let year = currentYear; year >= 2020; year--) {
            let option = document.createElement('option');
            option.value = year;
            option.textContent = year;
            select.appendChild(option);
        }

        $('#yearSelect').change(function() {
            updateChart($(this).val());
        });

        updateChart(currentYear);
    });

    function updateChart(year) {
        $.ajax({
            url: '{{ route("get.financial.data.yearly") }}',
            method: 'GET',
            data: { year: year },
            success: function(response) {
                if (financialChart) {
                    financialChart.destroy();
                }

                const ctx = document.getElementById('financialChart').getContext('2d');
                financialChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                        datasets: [
                            {
                                label: 'Pemasukan (Rp)',
                                data: response.pemasukanBulanan,
                                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1
                            },
                            {
                                label: 'Pengeluaran (Rp)',
                                data: response.pengeluaranBulanan,
                                backgroundColor: 'rgba(255, 99, 132, 0.6)',
                                borderColor: 'rgba(255, 99, 132, 1)',
                                borderWidth: 1
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return 'Rp ' + value.toLocaleString('id-ID');
                                    }
                                }
                            }
                        },
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        if (context.parsed.y !== null) {
                                            label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(context.parsed.y);
                                        }
                                        return label;
                                    }
                                }
                            }
                        }
                    }
                });
            },
            error: function(error) {
                console.error('Error fetching data:', error);
            }
        });
    }
</script>


</body>

</html>