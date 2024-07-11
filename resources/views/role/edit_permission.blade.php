@extends('layouts.main')


@push('title')
<title>Edit Permission</title>
@endpush

@section('main-section')


<div class="card shadow mb-4">
    <div class="card-header py-3" id="table-card-title">
        <h6 class="m-0 font-weight-bold text-primary">({{ $role->name }}) Permissions</h6>
    </div>
    <div class="card-body">

        <form method="post" action="{{ route('update-role-permission') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="role_id" value="{{ $role->id }}">

            <div class="row"> 
                <div class="form-group col-md-4">
                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" value="" id="checkAll">
                        <label class="form-check-label" for="checkAll">
                            Check All
                        </label>
                    </div>
                </div>
            </div>

            <div class="row">
                @foreach ($permissions as $permission)    
                <div class="form-group col-md-4">
                    <div class="form-check">
                        <input class="form-check-input" name="permissions[]" type="checkbox" value="{{ $permission->name }}" id="{{ $permission->id }}" {{ in_array($permission->id, $role_permissions) ? "checked" : "" }}>
                        <label class="form-check-label" for="{{ $permission->id }}">
                            {{$permission->name}}
                        </label>
                    </div>
                </div>
                @endforeach
            </div>

            <button type="submit" class="btn btn-primary mt-4">save</button>
        </form>

    </div>
</div>


<script>
    $(document).ready(function() {
        $('#checkAll').change(function() {
            $('input[name="permissions[]"]').prop('checked', $(this).prop('checked'));
        });
    });
</script>


@endsection