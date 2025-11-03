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
                    <h3 class="mb-0">Day Wise Menu</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Day wise menu</li>
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
                    <div class="card">
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">

                            <!-- <form action="{{ route('admin.menus.import') }}" method="POST" enctype="multipart/form-data" class="my-3">
                                @csrf
                                <div class="input-group">
                                    <input type="file" name="file" class="form-control" required>
                                    <button class="btn btn-primary" type="submit">Import</button>
                                </div>
                            </form>

                            <a href="{{ route('admin.menus.export') }}" class="btn btn-success mb-3">Export Excel</a> -->

                            <div class="d-flex justify-content-end mb-3">
                                <div class="">
                                    <button class="btn btn-success text-right" onclick="exportExcel()"><i class="fa fa-download"></i> Export Excel</button>
                                    <button class="btn btn-success text-right" id="btn_import"><i class="fa fa-upload"></i> Import Excel</button>

                                    <button class="btn btn-primary btn-add-menu text-right">Add Menu</button>
                                </div>
                            </div>
                            <div class="row my-3">
                                        <!-- Filter Type -->
                                        <div class="row align-items-end justify-content-end ">
                                            <div class="col-md-3">
                                                <select id="filterType" class="form-select">
                                                    <option value="month" selected>This Month</option>
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
                            <table id="dt_daywisemenu" class="table table-striped text-nowrap">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Image</th>
                                        <th>Date</th>
                                        <th>Title</th>
                                        <th>Items</th>
                                        <th>Price</th>
                                        <th>Is Active</th>
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
    <div class="modal fade" id="model_add_edit_menu" role="dialog" aria-labelledby="model_add_edit_menu">
        <div class="modal-dialog modal-dialog-centered model_add_edit_menu" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title "><span class="model_add_edit_menu_title"></span> Menu</h5>
                    <button class="btn-close py-0" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form_add_edit_menu" method="post" class="theme-form needs-validation" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="hid_menuid" id="hid_menuid">
                        <div class="form-group">
                            <label class="col-form-label" for="file_menu_image">Upload Menu Image</label>
                            <input type="file" class="form-control" id="file_menu_image" name="file_menu_image" accept="image/*">
                        </div>
                        <div class="form-group">
                            <label class="col-form-label" for="dt_date">Date</label>
                            <input class="form-control me-2" id="dt_date" type="date" name="dt_date">
                        </div>
                        <div class="form-group">
                            <label class="col-form-label" for="txt_title">Title</label>
                            <input class="form-control me-2" id="txt_title" type="text" name="txt_title">
                        </div>
                        <div class="form-group">
                            <label class="col-form-label" for="txt_item">Items</label>
                            <input class="form-control me-2" id="txt_item" type="text" name="txt_item">
                        </div>
                        <div class="form-group">
                            <label class="col-form-label" for="txt_price">Price</label>
                            <input class="form-control me-2" id="txt_price" type="text" name="txt_price">

                        </div>
                        <div>
                            <label class="col-form-label">Is Active</label>
                            <div class="form-check-size rtl-input mt-2">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input me-2" id="rbt_status_active" type="radio" name="rbt_is_active" value="1" checked="">
                                    <label class="form-check-label" for="rbt_status_active">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input me-2" id="rbt_status_inactive" type="radio" name="rbt_is_active" value="0">
                                    <label class="form-check-label" for="rbt_status_inactive">No</label>
                                </div>
                            </div>
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
    <div class="modal fade" id="model_add_import_request" role="dialog" aria-labelledby="model_add_import_request">
        <div class="modal-dialog modal-dialog-centered model_add_import_request" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title "><span class="model_add_edit_menu_title"></span> Import File</h5>
                    <button class="btn-close py-0" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form_add_import_request" method="post" class="theme-form needs-validation" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label class="col-form-label" for="file_menu_image">Upload File</label>
                            <input type="file" class="form-control" id="file_import_file" name="file_import_file">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary" type="submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--end::App Content-->
</main>
<script src="{{asset('admin-assets/validation/daywisemenu.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/exceljs/dist/exceljs.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/file-saver@2.0.5/dist/FileSaver.min.js"></script>

<script>
    async function exportExcel() {
        const ExcelJS = window.ExcelJS;
        const workbook = new ExcelJS.Workbook();
        const sheet = workbook.addWorksheet("Menu");

        // Headers
        sheet.addRow(["Title", "Price", "Date", "Items"]);
        $.ajax({
            url: "{{ route('admin.get-daywise-menu') }}", // Change this to your server endpoint
            type: 'GET',
            beforeSend: function() {
                $(".loader-wrapper").css("display", "flex")
            },
            success: async function(response) {
                // Handle success response
                if (response?.data) {
                    response?.data.forEach(ele => {
                        sheet.addRow([ele.title, ele.price, ele.menu_date, ele.items]);
                    });

                }
                const buf = await workbook.xlsx.writeBuffer();
                saveAs(new Blob([buf]), "menu.xlsx");
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


        // Example Data
        // const data = [{
        //     title: "Kathiyavadi dish",
        //     price: "100.00",
        //     menu_date: "2025-08-09",
        //     items: "2 roti, 2 sabji",
        //     image_url: "https://picsum.photos/200/300" // sample
        // }];

        // for (let rowIndex = 0; rowIndex < data.length; rowIndex++) {
        //     let d = data[rowIndex];
        //     sheet.addRow([d.title, d.price, d.menu_date, d.items, ""]);

        //     // fetch image
        //     // let response = await fetch(d.image_url);
        //     // let blob = await response.blob();
        //     // let buffer = await blob.arrayBuffer();

        //     // const imageId = workbook.addImage({
        //     //     buffer: buffer,
        //     //     extension: "png",
        //     // });

        //     // sheet.addImage(imageId, {
        //     //     tl: {
        //     //         col: 4,
        //     //         row: rowIndex + 1
        //     //     }, // column index (0-based)
        //     //     ext: {
        //     //         width: 80,
        //     //         height: 80
        //     //     }
        //     // });
        // }


    }
    $(document).ready(function() {
        const assetBase = "{{ asset('storage') }}";
        const defaultImage = "{{ asset('default.png') }}";
        var table = $("#dt_daywisemenu").DataTable({
            ajax: {
                url: '{{route("admin.get-daywise-menu")}}', // Replace with your server endpoint
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
                dataSrc: function(json) {
                    // Ensure the data is returned in a way that DataTable understands
                    console.log('Full response:', json);

                    return json.data;
                },
            },
            order: [],
            // dom: 'rtip',
            columns: [{
                    data: 'id',
                },
                {
                    data: 'image_path',
                    render: function(data) {
                        return `<img src="${data ? `${assetBase}/${data}` : defaultImage}" width="80" >`;
                    }
                },
                {
                    data: 'menu_date',
                },
                {
                    data: 'title',
                },
                {
                    data: 'items',
                },
                {
                    data: 'price',
                },
                {
                    data: 'is_active',
                    render: function(data, type, row) {
                        return (data) ? "<span class='bg-success text-white p-1 rounded'>Yes</span>" : "<span class='bg-danger text-white p-1 rounded'>No</span>"
                    },
                },
                {
                    data: null,
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
            createdRow: function(row, data) {
                if (!data.is_active) {
                    // $(row).css('background-color', '#f8d7da'); // light red
                    // OR add a class:
                    $(row).addClass('table-danger');
                }
            }
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

        $("#btn_import").click(function() {
            $("#file_import_file").val("");
            $('#form_add_import_request').validate().resetForm();
            $('#model_add_import_request').modal('toggle');
        });
        $(".btn-add-menu").click(function() {
            $('#form_add_edit_menu').validate().resetForm();
            $('#form_add_edit_menu')[0].reset();
            $('#model_add_edit_menu').modal('toggle');
            $(".model_add_edit_menu_title").text('Add')
            $(".btn_submit_add_edit_menu").text('Add')
            $(".btn_submit_add_edit_user").text('Save')

        })

        $('#dt_daywisemenu').on('click', '.btn-menu-edit', function(e) {
            e.preventDefault();
            $('#form_add_edit_menu').validate().resetForm();
            $('#form_add_edit_menu')[0].reset();
            $(".model_add_edit_menu_title").text('Edit')
            $(".btn_submit_add_edit_user").text('Save')
            // Get the row data
            const row = $(this).closest('tr');
            const rowData = table.row(row).data();
            // // Populate the modal with row data
            $("#hid_menuid").val(rowData.id)
            $('#dt_date').val(rowData.menu_date);
            $('#txt_title').val(rowData.title);
            $('#txt_item').val(rowData.items);
            $('#txt_price').val(rowData.price);
            $('input[name="rbt_is_active"][value="' + rowData.is_active + '"]').prop('checked', true);
            // Show the modal
            $('#model_add_edit_menu').modal('toggle');
        });
        $('#dt_daywisemenu').on('click', '.btn-menu-delete', function(e) {
            const row = $(this).closest('tr');
            const rowData = table.row(row).data();
            let confirmation = confirm(
                "Are you sure you want to " + (!rowData.is_active ? "restore" : "delete") + " this menu?"
            );
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
            rules: validationRules.userManagementForm.rules,
            messages: validationRules.userManagementForm.messages,
            submitHandler: function(form, event) {
                const formData = new FormData(form);

                // event.preventDefault();
                $.ajax({
                    url: "{{ route('admin.add-update-daywise-menu') }}", // Change this to your server endpoint
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
                            $('#model_add_edit_menu').modal('toggle');
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
        $('#form_add_import_request').validate({
            rules: validationRules.importRequestForm.rules,
            messages: validationRules.importRequestForm.messages,
            submitHandler: async function(form, event) {
                event.preventDefault();
                const fileInput = form.querySelector('input[name="file_import_file"]');
                const file = fileInput.files[0];
                const ExcelJS = window.ExcelJS;
                const workbook = new ExcelJS.Workbook();
                await workbook.xlsx.load(await file.arrayBuffer());
                const sheet = workbook.worksheets[0];
                let rowsData = [];
                sheet.eachRow((row, rowNumber) => {
                    if (rowNumber === 1) return; // skip header

                    rowsData.push({
                        // id: row.getCell(1).value,
                        title: row.getCell(1).value,
                        price: row.getCell(2).value,
                        date: row.getCell(3).value,
                        items: row.getCell(4).value,
                        image: null // will be filled later
                    });
                });

                // Read images & map them
                sheet.getImages().forEach((imgObj, index) => {
                    const img = workbook.getImage(imgObj.imageId);

                    let base64 = btoa(
                        new Uint8Array(img.buffer).reduce((data, byte) => data + String.fromCharCode(byte), "")
                    );

                    rowsData[index].image = "data:image/png;base64," + base64;
                });
                fetch("{{ route('admin.menus.import') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            data: rowsData
                        })
                    })
                    .then(res => res.json())
                    .then(res => {
                        if (res.success) {
                            toastSuccess(res.message);
                            $('#model_add_import_request').modal('toggle');
                            table.ajax.reload();

                        } else {
                            toastFail(res.message)
                        }
                    })
                    .catch(err => toastFail(err.message));

            }
        });
    });
</script>
@endsection