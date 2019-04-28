@extends('layouts.shop')
@section('title','微商城登录')
@section('content')
    <script src="{{asset('js/jquery.js')}}"></script>
    <script src="{{asset('layui/layui.js')}}"></script>
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
      <h3>还没有三级分销账号？点此<a class="orange" href="/zhubao/reg">注册</a></h3>
      <div class="lrBox">
       <div class="lrList"><input type="text" id="user_email" placeholder="输入手机号码或者邮箱号" /></div>
       <div class="lrList"><input type="text" id="user_pwd" placeholder="输入密码" /></div>
      </div><!--lrBox/-->
      <div class="lrSub">
       <input type="submit" id="submit" value="立即登录" />
      </div>
     </form>
    </div>@include('public.footer')
     <script>
         $(function(){
             layui.use(['layer'],function(){
                 var layer = layui.layer;
                 $(document).on('click','#submit',function () {
                     var user_email=$('#user_email').val();
                     var user_pwd=$('#user_pwd').val();
                     if(user_email==''){
                         layer.msg('账号不能为空',{code:5});
                         return false;
                     }
                     if(user_pwd==''){
                         layer.msg('密码不能为空',{code:5});
                         return false;
                     }
                     // 登录
                     if(user_email){
                         $.post(
                             "/zhubao/login",
                             {user_email:user_email,user_pwd:user_pwd},
                             function(res){
                                 if(res.code==5){
                                     layer.msg(res.msg,{icon:res.code});
                                 }else if(res.code==6){
                                     layer.msg(res.msg,{icon:res.code,time:2000},function(){
                                         location.href="/zhubao/index";
                                     });
                                 }else{
                                     layer.msg(res.msg,{icon:res.code,time:2000},function(){
                                         location.href="/zhubao/login";
                                     });
                                 }
                             },
                             'json'
                         );
                     }
                 })
             });
         })
     </script>
@endsection
