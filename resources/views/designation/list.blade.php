@extends('layouts.main')


@push('title')
<title>Designation</title>
@endpush

@section('main-section')
<!-- Custom styles for this page -->
<link href="{{ asset('vendors/datatables/dataTables.bootstrap5.css') }}" rel="stylesheet">

<script>
    //open edit model with data
    function editDesignation(id) {
    $.ajax({
            url: "{{ route('designation-edit', ['id' => '']) }}/" + id, // Pass the id here
            type: "GET",
            success: function (data) {
                // Handle success
                $('#designationModalLabel').text("edit designation");
                $('#designationid').val(data.id);
                $('#title').val(data.title);
                $('#status').val(data.status);
                $('#designationModal').modal('show');
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
        <h6 class="m-0 font-weight-bold text-primary">Designation</h6>
        <div>
            @can('add designation')
            <button class="btn btn-primary" id="addDesignation" data-coreui-toggle="modal" data-coreui-target="#designationModal"><i class="fas fa-plus"></i></button>
            @endcan
            @can('delete designation')
            <button id="delete-selected" class="btn btn-danger"><i class="fas fa-trash"></i></button>
            @endcan
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
        <table class="table table-bordered" id="designationTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th><input type="checkbox" id="check-all"></th>
                    <th>Designation</th>
                    <th>Status</th>
                    @can('edit designation')
                    <th>Edit</th>
                    @endcan
                    @can('delete designation')
                    <th>Delete</th>
                    @endcan
                </tr>
            </thead>
        </table>  
        </div>
    </div>
</div>


<!--designation-form-modal -->
<div class="modal fade" id="designationModal" tabindex="-1" role="dialog" aria-labelledby="designationModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="designationModalLabel">Designation form</h5>
        <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('designation-save') }}" method="post">
        <div class="modal-body">
          <div class="form-group">
            @csrf
            <input type="hidden" name="id" id="designationid" value="0">
            <label for="title" class="col-form-label">Designation:</label>
            <input type="text" class="form-control" name="title" id="title" oninvalid="this.setCustomValidity('Designation field is required')" oninput="setCustomValidity('')" required>
          </div>
          <div class="form-group">
            <label for="status" class="col-form-label">Status:</label>
            <select name="status" id="status" class="form-control">
                <option value="1">Enable</option>
                <option value="0">Disable</option>
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
<!--designation-form-modal end-->

<!-- Page level plugins -->
<script src="{{ asset('vendors/datatables/dataTables.js') }}"></script>
<script src="{{ asset('vendors/datatables/dataTables.bootstrap5.js') }}"></script>


<script>
$(document).ready(function () {

    $('#addDesignation').click(function(){
        $('#designationModalLabel').text("add new designation");
        $('#designationid').val(0);
        $('#title').val("");
        $('#status').val(0);
    });

    var table = $('#designationTable').DataTable({
        serverSide: true,
        ajax: "{{ route('designation') }}",
        columns: [
            { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false },
            { data: 'title', name: 'title' },
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
            @can('edit designation')
            { data: 'edit', name: 'edit', orderable: false, searchable: false },
            @endcan
            @can('delete designation')
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
        $('input[name="designation_id[]"]:checked').each(function () {
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
                        url: "{{ route('designation.deleteSelected') }}",
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
                                'Designation has been deleted.',
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
    'Designation!',
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
