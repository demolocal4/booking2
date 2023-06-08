<?php 

use Illuminate\Support\Facades\DB;

$access = DB::table('role_access')->where('levelType', session()->get('role_id'))->first();

?>

@extends('admin.layout.app')

@section('page_title', 'Manage Users')

@section('content')



<h3>Manage Users</h3>



<section class="section">

    <div class="card">

        <div class="card-header">

            @if($access->canAdd == 1)

            <a href="{{url('manage_users/create')}}" class="btn btn-primary float-end">New User</a>

            @endif

            @if(Session::get('success'))

            <div class="alert alert-success">

                {{ Session::get('success') }}

            </div>

            @endif

                    

        </div>

        <div class="card-body">

            <table class="table table-striped" id="table1">

                <thead>

                    <tr>

                        <th>Photo</th>

                        <th>Full Name</th>

                        <th>Username</th>

                        <th>Mobile</th>

                        <th>Email</th>

                        <th>Branch</th>

                        <th>Role</th>

                        <th>Status</th>

                        <th>Created at</th>

                        <th width="130">Action</th>

                    </tr>

                </thead>

                <tbody>

                    

                   @foreach($users as $list)

                    <tr>

                        <td>

                        <div class="avatar avatar-xl">

                        <img src="{{asset('uploads/'.$list->profile_pic)}}" alt="Photo" />

                        </div>

                        </td>

                        <td>{{$list->fullname}}</td>

                        <td>{{$list->name}}</td>

                        <td><a href="tel:{{$list->mobile}}">{{$list->mobile}}</a></td>

                        <td><a href="mailto:{{$list->email}}">{{$list->email}}</a></td>

                        <td>

                            @php

                            $branch = DB::table('branchs')->where('brCode', $list->brCode)->first();

                            @endphp

                            {{$branch->brName}}

                        </td>

                        <td>

                            @php

                            $roles = DB::table('userlevels')->where('id', $list->role)->first();

                            @endphp

                            {{$roles->levelType}}

                        </td>

                        <td>

                            @if($list->status == 1)

                            <span class="badge bg-success">Active</span>

                            @else

                            <span class="badge bg-danger">Inactive</span>

                            @endif

                        </td>

                        <td>{{$list->created_date}}</td>

                        <td>

                            @if($access->canEdit == 1)

                            <a href="{{url('manage_users/'.$list->id.'/edit')}}" class="btn btn-secondary btn-sm">Edit</a>

                            @endif 

                            @if($access->canDelete == 1)   

                            <a href="javascript:void(0);" class="btn btn-danger btn-sm user_delete">
                            <!-- <a href="javascript:void(0);" class="btn btn-danger btn-sm" onclick="$(this).find('form').submit();"> -->

                                <form method="POST" action="{{url('manage_users/'.$list->id)}}">

                                    @csrf

                                    {{-- @method('DELETE')    --}}

                                    <input type="hidden" name="_method" value="DELETE">

                                </form>      

                            Delete

                            </a>

                            @endif

                        </td>

                    </tr>

                    @endforeach



                    {{-- <tr>

                        <td>Nathaniel</td>

                        <td>mi.Duis@diam.edu</td>

                        <td>(012165) 76278</td>

                        <td>Grumo Appula</td>

                        <td>

                            <span class="badge bg-danger">Inactive</span>

                        </td>

                    </tr> --}}

                    

                </tbody>

            </table>
            <div class="row">
                <div class="col-12" style="display:flex;justify-content:center;">
                    {{ $users->links('pagination::bootstrap-4') }}
                </div>
            </div>

        </div>

    </div>



</section>



@endsection