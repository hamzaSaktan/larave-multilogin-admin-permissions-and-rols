(function () {
    "use strict";

    $('body').on('click','.action-edit , .action-create',function(){
        let me = $(this),
            // id = me.data('id'),
            route = me.data('route'),
            modal = $('.modal'),
            modal_title = modal.find('.modal-title'),
            modal_body = modal.find('.modal-body');

        $.ajax({
            url : route,
            method : 'get',
            success : function (data) {
                modal_body.html(data);
                modal_body.find('#select2 .select2-class').select2();
                modal_body.find('.select2').css('width','100%');
                modal.modal();
            }
        });

    });

    $('body').on('click','.action-delete',function(){
        var me = $(this);

        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this imaginary file!",
            type: "warning",
            showCancelButton: true,
            buttonsStyling: true,
            allowEnterKey: false,
            allowEscapeKey: false,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#ffffff',
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel plx!",
            closeOnConfirm: false,
            closeOnCancel: false
        }, function(isConfirm) {
            if (isConfirm) {
                me.next('.form-ajax').submit();
            } else {
                swal("Cancelled", "Your imaginary file is safe :)", "error");
            }
        });

    });

    $('body').on('submit','.form-ajax',function(e){
        let me = $(this),
            action = me.attr('action'),
            method = me.attr('method'),
            data = me.serialize();

        $.ajax({
            url : action,
            method : method,
            dataType : 'json',
            beforeSend : function(){
                $('.validation-error').remove();
            },
            data : data,
            success : function(data){
                console.log(data);

                if(typeof table != 'undefined'){
                    table.ajax.reload();
                }

                $('.modal').modal('hide');

                    if('response' in data){
                        swal(data.response.title, data.response.message, data.response.status);
                    }
            },
            error : function(xhr){
                if (xhr.status === 422){
                    $.each(xhr.responseJSON.errors, function(key, errors){
                        $('#'+key).after('<span class="text text-danger validation-error">'+errors[0]+'</span>');
                    });
                }
            }
        });
        e.preventDefault();
    });

    // empty the modal after hide event...
    // $('body').on('hide.bs.modal','.modal', function () {
    //     let me = $(this),
    //         modal_title = me.find('.modal-title'),
    //         modal_body = me.find('.modal-body');
    //         modal_body.html('');
    //         modal_title.html('');
    // });

})();
