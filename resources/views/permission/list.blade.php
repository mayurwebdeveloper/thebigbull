@extends('layouts.main')


@push('title')
<title>Permissions</title>
@endpush

@section('main-section')
<!-- Custom styles for this page -->
<link href="{{ asset('vendors/datatables/dataTables.bootstrap5.css') }}" rel="stylesheet">

<script>
    //open edit model with data
    function editPermission(id) {
    $.ajax({
            url: "{{ route('permission-edit', ['id' => '']) }}/" + id, // Pass the id here
            type: "GET",
            success: function (data) {
                // Handle success
                $('#permissionModalLabel').text("edit permission");
                $('#permissionid').val(data.id);
                $('#title').val(data.name);
                $('#permissionModal').modal('show');
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
        <h6 class="m-0 font-weight-bold text-primary">Permissions</h6>
        <div>
            <button class="btn btn-primary" id="addPermission" data-coreui-toggle="modal" data-coreui-target="#permissionModal"><i class="fas fa-plus"></i></button>
            <button id="delete-selected" class="btn btn-danger"><i class="fas fa-trash"></i></button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
        <table class="table table-bordered" id="permissionTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th><input type="checkbox" id="check-all"></th>
                    <th>Permission</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
        </table>  
        </div>
    </div>
</div>


<!--permission-form-modal -->
<div class="modal fade" id="permissionModal" tabindex="-1" role="dialog" aria-labelledby="permissionModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="permissionModalLabel">permission form</h5>
        <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('permission-save') }}" method="post">
        <div class="modal-body">
          <div class="form-group">
            @csrf
            <input type="hidden" name="id" id="permissionid" value="0">
            <label for="title" class="col-form-label">Permission:</label>
            <input type="text" class="form-control" name="title" id="title" oninvalid="this.setCustomValidity('Permission field is required')" oninput="setCustomValidity('')" required>
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
<!--permission-form-modal end-->

<!-- Page level plugins -->
<script src="{{ asset('vendors/datatables/dataTables.js') }}"></script>
<script src="{{ asset('vendors/datatables/dataTables.bootstrap5.js') }}"></script>

<script>
$(document).ready(function () {

    $('#addPermission').click(function(){
        $('#permissionModalLabel').text("add new permission");
        $('#permissionid').val(0);
        $('#title').val("");
    });

    var table = $('#permissionTable').DataTable({
        serverSide: true,
        ajax: "{{ route('permissions') }}",
        columns: [
            { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false },
            { data: 'name', name: 'name' , orderable: true, searchable: true},
            { data: 'edit', name: 'edit', orderable: false, searchable: false },
            { data: 'delete', name: 'delete', orderable: false, searchable: false },
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
        $('input[name="permission_id[]"]:checked').each(function () {
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
                        url: "{{ route('permission.deleteSelected') }}",
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
                                'Permission has been deleted.',
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
    'Permissions!',
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
