<?php 
use Illuminate\Support\Facades\DB;
?>
@extends('admin.layout.app')
@section('page_title', 'Users Tracking Report')
@section('content')
<h3>Users Tracking Report</h3>
<section class="section">
    <div class="card">
        <div class="card-header">
        <form action="{{ url('user_tracking_filter') }}" method="GET">
        <div class="row">
            <div class="col-md-3 col-12">
                <div class="form-group">
                    <label for="filter_by">Filer by</label>
                    <select name="filter_by" id="filter_by" class="form-select" required>
                        <option value="" hidden selected>Select from list</option>
                        <option value="userId"
                        {{ request('filter_by') == 'userId' ? 'selected' : '' }}
                        >User Id</option>
                        <option value="userName"
                        {{ request('filter_by') == 'userName' ? 'selected' : '' }}
                        >Username</option>
                        <option value="brCode"
                        {{ request('filter_by') == 'brCode' ? 'selected' : '' }}
                        >Branch Code</option>
                        <option value="loginDate"
                        {{ request('filter_by') == 'loginDate' ? 'selected' : '' }}
                        >Login Date</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="form-group">
                    <label for="find_what">Find What?</label>
                    <input type="text" name="find_what" id="find_what" class="form-control" value="{{ request('find_what') ?? '' }}" required>
                </div>
            </div>
            <div class="col-md-1 col-12">
                <div class="form-group">
                    <label for="" style="opacity: 0;">submit</label>
                    <button type="submit" class="btn btn-primary" style="width: 100%;">Filter</button>
                </div>
            </div>
            <div class="col-md-2 col-12">
                <div class="form-group">
                    <label for="" style="opacity: 0;">Clear Button</label>
                    <button type="button" class="btn btn-warning btn_clear">Clear Filter</button>
                </div>
            </div>
        </div>
        </form>    
        </div>
        <div class="card-body">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">User Id</th>
                        <th>Username</th>
                        <th>Login Date</th>
                        <th class="text-center">Login Time</th>
                        <th class="text-center">Logout Time</th>
                        <th class="text-center">Br.Code</th>
                        <th>Br.Name</th>
                        <th class="text-center">IP</th>
                    </tr>
                </thead>
                <tbody>
                   @foreach($data as $list)
                    <tr>
                        <td class="text-center">{{$list->userId}}</td>
                        <td>{{$list->userName}}</td>
                        <td>{{$list->loginDate}}</td>
                        <td class="text-center">{{$list->loginTime}}</td>
                        <td class="text-center">{{$list->logoutTime}}</td>
                        <td class="text-center">{{$list->brCode}}</td>
                        <td>{{$list->brName}}</td>
                        <td class="text-center">{{$list->iP}}</td>
                    </tr>
                    @endforeach
                </tbody>

            </table>
            <div class="row">
                <div class="col-12" style="display:flex;justify-content:center;">
                    {{ $data->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
<script src="{{asset('js/jquery-3.6.0.min.js')}}"></script>
<script>
    $(document).ready(function(){
        $(".btn_clear").on("click",function(){
            window.location.href = '{{ url("user_tracking") }}';
        });

        $("#filter_by").on("change",function(){
            let val = $(this).val();
            if(val == "loginDate") {
                $("#find_what").attr("placeholder", "DD-MM-YYYY");
            }else{
                $("#find_what").attr("placeholder", "");
            }

        });
    });
</script>