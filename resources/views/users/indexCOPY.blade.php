@extends('layouts.app')
@section('title', 'dashboard')
@section('datatable-css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{--
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    --}}
    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/2.0.7/css/dataTables.dataTables.css" rel="stylesheet">
@endsection
@section('content-header', 'Dashboard')
@section('content-action')
@endsection
@section('content')
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'manager')
                    <h4 class="card-title">
                        <button type="button" class="btn btn-primary btn-lg addUser" data-toggle="modal" data-target=".bd-example-modal-md">Add User</button>
                    </h4>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered data-table">
                        <thead>
                        <tr>
                            <th></th>
                            <th>ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Birthday</th>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <style>
        .small {
            font-size: 0.775em;
        }
    </style>
    <!-- Modal -->
    <div class="modal fade bd-example-modal-md" id="myModal" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-md">

            <!-- Modal content-->
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold" id="modal-title">Add User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="btn_close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" action="" class="profile-form mt-2 ml-2" enctype="multipart/form-data" id="addUser" name="addUser">
                        <input type="hidden" name="user_id" id="user_id">

                        <div class="form-group">
                            <label for="name" class="form-label font-weight-light">{{ __('Name') }}</label>
                            <input id="name" name="name" type="text" class="form-control" placeholder="Enter name" required autofocus autocomplete="name" />
                            <span class="text-danger small" id="nameError"></span>
                        </div>

                        <div class="form-group">
                            <label for="lastname" class="form-label font-weight-light">{{ __('Lastname') }}</label>
                            <input id="lastname" name="lastname" type="text" class="form-control" placeholder="Enter lastname"  required autofocus autocomplete="lastname" />
                            <span class="text-danger small" id="lastnameError"></span>
                        </div>

                        <div class="form-group">
                            <label for="username" class="form-label font-weight-light">{{ __('Username') }}</label>
                            <input id="username" name="username" type="text" class="form-control"  placeholder="Enter username" required autofocus autocomplete="username" />
                            <span class="text-danger small" id="usernameError"></span>
                        </div>

                        <div class="form-group">
                            <label for="birthday" class="form-label font-weight-light">{{ __('Birthday') }}</label>
                            <input id="birthday" name="birthday" type="date" class="form-control" placeholder="Enter birthday" required autofocus autocomplete="birthday" />
                            <span class="text-danger small" id="birthdayError"></span>
                        </div>
                        @if(auth()->user()->role === 'admin')
                            <div class="form-group">
                                <label class="small mb-1" for="role">Role</label>
                                <select class="form-control" name="role" id="role">
                                    <option value="admin">Admin</option>
                                    <option value="manager">Manager</option>
                                    <option value="user" >User</option>
                                </select>
                            </div>
                        @else
                            <div class="form-group">
                                <label class="small mb-1" for="role">Role</label>
                                <select class="form-control" name="role" id="role">
                                    <option value="user" >User</option>
                                </select>
                            </div>
                        @endif
                        <div class="form-group">
                            <label for="email" class="form-label font-weight-light">{{ __('Email') }}</label>
                            <input id="email" name="email" type="email" class="form-control"  placeholder="Enter email" required autocomplete="birthday" />
                            <span class="text-danger small" id="emailError"></span>
                        </div>

                        <div class="form-group">
                            <label for="password" class="form-label font-weight-light">{{ __('Password') }}</label>
                            <input id="password" name="password" type="password" class="form-control" placeholder="Enter password" autocomplete="password" />
                            <span class="text-danger small" id="passwordError"></span>
                        </div>

                        <div class="form-group">
                            <label for="confirmPassword" class="form-label font-weight-light">{{ __(' Confirm Password') }}</label>
                            <input id="confirmPassword" name="confirmPassword" type="password" class="form-control" placeholder="Confirm password" autocomplete="confirmPassword" />
                        </div>

                        <div class="modal-footer">
                            <input type="submit" name="saveBtn" id="saveBtn" class="btn btn-primary" value="Save" />
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" id="btn_dismiss">Close</button>
                        </div>
                        <!-- Add this div to display success and error messages -->
                        <div id="message-container"></div>
                    </form>
                </div>

            </div>

        </div>
    </div>
@endsection

@section('datatable-scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>

    <script>
        let table;

        function formatChildRow(data) {
            let username = data.username;
            let tableId = 'table_' + username;

            let tableContent = '<table id="' + tableId + '" class="table table-bordered table-hover tableYear">' +
                '<thead class="thead-dark">' +
                '<tr>' +
                '<th></th>' +
                '<th>Year</th>' +
                '<th>Hours worked</th>' +
                '<th>Minutes worked</th>' +
                '<th>Seconds worked</th>' +
                '</tr>' +
                '</thead>' +
                '<tbody>';

            data.forEach(function(entry) {
                tableContent += '<tr>' +
                    '<td></td>' +
                    '<td>' + entry.year + '</td>' +
                    '<td>' + entry.hours + '</td>' +
                    '<td>' + entry.minutes + '</td>' +
                    '<td>' + entry.seconds + '</td>' +
                    '</tr>';
            });

            tableContent += '</tbody></table>';

            return tableContent;
        }
        /*
                $('#' + tableId).DataTable({
                    "paging": false,
                    "searching": false,
                    "processing": true,
                    columns: [
                        {
                            className: 'details-control',
                            orderable: false,
                            data: null,
                            defaultContent: ''
                        },
                        { data: 'year' },
                        { data: 'hours' },
                        { data: 'minutes' },
                        { data: 'seconds' }
                    ],
                    order: [[1, 'asc']]
                });
        */


        $(function () {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('users.index') }}",
                columns: [
                    {
                        className: 'dt-control',
                        orderable: false,
                        data: null,
                        defaultContent: ''
                    },

                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'lastname', name: 'lastname'},
                    {data: 'username', name: 'username'},
                    {data: 'email', name: 'email'},
                    {data: 'birthday', name: 'birthday'},
                    {data: 'role', name: 'role'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                order: [[1, 'asc']]
            });

            table.on('click', 'td.dt-control', function (e) {
                let tr = e.target.closest('tr');
                let row = table.row(tr);

                if (row.child.isShown()) {
                    row.child.hide();
                    // tr.removeClass('shown');
                } else {
                    $.ajax({
                        url: "{{ route('users.fetch_years') }}",
                        method: "POST",
                        data: { operation: "years", username: row.data().username },
                        success: function(response) {
                            console.log(response);
                            row.child(formatChildRow(response)).show();
                        },
                        error: function(xhr, status, error) {
                            console.error("Error fetching data:", error);
                        }
                    });
                    // row.child(formatChildRow(row.data())).show();
                    // tr.addClass('shown');
                }
            });


        });


        $(document).on('click', '#btn_close', function () {
            closeModal();
            clearMessage();
        });

        $(document).on('click', '#btn_dismiss', function () {
            closeModal();
            clearMessage();
        });


        $(document).on('click', '.addUser', function() {
            $('.modal-title').text("Add New User");
            $('#saveBtn').val("Save User");
            resetForm(); // Reset the form fields
            openModal();
        });

        // $(document).on('click', function (event) {
        //     if ($(event.target).closest('.modal-content').length === 0 && !$(event.target).hasClass('addUser')) {
        //         closeModal();
        //         clearMessage();
        //     }
        // });

        $('.modal').on('click', function (event) {
            if (!$(event.target).closest('.modal-content').length && !$(event.target).hasClass('addUser')) {
                closeModal();
                clearMessage();
            }
        });

        $(document).on('submit', '#addUser', function(e) {
            e.preventDefault();
            var formData = $("#addUser").serialize();

            $('#nameError').addClass('d-none');
            $('#lastnameError').addClass('d-none');
            $('#usernameError').addClass('d-none');
            $('#birthdayError').addClass('d-none');
            $('#emailError').addClass('d-none');
            $('#passwordError').addClass('d-none');


            $.ajax({
                data: formData,
                url: "{{ route('users.store') }}",
                type: "POST",
                dataType: 'json',
                success: function (data){
                    showMessage('success', data.success);
                },
                error: function (data) {
                    console.log('Error data:', data);
                    console.log('Response Text:', data.responseText);
                    var errors;
                    try {
                        errors = JSON.parse(data.responseText);
                    } catch (e) {
                        console.error('Error parsing JSON:', e);
                    }

                    if (errors && errors.errors) {
                        $.each(errors.errors, function (key, value){
                            var ErrorID = '#' + key + 'Error';
                            $(ErrorID).removeClass("d-none");
                            $(ErrorID).text(value);
                        });
                    }
                }

            });
        });

        $(document).on('click', '.editUser', function(){
            var user_id = $(this).data('id');
            $.get("{{ url('users/') }}" + '/' + user_id + '/edit', function (data) {
                $('#modal-title').html("Edit User Details");
                $('#saveBtn').val("Edit User");
                $('#user_id').val(data.id);
                $('#name').val(data.name);
                $('#lastname').val(data.lastname);
                $('#username').val(data.username);
                $('#email').val(data.email);
                $('#birthday').val(data.birthday);
                $('#role').val(data.role);
                $('#password').closest('.form-group').hide();
                $('#confirmPassword').closest('.form-group').hide();
                openModal();
            });
        });



        $(document).on('click', '.deleteUser', function(){
            var user_id = $(this).data('id');
            Swal.fire({
                title: 'Are you sure?',
                text: 'You will not be able to recover this user!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true ,
                customClass: {
                    popup: 'custom-swal-popup'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    console.log(user_id);
                    var formData = new FormData();
                    formData.append('user_id', user_id);
                    formData.append('operation', 'Delete');
                    $.ajax({
                        type: "DELETE",
                        url: "{{ url('users/delete') }}"+'/'+user_id,
                        success: function (data) {
                            Swal.fire({
                                icon: "success",
                                title: "User deleted successfully",
                                showConfirmButton: false,
                                timer: 1500
                            });
                            table.draw();
                        },
                        error: function (data) {
                            console.log('Error:', data);
                        }
                    });
                }
            });
        });

        // function closeModal(){
        //     $('#message').html('');
        //     $('.error').remove();
        //     $('body').removeClass('modal-open');
        // }


        function closeModal() {
            $('#addUser').trigger("reset");
            $('#password').closest('.form-group').show();
            $('#confirmPassword').closest('.form-group').show();
            clearMessage();
            $('#myModal').modal("hide");
            $('.modal-backdrop').remove();
            $('body').removeClass('modal-open')
            $('.modal-title').text("Add New User");
            $('#saveBtn').val("Save User");
            table.draw();
        }

        function openModal(){
            $('body').addClass('modal-open').append('<div class="in"></div>');
            $('#myModal').modal("show");
        }

        function showMessage(type, message) {
            var messageContainer = $('#message-container');
            var alertHtml = '<div class="alert alert-success alert-dismissible fade show" role="alert">' +
                message +
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                '<span aria-hidden="true">&times;</span>' +
                '</button>' +
                '</div>';
            messageContainer.html(alertHtml);

        }

        function clearMessage() {
            $('#message-container').html('');
            $('#nameError').html('');
            $('#lastnameError').html('');
            $('#usernameError').html('');
            $('#birthdayError').html('');
            $('#emailError').html('');
            $('#passwordError').html('');
        }

        function resetForm() {
            $('#user_id').val('');
            $('#addUser')[0].reset();
        }

    </script>
@endsection
