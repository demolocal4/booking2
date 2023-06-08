@extends('admin.layout.app')
@section('page_title', 'Manage Users')
@section('content')
@php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
@endphp
<h3>Unclean - {{$id}}</h3>
<section class="section">
    <div class="card">
        <div class="card-header">
            <div class="col-md-12 col-12 mt-3">
               
            </div>   
        </div>
        <div class="card-body">
        
            <h3>Under Developing</h3>
           
        </div>    
</div>
</section>
<script src="{{asset('js/jquery-3.6.0.min.js')}}"></script>
<script>
    $(document).ready(function(){
        // setTimeout(() => {
        //          $(".alert").slideUp();
        //     }, 3000);
        
    });
</script>
@endsection
        