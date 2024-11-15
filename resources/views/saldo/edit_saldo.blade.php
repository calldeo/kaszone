<!DOCTYPE html>

<html lang="id">

<head>
    @include('template.headerr')
    <title>PityCash | {{auth()->user()->level}} | Edit Saldo Minimal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7fa;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: none;
        }
        .card-header {
            background: linear-gradient(45deg, #EB8153, #EB8153);
            color: white;
            border-radius: 15px 15px 0 0;
            padding: 20px;
        }
        .form-control {
            border-radius: 10px;
            border: 1px solid #e0e0e0;
            padding: 12px;
            font-family: 'Poppins', sans-serif;
        }
        .btn {
            border-radius: 10px;
            padding: 12px 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            font-family: 'Poppins', sans-serif;
        }
        .btn-primary {
            background-color: #EB8153;
            border-color: #EB8153;
        }
        .btn-primary:hover {
            background-color: #FF9A85;
            border-color: #FF9A85;
        }
        .btn-danger {
            background-color: #FF6B6B;
            border-color: #FF6B6B;
        }
        .btn-danger:hover {
            background-color: #FF8E8E;
            border-color: #FF8E8E;
        }
    </style>
</head>
<body class="bg-light">

    @include('template.topbarr')
    @include('template.sidebarr')
        
    <div class="content-body">
        <div class="container-fluid py-5">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0 text-white">Edit Saldo Minimal</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('update.minimal.saldo') }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label class="font-weight-bold">Saldo Saat Ini</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-wallet"></i></span>
                                        </div>
                                        <input type="text" class="form-control bg-light" value="Rp{{ number_format($saldo, 0, ',', '.') }}" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="font-weight-bold">Saldo Minimal</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-money-bill-wave"></i></span>
                                        </div>
                                        <input type="text" class="form-control nominal" id="nominal" name="saldo" placeholder="Masukkan jumlah saldo minimal..." required oninput="formatInputSaldo(event);" value="Rp{{ number_format($minimalSaldo, 0, ',', '.') }}">
                                        <input type="hidden" name="saldo_hidden" id="nominal_value" value="{{ $minimalSaldo }}">
                                    </div>
                                </div>
                                <div class="text-right mt-4">
                                    <button type="button" class="btn btn-danger mr-2" onclick="window.location.href='{{ route('saldo') }}'">
                                        <i class="fas fa-times mr-1"></i> Batal
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save mr-1"></i> Simpan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer mt-5">
        <div class="container">
            <div class="text-center">
                <p class="mb-0">&copy; {{ date('Y') }} SYNC. Hak Cipta Dilindungi.</p>
            </div>
        </div>
    </div>

    @include('template.scripts')
    
    <script>
    function formatInputSaldo(event) {
        const input = event.target;
        const hiddenInput = input.nextElementSibling; 
        let value = input.value.replace(/[^0-9]/g, ''); 

        if (value) {
            const formattedValue = parseInt(value).toLocaleString('id-ID'); 
            input.value = 'Rp' + formattedValue; 
            hiddenInput.value = value;
        } else {
            input.value = 'Rp0';
            hiddenInput.value = ''; 
        }
    }
    </script>
</body>
</html>