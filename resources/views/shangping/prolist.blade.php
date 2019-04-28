<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="Author" contect="http://www.webqin.net">
    <title>三级分销</title>
    <link rel="shortcut icon" href="images/favicon.ico" />

    <!-- Bootstrap -->
    {{--分页--}}
    <link rel="stylesheet" href="{{asset('css/page.css')}}"type="text/css"/>
    <script src="{{asset('layui/layui.js')}}"></script>
    <link href="{{asset('shop/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('shop/css/style.css')}}" rel="stylesheet">
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
    </header>><!--pro-select/-->
    <div class="prolist">
        @foreach($data as $v)
        <dl>
            <dt><a href="proinfo/{{$v->goods_id}}"><img src="{{asset('shop/goodsimgs')}}/{{$v->goods_img}}" width="100" height="100" /></a></dt>
            <dd>
                <h3><a href="javascript:;">{{$v->goods_name}}</a></h3>
                <div class="prolist-price"><strong>¥{{$v->self_price}}</strong> <span>¥{{$v->market_price}}</span></div>
            </dd>
            <hr/>

            <div class="clearfix"></div>
        </dl>
            <dl>
                <dd>
                    <a href="proinfodelete/{{$v->goods_id}}">删除</a>
                    <a href="proinfoupdate/{{$v->goods_id}}">修改</a>
                </dd>
            </dl>
            @endforeach
            {{ $data->links()}}
    </div><!--prolist/-->
    <div class="height1"></div>
    <div class="footNav">
        <dl>
            <a href="index.html">
                <dt><span class="glyphicon glyphicon-home"></span></dt>
                <dd>微店</dd>
            </a>
        </dl>
        <dl class="ftnavCur">
            <a href="prolist.html">
                <dt><span class="glyphicon glyphicon-th"></span></dt>
                <dd>所有商品</dd>
            </a>
        </dl>
        <dl>
            <a href="car.html">
                <dt><span class="glyphicon glyphicon-shopping-cart"></span></dt>
                <dd>购物车 </dd>
            </a>
        </dl>
        <dl>
            <a href="user.html">
                <dt><span class="glyphicon glyphicon-user"></span></dt>
                <dd>我的</dd>
            </a>
        </dl>
        <div class="clearfix"></div>
    </div><!--footNav/-->
</div><!--maincont-->
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="{{asset('shop/js/jquery.min.js')}}"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="{{asset('shop/js/bootstrap.min.js')}}"></script>
<script src="{{asset('shop/js/style.js')}}"></script>
<!--焦点轮换-->
<script src="{{asset('shop/js/jquery.excoloSlider.js')}}"></script>
<script>
    $(function () {
        $("#sliderA").excoloSlider();
    });
</script>
</body>
</html>