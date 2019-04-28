<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\DetailModel;
use Illuminate\Support\Facades\Redis;
use DB;
use Mail;
class shangpingController extends Controller
{


//    商品
    public function prolist(Request $request)
    {
        $goods_name=$request->goods_name??'';
        $where=[];
        if($goods_name){
            $where[]=['goods_name','like',"%$goods_name%"];
        }
        $data= DB::table('shop_goods')->where($where)->where(['goods_status'=>1])->paginate(4);
//        dump($data);
        return view('shangping/prolist',compact('data'));
    }

//    商品详情
    public function proinfo($id)
    {
        $ress=cache('ress_'.$id);
//        dump($ress);
        dump(1);
        if(!$ress){
            dump(2);
            $ress=DB::table('shop_goods')->where(['goods_id'=>$id])->first();
            cache(['ress_'.$id=>$ress],60);
        }
        return view('shangping/proinfo',compact('ress'));


    }

//    删除
    public function delete($id){
        $goods_id=\request()->id;
        $data= DB::table('shop_goods') ->where(['goods_id'=> $goods_id])->update(['goods_status'=>2]);
        if($data){
            cache(['ress_'.$goods_id=>null],0);
            echo"<script>alert('删除成功');location.href='/shangping/prolist'</script>>";
        }else{
            echo"<script>alert('删除失败');location.href='/shangping/prolist'</script>>";
        }
    }

//    修改
    public function update($id){
        $ress=cache('ress_'.$id);
        if(!$ress){
            dump(2);
            $ress=DB::table('shop_goods')->where(['goods_id'=>$id])->first();
            cache(['ress_'.$id=>$ress],60);
        }
        return  view('shangping/proinfoupdate',compact('ress'));
    }

//    修改执行
    public function updatedo(){
        $data=\request()->all();
        $id=$data['goods_id'];

//        dd(cache('ress_'.$goods_id));
        $ress=DB::table('shop_goods')->where(['goods_id'=>$data['goods_id']])->update($data);
        $ress=DB::table('shop_goods')->where(['goods_id'=>$id])->first();
//        dd($ress);
        $ress=cache(['ress_'.$id=>$ress],60);
        if($ress){
            echo "<script>alert('修改成功');location.href='/shangping/prolist';</script>";
        }else{
            echo "<script>alert('修改失败');location.href='/shangping/update';</script>";
        }
    }
}