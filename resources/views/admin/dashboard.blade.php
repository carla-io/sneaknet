@extends('admin.layouts.template')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">SneakTech /</span> Charts </h4>
    <!-- Basic Bootstrap Table -->
    <div class="card">
    
        <div class="col-lg-4 col-md-6">

            <div class="mt-3">

            <canvas id="salesChart" width="400" height="200"></canvas>
        
           

            </div>
        </div>
     </div>
</div>



@endsection
