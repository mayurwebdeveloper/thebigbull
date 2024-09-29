@extends('layouts.main')


@push('title')
<title>Comission List</title>
@endpush

@section('main-section')
<!-- Custom styles for this page -->
<link href="{{ asset('vendors/datatables/dataTables.bootstrap5.css') }}" rel="stylesheet">


<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3" id="table-card-title">
        <h6 class="m-0 font-weight-bold text-primary">Comission</h6>
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
        <form method="GET" action="{{ route('comission') }}" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <input type="text" name="username" class="form-control" placeholder="Username" value="{{ request('username') }}">
                </div>
                <div class="col-md-3">
                    <input type="number" name="year" class="form-control" placeholder="Year" value="{{ request('year') }}">
                </div>
                <div class="col-md-3">
                    <input type="number" name="month" class="form-control" placeholder="Month" value="{{ request('month') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </div>
        </form>
        <div class="table-responsive">
        <table class="table table-bordered" id="userTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                  
                    <th>Leader Name</th>
                    <th>Comission Amount</th>
                    <th>Month/Year</th>
                    
                </tr>
              
                


            </thead>

            <tbody>
                @foreach ($monthlyCommissions as $comission)
                <tr>
                    <td>{{ $comission->username }}</td>
                    <td>{{ $comission->total_commission }}</td>
                    <td>{{ $comission->month }} / {{ $comission->year }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>  
        </div>
    </div>
</div>




<!-- Page level plugins -->
<script src="{{ asset('vendors/datatables/dataTables.js') }}"></script>
<script src="{{ asset('vendors/datatables/dataTables.bootstrap5.js') }}"></script>

<script>
    $(document).ready(function () {
    
       
        var table = $('#userTable').DataTable();
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