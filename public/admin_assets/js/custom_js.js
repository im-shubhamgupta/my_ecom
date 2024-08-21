// $('#orderDatatable').datatable();
// $('#datas_datatable').dataTable({

// function order_click(){

// alert(78);


// }
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
function load_all_orders(){
    // alert(1);
    $('#orderDatatable').dataTable({
        "lengthMenu": [ [10, 25, 50, 100,-1], [10, 25, 50, 100,'All'] ],
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
                'headers' : {
                    // 'X-CSRF-TOKEN': csrfToken
                },
                'data' : {
                    'ajax_action' : 'fetch_all_bookings'
                }
        },
    });
}
