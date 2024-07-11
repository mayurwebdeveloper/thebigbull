@extends('layouts.main')


@push('title')
<title>Quiz</title>
@endpush

@section('main-section')
<!-- Custom styles for this page -->
<link href="{{ asset('vendors/datatables/dataTables.bootstrap5.css') }}" rel="stylesheet">

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3" id="table-card-title">
        <h6 class="m-0 font-weight-bold text-primary">Quiz</h6>
        <div>
            @can('add quiz')
            <a class="btn btn-primary" href="{{ route('add-quiz-form') }}"><i class="fas fa-plus"></i></a>
            @endcan
            @can('delete quiz')
            <button id="delete-selected" class="btn btn-danger"><i class="fas fa-trash"></i></button>
            @endcan
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
        <table class="table table-bordered" id="quizTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th><input type="checkbox" id="check-all"></th>
                    <th>Title</th>
                    <th>Status</th>
                    @can('view quizzes')
                    <th>View</th>
                    @endcan
                    @can('edit quiz')
                    <th>Edit</th>
                    @endcan
                    @can('delete quiz')
                    <th>Delete</th>
                    @endcan
                </tr>
            </thead>
        </table>  
        </div>
    </div>
</div>


<!-- Page level plugins -->
<script src="{{ asset('vendors/datatables/dataTables.js') }}"></script>
<script src="{{ asset('vendors/datatables/dataTables.bootstrap5.js') }}"></script>

<script>
$(document).ready(function () {

    var table = $('#quizTable').DataTable({
        serverSide: true,
        ajax: "{{ route('quizzes') }}",
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
            @can('view quizzes')
            { data: 'view', name: 'view', orderable: false, searchable: false },
            @endcan
            @can('edit quiz')
            { data: 'edit', name: 'edit', orderable: false, searchable: false },
            @endcan
            @can('delete quiz')
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
        $('input[name="quiz_id[]"]:checked').each(function () {
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
                        url: "{{ route('quiz.deleteSelected') }}",
                        type: "POST",
                        data: {
                            selectedIds: selectedIds,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function (data) {
                            // Handle success
                            table.ajax.reload();
                        },
                        error: function (data) {
                            // Handle error
                            console.log(data);
                        }
                    });
                    Swal.fire(
                    'Deleted!',
                    'Quiz has been deleted.',
                    'success'
                    );
                }
            });

        }
    });  
    

});
</script>

@if (Session::has('success'))
<script>
    Swal.fire(
    'Quiz!',
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
        text: 'Something went wrong !!!',
    });
</script>
@php
Session::forget('error');
@endphp
@endif



@endsection