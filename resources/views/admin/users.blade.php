@extends('layouts.admin.app')

@section('title', 'Users')

@section('content')
<main class="app-main">
    <!--begin::App Content Header-->
    <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Users</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Users</li>
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
                                    <button class="btn btn-primary btn-add-user text-right">Add New User</button>
                                </div>
                            </div>
                            <table id="dt_users" class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
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
    <div class="modal fade" id="model_add_edit_user" role="dialog" aria-labelledby="model_add_edit_user">
        <div class="modal-dialog modal-dialog-centered model_add_edit_user" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title "><span class="model_add_edit_user_title"></span> User</h5>
                    <button class="btn-close py-0" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form_add_edit_user" method="post" class="theme-form needs-validation">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="hid_userid" id="hid_userid">
                        <div class="form-group">
                            <label class="col-form-label" for="txt_name">Name</label>
                            <input class="form-control me-2" id="txt_name" type="text" name="txt_name" placeholder="Enter name">
                        </div>
                        <div class="form-group">
                            <label class="col-form-label" for="txt_email">Email</label>
                            <input class="form-control" id="txt_email" type="text" name="txt_email" placeholder="Enter email">
                        </div>
                        <div class="form-group">
                            <label class="col-form-label" for="txt_phone">Phone</label>
                            <input class="form-control" id="txt_phone" type="number" name="txt_phone" placeholder="Enter phone">
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
                url: '{{route("admin.get-all-users")}}', // Replace with your server endpoint
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
                    data: 'email',
                },
                {
                    data: 'phone',
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
                    },
                    // <a  class="text-danger btn-menu-delete m-2"><i class="fa fa-trash"></i></a>
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

        $(".btn-add-user").click(function() {
            $('#form_add_edit_user').validate().resetForm();
            $("#hid_userid").val('');
            $('#form_add_edit_user')[0].reset();
            $('#model_add_edit_user').modal('toggle');
            $(".model_add_edit_user_title").text('Add')
            $(".btn_submit_add_edit_menu").text('Add')
            $(".btn_submit_add_edit_user").text('Add')

        })

        $('#dt_users').on('click', '.btn-menu-edit', function(e) {
            e.preventDefault();
            $('#form_add_edit_user').validate().resetForm();
            $('#form_add_edit_user')[0].reset();
            $(".model_add_edit_user_title").text('Edit')
            $(".btn_submit_add_edit_user").text('Save')
            // Get the row data
            const row = $(this).closest('tr');
            const rowData = table.row(row).data();
            // // Populate the modal with row data
            $("#hid_userid").val(rowData.id)
            $('#txt_name').val(rowData.name);
            $('#txt_email').val(rowData.email);
            $('#txt_phone').val(rowData.phone);
            $('input[name="rbt_is_active"][value="' + rowData.is_active + '"]').prop('checked', true);

            // Show the modal
            $('#model_add_edit_user').modal('toggle');
        });
        $('#dt_users').on('click', '.btn-menu-delete', function(e) {
            const row = $(this).closest('tr');
            const rowData = table.row(row).data();
            let confirmation = confirm(
                "Are you sure you want to " + (rowData.is_delete ? "restore" : "delete") + " this user?"
            );
            if (confirmation) {
                $.ajax({
                    url: "{{ route('admin.delete-users') }}", // Change this to your server endpoint
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

        $('#form_add_edit_user').validate({
            rules: validationRules.userValidationForm.rules,
            messages: validationRules.userValidationForm.messages,
            submitHandler: function(form, event) {
                const formData = new FormData(form);

                // event.preventDefault();
                $.ajax({
                    url: "{{ route('admin.add-update-users') }}", // Change this to your server endpoint
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
                            $('#model_add_edit_user').modal('toggle');
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