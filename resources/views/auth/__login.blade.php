<!DOCTYPE html>

<html lang="en">

    <meta http-equiv="content-type" content="text/html;charset=utf-8" />    

<head>

    <style>

        .logo {

            width: 100%;

            max-width: 150px;

            padding-bottom: 4rem;

            

        }

        .logo img {

            width: 100%;

            object-fit: cover;

        }
        
        
    </style>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - {{ config('app.name') }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&amp;display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{asset('admin/assets/css/bootstrap.css')}}">

    <link rel="stylesheet" href="{{asset('admin/assets/vendors/bootstrap-icons/bootstrap-icons.css')}}">

    <link rel="stylesheet" href="{{asset('admin/assets/css/app.css')}}">

    <link rel="stylesheet" href="{{asset('admin/assets/css/pages/auth.css')}}">

    <link rel="shortcut icon" href="{{asset('images/favicon-32x32.png')}}" type="image/x-icon">

	<link rel="icon" href="{{asset('images/favicon-32x32.png')}}" type="image/x-icon">

</head>



<body>

    <div id="auth">



        <div class="row h-100">

            <div class="col-lg-5 col-12">

                <div id="auth-left">

                    <div class="logo">

                        <a href="{{ route('login') }}"><img src="{{asset('uploads/logo.png')}}" alt="Logo"></a>

                    </div>

                    <h1 class="auth-title">Log in.</h1>

                    

                    @if(Session::get('fail'))

                    <div class="alert alert-danger">

                    <i class="bi bi-exclamation-triangle"></i> 

                    {{ Session::get('fail') }}

                    </div>

                    @endif



                    @if(Session::get('logout'))

                    <div class="alert alert-success">

                    <i class="bi bi-check-circle"></i> 

                    {{ Session::get('logout') }}

                    </div>

                    @endif



                    @if(Session::get('auth'))

                    <div class="alert alert-warning">

                    <i class="bi bi-exclamation-triangle"></i> 

                    {{ Session::get('auth') }}

                    </div>

                    @endif

                    

                    <form action="{{route('auth_check')}}" method="POST" id="fmAuth">

                        @csrf

                        @error('name')

                        <span class="text-danger" style="font-size: 17px">{{$message}}</span>

                        @enderror

                        <div class="form-group position-relative has-icon-left mb-4">

                            <input type="text" name="name" class="form-control form-control-xl" placeholder="Username/Email" value="{{ old('name') }}" required>

                            <div class="form-control-icon">

                                <i class="bi bi-person"></i>

                            </div>

                        </div>

                        @error('password')

                        <span class="text-danger" style="font-size: 17px">{{$message}}</span>

                        @enderror

                        <div class="form-group position-relative has-icon-left mb-4">

                            <input type="password" name="password" class="form-control form-control-xl" placeholder="Password">

                            <div class="form-control-icon">

                                <i class="bi bi-shield-lock"></i>

                            </div>

                        </div>

                        <div class="d-flex align-items-end">
			    {{--	
                            <input class="form-check-input me-2" type="checkbox" value="" id="flexCheckDefault">
                            <label class="form-check-label text-gray-600" for="flexCheckDefault">
                                Keep me logged in
                            </label>
                            --}}
                            
                           
                             
                        </div>
			 
                        <button class="btn btn-primary btn-block btn-lg shadow-lg mt-5">Log in</button>

                    </form>

                    <div class="text-center mt-5 text-lg fs-4">

                        <p style="display: none;" class="text-gray-600">Don't have an account? <a href="{{route('register')}}"

                                class="font-bold">Sign

                                up</a>.</p>

                        <p style="display: none;"><a class="font-bold" href="{{route('forgotpassword')}}">Forgot password?</a>.</p>

                    </div>

                </div>

            </div>

            <div class="col-lg-7 d-none d-lg-block">

                <div id="auth-right">

                        <img src="{{asset('images/login-bg.jpg')}}" alt="login">

                </div>

            </div>

        </div>



    </div>

</body>
</html>