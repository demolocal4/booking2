@extends('admin.layout.app')

@section('page_title', 'User Profile')

@section('content')



<h3>User Profile</h3>



<div class="row" style="background-color: rgb(255, 255, 255); padding:30px 10px; margin:40px 0;">

    <div class="col">

    

    <div class="row">

    <div class="col col-md-2">

        <img class="profile-pic" src="{{asset('uploads/'.$users[0]->profile_pic)}}" />

        <form action="{{ route('profile_pic') }}" method="POST" enctype="multipart/form-data">

            @csrf

            <input type="hidden" name="id" value="{{$users[0]->id}}">

            <input type="file" name="photo" id="photo" accept=".jpg,.png,.jpeg,.gif">

            <label for="photo" class="btn-change">Change Photo</label>

            <button type="submit" class="btn btn-primary btn-photo">Update</button>

        </form>

    </div>

    <div class="col col-md-10">

        <h4>{{ucfirst($users[0]->name)}}</h4>

        <p>@ <a href="mailto:{{$users[0]->email}}">{{$users[0]->email}}</a></p>

        <p><i class="bi bi-phone"></i> <a href="tel:{{$users[0]->mobile}}">{{$users[0]->mobile}}</a></p>

    </div>

    </div>



    <div class="row">

        <div class="col col-md-4 offset-md-8">

            @if(Session::get('success'))

            <div class="alert alert-success">{{Session::get('success')}}</div>

            @endif

            @if(Session::get('fail'))

            <div class="alert alert-danger">{{Session::get('fail')}}</div>

            @endif

        </div>

    </div>

    

    <div class="row mt-5">

        <ul class="nav nav-tabs" id="myTab" role="tablist">

            <li class="nav-item" role="presentation">

              <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Basic Info</button>

            </li>

            <li class="nav-item" role="presentation">

              <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Edit Profile</button>

            </li>

            <li class="nav-item" role="presentation">

              <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">Reset Password</button>

            </li>

          </ul>

          <div class="tab-content" id="myTabContent">

            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">

                <table class="table profileTab">

                    <tbody>

                      <tr>

                        <td><strong>Full Name:</strong></td>

                        <td>{{$users[0]->fullname}}</td>

                      </tr>

                      <tr>

                        <td><strong>Role:</strong></td>

                        <td>{{$roles[0]->levelType}}</td>

                      </tr>

                      <tr>

                        <td><strong>Status:</strong></td>

                        <td>

                        @if($users[0]->status == 1)

                        <span class="badge bg-success">Active</span>

                        @else 

                        <span class="badge bg-danger">Inactive</span>

                        @endif

                        </td>

                      </tr>

                    </tbody>

                  </table>    

            </div>



            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">

                <form method="POST" action="{{ route('profile_update') }}">

                    @csrf

                    <input type="hidden" name="id" value="{{$users[0]->id}}">

                    <div class="mb-3">

                        <label for="username" class="form-label">Username</label>

                        <input type="text" class="form-control" name="username" id="username" value="{{$users[0]->name}}" aria-describedby="username">

                        @error('username')

                        <div id="username" class="form-text">{{$message}}</div>

                        @enderror



                    </div>



                    <div class="mb-3">

                        <label for="mobile" class="form-label">Mobile</label>

                        <input type="number" class="form-control" name="mobile" id="mobile" value="{{$users[0]->mobile}}" aria-describedby="mobile">

                        @error('mobile')

                        <div id="mobile" class="form-text">{{$message}}</div>

                        @enderror

                    </div>



                    <div class="mb-3">

                      <label for="email" class="form-label">Email address</label>

                      <input type="email" class="form-control" name="email" id="email" value="{{$users[0]->email}}" aria-describedby="emailHelp">

                      @error('email')

                      <div id="email" class="form-text">{{$message}}</div>

                      @enderror

                    </div>



                    <div class="mb-3">

                        <label for="fullname" class="form-label">Full Name</label>

                        <input type="text" class="form-control" name="fullname" id="fullname" value="{{$users[0]->fullname}}" aria-describedby="fullname">

                        @error('fullname')

                        <div id="fullname" class="form-text">{{$message}}</div>

                        @enderror

                    </div>

                    

                    <button type="submit" class="btn btn-primary">Update</button>

                  </form>  

            </div>

            <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">

                <form action="{{ route('reset_password') }}" method="POST">

                    @csrf

                    <input type="hidden" name="id" value="{{$users[0]->id}}">

                    <div class="mb-3">

                        <label for="password" class="form-label">New Password</label>

                        <input type="password" class="form-control" name="password" id="password" maxlength="15" placeholder="Password should be 5 character and Maximum 15" aria-describedby="password">

                        @error('password')

                        <span id="password" class="form-text">{{$message}}</span>

                        @enderror

                    </div>

                    <button type="submit" class="btn btn-info">Reset Password</button>

                </form>

            </div>

          </div>



    </div>



    </div>

</div>

@endsection

<script src="{{asset('js/jquery-3.6.0.min.js')}}"></script>

<script>

    $(document).ready(function(){



            $("#photo").on("change", function(e){

                let [filename] = e.target.files;

                $(".profile-pic").attr("src", URL.createObjectURL(filename));

        

            });



    });

</script>