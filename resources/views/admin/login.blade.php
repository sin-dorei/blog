<!doctype html>
<html class="x-admin-sm">

<head>
    <meta charset="UTF-8">
    <title>后台管理登录</title>
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    @include('admin.public.styles')
    <link rel="stylesheet" href="{{asset('X-admin/css/login.css')}}">
    @include('admin.public.script')
    <script src="https://cdn.bootcdn.net/ajax/libs/jquery-validate/1.19.2/jquery.validate.js"></script>
    <script src="https://cdn.bootcdn.net/ajax/libs/jquery-validate/1.19.2/additional-methods.js"></script>
    <script src="https://cdn.bootcdn.net/ajax/libs/jquery-validate/1.19.2/localization/messages_zh.js"></script>
    <style>
        .invalid {
            padding-top: 15px;
            display: block;
            color: #e00;
            font-size: 14px;
        }
        .layui-icon {
            font-size: 18px !important; 
            position: relative; 
            left: 0; 
            top: 2px;
        }
    </style>
</head>

<body class="login-bg">

    <div class="login">
        <div class="message">后台管理登录</div>
        <div id="darkbannerwrap"></div>
        <form id="login-form" action="{{ url('admin/doLogin') }}" method="post" class="layui-form">
            @csrf
            <input id="mname" class="layui-input" name="mname" type="text" placeholder="用户名" autocomplete="off" value="{{ old('mname') }}" maxlength="16">
            <hr class="hr20">
            <input class="layui-input" name="pass" type="password" placeholder="密码" autocomplete="off" maxlength="18">
            <hr class="hr15">
            <div class="layui-inline">
                <div class="layui-input-inline">
                    <input id="captcha" class="layui-input" name="captcha" type="text" placeholder="验证码" autocomplete="off" maxlength="4">
                </div>
                <div class="layui-input-inline">
                    <label class="layui-form-label">
                        <img src="{{ url('admin/captcha') }}" onclick="re_captcha(this)">
                    </label>
                </div>
            </div>
            <hr class="hr20">
            <input id="login-btn" value="登录" type="submit">
            <hr class="hr20">
        </form>
    </div>

    <script>
        re_captcha = (obj) => {
            $url = "{{ url('admin/captcha') }}" + '?' + Math.random()
            obj.src = $url
        }

        $(() => {
            @if ($errors->any())
                var errors = '<ul>'

                @foreach ($errors->all() as $error)
                    errors += '<li>{{ $error }}</li>'
                @endforeach

                errors += '</ul>'

            layui.use(['layer', 'form'], () => {
                const layer = layui.layer
                layer.open({
                    title: '提示',
                    offset: '300px',
                    resize: false,
                    skin: 'layui-layer-molv',
                    content: errors
                })
            })
            @endif
            
            $("#login-form").validate({
                errorClass: 'invalid',
                errorPlacement: function(error, element) {
                    if (element.attr('name') == 'captcha') {
                        error.insertAfter(element.parent().parent("div"));
                    } else {
                        error.insertAfter(element)
                    }
                },
                rules: {
                    mname: {
                        required: true,
                        mname: true,
                    },
                    pass: {
                        required: true,
                        pass: true,
                    },
                    captcha: {
                        required: true,
                        remote: {
                            url: "{{ url('admin/checkCaptcha') }}",
                            type: "post",
                            dataType: "json",
                            data: {
                                captcha: () => $("#captcha").val(),
                                _token: () => '{{ csrf_token() }}',
                            }
                        }
                    }
                },
                messages: {
                    mname: {
                        required: '<i class="layui-icon layui-icon-close-fill"></i> 用户名不能为空',
                    },
                    pass: {
                        required: '<i class="layui-icon layui-icon-close-fill"></i> 密码不能为空',
                    },
                    captcha: {
                        required: '<i class="layui-icon layui-icon-close-fill"></i> 验证码不能为空',
                        remote: '<i class="layui-icon layui-icon-close-fill"></i> 验证码不正确'
                    }
                },
                submitHandler: (form) => {
                    $('#login-btn').val('登录中...')
                    $('#login-btn').css('opacity', 0.6)
                    form.submit()
                },
            })

            $.validator.addMethod("mname", (value, element) => {
                return /^[a-zA-Z_]{1}[\w]{3,15}$/.test(value)
            }, '<i class="layui-icon layui-icon-close-fill"></i> 用户名不合法（只能为4-16位的字母、数字、下划线的组合，且不能以数字开头）')

            $.validator.addMethod("pass", (value, element) => {
                return /^[\w]{6,18}$/.test(value)
            }, '<i class="layui-icon layui-icon-close-fill"></i> 密码不合法（只能为6-18位的字母、数字、下划线的组合）')
        })
    </script>
    <!-- 底部结束 -->
</body>

</html>