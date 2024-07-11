@extends('layouts.main')


@push('title')
<title>Users</title>
@endpush

@section('main-section')
<!-- Custom styles for this page -->
<link href="{{ asset('vendors/datatables/dataTables.bootstrap5.css') }}" rel="stylesheet">

<script>
    //open edit model with data
    function editUser(id) {
    $.ajax({
            url: "{{ route('user-edit', ['id' => '']) }}/" + id, // Pass the id here
            type: "GET",
            success: function (data) {
                // Handle success
                $('#userModalLabel').text("edit user");
                $('#userid').val(data.user.id);
                $('#name').val(data.user.name);
                $('#email').val(data.user.email);
                $('#password').val("");
                $('#password').removeAttr("required");
                $('#status').val(data.user.status);

                var userRoles = data.user_roles;

                $('#roles option').each(function() {
                    var roleName = $(this).val();
                    if (userRoles.hasOwnProperty(roleName)) {
                        $(this).prop('selected', true);
                    }
                });

                $('#userModal').modal('show');
            },
            error: function () {
                // Handle error
                alert('An error occurred.');
            }
        });
    }

</script>

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3" id="table-card-title">
        <h6 class="m-0 font-weight-bold text-primary">Users</h6>
        <div>
            @can('add user')
            <button class="btn btn-primary" id="addUser" data-toggle="modal" data-target="#userModal"><i class="fas fa-plus"></i></button>
            @endcan

            @can('delete user')
            <button id="delete-selected" class="btn btn-danger"><i class="fas fa-trash"></i></button>
            @endcan
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
        <table class="table table-bordered" id="userTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th><input type="checkbox" id="check-all"></th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    @can('edit user')
                    <th>Edit</th>
                    @endcan
                    @can('delete user')
                    <th>Delete</th>
                    @endcan
                </tr>
            </thead>
        </table>  
        </div>
    </div>
</div>


<!--user-form-modal -->
<div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="userModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="userModalLabel">user form</h5>
        <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('user-save') }}" method="post">
        <div class="modal-body">
          <div class="form-group">
            @csrf
            <input type="hidden" name="id" id="userid" value="0">
            <label for="name" class="col-form-label">Name:</label>
            <input type="text" class="form-control" name="name" id="name" oninvalid="this.setCustomValidity('username field is required')" oninput="setCustomValidity('')" required>
          </div>
          <div class="form-group">
            <label for="email" class="col-form-label">Email:</label>
            <input type="email" class="form-control" name="email" id="email" oninvalid="this.setCustomValidity('email field is required')" oninput="setCustomValidity('')" required>
          </div>

          <div class="form-group">
            <label for="password" class="col-form-label">Password:</label>
            <input type="password" class="form-control" name="password" id="password" oninvalid="this.setCustomValidity('password field is required')" oninput="setCustomValidity('')" required>
          </div>

          <div class="form-group">
            <label for="status" class="col-form-label">Status:</label>
            <select name="status" id="status" class="form-control">
                <option value="1">Enable</option>
                <option value="0">Disable</option>
            </select>
          </div>

          <div class="form-group">
            <label for="roles" class="col-form-label">Roles:</label>
            <select name="roles[]" id="roles" class="form-control" required multiple>
                @foreach ($roles as $role)
                    <option value="{{ $role->name}}">{{ $role->name}}</option>
                @endforeach
            </select>
          </div>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!--user-form-modal end-->

<!-- Page level plugins -->
<script src="{{ asset('vendors/datatables/dataTables.js') }}"></script>
<script src="{{ asset('vendors/datatables/dataTables.bootstrap5.js') }}"></script>

<script>
$(document).ready(function () {

    $('#addUser').click(function(){
        $('#userModalLabel').text("add new user");
        $('#userid').val(0);
        $('#name').val("");
        $('#email').val("");
        $('#password').val("");
        $('#status').val(0);
        $('#roles').val([]);
        $('#userModal').modal('show');
    });

    var table = $('#userTable').DataTable({
        serverSide: true,
        ajax: "{{ route('users') }}",
        columns: [
            { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'status',
                name: 'status',
                    render: function (data) {
                    if (data === 1) {
                        return '<span class="badge me-1 bg-success">Enable</span>';
                    } else {
                        return '<span class="badge me-1 bg-danger">Disable</span>';
                    }
                }
            },
            @can('edit user')
            { data: 'edit', name: 'edit', orderable: false, searchable: false },
            @endcan

            @can('delete user')
            { data: 'delete', name: 'delete', orderable: false, searchable: false },
            @endcan
        ],
        // "order": [[0, "desc"]]
    });

    // Handle the "Check All" checkbox
    $('#check-all').click(function () {
        $('input:checkbox').not(this).prop('checked', this.checked);
    });


    // Handle "Delete Selected" button click
    $('#delete-selected').click(function () {
        var selectedIds = [];
        $('input[name="user_id[]"]:checked').each(function () {
            selectedIds.push($(this).val());
        });

        if (selectedIds.length === 0) {
            // alert("Please select at least one record to delete.");
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please select at least one record to delete.',
            });
        } else {
            
           Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('user.deleteSelected') }}",
                        type: "POST",
                        data: {
                            selectedIds: selectedIds,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function (data) {
                            // Handle success
                            table.ajax.reload();
                            if(data.status == 'success')
                            {
                                Swal.fire(
                                'Deleted!',
                                'user has been deleted.',
                                'success'
                                );
                            }else{
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: data.message,
                                });
                            }
                        },
                        error: function (data) {
                            // Handle error
                            console.log(data);
                        }
                    });
                }
            });

        }
    });  
    

});
</script>


@if (Session::has('success'))
<script>
    Swal.fire(
    'Users!',
    '{{Session::get("success")}}',
    'success'
    );
</script>
@php
Session::forget('success');
@endphp
@endif

@if (Session::has('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: '{{Session::get("error")}}',
    });
</script>
@php
Session::forget('error');
@endphp
@endif

@endsection
