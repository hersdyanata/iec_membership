/* ------------------------------------------------------------------------------
 *
 *  # Custom JS code
 *
 *  Place here all your custom js. Make sure it's loaded after app.js
 *
 * ---------------------------------------------------------------------------- */
function build_datatables(selector, postUrl, csrf, target) {
    var table;
    table = $('#'+selector).DataTable({
        processing: true,
        serverSide: true,
        autoWidth: false,
        pageLength: 25,
        lengthMenu: [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
        order: [],
        ajax: {
            url: postUrl,
            dataType: "JSON",
            type: "POST",
            data: {
                "_token": csrf,
            },
            beforeSend: function(){
                small_loader_open(selector);
            },
            complete: function(){
                small_loader_close(selector);
            },
        },
        columnDefs: [{
            orderable: false,
            targets: target,
        }],
        dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
        language: {
            search: '_INPUT_',
            searchPlaceholder: 'Type to filter...',
            lengthMenu: '<span>Show:</span> _MENU_',
            lengthMenu: '_MENU_',
            paginate: {
                'first': 'First',
                'last': 'Last',
                'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;',
                'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;'
            }
        },
        drawCallback: function () {
            $('.popover').popover({
                "html": true,
                trigger: 'hover',
                placement: 'right',
            });

            $('.tooltiped').tooltip({
                "html": true,
                trigger: 'hover',
                placement: 'top',
            });
        },
    });

    $('.dataTables_filter input').unbind();
    $('.dataTables_filter input').bind('keyup', function (e) {
        if (e.keyCode == 13) {
            table.search(this.value).draw();
        }
    });

    return table;
}

function build_datatables_select(selector, postUrl, csrf, target) {
    var table;
    table = $('#'+selector).DataTable({
        processing: true,
        serverSide: true,
        autoWidth: false,
        pageLength: 25,
        lengthMenu: [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
        order: [],
        ajax: {
            url: postUrl,
            dataType: "JSON",
            type: "POST",
            data: {
                "_token": csrf,
            },
            beforeSend: function(){
                small_loader_open(selector);
            },
            complete: function(){
                small_loader_close(selector);
            },
        },
        columnDefs: [{
            orderable: false,
            targets: target,
        }],
        dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
        select: {
            style: 'multi'
        },
        language: {
            search: '_INPUT_',
            searchPlaceholder: 'Type to filter...',
            lengthMenu: '<span>Show:</span> _MENU_',
            lengthMenu: '_MENU_',
            paginate: {
                'first': 'First',
                'last': 'Last',
                'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;',
                'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;'
            }
        },
        drawCallback: function () {
            $('.popover').popover({
                "html": true,
                trigger: 'hover',
                placement: 'right',
            });

            $('.tooltiped').tooltip({
                "html": true,
                trigger: 'hover',
                placement: 'top',
            });
        },
    });

    $('.dataTables_filter input').unbind();
    $('.dataTables_filter input').bind('keyup', function (e) {
        if (e.keyCode == 13) {
            table.search(this.value).draw();
        }
    });

    return table;
}

function build_datatables_with_params(selector, postUrl, csrf, target, params) {
    // console.log(params);
    var table;
    table = $('#'+selector).DataTable({
        processing: true,
        serverSide: true,
        autoWidth: false,
        pageLength: 25,
        lengthMenu: [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
        order: [],
        ajax: {
            url: postUrl,
            dataType: "JSON",
            type: "POST",
            data: {
                "_token": csrf,
                "params" : params
            },
            beforeSend: function(){
                small_loader_open(selector);
            },
            complete: function(){
                small_loader_close(selector);
            },
        },
        columnDefs: [{
            orderable: false,
            targets: target,
        }],
        dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
        language: {
            search: '_INPUT_',
            searchPlaceholder: 'Type to filter...',
            lengthMenu: '<span>Show:</span> _MENU_',
            lengthMenu: '_MENU_',
            paginate: {
                'first': 'First',
                'last': 'Last',
                'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;',
                'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;'
            }
        },
        drawCallback: function () {
            $('.popover').popover({
                "html": true,
                trigger: 'hover',
                placement: 'right',
            });

            $('.tooltiped').tooltip({
                "html": true,
                trigger: 'hover',
                placement: 'top',
            });
        },
    });

    $('.dataTables_filter input').unbind();
    $('.dataTables_filter input').bind('keyup', function (e) {
        if (e.keyCode == 13) {
            table.search(this.value).draw();
        }
    });

    return table;
}

function reload_table(src){
    src.ajax.reload();
}

function small_loader_open(selector){
    $($('#'+selector).parent()).block({
        message: '<i class="icon-spinner4 icon-2x spinner"></i>',
        overlayCSS: {
            backgroundColor: '#1B2024',
            opacity: 0.3,
            cursor: 'wait'
        },
        css: {
            border: 0,
            padding: 0,
            backgroundColor: 'none',
            color: '#fff'
        }
    });
}

function small_loader_close(selector){
    window.setTimeout(function () {
        $($('#'+selector).parent()).unblock();
    }, 1000);
}

function big_loader_open(selector){
    $($('#'+selector)).block({
        message: '<div class="pace-demo">\
                    <div class="theme_xbox">\
                        <div class="pace_progress"></div>\
                        <div class="pace_activity"></div>\
                    </div>\
                </div>\
                <p>Loading</p>',
        overlayCSS: {
            backgroundColor: '#1B2024',
            opacity: 0.73,
            cursor: 'wait'
        },
        css: {
            border: 0,
            padding: 0,
            backgroundColor: 'none',
            color: '#fff'
        }
    });
}

function big_loader_close(selector){
    window.setTimeout(function () {
        $($('#'+selector)).unblock();
    }, 1000);
}

function sw_multi_error(dt){
    var pesan = "";
    var obj = JSON.parse(dt.responseJSON['msg_body']);
    $.each(obj, function(key,value) {
        pesan += value+"<br>";
    });
    var swals = swalInit.fire({
        title: dt.responseJSON['msg_title'],
        html: pesan,
        confirmButtonClass: 'btn btn-danger',
        type: "error",
        icon: 'error'
    });

    return swals;
}

function sw_single_error(dt){
    var swals = swalInit.fire({
        title: dt.msg_title,
        text: dt.msg_body,
        confirmButtonClass: 'btn btn-danger',
        type: "error",
        icon: 'error',
    });

    return swals;
}

function sw_success(dt){
    var swals = swalInit.fire({
        title: dt.msg_title,
        text: dt.msg_body,
        type: 'success',
        icon: 'success',
        confirmButtonClass: 'btn btn-success',
    });

    return swals;
}

function sw_success_redirect(dt, url){
    var swals = swalInit.fire({
        title: dt.msg_title,
        text: dt.msg_body,
        type: 'success',
        icon: 'success',
        confirmButtonClass: 'btn btn-success',
        onClose: function() {
            window.location = url;
        }
    });

    return swals;
}

function sw_delete(postUrl, csrf_pre, identifier, deleteUrl, csrf_post, selector, datatable){
    $.ajax({
        url: postUrl,
        type: "GET",
        data: {
            _token : csrf_pre,
            id : identifier
        },
        beforeSend: function(){
            small_loader_open(selector);
        },
        success: function(s){
            swalInit.fire({
                title: s.msg_title,
                html: s.msg_body,
                type: 'warning',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Iya, tolong hapus!',
                cancelButtonText: 'Tidak, tolong batalkan!',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                allowOutsideClick: false
            }).then(function(result) {
                if(result.value) {

                    $.ajax({
                        url: deleteUrl,
                        type: "DELETE",
                        data: {
                            _token : csrf_post,
                            id : s.id
                        },
                        beforeSend: function(){
                            small_loader_open(selector);
                        },
                        success: function(d){
                            swalInit.fire({
                                title: d.msg_title,
                                html: d.msg_body,
                                type: 'success',
                                icon: 'success',
                                confirmButtonClass: 'btn btn-success',
                            });
                            reload_table(datatable);
                        },
                        complete: function(){
                            small_loader_close(selector);
                        }
                    });
                }
                else if(result.dismiss === swal.DismissReason.cancel) {
                    swalInit.fire({
                        title: 'Dibatalkan',
                        html: 'Data Anda aman 😉',
                        type: 'success',
                        icon: 'success',
                        confirmButtonClass: 'btn btn-success',
                        allowOutsideClick: false
                    });
                    small_loader_close(selector);
                }
            });
        },
        complete: function(){
            small_loader_close('section_divider');
        }
    });
}

function sw_delete_validated(postUrl, csrf_pre, identifier, deleteUrl, csrf_post, selector, datatable){
    $.ajax({
        url: postUrl,
        type: "GET",
        data: {
            _token : csrf_pre,
            id : identifier
        },
        beforeSend: function(){
            small_loader_open(selector);
        },
        success: function(s){
            if(s.permission === 'F'){
                swalInit.fire({
                    title: s.msg_title,
                    html: s.msg_body,
                    type: 'error',
                    icon: 'error',
                    confirmButtonClass: 'btn btn-danger',
                    allowOutsideClick: false
                });
                small_loader_close(selector);
            }else{
                swalInit.fire({
                    title: s.msg_title,
                    html: s.msg_body,
                    type: 'warning',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Iya, tolong hapus!',
                    cancelButtonText: 'Tidak, tolong batalkan!',
                    confirmButtonClass: 'btn btn-success',
                    cancelButtonClass: 'btn btn-danger',
                    buttonsStyling: false,
                    allowOutsideClick: false
                }).then(function(result) {
                    if(result.value) {
    
                        $.ajax({
                            url: deleteUrl,
                            type: "DELETE",
                            data: {
                                _token : csrf_post,
                                id : s.id
                            },
                            beforeSend: function(){
                                small_loader_open(selector);
                            },
                            success: function(d){
                                swalInit.fire({
                                    title: d.msg_title,
                                    html: d.msg_body,
                                    type: 'success',
                                    icon: 'success',
                                    confirmButtonClass: 'btn btn-success',
                                });
                                reload_table(datatable);
                            },
                            complete: function(){
                                small_loader_close(selector);
                            }
                        });
                    }
                    else if(result.dismiss === swal.DismissReason.cancel) {
                        swalInit.fire({
                            title: 'Dibatalkan',
                            html: 'Data Anda aman 😉',
                            type: 'success',
                            icon: 'success',
                            confirmButtonClass: 'btn btn-success',
                            allowOutsideClick: false
                        });
                        small_loader_close(selector);
                    }
                });
            }
        },
        complete: function(){
            small_loader_close('section_divider');
        }
    });
}

function sw_delete_validated_multiple_params(postUrl, csrf_pre, params, deleteUrl, csrf_post, selector, datatable){
    // console.log(params);
    $.ajax({
        url: postUrl,
        type: "POST",
        data: {
            _token : csrf_pre,
            param : params
        },
        beforeSend: function(){
            small_loader_open(selector);
        },
        success: function(s){
            if(s.permission === 'F'){
                swalInit.fire({
                    title: s.msg_title,
                    html: s.msg_body,
                    type: 'error',
                    icon: 'error',
                    confirmButtonClass: 'btn btn-danger',
                    allowOutsideClick: false
                });
                small_loader_close(selector);
            }else{
                swalInit.fire({
                    title: s.msg_title,
                    html: s.msg_body,
                    type: 'warning',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Iya, tolong hapus!',
                    cancelButtonText: 'Tidak, tolong batalkan!',
                    confirmButtonClass: 'btn btn-success',
                    cancelButtonClass: 'btn btn-danger',
                    buttonsStyling: false,
                    allowOutsideClick: false
                }).then(function(result) {
                    if(result.value) {
    
                        $.ajax({
                            url: deleteUrl,
                            type: "POST",
                            data: {
                                _token : csrf_post,
                                // id : s.id
                                param : params
                            },
                            beforeSend: function(){
                                small_loader_open(selector);
                            },
                            success: function(d){
                                swalInit.fire({
                                    title: d.msg_title,
                                    html: d.msg_body,
                                    type: 'success',
                                    icon: 'success',
                                    confirmButtonClass: 'btn btn-success',
                                });
                                reload_table(datatable);
                            },
                            complete: function(){
                                small_loader_close(selector);
                            }
                        });
                    }
                    else if(result.dismiss === swal.DismissReason.cancel) {
                        swalInit.fire({
                            title: 'Dibatalkan',
                            html: 'Data Anda aman 😉',
                            type: 'success',
                            icon: 'success',
                            confirmButtonClass: 'btn btn-success',
                            allowOutsideClick: false
                        });
                        small_loader_close(selector);
                    }
                });
            }
        },
        complete: function(){
            small_loader_close('section_divider');
        }
    });
}

function sidebar_collapsed(){
    $('.sidebar').addClass('sidebar-main-resized');
}