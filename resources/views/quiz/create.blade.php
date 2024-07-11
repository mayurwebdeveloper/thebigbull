@extends('layouts.main')


@push('title')
<title>Add Quiz</title>
@endpush


@section('main-section')

<div class="card shadow mb-4">
    <div class="card-header py-3" id="table-card-title">
        <h6 class="m-0 font-weight-bold text-primary">Add Quiz</h6>
    </div>
    <div class="card-body">
        <form method="post" class="row g-3" action="{{ route('add-quiz') }}" enctype="multipart/form-data">
            @csrf
            <div class="col-md-12">
                <label class="form-label" for="quiz_title">Quiz Title.</label>
                <input type="text" class="form-control @error('quiz_title') is-invalid @enderror" id="quiz_title" name="quiz_title" value="{{old('quiz_title')}}" placeholder="Quiz Title">
                @error('quiz_title')
                <div class="invalid-feedback">
                    {{$message}}
                </div>
                @enderror
            </div>

            <div class="col-md-12">
                <label class="form-label" for="quiz_description">Description.</label>
                <textarea name="quiz_description" id="" class="form-control @error('quiz_description') is-invalid @enderror" rows="5" placeholder="Description">{{old('quiz_description')}}</textarea>
                @error('quiz_description')
                <div class="invalid-feedback">
                    {{$message}}
                </div>
                @enderror
            </div>

            <div class="col-md-4">
                <label class="form-label" for="start_date">Quiz Date.</label>
                <input type="date" class="form-control @error('start_date') is-invalid @enderror" name="start_date" id="start_date" value="{{old('start_date')}}" placeholder="Quiz Date">
                @error('start_date')
                <div class="invalid-feedback">
                    {{$message}}
                </div>
                @enderror
            </div>
            <div class="col-md-4">
                <label class="form-label" for="start_time">Start Time.</label>
                <input type="time" class="form-control @error('start_time') is-invalid @enderror" name="start_time" id="start_time" value="{{old('start_time')}}" placeholder="Start Time">
                @error('start_time')
                <div class="invalid-feedback">
                    {{$message}}
                </div>
                @enderror
            </div>

            <div class="col-md-4">
                <label class="form-label" for="total_time">Quiz Total Time. (Minutes)</label>
                <input type="number" min="0" max="1000" class="form-control @error('total_time') is-invalid @enderror" name="total_time" id="total_time" value="{{old('total_time')}}" placeholder="Quiz Total Time">
                @error('total_time')
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

            <hr>

            <h6 class="m-0 font-weight-bold text-primary">Add Questions</h6>

            <hr>

            <div class="container" id="dynamic_field">
                    <div class="col-md-12">
                        <div class="card mb-3">
                            <div class="card-body">

                            <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label" for="question">Question.</label>
                                <input type="text" class="form-control" id="question" name="question" placeholder="Question">
                            </div>

                            <div class="col-md-12">
                                <label class="form-label" for="quiz_description">Description.</label>
                                <textarea name="quiz_description" id="" class="form-control" rows="5" placeholder="Description"></textarea>
                            </div>
                            </div>
                            </div>
                        </div>
                    </div>
            </div>

            <div class="col-12">
                <button type="button" name="add" id="add" class="btn btn-primary mb-4"><i class="fa fa-plus" aria-hidden="true"></i> Add Question</button>
            </div>

            <div class="col-12">
                <button class="btn btn-primary" type="submit">Save</button>
            </div>
        </form>

    </div>
</div>

<script>
    $(document).ready(function() {
        var i = 1;
        $("#add").click(function() {
            i++;
            $('#dynamic_field').append('<tr id="row' + i + '"><td><input type="file" name="documents[]" required class="form-control"/></td><td><input type="text" name="document_titles[]" placeholder="Document title" required class="form-control" /></td><td><button type="button" name="remove" id="' + i + '" class="btn btn-danger btn_remove"><i class="fa fa-trash" aria-hidden="true"></i></button></td></tr>');

        });

        $(document).on('click', '.btn_remove', function() {
            let button_id = $(this).attr("id");
            $('#row' + button_id + '').remove();
        });
    });
</script>


@endsection