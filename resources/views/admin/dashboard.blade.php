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
                    <h3 class="mb-0">Dashboard (Active Subscription-<span id="activeSubscriptionsCount"></span>)</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
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
            <!--begin::Row-->
            <div class="row">
                <!--begin::Col-->
                <div class="col-lg-3 col-6">
                    <!--begin::Small Box Widget 3-->
                    <div class="small-box text-bg-warning">
                        <div class="inner">
                            <h3 id="todayOrdersCount">0</h3>
                            <p>Today`s Orders</p>
                        </div>
                        <svg class="small-box-icon" xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-cart" viewBox="0 0 16 16">
                            <path d="M0 1.5A.5.5 0 0 1 .5 1h1a.5.5 0 0 1 .485.379L2.89 5H14.5a.5.5 0 0 1 .49.598l-1.5 7A.5.5 0 0 1 13 13H4a.5.5 0 0 1-.49-.402L1.01 2H.5a.5.5 0 0 1-.5-.5zM5 14a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm7 1a1 1 0 1 1-2 0 1 1 0 0 1 2 0z" />
                        </svg>

                        <a
                            href="#"
                            class="small-box-footer link-dark link-underline-opacity-0 link-underline-opacity-50-hover">
                            More info <i class="bi bi-link-45deg"></i>
                        </a>
                    </div>
                    <!--end::Small Box Widget 3-->
                </div>

                <div class="col-lg-3 col-6">
                    <!--begin::Small Box Widget 1-->
                    <div class="small-box text-bg-warning">
                        <div class="inner">
                            <h3 id="pendingOrdersCount">0</h3>
                            <p>Pending Orders</p>
                        </div>
                        <svg class="small-box-icon" xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-hourglass-split" viewBox="0 0 16 16">
                            <path d="M2 1.5a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1H13v2c0 .383-.144.735-.38 1.002a2 2 0 0 1-.377.338 2 2 0 0 1 .377.338c.236.267.38.619.38 1.002v2c0 .383-.144.735-.38 1.002a2 2 0 0 1-.377.338 2 2 0 0 1 .377.338c.236.267.38.619.38 1.002v2h.5a.5.5 0 0 1 0 1h-11a.5.5 0 0 1 0-1H3v-2c0-.383.144-.735.38-1.002a2 2 0 0 1 .377-.338 2 2 0 0 1-.377-.338A1.494 1.494 0 0 1 3 9.5v-2c0-.383.144-.735.38-1.002a2 2 0 0 1 .377-.338 2 2 0 0 1-.377-.338A1.494 1.494 0 0 1 3 3.5v-2h-.5a.5.5 0 0 1-.5-.5zM4 3.5v1.379a1 1 0 0 0 .276.688A4.02 4.02 0 0 0 8 7c1.081 0 2.067-.418 2.724-1.106A1 1 0 0 0 11 4.879V3.5H4zm0 9v-1.379a1 1 0 0 1 .276-.688A4.02 4.02 0 0 1 8 9c1.081 0 2.067.418 2.724 1.106A1 1 0 0 1 11 11.621V13h-7z" />
                        </svg>

                        <a
                            href="#"
                            class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                            More info <i class="bi bi-link-45deg"></i>
                        </a>
                    </div>
                    <!--end::Small Box Widget 1-->
                </div>


                <div class="col-lg-3 col-6">
                    <!--begin::Small Box Widget 3-->
                    <div class="small-box" style="background-color: #f4c430;">
                        <div class="inner">
                            <h3 id="todayRevenueCount">0</h3>
                            <p>Today`s Revenue (Delivered)</p>
                        </div>
                        <svg class="small-box-icon" xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-currency-rupee" viewBox="0 0 16 16">
                            <path d="M8.5 1a.5.5 0 0 1 .5.5V2H10a.5.5 0 0 1 0 1H9v1h1a.5.5 0 0 1 0 1H9a3 3 0 0 1-2.98 2.65l3.546 4.129a.5.5 0 1 1-.752.658l-4.286-5A.5.5 0 0 1 5 7h1a2 2 0 0 0 0-4H5a.5.5 0 0 1 0-1h3V1.5a.5.5 0 0 1 .5-.5z" />
                        </svg>


                        <a
                            href="#"
                            class="small-box-footer link-dark link-underline-opacity-0 link-underline-opacity-50-hover">
                            More info <i class="bi bi-link-45deg"></i>
                        </a>
                    </div>
                    <!--end::Small Box Widget 3-->
                </div>
                <!--end::Col-->
                <div class="col-lg-3 col-6">
                    <!--begin::Small Box Widget 4-->
                    <div class="small-box text-bg-warning">
                        <div class="inner">
                            <h3 id="weekluRevenueCount">0</h3>
                            <p>Weekly Revenue (Delivered)</p>
                        </div>
                        <svg class="small-box-icon" xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-person-badge" viewBox="0 0 16 16">
                            <path d="M6.5 2a.5.5 0 0 0 0 1H7v1h2V3h.5a.5.5 0 0 0 0-1h-3zM1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H1zm6-3a3 3 0 0 0-2.995 2.824A.5.5 0 0 0 4.5 14h5a.5.5 0 0 0 .495-.574A3 3 0 0 0 7 11z" />
                            <path d="M8 5a2 2 0 1 1-4 0 2 2 0 0 1 4 0z" />
                        </svg>

                        <a
                            href="#"
                            class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                            More info <i class="bi bi-link-45deg"></i>
                        </a>
                    </div>
                    <!--end::Small Box Widget 4-->
                </div>
                <!--end::Col-->
            </div>
            <!--end::Row-->
            <!--begin::Row-->
            <div class="row">
                <div class="app-content">
                    <!--begin::Container-->
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                            </div>

                            <div class="card">
                                <!-- /.card-header -->
                                <div class="card-body table-responsive">
                                    <div class="d-flex justify-content-end ">
                                        <div class="">
                                            <a href="{{ route('admin.selecttable') }}" class="btn btn-primary btn-add-menu text-right mb-3">Add New Table Order</a>
                                            <a href="{{ route('admin.neworder') }}" class="btn btn-primary btn-add-menu text-right mb-3">Add New Order</a>
                                        </div>
                                    </div>
                                    <div class="row my-3">
                                        <!-- Filter Type -->
                                        <div class="row align-items-end justify-content-end ">
                                            <div class="col-md-3">
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
                                    </div>
                                    <table id="dt_orderdata" class="table table-striped text-nowrap my-3">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Delivery Date</th>
                                                <th>Customer</th>
                                                <!-- <th>Address</th> -->
                                                <th>Payment Type</th>
                                                <!-- <th>Menu Type</th> -->
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
            </div>
            <!-- /.row (main row) -->
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
                            <option value="pending">Pending</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="cancelled">Cancelled</option>
                            <option value="outfordelivery">Out For Delivery</option>
                            <option value="delivered">Delivered</option>
                        </select>
                        <div class="form-group">
                            <label class="col-form-label" for="txt_note">Note</label>
                            <textarea class="form-control me-2" id="txt_note" name="txt_note"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary btn_update_status" type="submit">
                            <span class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
                            Save</button>
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
        var table = $("#dt_orderdata").DataTable({
            ajax: {
                url: '{{route("admin.get-today-order-list")}}', // Replace with your server endpoint
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
                            let cell = $('c[r^="F"] v', this).text(); // 👈 Column G (adjust if needed)
                            let value = parseFloat(cell) || 0;
                            total += value;
                        });
                        // Append a new row with TOTAL
                        let totalRow = `
                            <row r="${lastRow + 1}">
                                <c t="inlineStr" r="E${lastRow + 1}">
                                    <is><t>TOTAL</t></is>
                                </c>
                                <c t="n" r="F${lastRow + 1}">
                                    <v>${total.toFixed(2)}</v>
                                </c>
                            </row>
                        `;

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
                                let price = row[5].text || row[5]; // 👈 index of "total_amount"
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
                                    colSpan: 5,
                                    alignment: 'right',
                                    bold: true
                                });
                            } else if (i > 0 && i < 5) {
                                footerRow.push({}); // empty cells for colSpan
                            } else if (i === 5) {
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
                    data: 'order_id',
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
                        <p>${data?.address}</p>
                        `;
                    },
                },

                // {
                //     data: 'address',
                // },
                {
                    data: 'order_status',
                },
                // {
                //     data: 'order_type',
                // },
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
                    data: 'status_cap',
                },
                {
                    data: 'created_at',
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        let btn = `
                    ${data.next_btn_status}
                        <a  class="text-success m-2 btn-menu-edit"><i class="fa fa-edit"></i></a>
                        `;
                        if (data.status != 'Delivered') {
                            // btn += `  <a  class="text-danger btn-menu-delete m-2"><i class="fa fa-trash"></i></a>`;
                        }
                        return btn;
                    },
                    orderable: false,
                    searchable: false
                }
            ],
            order: [[1, 'asc']]
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

        function getFilterParams() {
            let filterType = $('#filterType').val(); // 👈 get selected value from dropdown
            let params = {};

            if (filterType === 'today') {
                params.filter = 'today';
            } else if (filterType === 'week') {
                params.filter = 'week';
            } else if (filterType === 'month') {
                params.filter = 'month';
            } else if (filterType === 'custom') {
                params.filter = 'custom';
                params.fromDate = $('#fromDate').val();
                params.toDate = $('#toDate').val();
            }

            return params;
        }
        getInfoCard()

        function getInfoCard() {
            let requestData = getFilterParams();
            $.ajax({
                url: '{{route("admin.get-info-card")}}', // Replace with your actual GET URL
                type: 'GET',
                data: requestData,
                dataType: 'json', // or 'html' depending on what the server returns
                success: function(response) {
                    if (response.success) {
                        const data = response.data;

                        $('#totalOrdersCount').text(data.total_order);
                        $('#pendingOrdersCount').text(data.pending_order);
                        $('#completedOrdersCount').text(data.completed_order);
                        $('#cancelledOrdersCount').text(data.cancelled_order);
                        $('#totalUsersCount').text(data.total_user);
                        $('#todayOrdersCount').text(data.today_order);
                        $('#todayRevenueCount').text('$ ' + data.today_revenue);
                        $('#weekluRevenueCount').text(data.weekly_revenue)
                        $('#activeSubscriptionsCount').text(data.active_subscription);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                    $('#result').html('Failed to load data.');
                }
            });
        }


        $('#dt_orderdata').on('click', '.btn-chanage-status', function(e) {
            e.preventDefault();
            const $btn = $('.btn-chanage-status');
            const $spinner = $btn.find('.spinner-border');
            const row = $(this).closest('tr');
            const rowData = table.row(row).data();
            const formData = new FormData();
            formData.append('drp_status', rowData.next_status);
            formData.append('hid_orderid', rowData.id);
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
                    $spinner.removeClass('d-none');
                    $btn.prop('disabled', true);
                },
                success: function(response) {
                    // Handle success response
                    if (response.success) {
                        toastSuccess(response.message);
                        getInfoCard();
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
                    $spinner.addClass('d-none');
                    $btn.prop('disabled', false);
                },
            });
        });
        $('#dt_orderdata').on('click', '.btn-menu-edit', function(e) {
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
        $('#dt_orderdata').on('click', '.btn-menu-delete', function(e) {
            const row = $(this).closest('tr');
            const rowData = table.row(row).data();
            let confirmation = confirm("Are you sure want to delete this Order ?");
            if (confirmation) {
                $.ajax({
                    url: "{{ route('admin.delete-order') }}", // Change this to your server endpoint
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    data: {
                        order_id: rowData.id,
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
                const $btn = $('.btn_update_status');
                const $spinner = $btn.find('.spinner-border');
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
                        $spinner.removeClass('d-none');
                        $btn.prop('disabled', true);
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
                        $spinner.addClass('d-none');
                        $btn.prop('disabled', false);
                    },
                });

            }
        });

    });
</script>
@endsection