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
            <h1>购物车</h1>
        </div>
    </header>
    <div class="head-top">
        <img src="{{asset('shop/images/head.jpg')}}" />
    </div><!--head-top/-->
    <div class="dingdanlist" >
        <table>

            <tr>
                <td class="dingimg" width="75%" colspan="2" onClick="window.location.href='/zhubao/addressdo'">新增收货地址</td>
                <td align="right" onClick="window.location.href='/zhubao/addressdo'"><img src="{{asset('shop/images/jian-new.png')}}" /></td>
            </tr>
            <tr>
                <td class="dingimg" width="75%" colspan="2" onClick="window.location.href='/zhubao/address'">收货地址</td>
                <td align="right" onClick="window.location.href='/zhubao/address'"><img src="{{asset('shop/images/jian-new.png')}}" /></td>
            </tr>
            <tr>
                <td class="dingimg" width="75%" colspan="2">支付方式</td>
                <td align="right"><span class="hui">网上支付</span></td>
            </tr>
            <tr>
                <td class="dingimg" width="75%" colspan="2">优惠券</td>
                <td align="right"><span class="hui">无</span></td>
            </tr>
            <tr>
                <td class="dingimg" width="75%" colspan="3">商品清单</td>
            </tr>
            @foreach($app as $v)
                <input type="hidden" goods_id="{{$v->goods_id}}" class="goods_id">
            <tr >
                <td class="dingimg" width="15%"><img src="{{asset('shop/goodsimgs')}}/{{$v->goods_img}}" /></td>
                <td width="50%">
                    <h3>{{$v->goods_name}}</h3>
                    <time>{{date("Y-m-d H:i",$v->create_time)}}</time>
                </td>
                <td align="right"><span class="qingdan">X {{$v->buy_number}}</span></td>
            </tr>
            <tr>

                <td class="dingimg" width="50%" colspan="2">商品单价</td>
                <th colspan="3"><strong class="orange">¥{{$v->self_price}}</strong></th>
                <td class="dingimg" width="50%" colspan="2">商品金额</td>
                <td align="right"><strong class="orange" >¥{{$v->self_price*$v->buy_number}}</strong></td>
            </tr>
            @endforeach
            <tr>
                <td class="dingimg" width="50%" colspan="2">折扣优惠</td>
                <td align="right"><strong class="green">¥0.00</strong></td>
            </tr>
            <tr>
                <td class="dingimg" width="50%" colspan="2">抵扣金额</td>
                <td align="right"><strong class="green">¥0.00</strong></td>
            </tr>
            <tr>
                <td class="dingimg" width="50%" colspan="2">运费</td>
                <td align="right"><strong class="orange">¥20.80</strong></td>
            </tr>

        </table>
    </div><!--dingdanlist/-->


</div><!--content/-->

<div class="height1"></div>
<div class="gwcpiao">
    <table>
        <tr>
            <th width="10%"><a href="javascript:history.back(-1)"><span class="glyphicon glyphicon-menu-left"></span></a></th>
            <td width="50%">总计：<strong class="succ" style="color: red">¥{{$count}}</strong></td>
            <td width="40%"><a href="javascript:;" id="success"><input type="button" value="提交订单"></a></td>
        </tr>
    </table>
</div><!--gwcpiao/-->
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
            $(document).on('click','#success',function () {
                var _goods_id=$('.goods_id');
                var goods_id='';
                _goods_id.each(function (index) {
                    goods_id+=$(this).attr('goods_id')+',';
                });
                goods_id=goods_id.substr(0,goods_id.length-1);

                $.post(
                    "/zhubao/successsubmit",
                    {goods_id:goods_id},
                    function(res){
                        if(res.code==5){
                            layer.msg(res.font,{icon:res.code});
                        }else if(res.code==6){
                            layer.msg(res.font,{icon:res.code,time:2000},function () {
                                location.href="/zhubao/success/"+res.order_id;
                            });
                        }else{
                            layer.msg(res.font,{icon:res.code,time:2000},function(){
                                location.href="/zhubao/login";
                            });
                        }

                    },
                    'json'
                );

            })
        })
    })
</script>