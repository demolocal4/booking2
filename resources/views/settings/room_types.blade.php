@extends('admin.layout.app')
@section('page_title', 'Room Types')
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
<section class="section">
    <!--Disabled Backdrop Modal -->
    <div class="row">
        <div class="col-12">
            @if(Session::has('success'))
            <div class="alert alert-success">{{ Session::get('success') }}</div>
            @endif
            @if(Session::has('fail'))
            <div class="alert alert-danger">{{ Session::get('fail') }}</div>
            @endif
        </div>
    </div>
    <div class="card">
        
        <div class="card-body">
            <div class="row">
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
        </div>

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



