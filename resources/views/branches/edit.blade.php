@extends('admin.layout.app')

@section('page_title', 'Update Branch')

@section('content')

@php

$users = DB::table('users')->where('status', 1)->get();

@endphp

<h3>Update Branch</h3>

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

                    @php
                    $ownersid = explode(',', $branchlist->owners);
                    @endphp

                    <form method="POST" class="form form-vertical" action="{{ url('manage_branches/'. $branchlist->id) }}" enctype="multipart/form-data">

                        @csrf

                        @method('PUT')

                        <div class="form-body">

                            <div class="row">

                                <div class="col-12 mb-2">
                                    <label for="owners">Building Owners</label>    
                                    <select class="choices form-select" name="owners[]" id="owners" multiple="multiple">
                                        @foreach(\DB::table('users')->where('role', 5)->get() as $owner)
                                        <option value="{{ $owner->id }}" {{ in_array($owner->id, $ownersid) ? 'selected' : '' }}>{{ $owner->fullname }}</option>
                                        @endforeach
                                    </select>
                                    @error('owners')
                                    <span class="text-danger" style="font-size: 17px">{{$message}}</span>
                                    @enderror
                                </div>

                                <div class="col-md-6 col-12">

                                    <div class="form-group">

                                        <label for="brName">Branch Name</label>

                                        <input type="text" id="brName" class="form-control" name="brName" value="{{ $branchlist->brName }}">

                                        @error('brName')

                                        <span class="text-danger" style="font-size: 17px">{{$message}}</span>

                                        @enderror

                                    </div>

                                </div>

                                <div class="col-md-6 col-12">

                                    <div class="form-group">

                                        <label for="brLocation">Location</label>

                                        <input type="text" id="brLocation" class="form-control" name="brLocation" value="{{ $branchlist->brLocation }}">

                                        @error('brLocation')

                                        <span class="text-danger" style="font-size: 17px">{{$message}}</span>

                                        @enderror

                                    </div>

                                </div>



                                <div class="col-md-6 col-12">

                                    <div class="form-group">

                                        @php

                                        $manager = DB::table('users')->where('id', $branchlist->brManager)->get();

                                        @endphp

                                        <label for="brManager">Branch Manager</label>

                                        <select class="form-select" name="brManager" id="brManager">

                                           <option value="{{ $manager[0]->id }}" selected>{{ ucfirst($manager[0]->name) }}</option>

                                            @foreach($users as $list)

                                            <option value="{{ $list->id }}">{{ ucfirst($list->name) }}</option>

                                            @endforeach

                                           

                                        </select>

                                        @error('brManager')

                                        <span class="text-danger" style="font-size: 17px">{{$message}}</span>

                                        @enderror

                                    </div>

                                </div>



                                <div class="col-md-6 col-12">

                                    <div class="form-group">

                                        <label for="brFloors">Branch Floors</label>

                                        <input type="number" id="brFloors" class="form-control" min="1" name="brFloors" value="{{ $branchlist->brFloors }}">

                                        @error('brFloors')

                                        <span class="text-danger" style="font-size: 17px">{{$message}}</span>

                                        @enderror

                                    </div>

                                </div>



                                <div class="col-md-6 col-12">

                                    <div class="form-group">

                                        <label for="brRooms">Branch Rooms</label>

                                        <input type="number" id="brRooms" class="form-control" min="1" name="brRooms" value="{{ $branchlist->brRooms }}">

                                        @error('brRooms')

                                        <span class="text-danger" style="font-size: 17px">{{$message}}</span>

                                        @enderror

                                    </div>

                                </div>



                                <div class="col-md-6 col-12">

                                    <div class="form-group">

                                        <label for="brContact">Branch Contact</label>

                                        <input type="number" id="brContact" class="form-control" name="brContact" value="{{ $branchlist->brContact }}">

                                        @error('brContact')

                                        <span class="text-danger" style="font-size: 17px">{{$message}}</span>

                                        @enderror

                                    </div>

                                </div>



                                <div class="col-md-6 col-12">

                                    <label for="ownerName">Owner Name</label>    

                                    <input type="text" name="ownerName" id="ownerName" class="form-control" value="{{ $branchlist->ownerName }}" required>

                                    </div>    

                                    <div class="col-md-6 col-12">

                                    <label for="landlordName">Landlord Name</label>    

                                    <input type="text" name="landlordName" id="landlordName" class="form-control" value="{{ $branchlist->landlordName }}" required>

                                    </div>

                                    <div class="col-md-6 col-12 mt-2">

                                    <label for="landlordEmail">Landlord Email</label>    

                                    <input type="email" name="landlordEmail" id="landlordEmail" class="form-control" value="{{ $branchlist->landlordEmail }}" required>

                                    </div>

                                    <div class="col-md-6 col-12 mt-2">

                                    <label for="landlordPhone">Landlord Phone</label>    

                                    <input type="number" name="landlordPhone" id="landlordPhone" class="form-control" value="{{ $branchlist->landlordPhone }}" required>

                                    </div>

                                    <div class="col-md-6 col-12 mt-2">

                                    <label for="propertyType">Property Type</label>    

                                    <select name="propertyType" id="propertyType" class="form-select">

                                    <option value="{{ $branchlist->propertyType }}" selected>{{ $branchlist->propertyType }}</option>    

                                    <option value="Residential" selected>Residential</option>

                                    <option value="Commercial">Commercial</option>    

                                    <option value="Industrial">Industrial</option>    

                                    </select>

                                    </div>    

                                    <div class="col-md-6 col-12 mt-2">

                                    <label for="plotNo">Plot No</label>

                                    <input type="text" name="plotNo" id="plotNo" class="form-control" value="{{ $branchlist->plotNo }}" required>    

                                    </div>

                                    <div class="col-md-6 col-12 mt-2">

                                    <label for="sDepositAmount">Security Deposit Amount</label>

                                    <input type="number" name="sDepositAmount" id="sDepositAmount" min="0" step=".01" class="form-control"  pattern="^\d*(\.\d{0,2})?$" value="{{ $branchlist->sDepositAmount }}" required>    

                                    </div>

                                    <div class="col-md-6 col-12 mt-2">

                                    <label for="modePayment">Mode of Payment</label>

                                    <input type="text" name="modePayment" id="modePayment" class="form-control" value="{{ $branchlist->modePayment }}" required>    

                                    </div>

                                    

                                    <div class="col-md-6 col-12 mt-2">

                                    <label for="logoFile">Branch Logo (Dimention 100 x 97)</label>

                                    <input type="file" name="logoFile" id="logoFile" accept=".jpg,.png,.jpeg,.gif" class="form-control">

                                    <input type="hidden" name="exist_logo" value="{{ $branchlist->logo }}">

                                    </div>

                                    <div class="col-md-2" style="padding: 10px;" class="logoImg">

                                     @if($branchlist->logo != '')

                                     <img src="{{ asset('uploads/'.$branchlist->logo) }}" class="logoImg" alt="logo">  

                                     @else

                                     <img src="{{ asset('uploads/logo_thums.png') }}" class="logoImg" alt="logo"> 

                                     @endif

                                    </div>



                                <div class="col-12 mt-3">

                                    <div class="form-group">

                                        <label for="">Terms & Conditions</label>

                                    </div>

                                    <textarea name="terms" id="terms" cols="30" rows="10" class="form-control">

                                        {{ $branchlist->terms }}

                                    </textarea>

                                       

                                </div>

                                                           



                                <div class="col-12 d-flex justify-content-end py-5">

                                    <button type="submit" class="btn btn-primary me-1 mb-1">Update Branch</button>

                                </div>

                            </div>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

<script src="{{asset('ckeditor/ckeditor.js')}}"></script>

<script>

    CKEDITOR.replace('terms');

</script>

@endsection

<script src="{{asset('js/jquery-3.6.0.min.js')}}"></script>

<script>

    $(document).ready(function(){



            $("#logoFile").on("change", function(e){

                let [filename] = e.target.files;

                $(".logoImg").attr("src", URL.createObjectURL(filename));

                

            });



    });

</script>