<!DOCTYPE html>
<html class="x-admin-sm">

<head>
    <meta charset="UTF-8">
    <title>欢迎页面-X-admin2.2</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('admin.public.styles')
    @include('admin.public.script')
</head>

<body>
    <div class="layui-fluid">
        <div class="layui-row">
            <form class="layui-form">
                <div class="layui-form-item">
                    <label for="L_email" class="layui-form-label">
                        <span class="x-red">*</span>角色名</label>
                    <div class="layui-input-inline">
                        <input type="text" id="L_email" name="rolename" required="" lay-verify="rolename" autocomplete="off" class="layui-input">
                    </div>
                    <div class="layui-form-item">
                        <label for="L_username" class="layui-form-label">
                            描述</label>
                        <div class="layui-input-block">
                            <input type="text" id="L_username" name="description" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label for="L_repass" class="layui-form-label"></label>
                        <button class="layui-btn" lay-filter="add" lay-submit="">增加</button></div>
            </form>
        </div>
    </div>
    <script>
        layui.use(['form', 'layer', 'jquery'],
            function() {
                $ = layui.jquery;
                var form = layui.form,
                    layer = layui.layer;

                //自定义验证规则
                form.verify({
                    rolename: function(value) {
                        if (value.length < 4) {
                            return '角色名至少得4个字符啊';
                        }
                    },
                });

                //监听提交
                form.on('submit(add)',
                    function(data) {
                        console.log(data);
                        //发异步，把数据提交给php
                        $.ajax({
                            url: '/admin/role',
                            method: 'post',
                            dataType: 'json',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: data.field,
                            success: (data) => {
                                // console.log(data);
                                if (data.status) {
                                    layer.alert(data.msg, {
                                        icon: 6
                                    }, () => {
                                        //关闭当前frame
                                        xadmin.close();
                                        // 可以对父窗口进行刷新 
                                        xadmin.father_reload();
                                    });
                                } else {
                                    layer.alert(data.msg, {
                                        icon: 5
                                    });
                                }
                            },
                            error: () => {

                            }
                        });

                        return false;
                    });

            });
    </script>
</body>

</html>