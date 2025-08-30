@extends('layouts.admin.app')

@section('title', 'Select table')

@section('content')
<style>
    /* Card polish */
    .plan-card {
        border: 1px solid #FEA116;
        border-radius: 1rem;
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .text-primary {
        color: #FEA116 !important;
    }

    .plan-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 1rem 2rem rgba(0, 0, 0, .08);
    }

    .plan-header {
        background: linear-gradient(180deg, rgba(254, 161, 22, .12), rgba(254, 161, 22, .03));
        border-bottom: 1px solid #FEA116;
        border-top-left-radius: 1rem;
        border-top-right-radius: 1rem;
    }

    .price {
        font-size: 2.25rem;
        font-weight: 800;
        letter-spacing: -0.5px;
    }

    .price i {
        padding: 12px 18px;
        background: #FEA116;
        color: #FFFFFF;
        border-radius: 100px
    }

    .btn-primary {
        background-color: #FEA116;
        border: 1px solid #FEA116;

    }

    .border-primary {
        border-color: #FEA116 !important;
    }

    .btn-primary:hover {
        background-color: #FEA116;
        border: 1px solid #FEA116;
    }

    .badge-soft {
        background-color: rgba(254, 161, 22, .12);
        color: #b66b00;
        border: 1px solid rgba(254, 161, 22, .3);
    }
</style>
<main class="app-main">
    <!--begin::App Content Header-->
    <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Select Table</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Select Table</li>
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
                            <div class="row g-3 row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4" id="tbl_list">


                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Container-->
            </div>
</main>
<script src="{{asset('admin-assets/validation/users.js')}}"></script>
<script>
    $(document).ready(function() {
        var userId = "{{Auth::user()->id}}";
        localStorage.setItem('user_id', userId);
        getAllTable();

        function getAllTable() {
            $.ajax({
                url: "{{ route('admin.get-all-table') }}", // Change this to your server endpoint
                type: 'GET',
                beforeSend: function() {
                    $(".loader-wrapper").css("display", "flex")
                },
                success: function(response) {
                    // Handle success response
                    let html = ``;
                    if (response.success) {

                        response?.data?.forEach(dt => {
                            let buttonHtml = '';

                            if (!dt.user_id) {
                                // Table is free
                                buttonHtml = `
                        <button class="btn btn-primary w-100 mt-auto btn-book-a-table" data-tableid='${dt.id}'>
                            Book a table
                        </button>`;
                            } else if (dt.user_id == userId) {
                                // Table booked by current user
                                buttonHtml = `
                        <button class="btn btn-warning w-100 mt-auto btn-book-a-table" data-tableid='${dt.id}'>
                            Edit Order
                        </button>`;
                            } else {
                                // Booked by someone else
                                buttonHtml = `
                        <button class="btn btn-secondary w-100 mt-auto" disabled>
                            Not Available
                        </button>`;
                            }

                            html += `
        <div class="col-12 col-md-6 col-lg-4">
            <div class="plan-card h-100 d-flex flex-column border-2 border-primary">
                <div class="p-4 d-flex flex-column flex-grow-1 plan-header">
                    <div class="d-flex flex-column justify-content-center gap-2 mb-3 w-100">
                        <div class="price text-center"><i class="fa fa-table"></i></div>
                        <div class="text-secondary text-center">${dt.name}</div>
                    </div>
                    <ul class="list-unstyled mb-4">
                        <li class="mb-2">
                            <i class="bi bi-check-circle me-2 text-primary"></i>
                            <strong>Capacity:</strong> ${dt.capicity}
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle me-2 text-primary"></i>
                            ${dt.user_id 
                                ? '<span class="badge bg-danger">Booked</span>' 
                                : '<span class="badge bg-success">Available</span>'}
                        </li>
                    </ul>
                    ${buttonHtml}
                </div>
            </div>
        </div>`;
                        });
                        $('#tbl_list').html(html);
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

        $(document).on("click", '.btn-book-a-table', function() {
            let tableId = $(this).attr('data-tableid');
            $.ajax({
                url: "{{ route('admin.book-table') }}",
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                data: {
                    table_id: tableId
                },
                beforeSend: function() {
                    $(".loader-wrapper").css("display", "flex")
                },
                success: function(response) {
                    if (response.success) {
                        localStorage.setItem('table_id', tableId);
                        window.location = './tableorder'
                    } else {
                        toastFail(response.message || "Application can't register, try again");
                    }
                    getAllTable();
                },
                error: function(xhr) {
                    var errors = xhr.responseJSON.errors;
                    toastFail(errors);
                },
                complete: function() {
                    $(".loader-wrapper").css("display", "none")
                }
            });

        })


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
                            toastFail(response.message || "Application can't register, try again");
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