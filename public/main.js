$(document).ready(function(){
    // $('#adminTable').DataTable({
    //     ordering: true,
    //     serverSide: true,  // Menunjukkan bahwa data diambil dari server
    //     processing: true,  // Menunjukkan bahwa ada proses loading data
    //     ajax: {
    //         url: $('#table-url').val(),  // Mengambil URL dari elemen input tersembunyi
    //         type: 'GET',  // Metode pengambilan data
    //         dataType: 'json',  // Jenis data yang diharapkan dari server
    //         error: function(jqXHR, textStatus, errorThrown) {  // Menangani error dari permintaan AJAX
    //             console.error('AJAX error:', textStatus, errorThrown);
    //             alert('Terjadi kesalahan saat memuat data. Silakan coba lagi nanti.');
    //         }
    //     },
    //     columns: [
    //         { data: 'DT_RowIndex', name: 'DT_RowIndex', width: '10px', orderable: false, searchable: false },
    //         { data: 'name', name: 'name' },
    //         { data: 'email', name: 'email' },
    //         { data: 'kelamin', name: 'kelamin' },
    //         { data: 'alamat', name: 'alamat' },

    //         { data: 'opsi', name: 'opsi', orderable: false, searchable: false }
    //     ],
    //     columnDefs: [
    //         // Contoh untuk menambahkan pengaturan kolom tambahan jika diperlukan
    //     ],
        
       
    // });
        $('#bendaharaTable').DataTable({
        ordering: true,
        serverSide: true,  // Menunjukkan bahwa data diambil dari server
        processing: true,  // Menunjukkan bahwa ada proses loading data
        ajax: {
            url: $('#table-url').val(),  // Mengambil URL dari elemen input tersembunyi
            type: 'GET',  // Metode pengambilan data
            dataType: 'json',  // Jenis data yang diharapkan dari server
            error: function(jqXHR, textStatus, errorThrown) {  // Menangani error dari permintaan AJAX
                console.error('AJAX error:', textStatus, errorThrown);
                alert('Terjadi kesalahan saat memuat data. Silakan coba lagi nanti.');
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', width: '50px', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'kelamin', name: 'kelamin' },
            { data: 'alamat', name: 'alamat' },
            { data: 'roles', name: 'roles' },
            { data: 'opsi', name: 'opsi', orderable: false, searchable: false }
        ],
        columnDefs: [
            // Contoh untuk menambahkan pengaturan kolom tambahan jika diperlukan
        ],
        
       
    });


    var filterData={
        start_created_at : null,
        end_created_at : null
    }
    pemasukanTable(filterData)
    $('.input-daterange-datepicker').val('');
    $('.input-daterange-datepicker').daterangepicker({
        autoUpdateInput : false,
        locale: {
            format: 'MM/DD/YYYY'
        }
    },function (start,end,label) {
            filterData.start_created_at =start.format('YYYY-MM-DD 00:00:00');
            filterData.end_created_at =end.format('YYYY-MM-DD 23:59:59');
            pemasukanTable(filterData)
        });

 
    $('.input-daterange-datepicker').on('apply.daterangepicker', function(ev, picker) {
        var startDate = picker.startDate.format('YYYY-MM-DD');
        var endDate = picker.endDate.format('YYYY-MM-DD');

        console.log('Selected date range: ' + startDate + ' to ' + endDate);

        
    });
    $('.input-daterange-datepicker').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
        filterData.start_created_at =null;
        filterData.end_created_at =null;
        pemasukanTable(filterData)

        
      
    });


    function pemasukanTable(filterData){
        tablePemasukan = $('#pemasukanTable').DataTable({
            ordering: true,
            destroy: true,
            serverSide: true,  // Menunjukkan bahwa data diambil dari server
            processing: true,  // Menunjukkan bahwa ada proses loading data
            ajax: {
                url: $('#table-url').val(),  // Mengambil URL dari elemen input tersembunyi
                type: 'GET',  // Metode pengambilan data
                dataType: 'json',  // Jenis data yang diharapkan dari server
                error: function(jqXHR, textStatus, errorThrown) {  // Menangani error dari permintaan AJAX
                    console.error('AJAX error:', textStatus, errorThrown);
                    alert('Terjadi kesalahan saat memuat data. Silakan coba lagi nanti.');
                },
                data: filterData
                
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', width: '10px', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'description', name: 'description' },
                { data: 'category', name: 'category' }, // Pastikan ini sesuai dengan addColumn di server
                { data: 'date', name: 'date' },
                { data: 'created_at', name: 'created_at' },
                { data: 'jumlah', name: 'jumlah' },
                { data: 'opsi', name: 'opsi', orderable: false, searchable: false }
            ],
           columnDefs: [
                            {
                            "targets": "_all",
                            "defaultContent": '<div className="align-middle text-center">-</div>'
                            },
        ]
               
            });
    }
     

   

    function pengeluaranTable(filterData1){
       tablePengeluaran = $('#pengeluaranTable').DataTable({
            ordering: true,
            destroy: true,
            serverSide: true,  // Menunjukkan bahwa data diambil dari server
            processing: true,  // Menunjukkan bahwa ada proses loading data
            ajax: {
                url: $('#table-url').val(),  // Mengambil URL dari elemen input tersembunyi
                type: 'GET',  // Metode pengambilan data
                dataType: 'json',  // Jenis data yang diharapkan dari server
                error: function(jqXHR, textStatus, errorThrown) {  // Menangani error dari permintaan AJAX
                    console.error('AJAX error:', textStatus, errorThrown);
                    alert('Terjadi kesalahan saat memuat data. Silakan coba lagi nanti.');
                },
                data: filterData1
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', width: '10px', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'description', name: 'description' },
                { data: 'category', name: 'category' }, // Pastikan ini sesuai dengan addColumn di server
                { data: 'tanggal', name: 'tanggal' },
                { data: 'jumlah_satuan', name: 'jumlah_satuan' },
                { data: 'nominal', name: 'nominal' },
                { data: 'dll', name: 'dll' },
                { data: 'image', name: 'image' },
                { data: 'jumlah', name: 'jumlah' },
                { data: 'created_at', name: 'created_at' },
                { data: 'opsi', name: 'opsi', orderable: false, searchable: false }
            ],
           columnDefs: [
                            {
                            "targets": "_all",
                            "defaultContent": '<div className="align-middle text-center">-</div>'
                            },
        ]
               
            });
    }
  


    $('#kategoriTable').DataTable({
        ordering: true,
        serverSide: true,  // Indicates that data is fetched from the server
        processing: true,  // Indicates that there is loading data processing
        ajax: {
            url: $('#table-url').val(),  // Fetch URL from hidden input element
            type: 'GET',  // Method to fetch data
            dataType: 'json',  // Expected data type from server
            error: function(jqXHR, textStatus, errorThrown) {  // Handle AJAX errors
                console.error('AJAX error:', textStatus, errorThrown);
                alert('Terjadi kesalahan saat memuat data. Silakan coba lagi nanti.');
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', width: '10px', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'jenis_kategori', name: 'jenis_kategori' },
            { data: 'description', name: 'description' },
            { data: 'opsi', name: 'opsi', orderable: false, searchable: false }
        ],
        columnDefs: [
            // Example for additional column settings if needed
        ],
        
        
    
    });

         $('#roleTable').DataTable({
        ordering: true,
        serverSide: true,  // Menunjukkan bahwa data diambil dari server
        processing: true,  // Menunjukkan bahwa ada proses loading data
        ajax: {
            url: $('#table-url').val(),  // Mengambil URL dari elemen input tersembunyi
            type: 'GET',  // Metode pengambilan data
            dataType: 'json',  // Jenis data yang diharapkan dari server
            error: function(jqXHR, textStatus, errorThrown) {  // Menangani error dari permintaan AJAX
                console.error('AJAX error:', textStatus, errorThrown);
                alert('Terjadi kesalahan saat memuat data. Silakan coba lagi nanti.');
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', width: '10px', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'guard_name', name: 'guard_name' },


            { data: 'opsi', name: 'opsi', orderable: false, searchable: false }
        ],
        columnDefs: [
            // Contoh untuk menambahkan pengaturan kolom tambahan jika diperlukan
        ],
        
       
    });

    
});

