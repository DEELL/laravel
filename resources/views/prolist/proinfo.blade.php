<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="Author" contect="http://www.webqin.net">
    <title>商品详情</title>
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
            <h1>产品详情</h1>
        </div>
    </header>
    <div id="sliderA" class="slider">
        @foreach($imgs as $k=>$v)
        <img src="{{asset('shop/goodsimgs')}}/{{$v}}" />
            @endforeach
    </div><!--sliderA/-->
    <table class="jia-len">
        <tr>
            <th><strong class="orange">{{$ress->self_price}}</strong></th>
            <td>
                <input type="text" value="1" id="buy_number"/>
                <input type="button"  id='more'  value="＋"/>
                <input type="button"  id="less"  value="－" />
                <input type="hidden" id="goods_num" value="{{$ress->goods_num}}">
                <input type="hidden" id="goods_id" value="{{$ress->goods_id}}">
            </td>
        </tr>
        <tr>
            <td>
                <strong>{{$ress->goods_name}}</strong>
                <p class="hui">{{$ress->goods_desc}}</p>
            </td>
            <td align="right">
                <a href="javascript:;"  ><span class="glyphicon glyphicon-star-empty" class="shoucang"></span></a>
            </td>
        </tr>
    </table>
    <div class="height2"></div>
    <h3 class="proTitle">商品规格</h3>
    <ul class="guige">
        <li class="guigeCur"><a href="javascript:;">50ML</a></li>
        <li><a href="javascript:;">100ML</a></li>
        <li><a href="javascript:;">150ML</a></li>
        <li><a href="javascript:;">200ML</a></li>
        <li><a href="javascript:;">300ML</a></li>
        <div class="clearfix"></div>
    </ul><!--guige/-->
    <div class="height2"></div>
    <div class="zhaieq">
        <a href="javascript:;" class="zhaiCur">商品简介</a>
        <a href="javascript:;">商品参数</a>
        <a href="javascript:;" style="background:none;">订购列表</a>
        <div class="clearfix"></div>
    </div><!--zhaieq/-->
    <div class="proinfoList">
        <img src="{{asset('shop/images/image4.jpg')}}" width="636" height="822" />
    </div><!--proinfoList/-->
    <div class="proinfoList">
        暂无信息....
    </div><!--proinfoList/-->
    <div class="proinfoList">
        暂无信息......
    </div><!--proinfoList/-->
    <table class="jrgwc">
        <tr>
            <th>
                <a href="index.html"><span class="glyphicon glyphicon-home"></span></a>
            </th>
            <td><a href="car.html"></a><input type="button" id="submit" value="加入购物车"></td>
        </tr>
    </table>
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
<!--jq加减-->
{{--<script src="{{asset('shop/js/jquery.spinner.js')}}"></script>--}}
<script>
    // $('.spinnerExample').spinner({});
</script>
</body>
</html>
<script>
    $(function(){
        layui.use(['layer'],function(){
            var layer = layui.layer;
            // 点击加号
            $(document).on('click','#more',function () {
                var _this=$(this);
                // 获取文本框的值
                var byu_number=parseInt($('#buy_number').val());
                // 获取库存
                var num=parseInt($('#goods_num').val());

                if(byu_number>=num){
                    _this.prop('disabled',true);
                    _this.next('input').prop('disabled',false);
                }else{
                    byu_number=byu_number+1;
                    $('#buy_number').val(byu_number);
                    _this.next('input').prop('disabled',false);
                }
            });

            // 点击减号
            $(document).on('click','#less',function () {
                var _this=$(this);
                // 获取文本框的值
                var byu_number=parseInt($('#buy_number').val());

                if(byu_number==1){
                    _this.prop('disabled',true);
                    _this.prev('input').prop('disabled',false);
                }else{
                    byu_number=byu_number-1;
                    $('#buy_number').val(byu_number);
                    _this.prev('input').prop('disabled',false);
                }
            });

            // 获取文本框的值
            $(document).on('blur','#buy_number',function () {
                var _this=$(this);
                // 获取文本框的值
                var byu_number=parseInt($('#buy_number').val());
                // 获取库存
                var num=parseInt($('#goods_num').val());

                var reg=/^\d{1,}$/;
                
                if(byu_number==''&&byu_number<=1){
                    _this.val(1);
                }else if (byu_number>num) {
                    _this.val(parseInt(num));
                }else if(!reg.test(byu_number)){
                   _this.val(1);
                }else{
                    _this.val(parseInt(byu_number));
                }
            });

            // 加入购车
            $(document).on('click','#submit',function () {
                // 获取商品id
                var goods_id=$('#goods_id').val();
                // 获取购买的数量
                var byu_num=$('#buy_number').val();
                $.post(
                    "/zhubao/cartt",
                    {goods_id:goods_id,byu_num:byu_num},
                    function (res) {
                        if(res.code==6){
                            layer.msg(res.msg,{icon:res.code},function(){
                                window.location.href="/zhubao/car";
                            });
                        }else if(res.code==5){
                            layer.msg(res.msg,{icon:res.code},function(){
                                window.location.href="/zhubao/login";
                            });
                        }else{
                            layer.msg(res.msg,{icon:res.code});
                        }
                    },
                    'json'
                );
            });
            // 收藏
            $(document).on('click','.shoucang',function () {
                var ss=$('.shoucang').text();

            })
        })
    });
</script>