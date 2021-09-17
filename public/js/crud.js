$('body').on('click', '.modal-show', function (e) {
    e.preventDefault();

    var me = $(this),
        url = me.attr('href'),
        title = me.attr('title');
    
    $('.btn-submit').text(me.hasClass('edit') ? 'Edit' : 'Simpan');
    $('#modal-title').text(title);

    $.ajax({
        url: url,
        dataType: 'html',
        success: function (res) {
            $('.modal-body').html(res);
        },
        error: function () {
            $('.modal-body').html('Gagal memuat');
        }
    })

    $('#modal').modal('show');
})

$('.btn-submit').click(function (e) {
    e.preventDefault();

    var form = $('.modal-body form'),
        url = form.attr('action'),
        method = $('input[name=_method').val() == undefined ? 'POST' : 'PUT';
    
    var msg = method == 'POST' ? 'Data berhasil disimpan' : 'Data berhasil diubah';

    form.find('.is-invalid').removeClass('is-invalid');
    form.find('.invalid-feedback').remove();

    $.ajax({
        url: url,
        method: method,
        data: form.serialize(),
        success: function () {
            form.trigger('reset');
            $('#modal').modal('hide');
            
            $('#table').DataTable().ajax.reload();
            Swal.fire({
                title: 'Sukses',
                text: msg,
                type: 'success',
                confirmButtonClass: 'btn btn-confirm mt-2'
            })
        },
        error: function (xhr) {
            var res = xhr.responseJSON;

            if ($.isEmptyObject(res) === false) {
                $.each(res.errors, function (key, val) {
                    $('#' + key)
                        .closest('.form-control')
                        .addClass('is-invalid')
                        .closest('.form-group')
                        .append('<div class="invalid-feedback">' + val + '</div>');
                    
                    if (key == 'status_persetujuan') {
                        $('#' + key).append('<div class="invalid-feedback" style="display:block">' + val + '</div>')
                        console.log(key)
                    }
                })
            }
        }
    })
})