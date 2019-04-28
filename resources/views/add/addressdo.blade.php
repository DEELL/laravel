<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="Author" contect="http://www.webqin.net">
    <title>三级分销</title>
    <link rel="shortcut icon" href="{{asset('shop/images/favicon.ico')}}" />

    <!-- Bootstrap -->
    <script src="{{asset('js/jquery.js')}}"></script>
    <script src="{{asset('layui/layui.js')}}"></script>
    <link href="{{asset('shop/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('shop/css/style.css')}}" rel="stylesheet">
    <link href="{{asset('shop/css/response.css')}}" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="http://cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div class="maincont">
    <header>
        <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
        <div class="head-mid">
            <h1>收货地址</h1>
        </div>
    </header>
    <div class="head-top">
        <img src="{{asset('shop/images/head.jpg')}}" />
    </div><!--head-top/-->
    <form action="javascript:;" method="get" class="reg-login">
        <div class="lrBox">
            <div class="lrList"><input type="text" placeholder="收货人"  id="address_name"/></div>
            <div class="lrList"><input type="text" placeholder="详细地址" id="address_darail" /></div>
            <div class="lrList">
                <select class="area" id="province">
                    <option >省份/直辖市</option>
                    @foreach($cartInfo as $v)
                    <option value="{{$v->id}}">{{$v->name}}</option>
                     @endforeach
                </select>
            </div>
            <div class="lrList">
                <select class="area" id="city">
                    <option >区县</option>
                </select>
            </div>
            <div class="lrList">
                <select class="area" id="area">
                    <option >详细地址</option>
                </select>
            </div>
            <div class="lrList"><input type="text" placeholder="手机" id="address_tel" /></div>
            <div class="lrList2"><input type="checkbox" id="is_default">设为默认</div>
        </div><!--lrBox/-->
        <div class="lrSub">
            <input type="submit" value="保存" id="submit" />
        </div>
    </form><!--reg-login/-->

@include('public.footer')
<!--footNav/-->
</div><!--maincont-->
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="{{asset('shop/js/jquery.min.js')}}"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="{{asset('shop/js/bootstrap.min.js')}}"></script>
<script src="{{asset('shop/js/style.js')}}"></script>
<!--jq加减-->
<script src="{{asset('shop/js/jquery.spinner.js')}}"></script>
<script>
    $('.spinnerExample').spinner({});
</script>
</body>
</html>
<script>
    $(function(){
        layui.use(['layer'],function(){
            var layer=layui.layer;
            // 三级联动
            $(document).on('change','.area',function(){
               var  _this=$(this);
               var  id=_this.val();
               var _option="<option  value='0'></option>";
                _this.parent("div").nextAll('div').children("select[class='area']").html(_option);
               $.post(
                   "/zhubao/att",
                   {id:id},
                   function(res){
                       if(res.code==5){
                           layer.msg(res.msg,{icon:res.code});
                       }else{
                           for(var i in res){
                               _option+="<option  value='"+res[i]['id']+"'>"+res[i]['name']+"</option>";
                               _this.parent("div").nextAll('div').children("select[class='area']").html(_option);
                           }
                           console.log(res);
                       }
                   }
               );
            })

            // 保存收货地址
            $(document).on('click','#submit',function(){
                var obj={};
                obj.address_name=$('#address_name').val();
                obj.address_tel=$('#address_tel').val()
                obj.address_darail=$('#address_darail').val();
                obj.province=$('#province').val();
                obj.city=$('#city').val();
                obj.area=$('#area').val();
                obj.is_default=$('#is_default').prop('checked');
                if(obj.is_default==true){
                    obj.is_default=1;
                }else{
                    obj.is_default=2;
                }
                if(obj.address_name==''){
                    alert('收货人必填');
                    return false;
                }
                if(obj.address_tel==''){
                    alert('联系方式');
                    return false;
                }
                if(obj.address_name==''){
                    alert('收货人必填');
                    return false;
                }
                if(obj.addres_darail==''){
                    alert('详细地址必填');
                     return false;
                }
                if(obj.province==''){
                    alert('省份必填');
                    return false;
                }
                if(obj.city==''){
                    alert('市区必填');
                    return false;
                }
                if(obj.province==''){
                    alert('区县必填');
                    return false;
                }
                $.post(
                    "/zhubao/addsubmit",
                    obj,
                    function (res) {
                        // console.log(res);
                        layer.msg(res.msg,{icon:res.code});
                    },
                    'json'
                );
            })
        })
    })
</script>