@extends('layouts.main')


@push('title')
<title>Roles</title>
@endpush

@section('main-section')
<!-- Custom styles for this page -->
<link href="{{ asset('vendors/datatables/dataTables.bootstrap5.css') }}" rel="stylesheet">

<script>
    //open edit model with data
    function editRole(id) {
    $.ajax({
            url: "{{ route('role-edit', ['id' => '']) }}/" + id, // Pass the id here
            type: "GET",
            success: function (data) {
                // Handle success
                $('#roleModalLabel').text("edit role");
                $('#roleid').val(data.id);
                $('#title').val(data.name);
                $('#roleModal').modal('show');
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
        <h6 class="m-0 font-weight-bold text-primary">Roles</h6>
        <div>
            @can('add role')
            <button class="btn btn-primary" id="addRole" data-coreui-toggle="modal" data-coreui-target="#roleModal"><i class="fas fa-plus"></i></button>
            @endcan
            @can('delete role')            
            <button id="delete-selected" class="btn btn-danger"><i class="fas fa-trash"></i></button>
            @endcan
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
        <table class="table table-bordered" id="roleTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th><input type="checkbox" id="check-all"></th>
                    <th>Role</th>
                    @can('assign role-permissions')
                    <th>Permissions</th>
                    @endcan
                    @can('edit role')
                    <th>Edit</th>
                    @endcan
                    @can('delete role')
                    <th>Delete</th>
                    @endcan
                </tr>
            </thead>
        </table>  
        </div>
    </div>
</div>


<!--role-form-modal -->
<div class="modal fade" id="roleModal" tabindex="-1" role="dialog" aria-labelledby="roleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="roleModalLabel">role form</h5>
        <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('role-save') }}" method="post">
        <div class="modal-body">
          <div class="form-group">
            @csrf
            <input type="hidden" name="id" id="roleid" value="0">
            <label for="title" class="col-form-label">Role:</label>
            <input type="text" class="form-control" name="title" id="title" oninvalid="this.setCustomValidity('Role field is required')" oninput="setCustomValidity('')" required>
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
<!--role-form-modal end-->

<!-- Page level plugins -->
<script src="{{ asset('vendors/datatables/dataTables.js') }}"></script>
<script src="{{ asset('vendors/datatables/dataTables.bootstrap5.js') }}"></script>

<script>
$(document).ready(function () {

    $('#addRole').click(function(){
        $('#roleModalLabel').text("add new role");
        $('#roleid').val(0);
        $('#title').val("");
    });

    var table = $('#roleTable').DataTable({
        serverSide: true,
        ajax: "{{ route('roles') }}",
        columns: [
            { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false },
            { data: 'name', name: 'title' },
            @can('assign role-permissions')
            { data: 'permissions', name: 'permissions', orderable: false, searchable: false },
            @endcan
            
            @can('edit role')
            { data: 'edit', name: 'edit', orderable: false, searchable: false },
            @endcan
            @can('delete role')
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
        $('input[name="role_id[]"]:checked').each(function () {
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
                        url: "{{ route('role.deleteSelected') }}",
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
                                'Role has been deleted.',
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
    'Roles!',
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
