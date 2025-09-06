@extends('layouts.admin.app')

@section('title', 'Subscription')

@section('content')
<main class="app-main">
    <!--begin::App Content Header-->
    <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Subscription</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Subscription</li>
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
                                    <button class="btn btn-primary btn-add-menu text-right">Add New Subscription</button>
                                </div>
                            </div>
                            <table id="dt_subscription" class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>price</th>
                                        <th>Total Meals</th>
                                        <th>Days</th>
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
    <div class="modal fade" id="model_add_edit_subscription" role="dialog" aria-labelledby="model_add_edit_subscription">
        <div class="modal-dialog modal-dialog-centered model_add_edit_subscription" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title "><span class="model_add_edit_subscription_title"></span> Menu</h5>
                    <button class="btn-close py-0" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form_add_edit_subscription" method="post" class="theme-form needs-validation">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="hid_subid" id="hid_subid">
                        <div class="form-group">
                            <label class="col-form-label" for="txt_name">Name</label>
                            <input class="form-control me-2" id="txt_name" type="text" name="txt_name" placeholder="Enter name">
                        </div>
                        <div class="form-group">
                            <label class="col-form-label" for="txt_description">Description</label>
                            <textarea class="form-control me-2" id="txt_description" type="text" name="txt_description" placeholder="Enter description"></textarea>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label" for="txt_price">Price</label>
                            <input class="form-control" id="txt_price" type="number" name="txt_price" placeholder="Enter price" step="0.01">
                        </div>
                        <div class="form-group">
                            <label class="col-form-label" for="txt_meals">Total Meals</label>
                            <input class="form-control" id="txt_meals" type="number" name="txt_meals" placeholder="Enter total meal" step="0.01">
                        </div>
                        <div class="form-group">
                            <label class="col-form-label" for="txt_price">Days</label>
                            <input class="form-control" id="txt_days" type="number" name="txt_days" placeholder="Enter Days" step="0.01">
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
<script src="{{asset('admin-assets/validation/subscription.js')}}"></script>
<script>
    $(document).ready(function() {

        var table = $("#dt_subscription").DataTable({
            ajax: {
                url: '{{route("admin.get-all-subscription")}}', // Replace with your server endpoint
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
                    data: 'description',
                },
                {
                    data: 'price',
                },
                {
                    data: 'total_meals',
                },
                {
                    data: 'days',
                    render: function(data, type, row) {
                        return (data) ? data : "Unlimited"
                    },
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
            $('#form_add_edit_subscription').validate().resetForm();
            $("#hid_subid").val('');
            $('#form_add_edit_subscription')[0].reset();
            $('#model_add_edit_subscription').modal('toggle');
            $(".model_add_edit_subscription_title").text('Add')
            $(".btn_submit_add_edit_menu").text('Add')
            $(".btn_submit_add_edit_user").text('Add')

        })

        $('#dt_subscription').on('click', '.btn-menu-edit', function(e) {
            e.preventDefault();
            $('#form_add_edit_subscription').validate().resetForm();
            $('#form_add_edit_subscription')[0].reset();
            $(".model_add_edit_subscription_title").text('Edit')
            $(".btn_submit_add_edit_user").text('Edit')
            // Get the row data
            const row = $(this).closest('tr');
            const rowData = table.row(row).data();
            // // Populate the modal with row data
            $("#hid_subid").val(rowData.id)
            $('#txt_name').val(rowData.name);
            $('#txt_description').val(rowData.description);
            $('#txt_price').val(rowData.price);
            $('#txt_meals').val(rowData.total_meals);
            $('#txt_days').val(rowData.days);

            $('input[name="rbt_is_active"][value="' + rowData.is_active + '"]').prop('checked', true);

            // Show the modal
            $('#model_add_edit_subscription').modal('toggle');
        });
        $('#dt_subscription').on('click', '.btn-menu-delete', function(e) {
            const row = $(this).closest('tr');
            const rowData = table.row(row).data();
            let confirmation = confirm("Are you sure want to delete this subscription ?");
            if (confirmation) {
                $.ajax({
                    url: "{{ route('admin.delete-subscription') }}", // Change this to your server endpoint
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

        $('#form_add_edit_subscription').validate({
            rules: validationRules.validationForm.rules,
            messages: validationRules.validationForm.messages,
            submitHandler: function(form, event) {
                const formData = new FormData(form);

                // event.preventDefault();
                $.ajax({
                    url: "{{ route('admin.add-update-subscription') }}", // Change this to your server endpoint
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
                            $('#model_add_edit_subscription').modal('toggle');
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