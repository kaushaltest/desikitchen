@extends('layouts.admin.app')

@section('title', 'Menu - Dining')

@section('content')
<main class="app-main">
    <!--begin::App Content Header-->
    <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Dining Menu</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Dining menu</li>
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
                            <div class="d-flex justify-content-end mb-3">
                                <div class="">
                                    <button class="btn btn-success text-right" onclick="exportExcel()"><i class="fa fa-download"></i> Export Excel</button>
                                    <button class="btn btn-success text-right" id="btn_import"><i class="fa fa-upload"></i> Import Excel</button>

                                    <button class="btn btn-primary btn-add-menu text-right">Add Menu</button>
                                </div>
                            </div>
                            <table id="dt_diningemenu" class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Image</th>
                                        <th>Category Name</th>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>price</th>
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
    <div class="modal fade" id="model_add_edit_menu" role="dialog" aria-labelledby="model_add_edit_menu" tabindex="-1">>
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
                        <div class="input-group " style="width:100%">
                            <label class="form-label d-block">Select Category</label>
                            <div class="d-flex gap-2" style="width:100%">
                                <select id="drp_category" name="drp_category" class="form-select">
                                </select>
                                <button type="button" class="btn btn-primary" id="btn_add_category" style="width: 200px;">Add Category</button>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-form-label" for="file_menu_image">Upload Menu Image</label>
                            <input type="file" class="form-control" id="file_menu_image" name="file_menu_image" accept="image/*">
                        </div>
                        <div class="form-group">
                            <label class="col-form-label" for="txt_title">Name</label>
                            <input class="form-control me-2" id="txt_title" type="text" name="txt_title">
                        </div>

                        <div class="form-group">
                            <label class="col-form-label" for="txt_price">Price</label>
                            <input class="form-control me-2" id="txt_price" type="text" name="txt_price">

                        </div>
                        <div class="form-group">
                            <label class="col-form-label" for="text_description">Description</label>
                            <textarea class="form-control me-2" id="text_description" type="text" name="text_description"></textarea>
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
    <div class="modal fade" id="model_add_category" role="dialog" aria-labelledby="model_add_category">
        <div class="modal-dialog modal-dialog-centered model_add_category" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title "><span class="model_add_category_title"></span> Add Category</h5>
                    <button class="btn-close py-0" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form_add_category" method="post" class="theme-form needs-validation" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label class="col-form-label" for="txt_category">Name</label>
                            <input class="form-control me-2" id="txt_category" type="text" name="txt_category">
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
<script src="{{asset('admin-assets/validation/alacartemenu.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/exceljs/dist/exceljs.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/file-saver@2.0.5/dist/FileSaver.min.js"></script>
<script>
    getCategory();
    let categoryArr = [];
    let categoryArrGetId = [];

    function getCategory() {
        $.ajax({
            url: "{{ route('admin.get-category') }}", // Change this to your server endpoint
            type: 'GET',
            beforeSend: function() {
                $(".loader-wrapper").css("display", "flex")
            },
            success: async function(response) {
                // Handle success response
                let html = '<option value="">Select Category</option>';
                if (response?.data) {
                    categoryArrGetId=response?.data;
                    response?.data.forEach(ele => {
                        html += `<option value="${ele.id}">${ele.category}</option>`;
                        categoryArr.push(ele.category); // 👈 collect categories
                    });

                }
                $("#drp_category").html(html);
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
    async function exportExcel() {
        const ExcelJS = window.ExcelJS;
        const workbook = new ExcelJS.Workbook();
        const sheet = workbook.addWorksheet("Menu");

        // Headers
        sheet.addRow(["Category", "Name", "Description", "Price", "Is Active"]);

        // Add dropdown validation for Category column (B column)
        sheet.dataValidations.add('E2:E100', { // from B2 to B100 (adjust as per your need)
            type: 'list',
            allowBlank: true,
            formulae: [`"Yes,No"`] // values for dropdown
        });

        sheet.dataValidations.add('A2:A100', { // from B2 to B100 (adjust as per your need)
            type: 'list',
            allowBlank: true,
            formulae: [`"${categoryArr.join(",")}"`] // values for dropdown
        });

        $.ajax({
            url: "{{ route('admin.get-dining-menu') }}",
            type: 'GET',
            beforeSend: function() {
                $(".loader-wrapper").css("display", "flex")
            },
            success: async function(response) {
                if (response?.data) {
                    response?.data.forEach(ele => {
                        sheet.addRow([
                            ele.category_name, // Category (dropdown will apply here)
                            ele.name,
                            ele.description,
                            ele.price,
                            ele.is_active ? "Yes" : "No",
                        ]);
                    });
                }

                const buf = await workbook.xlsx.writeBuffer();
                saveAs(new Blob([buf]), "dining_menu.xlsx");
            },
            error: function(xhr, status, error) {
                toastFail(error)
            },
            complete: function() {
                $(".loader-wrapper").css("display", "none")
            },
        });
    }

    $(document).ready(function() {
        const assetBase = "{{ asset('storage') }}";
        const defaultImage = "{{ asset('default.png') }}";

        var table = $("#dt_diningemenu").DataTable({
            ajax: {
                url: '{{route("admin.get-dining-menu")}}', // Replace with your server endpoint
                type: 'GET', // or 'POST', depending on your server setup
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
                    data: 'category_name',
                },
                {
                    data: 'name',
                },
                {
                    data: 'description',
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
                       <a  class="text-danger btn-menu-delete m-2"><i class="fa fa-trash"></i></a>
                        `;
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

        $(".btn-add-menu").click(function() {
            $('#form_add_edit_menu').validate().resetForm();
            $("#hid_menuid").val('');
            $('#form_add_edit_menu')[0].reset();
            $("#btn_add_category").show();
            $('#model_add_edit_menu').modal('toggle');
            $(".model_add_edit_menu_title").text('Add')
            $(".btn_submit_add_edit_menu").text('Add')
            $(".btn_submit_add_edit_user").text('Add')

        })

        $('#dt_diningemenu').on('click', '.btn-menu-edit', function(e) {
            e.preventDefault();
            $('#form_add_edit_menu').validate().resetForm();
            $('#form_add_edit_menu')[0].reset();
            $("#btn_add_category").hide();
            $(".model_add_edit_menu_title").text('Edit')
            $(".btn_submit_add_edit_user").text('Edit')
            // Get the row data
            const row = $(this).closest('tr');
            const rowData = table.row(row).data();
            // // Populate the modal with row data
            $("#hid_menuid").val(rowData.id);
            $("#drp_category").val(rowData?.category_id)
            $('#txt_title').val(rowData.name);
            $('#text_description').val(rowData.description);
            $('#txt_price').val(rowData.price);
            $('input[name="rbt_is_active"][value="' + rowData.is_active + '"]').prop('checked', true);

            // Show the modal
            $('#model_add_edit_menu').modal('toggle');
        });
        $('#dt_diningemenu').on('click', '.btn-menu-delete', function(e) {
            const row = $(this).closest('tr');
            const rowData = table.row(row).data();
            let confirmation = confirm("Are you sure want to delete this menu ?");
            if (confirmation) {
                $.ajax({
                    url: "{{ route('admin.delete-diningmenu') }}", // Change this to your server endpoint
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
            errorElement: "div",
            errorClass: "invalid-feedback",
            highlight: function(element) {
                $(element).addClass("is-invalid");
            },
            unhighlight: function(element) {
                $(element).removeClass("is-invalid");
            },
            errorPlacement: function(error, element) {
                const $group = element.closest(".input-group");
                if ($group.length) {
                    error.insertAfter($group);
                } else {
                    error.insertAfter(element);
                }
            },
            submitHandler: function(form, event) {
                const formData = new FormData(form);

                // event.preventDefault();
                $.ajax({
                    url: "{{ route('admin.add-update-dining-menu') }}", // Change this to your server endpoint
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
                            toastFail((response.message) ? response.message : "Application cant register try again");
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
        $("#btn_import").click(function() {
            $("#file_import_file").val("");
            $('#form_add_import_request').validate().resetForm();
            $('#model_add_import_request').modal('toggle');
        });
        $(document).on("click", "#btn_add_category", function() {
            $('#txt_category').val('');
            $('#model_add_edit_menu').modal('toggle');
            $('#model_add_category').modal('toggle');
        });

        $('#form_add_category').validate({
            rules: validationRules.categoryForm.rules,
            messages: validationRules.categoryForm.messages,
            errorElement: "div",
            errorClass: "invalid-feedback",
            highlight: function(element) {
                $(element).addClass("is-invalid");
            },
            unhighlight: function(element) {
                $(element).removeClass("is-invalid");
            },
            errorPlacement: function(error, element) {
                const $group = element.closest(".input-group");
                if ($group.length) {
                    error.insertAfter($group);
                } else {
                    error.insertAfter(element);
                }
            },
            submitHandler: function(form, event) {
                const formData = new FormData(form);

                // event.preventDefault();
                $.ajax({
                    url: "{{ route('admin.add-category') }}", // Change this to your server endpoint
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
                            $("#model_add_category").modal('toggle');
                            getCategory()

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
                    const catId = categoryArrGetId.find(c => c.category === row.getCell(1).value)?.id || null;

                    rowsData.push({
                        // id: row.getCell(1).value,
                        category: catId,
                        name: row.getCell(2).value,
                        description: row.getCell(3).value,
                        price: row.getCell(4).value,
                        is_active: (row.getCell(5).value == 'Yes') ? true : false,
                        image: null
                    });
                });

                // Read images & map them
                // sheet.getImages().forEach((imgObj, index) => {
                //     const img = workbook.getImage(imgObj.imageId);

                //     let base64 = btoa(
                //         new Uint8Array(img.buffer).reduce((data, byte) => data + String.fromCharCode(byte), "")
                //     );

                //     rowsData[index].image = "data:image/png;base64," + base64;
                // });
                fetch("{{ route('admin.import_dining') }}", {
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