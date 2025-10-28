@extends('layouts.admin.app')

@section('title', 'Admin Dashboard')

@section('content')
<main class="app-main">
    <!--begin::App Content Header-->
    <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Orders</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Orders</li>
                    </ol>
                </div>
            </div>
            <!--end::Row-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::App Content Header-->
    <!--begin::App Content-->
    <div class="app-content">
        <!--begin::Container-->
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                </div>

                <div class="card">
                    <!-- /.card-header -->
                    <div class="card-body table-responsive">
                        <div class="d-flex justify-content-end mb-3">
                            <div class="">
                                <a href="{{ route('admin.neworder') }}" class="btn btn-primary btn-add-menu text-right">Add New Order</a>
                            </div>
                        </div>

                    </div>
                    <div class="row align-items-end mb-3">
                        <!-- Filter Type -->
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Select Filter</label>
                            <select id="filterType" class="form-select">
                                <option value="today" selected>Today</option>
                                <option value="week">This Week</option>
                                <option value="month">This Month</option>
                                <option value="custom">Custom Range</option>
                            </select>
                        </div>

                        <!-- From Date -->
                        <div class="col-md-3 custom-range d-none">
                            <label class="form-label fw-bold">From Date</label>
                            <input type="date" id="fromDate" class="form-control">
                        </div>

                        <!-- To Date -->
                        <div class="col-md-3 custom-range d-none">
                            <label class="form-label fw-bold">To Date</label>
                            <input type="date" id="toDate" class="form-control">
                        </div>

                        <!-- Apply Button -->
                        <div class="col-md-2 d-flex align-items-end">
                            <button id="applyFilter" class="btn btn-primary w-100">
                                Apply
                            </button>
                        </div>

                    </div>
                    <table id="dt_daywisemenu" class="table table-striped text-nowrap">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Ordered On</th>
                                <th>Customer</th>
                                <th>Address</th>
                                <th>Menu Type</th>
                                <th>Item Name</th>
                                <th>Total price</th>
                                <th>Status</th>
                                <th>Order Created Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
    </div>
    <!--end::Container-->
    </div>
    <div class="modal fade" id="model_add_edit_order" role="dialog" aria-labelledby="model_add_edit_order">
        <div class="modal-dialog modal-dialog-centered model_add_edit_order" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title "><span class="model_add_edit_order_title"></span> Order</h5>
                    <button class="btn-close py-0" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form_add_edit_menu" method="post" class="theme-form needs-validation" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="hid_orderid" id="hid_orderid">
                        <select name="drp_status" id="drp_status" class="form-select">
                            <option value="">Select status</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="cancelled">Cancelled</option>
                            <option value="dispatched">Dispatched</option>
                            <option value="delivered">Delivered</option>
                        </select>
                        <div class="form-group">
                            <label class="col-form-label" for="txt_note">Note</label>
                            <textarea class="form-control me-2" id="txt_note" name="txt_note"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary btn_submit_add_edit_user" type="submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--end::App Content-->
</main>
<script src="{{asset('admin-assets/validation/orderval.js')}}"></script>
<script>
    $(document).ready(function() {

        var table = $("#dt_daywisemenu").DataTable({
            ajax: {
                url: '{{route("admin.get-order-list")}}', // Replace with your server endpoint
                type: 'GET', // or 'POST', depending on your server setup
                data: function(d) {
                    let filterType = $('#filterType').val();

                    if (filterType === 'today') {
                        d.filter = 'today';
                    } else if (filterType === 'week') {
                        d.filter = 'week';
                    } else if (filterType === 'month') {
                        d.filter = 'month';
                    } else if (filterType === 'custom') {
                        d.filter = 'custom';
                        d.fromDate = $('#fromDate').val();
                        d.toDate = $('#toDate').val();
                    }
                },
            },
            order: [],
            dom: 'Bfrtip', // Add buttons on top
            buttons: [{
                    extend: 'excelHtml5',
                    title: 'Orders Report',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8] // 👈 exclude specific columns
                    },
                    customize: function(xlsx) {
                        let sheet = xlsx.xl.worksheets['sheet1.xml'];

                        // Count how many rows exist (to place TOTAL at bottom)
                        let lastRow = $('row', sheet).length;

                        // Loop through each row to sum the total price (7th column = "G")
                        let total = 0;

                        $('row', sheet).each(function(index) {
                            if (index === 0) return; // skip header
                            let cell = $('c[r^="G"] v', this).text(); // 👈 Column G (adjust if needed)
                            let value = parseFloat(cell) || 0;
                            total += value;
                        });

                        // Append a new row with TOTAL
                        let totalRow =
                            `<row r="${lastRow + 1}">
                            <c t="inlineStr" r="F${lastRow + 1}">
                                <is><t style="font-weight:bold">TOTAL</t></is>
                            </c>
                            <c t="n" r="G${lastRow + 1}">
                                <v>${total.toFixed(2)}</v>
                            </c>
                        </row>`;

                        // Add TOTAL row before closing </sheetData>
                        sheet.childNodes[0].childNodes[1].innerHTML += totalRow;
                    }
                },
                {
                    extend: 'pdfHtml5',
                    title: 'Orders Report',
                    orientation: 'landscape',
                    pageSize: 'A4',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8] // 👈 exclude specific columns
                    },
                    customize: function(doc) {
                        doc.styles.tableHeader.alignment = 'left';

                        let tableBody = doc.content[1].table.body;

                        // Calculate total sum (your total_amount column is at index 6 in DataTable, 
                        // but in export it's index 6 - adjust if needed)
                        let total = 0;
                        tableBody.forEach(function(row, index) {
                            if (index > 0) { // skip header
                                let price = row[6].text || row[6]; // 👈 index of "total_amount"
                                price = parseFloat(String(price).replace(/[^\d.-]/g, '')) || 0;
                                total += price;
                            }
                        });

                        // Build footer row (must match exactly 9 columns!)
                        let footerRow = [];
                        for (let i = 0; i < 9; i++) {
                            if (i === 0) {
                                footerRow.push({
                                    text: 'TOTAL',
                                    colSpan: 6,
                                    alignment: 'right',
                                    bold: true
                                });
                            } else if (i > 0 && i < 6) {
                                footerRow.push({}); // empty cells for colSpan
                            } else if (i === 6) {
                                footerRow.push({
                                    text: total.toFixed(2), // show total under "Total price"
                                    bold: true
                                });
                            } else {
                                footerRow.push({}); // keep alignment for rest columns
                            }
                        }

                        tableBody.push(footerRow);
                    }


                }
            ],
            columns: [{
                    data: 'id',
                },
                {
                    data: 'order_date',
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        return `
                        <div>${data?.customer?.name}</div>
                        <strong>Mo.${data?.customer?.phone}</strong>
                        `;
                    },
                },

                {
                    data: 'address',
                },
                {
                    data: 'order_type',
                },
                {
                    data: 'items',
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        return `
                        <div>$${data?.total_amount}</div>
                        `;
                    },
                },
                {
                    data: 'status_badge',
                },
                {
                    data: 'created_at',
                },
                {
                    data: null,
                    className: 'noExport',
                    render: function(data, type, row) {
                        return `
                        <a  class="text-success m-2 btn-menu-edit"><i class="fa fa-edit"></i></a>
                        `;
                        // <a  class="text-danger btn-menu-delete m-2"><i class="fa fa-trash"></i></a>

                    },
                    orderable: false,
                    searchable: false
                }
            ],
            // columnDefs: [{
            //     targets: 7, // Index of the column you want to hide
            //     visible: false,
            //     render: function(data, type, row) {
            //         return new Date(data); // Format date as needed
            //     }
            // }]
        });
        $('#filterType').on('change', function() {
            if ($(this).val() === 'custom') {
                $('.custom-range').removeClass('d-none');
            } else {
                $('.custom-range').addClass('d-none');
            }
        });

        // Apply filter
        $('#applyFilter').on('click', function() {
            table.ajax.reload();
        });
        $(".btn-add-menu").click(function() {
            $('#form_add_edit_menu').validate().resetForm();
            $('#form_add_edit_menu')[0].reset();
            $('#model_add_edit_order').modal('toggle');
            $(".model_add_edit_order_title").text('Add')
            $(".btn_submit_add_edit_menu").text('Add')
            $(".btn_submit_add_edit_user").text('Add')

        })

        $('#dt_daywisemenu').on('click', '.btn-menu-edit', function(e) {
            e.preventDefault();
            $('#form_add_edit_menu').validate().resetForm();
            $('#form_add_edit_menu')[0].reset();
            $(".model_add_edit_order_title").text('Edit')
            $(".btn_submit_add_edit_user").text('Edit')
            // Get the row data
            const row = $(this).closest('tr');
            const rowData = table.row(row).data();
            // // Populate the modal with row data
            $("#hid_orderid").val(rowData.id)
            $('#drp_status').val(rowData.status.toLowerCase());
            $('#txt_note').val(rowData.note);
            $('#model_add_edit_order').modal('toggle');
        });
        $('#dt_daywisemenu').on('click', '.btn-menu-delete', function(e) {
            const row = $(this).closest('tr');
            const rowData = table.row(row).data();
            let confirmation = confirm("Are you sure want to delete this menu ?");
            if (confirmation) {
                $.ajax({
                    url: "{{ route('admin.delete-daywisemenu') }}", // Change this to your server endpoint
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    data: {
                        user_uid: rowData.id,
                        image_path: rowData.image_path
                    },
                    beforeSend: function() {
                        $(".loader-wrapper").css("display", "flex")
                    },
                    success: function(response) {
                        // Handle success response
                        if (response.success) {
                            toastSuccess(response.message);
                            table.ajax.reload();
                        } else {
                            toastFail(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle error response
                        // var errors = xhr.responseJSON.errors;
                        toastFail(error)
                    },
                    complete: function() {
                        $(".loader-wrapper").css("display", "none")
                    },
                });
            }

        });

        $('#form_add_edit_menu').validate({
            rules: validationRules.validationForm.rules,
            messages: validationRules.validationForm.messages,
            submitHandler: function(form, event) {
                const formData = new FormData(form);

                // event.preventDefault();
                $.ajax({
                    url: "{{ route('admin.add-update-order') }}", // Change this to your server endpoint
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $(".loader-wrapper").css("display", "flex")

                    },
                    success: function(response) {
                        // Handle success response
                        if (response.success) {
                            toastSuccess(response.message);
                            $('#model_add_edit_order').modal('toggle');
                            table.ajax.reload();

                        } else {
                            toastFail((response.message) ? response.message : "Something went wrong. Please try again later.");
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle error response
                        var errors = xhr.responseJSON.errors;
                        toastFail(errors)
                    },
                    complete: function() {
                        $(".loader-wrapper").css("display", "none")
                    },
                });

            }
        });
    });
</script>
@endsection