$(document).ready(function(){
    $.ajax({
        url: "data/notifikasi-hrd",
        type: "get",
        dataType: "json",
        success: function (data) {
            if ($.isEmptyObject(data) === false) {
                var count = data.length;
                if (count <= 3) {
                    $('#jumlah-notifikasi-hrd').text(count);
                } else {
                    $('#jumlah-notifikasi-hrd').text('3+');
                }

                for (var i in data) {
                    $('#notifikasi-hrd').append('<a class="dropdown-item d-flex align-items-center" href="pengajuan">' +
                        '<div class="mr-3">' +
                        '<div class="icon-circle bg-primary">' +
                        '<i class="fas fa-file-alt text-white"></i>' +
                        '</div>' +
                        '</div>' +
                        '<div>' +
                        '<div class="small text-gray-500">'+data[i].name+'</div>' +
                        '<span class="font-weight-bold">Mengajukan cuti selama '+data[i].jumlah_cuti+' hari!</span>' +
                        '</div>'+
                        '</a>')
                }
            } else {
                $('#jumlah-notifikasi-hrd').remove();
                $('.dropdown-notifikasi-hrd').remove();
            }
        }
    })
    $.ajax({
        url: "data/notifikasi-karyawan",
        type: "get",
        dataType: "json",
        success: function (data) {
            if ($.isEmptyObject(data) === false) {
                var count = data.length,
                    end = data[0].tanggal_akhir,
                    now = new Date(),
                    nowDate = now.getFullYear() + '-' + now.getMonth() + '-' + now.getDate();
                
                if (nowDate < end) {
                    $('#jumlah-notifikasi-karyawan').remove();
                    $('.dropdown-notifikasi-karyawan').remove();
                }
                
                $('#jumlah-notifikasi-karyawan').text(count);

                for (var i in data) {
                    var status = data[i].status_persetujuan == 1 ? 'disetujui' : 'ditolak';

                    $('#notifikasi-karyawan').append('<a class="dropdown-item d-flex align-items-center" href="pengajuan">' +
                        '<div class="mr-3">' +
                        '<div class="icon-circle bg-primary">' +
                        '<i class="fas fa-file-alt text-white"></i>' +
                        '</div>' +
                        '</div>' +
                        '<div>' +
                        '<div class="small text-gray-500">'+status+'</div>' +
                        '<span class="font-weight-bold">Pengajuan cuti tanggal '+data[i].tanggal_awal+' sampai '+data[i].tanggal_akhir+' telah '+status+'</span>' +
                        '</div>'+
                        '</a>')
                }
            } else {
                $('#jumlah-notifikasi-karyawan').remove();
                $('.dropdown-notifikasi-karyawan').remove();
            }
        }
    })
})