@extends('layouts.main')


@push('title')
<title>Add Staff</title>
@endpush


@section('main-section')

<div class="card shadow mb-4">
    <div class="card-header py-3" id="table-card-title">
        <h6 class="m-0 font-weight-bold text-primary">Add Staff</h6>
    </div>
    <div class="card-body">
        <form method="post" class="row g-3" action="{{ route('add-staff') }}" enctype="multipart/form-data">
            @csrf
                <div class="col-md-4">
                    <label class="form-label" for="fname">First Name.</label>
                    <input type="text" class="form-control @error('fname') is-invalid @enderror" id="fname" name="fname" value="{{old('fname')}}" placeholder="First Name">
                    @error('fname')
                    <div class="invalid-feedback">
                    {{$message}}
                    </div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="lname">Last Name.</label>
                    <input type="text" class="form-control @error('lname') is-invalid @enderror" name="lname" id="lname" value="{{old('lname')}}" placeholder="Last Name">
                    @error('lname')
                    <div class="invalid-feedback">
                    {{$message}}
                    </div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="dob">Date of birth.</label>
                    <input type="date" class="form-control @error('dob') is-invalid @enderror" name="dob" id="dob" value="{{old('dob')}}" placeholder="Date of birth">
                    @error('dob')
                    <div class="invalid-feedback">
                    {{$message}}
                    </div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="mo">Mobile no.</label>
                    <input type="text" class="form-control @error('mo') is-invalid @enderror" name="mo" id="mo" value="{{old('mo')}}" placeholder="Mobile no">
                    @error('mo')
                    <div class="invalid-feedback">
                    {{$message}}
                    </div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="email">Email.</label>
                    <input type="text" class="form-control @error('email') is-invalid @enderror" name="email" id="email" value="{{old('email')}}" placeholder="Email">
                    @error('email')
                    <div class="invalid-feedback">
                    {{$message}}
                    </div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="designation">Designation.</label>
                    <select class="form-control @error('designation') is-invalid @enderror" name="designation" id="designation" value="{{old('designation')}}" placeholder="Designation">
                        @foreach ($designations as $designation)
                            <option value="{{ $designation->id }}">{{ $designation->title }}</option>
                        @endforeach
                    </select>
                    @error('designation')
                    <div class="invalid-feedback">
                    {{$message}}
                    </div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="join_date">Join Date.</label>
                    <input type="date" class="form-control @error('join_date') is-invalid @enderror" name="join_date" id="join_date" value="{{old('join_date')}}" placeholder="Join Date">
                    @error('join_date')
                    <div class="invalid-feedback">
                    {{$message}}
                    </div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="salary">Salary.</label>
                    <input type="text" class="form-control @error('salary') is-invalid @enderror" name="salary" id="salary" value="{{old('salary')}}" placeholder="Salary">
                    @error('salary')
                    <div class="invalid-feedback">
                    {{$message}}
                    </div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="password">Password.</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="password" value="{{old('password', '12345')}}" placeholder="Password">
                    @error('password')
                    <div class="invalid-feedback">
                    {{$message}}
                    </div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="status">Status.</label>
                    <select class="form-control @error('status') is-invalid @enderror" name="status" id="status" value="{{old('status')}}" placeholder="Status">
                        <option value="1">Enable</option>
                        <option value="0">Disable</option>
                    </select>
                    @error('status')
                    <div class="invalid-feedback">
                    {{$message}}
                    </div>
                    @enderror
                </div>

                <div class="col-md-12">
                    <label class="form-label" for="photo">Photo.</label>
                    <input type="file" class="form-control @error('photo') is-invalid @enderror" name="photo" id="photo" placeholder="Photo">
                    @error('photo')
                    <div class="invalid-feedback">
                    {{$message}}
                    </div>
                    @enderror
                </div>

                <div class="col-md-12">
                    <label class="form-label" for="address">Address.</label>
                    <textarea name="address" id="" class="form-control @error('address') is-invalid @enderror" rows="5" placeholder="Address">{{old('address')}}</textarea>
                    @error('address')
                    <div class="invalid-feedback">
                    {{$message}}
                    </div>
                    @enderror
                </div>

                <div class="col-12">
                    <button class="btn btn-primary" type="submit">Save</button>
                </div>
        </form>

    </div>
</div>
@endsection