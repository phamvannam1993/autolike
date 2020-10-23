<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Clone Facebook</title>

    <!-- Bootstrap -->
    <link href="{{url('css')}}/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{url('css')}}/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="{{url('css')}}/nprogress.css" rel="stylesheet">
    <!-- Animate.css -->
    <link href="{{url('css')}}/animate.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="{{url('css')}}/custom.min.css" rel="stylesheet">

    <!-- Fix style  -->
    <style>
        .alert-danger, .alert-error {
            color: #FFF;
        }
    </style>
</head>
<body class="login">
<div>
    <div class="login_wrapper">
        <div class="animate form login_form">
            <section class="login_content">
                <form action="{{route('admin.login')}}" method="post">
                    <h1>Đăng nhập tài khoản</h1>
                    @if($messageError)
                    <div class="alert alert-danger">
                        <strong style="font-weight: 500;">{{$messageError}}.</strong>
                    </div>
                    @endif
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div>
                        <input type="text" name="email" class="form-control"
                               placeholder="Email" value="{{ isset($dataLogin['email']) ? $dataLogin['email'] : ''}}" required
                        />
                    </div>
                    <div>
                        <input type="password" value="{{ isset($dataLogin['password']) ? $dataLogin['password'] : ''}}" name="password" class="form-control" placeholder="Mật khẩu" required="" />
                    </div>
                    <div>
                        <button class="btn btn-default submit" type="submit">Đăng nhập</button>

                    </div>

                    <div class="clearfix"></div>

                    <div class="separator">
                        <p class="change_link">Bạn chưa có tài khoản?
                            <a href="#" class="to_register"> Đăng ký </a>
                        </p>

                        <div class="clearfix"></div>
                        <br />
                        <div>
                            <h1><i class="fa fa-thumbs-o-up"></i>www.autofarmer.xyz</h1>
                            <p>©2018 Bản quyền Website thuộc về www.autofarmer.xyz</p>
                        </div>
                    </div>
                </form>
            </section>
        </div>
    </div>
</div>
</body>