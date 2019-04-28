<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::prefix('zhubao')->group(function (){
//首页
    Route::any('/index','indexController@index');
//Route::any('index',function(){
////    return view('index.index');
//});
//登录
Route::any('/login','indexController@login');
//注册
Route::any('/reg','indexController@reg');
//手机发送验证码
Route::any('/tel','indexController@tel');
//邮箱发送验证码
    Route::any('/email','indexController@email');
//    商品
    Route::any('/prolist/{id}','indexController@prolist');
//    商品详情
    Route::any('/proinfo/{goods_id}','indexController@proinfo')->middleware('login');
//    加入购物车
    Route::post('/cartt','indexController@cartt')->middleware('login');
//    购车展示
    Route::any('/car','indexController@car');
    //    购车小计
    Route::any('/xiaoji','indexController@xiaoji');
//    更改购买数据
    Route::any('/chdckbuynumber','indexController@chdckbuynumber');
//    获取总价
    Route::any('/counttotal','indexController@counttotal');
//    删除删品
    Route::any('/delete','indexController@delete');
//    结算前 判断是否登录
    Route::any('/check','indexController@check');
//    购物车去结算
    Route::get('/carsubmit/{goods_id}','indexController@pay')->middleware('login');
//    个人中心
    Route::any('/user','indexController@user')->middleware('login');
//    收货地址
    Route::any('/address','indexController@address');
//    新增收货地址
    Route::any('/addressdo','indexController@addressdo');
//    二级联动
    Route::any('/att','indexController@att');
//    添加收货地址
    Route::any('/addsubmit','indexController@addsubmit');
//    修改收货地址
    Route::any('/addresss/{id}','indexController@addresss');
//    修改执行
    Route::any('/addsubmitdo','indexController@addsubmitdo');
//    提交订单
    Route::any('/successsubmit','indexController@successsubmit')->middleware('login');
//    订单详情页面
    Route::any('/success/{order_id}','indexController@success')->middleware('login');
//    订单支付
    Route::any('/alipay/{order_id}','indexController@alipay');
//    支付同步跳转
    Route::any('/returnpay','indexController@returnpay');
//    支付宝异步跳转
    Route::any('/notify','indexController@notify');

//    退出登录
    Route::any('/tuichu','indexController@tuichu')->middleware('login');
});

//商品
Route::prefix('shangping')->group(function (){
    //    商品
    Route::any('/prolist','shangpingController@prolist');

    //    商品详情
    Route::any('/proinfo/{id}','shangpingController@proinfo');

    //    商品删除
    Route::any('/proinfodelete/{id}','shangpingController@delete');

//    修改
    Route::any('/proinfoupdate/{id}','shangpingController@update');
//    修改执行
    Route::post('/updatedo','shangpingController@updatedo');
});
