@extends('admin.layout.app')



@section('page_title', 'Update User')



@section('content')



@php



$role = DB::table('userlevels')->get();



$branch = DB::table('branchs')->get();



@endphp



<h3>Edit User</h3>



<div class="row">



    <div class="col-md-12 col-12">



        <div class="card">



            <div class="card-header">



                {{-- <h4 class="card-title">Vertical Form</h4> --}}



                @if(Session::get('success'))



                        <div class="alert alert-success">



                        <i class="bi bi-check-circle"></i> 



                        {{ Session::get('success') }}



                        </div>



                    @endif



                    @if(Session::get('fail'))



                        <div class="alert alert-danger">



                        <i class="bi bi-check-circle"></i> 



                        {{ Session::get('fail') }}



                        </div>



                    @endif







            </div>



            <div class="card-content">



                <div class="card-body">



                    <form  method="POST" class="form form-vertical" action="{{url('manage_users/'.$userslist->id)}}">



                        @csrf



                        @method('PUT')



                        <div class="form-body">



                            <div class="row">



                                <div class="col-md-6 col-12">



                                    <div class="form-group">



                                        <label for="fullname">Full Name</label>



                                        <input type="text" id="fullname" class="form-control" name="fullname" value="{{ $userslist->fullname }}">



                                        @error('fullname')



                                        <span class="text-danger" style="font-size: 17px">{{$message}}</span>



                                        @enderror



                                    </div>



                                </div>



                                <div class="col-md-6 col-12">



                                    <div class="form-group">



                                        <label for="name">Username</label>



                                        <input type="text" id="name" class="form-control" name="name" value="{{ $userslist->name }}">



                                        @error('name')



                                        <span class="text-danger" style="font-size: 17px">{{$message}}</span>



                                        @enderror



                                    </div>



                                </div>



                                <div class="col-md-6 col-12">



                                    <div class="form-group">



                                        <label for="email">Email</label>



                                        <input type="email" id="email" class="form-control" name="email" value="{{ $userslist->email }}">



                                        @error('email')



                                        <span class="text-danger" style="font-size: 17px">{{$message}}</span>



                                        @enderror



                                    </div>



                                </div>



                                <div class="col-md-6 col-12">



                                    <div class="form-group">



                                        <label for="mobile">Mobile</label>



                                        <input type="number" id="mobile" class="form-control" name="mobile" value="{{ $userslist->mobile }}">



                                        @error('mobile')



                                        <span class="text-danger" style="font-size: 17px">{{$message}}</span>



                                        @enderror



                                    </div>



                                </div>



                                @php



                                    $rolelist = DB::table('userlevels')->where('id', $userslist->role)->get();



                                @endphp



                                <div class="col-md-6 col-12">



                                    <div class="form-group">



                                        <label for="role">Assign Role</label>



                                        <select class="form-select" name="role" id="role">



                                            @if(!$userslist->id)



                                            <option value="" selected hidden disabled>Select Role</option>



                                            @else



                                            <option value="{{ $rolelist[0]->id }}" selected>{{ $rolelist[0]->levelType }}</option>



                                            @endif



                                            @foreach($role as $list)



                                            <option value="{{$list->id}}">{{$list->levelType}}</option>



                                            @endforeach



                                        </select>



                                        @error('role')



                                        <span class="text-danger" style="font-size: 17px">{{$message}}</span>



                                        @enderror



                                    </div>



                                </div>







                                <div class="col-md-6 col-12">

                                    @php

                                    if($userslist->status == 1) {

                                        $active = 'selected';

                                    }else{

                                        $active = '';

                                    }

                                    if($userslist->status == 0) {

                                        $inactive = 'selected';

                                    }else{

                                        $inactive = '';

                                    }

                                    @endphp

                                    <div class="form-group">



                                        <label for="status">Status</label>



                                        <select class="form-select" name="status" id="status">



                                            <option value="1" {{ $active }}>Active</option>



                                            <option value="0" {{ $inactive }}>Inactive</option>



                                        </select>



                                        @error('status')



                                        <span class="text-danger" style="font-size: 17px">{{$message}}</span>



                                        @enderror



                                    </div>



                                </div>



                               <div class="col-md-6 col-12">

                                    <div class="form-group">

                                        <label for="branch">Assign Branch</label>

                                        <select class="form-select" name="branch" id="branch">

                                            @if(!$userslist->id)    

                                            <option value="" selected hidden disabled>Select Branch</option>

                                            @else

                                            @php

                                            $br = DB::table('branchs')->where('brCode', $userslist->brCode)->first();

                                            @endphp                                            

                                            <option value="{{$br->brCode}}" selected>{{ $br->brName }}</option>

                                            @endif

                                            @foreach($branch as $br)

                                            <option value="{{$br->brCode}}">{{$br->brName}}</option>

                                            @endforeach

                                        </select>

                                        @error('branch')

                                        <span class="text-danger" style="font-size: 17px">{{$message}}</span>

                                        @enderror

                                    </div>

                                </div>



                                <div class="col-md-6 col-12">

                                    <div class="form-group">

                                            @php

                                            $pages = DB::table('menus')->orderBy('m_order','asc')->where('access', 1)->get();

                                            @endphp

                                        <label for="pages">Assigned Pages</label>

                                        @php

                                        $p = explode(',', $userslist->pages);

                                        @endphp

                                        <select class="choices form-select multiple-remove multi_pages" name="pages[]" id="pages" multiple="multiple">

                                            @foreach($pages as $page)

                                            <option value="{{ $page->id }}" @if(in_array($page->id, $p)) selected @endif>{{ $page->name }}</option>

                                            @endforeach

                                        </select>

                                        @error('pages')

                                        <span class="text-danger" style="font-size: 17px">{{$message}}</span>

                                        @enderror

                                    </div>    

                                </div>



                                <div class="col-12 d-flex justify-content-end py-5">



                                    <button type="submit" class="btn btn-primary me-1 mb-1">Update User</button>



                                </div>



                                <div class="col-12 py-5">&nbsp;</div>



                            </div>



                        </div>



                    </form>



                </div>



            </div>



        </div>



    </div>



</div>







@endsection