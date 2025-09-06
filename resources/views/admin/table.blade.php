@extends('layouts.admin.app')

@section('title', 'Tables')

@section('content')
<main class="app-main">
    <!--begin::App Content Header-->
    <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Tables</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Tables</li>
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
                                    <button class="btn btn-primary btn-add-user text-right">Add New Table</button>
                                </div>
                            </div>
                            <table id="dt_users" class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Capicity</th>
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
    <div class="modal fade" id="model_add_edit_tables" role="dialog" aria-labelledby="model_add_edit_tables">
        <div class="modal-dialog modal-dialog-centered model_add_edit_tables" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title "><span class="model_add_edit_tables_title"></span> Table</h5>
                    <button class="btn-close py-0" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form_add_edit_table" method="post" class="theme-form needs-validation">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="hid_tableid" id="hid_tableid">
                        <div class="form-group">
                            <label class="col-form-label" for="txt_name">Table Name</label>
                            <input class="form-control me-2" id="txt_name" type="text" name="txt_name" placeholder="Enter table name">
                        </div>
                        <div class="form-group">
                            <label class="col-form-label" for="txt_capicity">Table Capicity</label>
                            <input class="form-control" id="txt_capicity" type="text" name="txt_capicity" placeholder="Enter table capicity">
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
    <!--end::App Content-->
</main>
<script src="{{asset('admin-assets/validation/users.js')}}"></script>
<script>
    $(document).ready(function() {

        var table = $("#dt_users").DataTable({
            ajax: {
                url: '{{route("admin.get-all-table")}}', // Replace with your server endpoint
                type: 'GET', // or 'POST', depending on your server setup
                dataSrc: function(json) {
                    return json.data;
                },
            },
            order: [],
            // dom: 'rtip',
            columns: [{
                    data: 'id',
                },
                {
                    data: 'name',
                },
                {
                    data: 'capicity',
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

        $(".btn-add-user").click(function() {
            $('#form_add_edit_table').validate().resetForm();
            $(".txt_password").show();
            $("#hid_tableid").val('');
            $('#form_add_edit_table')[0].reset();

            $('#model_add_edit_tables').modal('toggle');
            $(".model_add_edit_tables_title").text('Add')
            $(".btn_submit_add_edit_menu").text('Add')
            $(".btn_submit_add_edit_user").text('Add')

        })

        $('#dt_users').on('click', '.btn-menu-edit', function(e) {
            e.preventDefault();
            $(".txt_password").hide();
            $('#form_add_edit_table').validate().resetForm();
            $('#form_add_edit_table')[0].reset();
            $(".model_add_edit_tables_title").text('Edit')
            $(".btn_submit_add_edit_user").text('Edit')
            // Get the row data
            const row = $(this).closest('tr');
            const rowData = table.row(row).data();
            // // Populate the modal with row data
            $("#hid_tableid").val(rowData.id)
            $('#txt_name').val(rowData.name);
            $('#txt_capicity').val(rowData.capicity);
            $('input[name="rbt_is_active"][value="' + rowData.is_active + '"]').prop('checked', true);

            // Show the modal
            $('#model_add_edit_tables').modal('toggle');
        });
        $('#dt_users').on('click', '.btn-menu-delete', function(e) {
            const row = $(this).closest('tr');
            const rowData = table.row(row).data();
            let confirmation = confirm("Are you sure want to delete this user ?");
            if (confirmation) {
                $.ajax({
                    url: "{{ route('admin.delete-admin-users') }}", // Change this to your server endpoint
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    data: {
                        sub_id: rowData.id,
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

        $('#form_add_edit_table').validate({
            rules: validationRules.tableValidationForm.rules,
            messages: validationRules.tableValidationForm.messages,
            submitHandler: function(form, event) {
                const formData = new FormData(form);

                $.ajax({
                    url: "{{ route('admin.add-update-table') }}",
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
                        if (response.success) {
                            toastSuccess(response.message);
                            $('#model_add_edit_tables').modal('toggle');
                            table.ajax.reload();
                        } else {
                            toastFail(response.message || "Something went wrong. Please try again later.");
                        }
                    },
                    error: function(xhr) {
                        var errors = xhr.responseJSON.errors;
                        toastFail(errors);
                    },
                    complete: function() {
                        $(".loader-wrapper").css("display", "none")
                    }
                });
            }
        });

    });
</script>
@endsection