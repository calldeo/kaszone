$(document).ready(function(){
    $('#adminTable').DataTable({
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
            { data: 'email', name: 'email' },
            { data: 'level', name: 'level' },
            { data: 'kelamin', name: 'kelamin' },
            { data: 'alamat', name: 'alamat' },

            { data: 'opsi', name: 'opsi', orderable: false, searchable: false }
        ],
        columnDefs: [
            // Contoh untuk menambahkan pengaturan kolom tambahan jika diperlukan
        ],
        
       
    });
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
            { data: 'DT_RowIndex', name: 'DT_RowIndex', width: '10px', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'level', name: 'level' },
            { data: 'kelamin', name: 'kelamin' },
            { data: 'alamat', name: 'alamat' },

            { data: 'opsi', name: 'opsi', orderable: false, searchable: false }
        ],
        columnDefs: [
            // Contoh untuk menambahkan pengaturan kolom tambahan jika diperlukan
        ],
        
       
    });
     $('#pemasukanTable').DataTable({
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
        { data: 'description', name: 'description' },
        { data: 'category', name: 'category' }, // Pastikan ini sesuai dengan addColumn di server
        { data: 'date', name: 'date' },
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

    $('#pengeluaranTable').DataTable({
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
        { data: 'description', name: 'description' },
        { data: 'category', name: 'category' }, // Pastikan ini sesuai dengan addColumn di server
        { data: 'date', name: 'date' },
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
            { data: 'description', name: 'description' },
            { data: 'opsi', name: 'opsi', orderable: false, searchable: false }
        ],
        columnDefs: [
            // Example for additional column settings if needed
        ],
        
        
    
    });

        

    
});
