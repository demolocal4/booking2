<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        * {
            padding:0;
            margin:0;
            box-sizing:border-box;
            font-family: sans-serif;
            line-height: 1.8;
        }
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background-color: #72a8e7;
        }
        h1 {
            font-size: 45px;
            color: white;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.9);
        }
        a {
            color:rgb(7, 7, 7);
            font-size: 16px;
            transition: all .3s ease-in-out;
        }
        a:hover {
            color: rgb(44, 44, 44);
        }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>405 - Bad Request</title>
</head>
<body>
        <img src="{{ asset('images/logo.png') }}" style="width: 130px;" alt="">
        <h1>Bad Request</h1>
        <a href="{{ url('manage_booking') }}">Return back</a>
</body>
</html>