<!DOCTYPE html>

<html lang="en">

<head>
    @include('template.headerr')
    <title>PityCash | {{auth()->user()->level}} | Edit Saldo</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>

    @include('template.topbarr')
    @include('template.sidebarr')
        
    <div class="content-body">
        <div class="container-fluid">
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Form</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Edit Saldo</a></li>
                    </ol>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Edit Saldo</h4>
                        </div>
                        <div class="card-body">
                            <div class="basic-form">
                                <form action="{{ route('update.minimal.saldo') }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group">
                                        <label class="text-label">Saldo Saat Ini</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp</span>
                                            </div>
                                            <input type="text" class="form-control" value="Rp {{ number_format($saldo, 0, ',', '.') }}" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="text-label">Minimal Saldo</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp</span>
                                            </div>
                                            <input type="text" class="form-control nominal" id="nominal" name="saldo" placeholder="Enter amount.." required oninput="formatInputSaldo(event);" value="{{ 'Rp ' . number_format($minimalSaldo, 0, ',', '.') }}">
                                            <input type="hidden" name="saldo_hidden" id="nominal_value" value="{{ $minimalSaldo }}">
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-danger btn-cancel" onclick="window.location.href='{{ route('saldo') }}'">Batal</button>
                                    <button type="submit" class="btn mr-2 btn-primary btn-submit">Submit</button>
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
    
    <script>
    function formatInputSaldo(event) {
        const input = event.target;
        const hiddenInput = input.nextElementSibling; 
        let value = input.value.replace(/[^0-9]/g, ''); 

        if (value) {
            const formattedValue = parseInt(value).toLocaleString('id-ID'); 
            input.value = 'Rp ' + formattedValue; 
            hiddenInput.value = value;
        } else {
            input.value = 'Rp 0';
            hiddenInput.value = ''; 
        }
    }
    </script>
</body>
</html>