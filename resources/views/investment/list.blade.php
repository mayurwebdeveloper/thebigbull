@extends('layouts.main')


@push('title')
<title>Investment List</title>
@endpush

@section('main-section')
<!-- Custom styles for this page -->
<link href="{{ asset('vendors/datatables/dataTables.bootstrap5.css') }}" rel="stylesheet">

<script>
    //open edit model with data
    function editUser(id) {
    $.ajax({
            url: "{{ route('edit-investment-form', ['id' => '']) }}/" + id, // Pass the id here
            type: "GET",
            success: function (data) {
                // Handle success
                $('#userModalLabel').text("edit Investment");
                $('#user_id').val(data.user_id);
                $('#investment_id').val(data.investment_id);
                $('#investment_amount').val(data.investment_amount);
                console.log(data); // Check the value

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
            @can('add investment')
            <button class="btn btn-primary" id="addUser" data-toggle="modal" data-target="#userModal"><i class="fas fa-plus"></i></button>
            @endcan

            @can('delete investment')
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
                    <th>Leader Name</th>
                    <th>Investment Amount</th>
                    <th>Status</th>
                    @can('edit investment')
                    <th>Edit</th>
                    @endcan
                    @can('delete investment')
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
        <h5 class="modal-title" id="userModalLabel">Investment Form</h5>
        <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('add-investment') }}" method="post">
        <div class="modal-body">
          <div class="form-group">
            @csrf
            <input type="hidden" name="id" id="investment_id" value="0">

            <div class="form-group">
                <label for="user_id" class="col-form-label">Leader:</label>
                <select class="form-control" name="user_id" id="user_id" >
                    @foreach ($leaders as $leader)
                        <option value="{{ $leader->id }}">{{ $leader->name }}</option>    
                    @endforeach
                </select>
              </div>
            <label for="name" class="col-form-label">Investment Amount:</label>
            <input type="text" class="form-control" name="investment_amount" id="investment_amount" oninvalid="this.setCustomValidity('Investment Amount field is required')" oninput="setCustomValidity('')" required>
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
            $('#investment_id').val(0);
            $('#userModal').modal('show');
        });
    
        var table = $('#userTable').DataTable({
            serverSide: true,
            ajax: "{{ route('investment') }}",
            columns: [
                { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false },
                { data: 'user_name', name: 'name' },
                { data: 'investment_amount', name: 'investment_amount' },
                { data: 'created_at',name: 'created'},
                @can('edit investment')
                { data: 'edit', name: 'edit', orderable: false, searchable: false },
                @endcan
    
                @can('delete investment')
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
                            url: "{{ route('investment.deleteSelected') }}",
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
                                    'leader has been deleted.',
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