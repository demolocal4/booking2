@extends('admin.layout.app')

@section('page_title', 'General Setting')

@section('content')

@php

use Illuminate\Support\Facades\DB;

$users = DB::table('users')->where('id', session()->get('user_id'))->get();

$count = DB::table('add_charges')->first();

$booking_com_duration = DB::table('settings')->where('id', 9)->first();

@endphp

<div class="loader">

<img src="{{asset('images/Ripple-1s-200px.svg')}}" alt="preloader">

<span>Please Wait...</span>

</div>

<h3>General Settings</h3>

<section class="section">

    <!--Disabled Backdrop Modal -->

    <div class="card">

        {{-- <div class="card-header">

            @if(Session::get('success'))

                        <div class="alert alert-success">

                        <i class="bi bi-check-circle"></i> 

                        {{ Session::get('success') }}

                        </div>

            @endif

            @if(Session::get('fail'))

                        <div class="alert alert-danger">

                        {{ Session::get('fail') }}

                        </div>

            @endif  --}}

            <div class="card-body">

                <h4>Additional Charges (Booking.com)</h4>

                <form action="{{ url('additionalCharges') }}" method="POST">

                <input type="hidden" name="id" value="1">

                @csrf

                <div class="row">

                    <div class="col-6">

                    <div class="col-12">

                      <div class="form-group">

                        <label for="commission">Commission Charges(%)</label>

                        <input type="number" name="commission" id="commission" min="0" step="0.01" 

                        value="{{ $count ? $count->commission : 0 }}" 

                        class="form-control">

                      </div>

                    </div>

                    <div class="col-12">

                      <div class="form-group">

                        <label for="payment">Payment Charges(%)</label>

                        <input type="number" name="payment" id="payment" min="0" step="0.01"

                        value="{{ $count ? $count->payment : 0 }}" 

                        class="form-control">

                      </div>

                    </div>

                    <div class="col-12">

                      <div class="form-group">

                        <label for="vat_online">Vat Online(%)</label>

                        <input type="number" name="vat_online" id="vat_online" min="0" step="0.01"

                        value="{{ $count ? $count->vat_online : 0 }}" 

                        class="form-control">

                      </div>

                    </div>

                    <div class="col-12">

                        @if($count)

                        <button type="submit" class="btn btn-primary float-end">Update</button>    

                        @else

                        <button type="submit" class="btn btn-primary float-end">Save</button>

                        @endif

                    </div>

                    </div>

                </div>

                </form>

                <form action="{{ url('cancelTimeDuration') }}" method="post">

                @csrf

                <div class="row mt-3 mb-3">

                <h4>Cancel time Duration in minutes (Booking.com)</h4>    

                    <div class="col-6">

                        <span class="note" style="font-size:14px;display:flex;justify-content:flex-end;">e.g. 1440/60 = 24h</span>

                        <div class="col-12">

                            <div class="form-group">

                              <input type="number" name="booking_com_duration" id="booking_com_duration" min="0" 

                              value="{{ $booking_com_duration->setting_val }}" 

                              class="form-control" required>

                            </div>

                          </div>

                          <div class="col-12">

                            <button type="submit" class="btn btn-primary float-end">Update</button>    

                        </div>

                    </div>

                </div>

                </form>

                <div class="row mt-4">

                    <div class="col-6">

                        @if(Session::has('success1'))

                        <div class="alert alert-success">{{ Session::get('success1') }}</div>

                        @endif

                        @if(Session::has('fail1'))

                        <div class="alert alert-danger">{{ Session::get('fail1') }}</div>

                        @endif

                    </div>

                </div>

            </div>

        </div>

        <hr>

        <div class="card-body">

            <div class="row">

            <div class="col-md-3 col-12">

                <h4>Booking Fee On/Off</h4>

                <div class="form-check form-switch">

                    @if($data->setting_val == 1)

                    <input class="form-check-input" type="checkbox" id="vatonoff" checked>

                    @else

                    <input class="form-check-input" type="checkbox" id="vatonoff">

                    @endif

                    <label class="form-check-label" for="vatonoff"></label>

                </div>

            </div>

            </div> 

            <hr>

            <div class="row mt-3">

            <div class="col-md-12 col-12">

                <h4>Cancellation time Duration in minutes</h4>  

                <div class="row">

                    <div class="col-md-6 col-12">

                        <span class="note" style="font-size:14px;display:flex;justify-content:flex-end;">e.g. 1440/60 = 24h</span>

                        @php

                        $duration = DB::table('settings')->where('setting_name', 'time_duration')->get();

                        @endphp

                        @if($duration->isEmpty())

                        <input type="number" name="duration" id="duration" min="0" class="form-control">      

                        @else 

                        <input type="number" name="duration" id="duration" min="0" value="{{ $duration[0]->setting_val }}" class="form-control">

                        @endif

                    </div>

                    <div class="col-md-6 col-12">

                        <button type="submit" id="btn_add" class="btn btn-primary">New Duration</button>

                    </div>

                </div>

            </div>    

            </div>    

            <hr>

            <div class="row mt-3">

                <h4>New Role/Assign access</h4>

                <div class="col-md-12 col-12">

                    <div class="row">



                        <div class="col-md-3 col-12">



                        <input type="text" name="newrole" id="newrole" class="form-control" placeholder="Type new Role">



                        </div>



                        <div class="col-md-1 col12">



                        <input type="submit" value="Add" class="btn btn-success" id="btn_newrole">



                        </div>



                        @php



                        $levels = DB::table('userlevels')->orderBy('levelType','asc')->get();    



                        @endphp



                        <div class="col-md-3 col-12">



                        <select name="levelType" id="levelType" class="form-select">



                            <option value="" selected hidden disabled>Select</option>



                            @foreach($levels as $list)



                            <option value="{{ $list->id }}">{{$list->levelType }}</option>    



                            @endforeach



                        </select>



                        </div>



                        <div class="col-md-2 col-12">



                            <input type="button" id="btn_access" value="Add" class="btn btn-primary">



                            <input style="display: none;" type="button" value="Delete" class="btn btn-outline-danger" id="btn_delete">



                        </div>



                    </div>



                    <div class="row mt-3">

                        <div class="col-12">

                            <div class="card">

                                <div class="card-header">

                                    @if(Session::get('success'))

                                    <div class="alert alert-success mt-3">

                                    <i class="bi bi-check-circle"></i> 

                                    {{ Session::get('success') }}

                                    </div>

                                    @endif

                                    @if(Session::get('fail'))

                                    <div class="alert alert-danger mt-3">

                                    {{ Session::get('fail') }}

                                    </div>

                                    @endif

                                </div>

                                <div class="card-body">

                                    <table class="table table-striped" id="roleTable">

                                        <thead>

                                            <tr>

                                                <th>S.No</th>

                                                <th style="text-align: left;">Role Name</th>

                                                <th>Add</th>

                                                <th>Edit</th>

                                                <th>Delete</th>

                                                <th>View</th>

                                                <th>Created By</th>

                                                <th>Updated By</th>

                                                <th>Creatd At</th>

                                                <th>Updated At</th>

                                                <th>Action</th>

                                            </tr>

                                        </thead>

                                        <tbody id="roleBody">



                                                 @php



                                                 $counter = 1;



                                                 $roles = DB::table('role_access')->get();   



                                                 @endphp                                 



                                                 



                                                 @foreach($roles as $role)



                                                        @php



                                                        $roleName = DB::table('userlevels')->where('id', $role->levelType)->get();



                                                        @endphp







                                                        @foreach($roleName as $list)



                                                        <tr>



                                                            <td>{{ $counter++ }}</td>



                                                            <td style="text-align: left;">{{ $list->levelType }}</td>



                                                            <td>



                                                                



                                        <div class="form-check form-switch">



                                        @if($role->canAdd == 1)



                                        <input class="form-check-input canAdd" data-id="{{ $role->id }}" type="checkbox" checked>



                                        @else



                                        <input class="form-check-input canAdd" data-id="{{ $role->id }}" type="checkbox">    



                                        @endif        



                                        </div>



                                               



                                                            </td>



                                                            <td>



                                        <div class="form-check form-switch">



                                        @if($role->canEdit == 1)



                                        <input class="form-check-input canEdit" data-id="{{ $role->id }}" type="checkbox" checked>



                                        @else



                                        <input class="form-check-input canEdit" data-id="{{ $role->id }}" type="checkbox">    



                                        @endif        



                                        </div>                        



                                                            



                                                          



                                                            



                                                            </td>



                                                            <td>



                                        <div class="form-check form-switch" style="display: none;">



                                        @if($role->canDelete == 1)



                                        <input class="form-check-input canDelete" data-id="{{ $role->id }}" type="checkbox" checked>



                                        @else



                                        <input class="form-check-input canDelete" data-id="{{ $role->id }}" type="checkbox">    



                                        @endif        



                                        </div>    



                                                            



                                                            



                                                            </td>



                                                            <td>



                                        <div class="form-check form-switch">



                                        @if($role->canView == 1)



                                        <input class="form-check-input canView" data-id="{{ $role->id }}" type="checkbox" checked>



                                        @else



                                        <input class="form-check-input canView" data-id="{{ $role->id }}" type="checkbox">    



                                        @endif        



                                        </div>    



                                                          



                                        </td>



                                        @php



                                        $createName = DB::table('users')->where('id', $role->created_by)->first();



                                        @endphp



                                        <td>{{ ucfirst($createName->name) }}</td>



                                        @php



                                        $updateName = DB::table('users')->where('id', $role->updated_by)->first();



                                        @endphp



                                        <td>{{ ucfirst($updateName->name) }}</td>



                                        



                                        <td>{{ date('Y-m-d', strtotime($role->created_at)) }}</td>



                                        <td>{{ date('Y-m-d', strtotime($role->updated_at)) }}</td>



                                        



                                        <td style="display: none;">



                                            <a href="{{ url('role_delete/'.$role->id) }}" class="btn btn-outline-danger btn-sm delete">Delete</a>



                                        </td>



                                        </tr>



                                        @endforeach



                                        @endforeach



                                        </tbody>



                                    </table>



                                </div>



                            </div>



                        </div>



                    </div>



                </div>



            </div>     

            

            <hr>



            {{-- <div class="row mt-3">

                <div class="col-12">

                <h4>Room Types</h4>   

                </div>

                

            </div>

            @php

                $branch = DB::table('branchs')->whereNotIn('id',[1000])->where('status', 1)->get();

            @endphp

            <form action="{{ url('addType') }}" method="POST">

            @csrf

            <input type="hidden" name="id" id="id">

            <div class="row">

                    <div class="col-4">

                        <div class="form-group">

                            <select name="branch_id" id="branch_id" class="form-select">

                                <option value="" selected disabled>Select Branch</option>

                                @foreach($branch as $brc)

                                <option value="{{ $brc->brCode }}">{{ $brc->brName }}</option>    

                                @endforeach

                            </select>

                        </div>     

                    </div>

                    <div class="col-4">

                       <div class="form-group">

                       <input type="text" name="rType" id="type" class="form-control" required placeholder="Room Type"> 

                       </div>     

                    </div>

                    <div class="col-2">

                     <button type="submit" class="btn btn-primary btn_multi">Add Type</button>   

                    </div>

            </div>

        </form>

        <table class="table table-striped" id="table1">

            <thead>

                <tr>

                    <th width="100" class="text-center">Type ID#</th>

                    <th>Branch</th>

                    <th>Type</th>

                    <th width="50">Action</th>

                </tr>

            </thead>

            <tbody id="typeBody">

                @foreach(\App\Models\RoomType::with('branch')->get() as $type)

                <tr>

                    <th class="text-center">{{ $type->id }}</th>

                    <td>{{ $type->branch->brName }}
                    <span style="visibility: hidden;" class="branch_id">{{ $type->branch_id }}</span>	
                    </td>


                    <td>{{ $type->rType }}</td>

                    <td>

                        <button class="btn btn-outline-info btn-sm type_edit">Edit</button>

                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

        </div> --}}



    </div>



   



</section>



<script src="{{asset('js/jquery-3.6.0.min.js')}}"></script>



<script>



    $(document).ready(function(){



       $("#typeBody").on("click", ".type_edit", function(){

            let id = $(this).closest("tr").find("th:nth(0)").html();
            let branchId = $(this).closest("tr").find("td .branch_id").html();
            let value = $(this).closest("tr").find("td:nth(1)").html();

            $("#id").val(id);

            $("#type").val(value);

            $(".btn_multi").html("Update Type");

            $('#branch_id option[value="'+branchId+'"]').prop('selected', true);

           

       });



       

        setTimeout(() => {

                 $(".alert").slideUp();

            }, 3000);          

    $("#vatonoff").on("click",function(){

                if($(this).is(':checked')) {

                    $(".loader").show();

                    $.ajax({

                            url: '{{ url("vatSetting") }}',    

                            type: 'POST',

                            data: {action:1, _token:'{{ csrf_token() }}'},

                            success:function(response) {

                            $(".loader").hide();

                            if(response.status == 'success') {







                                        Toastify({



                                        text: response.message,



                                        duration: 3000,



                                        close: true,



                                        gravity: "top", // `top` or `bottom`



                                        position: "right", // `left`, `center` or `right`



                                        backgroundColor: "linear-gradient(to right, #1cab14, #10c61f)",



                                        }).showToast();







                            }else{







                                        Toastify({



                                        text: response.message,



                                        duration: 3000,



                                        close: true,



                                        gravity: "top", // `top` or `bottom`



                                        position: "right", // `left`, `center` or `right`



                                        backgroundColor: "linear-gradient(to right, #de1919, #bb1e1e)",



                                        }).showToast();



                            }







                          



                            }







                    });







                }else{



                    $(".loader").show();



                    $.ajax({







                        url: '{{ url("vatSetting") }}',



                        type: 'POST',



                        data: {action:0, _token:'{{ csrf_token() }}'},



                        success:function(response) {



                            $(".loader").hide();



                            if(response.status == 'success') {







                                    Toastify({



                                    text: response.message,



                                    duration: 3000,



                                    close: true,



                                    gravity: "top", // `top` or `bottom`



                                    position: "right", // `left`, `center` or `right`



                                    backgroundColor: "linear-gradient(to right, #1cab14, #10c61f)",



                                    }).showToast();







                                    }else{







                                    Toastify({



                                    text: response.message,



                                    duration: 3000,



                                    close: true,



                                    gravity: "top", // `top` or `bottom`



                                    position: "right", // `left`, `center` or `right`



                                    backgroundColor: "linear-gradient(to right, #de1919, #bb1e1e)",



                                    }).showToast();



                                    }



                        }







                        });



                }



                







        });







        //time_duration







        $("#btn_add").on("click", function(){



            let duration = $("#duration").val();



            if(duration != '') {



                $(".loader").show();



                $.ajax({







                        url: '{{ url("time_duration") }}',



                        type: 'POST',



                        data: {_token:'{{ csrf_token() }}', duration:duration},



                        success:function(response) {



                        $(".loader").hide();    



                        if(response.status == 'success') {







                            Toastify({



                            text: response.message,



                            duration: 3000,



                            close: true,



                            gravity: "top", // `top` or `bottom`



                            position: "right", // `left`, `center` or `right`



                            backgroundColor: "linear-gradient(to right, #1cab14, #10c61f)",



                            }).showToast();







                            }else{







                            Toastify({



                            text: response.message,



                            duration: 3000,



                            close: true,



                            gravity: "top", // `top` or `bottom`



                            position: "right", // `left`, `center` or `right`



                            backgroundColor: "linear-gradient(to right, #de1919, #bb1e1e)",



                            }).showToast();



                            }



                        }







                });







            }else{







                Toastify({



                text: 'Must be fill duration field',



                duration: 3000,



                close: true,



                gravity: "top", // `top` or `bottom`



                position: "right", // `left`, `center` or `right`



                backgroundColor: "linear-gradient(to right, #de1919, #bb1e1e)",



                }).showToast();



                $("#duration").focus();



            }







        });











        $("#btn_access").on("click",function(){







            let role = $("#levelType").val();



            if(role) {



                    $(".loader").show();



                    $.ajax({







                               url: '{{ url("add_access") }}',



                               type: 'POST',



                               data: {_token:'{{ csrf_token() }}', role:role},



                               success:function(response) {







                                $(".loader").hide();    



                                if(response.status == 'success') {







                                    if(!alert(response.message)) {



                                        window.location.reload();



                                    }







                                }else{







                                    Toastify({



                                    text: response.message,



                                    duration: 3000,



                                    close: true,



                                    gravity: "top", // `top` or `bottom`



                                    position: "right", // `left`, `center` or `right`



                                    backgroundColor: "linear-gradient(to right, #de1919, #bb1e1e)",



                                    }).showToast();







                                }







                               }    



                    });







            }else{







                Toastify({



                text: 'Select Role before Add',



                duration: 3000,



                close: true,



                gravity: "top", // `top` or `bottom`



                position: "right", // `left`, `center` or `right`



                backgroundColor: "linear-gradient(to right, #de1919, #bb1e1e)",//danger color scheme



                }).showToast();



            }







        });







        $("#roleBody").on("click",".canAdd",function(){



                let newAccess;



                if($(this).is(':checked')) {



                let id = $(this).data('id');



                newAccess = 1;    



                $(".loader").show();



                $.ajax({







                      url: '{{ url("assign_access") }}',



                      type: 'POST',



                      data:{_token:'{{ csrf_token() }}',access:newAccess,action:'canAdd',id:id},



                      success:function(response) {



                            $(".loader").hide();



                            if(response.status == 'success') {







                            if(!alert(response.message)) {



                                window.location.reload();



                            }    







                            }else{







                            if(!alert(response.message)) {



                                window.location.reload();



                            }    







                            }







                      }  







                });  



                    



                }else{



                newAccess = 0;    



                let id = $(this).data('id');



                $(".loader").show();



                $.ajax({







                      url: '{{ url("assign_access") }}',



                      type: 'POST',



                      data:{_token:'{{ csrf_token() }}',access:newAccess,action:'canAdd',id:id},



                      success:function(response) {



                            $(".loader").hide();



                            if(response.status == 'success') {







                            if(!alert(response.message)) {



                                window.location.reload();



                            }



                           



                            }else{



                            



                            if(!alert(response.message)) {



                                window.location.reload();



                            }    







                            // Toastify({



                            // text: response.message,



                            // duration: 3000,



                            // close: true,



                            // gravity: "top", // `top` or `bottom`



                            // position: "right", // `left`, `center` or `right`



                            // stopOnFocus: true, // Prevents dismissing of toast on hover



                            // backgroundColor: "linear-gradient(to right, #de1919, #bb1e1e)",



                            // onClick: function(){



                            //   window.location.reload();                              



                                



                            // } // Callback after click



                            // }).showToast();







                            }







                      }  







                });



                    



                }







                



        });











        $("#roleBody").on("click",".canEdit",function(){



                let newAccess;



                if($(this).is(':checked')) {



                let id = $(this).data('id');



                newAccess = 1;    



                $(".loader").show();



                $.ajax({







                      url: '{{ url("assign_access") }}',



                      type: 'POST',



                      data:{_token:'{{ csrf_token() }}',access:newAccess,action:'canEdit',id:id},



                      success:function(response) {



                            $(".loader").hide();



                            if(response.status == 'success') {







                            if(!alert(response.message)) {



                                window.location.reload();



                            }    







                            }else{







                            if(!alert(response.message)) {



                                window.location.reload();



                            }    







                            }







                      }  







                });  



                    



                }else{



                newAccess = 0;    



                let id = $(this).data('id');



                $(".loader").show();



                $.ajax({







                      url: '{{ url("assign_access") }}',



                      type: 'POST',



                      data:{_token:'{{ csrf_token() }}',access:newAccess,action:'canEdit',id:id},



                      success:function(response) {



                            $(".loader").hide();



                            if(response.status == 'success') {







                            if(!alert(response.message)) {



                                window.location.reload();



                            }



                           



                            }else{



                            



                            if(!alert(response.message)) {



                                window.location.reload();



                            }    







                            }







                      }  







                });



                    



                }







                



        });











        $("#roleBody").on("click",".canDelete",function(){



                let newAccess;



                if($(this).is(':checked')) {



                let id = $(this).data('id');



                newAccess = 1;    



                $(".loader").show();



                $.ajax({







                      url: '{{ url("assign_access") }}',



                      type: 'POST',



                      data:{_token:'{{ csrf_token() }}',access:newAccess,action:'canDelete',id:id},



                      success:function(response) {



                            $(".loader").hide();



                            if(response.status == 'success') {







                            if(!alert(response.message)) {



                                window.location.reload();



                            }    







                            }else{







                            if(!alert(response.message)) {



                                window.location.reload();



                            }    







                            }







                      }  







                });  



                    



                }else{



                newAccess = 0;    



                let id = $(this).data('id');



                $(".loader").show();



                $.ajax({







                      url: '{{ url("assign_access") }}',



                      type: 'POST',



                      data:{_token:'{{ csrf_token() }}',access:newAccess,action:'canDelete',id:id},



                      success:function(response) {



                            $(".loader").hide();



                            if(response.status == 'success') {







                            if(!alert(response.message)) {



                                window.location.reload();



                            }



                           



                            }else{



                            



                            if(!alert(response.message)) {



                                window.location.reload();



                            }    







                            }







                      }  







                });



                    



                }







                



        });











        $("#roleBody").on("click",".canView",function(){



                let newAccess;



                if($(this).is(':checked')) {



                let id = $(this).data('id');



                newAccess = 1;    



                $(".loader").show();



                $.ajax({







                      url: '{{ url("assign_access") }}',



                      type: 'POST',



                      data:{_token:'{{ csrf_token() }}',access:newAccess,action:'canView',id:id},



                      success:function(response) {



                            $(".loader").hide();



                            if(response.status == 'success') {







                            if(!alert(response.message)) {



                                window.location.reload();



                            }    







                            }else{







                            if(!alert(response.message)) {



                                window.location.reload();



                            }    







                            }







                      }  







                });  



                    



                }else{



                newAccess = 0;    



                let id = $(this).data('id');



                $(".loader").show();



                $.ajax({







                      url: '{{ url("assign_access") }}',



                      type: 'POST',



                      data:{_token:'{{ csrf_token() }}',access:newAccess,action:'canView',id:id},



                      success:function(response) {



                            $(".loader").hide();



                            if(response.status == 'success') {







                            if(!alert(response.message)) {



                                window.location.reload();



                            }



                           



                            }else{



                            



                            if(!alert(response.message)) {



                                window.location.reload();



                            }    







                            }







                      }  







                });



                    



                }







                



        });







        $(".delete").on("click",function(){







            return confirm('Are you sure?');







        });







        $("#btn_newrole").on("click",function(){



            let value = $("#newrole").val();



            if(value) { 



                $(".loader").show();



                $.ajax({







                        url: '{{ url("role_add") }}',



                        type: 'POST',



                        data:{_token:'{{ csrf_token() }}',role:value},



                        success:function(response) {



                            $(".loader").hide();



                             if(response.status == 'exist') {







                                Toastify({



                                text: response.message,



                                duration: 3000,



                                close: true,



                                gravity: "top", // `top` or `bottom`



                                position: "right", // `left`, `center` or `right`



                                backgroundColor: "linear-gradient(to right, #de1919, #bb1e1e)",//danger color scheme



                                }).showToast();







                             }







                            if(response.status == 'success') {







                                    if(!alert(response.message)) {







                                        window.location.reload();



                                    }







                            }







                            if(response.status == 'fail') {







                                Toastify({



                                text: response.message,



                                duration: 3000,



                                close: true,



                                gravity: "top", // `top` or `bottom`



                                position: "right", // `left`, `center` or `right`



                                backgroundColor: "linear-gradient(to right, #de1919, #bb1e1e)",//danger color scheme



                                }).showToast();







                            }



                        }







                });







            }else{







                Toastify({



                text: 'Type Role Name before Add',



                duration: 3000,



                close: true,



                gravity: "top", // `top` or `bottom`



                position: "right", // `left`, `center` or `right`



                backgroundColor: "linear-gradient(to right, #de1919, #bb1e1e)",//danger color scheme



                }).showToast();



                $("#newrole").focus();



            }







        });







        $("#btn_delete").on("click",function(){

                if(confirm('Are you sure?')){

                    let getRole = $("#levelType").val();

                    window.location.href = '{{ url("levelType_delete") }}'+'/'+getRole;



                }



        });



       







    });



</script>



@endsection







