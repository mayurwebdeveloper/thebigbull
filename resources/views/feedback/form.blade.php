@extends('layouts.main')


@push('title')
<title>Investment List</title>
@endpush

@section('main-section')

<link href="{{ asset('vendors/datatables/dataTables.bootstrap5.css') }}" rel="stylesheet">

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
        <form action="{{ route('feedback.store') }}" method="post">
                @csrf
            <div class="form-group">
                <label for="name" class="col-form-label">Name:</label>
                <input type="text" class="form-control" name="name" id="name" oninvalid="this.setCustomValidity('Name field is required')" oninput="setCustomValidity('')" required>
            </div>

            <div class="form-group">
                <label for="name" class="col-form-label">Email:</label>
                <input type="email" class="form-control" name="email" id="email" oninvalid="this.setCustomValidity('Email field is required')" oninput="setCustomValidity('')" required>
            </div>

            <div class="form-group">
                <label for="phone" class="col-form-label">Phone Number:</label>
                <input type="tel" class="form-control" name="phone" id="phone" oninvalid="this.setCustomValidity('Phone field is required')" oninput="setCustomValidity('')" required>
            </div>

            <div class="form-group">
                <label for="name" class="col-form-label">Message:</label>
                <textarea name="message"  class="form-control" id="message"></textarea>
            </div>
        
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
          </form>
    </div>
</div>



@endsection