@extends('layouts.shop')
@section('title','微商城登录')
@section('content')
 <script src="{{asset('js/jquery.js')}}"></script>
 <script src="{{asset('layui/layui.js')}}"></script>
    <div class="maincont">
     <header>
      <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
      <div class="head-mid">
       <h1>会员注册</h1>
      </div>
     </header>
     <div class="head-top">
      <img src="{{asset('shop/images/head.jpg')}}" />
     </div><!--head-top/-->
     <form action="javascript:;" method="get" class="reg-login">
      <h3>已经有账号了？点此<a class="orange" href="/zhubao/login">登陆</a></h3>
      <input type="hidden" name="_token" value="'.csrf_token().'">
      <div class="lrBox">
       <div class="lrList"><input type="text"  id="user_email" placeholder="输入手机号码或者邮箱号" /></div>
       <div class="lrList2"><input type="text" id="code" placeholder="输入短信验证码" /> <button id="lrList2">获取验证码</button></div>
       <div class="lrList"><input type="text" id="user_pwd" placeholder="设置新密码（6-18位数字或字母）" /></div>
       <div class="lrList"><input type="text" id="user_pwd1" placeholder="再次输入密码" /></div>
       <input type="hidden" id="type" name="type">
      </div><!--lrBox/-->
      <div class="lrSub">
       <input type="submit" value="立即注册" id="submit" />
      </div>
     </form>
    </div><!--maincont-->
    <script>
     $(function(){
      layui.use(['layer', 'form'], function () {
       var form = layui.form;
       var layer = layui.layer;
        // 点击获取
        $(document).on('click','#lrList2',function(){
          var user_email=$("#user_email").val();
          var reg=/^[A-Za-z\d]+([-_.][A-Za-z\d]+)*@([A-Za-z\d]+[-.])+[A-Za-z\d]{2,4}$/;
          var tel=/^\d{11}$/;
         if(user_email==''){
          layer('注册账号不能为空',5);
          return false;
         }
         // 手机号发送
          if(tel.test(user_email)){

             $.post(
               "/zhubao/tel",
                     {user_email:user_email},
                     function(res){
                       if(res.code==5){
                           layer.msg(res.msg,{icon:res.code});
                       }else{
                           layer.msg(res.msg,{icon:res.code});
                       }
                     },
                 'json'
             );
           $('#type').val('1');
           // 邮箱发送
          }else if(reg.test(user_email)){
          $.post(
                  "/zhubao/email",
                  {user_email:user_email},
                  function(res){
                      if(res.code==6){
                          layer.msg('发送成功',{icon:6});
                      }else{
                          layer.msg(res.msg,{icon:res.code});
                      }
                  },
              'json'
          );
           $('#type').val('2');
         }else{
           alert('格式不正确');
          }
        });
        // 点击注册
        $(document).on('click','#submit',function(){
            var user_email=$("#user_email").val();
            var code=$("#code").val();
            var user_pwd=$("#user_pwd").val();
            var user_pwd1=$("#user_pwd1").val();
            var type=$("#type").val();
           var reg=/^\d{6,}$/;
           if(!reg.test(user_pwd)){
              layer.msg('密码最少六位',{code:5});
              return false;
           }
            if(user_pwd1!=user_pwd){
                layer.msg('密码必须和确认密码一致',{icon:5});
                return false;
            }
            $.post(
                "/zhubao/reg",
                {user_email:user_email,user_pwd:user_pwd,code:code,type:type},
                function(res){
                    console.log(res);
                }
            );
        })
     });
     });
    </script>
@endsection


