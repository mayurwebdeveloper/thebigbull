@extends('layouts.main')


@push('title')
<title>Employee</title>
@endpush

@section('main-section')



<div class="card shadow mb-4">
    <div class="card-header py-3" id="table-card-title">
        <h6 class="m-0 font-weight-bold text-primary">View Employee Details</h6>
    </div>

    <div class="card-body">

    @csrf
    <div class="form-row">
        <div class="form-group col-md-4">
            <label for="fname">First Name.</label>
            <input type="text" class="form-control" value="{{$staff->fname}}" readonly disabled placeholder="First Name">
        </div>
        <div class="form-group col-md-4">
            <label for="mname">Middle Name.</label>
            <input type="text" class="form-control" value="{{$staff->mname}}" readonly disabled placeholder="Middle Name">
        </div>
        <div class="form-group col-md-4">
            <label for="lname">Last Name.</label>
            <input type="text" class="form-control" value="{{$staff->lname}}" readonly disabled placeholder="Last Name">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-4">
            <label for="dob">Date of birth.</label>
            <input type="date" class="form-control" value="{{$staff->DOB}}" readonly disabled placeholder="Date of birth">
        </div>
        <div class="form-group col-md-4">
            <label for="mo">Mobile no.</label>
            <input type="text" class="form-control" readonly disabled placeholder="Mobile no">
        </div>
        <div class="form-group col-md-4">
            <label for="status">Status.</label>
            <select class="form-control" readonly disabled placeholder="Status">
                <option value="1">Enable</option>
                <option value="0" @if($staff->status == '0') {{ "selected" }} @endif >Disable</option>
            </select>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-4">
            <label for="designation">Designation.</label>
            <select class="form-control" readonly disabled placeholder="Designation">
                @foreach ($designations as $designation)
                    <option @if($staff->designation_id == $designation->id) selected @endif value="{{ $designation->id }}">{{ $designation->title }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-4">
            <label for="join_date">Join Date.</label>
            <input type="date" class="form-control"  value="{{$staff->join_date}}" readonly disabled placeholder="Join Date">
        </div>
        <div class="form-group col-md-4">
            <label for="salary">Salary.</label>
            <input type="text" class="form-control" value="{{$staff->salary}}" readonly disabled placeholder="Salary">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="bank">Bank Name.</label>
            <input type="text" class="form-control" value="{{$staff->bank}}" readonly disabled placeholder="Bank Name">
        </div>
        <div class="form-group col-md-6">
            <label for="ac_no">Account no.</label>
            <input type="text" class="form-control" value="{{$staff->ac_no}}" readonly disabled placeholder="Account no">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-4">
            <label for="ifsc_no">IFSC No.</label>
            <input type="text" class="form-control" value="{{$staff->ifsc_no}}" readonly disabled placeholder="IFSC No">
            
        </div>
        <div class="form-group col-md-4">
            <label for="licence_no">Licence no.</label>
            <input type="text" class="form-control" value="{{$staff->licence_no}}" readonly disabled placeholder="Licence no">
        </div>
        <div class="form-group col-md-4">
            <label for="licence_exp_date">Licence expiry date.</label>
            <input type="date" class="form-control" value="{{$staff->licence_exp_date}}" readonly disabled placeholder="Licence expiry date">
        </div>
        
    </div>
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="bus">Bus.</label>
            <select class="form-control" readonly disabled placeholder="Bus">
                @foreach ($buses as $bus)
                    <option @if($staff->bus_id == $bus->id) selected @endif value="{{ $bus->id }}">{{ $bus->vehicle_no }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-6">
            <label for="route">Route.</label>
            <select class="form-control" readonly disabled placeholder="Route">
                @foreach ($bus_routes as $route)
                    <option @if($staff->route_id == $route->id) selected @endif value="{{ $route->id }}">{{ $route->title }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-12">
            <label for="address">Address.</label>
            <textarea class="form-control" rows="5" readonly disabled placeholder="Address">{{ $staff->address }}</textarea>
        </div>
    </div>


    <div class="form-row">
        <div class="container">
            <div class="row">
                <div class="col-md-10">
                    <div class="form-group">
                        <table class="table table-bordered table-hover">
                            <tr>
                                <th>Document</th>
                                <th>Title</th>
                            </tr>
                            <tr>
                                <td><img src="{!! asset('images/staff/photos/') !!}/{{$staff->photo ?? 'no-img.png'}}" alt="staff-photo" width="100px"></td>
                                <td>Staff Photo</td>
                            </tr>
                            <!-- dynamicly add documents field -->
                            @php
                            $i = 1;
                            @endphp
                            @foreach($staffDocuments as $document)
                            <tr id="row{{ $i }}">
                                <td>
                                    {!! \App\Helpers\Helper::docfileType($document->document,'staff') !!}
                                </td>
                                <td><input type="text" value="{{ $document->title }}" name="old_document_titles[]" readonly disabled placeholder="title" required class="form-control" /></td>
                            </tr>
                            @php
                            $i = $i+1;
                            @endphp
                            @endforeach
                            <!-- dynamicly add documents field end-->
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>

</div>


@endsection