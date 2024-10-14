// $('#orderDatatable').datatable();
// $('#datas_datatable').dataTable({

// const { before } = require("lodash");

// function order_click(){

// alert(78);


// }
$(function () {
    $('[data-toggle="tooltip"]').tooltip()
});
// iDisplayLength: 3, //show extra tr under on tbody
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
function load_all_orders(){
    $('#orderDatatable').dataTable({
        "lengthMenu": [ [10, 25, 50, 100,-1], [10, 25, 50, 100,'All'] ],
        "pageLength": 25,
        'order':[0,'DESC'],
        "ordering" : true,
        responsive: true,
        lengthChange: true,
        // dom:
        //     "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'lB>>" +
        //     "<'row'<'col-sm-12'tr>>" +
        //     "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        dom:"blftrip",
        "columnDefs": [
            {"className": "text-center", "targets": "_all"}
            ],
        buttons: [
            {
                extend: 'print',
                text: 'Print',
                titleAttr: 'Print Table',
                className: 'btn-outline-primary btn-sm'
            }
        ],

        "processing": true,
        "serverSide": true,
        "scrollX": true,
        "ajax":{
            'url' : 'fetchOrders',
            'type': "POST",
            'data' : {
                'ajax_action' : 'fetch_all_bookings'
            },
        "error": function(xhr, status, error) {
            if (xhr.status === 419) { // Laravel returns 419 for expired tokens
                alert('Session expired. Please refresh the page.');
                location.reload(); // Or request a new token and retry the request
            }else {
                console.error("An xhr occurred: " + xhr);
                console.error("An error occurred: " + error);
            }
        },
        },
    });
}
function load_all_product_review(){
    $('#reviewDatable').dataTable({
        "lengthMenu": [ [10, 25, 50, 100,-1], [10, 25, 50, 100,'All'] ],
        "pageLength": 25,
        'order':[0,'DESC'],
        "responsive": true,
        "lengthChange": true,
        // "sorting" : false,
        dom:
            `<'row mb-3'
                <'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f>
                <'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'lB>
            >" +
            "<'row'
                <'col-sm-12 add_custom_class'tr>
            >" +
            "<'row'
                <'col-sm-12 col-md-5'i>
                <'col-sm-12 col-md-7'p>
            >`,
        // dom:"blftrip",
        "columnDefs": [
            {"className": "text-center", "targets": "_all"}
            ],

        buttons: [
            {
                extend: 'print',
                text: 'Print',
                titleAttr: 'Print Table',
                className: 'btn-outline-primary btn-sm'
            }
        ],
        "processing": true,
        "serverSide": true,
        "scrollX": true,
        "ajax":{
            'url' : 'fetchProductReviews',
            'type': "POST",
            'data' : {
                // 'ajax_action' : 'fetch_all_product_review'
            }
        },
    });
}
function load_all_categories(){
    $('#categoryDatatable').dataTable({
        "lengthMenu": [ [10, 25, 50, 100,-1], [10, 25, 50, 100,'All'] ],
        "pageLength": 25,
        'order':[0,'DESC'],
        "responsive": true,
        "lengthChange": true,
        dom:
            `<'row mb-3'
                <'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f>
                <'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'lB>
            >" +
            "<'row'
                <'col-sm-12 add_custom_class'tr>
            >" +
            "<'row'
                <'col-sm-12 col-md-5'i>
                <'col-sm-12 col-md-7'p>
            >`,
        // dom:"blftrip",
        "columnDefs": [
            {"className": "text-center", "targets": "_all"}
            ],

        buttons: [
            {
                extend: 'print',
                text: 'Print',
                titleAttr: 'Print Table',
                className: 'btn-outline-primary btn-sm'
            }
        ],
        "processing": true,
        "serverSide": true,
        "scrollX": true,
        "ajax":{
            'url' : 'fetchDataByAjax',
            'type': "POST",
            'data' : {
                'ajax_action' : 'fetchAllCategories'
            }
        },
    });
}
function export_product_review(self){

    $.ajax({
        url :"export_product_review_sheet",
        type: 'POST',
        // data: form_data,
        dataType: 'JSON',
        contentType:false,
        cache:false,
        processData:false,
        beforeSend: function(){
            $(self).html('  <i class="fa fa-spinner fa-spin"></i>');//.attr('disabled',true)
        },
        complete: function(){ $(self).html(btn_name);//.attr('disabled',false);
        },
        success: function (data) {
            if(data.check=='success'){
                // fetch_all_category();
                // $('#users_datatable').DataTable().ajax.reload();
                // iziToast.success({
                //     title: 'Success',
                //     message: data.msg,
                //     onClosed: function () {
                //         // redirect('all_orders');
                //     }

                // });
            }
        }
    });
}
function initiateRazorpayPaymentGateway(self){
    var form_data = {
        "test" : "test"
    };
console.log(self);
    var btn_name = $(self).text();
    $.ajax({
        url :"checkout/initiatePayment",
        type: 'POST',
        dataType: 'JSON',
        data: form_data,
        contentType:false,
        cache:false,
        processData:false,
        beforeSend: function(){
            $(self).html('  <i class="fa fa-spinner fa-spin"></i>').attr('disabled',true);
        },
        complete: function(){
            $(self).html(btn_name).attr('disabled',false);
        },
        success: function (data) {
            console.log(data);
            if(data.check=='success'){
                // fetch_all_category();
                // $('#users_datatable').DataTable().ajax.reload();
                // iziToast.success({
                //     title: 'Success',
                //     message: data.msg,
                //     onClosed: function () {
                //         // redirect('all_orders');
                //     }

                // });
            }
        }
    });


}
