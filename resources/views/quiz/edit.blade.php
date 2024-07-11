@extends('layouts.main')


@push('title')
<title>Edit Employee</title>
@endpush

@section('main-section')


<div class="card shadow mb-4">
    <div class="card-header py-3" id="table-card-title">
        <h6 class="m-0 font-weight-bold text-primary">Edit Employee</h6>
    </div>
    <div class="card-body">

        <form method="post" action="{{ route('update-staff') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-row">
                <div class="form-group col-md-4">
                    <input type="hidden" name="staff_id" value="{{ $staff->id }}">
                    <label for="fname">First Name.</label>
                    <input type="text" class="form-control @error('fname') is-invalid @enderror" id="fname" name="fname" value="{{old('fname', $staff->fname)}}" placeholder="First Name">
                    @error('fname')
                    <div class="invalid-feedback">
                    {{$message}}
                    </div>
                    @enderror
                </div>
                <div class="form-group col-md-4">
                    <label for="mname">Middle Name.</label>
                    <input type="text" class="form-control @error('mname') is-invalid @enderror" name="mname" id="mname" value="{{old('mname', $staff->mname)}}" placeholder="Middle Name">
                    @error('mname')
                    <div class="invalid-feedback">
                    {{$message}}
                    </div>
                    @enderror
                </div>
                <div class="form-group col-md-4">
                    <label for="lname">Last Name.</label>
                    <input type="text" class="form-control @error('lname') is-invalid @enderror" name="lname" id="lname" value="{{old('lname', $staff->lname)}}" placeholder="Last Name">
                    @error('lname')
                    <div class="invalid-feedback">
                    {{$message}}
                    </div>
                    @enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="dob">Date of birth.</label>
                    <input type="date" class="form-control @error('dob') is-invalid @enderror" name="dob" id="dob" value="{{old('dob', $staff->DOB)}}" placeholder="Date of birth">
                    @error('dob')
                    <div class="invalid-feedback">
                    {{$message}}
                    </div>
                    @enderror
                </div>
                <div class="form-group col-md-4">
                    <label for="mo">Mobile no.</label>
                    <input type="text" class="form-control @error('mo') is-invalid @enderror" name="mo" id="mo" value="{{old('mo', $staff->mo)}}" placeholder="Mobile no">
                    @error('mo')
                    <div class="invalid-feedback">
                    {{$message}}
                    </div>
                    @enderror
                </div>
                <div class="form-group col-md-4">
                    <label for="status">Status.</label>
                    <select class="form-control @error('status') is-invalid @enderror" name="status" id="status" value="{{old('status', $staff->status)}}" placeholder="Status">
                        <option value="1">Enable</option>
                        <option value="0" @if($staff->status == '0') {{ "selected" }} @endif >Disable</option>
                    </select>
                    @error('status')
                    <div class="invalid-feedback">
                    {{$message}}
                    </div>
                    @enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="designation">Designation.</label>
                    <select class="form-control @error('designation') is-invalid @enderror" name="designation" id="designation" value="{{old('designation')}}" placeholder="Designation">
                        @foreach ($designations as $designation)
                            <option @if(old('designation', $staff->designation_id) == $designation->id) selected @endif value="{{ $designation->id }}">{{ $designation->title }}</option>
                        @endforeach
                    </select>
                    @error('designation')
                    <div class="invalid-feedback">
                    {{$message}}
                    </div>
                    @enderror
                </div>
                <div class="form-group col-md-4">
                    <label for="join_date">Join Date.</label>
                    <input type="date" class="form-control @error('join_date') is-invalid @enderror" name="join_date" id="join_date" value="{{old('join_date', $staff->join_date)}}" placeholder="Join Date">
                    @error('join_date')
                    <div class="invalid-feedback">
                    {{$message}}
                    </div>
                    @enderror
                </div>
                <div class="form-group col-md-4">
                    <label for="salary">Salary.</label>
                    <input type="text" class="form-control @error('salary') is-invalid @enderror" name="salary" id="salary" value="{{old('salary', $staff->salary)}}" placeholder="Salary">
                    @error('salary')
                    <div class="invalid-feedback">
                    {{$message}}
                    </div>
                    @enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="bank">Bank Name.</label>
                    <input type="text" class="form-control @error('bank') is-invalid @enderror" id="bank" name="bank" value="{{old('bank', $staff->bank)}}" placeholder="Bank Name">
                    @error('bank')
                    <div class="invalid-feedback">
                    {{$message}}
                    </div>
                    @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="ac_no">Account no.</label>
                    <input type="text" class="form-control @error('ac_no') is-invalid @enderror" name="ac_no" id="ac_no" value="{{old('ac_no', $staff->ac_no)}}" placeholder="Account no">
                    @error('ac_no')
                    <div class="invalid-feedback">
                    {{$message}}
                    </div>
                    @enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="ifsc_no">IFSC No.</label>
                    <input type="text" class="form-control @error('ifsc_no') is-invalid @enderror" name="ifsc_no" id="ifsc_no" value="{{old('ifsc_no', $staff->ifsc_no)}}" placeholder="IFSC No">
                    @error('ifsc_no')
                    <div class="invalid-feedback">
                    {{$message}}
                    </div>
                    @enderror
                </div>
                <div class="form-group col-md-4">
                    <label for="licence_no">Licence no.</label>
                    <input type="text" class="form-control @error('licence_no') is-invalid @enderror" id="licence_no" name="licence_no" value="{{old('licence_no', $staff->licence_no)}}" placeholder="Licence no">
                    @error('licence_no')
                    <div class="invalid-feedback">
                    {{$message}}
                    </div>
                    @enderror
                </div>
                <div class="form-group col-md-4">
                    <label for="licence_exp_date">Licence expiry date.</label>
                    <input type="date" class="form-control @error('licence_exp_date') is-invalid @enderror" name="licence_exp_date" id="licence_exp_date" value="{{old('licence_exp_date', $staff->licence_exp_date)}}" placeholder="Licence expiry date">
                    @error('licence_exp_date')
                    <div class="invalid-feedback">
                    {{$message}}
                    </div>
                    @enderror
                </div>
                
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="bus">Bus.</label>
                    <select class="form-control @error('bus') is-invalid @enderror" name="bus" id="bus" value="{{old('bus')}}" placeholder="Bus">
                        @foreach ($buses as $bus)
                            <option @if(old('bus', $staff->bus_id) == $bus->id) selected @endif value="{{ $bus->id }}">{{ $bus->vehicle_no }}</option>
                        @endforeach
                    </select>
                    @error('bus')
                    <div class="invalid-feedback">
                    {{$message}}
                    </div>
                    @enderror
                </div>
                <div class="form-group col-md-4">
                    <label for="route">Route.</label>
                    <select class="form-control @error('route') is-invalid @enderror" name="route" id="route" value="{{old('route')}}" placeholder="Route">
                        @foreach ($bus_routes as $route)
                            <option @if(old('route', $staff->route_id) == $route->id) selected @endif value="{{ $route->id }}">{{ $route->title }}</option>
                        @endforeach
                    </select>
                    @error('route')
                    <div class="invalid-feedback">
                    {{$message}}
                    </div>
                    @enderror
                </div>
                <div class="form-group col-md-4">
                    <label for="photo">Photo.</label>
                    <input type="file" class="form-control @error('photo') is-invalid @enderror" name="photo" id="photo" placeholder="Photo">
                    <img src="{!! asset('images/staff/photos/') !!}/{{$staff->photo ?? 'no-img.png'}}" alt="staff-photo" width="100px">
                    @error('photo')
                    <div class="invalid-feedback">
                    {{$message}}
                    </div>
                    @enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="address">Address.</label>
                    <textarea name="address" id="" class="form-control @error('address') is-invalid @enderror" rows="5" placeholder="Address">{{old('address', $staff->address)}}</textarea>
                    @error('address')
                    <div class="invalid-feedback">
                    {{$message}}
                    </div>
                    @enderror
                </div>
            </div>

            <button type="button" name="add" id="add" class="btn btn-primary mb-4"><i class="fa fa-plus" aria-hidden="true"></i> Add Document</button>

            <div class="form-row">
                <div class="container">
                    <div class="row">
                        <div class="col-md-10">
                            <div class="form-group">
                                <table class="table table-bordered table-hover" id="dynamic_field">
                                    <!-- dynamicly add documents field -->
                                    @php
                                    $i = 1;
                                    @endphp
                                    @foreach($staffDocuments as $document)
                                    <tr id="row{{ $i }}">
                                        <td>
                                            <input type="hidden" name="document_id[]" value="{{ $document->id }}" required />
                                            <input type="hidden" name="old_documents[]" value="{{ $document->document }}" required />
                                            {!! \App\Helpers\Helper::docfileType($document->document,'staff') !!}
                                        </td>
                                        <td><input type="text" value="{{ $document->title }}" name="old_document_titles[]" placeholder="title" required class="form-control" /></td>
                                        <td><button type="button" name="remove" id="{{ $i }}" class="btn btn-danger btn_remove"><i class="fa fa-trash" aria-hidden="true"></i></button></td>
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
            <button type="submit" class="btn btn-primary">save</button>
        </form>


    </div>
</div>



<script>

var i = "{{ $documentCount }}";
        $("#add").click(function() {
            i++;
            $('#dynamic_field').append('<tr id="row' + i + '"><td><input type="file" name="documents[]" required class="form-control"/></td><td><input type="text" name="document_titles[]" placeholder="Document title" required class="form-control" /></td><td><button type="button" name="remove" id="' + i + '" class="btn btn-danger btn_remove"><i class="fa fa-trash" aria-hidden="true"></i></button></td></tr>');
        });

        $(document).on('click', '.btn_remove', function() {
            let button_id = $(this).attr("id");
            $('#row' + button_id + '').remove();
        });

</script>

@endsection