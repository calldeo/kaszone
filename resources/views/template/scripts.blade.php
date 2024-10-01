<script src="{{asset('dash/vendor/global/global.min.js')}}"></script>
	<script src="{{asset('dash/vendor/bootstrap-select/dist/js/bootstrap-select.min.js')}}"></script>
	<script src="{{asset('dash/vendor/chart.js/Chart.bundle.min.js')}}"></script>
    <script src="{{asset('dash/vendor/moment/moment.min.js')}}"></script>
    <script src="{{asset('dash/vendor/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
    <script src="{{asset('dash/js/plugins-init/bs-daterange-picker-init.js')}}"></script>
	<!-- Chart piety plugin files -->
    <script src="{{asset('dash/vendor/peity/jquery.peity.min.js')}}"></script>
	
	<!-- Apex Chart -->
	<script src="{{asset('dash/vendor/apexchart/apexchart.js')}}"></script>
	
	<!-- Dashboard 1 -->
	<script src="{{asset('dash/js/dashboard/dashboard-1.js')}}"></script>
	<script src="{{asset('dash/vendor/select2/js/select2.full.min.js')}}"></script>
    <script src="{{asset('dash/js/plugins-init/select2-init.js')}}"></script>

	<script src="{{asset('dash/vendor/owl-carousel/owl.carousel.js')}}"></script>
    <script src="{{asset('dash/js/custom.min.js')}}"></script>
	<script src="{{asset('dash/js/deznav-init.js')}}"></script>
    <script src="{{asset('dash/js/search.js')}}"></script>
    <script src="{{asset('dash/js/demo.js')}}"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> --}}

    <script src="{{asset('dash/vendor/ckeditor/ckeditor.js')}}"></script>
    <script src="{{asset('dash/vendor/datatables/js/jquery.dataTables.min.js')}}"></script>
    {{-- <script src="{{asset('dash/js/plugins-init/datatables.init.js')}}"></script> --}}
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>



    <script src="{{asset('dash/js/styleSwitcher.js')}}"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
	<script>
    function redirectToAdmin() {
        // Ganti URL dengan URL halaman admin yang diinginkan
        window.location.href = "/user";
    }
	</script>
    <script>
    function redirectToRole() {
        // Ganti URL dengan URL halaman admin yang diinginkan
        window.location.href = "/role";
    }
	</script>
    <script>
    function redirectToKategori() {
        // Ganti URL dengan URL halaman admin yang diinginkan
        window.location.href = "/kategori";
    }
	</script>
	
    <script>
    function redirectToLogin() {
        // Ganti URL dengan URL halaman admin yang diinginkan
        window.location.href = "/login";
    }
	</script>
<script>
function switchRole(role) {
    document.getElementById('roleInput').value = role;
    document.getElementById('roleSwitchForm').submit();
}
</script>

