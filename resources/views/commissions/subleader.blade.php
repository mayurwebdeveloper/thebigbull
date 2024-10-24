@extends('layouts.main')


@push('title')
<title>Comission List</title>
@endpush

@section('main-section')
<!-- Custom styles for this page -->
<link href="{{ asset('vendors/datatables/dataTables.bootstrap5.css') }}" rel="stylesheet">
<style>
    .nested-list {
        list-style-type: none;
        padding-left: 40px; /* Indentation for nested lists */
    }
    .nested-list li {
        margin: 10px 0;
    }
</style>
<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3" id="table-card-title">
        <h6 class="m-0 font-weight-bold text-primary">Comission</h6>
        
    </div>
    <div class="card-body">
        <ul class="nested-list">
            @php 
               
            @endphp
            @foreach ($subLeadersArray as $user)
                
            <li><strong>Level 1:</strong> 
                <br><strong>{{ $user['name'] }}</strong>
                <br><span> Total Investment: {{ $user['total_investment'] }} </span>
                <br><span> Total Comission: {{ $user['total_commission'] }} </span>
                
                <ul class="nested-list">
                    @foreach ($user['children'] as $child)
                        
                    
                    <li><strong>Level 2:</strong> 
                        <strong>{{ $child['name'] }}</strong>
                        <br><span> Total Investment: {{ $child['total_investment'] }} </span>
                        <br><span> Total Comission: {{ $child['total_commission'] }} </span>
                        <ul class="nested-list">
                            @foreach ($child['children'] as $grandchild)
                                <li><strong>Level 3:</strong>
                                    <strong> {{ $grandchild['name'] }}</strong>
                                    <br><span> Total Investment: {{ $grandchild['total_investment'] }} </span>
                                    <br><span> Total Comission: {{ $grandchild['total_commission'] }} </span>
                        
                                </li>                               
                            @endforeach
                        </ul>
                    </li>
                    @endforeach
                    
                </ul>
            </li>
            
            @endforeach
        </ul>
    </div>
</div>


@endsection
