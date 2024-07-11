@extends('layouts.main')


@push('title')
<title>Dashboard</title>
@endpush


@section('main-section')

<div class="body flex-grow-1">
    <div class="container-lg px-4">
    <div class="row g-4 mb-4">
       
        <div class="col-sm-6 col-xl-3">
        <a href="{{ route('investment') }}" class="text-decoration-none">
          <div class="card text-white bg-primary">
            <div class="card-body pb-0 mb-3 d-flex justify-content-between align-items-start">
              <div>
                <div class="fs-4 fw-semibold">{{ $totalInvestment }}</div>
                <div>Total Investment</div>
              </div>
            </div>
          </div>
        </a>
        </div>

        <div class="col-sm-6 col-xl-3">
        <a href="{{ route('leader') }}" class="text-decoration-none">
          <div class="card text-white bg-info">
            <div class="card-body pb-0 mb-3 d-flex justify-content-between align-items-start">
              <div>
                <div class="fs-4 fw-semibold">{{ $totalUsersWithRole }}</div>
                <div>Total Leaders</div>
              </div>
            </div>
          </div>
        </a>
        </div>


        {{--  <div class="col-sm-6 col-xl-3">
        <a href="https://yuvapahel.com/quiz" class="text-decoration-none">
          <div class="card text-white bg-warning">
            <div class="card-body pb-0 mb-3 d-flex justify-content-between align-items-start">
              <div>
                <div class="fs-4 fw-semibold">12</div>
                <div>Quizzes</div>
              </div>
            </div>
          </div>
        </a>
        </div>

        <div class="col-sm-6 col-xl-3">
        <a href="https://yuvapahel.com/enquiries" class="text-decoration-none">
          <div class="card text-white bg-danger">
            <div class="card-body pb-0 mb-3 d-flex justify-content-between align-items-start">
              <div>
                <div class="fs-4 fw-semibold">1</div>
                <div>Notifications</div>
              </div>
            </div>
          </div>
        </a>
        </div>  --}}

    </div>
{{--  
    <div class="row g-4 mb-4">
        <div class="col-sm-12 col-lg-6">
          <div class="card">
            <div class="card-header position-relative d-flex justify-content-center align-items-center">
                <h6 class="card-title mb-0">Current Month Participation</h6>
            </div>
            <div class="card-body row text-center">
                <canvas id="quizParticipationChart" height="300" style="display: block; box-sizing: border-box; height: 300px; width: 100%;"></canvas> 
            </div>
          </div>
        </div>

        <div class="col-sm-12 col-lg-6">
          <div class="card">
            <div class="card-header position-relative d-flex justify-content-center align-items-center">
                <h6 class="card-title mb-0">Participation by Designation</h6>
            </div>
            <div class="card-body row text-center" style="height:330px;">
                <canvas id="quizParticipationByDesignationChart" ></canvas> 
            </div>
          </div>
        </div>

    </div>  --}}

</div>
</div>

@endsection