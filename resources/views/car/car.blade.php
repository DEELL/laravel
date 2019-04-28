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
  <table class="shoucangtab">
    <tr>
      <td width="75%"><span class="hui">购物车共有：<strong class="orange">{{$data}}</strong>件商品</span></td>
      <td width="25%" align="center" style="background:#fff url(images/xian.jpg) left center no-repeat;">
        <span class="glyphicon glyphicon-shopping-cart" style="font-size:2rem;color:#666;"></span>
      </td>
    </tr>
  </table>
  <div class="dingdanlist">
    <table>

      <tr>
        <td width="100%" colspan="4"><a href="javascript:;"><input type="checkbox"  id="allbox" /> 全选</a></td>
      </tr>
      @foreach($res as $k=>$v)
      <tr>
        <td width="4%"><input type="checkbox" class="box" goods_id="{{$v->goods_id}}"/></td>
        <td class="dingimg" width="15%"><img src="{{asset('shop/goodsimgs')}}/{{$v->goods_img}}" /></td>
        <td width="50%">
          <h3>{{$v->goods_name}}</h3>
          <time>{{date("Y-m-d H:i",$v->create_time)}}</time>
        </td>
        <td align="right">
          <input type="text" value="{{$v->buy_number}}" class="buy_number" />
          <input type="button"  class='more'  value="＋" goods_id="{{$v->goods_id}}"/>
          <input type="button"  class="less"  value="－" />
          <input type="hidden" class="goods_num" value="{{$v->goods_num}}/">
        </td>
      </tr>
      <tr>
        <th colspan="4"><strong class="orange">¥{{$v->self_price}}</strong></th>
      </tr>
      @endforeach
      <tr>
        <td width="100%" colspan="4"><a href="javascript:;" id="delete"><input type="checkbox"  /> 删除</a></td>
      </tr>
    </table>
  </div><!--dingdanlist/-->
  <div class="height1"></div>
  <div class="gwcpiao">
    <table>
      <tr>
        <th width="10%"><a href="javascript:history.back(-1)"><span class="glyphicon glyphicon-menu-left"></span></a></th>
        <td width="50%">总计：<strong class="orange" id="pp">¥0</strong></td>
        <td width="40%"><a href="javascript:;" class="jiesuan" id="submit">去结算</a></td>
      </tr><!--pay.html-->
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
      var layer=layui.layer;
      // 点击加号
      $(document).on('click','.more',function () {
        var _this=$(this);
        // 获取文本框的值
        var byu_number=_this.prev('input').val();
        // alert(byu_number);
        // 获取库存
        var num=parseInt($('.goods_num').val());

        var goods_id=_this.attr('goods_id');
        if(parseInt(byu_number)>=num){
          _this.prop('disabled',true);
          _this.next('input').prop('disabled',false);
        }else{
          byu_number=parseInt(byu_number)+1;
          _this.prev('input').val(byu_number);
          _this.next('input').prop('disabled',false);
        }

        // 小计
        xiaoji(_this,goods_id);

        // 更改购买数量
        chdckBuynumber(goods_id,byu_number,);

        // 获取总价
        counttotal();

        // 给当前复选框选中x
        boxChecked(_this);
      });

      // 点击减号
      $(document).on('click','.less',function () {
        var _this=$(this);
        // 获取id
        var goods_id=_this.prev().attr('goods_id');
        // 获取文本框的值
        var byu_number=_this.siblings('input[class=buy_number]').val();
        if(parseInt(byu_number)==1){
          _this.prop('disabled',true);
          _this.prev('input').prop('disabled',false);
        }else{
          byu_number=parseInt(byu_number)-1;
          _this.siblings('input[class=buy_number]').val(byu_number);
          _this.prev('input').prop('disabled',false);
        }
        // 小计
        xiaoji(_this,goods_id);
        // 更改购买数量
        chdckBuynumber(goods_id,byu_number,);
        // 获取总价
        counttotal();

        // 给当前复选框选中x
        boxChecked(_this);
      });

      // 获取文本框的值
      $(document).on('blur','.buy_number',function () {
        var _this=$(this);
        // 获取id
        var goods_id=_this.next().attr('goods_id');
        // 获取文本框的值
        var byu_number=parseInt($('.buy_number').val());
        // 获取库存
        var num=parseInt($('.goods_num').val());

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
        // 更改购买数量
        chdckBuynumber(goods_id,byu_number,);
        // 小计
        xiaoji(_this,goods_id);
        // 获取总价
        counttotal();

        // 给当前复选框选中x
        boxChecked(_this);
      });

      // 全选
      $(document).on('click','#allbox',function (){
          var _this=$(this);
         var  stas=_this.prop('checked');
         $('.box').prop('checked',stas);
        // 给当前复选框选中
        counttotal()
      });
      // 获取小计
          function xiaoji(_this,goods_id){

              $.post(
                      "/zhubao/xiaoji",
                      {goods_id:goods_id},
                      function(res){
                        console.log(res);
                      }
              );
          }

          // 复选框
      $(document).on('click','.box',function(){
        // alert(11);
        // // 重新计算总价
        counttotal();
      });

      // 给当前复选框选中
      function boxChecked(_this){
        _this.parents("tr").find("input[class='box']").prop("checked",true);
        // 获取总价
        counttotal();
      }

          // 获取总价
      function counttotal(){
            var _box=$('.box');
            var goods_id='';
          _box.each(function(index){
             if($(this).prop("checked")==true){
                 goods_id+=$(this).attr("goods_id")+',';
             }
          });
            goods_id=goods_id.substr(0,goods_id.length-1);
            $.post(
                    "/zhubao/counttotal",
                    {goods_id:goods_id},
                    function(res){
                      $('#pp').text(res);
                      // alert(res);
                    }
            );
      }

          // 更改购买数量
      function chdckBuynumber(goods_id,byu_number,){
            $.post(
                    "/zhubao/chdckbuynumber",
                    {goods_id:goods_id,buy_number:byu_number},
                    function(res){
                      layer.msg(res.msg,{icon:res.code});
                    },
                    'json'
            );
      }

      // 删除
      $(document).on('click','#delete',function(){
        var _box=$('.box');
          var goods_id='';
          _box.each(function(index){
              if($(this).prop('checked')==true){
                goods_id+=$(this).attr('goods_id')+',';
              }
          });
          goods_id=goods_id.substr(0,goods_id.length-1);
          $.post(
                  "/zhubao/delete",
                  {goods_id:goods_id},
                  function(res){
                    console.log(res);
                    // layer.msg(res.msg,{icon:res.code});
                    // history.go(0);
                  },
                  'json'
          );


      })

      // 结算
      $(document).on('click','#submit',function () {
        // 实例化是否登录
        var  str=Check();
        if(str==1){
          var _box=$('.box');
          var goods_id='';
          _box.each(function(index){
            if($(this).prop('checked')==true){
              goods_id+=$(this).attr('goods_id')+',';
            }
          });
          goods_id=goods_id.substr(0,goods_id.length-1);
          if(goods_id==''){
            layer.msg('请选择一件商品',{code:5});
            return false;
          }
          location.href="/zhubao/carsubmit/"+goods_id;
        }else{
          layer.msg('清先等登录',{code:5},function () {
            location.href="/zhubao/login";
          })
        }

      });

      // 结算之前检测是否登录
      function Check(){
        var status;
        $.ajax({
          url:"/zhubao/check",
          async:false,
          success:function(res) {
            // alert(res);
            status = res;

          }
          // ,'json'
        });
        return status;

      }

    })
  })
</script>