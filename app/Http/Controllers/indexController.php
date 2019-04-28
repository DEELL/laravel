<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\DetailModel;
use DB;
use Mail;
use \Log;  //日志
class indexController extends Controller
{
//    首页
    public function index(Request $request){

//        查询分类
        $res=DB::table('shop_category')->where(['cate_navshow'=>1])->get();
//        dd($res);
//        首页展示
        $appt=DB::table('shop_goods')->where(['is_new'=>1])->get();
//        轮播图
        $data=DB::table('shop_goods')->where(['is_hot'=>1])->select('goods_img','goods_id')->orderBy('goods_id','desc')->get();
//        dd($data);

            return view('index/index',compact('res','appt','data'));
    }

//    登录
    public  function login(Request $request){
        if($request->Post()){
           $user_email=$request->user_email;
           $user_pwd=$request->user_pwd;

//           账号非空
            if($user_pwd==''){
                return [
                    'msg'=>'密码不能为空',
                    'code'=>5
                ];
            }

//            密码非空
            if($user_email==''){
                return [
                    'msg'=>'账号不能为空',
                    'code'=>5
                ];
            }

//            手机号
            $where1=[
                'user_tel'=>$user_email,
            ];

//            邮箱
            $where2=[
                'user_email'=>$user_email
            ];
            $res=DB::table('shop_user')->where($where1)->orWhere($where2)->first();
            if(!$res){
                return [
                    'msg'=>'账号或密码有误',
                    'code'=>5
                ];
            }else{
                if(md5($user_pwd)==$res->user_pwd){
//                    dump(11);
                    $user=[
                        'user'=>$user_email,
                        'user_id'=>$res->user_id
                    ];
                    $request->session()->put('userlogin',$user);
                    return [
                        'msg'=>'登录成功',
                        'code'=>6
                    ];
                }else{
                    return [
                        'msg'=>'登录失败',
                        'code'=>2
                    ];
                }
            }
        }else{
            return view('login.login');
        }

    }

//    注册
    public  function reg(Request $request){
        if($request->Post()){
           $user_email=$request->user_email;
            $user_pwd=$request->user_pwd;
            $code=$request->code;
            $type=$request->type;
            if($type==2){
//            取出session
                $session=$request->session()->get('Email');
                if($code!=$session['code']){
                  return [
                      'msg'=>'验证码不正确',
                      'code'=>5
                  ];
                }
                $res=DB::table('shop_user')->insert(
                    ['user_email' => $user_email, 'user_pwd'=> md5($user_pwd),'user_code'=>$code]
                );
                if($res){
                    return [
                        'msg'=>'注册成功',
                        'code'=>6
                    ];
                }

            }else{
//                取出session
                $session=$request->session()->get('Tel');
                if($code!=$session['code']){
                    return [
                        'msg'=>'验证码不正确',
                        'code'=>5
                    ];
                }
                $res=DB::table('shop_user')->insert(
                    ['user_tel' => $user_email, 'user_pwd' => md5($user_pwd),'user_code'=>$code]
                );
                if($res){
                    return [
                        'msg'=>'注册成功',
                        'code'=>6
                    ];
                }
            }



        }else{
            return view('login.reg');
        }

    }

//    邮箱发送验证码
    public function email(Request $request){
        $user_email=$request->user_email;
        if(!$user_email){
            $this->error('请填写邮箱');die;
        }

        $str=DB::table('shop_user')->where(['user_email'=>$user_email])->first();
        if($str){
                return [
                    'msg'=>'邮箱已注册',
                    'code'=>5
                ];
        }else{
            //        生成随机数
            $code = rand(100000, 999999);
            $res=Mail::send('login/shitu',['code'=>$code], function ($message) use ($user_email) {
//                设置主题
                $message->subject("珠宝微商城");
//                设置接受方
                $message->to($user_email);
            });
            if(!$res){
//                先清除session
                $request->session()->forget('Email');
//                存session
                $request->session()->put('Email',['code'=>$code,'user_email'=>$user_email]);
                return [
                    'msg'=>'发送成功',
                    'code'=>6
                ];
            }
        }
    }

//    注册账号手机发送短信
    public function tel(Request $request){
    $user_tel=$request->user_email;
    $res=DB::table('shop_user')->where(['user_tel'=>$user_tel])->first();
    if($res){
        return[
            'msg'=>'手机号已注册',
            'code'=>5
        ];
    }else{
        $code = rand(100000, 999999);
        $host = "http://dingxin.market.alicloudapi.com";
        $path = "/dx/sendSms";
        $method = "POST";
        $appcode = "61f7ac94b3ba42c58641a53f06c33c67";
        $headers = array();
        array_push($headers, "Authorization:APPCODE " . $appcode);
        $querys = "mobile=".$user_tel."&param=code%3A".$code."&tpl_id=TP1711063";
        $bodys = "";
        $url = $host . $path . "?" . $querys;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        if (1 == strpos("$".$host, "https://"))
        {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        $srt=json_decode(curl_exec($curl),true);
        if($srt['return_code']==00000){
//            清除session
            $request->session()->forget('Tel');
//            存session
            $request->session()->put('Tel',['code'=>$code,'user_tel'=>$user_tel]);
            return [
                'msg'=>'发送成功',
                'code'=>6
            ];
        }
    }

    }

//    商品
    public function prolist($id=0){
//        $mem= new \Memcache();
//        $res=mem('127.0.0.1',11211);
        $goods_name=\request()->goods_name;
//        $field=$request->field;

        $cate_id=\request()->id;

        if($cate_id==0){
            $res=cache('res_'.$cate_id);
            $res=cache('ress'.$cate_id);
            dump(8);
            if(!$res) {
                dump(3);
//            搜索
                if ($goods_name) {
                    dump(1);
                    $where = [
                        ['goods_name', 'like', "%$goods_name%"]
                    ];
                    $res = DB::table('shop_goods')->where($where)->get();
                    cache(['ress'.$cate_id => $res], 1);
                } else {
                    dump(2);
                    $res = DB::table('shop_goods')->get();

                }
                cache(['res_' . $cate_id => $res], 1);
            }
                return view('prolist.prolist', compact('res'));

       }else {
            $res = cache('res_' . $cate_id);
            if (!$res) {
                $cateInfo = DB::table('shop_category')->get();
                // 获取所有子类id--递归
                $ppt = $this->getCateId($cateInfo, $cate_id);
                $res = DB::table('shop_goods')
                    ->whereIn('cate_id', $ppt)
                    ->get();
                cache(['res_' . $cate_id => $res], 60 * 24);
            }
            return view('prolist.prolist', compact('res'));
        }


    }

    // 获取所有子类id--递归
    function getCateId($cateInfo,$cate_id){
        static $id=[]; //静态方法
        foreach($cateInfo as $k=>$v){
            if($v->pid==$cate_id){
                $id[]=$v->cate_id;
                $this->getCateId($cateInfo,$v->cate_id);
            }
        }
        return $id;
    }


//    商品详情
    public  function proinfo ($id){
        if($id){
            $ress=DB::table('shop_goods')->where(['goods_id'=>$id])->first();
            $imgs=explode('|',rtrim($ress->goods_imgs,'|'));
//            dd($goods_imgs);
            return view('prolist/proinfo',compact('ress','imgs'));
        }

    }

//    加入购物车
    public function cartt(Request $request){
            $goods_id=$request->goods_id;
            $buy_number=$request->byu_num;

        //        取session
        $session= $request->session()->get('userlogin');
        $user_id=$session['user_id'];
//        判断是否登录
//        if(empty($session)) {
//            return [
//                'msg' => '请先登录',
//                'code' => 5
//            ];
//        }
        //            商品id非空
            if(empty($goods_id)){
                return  [
                    'msg'=>'请选择一件商品',
                    'code'=>2
                ];
            }
//            购买数量非空
        if(empty($buy_number)){
            return  [
                'msg'=>'购买数量能为空',
                'code'=>2
            ];
        }

        $whereinfo=[
                'goods_id'=>$goods_id,
                'user_id'=>$user_id,
                'cart_status'=>1
        ];
        $ress=DB::table('shop_cart')->where($whereinfo)->first();
//        查询到做累加
            if($ress){
//                检测库存
                $appt=$this->checkGoodsNum($goods_id,$ress->buy_number,$buy_number);
                if($appt==true){
                    $where=[
                        'buy_number'=>$buy_number+$ress->buy_number,
                        'update_time'=>time()
                    ];

                    $attr=DB::table('shop_cart')
                        ->where($whereinfo)
                        ->update($where);
                    if($attr){
                        return[
                            'msg'=>'加入购车成功',
                            'code'=>6
                        ];
                    }else{
                        return[
                            'msg'=>'加入购车失败',
                            'code'=>5
                        ];
                    }
                }
            }else{
                $where=[
                    'goods_id'=>$goods_id,
                    'user_id'=>$user_id,
                    'buy_number'=>$buy_number,
                    'create_time'=>time(),
                    'update_time'=>time()
                ];
                $resss=DB::table('shop_cart')->insert($where);
                   if($resss){
                       return[
                           'msg'=>'加入购车成功',
                           'code'=>6
                       ];
                   }else{
                       return[
                           'msg'=>'加入购车失败',
                           'code'=>5
                       ];
                   }
            }
        }

//        购车展示
    public function car(Request $request){
        //        取session
        $session= $request->session()->get('userlogin');
        $user_id=$session['user_id'];
        $where=[
            'user_id'=>$user_id,
            'cart_status'=>1
        ];
        $res=DB::table('shop_goods as g')
            ->join("shop_cart as c",'g.goods_id','=','c.goods_id')
            ->where($where)
            ->get();
//        $res=array_reverse($res,desc);
       $data= DB::table('shop_cart')->where($where)->count();
        if($res){
            return view('car/car',compact('res','data'));
        }

    }
    //    检测库存
    public function checkGoodsNum($goods_id,$num,$buy_number){
//        dump(111);die;
        $goodsWhere=[
            'goods_id'=>$goods_id
        ];
        $arr=DB::table('shop_goods')->where($goodsWhere)->value('goods_num');
//        dump($arr);die;
        if(($buy_number+$num)>$arr){
            $n=$buy_number-$num;
            echo ( "购买的数量超过库存，您还可以购买'.$n.'件");
            return false;
        }else{
            return true;
        }
    }

//    购物车小计
        public function xiaoji(Request $request){
        $goods_id=$request->all();
            if(empty($goods_id)){
                echo 0;
            }
        }

//    更改购买数据
    public function chdckbuynumber(Request $request){
        $goods_id=$request->goods_id;
        $buy_number=$request->buy_number;
//        取session
        $session= $request->session()->get('userlogin');
        $user_id=$session['user_id'];
        $appt=$this->checkGoodsNum($goods_id,$buy_number,0);
        if($appt==true){
            $where=[
                'goods_id'=>$goods_id,
                'user_id'=>$user_id
            ];
            $up=[
                'buy_number'=>$buy_number,
                'update_time'=>time()
            ];
            $res=DB::table('shop_cart')
                ->where($where)
                ->update($up);
        }else{
            return[
                'msg'=>'购买数量超出库存',
                'code'=>5
            ];
        }
    }

//    获取总价
    public function counttotal(){
        $goods_id=request()->goods_id;

        //        取session
        $session= request()->session()->get('userlogin');
        $user_id=$session['user_id'];
        $where=[
            'cart_status'=>1,
            'user_id'=>$user_id
        ];
        $goods_id=explode(',',$goods_id);
        $ress=DB::table('shop_cart as c')
            ->select('buy_number','self_price','c.goods_id')
            ->join('shop_goods as g','c.goods_id','=','g.goods_id')
            ->where($where)
            ->get();
        $count=0;
        foreach ($ress as $k=>$v){
            foreach ($goods_id as $key=>$val){
                if($v->goods_id==$val){
//                    dump($v);
                    $count+=$v->buy_number*$v->self_price;
                }
            }

        }
        return $count;
    }

//    删除购物车商品
    public function delete(){
        $goods_id=request()->goods_id;
        $session= request()->session()->get('userlogin');
        $user_id=$session['user_id'];
        $goods_id=explode(',',$goods_id);
//        dd($goods_id);
        $where=[
            'user_id'=>$user_id,
        ];
//        dd($where);
        $where1=[
            'cart_status'=>2
        ];
        $ress=DB::table('shop_cart')
            ->where($where)->whereIn('goods_id',$goods_id)
            ->update($where1);
//        dd($ress);
        if($ress){
            return [
                'msg'=>'删除成功',
                'code'=>6
            ];
        }else{
            return [
                'msg'=>'删除失败',
                'code'=>5
            ];
        }
    }

    public  function check(){
        $session=\request()->session()->get('userlogin');
        $user_id=$session['user_id'];
        if($user_id){
            echo 1;
        }else{
            echo 2;
        }
    }

//    购物车结算
    public function paysubmit(){
        $goods_id=\request()->goods_id;
        //        取session
        $session= request()->session()->get('userlogin');
        $user_id=$session['user_id'];

        if(empty($goods_id)){
            return[
                'msg'=>'请选择一件商品',
                'code'=>5
            ];
        }
    }

//    个人中心
    public function user(){
        return view('user.user');
    }

//    收货地址
    public function address(){
        $session=\request()->session()->get('userlogin');
        $user_id=$session['user_id'];
        $where=[
            'address_status'=>1,
            'user_id'=>$user_id
        ];
        $ress=DB::table('shop_address')->where($where)->get();
        $arr = json_decode(json_encode($ress),true);
        if(!empty($arr)){
            foreach ($arr as $k=>$v){
                $arr[$k]['province']=DB::table('shop_area')->where(['id'=>$v['province']])->value('name');
                $arr[$k]['city']=DB::table('shop_area')->where(['id'=>$v['city']])->value('name');
                $arr[$k]['area']=DB::table('shop_area')->where(['id'=>$v['area']])->value('name');
            }
//            dump($arr);
            $arr = json_decode(json_encode($arr));
        }
        return view('add.address',compact('arr'));
    }

//    修改收货地址
    public function addresss($id){
        if(empty($id)){
            return [
                'msg'=>'请选择修改的地址',
                'code'=>5
            ];
        }
        $ress=DB::table('shop_address')->where( 'address_id',$id)->first();
//        三级联动
        $cartInfo=$this->getAreaInfo(0);
//        市
        $city=$this->getAreaInfo($ress->province);
//        区县
        $area=$this->getAreaInfo($ress->city);
        return view('add/addresss',compact('cartInfo','ress','city','area'));
    }

//    修改地址执行
    public function addsubmitdo(){
        $ress=\request()->all();
        //        取session
        $session= \request()->session()->get('userlogin');
        $user_id=$session['user_id'];
        if($ress['is_default']==1){
            $res=DB::table('shop_address')->where('address_id',$ress['address_id'])->update($ress);
            if($res){
                $resss=DB::table('shop_address')->where('user_id',$user_id)->where('address_id','!=',$ress['address_id'])->update(['is_default'=>2]);
                return [
                    'msg'=>'修改成功',
                    'code'=>6
                ];
            }

        }else{
            $res=DB::table('shop_address')->where('address_id',$ress['address_id'])->update($ress);
            if($res){
                return [
                    'msg'=>'修改成功',
                    'code'=>6
                ];
            }else{
                return [
                    'msg'=>'修改失败',
                    'code'=>5
                ];
            }
        }

    }
//    新增收货地址
    public function addressdo(){
//        三级联动 省
        $cartInfo=$this->getAreaInfo(0);
        return view('add.addressdo',compact('cartInfo'));
    }

//    三级联动
    public function getAreaInfo($pid){
        $where=[
                'pid'=>$pid
        ];
        $ress=DB::table('shop_area')->where($where)->get();
        if($ress){
            return $ress;
        }else{
            return false;
        }
    }

//    二级联动
    public function att(){
        $id=\request()->id;
        if(empty($id)){
            return [
                'msg'=>'选择一个',
                'code'=>5
            ];
        }
        $where=[
            'pid'=>$id
        ];
       $attp=$this->getAreaInfo($id);
        return $attp;
    }

//    添加收货地址
    public  function addsubmit(){
        $res=\request()->all();
        //        取session
        $session= \request()->session()->get('userlogin');
        $res['user_id']=$session['user_id'];
        $res['create_time']=time();
        if(empty($res['user_id'])){
            return [
                'msg'=>'请先登录',
                'code'=>2
            ];
        }
        $where=[
            'user_id'=>$res['user_id']
            ,'address_status'=>1
        ];
//                dd($res);
//        判断有没有此用户
       $resss= DB::table('shop_address')->where($where)->first();
       if($resss !=''){
           if($res['is_default']==1){
               DB::table('shop_address')->where('user_id',$res['user_id'])->update(['is_default'=>2]);
           }
           $app=DB::table('shop_address')->insert($res);

           if($app){
               return [
                   'msg'=>'添加成功',
                   'code'=>6
               ];
           }else{
               return [
                   'msg'=>'添加失败',
                   'code'=>5
               ];
           }
       }else{
           $app=DB::table('shop_address')->insert($res);
           if($app){
               return [
                   'msg'=>'添加成功',
                   'code'=>6
               ];
           }else{
               return [
                   'msg'=>'添加失败',
                   'code'=>5
               ];
           }
       }
    }

//    去结算
    public function pay(){
        $goods_id=\request()->goods_id;
        //        取session
        $session= request()->session()->get('userlogin');
        $user_id=$session['user_id'];
        $goods_id=explode(',',$goods_id);
        $where=[
            'user_id'=>$user_id,
            'cart_status'=>1
        ];
//        DB::table('shop_cart')->whereIn()
       $app= DB::table('shop_cart as c')
            ->join("shop_goods as g",'c.goods_id','=','g.goods_id')
            ->where($where)
           ->whereIn('c.goods_id',$goods_id)
            ->get();
       $count=0;
       foreach ($app as $k=>$v){
           foreach ($goods_id as $key=>$val){
               if($v->goods_id==$val){
                   $count+=$v->buy_number*$v->self_price;
               }
           }
       }
       $where1=[
           'user_id'=>$user_id
           ,'is_default'=>1,
           'address_status'=>1
       ];
//        $data=DB::table('shop_address')->where($where1)->first();
//
//        $data=json_decode(json_encode($data),true);
//        dump($data);
////        三级联动
//        $data['province']=$this->getAreaInfo(0);
////        市
//        $data['city']=$this->getAreaInfo($data->province);
////        区县
//        $data['area']=$this->getAreaInfo($data->city);
//        dd($data);
        return view('pay.pay',compact('app','count'));
    }

    //    订单号生成
    public function createOrderNo(){
        //        取session
        $session= request()->session()->get('userlogin');
        $user_id=$session['user_id'];
        return time('Ymd').rand(1000,9999).$user_id;
    }

    //    订单总金额
    public function getOrderAmount($goods_id){
        //        取session
        $session= request()->session()->get('userlogin');
        $user_id=$session['user_id'];
        $goods_id=explode(',',$goods_id);
        $where=[
            'user_id'=>$user_id,
            'cart_status'=>1
        ];
//        DB::table('shop_cart')->whereIn()
        $app= DB::table('shop_cart as c')
            ->join("shop_goods as g",'c.goods_id','=','g.goods_id')
            ->where($where)
            ->whereIn('c.goods_id',$goods_id)
            ->get();
        $count=0;
        foreach ($app as $k=>$v){
            foreach ($goods_id as $key=>$val){
                if($v->goods_id==$val){
                    $count+=$v->buy_number*$v->self_price;
                }
            }
        }
        return $count;
    }

//    提交订单
    public function successsubmit(){
        $goods_id=\request()->goods_id;
        //        取session
        $session= request()->session()->get('userlogin');
        $user_id=$session['user_id'];
        if(empty($user_id)){
                return [
                    'msg'=>'请先登录',
                    'code'=>5
                ];
        }
        if(empty($goods_id)){
            return [
                'msg'=>'请选择下单的商品',
                'code'=>2
            ];
        }
        // 启动事务
        DB::beginTransaction();
        try{

//            订单号
            $order_no=$this->createOrderNo();    //    订单号生成
            $order_amount=$this->getOrderAmount($goods_id);//总金额
            $cartInfo['order_no']=$order_no;
            $cartInfo['order_amount']=$order_amount;
            $cartInfo['user_id']=$user_id;
            $cartInfo['create_time']=time();
            $cartInfo['update_time']=time();
            $res1=DB::table('shop_order')->insertGetId($cartInfo);
            if(empty($res1)){
                throw new \Exception('订单详情信息写入失败');
            }

//            订单详情
//        订单详情表添加 订单id（获取刚刚添加的自增id）
            $order_id=$res1;//获取刚刚订单表添加的id
            $where=[
                'is_default'=>1,
                'user_id'=>$user_id
            ];
            $order_address=DB::table('shop_address')
                ->where($where)
                ->select('address_name','address_tel','address_darail','province','city','area')
                ->first();
            $order_address->order_id=$order_id;
            $order_address->user_id=$user_id;
            $order_address->create_time=time();
            $order_address->update_time=time();
            $order_address=json_decode(json_encode($order_address),true);
            $res2=DB::table('shop_order_address')->insert($order_address);
            if(empty($res2)){
                throw new \Exception('订单收货地址写入失败');
            }
//            订单商品详情
            $goods_id=explode(',',$goods_id);
            $where=[
                'user_id'=>$user_id,
                'cart_status'=>1
            ];
            $data=DB::table('shop_cart as c')
                ->select('buy_number','g.goods_id','self_price','goods_name','goods_img')
                ->join("shop_goods as g",'c.goods_id','=','g.goods_id')
                ->where($where)
                ->get();
//            添加字段
            $date=[];
                foreach ($data as $k=>$v){
                    foreach ($goods_id as $key=>$val){
                        if($v->goods_id==$val){
                            $v->order_id=$order_id;
                            $v->create_time=time();
                            $v->update_time=time();
                            $v->user_id=$user_id;
                             $date[]=$v;
                        }
                    }
                }

//                对象转换成数组
                $date=json_decode(json_encode($date),true);
                $res3=DB::table('shop_order_detail')->insert($date);
            if(!$res3){
                throw new \Exception('订单商品详情写入失败');
            }
            // 减少商品购买的数量
            //        取session
            $session= request()->session()->get('userlogin');
            $user_id=$session['user_id'];

            $ress=DB::table('shop_cart')
                ->select('shop_cart.buy_number','shop_cart.goods_id','shop_goods.goods_num')
                ->join('shop_goods','shop_cart.goods_id','=','shop_goods.goods_id')
                ->where(['cart_status'=>1,'user_id'=>$user_id])
                ->get();
            foreach ($ress as $k=>$v){
                foreach($goods_id as $key=>$val){
                    if($v->goods_id ==$val){
                        $v->goods_num =$v->goods_num - $v->buy_number;
                        $res = DB::table('shop_goods')->where('goods_id',$val)->update(['goods_num'=>$v->goods_num]);
                    }
                }
            }

//            删除购物车数据
            $goods_id=\request()->goods_id;
            //        取session
            $session= request()->session()->get('userlogin');
            $user_id=$session['user_id'];
            $s_id=explode(',',$goods_id);
//            $where=[
//                'goods_id'=>$s_id,
//                'user_id'=>$user_id
//            ];
            if(strpos($goods_id,',')==false){
                $res4=DB::table('shop_cart')->where('goods_id',$goods_id)->update(['cart_status'=>2]);
            }else{
                $res4=DB::table('shop_cart')->whereIn( 'goods_id',$s_id)->update(['cart_status'=>2]);
            }
            if($res4==0){
                throw new \Exception('购物车删除失败');
            }


            // 提交事务
            DB::commit();
            return [
                'code'=>6,
                'font'=>'下单成功',
                'order_id'=>$order_id
            ];
        }catch (\Exception $e) {
//            dump($e->getMessage());
            DB::rollback();
            return [
                'font'=>'下单失败',
                'code'=>5
            ];
        }



    }

//    订单展示页面
    public  function success($id){
            $res=DB::table('shop_order')->where('order_id',$id)->first();
        return view('pay.success',compact('res'));
    }

//    订单支付
    public function alipay($order_id){
        if(!$order_id){
            return redirect('/zhubao/success')->with('没有次订单');
        }
        $res=DB::table('shop_order')->select('order_no','order_amount')->where('order_id',$order_id)->first();
       if($res->order_amount<=0){
           return redirect('/zhubao/success')->with('次订单无效');
       }
       app_path('libs/alipay/pagepay/service/AlipayTradeService.php');
        require_once app_path('libs/alipay/pagepay/service/AlipayTradeService.php');
        require_once app_path('libs/alipay/pagepay/buildermodel/AlipayTradePagePayContentBuilder.php');

        //商户订单号，商户网站订单系统中唯一订单号，必填
        $out_trade_no =$res->order_no;

        //订单名称，必填
        $subject = '商品';

        //付款金额，必填
        $total_amount = $res->order_amount;

        //商品描述，可空
        $body =  '商品';

        //构造参数
        $payRequestBuilder = new \AlipayTradePagePayContentBuilder();
        $payRequestBuilder->setBody($body);
        $payRequestBuilder->setSubject($subject);
        $payRequestBuilder->setTotalAmount($total_amount);
        $payRequestBuilder->setOutTradeNo($out_trade_no);

        /**
         * pagePay 电脑网站支付请求
         * @param $builder 业务参数，使用buildmodel中的对象生成。
         * @param $return_url 同步跳转地址，公网可以访问
         * @param $notify_url 异步通知地址，公网可以访问
         * @return $response 支付宝返回的信息
         */
        $aop = new \AlipayTradeService(config('alipay'));
        $response = $aop->pagePay($payRequestBuilder,config('alipay.return_url'),config('alipay.notify_url'));
        //输出表单
        var_dump($response);
        echo '支付';
    }
//    支付宝同步
    public  function returnpay(){
        /* *
         * 功能：支付宝页面跳转同步通知页面
         * 版本：2.0
         * 修改日期：2017-05-01
         * 说明：
         * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。

         *************************页面功能说明*************************
         * 该页面可在本机电脑测试
         * 可放入HTML等美化页面的代码、商户业务逻辑程序代码
         */
        $config=config('alipay');
        require_once app_path('libs/alipay/pagepay/service/AlipayTradeService.php');


        $arr=$_GET;
        $alipaySevice = new\AlipayTradeService($config);
        $result = $alipaySevice->check($arr);

        /* 实际验证过程建议商户添加以下校验。
        1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
        2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
        3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
        4、验证app_id是否为该商户本身。
        */
        if($result) {//验证成功
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //请在这里加上商户的业务逻辑程序代码

            //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
            //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表

            //商户订单号
            $where['order_no'] = htmlspecialchars($_GET['out_trade_no']);
            //商户订单金额
            $where['order_amount'] = htmlspecialchars($_GET['total_amount']);
//            取数据库查询
            $count=DB::table('shop_order')->where($where)->count();

            $money=json_encode($arr);
            if(!$count){
                Log::channel('alipay')->info('订单号和金额不符合，没有当前记录'.$money);
            }

            if(htmlspecialchars($_GET['seller_id'])!=config('alipay.seller_id')||htmlspecialchars($_GET['app_id'])!=config('alipay.app_id')){
                Log::channel('alipay')->info('订单商户不符'.$money);
            }
            //支付宝交易号
            $trade_no = htmlspecialchars($_GET['trade_no']);
                Log::channel('alipay')->info("验证成功<br />支付宝交易号：".$trade_no);

            //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——

            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        }
        else {
            //验证失败
            echo "验证失败";
        }
    }

//    异步通知
    public function notify(){
echo 11;
        $config=config('alipay');
        require_once app_path('libs/alipay/pagepay/service/AlipayTradeService.php');

        $arr=$_POST;
        dump($arr);
        $alipaySevice = new \AlipayTradeService($config);
        $alipaySevice->writeLog(var_export($_POST,true));
        $result = $alipaySevice->check($arr);
        Log::channel('alipay')->info('异步通知:'.$result);die;

        /* 实际验证过程建议商户添加以下校验。
        1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
        2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
        3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
        4、验证app_id是否为该商户本身。
        */
        if($result) {//验证成功
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //请在这里加上商户的业务逻辑程序代


            //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——

            //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表

            //商户订单号

            $out_trade_no = $_POST['out_trade_no'];

            //支付宝交易号

            $trade_no = $_POST['trade_no'];

            //交易状态
            $trade_status = $_POST['trade_status'];


            if($_POST['trade_status'] == 'TRADE_FINISHED') {

                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //请务必判断请求时的total_amount与通知时获取的total_fee为一致的
                //如果有做过处理，不执行商户的业务程序

                //注意：
                //退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知
            }
            else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //请务必判断请求时的total_amount与通知时获取的total_fee为一致的
                //如果有做过处理，不执行商户的业务程序
                //注意：
                //付款完成后，支付宝系统发送该交易状态通知
            }
            //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
            echo "success";	//请不要修改或删除
        }else {
            //验证失败
            echo "fail";

        }
    }
//    退出登录
    public function tuichu(Request $request){
        $res=$request->session()->forget('userlogin');
           if($res==null){
               return [
                   'msg'=>'退出成功',
                   'code'=>6
               ];
           }

    }
}
