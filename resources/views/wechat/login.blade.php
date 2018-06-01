<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('/extend/jquery-3.3.1.min.js') }}"></script>
    <title>系统登录</title>
    <style>
        body {
            height: 100%;
            overflow: hidden;
            font-family: "Gudea", sans-serif;
            background: linear-gradient(135deg, #ea5c54 0%, #bb6dec 100%);
        }
        body .login {
            position: relative;
            position: absolute;
            top: 20px;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;

            width: 267px;
            height: 381px;
            padding: 100px 40px 40px 40px;
            margin: auto;

            opacity: 1;
            border-top: 2px solid #d8312a;
            background: #35394a;
        }
        body .login .validation {
            position: absolute;
            top: 6px;
            right: 10px;
            z-index: 1;

            opacity: 0;
        }
        body .login .disclaimer {
            position: absolute;
            bottom: 20px;
            left: 35px;

            width: 250px;
        }
        body .login-title {
            height: 60px;

            font-size: 16px;

            text-align: left;

            color: #afb1be;
        }
        body .login-fields {
            position: absolute;
            left: 0;

            height: 208px;
        }

        body .login-fields .icon {
            position: absolute;
            top: 8px;
            left: 36px;
            z-index: 1;

            opacity: .5;
        }
        body .login-fields input[type="text"],
        body .login-fields input[type="password"] {
            left: 0;

            width: 190px;
            padding: 10px 65px;
            margin-top: -2px;

            font-family: "Gudea", sans-serif;

            color: #afb1be;
            border-top: 2px solid #393d52;
            border-right: none;
            border-bottom: 2px solid #393d52;
            border-left: none;
            outline: none;
            background: #32364a;
            box-shadow: none;
        }
        body .login-fields-user,
        body .login-fields-password {
            position: relative;
        }
        body .login-fields-submit {
            position: relative;
            top: 35px;
            right: 0;
            left: 0;

            width: 36%;
            margin: auto;
        }
        body .login-fields-submit input {
            padding: 10px 50px;
            font-size: 11px;
            -webkit-transition-duration: .2s;
            transition-duration: .2s;
            -webkit-transition-property: background,color;
            transition-property: background,color;
            text-transform: uppercase;
            outline: none;
            color: #dc6180;
            border: 2px solid #dc6180;
            border-radius: 50px;
            background: transparent;
        }

        .brand img {
            width: 30px;
        }
        #warm{
            margin: auto;
            font-family: 'Raleway', sans-serif;
            font-size: 17px;
            color: #dc6180;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class='login'>
        <div class='login-title'>
            <span>课表登录</span>
        </div>
        <div class='login-fields'>
            <div class='login-fields-user'>
                <div class='icon'>
                    <img src={{ asset('images/wechat/user.png') }}>
                </div>
                <input placeholder='姓名' type='text' autocomplete="off" id="account">
                <div class='validation'>
                    <img src={{asset('images/wechat/tick.png')}}>
                </div>
                </input>
            </div>
            <div id="warm"></div>
            <div class='login-fields-submit'>
                <input type='submit' value='登录' onclick="login()">
            </div>
        </div>
    </div>
</body>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    document.onkeydown = function (event) {
        var e = event || window.event || arguments.callee.caller.arguments[0];
        if (e&&e.keyCode == 13){
            login();
        }
    }


    function login() {
        let name = $('#account').val();
        if(name == ""){
            $('#warm').text('请输入姓名');
            return;
        }
        console.log(name);
        $.post('/wechat/login/check',{
            name: name,
        }, function (data, status) {
            if(data.code === 1){
                $('#warm').text("");
                sessionStorage.setItem('wechat',name);
                window.location = '/wechat/#/teacher/course';
            }else{
                console.log(data.msg);
                $('#warm').text("");
                $('#warm').text(data.msg);
            }
        });
    }
</script>
</html>