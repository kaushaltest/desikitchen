@extends('layouts.admin.app')

@section('title', 'Menu - Category')

@section('content')
<main class="app-main">
    <!--begin::App Content Header-->
    <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Category</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Category</li>
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
                                    <button class="btn btn-primary btn-add-category text-right">Add Category</button>
                                </div>
                            </div>
                            <table id="dt_category" class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Category Name</th>
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
    <div class="modal fade" id="model_add_category" role="dialog" aria-labelledby="model_add_category">
        <div class="modal-dialog modal-dialog-centered model_add_category" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title "><span class="model_add_category_title"></span> Category</h5>
                    <button class="btn-close py-0" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form_add_category" method="post" class="theme-form needs-validation" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="hid_menuid" id="hid_menuid">
                        <div class="form-group">
                            <label class="col-form-label" for="txt_category">Name</label>
                            <input class="form-control me-2" id="txt_category" type="text" name="txt_category">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary btn_submit_add_edit_category" type="submit">Submit</button>
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
    let categoryArr = [];
    let categoryArrGetId = [];


    $(document).ready(function() {
        const assetBase = "{{ asset('storage') }}";
        const defaultImage = "{{ asset('default.png') }}";

        var table = $("#dt_category").DataTable({
            ajax: {
                url: '{{route("admin.get-category")}}', // Replace with your server endpoint
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
                    data: 'category',
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

        $(".btn-add-category").click(function() {
            $('#form_add_category').validate().resetForm();
            $("#hid_menuid").val('');
            $('#form_add_category')[0].reset();
            $('#model_add_category').modal('toggle');
            $(".model_add_category_title").text('Add')
            $(".btn_submit_add_edit_menu").text('Add')
            $(".btn_submit_add_edit_category").text('Add')

        })

        $('#dt_category').on('click', '.btn-menu-edit', function(e) {
            e.preventDefault();
            $('#form_add_category').validate().resetForm();
            $('#form_add_category')[0].reset();
            $(".model_add_category_title").text('Edit')
            $(".btn_submit_add_edit_category").text('Edit')
            // Get the row data
            const row = $(this).closest('tr');
            const rowData = table.row(row).data();
            console.log(rowData)
            // // Populate the modal with row data
            $("#hid_menuid").val(rowData.id);
            $("#txt_category").val(rowData?.category)
            $('#model_add_category').modal('toggle');
        });
        $('#dt_category').on('click', '.btn-menu-delete', function(e) {
            const row = $(this).closest('tr');
            const rowData = table.row(row).data();
            let confirmation = confirm("Are you sure want to delete this category ?");
            if (confirmation) {
                $.ajax({
                    url: "{{ route('admin.delete-category') }}", // Change this to your server endpoint
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    data: {
                        category_id: rowData.id,
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
                    url: "{{ route('admin.add-edit-category') }}", // Change this to your server endpoint
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