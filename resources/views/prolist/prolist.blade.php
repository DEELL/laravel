<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="Author" contect="http://www.webqin.net">
    <title>珠宝商品</title>
    <link rel="shortcut icon" href="images/favicon.ico" />

    <!-- Bootstrap -->
    <script src="{{asset('js/jquery.js')}}"></script>
    <script src="{{asset('layui/layui.js')}}"></script>
    <link href="{{asset('shop/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('shop/css/style.css')}}" rel="stylesheet">
    {{--<link href="{{asset('shop/css/response.css')}}}" rel="stylesheet">--}}
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
            <form action="" method="get" >
                <tr>
                    <td>
                        <input type="text" name="goods_name" />
                    </td>
                    <td>
                        <button>搜索</button>
                    </td>
                </tr>

            </form>
        </div>
    </header>
    <ul class="pro-select">
        <li class="pro-selCur">
            <a href="javascript:;" class="default" field="is_new"  a_type='1'>新品</a></li>
        <li><a href="javascript:;" class="default"  field="goods_num" a_type='2'>销量</a></li>
        <li><a href="javascript:;" class="default" field="self_price" a_type='3'>价格</a></li>
    </ul><!--pro-select/-->
    <div class="prolist" id="show">
            @foreach($res as $k=>$v)
                <dl>
                    <dt><a href="/zhubao/proinfo/{{$v->goods_id}}"><img src="{{asset('shop/goodsimgs')}}/{{$v->goods_img}}" width="100" height="100" /></a></dt>
                    <dd>
                        <h3><a href="/zhubao/proinfo/{{$v->goods_id}}">{{$v->goods_name}}</a></h3>
                        <div class="prolist-price"><strong>¥{{$v->self_price}}</strong> <span>¥{{$v->market_price}}</span></div>
                        <div class="prolist-yishou"><span>5.0折</span> <em>已售：35</em></div>
                    </dd>
                    <div class="clearfix"></div>
                </dl>
            @endforeach
    </div>
 @include('public.footer')
    <script>
        $(function(){
            $(document).on('click','.default',function () {
                var _default=$(this).attr('a_type');
                var field='';
                if(_default==1){
                    field='is_new';
                    // field=1;
                }else if(_default==2){
                    // field=2;
                    field='is_hot';
                }else{
                    // field=3;
                    field='self_price';
                }
                alert(field);
                $.post(
                    "/zhubao/prolist",
                    field,
                    function (res) {
                        // $('#show').html(res);
                    }
                );
            })
        })
    </script>
