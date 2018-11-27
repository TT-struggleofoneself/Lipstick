<?php
/**
 * 答题挑战模块小程序接口定义
 *
 * @author huichuang
 * @url http://bbs.we7.cc
 */

defined('IN_IA') or exit('Access Denied');
require_once IA_ROOT."/addons/kh_small_yx/inc/model.class.php";
require_once IA_ROOT."/addons/kh_small_yx/wxBizDataCrypt.php";

header('Access-Control-Allow-Origin:*');

class kh_small_yxModuleWxapp extends WeModuleWxapp {



    //商品列表---带有分页功能
    public function  doPageShopList(){
         return $this->result(0, '请求成功',$json);
    }

    //首页通知接口
    public function  doPageNoticeList(){
        
      $list = pdo_getall('kh_small_yx_notice', array('state' => 1,));
      return $this->result(1, '请求成功',$list);
    }




    // public function doPageStartGame()
    // {
    //     global $_W, $_GPC;
    //     // //这个操作被定义用来呈现 管理中心导航菜单
    //     // if (!empty($_GPC['name'])) {
    //     //     $data['name'] = $_GPC['name'];
    //     //     $data['uniacid'] = $_W['uniacid'];
    //     //     $data['sort'] = $_GPC['sort'];
    //     //     $data['stact'] = $_GPC['stact'];
    //     //     $data['img'] = $_GPC['img'];
    //     //     $data['content'] = $_GPC['content'];
    //     //     $data['time'] = date("Y-m-d");
    //     //     pdo_insert('hczhongzhuan_shenhe', $data);
    //     // }
    //     include $this->template("game");
    // }


    //微信通过code 获取openid的接口
    public function doPageLoginOpenid()
    {
        ob_end_clean();
        global $_GPC, $_W;
        $code = $_GPC['code'];
        $uniacid=$_GPC['i'];
        $account = pdo_get('account_wxapp', array('uniacid' => $uniacid));
        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid=' . $account['key'] . '&secret=' . $account['secret'] . '&js_code=' . $code . '&grant_type=authorization_code';
        // echo  $url ;exit;
        $result = $this->get_url_content($url);
        $result = json_decode($result, true);
       
       //查询数据库---先判断openid 是否存在
        $openid=$result["openid"];
        $session_key=$result["session_key"];

        if(empty($openid) || empty($session_key)){
             return $this->result(0, 'code 无效！');
        }

        $user = pdo_get('kh_small_yx_users',array('open_id'=>$openid,'uniacid'=>$_W['uniacid']));
        if($user){//用户存在
                return $this->result(1, '老用户登录',$result);
        }else{//用户不存在
            $data["open_id"]=$openid;
            $data["uniacid"]=$uniacid;
            $data["session_key"]=$session_key;
            $data["black"]=0;//正常用户
            $data["create_time"]=date('y-m-d h:i:s',time());
            $data["last_login_time"]=date('y-m-d h:i:s',time());
            $data["money"]=0;
            $result_add = pdo_insert('kh_small_yx_users',$data);
             if($result_add){//插入成功
                return $this->result(1, '保存成功',$result);
             }else{
                  return $this->result(0, '保存信息失败，请联系客服');
             }
        }
    }


    //登录接口---存入或者更新用户信息---接口
    public function doPageLogin(){
         global $_GPC, $_W;
        $openid = $_GPC['openid'];
        $nickname = $_GPC['nickname'];
        $header_url = $_GPC['header_url'];
        $sex = $_GPC['sex'];
        $uniacid=$_GPC['i'];
        $fatherid=$_GPC['fatherid'];//师傅ID
        //查询是否是否存在
        if(!empty($fatherid)){
            $fatherUser = pdo_get('kh_small_yx_users',array('user_id'=>$fatherid,'uniacid'=>$uniacid));
            if(!$fatherUser){
                return $this->result(0, '师傅不存在');//用户不存在
            }
        }

        $user = pdo_get('kh_small_yx_users',array('open_id'=>$openid,'uniacid'=>$uniacid));
        //先查询用户是否存在
        if($user){
            $data["nick_name"]=$nickname;
            $data["head_pic"]=$header_url;
            $data["gender"]=$sex;
            $data["fatherid"]=$fatherid;
            //更改用户信息
            $result=pdo_update('kh_small_yx_users',$data, array('open_id'=>$openid));
            $user["open_id"]="";
            $user["session_key"]="";
            $user["id"]=$user["user_id"];
            $user["balance"]=$user["money"];
            return $this->result(1, '登录成功',$user);
        }else{
            return $this->result(0, '非法操作！');//用户不存在
        }
    }



    //获取用户信息--接口
    public function doPageUserInfo(){
         global $_GPC, $_W;
        $user_id = $_GPC['user_id'];
        $uniacid = $_GPC['i'];
        $user = pdo_get('kh_small_yx_users',array('user_id'=>$user_id,'uniacid'=>$uniacid));

        //查询用户的口红数量
        $orderCount = pdo_get('kh_small_yx_orders', array('user_id'=>$user["user_id"],'type'=>0,'status'=>1), array('COUNT(*)'));
        //先查询用户是否存在
        if($user){
            $user["open_id"]="";
            $user["session_key"]="";
            $user["id"]=$user["user_id"];
            $user["orderCount"]=$orderCount["COUNT(*)"];
            return $this->result(1, '获取用户信息成功',$user);
        }else{
            return $this->result(0, '非法操作！');//用户不存在
        }
    }




    //商品列表
    public function doPageGoodslist(){

        global $_GPC, $_W;      
        $list = pdo_getall('kh_small_yx_goods', array('status' => 1,'uniacid'=>$_GPC['i']), array(),'','displayorder asc');
        foreach ($list as $k => $v) {
            $list[$k]['main_img'] = $_W['attachurl'].$v['main_img'];
        }
        return $this->result(1, '商品列表',$list);
    }


    //启动图------接口
    public function doPageGetloading(){

        global $_GPC, $_W; 
        $set = pdo_get('kh_small_yx_set',array('id'=>19));
        $list = pdo_getall('kh_small_yx_adv', array('enabled' => 1,'type'=>0), array(),'','displayorder asc');
        foreach ($list as $k => $v) {
            $list[$k]['thumb'] = $_W['attachurl'].$v['thumb'];
            $list[$k]['advname'] = $set["xcx"];
        }
        return $this->result(1, '启动页图片',$list[0]);
    }



    //首页轮播图，小程序二维码以及游戏规则接口
    public function doPageGetindex(){
        global $_GPC, $_W;   

        $set = pdo_get('kh_small_yx_set',array('id'=>19));   
        $list = pdo_getall('kh_small_yx_adv', array('enabled' => 1,'type'=>array('0', '1', '2', '3'),'uniacid'=>$_GPC['i']), array(),'','displayorder asc');
        
        $lodingDao;
        $homeBannerDao;
        $erweimaDao;
        $gongzhonghaoDao;
        foreach ($list as $k => $v) {
            $list[$k]['thumb'] = $_W['attachurl'].$v['thumb'];/*  */
            if($v['type']==0){//启动数据
                $lodingDao=$list[$k];
            }else if($v['type']==1){//首页banner数据
                $homeBannerDao=$list[$k];
            }else if($v['type']==2){//小程序二维码数据
                $erweimaDao=$list[$k]; 
            }else if($v['type']==3){//小程序二维码数据
                $gongzhonghaoDao=$list[$k]; 
            }
        }

        $resultData["title"]=$set["xcx"];//标题
        $resultData["loading_url"]=$lodingDao["thumb"];//启动图片
        $resultData["banner_url"]=$homeBannerDao["thumb"];//首页banner图
        $resultData["qrcode_url"]=$erweimaDao["thumb"];//小程序二维码图
        $resultData["gzh_url"]=$gongzhonghaoDao["thumb"];//公众号关注图
        $resultData["music_url"]=$_W['attachurl']."file/music1.mp3";//背景音乐文件
        $resultData["explain_title"]=$lodingDao["advname"];//标题说明
        $resultData["explain_info"]="1、闯关购规则
活动开始前选择您喜欢的口红;
每关只需将所显示的口红插满圆盘，即可过关;连闯三关就可以获得心仪的口红;
获得商品后，填写您的收货地址，我们会以快递的形式将您的口红寄给您(仅限中国大陆地区)
2、商品说明
我们所提供的商品均为官方正品，支持专柜验货;
3、快递说明
一般情况下， 您闯关得到的商品提交订单后3个工作日内我们会为您安排发货，如遇特殊情况，我们会在公众号公布。";//规则说明
        $resultData["notice_title"]=$lodingDao["advname"];//通知ID
        $resultData["notice_info"]=$lodingDao["advname"];
        return $this->result(1, '启动页图片',$resultData);
    }


    //cl充值数据接口
    public function  doPageRechangeList(){
        global $_GPC, $_W;      
        $list = pdo_getall('kh_small_yx_rechange', array('uniacid'=>$_GPC['i'],'state'=>1), array(),'','');
        
        return $this->result(1, '充值类型数据',$list);
    }



    //开始游戏------接口
    public function  doPageConsume(){
        global $_GPC, $_W; 

        $parentRatio=0.3;//师傅分成多少  30%
        $goods_id=$_GPC['goods_id'];
        $openid=$_GPC['openid'];
        $user_id=$_GPC['user_id'];
        $uniacid=$_GPC['i'];
        //先查询用户是否存在
        $user = pdo_get('kh_small_yx_users',array('user_id'=>$user_id,'open_id'=>$openid,'uniacid'=>$uniacid));
        if($user){

            //在查询商品是否存在
            $goods = pdo_get('kh_small_yx_goods', array('status' => 1,'id' => $goods_id,'uniacid'=>$uniacid), array(),'','displayorder asc');
            if($goods){//商品存在


                //先判断-----之前付款的游戏是否已经完成了。
                $moneylog = pdo_get('kh_small_yx_moneylog',array('goods_id'=>$goods_id,'state'=>0,'game_level'=>0,'user_id'=>$user["user_id"]));
                if($moneylog){
                     $gameData["game_id"]=$moneylog["game_id"];
                     return $this->result(1, '可以开始游戏',$gameData); 
                }


                //判断余额是否充足
                if($user["money"]<$goods["rmb"]){
                    $resultData["balance"]=$user["money"];
                    $resultData["price"]=$goods["rmb"];
                    return $this->result(1024, '余额不足',$resultData); 
                }else{//可以抽奖开始游戏
                    //生成游戏ID
                    $game_id="kh_yx001".time().$goods_id;
                    $gameData["game_id"]=$game_id;
                     //查询充值类型是多少金额
                    $userMoneyData["money"]=$user["money"]-$goods["rmb"];
                    
                    //记录金额变动的记录
                    $moneylogData["user_id"]=$user_id;
                    $moneylogData["goods_id"]=$goods_id;
                    $moneylogData["type"]="1";
                    $moneylogData["last_money"]=$user["money"];
                    $moneylogData["current_money"]=$userMoneyData["money"];
                    $moneylogData["add_time"]=date('y-m-d h:i:s',time());
                    $moneylogData["change_money"]=$goods["rmb"];
                    $moneylogData["game_level"]="0";
                    $moneylogData["game_id"]=$game_id;
                    //增加金额变动记录
                    pdo_insert('kh_small_yx_moneylog',$moneylogData);

                    //更新用户钱包
                    $result=pdo_update('kh_small_yx_users',$userMoneyData, array('user_id'=>$user["user_id"]));
                    if($result){
                        //如果有师傅ID  那么说明有师傅，就要分钱
                        if(!empty($user["fatherid"])){
                            //先查询师傅--用户
                            $fatherUser = pdo_get('kh_small_yx_users',array('user_id'=>$user["fatherid"],'uniacid'=>$uniacid));
                            if($fatherUser){
                                $ratioPrice=$goods["rmb"]*$parentRatio;
                                $fatherUserMoneyData["money"]=$fatherUser["money"]+$ratioPrice;
                                //记录金额变动的记录
                                $fatherMoneylogData["user_id"]=$fatherUser["user_id"];
                                $fatherMoneylogData["goods_id"]=$goods_id;
                                $fatherMoneylogData["type"]="1";
                                $fatherMoneylogData["last_money"]=$fatherUser["money"];
                                $fatherMoneylogData["current_money"]=$fatherUserMoneyData["money"];
                                $fatherMoneylogData["add_time"]=date('y-m-d h:i:s',time());
                                $fatherMoneylogData["change_money"]=$ratioPrice;
                                $fatherMoneylogData["game_level"]="0";
                                $fatherMoneylogData["game_id"]=$game_id;
                                //增加金额变动记录
                                $result=pdo_insert('kh_small_yx_moneylog',$fatherMoneylogData);
                                if($result){
                                    pdo_update('kh_small_yx_users',$fatherUserMoneyData, array('user_id'=>$fatherUser["user_id"]));

                                }
                            }
                        }
                         //先扣款----在玩游戏
                        return $this->result(1, '可以开始游戏',$gameData); 
                    }else{
                        return $this->result(0, '操作异常！'); 
                    }
                }
                return $this->result(0, '非法操99999'); 
            }else{
                 return $this->result(0, '非法操作2'); 
            }
            //判断余额是否充足
        }else{
           return $this->result(0, '非法操作1'); 
        }
    }



    //正式游戏入口
    public function  doPageGame(){
        global $_GPC, $_W; 

        $goods_id=$_GPC['goods_id'];
        $game_id=$_GPC['hykj'];
        $openid=$_GPC['openid'];
        $user_id=$_GPC['user_id'];
        $uniacid=$_GPC['i'];

        $user = pdo_get('kh_small_yx_users',array('user_id'=>$user_id,'open_id'=>$openid,'uniacid'=>$uniacid));
        if($user){
            $moneyLog = pdo_get('kh_small_yx_moneylog',array('user_id'=>$user_id,'goods_id'=>$goods_id,'game_id'=>$game_id,'type'=>1,'game_level'=>0));

            if($moneyLog){
                include $this->template("game");
            }else{
                echo "非法操作1";
                 // return $this->result(0, '非法操作1'); 
            }
        }else{
            echo "非法操作2";
            // return $this->result(0, '非法操作2'); 
        }
    }



    //正式游戏入口
    public function  doPageGzhGame(){
        global $_GPC, $_W; 

        $goods_id=$_GPC['goods_id'];
        $game_id=$_GPC['hykj'];
        $openid=$_GPC['openid'];
        $user_id=$_GPC['user_id'];
        $uniacid=$_GPC['i'];

        $user = pdo_get('kh_small_yx_users',array('user_id'=>$user_id,'open_id'=>$openid,'uniacid'=>$uniacid));
        if($user){
            $moneyLog = pdo_get('kh_small_yx_moneylog',array('user_id'=>$user_id,'goods_id'=>$goods_id,'game_id'=>$game_id,'type'=>1,'game_level'=>0));

            if($moneyLog){
                include $this->template("gzhgame");
            }else{
                echo "非法操作1";
                 // return $this->result(0, '非法操作1'); 
            }
        }else{
            echo "非法操作2";
            // return $this->result(0, '非法操作2'); 
        }
    }



    //模拟试玩游戏接口
    public function  doPageGames(){
        global $_GPC, $_W; 
        $game_id=$_GPC['hykj'];//游戏ID
        if($game_id!='kh_yx001'){
             return $this->result(0, '非法操作'); 
             exit;
        }
        include $this->template("dgame");


        // $url='http://youxi.xiaopangxiekeji.com/app/index.html'; 
        // header("location:".$url);
        //  // include $this->template('game1/index');
    }


    //支付----接口
    public function doPagePay(){
        global $_GPC, $_W;
       
        $user_id = $_GPC['user_id'];
        $rechange_id = $_GPC['rechange_id'];//充值类型ID---确定充值多少金额
        $weid= $_GPC['i'];
        //查询兑换类型是否存在

        $rechange = pdo_get('kh_small_yx_rechange',array('id'=>$rechange_id));
       if(empty($rechange)){
             return $this->result(0, '非法操作！');
        }
        $user = pdo_get('kh_small_yx_users',array('user_id'=>$user_id));
        if(empty($rechange)){
             return $this->result(0, '非法操作！');
        }
       
         
        $data = array(
            'weid' => $weid,
            'uid'  => $user_id,
            'goods_id'  => $rechange_id,//如果是充值的话那么goods_id 为kh_small_yx_rechange 表中的ID
            'paytype'  => 0,
            'fee'      => 1,//测试金额//$rechange["price"]*100,
            'ordersn'  => date('YmdHis').time().rand(100000,999999),
            'status'   => 1,
            'createtime'=>date('y-m-d h:i:s',time())
        );
        $res = pdo_insert('kh_small_yx_payment',$data);
        if(!empty($res)){
                 //订单号
                $oid = pdo_insertid();
                //普通
                $order['uniacid'] = $weid;
                $order['userName'] = $user["nick_name"];
                $order['postalCode'] = "";//邮政编码
                $order['provinceName'] = "";
                $order['cityName'] =  "";
                $order['countyName'] =  "";
                $order['detailInfo'] =  "充值金额：".$rechange["price"]."元";
                $order['nationalCode'] = "";
                $order['telNumber'] = "";
                $order['user_id'] = $user_id;
                $order['goods_id'] = $rechange_id;
                $order['type'] = 2;//表示充值
                $order['status'] = 0;
                $order['oid'] = $oid;
                $order['time'] = date('y-m-d h:i:s',time());
                $aaa = pdo_insert('kh_small_yx_orders', $order, $replace = true);
        }
        $pay_params = $this->payment($oid);
        if (is_error($pay_params)) {
            return $this->result(0, '支付失败，请重试');
        }
        return $this->result(1, '',$pay_params);
    }


    /**
     * 调起微信支付
     */
    public function payment($order_id){
        global $_GPC, $_W;
        $weid = $_GPC['i'];
        load()->model('payment');
        load()->model('account');
        $setting = uni_setting($weid, array('payment'));
        $wechat_payment = array(
            'appid'   => $_W['account']['key'],
            'signkey' => $setting['payment']['wechat']['signkey'],
            'mchid'   => $setting['payment']['wechat']['mchid'],
        );

        // echo "<pre>";print_r($wechat_payment);die;
        $order  = pdo_get('kh_small_yx_payment',array('id'=>$order_id,'weid'=>$weid));

        $openid = pdo_getcolumn('kh_small_yx_users',array('user_id'=>$order['uid']),array('open_id'));
        //返回小程序参数
        $notify_url = $_W['siteroot'].'addons/kh_small_yx/wxpay.php';
        // echo "支付回调地址：".$notify_url;exit;

        $res = $this->getPrePayOrder($wechat_payment,$notify_url,$order,$openid);
        
        if($res['return_code']=='FAIL'){
            return $this->result(0, '操作失败',$res['return_msg']);
        }
        if($res['result_code']=='FAIL'){
            return $this->result(0, '操作失败',$res['err_code'].$res['err_code_des']);
        }

        if($res['return_code']=='SUCCESS'){
            $wxdata = $this->getOrder($res['prepay_id'],$wechat_payment,$order_id);
            //echo "<pre>";print_r($wxdata);
            pdo_update('kh_small_yx_payment', array('package'=>$res['prepay_id']), array('id'=>$order_id));

            return $this->result(1, '操作成功',$wxdata);
        }else{
            return $this->result(0, '操作失败');
        }
    }
    
    //微信统一支付
    public function getPrePayOrder($wechat_payment,$notify_url,$order,$openid){
        $model = new HcfkModel();
        $url = "https://api.mch.weixin.qq.com/pay/unifiedorder";
        
        $data["appid"] = $wechat_payment['appid'];
        $data["body"] = $order['ordersn'];
        $data["mch_id"] = $wechat_payment['mchid'];
        $data["nonce_str"] = $model->getRandChar(32);
        $data["notify_url"] = $notify_url;
        $data["out_trade_no"] = $order['ordersn'];
        $data["spbill_create_ip"] = $model->get_client_ip();
        $data["total_fee"] = "1";//测试金额//$order['fee']*100;
        $data["trade_type"] = "JSAPI";
        $data["openid"] = $openid;
        $data["sign"] = $model->getSign($data,$wechat_payment['signkey']);
    
        $xml = $model->arrayToXml($data);
        $response = $model->postXmlCurl($xml, $url);
        return $model->xmlstr_to_array($response);
    }
    




    // 执行第二次签名，才能返回给客户端使用
    public function getOrder($prepayId,$wechat_payment,$order_id){
        global $_GPC, $_W;
        $model = new HcfkModel();
        $data["appId"] = $wechat_payment['appid'];
        $data["nonceStr"] = $model->getRandChar(32);
        $data["package"] = "prepay_id=".$prepayId;
        $data['signType'] = "MD5";
        $data["timeStamp"] = date('y-m-d h:i:s',time());
        $order = pdo_get('kh_small_yx_orders',array('oid'=>$order_id));
        $data['order_id'] = $order['id'];
        $data["sign"] = $model->getSign1($data,$wechat_payment['signkey']);
        return $data;
    }






    //我的订单-----接口
    public function  doPageOrderlist(){
        global $_GPC, $_W; 

        $user_id = $_GPC['user_id'];
        $uniacid=$_GPC['i'];
        $pindex = max(intval($_GPC['page']), 1);
        $psize = 20;
        // $list = pdo_getslice('ims_kh_small_yx_orders', array('type' => 0), array($pindex, $psize), $total, array(), '', array('id desc'));
        // $list = pdo_getslice('ims_kh_small_yx_orders', array('type' => 0), array($pindex, $psize), $total, array(), '', array('id desc'));
        $list = pdo_getall('kh_small_yx_orders', array('type'=>0,'status'=>1,'user_id'=>$user_id));
        foreach ($list as $key => $value) {
            //查询商品信息
             $goods = pdo_get('kh_small_yx_goods', array('status' => 1,'id' => $value["goods_id"],'uniacid'=>$uniacid), array(),'','displayorder asc');
             $list[$key]["good_name"]=$goods["goods_name"];
             $list[$key]["main_img"]=$goods["main_img"];
             $list[$key]["zg_price"]=$goods["price2"];//专柜价格。
        }

        return $this->result(1, '请求成功',$list);
    }


    //订单详情-----接口
    public function  doPageOrderinfo(){
        global $_GPC, $_W; 

        $user_id = $_GPC['user_id'];
        $uniacid=$_GPC['i'];
        $orderid=$_GPC['orderid'];
        $orderinfo = pdo_get('kh_small_yx_orders', array('user_id'=>$user_id,'id'=>$orderid,'uniacid'=>$uniacid));
        //查询商品信息
         $goods = pdo_get('kh_small_yx_goods', array('status' => 1,'id' => $orderinfo["goods_id"],'uniacid'=>$uniacid), array(),'','displayorder asc');
         $orderinfo["good_name"]=$goods["goods_name"];
         $orderinfo["main_img"]=$goods["main_img"];
         $orderinfo["zg_price"]=$goods["price2"];//专柜价格。

         //查询快递信息
         $winlog = pdo_get('kh_small_yx_winlog', array('id' => $orderinfo["id"]));
         $orderinfo["userName"]=$winlog["userName"];
         $orderinfo["telNumber"]=$winlog["telNumber"];
         $orderinfo["express"]=$winlog["express"];
         $orderinfo["expressname"]=$winlog["expressname"];
         $orderinfo["fahuotime"]=$winlog["fahuotime"];
         $orderinfo["address"]=$winlog["address"];
        return $this->result(1, '请求成功',$orderinfo);
    }





    //游戏结束-----接口
    public function doPageEndGame(){
        global $_GPC, $_W;  
        $user_id = $_GPC['user_id'];
        $goods_id = $_GPC['goods_id'];
        $game_id = $_GPC['game_id'];
        $openid = $_GPC['openid'];
        $level = $_GPC['level'];
        $uniacid = $_GPC['i'];

        if(empty( $level)){
             return $this->result(0, '非法操作4');
        }
        if(empty( $game_id)){
             return $this->result(0, '非法操作5');
        }
        if(empty( $openid)){
             return $this->result(0, '非法操作6');
        }

        // echo $user_id; echo $openid; echo $uniacid;exit;
        //先查询用户是否存在
        $user = pdo_get('kh_small_yx_users',array('user_id'=>$user_id,'open_id'=>$openid,'uniacid'=>$uniacid));
        if($user){
            //在查询商品是否存在
            $goods = pdo_get('kh_small_yx_goods', array('status' => 1,'id' => $goods_id,'uniacid'=>$uniacid), array(),'','displayorder asc');
            if($goods){//商品存在
                //先查询消费记录是否存在
                 $moneylog = pdo_get('kh_small_yx_moneylog',array('game_id'=>$game_id,'goods_id'=>$goods_id,'user_id'=>$user["user_id"]));
                 
                 if($moneylog && $moneylog["game_level"]==0 && $moneylog["state"]==0 ){
                     $moneylogData["game_level"]=$level;
                      $moneylogData["state"]=1;
                     // var_dump($moneylogData);    exit;
                     $result=pdo_update('kh_small_yx_moneylog',$moneylogData, array('game_id'=>$game_id,'goods_id'=>$goods_id,'user_id'=>$user["user_id"]));

                     // pdo_debug();
                     // echo  $result;exit;

                     // exit;
                     if($result &&  $level==3){
                            //增加一条订单记录
                            $order['uniacid'] = $uniacid;
                            $order['userName'] = $user["nick_name"];
                            $order['postalCode'] = "";//邮政编码
                            $order['provinceName'] = "";
                            $order['cityName'] =  "";
                            $order['countyName'] =  "";
                            $order['detailInfo'] =  "恭喜您获得".$goods["goods_name"].'一支';
                            $order['nationalCode'] = "";
                            $order['telNumber'] = "";
                            $order['user_id'] = $user["user_id"];
                            $order['goods_id'] = $goods_id;
                            $order['type'] = 0;//表示通过玩游戏通关兑换的商品
                            $order['status'] = 1;
                            $order['oid'] = '';
                            $order['time'] = date('y-m-d h:i:s',time());
                            $order['game_id'] = $game_id;
                            $inserOrderResult = pdo_insert('kh_small_yx_orders', $order, $replace = true);
                            $id = pdo_insertid();
                            if($inserOrderResult){
                                $appData["id"]=$id;
                                $appData["detailInfo"]=$order['detailInfo'];
                                $appData["game_id"]=$game_id;
                                $appData["user_id"]=$user["user_id"];
                                $appData["goods_id"]=$goods_id;
                                return $this->result(1, '兑换成功',$appData);
                            }else{
                                return $this->result(0, '操作异常');
                            }
                     }else{
                        return $this->result(0 ,'没有达到兑换条件');
                     }
                 }else{
                    return $this->result(0, '非法操作3');
                 }
            }else{
                 return $this->result(0, '非法操作2');
            }
        }else{
               return $this->result(0, '非法操作1');
        }
    }


    //得到奖品天蝎收货地址-----接口
    public function  doPageAddress(){
        global $_GPC, $_W; 
        $user_id = $_GPC['user_id'];
        $uniacid=$_GPC['i'];
        $address=$_GPC['address'];
        $order_id=$_GPC['order_id'];
        $name=$_GPC['name'];
        $mobile=$_GPC['mobile'];
        $goods_id=$_GPC['goods_id'];

        //先查询订单是否真实存在
        $order = pdo_get('kh_small_yx_orders', array('type' => 0,'status'=>1,'user_id'=>$user_id,'goods_id' => $goods_id,'id'=>$order_id,'uniacid'=>$uniacid));
        // pdo_debug();exit;
        if($order){
             $data["address"]=$address;
             $data["telNumber"]=$mobile;
             $data["userName"]=$name;
             $result=pdo_update('kh_small_yx_orders',$data, array('id'=>$order["id"]));
             if($result){
                return $this->result(1, '提交成功，请联系客服领取！');
             }else{
                 return $this->result(0, '操作异常');
             }
        }else{
            return $this->result(0, '非法操作');
        }
    }


    //---------------------------------以上是使用过，有用的接口----------------------------------------------------





    //  //测试支付回调----接口
    // public function doPageResultPay(){
    //     global $_GPC, $_W;


    //     $resultPayString="<xml><appid><![CDATA[wxa6e3e60146dd636c]]></appid>
    //     <bank_type><![CDATA[CFT]]></bank_type>
    //     <cash_fee><![CDATA[1]]></cash_fee>
    //     <fee_type><![CDATA[CNY]]></fee_type>
    //     <is_subscribe><![CDATA[N]]></is_subscribe>
    //     <mch_id><![CDATA[1404617902]]></mch_id>
    //     <nonce_str><![CDATA[ioaBqWmeBpVi7DewRsNaqBFYXXHkfd9s]]></nonce_str>
    //     <openid><![CDATA[owXJZ5NDBN8neca2hNgKb-5SFgUM]]></openid>
    //     <out_trade_no><![CDATA[201811161324161542345856480205]]></out_trade_no>
    //     <result_code><![CDATA[SUCCESS]]></result_code>
    //     <return_code><![CDATA[SUCCESS]]></return_code>
    //     <sign><![CDATA[1F76891093CF66E9F3C0CA283AFA2079]]></sign>
    //     <time_end><![CDATA[20181116132421]]></time_end>
    //     <total_fee>1</total_fee>
    //     <trade_type><![CDATA[JSAPI]]></trade_type>
    //     <transaction_id><![CDATA[4200000231201811166541693376]]></transaction_id>
    //     </xml>";
    //     // libxml_disable_entity_loader(true);
    //     // $xmlstring = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
    //     // $payreturn = json_decode(json_encode($xmlstring),true);
    //     // echo "解析结果：";
    //     $payreturn=$this->xmlToArray($resultPayString);

    //     if ($payreturn['result_code'] == 'SUCCESS' && $payreturn['result_code'] == 'SUCCESS') {

    //         $ordersn = trim($payreturn['out_trade_no']);
    //         //查询支付表中的支付记录
    //         $order = pdo_get('kh_small_yx_payment',array('ordersn'=>$ordersn));

    //        //更改订单的状态
    //         $goods_order = pdo_get('kh_small_yx_orders',array('goods_id'=>$order['goods_id'],'oid'=>$order['id']));
    //         if($goods_order){
    //             pdo_update('kh_small_yx_orders', array('status'=>1), array('goods_id'=>$order['goods_id'],'oid'=>$order['id']));
    //         }

    //         //更改
    //         $user = pdo_get('kh_small_yx_users',array('user_id'=>$order['uid']));
    //         if ($order){
    //             var_dump($order);
    //             //更新订单状态
    //             $order_data = array(
    //                 'paystatus'=>1,
    //                 'paytime'=>time(),
    //                 'transid'=>$payreturn['transaction_id']
    //             );
    //             pdo_update('kh_small_yx_payment', $order_data, array('ordersn'=>$ordersn));

    //         }
    //         //查询支付类型
    //         $type = pdo_getcolumn('kh_small_yx_payment',array('ordersn'=>$ordersn),array('paytype'));
    //         if($type == 0){//微信支付
    //             //查询用户信息
    //             //查询充值类型是多少金额
    //             $user = pdo_get('kh_small_yx_users',array('user_id'=>$order['uid']));
    //             $rechange = pdo_get('kh_small_yx_rechange', array('id'=>$order["goods_id"]));
    //             $userMoneyData["money"]=$user["money"]+$rechange["price"];
    //             $result=pdo_update('kh_small_yx_users',$userMoneyData, array('user_id'=>$user["user_id"]));
    //         }
            


    //         // if($type == 0){
    //         //     $user_data = array('status'=>0);
    //         //     pdo_update('kh_small_yx_orders', $user_data, array('oid'=>$order['id']));       //用户状态为代理
    //         //     $nowinventory = $goods['inventory'] - 1;//库存减少
    //         //     pdo_update('kh_small_yx_goods',array('inventory'=>$nowinventory), array('id' => $order['goods_id']));
    //         // }
    //         // if($type == 1)
    //         // {
    //         //     $user_data = array('status'=>0);
    //         //     pdo_update('kh_small_yx_orders', $user_data, array('oid'=>$order['id']));       //用户状态为代理
    //         //     $nowinventory = $goods['inventory'] - 1;
    //         //     pdo_update('kh_small_yx_goods',array('inventory'=>$nowinventory), array('id' => $order['goods_id']));
    //         //     $nowmoney = $user['money'] - $goods['price2'];
    //         //     pdo_update('kh_small_yx_users',array('money'=>$nowmoney), array('user_id' =>$order['uid']));
    //         // }
    //         echo 'success';
    //     }else{
    //          echo  "失败了";
    //     }
                    
        

    // }



    // function arrayToXml($arr)
    // {
    //     $xml = "<xml>";
    //     foreach ($arr as $key=>$val)
    //     {
    //         if (is_numeric($val)){
    //             $xml.="<".$key.">".$val."</".$key.">";
    //         }else{
    //              $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
    //         }
    //     }
    //     $xml.="</xml>";
    //     return $xml;
    // }

    //  //将XML转为array
    // function xmlToArray($xml)
    // {
    //     //禁止引用外部xml实体
    //     libxml_disable_entity_loader(true);
    //     $values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    //     return $values;
    // }






    //商品购买支付----接口-----暂时没用上
    public function doPageGoodsPay(){
        global $_GPC, $_W;
        $uid = $_GPC['user_id'];
        $goods_id = $_GPC['goods_id'];
        $paytype = $_GPC['state'];
        $weid= $_GPC['i'];
        $goods = pdo_get('kh_small_yx_goods',array('id'=>$goods_id));
        $user = pdo_get('kh_small_yx_users',array('user_id'=>$uid));
        if($paytype == 0){
            $fee = $goods['maxrmb'];
            $orders = pdo_getall('kh_small_yx_orders', array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id'],'goods_id'=>$_GPC['goods_id']));
            $ordersum = count($orders);
            if($goods['inventory'] <=0 ){
            return $this->result(0, '库存不足',$goods);
            }
            if($goods['maxbuy'] > 0){
                if($ordersum >= $goods['maxbuy']){
                    // echo "111111";exit;
                    return $this->result(0, '已达兑换上限',$user);
                }
            }
        }
        // if($paytype == 1){
        //     $fee = $goods['rmb'];
        //     if($user['money'] < $goods['price2']){
        //         return $this->result(1, '金币不足');
        //     }
        //     if($goods['inventory'] <=0 ){
        //     return $this->result(1, '库存不足',$goods);
        //     }
        //     $orders = pdo_getall('kh_small_yx_orders', array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id'],'goods_id'=>$_GPC['goods_id']));
        //     $ordersum = count($orders);
        //     if($goods['maxbuy'] > 0){
        //         if($ordersum >= $goods['maxbuy']){
        //             return $this->result(1, '已达兑换上限',$user);
        //         }
        //     }
        // }        
        $data = array(
            'weid' => $weid,
            'uid'  => $uid,
            'goods_id'  => $goods_id,
            'paytype'  => $paytype,
            'fee'      => $fee,
            'ordersn'  => date('YmdHis').time().rand(100000,999999),
            'status'   => 1,
            'createtime'=>time()
        );
        $res = pdo_insert('kh_small_yx_payment',$data);
        if(!empty($res)){
            $oid = pdo_insertid();
            //写入订单表
            if($paytype == 0){

                if($goods['selltype'] == 1){//核销
                    $order['uniacid'] = $_GPC['i'];
                    $order['user_id'] = $_GPC['user_id'];
                    $order['goods_id'] = $_GPC['goods_id'];
                    $order['type'] = 10;
                    $order['status'] = 2;
                    $order['oid'] = $oid;
                    $order['time'] = time();
                              
                    $aaa = pdo_insert('kh_small_yx_orders', $order, $replace = true);
                }else{//普通
                    $order['uniacid'] = $_GPC['i'];
                    $order['userName'] = $_GPC['userName'];
                    $order['postalCode'] = $_GPC['postalCode'];
                    $order['provinceName'] = $_GPC['provinceName'];
                    $order['cityName'] = $_GPC['cityName'];
                    $order['countyName'] = $_GPC['countyName'];
                    $order['detailInfo'] = $_GPC['detailInfo'];
                    $order['nationalCode'] = $_GPC['nationalCode'];
                    $order['telNumber'] = $_GPC['telNumber'];
                    $order['user_id'] = $_GPC['user_id'];
                    $order['goods_id'] = $_GPC['goods_id'];
                    $order['type'] = 0;
                    $order['status'] = 2;
                    $order['oid'] = $oid;
                    $order['time'] = time();
                              
                    $aaa = pdo_insert('kh_small_yx_orders', $order, $replace = true);
                }             
            }
            // if($paytype == 1){

            //     if($goods['selltype'] == 1){
            //         $order['uniacid'] = $_GPC['i'];
            //         $order['user_id'] = $_GPC['user_id'];
            //         $order['goods_id'] = $_GPC['goods_id'];
            //         $order['type'] = 11;
            //         $order['status'] = 2;
            //         $order['oid'] = $oid;
            //         $order['time'] = time();
                              
            //         $aaa = pdo_insert('kh_small_yx_orders', $order, $replace = true);
            //     }else{
            //         $order['uniacid'] = $_GPC['i'];
            //         $order['userName'] = $_GPC['userName'];
            //         $order['postalCode'] = $_GPC['postalCode'];
            //         $order['provinceName'] = $_GPC['provinceName'];
            //         $order['cityName'] = $_GPC['cityName'];
            //         $order['countyName'] = $_GPC['countyName'];
            //         $order['detailInfo'] = $_GPC['detailInfo'];
            //         $order['nationalCode'] = $_GPC['nationalCode'];
            //         $order['telNumber'] = $_GPC['telNumber'];
            //         $order['user_id'] = $_GPC['user_id'];
            //         $order['goods_id'] = $_GPC['goods_id'];
            //         $order['type'] = 1;
            //         $order['status'] = 2;
            //         $order['oid'] = $oid;
            //         $order['time'] = time();
                              
            //         $aaa = pdo_insert('kh_small_yx_orders', $order, $replace = true);
            //     }              
            // }
            $pay_params = $this->payment($oid);
            if (is_error($pay_params)) {
                return $this->result(0, '支付失败，请重试');
            }
            return $this->result(1, '',$pay_params);
        }else{
            return $this->result(0, '操作失败');
        }
    }









    // //报名---入口
    // public function doPageLogin(){

    //     global $_GPC, $_W;
    //     // $account = pdo_get('account_wxapp', array('uniacid' => $_GPC['i']));
    //     $set = pdo_get('kh_small_yx_activity', array('uniacid' => $_GPC['i'],'id'=>$_GPC['aid']));
    //     $time=date('Y-m-d',time());
    //     $code = $_GPC['code'];
    //     $account = pdo_get('account_wxapp', array('uniacid' => $_GPC['i']));
    //     $url = 'https://api.weixin.qq.com/sns/jscode2session?appid=' . $account['key'] . '&secret=' . $account['secret'] . '&js_code=' . $code . '&grant_type=authorization_code';
    //     $result = $this->get_url_content($url);
    //     $result = json_decode($result, true);
    //     var_dump($result);exit;

    //     // $uid           = $_GPC['user_id'];
    //     // $encryptedData = $_GPC['wRunEncryptedData'];
    //     // $iv            = $_GPC['iv'];
    //     $sessionKey    = $result['session_key'];
    //     $appid = $_W['account']['key'];
    //     $pc = new WXBizDataCrypt($appid, $sessionKey);
    //     $errCode = $pc->decryptData($encryptedData, $iv, $data);
    //     if($errCode == 0){
    //         $bushu = json_decode($data,'true');
    //         var_dump($bushu);exit();
    //         $bushu = $bushu['stepInfoList'][30]['step'];
    //         if($bushu >= $set['step']){
    //             pdo_update('kh_small_yx_activitylog',array('step'=>$bushu,'status'=>1), array('user_id' => $_GPC['user_id'],'aid'=>$_GPC['aid'],'time'=>$time,'uniacid'=>$_GPC['i']));
    //         }else{
    //            pdo_update('kh_small_yx_activitylog',array('step'=>$bushu), array('user_id' => $_GPC['user_id'],'aid'=>$_GPC['aid'],'time'=>$time,'uniacid'=>$_GPC['i'])); 
    //         }          
    //         return $this->result(0,'同步成功',$bushu);
    //     }else{
    //         return $this->result(1, array('errcode'=>$errCode));
    //     }
        
    // }




// //用户管理
//     public function doWebUsers(){
//         global $_W,$_GPC;
//         $op = $_GPC['op'];
//         if ($op == 'del') {
//             $id = $_GPC['id'];
//             $item = pdo_get('kh_small_yx_users',array('user_id'=>$id,'uniacid'=>$_W['uniacid']));
//             if(empty($item)){
//                 message('操作失败',$this->createWebUrl('users'),'error');
//             }
//             if(pdo_delete('kh_small_yx_users',array('user_id'=>$id,'uniacid'=>$_W['uniacid'])) === false) message('操作失败',referer(),'error');
//             else message('操作成功',$this->createWebUrl('users'),'success');
//         }
//         if ($op == 'black') {
//             $id = $_GPC['id'];
//             $item = pdo_get('kh_small_yx_users',array('user_id'=>$id,'uniacid'=>$_W['uniacid']));
//             if(empty($item)){
//                 message('操作失败',$this->createWebUrl('users'),'error');
//             }
//             if(pdo_update('kh_small_yx_users',array('black'=>1), array('user_id'=>$id,'uniacid'=>$_W['uniacid'])) === false) message('操作失败',referer(),'error');
//             else message('操作成功',$this->createWebUrl('users'),'success');
//         }
//         $keyword = $_GPC['keyword'];        
//         if(!empty($_GPC['keyword']) and $_GPC['order_status'] ==1){
//             $where['nick_name LIKE'] = '%'.$keyword.'%';
//         }
//         if(!empty($_GPC['keyword']) and $_GPC['order_status'] ==2){
//             $where['user_id'] = $keyword;
//         }
//         $where['uniacid'] = $_W['uniacid'];
//         $where['black'] = 0;
//         $pageindex = max(1, intval($_GPC['page']));
//         $pagesize = 10;

//         $list = pdo_getslice('kh_small_yx_users',$where,array($pageindex, $pagesize),$total,array(),'','user_id desc');
//         $page = pagination($total, $pageindex, $pagesize);
       
//         include $this->template('users');      
//     }




    // //报名---入口
    // public function doPageUpdatestep(){
        
    //     global $_GPC, $_W;
    //     $set = pdo_get('kh_small_yx_activity', array('uniacid' => $_GPC['i'],'id'=>$_GPC['aid']));
    //     $time=date('Y-m-d',time());
    //     $code = $_GPC['code'];
    //     $account = pdo_get('account_wxapp', array('uniacid' => $_GPC['i']));
    //     $url = 'https://api.weixin.qq.com/sns/jscode2session?appid=' . $account['key'] . '&secret=' . $account['secret'] . '&js_code=' . $code . '&grant_type=authorization_code';
    //     $result = $this->get_url_content($url);
    //     $result = json_decode($result, true);

    //     $uid           = $_GPC['user_id'];
    //     $encryptedData = $_GPC['wRunEncryptedData'];
    //     $iv            = $_GPC['iv'];
    //     $sessionKey    = $result['session_key'];
    //     $appid = $_W['account']['key'];
    //     $pc = new WXBizDataCrypt($appid, $sessionKey);
    //     $errCode = $pc->decryptData($encryptedData, $iv, $data);
    //     if($errCode == 0){
    //         $bushu = json_decode($data,'true');
    //         $bushu = $bushu['stepInfoList'][30]['step'];
    //         if($bushu >= $set['step']){
    //             pdo_update('kh_small_yx_activitylog',array('step'=>$bushu,'status'=>1), array('user_id' => $_GPC['user_id'],'aid'=>$_GPC['aid'],'time'=>$time,'uniacid'=>$_GPC['i']));
    //         }else{
    //            pdo_update('kh_small_yx_activitylog',array('step'=>$bushu), array('user_id' => $_GPC['user_id'],'aid'=>$_GPC['aid'],'time'=>$time,'uniacid'=>$_GPC['i'])); 
    //         }          
    //         return $this->result(0,'同步成功',$bushu);
    //     }else{
    //         return $this->result(1, array('errcode'=>$errCode));
    //     }
        
    // }





     //微信通过code 获取openid的接口
    public function doPageGetopenid()
    {
        ob_end_clean();
        global $_GPC, $_W;
        $code = $_GPC['code'];
        $account = pdo_get('account_wxapp', array('uniacid' => $_GPC['i']));
        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid=' . $account['key'] . '&secret=' . $account['secret'] . '&js_code=' . $code . '&grant_type=authorization_code';
       echo  $url  ;exit;
       $result = $this->get_url_content($url);
        $result = json_decode($result, true);
        var_dump($result);
        return $this->result(0, '获取成功', $result);
    }






    public function doPageTest(){
        ob_end_clean();
        global $_GPC, $_W;
        $post_url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$this->wx_get_token();
        $post_data = '';

        $json = $this->api_notice_increment($post_url,$post_data);
        $json=json_decode($json,true);
        return $this->result(0, '获取成功',$json);
    }




    public function get_url_content($url)
    {
        $user_agent = "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; Trident/4.0)";
        //$data_string = json_encode($data);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        //curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            //'Content-Length: ' . strlen($data_string)
        ));

        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }



    

    public function doPageZhuce()
    {
        ob_end_clean();
        global $_GPC, $_W;
        $set = pdo_get('kh_small_yx_set',array('uniacid'=>$_GPC['i']));
        $data['uniacid'] = $_GPC['i'];
        $data['city'] = $_POST['city'];
        $data['country'] = $_POST['country'];
        $data['gender'] = $_POST['gender'];
        $data['open_id'] = $_POST['open_id'];
        $data['nick_name'] = $_POST['nickName'];
        $data['head_pic'] = $_POST['avatarUrl'];
        $data['province'] = $_POST['province'];
        $stact = 1;
        if(empty($data['open_id'])){
            $this->result(1, $stact, '');
        }
        $uid = pdo_getcolumn('kh_small_yx_users', array('open_id' => $data['open_id'], 'uniacid' => $data['uniacid']), 'user_id', 1);
        if (empty($uid)){
            if($set['invitetype'] == 1){
                $stact = 0;
                //记录父id
                if($_POST['goods_id'] > 0){                 
                    $result = pdo_insert('kh_small_yx_users', $data, $replace = true);
                    $uid = pdo_insertid();
                    //添加一条邀请记录
                    $log['uniacid'] = $_GPC['i'];
                    $log['user_id'] = $_POST['invite'];
                    $log['son_id'] = $uid;
                    $log['goods_id'] = $_POST['goods_id'];
                    $log['time'] = time();
                    if($log['user_id'] > 0){
                        $invitelog = pdo_insert('kh_small_yx_peoplelog', $log, $replace = true);
                    }
                }else{
                    $data['fatherid'] = $_POST['invite'];                  
                    $result = pdo_insert('kh_small_yx_users', $data, $replace = true);
                    $uid = pdo_insertid();
                    //添加一条邀请记录
                    $log['uniacid'] = $_GPC['i'];
                    $log['user_id'] = $data['fatherid'];
                    $log['sonid'] = $uid;
                    $log['step'] = $set['sharestep'];
                    $log['invite_time'] = time();
                    $log['time'] = date('Y-m-d',time());
                    $log['status'] = 0;
                    if($log['user_id'] > 0){
                        $invitelog = pdo_insert('kh_small_yx_invitelog', $log, $replace = true);
                    }
                }               
            }
            if($set['invitetype'] == 2){
                $stact = 0;
                //记录父id
                if($_POST['goods_id'] > 0){                 
                    $result = pdo_insert('kh_small_yx_users', $data, $replace = true);
                    $uid = pdo_insertid();
                    //添加一条邀请记录
                    $log['uniacid'] = $_GPC['i'];
                    $log['user_id'] = $_POST['invite'];
                    $log['son_id'] = $uid;
                    $log['goods_id'] = $_POST['goods_id'];
                    $log['time'] = time();
                    if($log['user_id'] > 0){
                        $invitelog = pdo_insert('kh_small_yx_peoplelog', $log, $replace = true);
                    }
                }else{
                    $data['hongbaofid'] = $_POST['invite'];                  
                    $result = pdo_insert('kh_small_yx_users', $data, $replace = true);
                    $uid = pdo_insertid();
                    //添加一条邀请记录
                    $log['uniacid'] = $_GPC['i'];
                    $log['user_id'] = $data['hongbaofid'];
                    $log['sonid'] = $uid;
                    $log['invite_time'] = time();
                    $log['status'] = 0;
                    if($log['user_id'] > 0){
                        $invitelog = pdo_insert('kh_small_yx_hongbaolog', $log, $replace = true);
                        $hongbao = pdo_getall('kh_small_yx_hongbaolog',array('user_id'=>$log['user_id'],'uniacid'=>$_GPC['i']));
                        $count = count($hongbao);
                        $hbmoney = pdo_getcolumn('kh_small_yx_hongbao',array('displayorder'=>$count,'uniacid'=>$_GPC['i']),'hongbaomoney',1);
                        if($hbmoney){
                           $user = pdo_get('kh_small_yx_users',array('uniacid'=>$_GPC['i'],'user_id'=>$log['user_id']));
                           $nowmoney = $user['money'] + $hbmoney;
                           pdo_update('kh_small_yx_users',array('money'=>$nowmoney),array('user_id'=>$log['user_id']));
                        }
                    }
                }                
            }            
        }else{
            $result = pdo_update('kh_small_yx_users', $data, array('user_id' => $uid));
        }

        $mc_have = pdo_get('mc_mapping_fans',array('openid'=>$data['open_id']));
        if(empty($mc_have)){
            $mc_data = array(
                'acid' =>$data['uniacid'],
                'uniacid' =>$data['uniacid'],
                'uid' =>$uid,
                'openid' =>$data['open_id'],
                'nickname' =>$data['nick_name'],
            );
            pdo_insert('mc_mapping_fans',$mc_data);
        }
        
        $this->result(0, $stact, $uid);
    }

    //是否拉黑
    public function doPageBlack(){
        global $_GPC, $_W;       
        $user = pdo_get('kh_small_yx_users',array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id']));              
        return $this->result(0, '是否拉黑',$user['black']);
    }

    //签到
    public function doPageSignin(){
        
        global $_GPC, $_W;        
        $user = pdo_get('kh_small_yx_users',array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id']));
        /**如果有就进行判断时间差，然后处理签到次数*/
        /**昨天的时间戳时间范围*/
        $t = time();
        $last_start_time = mktime(0,0,0,date("m",$t),date("d",$t)-1,date("Y",$t));
        $last_end_time = mktime(23,59,59,date("m",$t),date("d",$t)-1,date("Y",$t));
        /**今天的时间戳时间范围*/
        $now_start_time = mktime(0,0,0,date("m",$t),date("d",$t),date("Y",$t));
        $now_end_time = mktime(23,59,59,date("m",$t),date("d",$t),date("Y",$t));
        /**判断最后一次签到时间是否在昨天的时间范围内*/
        if($last_start_time<$user['lasttime']&&$user['lasttime']<$last_end_time){
            $user['lasttime'] = time();
            $user['signtime'] = $user['signtime'] + 1;
            //$user['money'] = $user['money'] + 2;
            /**这里还可以加一些判断连续签到几天然后加积分等等的操作*/
            $result = pdo_update('kh_small_yx_users',$user,array('user_id' => $_GPC['user_id']));
            return $this->result(0, '获取成功',$result);
        }elseif($now_start_time<$user['lasttime']&&$user['lasttime']<$now_end_time){
            /**返回已经签到的操作*/
            $user['lasttime'] = time();
            //$user['signtime'] = 1;
            $result = pdo_update('kh_small_yx_users',$user,array('user_id' => $_GPC['user_id']));
            return $this->result(0, '获取成功',$result);
        }else{
            $user['lasttime'] = time();
            $user['signtime'] = 1;
            $result = pdo_update('kh_small_yx_users',$user,array('user_id' => $_GPC['user_id']));
            return $this->result(0, '获取成功',$result);
        }
        
    }

    //签到分享
    public function doPageSignshare(){
        global $_GPC, $_W;        
        $user = pdo_get('kh_small_yx_users',array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id']));
        $set = pdo_get('kh_small_yx_set',array('uniacid'=>$_GPC['i']));
        /**如果有就进行判断时间差，然后处理签到次数*/
        /**昨天的时间戳时间范围*/
        $t = time();
        //$last_start_time = mktime(0,0,0,date("m",$t),date("d",$t)-1,date("Y",$t));
        //$last_end_time = mktime(23,59,59,date("m",$t),date("d",$t)-1,date("Y",$t));
        /**今天的时间戳时间范围*/
         $now_start_time = mktime(0,0,0,date("m",$t),date("d",$t),date("Y",$t));
         $now_end_time = mktime(23,59,59,date("m",$t),date("d",$t),date("Y",$t));
        /**判断最后一次签到时间是否在昨天的时间范围内*/
        if($now_start_time<$user['sharetime']&&$user['sharetime']<$now_end_time){
            /**这里还可以加一些判断连续签到几天然后加积分等等的操作*/
            return $this->result(0, '今天已经分享过',$user);
        }else{
            /**返回已经签到的操作*/
            $user['sharetime'] = time();
            $user['money'] = $user['money'] + $set['signsharemoney'];
            $result = pdo_update('kh_small_yx_users',$user,array('user_id' => $_GPC['user_id']));
            return $this->result(0, '分享成功',$result);
        }
    }

    //审核状态
    public function doPageStatus(){
        global $_GPC, $_W;
        $config = pdo_get('hczhongzhuan_set',array('uniacid'=>$_GPC['i']),array('status'));       
         
        return $this->result(0, '审核状态',$config);
    }
    //页面
    /*public function doPageShenhe(){
        global $_GPC, $_W;
        $shenhe = pdo_getall('hczhongzhuan_shenhe', array('stact'=>1,'uniacid' => $_GPC['i']), array(),'','sort asc');
        foreach ($shenhe as $key => $val) {
            $shenhe[$key]['icon'] = $_W['attachurl'].$val['img'];
        }      
         
        ob_end_clean();
        global $_GPC, $_W;
        $shenhe=pdo_get('hczhongzhuan_shenhe', array('id' => $_GPC['id']));
        $this->result(0, '获取成功', $shenhe);
        //return $this->result(0, '页面',$shenhe);
    }*/
    
    function api_notice_increment($url, $data){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    public function doPagebushu(){
        global $_GPC, $_W;
        $set = pdo_get('kh_small_yx_set',array('uniacid'=>$_GPC['i']));
        $user = pdo_get('kh_small_yx_users',array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id']));

        $code = $_GPC['code'];
        $account = pdo_get('account_wxapp', array('uniacid' => $_GPC['i']));
        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid=' . $account['key'] . '&secret=' . $account['secret'] . '&js_code=' . $code . '&grant_type=authorization_code';
        $result = $this->get_url_content($url);
        $result = json_decode($result, true);

        $uid           = $_GPC['user_id'];
        $encryptedData = $_GPC['wRunEncryptedData'];
        $iv            = $_GPC['iv'];
        $sessionKey    = $result['session_key'];
        $appid = $_W['account']['key'];
        $pc = new WXBizDataCrypt($appid, $sessionKey);
        $errCode = $pc->decryptData($encryptedData, $iv, $data);
        if ($errCode == 0) {
            $bushu = json_decode($data,'true');
            $bushu = $bushu['stepInfoList'][30]['step'];

            $weixinbushu = $bushu;
            
            $time=date('Y-m-d',time());
            //兑换记录
            $log = pdo_getall('kh_small_yx_bushulog',array('user_id'=>$_GPC['user_id'],'uniacid'=>$_GPC['i'],'time'=>$time));
            $zong = array_sum(array_column($log,'bushu'));
            //邀请记录
            $invitelog = pdo_getall('kh_small_yx_invitelog',array('user_id'=>$_GPC['user_id'],'uniacid'=>$_GPC['i'],'status'=>1,'time'=>$time));
            $invitezong = array_sum(array_column($invitelog,'step'));
            //步数加成比例
            $invitelogall = pdo_getall('kh_small_yx_invitelog',array('user_id'=>$_GPC['user_id'],'uniacid'=>$_GPC['i']));
            $invitelogtoday = pdo_getall('kh_small_yx_invitelog',array('user_id'=>$_GPC['user_id'],'uniacid'=>$_GPC['i'],'time'=>$time));
            $count = count($invitelogall) - count($invitelogtoday);
            //步数加成
            $uplog = pdo_getall('kh_small_yx_uplog',array('user_id'=>$_GPC['user_id'],'uniacid'=>$_GPC['i'],'day'=>$time));
            $upzong = array_sum(array_column($uplog,'step'));
            $upbushu = $bushu * $count*$set['up']/100 - $upzong;
            $upbushu = floor($upbushu);
            //关注送步数
            $guanzhu = pdo_get('kh_small_yx_guanzhulog',array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id'],'status'=>1));

            $bushu = $bushu + $invitezong + $upzong + $guanzhu['step'] - $zong ;
             
            return $this->result(0, '操作成功',array('bushu'=>$bushu,'user'=>$user,'upbushu'=>$upbushu,'weixinbushu'=>$weixinbushu));
        } else {
            return $this->result(1, array('errcode'=>$errCode,'appid'=>$appid));
        }

    }

    public function doPagebushu2money(){
        
        global $_GPC, $_W;
        
        $set = pdo_get('kh_small_yx_set',array('uniacid'=>$_GPC['i']));
        $time=date('Y-m-d',time());
        $log = pdo_getall('kh_small_yx_bushulog',array('user_id'=>$_GPC['user_id'],'uniacid'=>$_GPC['i'],'time'=>$time));
        $zong = array_sum(array_column($log,'bushu'));
       
        $data['bushu'] = $_GPC['step'];
        $data['user_id'] = $_GPC['user_id'];
        $money = $data['bushu']*$set['rate'];

        if($data['bushu'] == 0){
           return $this->result(1,$set['zerotip']);
        }elseif($zong >= $set['maxstep'] and !empty($zong)){
           return $this->result(1,'今日已达到步数兑换上限，请明日再来吧');
        }else{
           if(($data['bushu'] +$zong) > $set['maxstep']){
             $money = ($set['maxstep'] - $zong)*$set['rate'];
             return $this->result(0,'',$money);
           }else{
             return $this->result(0,'',$money);
           }         
        }      
    }

    public function doPagebushulog(){
        global $_GPC, $_W;
        $user = pdo_get('kh_small_yx_users',array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id']));
        $set = pdo_get('kh_small_yx_set',array('uniacid'=>$_GPC['i']));
        
        $time=date('Y-m-d',time());
        $log = pdo_getall('kh_small_yx_bushulog',array('user_id'=>$_GPC['user_id'],'uniacid'=>$_GPC['i'],'time'=>$time));
        $zong = array_sum(array_column($log,'bushu'));
        
        $data['bushu'] = $_GPC['step'];
        $data['user_id'] = $_GPC['user_id'];
        $data['uniacid'] = $_GPC['i'];
        $data['timestamp'] = time();
        $data['time'] = date('Y-m-d',time());
        $data['money'] = $data['bushu']*$set['rate'];

        if($data['bushu'] == 0){
           return $this->result(1,$set['zerotip']);
        }elseif(($data['bushu'] + $zong) >= $set['maxstep']){
            $data['bushu'] = $set['maxstep'] - $zong;
            $data['user_id'] = $_GPC['user_id'];
            $data['uniacid'] = $_GPC['i'];
            $data['timestamp'] = time();
            $data['time'] = date('Y-m-d',time());
            $data['money'] = $data['bushu']*$set['rate'];

            $result = pdo_insert('kh_small_yx_bushulog',$data);

            $nowmoney = $user['money']+$data['money'];

            pdo_update('kh_small_yx_users',array('money'=>$nowmoney), array('user_id' => $_GPC['user_id']));  

            return $this->result(0, '操作成功',$result);         
        }else{
           $result = pdo_insert('kh_small_yx_bushulog',$data);

           $nowmoney = $user['money']+$data['money'];

           pdo_update('kh_small_yx_users',array('money'=>$nowmoney), array('user_id' => $_GPC['user_id']));  

           return $this->result(0, '操作成功',$result);
        }

    }

    public function doPageHeadcolor(){
        global $_GPC, $_W;
        
        $user = pdo_get('kh_small_yx_users',array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id']));
        $set = pdo_get('kh_small_yx_set',array('uniacid'=>$_GPC['i']));
        $user['stepid'] = $user['user_id'] + 100000;

        $set['sharepic'] = $_W['attachurl'].$set['sharepic'];
        $set['rulepic'] = $_W['attachurl'].$set['rulepic'];
        $set['voice'] = $_W['siteroot']."addons/kh_small_yx/coin.mp3";
        $set['no'] = $_W['siteroot']."addons/kh_small_yx/no.png";
        $set['no2'] = $_W['siteroot']."addons/kh_small_yx/no2.png";
        
        if(!empty($set['loginpic'])){
            $set['loginpic'] = $_W['attachurl'].$set['loginpic']; 
        }else{
            $set['loginpic'] = '';
        }
        if(!empty($set['indexbg'])){
            $set['indexbg'] = $_W['attachurl'].$set['indexbg']; 
        }else{
            $set['indexbg'] = '';
        }
        if(!empty($set['indexbutton'])){
            $set['indexbutton'] = $_W['attachurl'].$set['indexbutton']; 
        }else{
            $set['indexbutton'] = '';
        }
        if(!empty($set['inviteball'])){
            $set['inviteball'] = $_W['attachurl'].$set['inviteball']; 
        }else{
            $set['inviteball'] = '';
        }
        if(!empty($set['upball'])){
            $set['upball'] = $_W['attachurl'].$set['upball']; 
        }else{
            $set['upball'] = '';
        }
        if(!empty($set['questionpic'])){
            $set['questionpic'] = $_W['attachurl'].$set['questionpic']; 
        }else{
            $set['questionpic'] = '';
        }
        if(!empty($set['followpic'])){
            $set['followpic'] = $_W['attachurl'].$set['followpic']; 
        }else{
            $set['followpic'] = '';
        }
        if(!empty($set['kefupic'])){
            $set['kefupic'] = $_W['attachurl'].$set['kefupic']; 
        }else{
            $set['kefupic'] = '';
        }
        if(!empty($set['followlogo'])){
            $set['followlogo'] = $_W['attachurl'].$set['followlogo']; 
        }else{
            $set['followlogo'] = '';
        }
        if(!empty($set['boxpic'])){
            $set['boxpic'] = $_W['attachurl'].$set['boxpic']; 
        }else{
            $set['boxpic'] = '';
        }
        if(!empty($set['activitypic'])){
            $set['activitypic'] = $_W['attachurl'].$set['activitypic']; 
        }else{
            $set['activitypic'] = '';
        }
        if(!empty($set['applypic'])){
            $set['applypic'] = $_W['attachurl'].$set['applypic']; 
        }else{
            $set['applypic'] = '';
        }
        if(!empty($set['icon'])){
            $set['icon'] = $_W['attachurl'].$set['icon']; 
        }else{
            $set['icon'] = '';
        }
        if(!empty($set['signpic'])){
            $set['signpic'] = $_W['attachurl'].$set['signpic']; 
        }else{
            $set['signpic'] = '';
        }
        if(!empty($set['frame'])){
            $set['frame'] = $_W['attachurl'].$set['frame']; 
        }else{
            $set['frame'] = '';
        }
        if(!empty($set['signicon'])){
            $set['signicon'] = $_W['attachurl'].$set['signicon']; 
        }else{
            $set['signicon'] = '';
        }
        if(!empty($set['buttonbg'])){
            $set['buttonbg'] = $_W['attachurl'].$set['buttonbg']; 
        }else{
            $set['buttonbg'] = '';
        }
        if(!empty($set['coinpic'])){
            $set['coinpic'] = $_W['attachurl'].$set['coinpic']; 
        }else{
            $set['coinpic'] = '';
        }
        if(!empty($set['hongbaobg'])){
            $set['hongbaobg'] = $_W['attachurl'].$set['hongbaobg']; 
        }else{
            $set['hongbaobg'] = '';
        }
        if(!empty($set['longbg'])){
            $set['longbg'] = $_W['attachurl'].$set['longbg']; 
        }else{
            $set['longbg'] = '';
        }


        if($_GPC['user_id'] and $_GPC['user_id'] <> 0){

            $inviteball = pdo_getall('kh_small_yx_invitelog',array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id'],'status'=>0));
            $sonlist = pdo_getall('kh_small_yx_users',array('uniacid'=>$_GPC['i'],'fatherid'=>$_GPC['user_id'],'fatherid !='=>0));
            //步数加成
            $time=date('Y-m-d',time());
            $invitelogall = pdo_getall('kh_small_yx_invitelog',array('user_id'=>$_GPC['user_id'],'uniacid'=>$_GPC['i']));
            $invitelogtoday = pdo_getall('kh_small_yx_invitelog',array('user_id'=>$_GPC['user_id'],'uniacid'=>$_GPC['i'],'time'=>$time));
            $count = count($invitelogall) - count($invitelogtoday);
            $tcount = count($invitelogall);
            //是否显示关注气泡
            $guanzhu = pdo_get('kh_small_yx_guanzhulog',array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id']));
            if($guanzhu['status'] == 0 && !empty($guanzhu)){
                $set['is_guanzhujiang'] = 1; //显示气泡
            }else{
                $set['is_guanzhujiang'] = 0; //不显示气泡
            }

        }
        
        $set['todayup'] = $set['up']*$count;
        $set['tomorrow'] = $set['up']*$tcount;
        $set['adunit'] = $set['adunit'];
        $set['adunit2'] = $set['adunit2'];
        $set['adunit3'] = $set['adunit3'];

        //骗审数据
        //$fake = pdo_getll('kh_small_yx_users',array('uniacid'=>$_GPC['i']));
        $fake = pdo_getslice('kh_small_yx_users', array('uniacid'=>$_GPC['i']), array(0, 10), $total);
        $fake[0]['step'] = 23845;
        $fake[1]['step'] = 20667;
        $fake[2]['step'] = 18652;
        $fake[3]['step'] = 16632;
        $fake[4]['step'] = 12420;
        $fake[5]['step'] = 10248;
        $fake[6]['step'] = 8649;
        $fake[7]['step'] = 7456;
        $fake[8]['step'] = 6324;
        $fake[9]['step'] = 4396;

        $shop = pdo_get('kh_small_yx_shop',array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id']));
        if(!empty($shop)){
            $set['is_sao'] = 1; //显示扫一扫
        }else{
            $set['is_sao'] = 0; //不显示
        }

        return $this->result(0,'操作成功',array('inviteball'=>$inviteball,'sonlist'=>$sonlist,'user'=>$user,'set'=>$set,'fake'=>$fake));

    }

    //签到分享状态
    public function doPageIssignshare(){
        
        global $_GPC, $_W;
        $user = pdo_get('kh_small_yx_users',array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id']));
        $set = pdo_get('kh_small_yx_set',array('uniacid'=>$_GPC['i']));
        //判断今日签到是否分享过
        $t = time();
        /**今天的时间戳时间范围*/
        $now_start_time = mktime(0,0,0,date("m",$t),date("d",$t),date("Y",$t));
        $now_end_time = mktime(23,59,59,date("m",$t),date("d",$t),date("Y",$t));
        /**判断最后一次签到时间是否在昨天的时间范围内*/
        if($now_start_time<$user['sharetime']&&$user['sharetime']<$now_end_time){
            /**这里还可以加一些判断连续签到几天然后加积分等等的操作*/
            $set['is_signshare'] = 1;//今日已分享
        }else{
            $set['is_signshare'] = 0;//今日未分享
        }

        return $this->result(0,'操作成功',array('set'=>$set,'user'=>$user));
    }

    

    //商品列表
    public function doPageGoodsdetail(){

        global $_GPC, $_W;    
        $user = pdo_get('kh_small_yx_users',array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id']));
        if($_GPC['index'] == 1){
            $goods = pdo_get('kh_small_yx_goods',array('id'=>$_GPC['goods_id'],'uniacid'=>$_GPC['i']));
            if($goods['selltype'] == 1){
               $shop = pdo_get('kh_small_yx_shop',array('id'=>$goods['shop_id']));
               $goods['sheng'] = $shop['sheng'];
               $goods['shi'] = $shop['shi'];
               $goods['qu'] = $shop['qu'];
               $goods['address'] = $shop['address'];
               $goods['tel'] = $shop['tel'];
               $goods['starttime'] = $shop['starttime'];
               $goods['endtime'] = $shop['endtime'];
            }
            //兑换记录
            $goodslog = pdo_getall('kh_small_yx_orders', array('uniacid'=>$_GPC['i'],'goods_id'=>$goods['id'],'status !=' => 2));
            $xuni = pdo_getall('kh_small_yx_xuni', array('uniacid'=>$_GPC['i'],'goods_id'=>$goods['id']));
            foreach ($goodslog as $k => $v){
                $userinfo = pdo_get('kh_small_yx_users',array('uniacid'=>$_GPC['i'],'user_id'=>$v['user_id']));
                $list1[$k]['nick_name'] = $userinfo['nick_name'];
                $list1[$k]['head_pic'] = $userinfo['head_pic'];
                $list1[$k]['time'] = date('Y-m-d H:i',$v['time']);
            }
            foreach ($xuni as $k => $v){
                $list2[$k]['nick_name'] = $v['nick_name'];
                $list2[$k]['head_pic'] = $_W['attachurl'].$v['head_pic'];
                $list2[$k]['time'] = $v['time'];
            }
            
            if(!empty($list1) and !empty($list2)){
                $list = array_merge($list1,$list2);
                $temp = array_column($list,'time');
                array_multisort($temp, SORT_DESC, $list);
            }elseif(!empty($list1) and empty($list2)){
                $list = $list1;
                $temp = array_column($list,'time');
                array_multisort($temp, SORT_DESC, $list); 
            }elseif(empty($list1) and !empty($list2)){
                $list = $list2; 
                $temp = array_column($list,'time');
                array_multisort($temp, SORT_DESC, $list);
            }else{
                $list = $list; 
            }
            //邀请记录
            $peoplelog = pdo_getall('kh_small_yx_peoplelog', array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id'],'goods_id'=>$_GPC['goods_id']));
            
            for ($i=0; $i<$goods['minpeople']; $i++)
            {
                if(empty($peoplelog[$i])){
                   $people[$i]['head_pic'] = '';
                   $people[$i]['nick_name'] = ''; 
                }else{
                   $user = pdo_get('kh_small_yx_users',array('user_id'=>$peoplelog[$i]['son_id'],'uniacid'=>$_GPC['i']));
                   $people[$i]['head_pic'] = $user['head_pic'];
                   $people[$i]['nick_name'] = $user['nick_name'];
                }
            }

            $peoplesum = count($peoplelog);
            if($peoplesum >= $goods['minpeople']){
                $goods['is_duihuan'] = 1;  //满足邀请好友条件
            }else{
                $goods['is_duihuan'] = 0;  //未满足
            }

            $goods['goods_img'] = json_decode($goods['goods_img']);

            $goods['is_you'] = 1;
            if($goods['inventory'] <= 0){
                $goods['is_you'] = 0;
                //return $this->result(1, '已经抢光了',$goods);
            }
            if($goods['paytype'] == 1 and $goods['price2'] >$user['money']){
                $goods['is_rich'] = 0;//钱不够
            }elseif($goods['paytype'] == 0 and $goods['price'] >$user['money']){
                $goods['is_rich'] = 0;//钱不够
            }else{
                $goods['is_rich'] = 1;//钱够
            }

            if(!empty($goods['goods_img'])){
                foreach ($goods['goods_img'] as $k => $v) {
                   $goods['goods_img'][$k] = $_W['attachurl'].$v;
                }
            }
            
            $goods['usermoney'] = $user['money'];

            return $this->result(0, '商品详情',array('goods'=>$goods,'list'=>$list,'people'=>$people));
        }else{
            $goods = pdo_get('kh_small_yx_awards',array('id'=>$_GPC['goods_id'],'uniacid'=>$_GPC['i']));
            $goods['goods_img'] = json_decode($goods['goods_img']);

            if($goods['inventory'] <= 0){
                return $this->result(1, '已经抢光了',$goods);
            }
            if(!empty($goods['goods_img'])){
                foreach ($goods['goods_img'] as $k => $v) {
                   $goods['goods_img'][$k] = $_W['attachurl'].$v;
                }
            }          
            $goods['usermoney'] = $user['money'];
            return $this->result(0, '商品详情',$goods);
        }
    }

    //奖品列表
    public function doPageAwardslist(){

        global $_GPC, $_W;      
        $list = pdo_getall('kh_small_yx_awards', array('uniacid'=>$_GPC['i']));
        foreach ($list as $k => $v) {
            $list[$k]['main_img'] = $_W['attachurl'].$v['main_img'];
        }
        return $this->result(0, '商品列表',$list);

    }

    //商品列表
    public function doPageCreateorder(){

        global $_GPC, $_W; 

        $goods = pdo_get('kh_small_yx_goods',array('id'=>$_GPC['goods_id'],'uniacid'=>$_GPC['i']));
        $user = pdo_get('kh_small_yx_users',array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id']));   
        
        $data['uniacid'] = $_GPC['i'];
        $data['userName'] = $_GPC['userName'];
        $data['postalCode'] = $_GPC['postalCode'];
        $data['provinceName'] = $_GPC['provinceName'];
        $data['cityName'] = $_GPC['cityName'];
        $data['countyName'] = $_GPC['countyName'];
        $data['detailInfo'] = $_GPC['detailInfo'];
        $data['nationalCode'] = $_GPC['nationalCode'];
        $data['telNumber'] = $_GPC['telNumber'];
        $data['user_id'] = $_GPC['user_id'];
        $data['goods_id'] = $_GPC['goods_id'];
        $data['type'] = 2;
        $data['status'] = 0;
        $data['time'] = time();

        $orders = pdo_getall('kh_small_yx_orders', array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id'],'goods_id'=>$_GPC['goods_id']));
        $ordersum = count($orders);
        if($goods['maxbuy'] > 0){
            if($ordersum >= $goods['maxbuy']){
                return $this->result(1, '已达兑换上限',$user);
            }
        }

        if($user['money'] < $goods['price']){
            return $this->result(1, '余额不足',$user);
        }elseif($goods['inventory'] <=0 ){
            return $this->result(1, '库存不足',$goods);
        }else{
            $order = pdo_insert('kh_small_yx_orders', $data, $replace = true);
            $nowmoney = $user['money'] - $goods['price'];
            pdo_update('kh_small_yx_users',array('money'=>$nowmoney), array('user_id' => $_GPC['user_id']));
            $nowinventory = $goods['inventory'] - 1;
            pdo_update('kh_small_yx_goods',array('inventory'=>$nowinventory), array('id' => $_GPC['goods_id']));
            if($order){
               return $this->result(0, '兑换成功',$order); 
            }else{
               return $this->result(1, '兑换失败',$order);
            }
        } 
        
    }

    //商品列表
    public function doPageCreatepeoplegoods(){

        global $_GPC, $_W; 

        $goods = pdo_get('kh_small_yx_goods',array('id'=>$_GPC['goods_id'],'uniacid'=>$_GPC['i']));
        $user = pdo_get('kh_small_yx_users',array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id'])); 
        $log =  pdo_get('kh_small_yx_orders',array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id'],'goods_id'=>$_GPC['goods_id'],'type'=>3)); 

        if(!empty($log)){
           return $this->result(1, '您已经兑换过此商品',$log);
        }else{
           $data['uniacid'] = $_GPC['i'];
            $data['userName'] = $_GPC['userName'];
            $data['postalCode'] = $_GPC['postalCode'];
            $data['provinceName'] = $_GPC['provinceName'];
            $data['cityName'] = $_GPC['cityName'];
            $data['countyName'] = $_GPC['countyName'];
            $data['detailInfo'] = $_GPC['detailInfo'];
            $data['nationalCode'] = $_GPC['nationalCode'];
            $data['telNumber'] = $_GPC['telNumber'];
            $data['user_id'] = $_GPC['user_id'];
            $data['goods_id'] = $_GPC['goods_id'];
            $data['type'] = 3;
            $data['status'] = 0;
            $data['time'] = time();
           

                $order = pdo_insert('kh_small_yx_orders', $data, $replace = true);
                $oid = pdo_insertid();
                $nowmoney = $user['money'] - $goods['price'];
                pdo_update('kh_small_yx_users',array('money'=>$nowmoney), array('user_id' => $_GPC['user_id']));
                $nowinventory = $goods['inventory'] - 1;
                pdo_update('kh_small_yx_goods',array('inventory'=>$nowinventory), array('id' => $_GPC['goods_id']));
                if($order){
                   return $this->result(0, '兑换成功',$oid); 
                }else{
                   return $this->result(1, '兑换失败',$oid);
                }
 
        }     
    }

    //商品列表
    public function doPageCreatehexiao(){

        global $_GPC, $_W; 

        $goods = pdo_get('kh_small_yx_goods',array('id'=>$_GPC['goods_id'],'uniacid'=>$_GPC['i']));
        $user = pdo_get('kh_small_yx_users',array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id']));   
        
        $data['uniacid'] = $_GPC['i'];
        /*$data['userName'] = $_GPC['userName'];
        $data['postalCode'] = $_GPC['postalCode'];
        $data['provinceName'] = $_GPC['provinceName'];
        $data['cityName'] = $_GPC['cityName'];
        $data['countyName'] = $_GPC['countyName'];
        $data['detailInfo'] = $_GPC['detailInfo'];
        $data['nationalCode'] = $_GPC['nationalCode'];
        $data['telNumber'] = $_GPC['telNumber'];*/
        $data['user_id'] = $_GPC['user_id'];
        $data['goods_id'] = $_GPC['goods_id'];
        $data['type'] = 12;
        $data['status'] = 0;
        $data['time'] = time();

        $orders = pdo_getall('kh_small_yx_orders', array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id'],'goods_id'=>$_GPC['goods_id']));
        $ordersum = count($orders);
        if($goods['maxbuy'] > 0){
            if($ordersum >= $goods['maxbuy']){
                return $this->result(1, '已达兑换上限',$user);
            }
        }
       
        if($user['money'] < $goods['price']){
            return $this->result(1, '余额不足',$user);
        }elseif($goods['inventory'] <= 0){
            return $this->result(1, '库存不足',$goods);
        }else{
            $order = pdo_insert('kh_small_yx_orders', $data, $replace = true);
            $oid = pdo_insertid();
            $nowmoney = $user['money'] - $goods['price'];
            pdo_update('kh_small_yx_users',array('money'=>$nowmoney), array('user_id' => $_GPC['user_id']));
            $nowinventory = $goods['inventory'] - 1;
            pdo_update('kh_small_yx_goods',array('inventory'=>$nowinventory), array('id' => $_GPC['goods_id']));
            if($order){
               return $this->result(0, '兑换成功',$oid); 
            }else{
               return $this->result(1, '兑换失败',$oid);
            }
        } 
        
    }
    //点击邀请气泡换步数
    public function doPageBall2bushu(){
        global $_GPC, $_W;
        $ballid = $_GPC['id'];
        $result = pdo_update('kh_small_yx_invitelog',array('status'=>1), array('id' => $ballid));
        if($result){
           return $this->result(0, '获取成功',$result); 
        }else{
           return $this->result(1, '获取失败',$result);
        }
    }

    //点击加成气泡换步数
    public function doPageUpball2bushu(){
        global $_GPC, $_W;

        $data['uniacid'] = $_GPC['i'];
        $data['user_id'] = $_GPC['user_id'];
        $data['step'] = $_GPC['upbushu'];
        $data['time'] = time();
        $data['day'] = date('Y-m-d',time());
   
        $result = pdo_insert('kh_small_yx_uplog', $data, $replace = true);
        if($result){
           return $this->result(0, '获取成功',$result); 
        }else{
           return $this->result(1, '获取失败',$result);
        }
    }

    //点击关注气泡换步数
    public function doPageGuanzhuball2bushu(){
        global $_GPC, $_W;

        $data['uniacid'] = $_GPC['i'];
        $data['user_id'] = $_GPC['user_id'];
        $result = pdo_update('kh_small_yx_guanzhulog',array('status'=>1), array('user_id' => $_GPC['user_id'],'uniacid'=>$_GPC['i']));

        if($result){
           return $this->result(0, '获取成功',$result); 
        }else{
           return $this->result(1, '获取失败',$result);
        }
    }

    //点击邀请气泡换步数
    public function doPageYy(){
        global $_GPC, $_W;
        $result = pdo_update('kh_small_yx_users',array('is_yy'=>1), array('user_id' =>$_GPC['user_id']));
        if($result){
           return $this->result(0, '获取成功',$result); 
        }else{
           return $this->result(1, '获取失败',$result);
        }
    }

    //抽奖
    public function doPageLotto(){
        
        global $_GPC, $_W;
        $user = pdo_get('kh_small_yx_users',array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id']));
        $set = pdo_get('kh_small_yx_set',array('uniacid'=>$_GPC['i']));
        if($user['money'] < $set['boxprice']){
            return $this->result(1, $set['coinname'].'不足',$user);
        }else{
            $list = pdo_getall('kh_small_yx_awards', array('uniacid'=>$_GPC['i'],'status'=>1));
            if($list){
               $idlist = array_column($list,'id');
               $awardsid = array_rand($idlist,1);

               $data['uniacid'] = $_GPC['i'];
               $data['user_id'] = $_GPC['user_id'];
               $data['goods_id'] = $idlist[$awardsid];
               $data['time'] =time();
               $result = pdo_insert('kh_small_yx_winlog', $data, $replace = true);
               
               $nowmoney = $user['money'] - $set['boxprice'];
               pdo_update('kh_small_yx_users',array('money'=>$nowmoney), array('user_id' => $_GPC['user_id']));

               $goods = pdo_get('kh_small_yx_awards',array('id'=>$data['goods_id'],'uniacid'=>$_GPC['i']));
               $tip = "恭喜您抽中了".$goods['goods_name'].",请到个人中心中奖纪录完善收货信息。";
               return $this->result(0,$tip,$goods['goods_name']); 
            }else{
               $nowmoney = $user['money'] - $set['boxprice'];
               pdo_update('kh_small_yx_users',array('money'=>$nowmoney), array('user_id' => $_GPC['user_id']));
               return $this->result(1, '本次未抽中，请继续努力',$list);
            }
        }       

    }

    //完善中奖订单信息
    public function doPageEditwinlog(){

        global $_GPC, $_W;    
        
        $data['userName'] = $_GPC['userName'];
        $data['postalCode'] = $_GPC['postalCode'];
        $data['provinceName'] = $_GPC['provinceName'];
        $data['cityName'] = $_GPC['cityName'];
        $data['countyName'] = $_GPC['countyName'];
        $data['detailInfo'] = $_GPC['detailInfo'];
        $data['nationalCode'] = $_GPC['nationalCode'];
        $data['telNumber'] = $_GPC['telNumber'];

        $order = pdo_update('kh_small_yx_winlog', $data, array('id' =>$_GPC['id']));


        if($order){
           return $this->result(0,'修改成功',$order); 
        }else{
           return $this->result(1,'修改失败',$order);
        }

    }

    //兑换记录
    public function doPageLog(){
       
        global $_GPC, $_W;

        $qie = $_GPC['qie'];
        //燃力币兑换记录
        if($qie == 0){
           $coinlog = pdo_getall('kh_small_yx_bushulog',array('user_id'=>$_GPC['user_id'],'uniacid'=>$_GPC['i']),array(),'','timestamp desc');
            foreach ($coinlog as $k => $v) {
                $coinlog[$k]['timestamp'] = date('Y-m-d H:i:s',$v['timestamp']);
            } 
        }
        //商品兑换记录
        if($qie == 1){
            $goodslog = pdo_getall('kh_small_yx_orders',array('user_id'=>$_GPC['user_id'],'uniacid'=>$_GPC['i'],'status !='=> 2,'type <'=>10),array(),'','time desc');
            foreach ($goodslog as $k => $v) {
                $goods = pdo_get('kh_small_yx_goods',array('id'=>$v['goods_id'],'uniacid'=>$_GPC['i']));
                $goodslog[$k]['goods_name'] = $goods['goods_name'];
                $goodslog[$k]['time'] = date('Y-m-d H:i:s',$v['time']);
                $goodslog[$k]['fahuotime'] = date('Y-m-d H:i:s',$v['fahuotime']);
                if($v['status'] == 0){
                    $goodslog[$k]['fahuo'] = '待发货';
                }else{
                    $goodslog[$k]['fahuo'] = '已发货';
                }           
            } 
        }

        //奖品兑换记录
        if($qie == 2){
            $awardslog = pdo_getall('kh_small_yx_winlog',array('user_id'=>$_GPC['user_id'],'uniacid'=>$_GPC['i']),array(),'','time desc');
            foreach ($awardslog as $k => $v) {
                $goods = pdo_get('kh_small_yx_awards',array('id'=>$v['goods_id'],'uniacid'=>$_GPC['i']));
                $awardslog[$k]['main_img'] = $_W['attachurl'].$goods['main_img'];
                $awardslog[$k]['goods_name'] = $goods['goods_name'];
                $awardslog[$k]['time'] = date('Y-m-d H:i:s',$v['time']);
                
                if($awardslog[$k]['status'] == 0){
                    $awardslog[$k]['zhuangtai'] = "待发货";
                }else{
                    $awardslog[$k]['zhuangtai'] = "已发货";
                }
                if($awardslog[$k]['userName']){
                    $awardslog[$k]['is_dizhi'] = 1;
                }else{
                    $awardslog[$k]['is_dizhi'] = 0;
                }
            } 
        }

        return $this->result(0, '获取成功',array('coinlog'=>$coinlog,'goodslog'=>$goodslog,'awardslog'=>$awardslog));
    }

    //邀请记录
    public function doPageInvitelog(){
       
        global $_GPC, $_W;

        $set = pdo_get('kh_small_yx_set', array('uniacid' => $_W['uniacid']));

        if($set['invitetype'] == 1){
           $invitelog = pdo_getall('kh_small_yx_invitelog', array('user_id'=>$_GPC['user_id'],'uniacid' => $_GPC['i']), array(), '', 'invite_time desc');  
        }else{
            $invitelog = pdo_getall('kh_small_yx_hongbaolog', array('user_id'=>$_GPC['user_id'],'uniacid' => $_GPC['i']), array(), '', 'invite_time desc');
        }

        
        foreach ($invitelog as $k => $v) {
            $user = pdo_get('kh_small_yx_users',array('user_id'=>$v['sonid'],'uniacid'=>$_GPC['i']));
            $invitelog[$k]['nick_name'] = $user['nick_name'];
            $invitelog[$k]['head_pic'] = $user['head_pic'];  
            $invitelog[$k]['invite_time'] = date('Y-m-d H:i',$v['invite_time']);
        }

        $invitetype = $set['invitetype'];

        return $this->result(0, '获取成功',array('invitelog'=>$invitelog,'invitetype'=>$invitetype));
    }

    //问题列表
    public function doPageQuestion(){

        global $_GPC, $_W;
        $question = pdo_getall('kh_small_yx_question', array('enabled' => 1,'uniacid'=>$_GPC['i']));
        
        foreach ($question as $k => $v) {
            $question[$k]['createtime'] = date('Y-m-d',$v['createtime']);
        }
        
        return $this->result(0,'问题',$question);

    }
    public function doPagePosterlist(){
        global $_GPC, $_W;
        $user = pdo_get('kh_small_yx_users',array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id']));
        $set = pdo_get('kh_small_yx_set',array('uniacid'=>$_GPC['i'])); 

        if(!empty($set['posterpic'])){
            $data['url'] = $_W['attachurl'].$set['posterpic']; 
        }else{
            $data['url'] = $_W['siteroot']."addons/kh_small_yx/tu1.jpg";
        }   
        
        $data['text'] = $set['comeon']; 
        $data['nick_name'] = $user['nick_name'];
        $data['head_pic'] = $user['head_pic'];
        $data['time'] = date('Y年m月d日',time());

        /*$accessTokenObject = json_decode(file_get_contents('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$_W['account']['key'].'&secret='.$_W['account']['secret']));
        //二维码接口
        //$url="https://api.weixin.qq.com/cgi-bin/wxaapp/createwxaqrcode?access_token=".$accessTokenObject->access_token;
        //菊花码
        $url = "https://api.weixin.qq.com/wxa/getwxacode?access_token=".$accessTokenObject->access_token;
        $width = '430';
        $path = '/kh_small_yx/pages/index/index?invite='.$user_id;
        $json='{"path":"'.$path.'","width":'.$width.'}';
        //$json = '{"scene": "/user_id/'.$user_id.'", "width": 50, "page": ""}';
        //$data=$this->api_notice_increment($url,$json);
        $aaa=$this->request_post($url,$json);*/

        $data['qrcode'] = $this->GetQrcode($_GPC['user_id']);
        $data['xcx'] = $set['xcx'];
        /*$data['1'] =  $accessTokenObject;
        $data['2'] =  $url;
        $data['3'] =  $aaa;*/   
        
        return $this->result(0,'海报',$data);
    }

    public function doPagePosterurl(){
        global $_GPC, $_W;    
        $url = $_W['siteroot']."addons/kh_small_yx/poster.jpg";       
        
        return $this->result(0,'海报',$url);
    }

    public function doPageCreate(){
        ob_end_clean();
        ob_clean();
        global $_GPC, $_W;   
        $res = $this->createPoster($_GPC['bushu'],$_GPC['user_id']);
    }

    // 生成海报
    public function CreatePoster($bushu,$user_id){
        ob_end_clean();
        ob_clean();
        global $_W,$_GPC;
        $set = pdo_get('kh_small_yx_set',array('uniacid'=>$_GPC['i'])); 
        $user = pdo_get('kh_small_yx_users',array('uniacid'=>$_GPC['i'],'user_id'=>$user_id));
        if(!empty($set['posterpic'])){
            $bg_path = $_W['attachurl'].$set['posterpic']; 
        }else{
            $bg_path = $_W['siteroot']."addons/kh_small_yx/tu1.jpg";//自定义背景
        }               
        $dst_path = $_W['siteroot']."addons/kh_small_yx/white.png";//背景图
        $hp_path = $this->download_remote_pic($user['head_pic']);
        $qr_path = $this->GetQrcode($user_id);//小程序码
        //创建图片的实例
        $dst = imagecreatefromstring(file_get_contents($dst_path));
        $qr = imagecreatefromstring(file_get_contents($qr_path));
        $hp = imagecreatefromstring(file_get_contents($hp_path));
        $bg = imagecreatefromstring(file_get_contents($bg_path)); 
        //打上文字
        $font = '../addons/kh_small_yx/PingFang.ttf';//字体路径
        $boldfont = '../addons/kh_small_yx/Bold.ttf';//粗字体路径
        //echo $font;
        $black = imagecolorallocate($dst, 0x00, 0x00, 0x00);//字体颜色
        $red = imagecolorallocate($dst,224,47,37);//券后价字体颜色
        $white = imagecolorallocate($dst,255,255,255);//优惠券颜色
        $hui = imagecolorallocate($dst,126,126,126);//原价颜色      
        
        //印上背景图
        list($bg_w, $bg_h) = getimagesize($bg_path);
        $bgnew_x = 689;
        $bgnew_y = 772;
        $image_bg = imagecreatetruecolor($bgnew_x,$bgnew_y); //设置缩略图
        imagecopyresampled($image_bg, $bg, 0, 0, 0, 0, $bgnew_x, $bgnew_y, $bg_w, $bg_h);
        imagecopymerge($dst,$image_bg,0,0,0,0,$bgnew_x,$bgnew_y,100);
        //印上二维码
        list($qr_w, $qr_h) = getimagesize($qr_path);
        $new_x = 115;
        $new_y = 115;
        $image_p = imagecreatetruecolor($new_x, $new_y); //设置缩略图
        imagecopyresampled($image_p, $qr, 0, 0, 0, 0, $new_x, $new_y, $qr_w, $qr_h);
        imagecopymerge($dst,$image_p,570,773,0,0,$new_x,$new_y,100);
        //印上头像
        list($hp_w, $hp_h) = getimagesize($hp_path);
        $hnew_x = 110;
        $hnew_y = 110;
        $image_hp = imagecreatetruecolor($hnew_x,$hnew_y); //设置缩略图
        imagecopyresampled($image_hp, $hp, 0, 0, 0, 0, $hnew_x, $hnew_y, $hp_w, $hp_h);
        imagecopymerge($dst,$image_hp,17,637,0,0,$hnew_x,$hnew_y,100);
        //打上文字
        $nickname =$user['nick_name'];
        $text =$set['comeon'];
        $date = date('Y年m月d日',time());
        $wname = $set['xcx'];
        $long = strlen($bushu);
        $right = 25 + 43*$long;
        imagefttext($dst,18,0,137,670,$white,$boldfont,$nickname);//昵称
        imagefttext($dst,18,0,137,700,$white,$font,'——————————————————————————————————————————');//昵称
        imagefttext($dst,30,0,137,730,$white,$boldfont,$text);//格言
        imagefttext($dst,15,0,480,25,$white,$boldfont,$date);//日期
        imagefttext($dst,50,0,25,850,$black,$boldfont,$bushu);//步数
        imagefttext($dst,20,0,$right,850,$black,$boldfont,"步");//步数
        imagefttext($dst,23,0,415,830,$black,$boldfont,$wname);//步数
        imagefttext($dst,15,0,415,860,$hui,$boldfont,'长按识别小程序');//步数
        //输出图片
       /* list($dst_w, $dst_h, $dst_type) = getimagesize($dst_path);
        switch ($dst_type) {
        case 1://GIF
           //header('Content-Type: image/gif');
           imagegif($dst);
           break;
        case 2://JPG
           //header('Content-Type: image/jpeg');
           imagejpeg($dst);
           break;
        case 3://PNG
           //header('Content-Type: image/png');          
           imagepng($dst);
           break;
        default:
           break;
        }*/

        $filename = dirname(__FILE__)."/poster.jpg";
        imagepng($dst,$filename);
        imagedestroy($dst);

    }
    //生成小程序码
    public function GetQrcode($user_id){
        global $_GPC, $_W;
        // 获取access_token
        //$accessTokenObject = json_decode(file_get_contents('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$_W['account']['key'].'&secret='.$_W['account']['secret']));
        $account_api = WeAccount::create();
        $token = $account_api->getAccessToken();
        //菊花码
        //$url = "https://api.weixin.qq.com/wxa/getwxacode?access_token=".$accessTokenObject->access_token;
        $url = "https://api.weixin.qq.com/wxa/getwxacode?access_token=".$token;
        $width = '430';
        $path = '/kh_small_yx/pages/index/index?invite='.$user_id;
        $json='{"path":"'.$path.'","width":'.$width.'}';
        //$json = '{"scene": "/user_id/'.$user_id.'", "width": 50, "page": ""}';
        //$data=$this->api_notice_increment($url,$json);
        $data=$this->request_post($url,$json);
        $filename = dirname(__FILE__)."/erweima.png";
        $local_file = fopen($filename, 'w');
        if (false !== $local_file) {
            if (false !== fwrite($local_file, $data)) {
                fclose($local_file);
            }
        }
        //file_put_contents($filename,$data);
        return $_W['siteroot']."addons/kh_small_yx/erweima.png";
    }

    //生成小程序码
    public function doPageGethxcode(){
        global $_GPC, $_W;
        //获取access_token
        //$accessTokenObject = json_decode(file_get_contents('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$_W['account']['key'].'&secret='.$_W['account']['secret']));
        $order = pdo_get('kh_small_yx_orders',array('id'=>$_GPC['order_id']));
        $goods = pdo_get('kh_small_yx_goods',array('id'=>$order['goods_id'])); 
        $shop  = pdo_get('kh_small_yx_shop',array('id'=>$goods['shop_id']));
        $aaa['logo'] = $_W['attachurl'].$shop['logo'];
        $aaa['shopname'] = $shop['shopname']; 
        $account_api = WeAccount::create();
        $token = $account_api->getAccessToken();
        //菊花码
        //$url = "https://api.weixin.qq.com/wxa/getwxacode?access_token=".$accessTokenObject->access_token;
        $url = "https://api.weixin.qq.com/wxa/getwxacode?access_token=".$token;
        $width = '180';
        $path = '/kh_small_yx/pages/room/room?order_id='.$_GPC['order_id'];
        $json='{"path":"'.$path.'","width":'.$width.'}';
        //$json = '{"scene": "/user_id/'.$user_id.'", "width": 50, "page": ""}';
        //$data=$this->api_notice_increment($url,$json);
        $data = $this->request_post($url,$json);
        $png = "/headpic/hexiao".$_GPC['i'].time().".png";
        $filename = dirname(__FILE__).$png;
        $local_file = fopen($filename, 'w');
        if (false !== $local_file) {
            if (false !== fwrite($local_file, $data)) {
                fclose($local_file);
            }
        }
        $url = $_W['siteroot']."addons/kh_small_yx".$png;
        $aaa['url'] = $url;
        return $this->result(0,'获取成功',$aaa);
        //return $_W['siteroot']."addons/kh_small_yx/hexiao".$_GPC['i'].".png";
    }

    //消息客服
    public function doPageKefu()
    {
        ob_end_clean();
        global $_GPC, $_W;

        $user_id = $_GPC['user_id'];
        $users=pdo_get('kh_small_yx_users', array('user_id' => $user_id));
        $setup=pdo_get('kh_small_yx_set', array('uniacid' => $_GPC['i']));
        $post_url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$this->wx_get_token();
        $post_data = json_encode(array(
            'touser' =>$users['open_id'],
            'msgtype' => 'link',
            'link' => array(
                'title'=>$setup['kefu_title'],
                'description'=>$setup['kefu_gaishu'],
                'url'=>$setup['kefu_url'],
                'thumb_url'=>$_W['attachurl'].$setup['kefu_img'],
            ),
        ), JSON_UNESCAPED_UNICODE);
        $json = $this->api_notice_increment($post_url,$post_data);
        $arr = json_decode($json,true);
        if($arr['errcode']!=0){
            $this->kefu($user_id);
        }
        $myfile = fopen(IA_ROOT."/addons/kh_small_yx/kefu.txt", 'w');
        fwrite($myfile, $json);
        fclose($myfile);
        $json=json_decode($json,true);
        return $this->result(0, '获取成功',$json);
    }


    public function kefu($user_id)
    {
        ob_end_clean();
        global $_GPC, $_W;
        $users=pdo_get('kh_small_yx_users', array('user_id' => $user_id));
        $setup=pdo_get('kh_small_yx_set', array('uniacid' => $users['uniacid']));
        $post_url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$this->wx_get_token();
        $post_data = json_encode(array(
            'touser' =>$users['open_id'],
            'msgtype' => 'link',
            'link' => array(
                'title'=>$setup['kefu_title'],
                'description'=>$setup['kefu_gaishu'],
                'url'=>$setup['kefu_url'],
                'thumb_url'=>$_W['attachurl'].$setup['kefu_img'],
            ),
        ), JSON_UNESCAPED_UNICODE);

        $json = $this->api_notice_increment($post_url,$post_data);
        $json=json_decode($json,true);
        if($json['errcode']!=0){
            $this->kefu($user_id);
        }
        return $this->result(0, '获取成功',$json);
    }

    //关注记录
    public function doPageGuanzhuaddstep()
    {
        ob_end_clean();
        global $_GPC, $_W;
        $set = pdo_get('kh_small_yx_set', array('uniacid' => $_GPC['i']));
        $data['uniacid'] = $_GPC['i'];
        $data['user_id'] = $_GPC['user_id'];
        $data['step'] = $set['guanzhu_step'];
        $data['time'] = time();
        $data['status'] = 0;//关注未赠送

        $ishave = pdo_get('kh_small_yx_guanzhulog', array('user_id' => $_GPC['user_id'],'uniacid'=>$_GPC['i']));
            if($ishave){
                $aaa = 1;
            }else{
                $result = pdo_insert('kh_small_yx_guanzhulog', $data, $replace = true);
            }
            if(!empty($result)) {
               return $this->result(0, '获取成功',$result);
            }else{
               return $this->result(1,'',$result);
            }
    }

    //根据版本号骗审
    public function doPageShenhe(){

        global $_GPC, $_W;
    
        $shenhe = pdo_get('kh_small_yx_set', array('uniacid'=>$_GPC['i']));

        if($shenhe['version'] == $_GPC['v']){
            $data['shenhe'] = 1;
        }else{
            $data['shenhe'] = 0;
        }
        
        $this->result(0, '是否骗审',$data);      
          
    }
    //骗审功能接口
    public function doPageSanshibushu(){
        global $_GPC, $_W;
        $set = pdo_get('kh_small_yx_set',array('uniacid'=>$_GPC['i']));
        $user = pdo_get('kh_small_yx_users',array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id']));

        $code = $_GPC['code'];
        $account = pdo_get('account_wxapp', array('uniacid' => $_GPC['i']));
        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid=' . $account['key'] . '&secret=' . $account['secret'] . '&js_code=' . $code . '&grant_type=authorization_code';
        $result = $this->get_url_content($url);
        $result = json_decode($result, true);

        $uid           = $_GPC['user_id'];
        $encryptedData = $_GPC['wRunEncryptedData'];
        $iv            = $_GPC['iv'];
        $sessionKey    = $result['session_key'];
        $appid = $_W['account']['key'];
        $pc = new WXBizDataCrypt($appid, $sessionKey);
        $errCode = $pc->decryptData($encryptedData, $iv, $data);
        if ($errCode == 0) {
            $bushu = json_decode($data,'true');
            //$bushu = $bushu['stepInfoList'][30]['step'];
            $bushu = $bushu['stepInfoList'];
            foreach ($bushu as $k => $v) {
                $bushu[$k]['timestamp'] = date('Y-m-d',$v['timestamp']);
                $arr[] = $v['step'];
            }
            
            $time=date('Y-m-d',time());

            $aaa['today'] = $bushu[30]['step'];
            $aaa['max'] = max($arr);
            $aaa['count'] = array_sum($arr);
                        
            return $this->result(0, '操作成功',array('bushu'=>$bushu,'data'=>$aaa));
        }else{
            return $this->result(1, array('errcode'=>$errCode,'appid'=>$appid));
        }

    }
    public function doPageAdv(){

        global $_GPC, $_W;
        //首页轮播图
        $banner = pdo_getall('kh_small_yx_adv', array('enabled'=>1,'uniacid' => $_GPC['i']), array(), '', 'displayorder asc');
        foreach ($banner as $key => $val) {
            if(strpos($val['thumb'],'https') !== false){
               $banner[$key]['thumb'] = $val['thumb'];
            }else{
               $banner[$key]['thumb'] = $_W['attachurl'].$val['thumb'];
            } 

            if(strpos($val['tippic'],'https') !== false){
               $banner[$key]['tippic'] = $val['tippic'];
            }else{
               $banner[$key]['tippic'] = $_W['attachurl'].$val['tippic'];
            }       
        }

        $icon = pdo_getall('kh_small_yx_icon', array('enabled'=>1,'uniacid' => $_GPC['i']), array(), '', 'displayorder asc');
        foreach ($icon as $key => $val) {
            if(strpos($val['thumb'],'https') !== false){
               $icon[$key]['thumb'] = $val['thumb'];
            }else{
               $icon[$key]['thumb'] = $_W['attachurl'].$val['thumb'];
            }
            if(strpos($val['tippic'],'https') !== false){
               $icon[$key]['tippic'] = $val['tippic'];
            }else{
               $icon[$key]['tippic'] = $_W['attachurl'].$val['tippic'];
            }
            if($icon[$key]['jump'] == 0 ){
                if(empty($val['runpic'])){
                   $runpic = $val['runpic'];
                }else{
                   $runpic = $_W['attachurl'].$val['runpic'];
                }
            }        
        }

        $hongbao = pdo_getall('kh_small_yx_hongbao', array('enabled'=>1,'uniacid' => $_GPC['i']), array(), '', 'displayorder asc');
        $hongbaolog = pdo_getall('kh_small_yx_hongbaolog',array('user_id'=>$_GPC['user_id'],'uniacid'=>$_GPC['i']));
        $count = count($hongbaolog);
        foreach ($hongbao as $key => $val) {
            if(strpos($val['hongbaopic'],'https') !== false){
               $hongbao[$key]['hongbaopic'] = $val['hongbaopic'];
            }else{
               $hongbao[$key]['hongbaopic'] = $_W['attachurl'].$val['hongbaopic'];
            }        
        }
        foreach ($hongbao as $k => $v) {
            if($v['displayorder'] <= $count){
                $hongbao[$k]['status'] = 1;//已分享
            }else{
                $hongbao[$k]['status'] = 0;//未分享
            }        
        }

        $hongbao['finishpic'] = $_W['siteroot']."addons/kh_small_yx/hongbao.png";

        return $this->result(0,'操作成功',array('adv'=>$banner,'icon'=>$icon,'runpic'=>$runpic,'hongbao'=>$hongbao));
    }

    //3000,5000,10000
    public function doPageActivity(){
        global $_GPC, $_W;
        //首页轮播图
        $activity = pdo_getall('kh_small_yx_activity', array('uniacid' => $_GPC['i']), array(), '', 'displayorder asc');
        foreach ($activity as $k => $v) {
            $activity[$k]['step'] = $v['step'].'步';
            $activity[$k]['tomorrow'] = date("Y-m-d",strtotime("+1 day"));
            //$weekarray=array("日","一","二","三","四","五","六");
            //$activity[$k]['libai'] = $weekarray[date("w",strtotime(date("Y-m-d",strtotime("+1 day"))))];
        }

        return $this->result(0, '操作成功',array('activity'=>$activity));
    }

    //3000步活动昨天今天明天
    public function doPageActivitylist(){
        
        global $_GPC, $_W;       
        $activity = pdo_getall('kh_small_yx_activity', array('uniacid' => $_GPC['i']), array(), '', 'displayorder asc');
        $aid = $_GPC['aid'];      
        if($aid){
            $set = pdo_get('kh_small_yx_activity',array('uniacid'=>$_GPC['i'],'id'=>$aid));
        }else{
            $set = $activity[0];
        }                 
        
        $nextday = date('Y-m-d',strtotime("+1 day"));
        $mingtian = date('Y年m月d日',strtotime("+1 day"));
        
        $time=date('Y-m-d',time());
        $jintian = date('Y年m月d日',time());
        
        $lastday = date('Y-m-d',strtotime("-1 day"));
        $zuotian = date('Y年m月d日',strtotime("-1 day"));
        $weekarray=array("日","一","二","三","四","五","六");

        if($aid){
            $ishave = pdo_get('kh_small_yx_activitylog',array('user_id'=>$_GPC['user_id'],'aid'=>$_GPC['aid'],'time'=>$nextday));
        }else{
            $set = $activity[0];
            $ishave = pdo_get('kh_small_yx_activitylog',array('user_id'=>$_GPC['user_id'],'aid'=>$set['id'],'time'=>$nextday));
        }
        if($ishave){
            $data['tomorrow']['status'] = 1; //已报名
        }else{
            $data['tomorrow']['status'] = 0; //未报名
        }

        $data['tomorrow']['aid'] = $set['id'];
        $data['tomorrow']['renshu'] = pdo_getall('kh_small_yx_activitylog',array('uniacid'=>$_GPC['i'],'time'=>$nextday,'aid'=>$_GPC['aid']));
        $data['tomorrow']['renshu'] = count($data['tomorrow']['renshu']);
        $data['tomorrow']['jiangjin'] = $data['tomorrow']['renshu'] * $set['entryfee']; 
        $data['tomorrow']['entryfee'] = $set['entryfee'];
        $data['tomorrow']['step'] = $set['step'];
        $data['tomorrow']['kaishi'] = $set['starttime'];
        $data['tomorrow']['jieshu'] = $set['endtime'];
        $data['tomorrow']['day'] = $mingtian;
        $data['tomorrow']['week'] = $weekarray[date("w",strtotime($nextday))];
        $data['tomorrow']['nowtime'] = time();
        $data['tomorrow']['endtime'] = strtotime(date("Y-m-d",strtotime("+1 day")));

        $data['today']['success'] = pdo_getall('kh_small_yx_activitylog',array('uniacid'=>$_GPC['i'],'time'=>$time,'status !='=>0,'aid'=>$_GPC['aid']));
        $data['today']['success'] = count($data['today']['success']);
        $data['today']['fail'] = pdo_getall('kh_small_yx_activitylog',array('uniacid'=>$_GPC['i'],'time'=>$time,'status'=>0,'aid'=>$_GPC['aid']));
        $data['today']['fail'] = count($data['today']['fail']);
        $data['today']['zong'] = pdo_getall('kh_small_yx_activitylog',array('uniacid'=>$_GPC['i'],'time'=>$time,'aid'=>$_GPC['aid']));
        $data['today']['zong'] = count($data['today']['zong']);
        if($data['today']['success'] == 0){
           $data['today']['returnrate'] = -100;
        }else{
           $data['today']['returnrate'] = ($data['today']['zong']/$data['today']['success'] - 1)*100;
        }
        $data['today']['returnrate'] = round($data['today']['returnrate'],2);
        $data['today']['jiangjin'] = $data['today']['zong'] * $set['entryfee'];
        $data['today']['day'] = $jintian;
        $data['today']['week'] = $weekarray[date("w",strtotime($time))];
        $data['today']['canjia'] = pdo_getall('kh_small_yx_activitylog',array('uniacid'=>$_GPC['i'],'time'=>$time,'user_id'=>$_GPC['user_id'],'aid'=>$_GPC['aid']));
        if($data['today']['canjia']){
            $data['today']['status'] = 1; //已参加
        }else{
            $data['today']['status'] = 0; //未参加
        }

        $data['yesterday']['success'] = pdo_getall('kh_small_yx_activitylog',array('uniacid'=>$_GPC['i'],'time'=>$lastday,'status !='=>0,'aid'=>$_GPC['aid']));
        $data['yesterday']['success'] = count($data['yesterday']['success']);
        $data['yesterday']['zong'] = pdo_getall('kh_small_yx_activitylog',array('uniacid'=>$_GPC['i'],'time'=>$lastday,'aid'=>$_GPC['aid']));
        $data['yesterday']['zong'] = count($data['yesterday']['zong']);
        $data['yesterday']['jiangjin'] = $data['yesterday']['zong'] * $set['entryfee'];
        $data['yesterday']['day'] = $zuotian;
        $data['yesterday']['week'] = $weekarray[date("w",strtotime($lastday))];
        $data['yesterday']['canjia'] = pdo_getall('kh_small_yx_activitylog',array('uniacid'=>$_GPC['i'],'time'=>$lastday,'user_id'=>$_GPC['user_id'],'aid'=>$_GPC['aid']));
        if($data['yesterday']['canjia']){
            $data['yesterday']['status'] = 1; //已参加
        }else{
            $data['yesterday']['status'] = 0; //未参加
        }

        return $this->result(0, '操作成功',array('data'=>$data));
    }

    //参赛记录
    public function doPageActivitylog(){
        global $_GPC, $_W;
        $tomorrow = date('Y-m-d',strtotime("+1 day"));      
        $today=date('Y-m-d',time());      
        $yesterday = date('Y-m-d',strtotime("-1 day"));

        $log = pdo_getall('kh_small_yx_activitylog',array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id']),array(),'','timestamp desc');
        foreach ($log as $k => $v){
              $activity = pdo_get('kh_small_yx_activity',array('uniacid' => $_GPC['i'],'id'=>$v['aid']));
              $log[$k]['name'] = $activity['step'];
              $log[$k]['entryfee'] = $activity['entryfee'];
              if($v['time'] == $tomorrow){
                $log[$k]['status'] = '未开赛';
                $log[$k]['color'] = '00b4ff';
              }elseif($v['time'] == $today){
                $log[$k]['status'] = '进行中';
                $log[$k]['color'] = '00b4ff';
              }else{
                if($v['status'] == 0){
                    $log[$k]['status'] = '挑战失败';
                    $log[$k]['color'] = 'ff0000';
                }else{
                    $log[$k]['status'] = '挑战成功';
                    $log[$k]['color'] = '309e4d';
                }
              }           
          }

        return $this->result(0, '操作成功',array('log'=>$log));      
    }

    //参赛记录
    public function doPageHexiaolog(){
        
        global $_GPC, $_W;
        $state = $_GPC['state'];
        $log = pdo_getall('kh_small_yx_orders',array('uniacid'=>$_GPC['i'],'type >'=>9,'user_id'=>$_GPC['user_id'],'hexiaostatus'=>$state,'status <'=>2),array(),'','time desc');
        foreach ($log as $k => $v){
           $goods = pdo_get('kh_small_yx_goods',array('uniacid'=>$_GPC['i'],'id'=>$v['goods_id']));
           $shop = pdo_get('kh_small_yx_shop',array('uniacid'=>$_GPC['i'],'id'=>$goods['shop_id']));
           $log[$k]['shopname'] = $shop['shopname'];
           $log[$k]['logo'] = $_W['attachurl'].$shop['logo'];
           $log[$k]['sheng'] = $shop['sheng'];
           $log[$k]['shi'] = $shop['shi'];
           $log[$k]['qu'] = $shop['qu'];
           $log[$k]['address'] = $shop['address'];
           $log[$k]['goods_name'] = $goods['goods_name'];
           $log[$k]['paytime'] = date("Y-m-d H:i",$v['time']);
           $log[$k]['hexiaotime'] = date("Y-m-d:H:i",$v['hexiaotime']);
        }
        return $this->result(0, '操作成功',array('log'=>$log));      
    
    }

    public function doPageHexiao(){
        
        global $_GPC, $_W;
        $order = pdo_get('kh_small_yx_orders',array('uniacid'=>$_GPC['i'],'id'=>$_GPC['order_id']));
        $user = pdo_get('kh_small_yx_users',array('uniacid'=>$_GPC['i'],'user_id'=>$order['user_id']));
        $goods = pdo_get('kh_small_yx_goods',array('uniacid'=>$_GPC['i'],'id'=>$order['goods_id']));
        $shop =  pdo_get('kh_small_yx_shop',array('uniacid'=>$_GPC['i'],'id'=>$goods['shop_id']));

        $data['username'] = $user['nick_name'];
        $data['goodsname'] = $goods['goods_name'];
        $data['shopname'] = $shop['shopname'];
        $data['topbg'] = $_W['attachurl'].$shop['topbg'];
        $data['paytime'] = date("Y-m-d:H:i:s",$order['time']);
        if($order['hexiaostatus'] == 1){
           $data['hexiaostatus'] = '已核销';
        }else{
           $data['hexiaostatus'] = '未核销'; 
        }
        return $this->result(0, '操作成功',array('data'=>$data));          
    }

    public function doPageHexiaoshenhe(){
        
        global $_GPC, $_W;
        $order = pdo_get('kh_small_yx_orders',array('uniacid'=>$_GPC['i'],'id'=>$_GPC['order_id']));
        $user = pdo_get('kh_small_yx_users',array('uniacid'=>$_GPC['i'],'user_id'=>$order['user_id']));
        $goods = pdo_get('kh_small_yx_goods',array('uniacid'=>$_GPC['i'],'id'=>$order['goods_id']));
        $shop =  pdo_get('kh_small_yx_shop',array('uniacid'=>$_GPC['i'],'id'=>$goods['shop_id']));

        if($shop['user_id'] == $_GPC['shenheyuan_id']){
            $data = pdo_update('kh_small_yx_orders',array('hexiaostatus'=>1,'hexiaotime'=>time()), array('id' => $_GPC['order_id']));
            return $this->result(0, '核销成功',array('data'=>$data));
        }elseif($order['hexiaostatus'] == 1){
            return $this->result(1, '已核销',$order);
        }else{
            return $this->result(1, '没有核销权限',$order);
        }      
    }

    //报名
    public function doPageApply(){
        global $_GPC, $_W;
        $user = pdo_get('kh_small_yx_users',array('uniacid'=>$_GPC['i'],'user_id'=>$_GPC['user_id']));
        //记录报名
        $set = pdo_get('kh_small_yx_activity', array('uniacid' => $_GPC['i'],'id'=>$_GPC['aid']));

        if($user['money'] < $set['entryfee']){           
           return $this->result(1, '报名费不足');       
        }else{           
            $data['uniacid'] = $_GPC['i'];
            $data['user_id'] = $_GPC['user_id'];
            $data['aid'] = $_GPC['aid'];
            $data['step'] = 0;
            $data['entryfee'] = $set['entryfee'];
            $data['timestamp'] = time();
            $data['time'] = date('Y-m-d',strtotime("+1 day"));
            $data['status'] = 0;
            $result = pdo_insert('kh_small_yx_activitylog', $data, $replace = true);
            $nowmoney = $user['money'] - $set['entryfee'];

            pdo_update('kh_small_yx_users',array('money'=>$nowmoney), array('user_id' => $_GPC['user_id']));

            return $this->result(0, '报名成功',array('result'=>$result));
        }       
    }

    public function doPageFormid(){
        ob_end_clean();
        global $_GPC, $_W;
        $data['user_id']=$_GPC['user_id'];
        $data['formid']=$_GPC['formid'];
        //$aa=$this->getMessagemoney($user_id,$money);
        if(strpos($data['formid']," ")){
            $this->result(0, 'formid不正确');
        }elseif(empty($_GPC['formid']))
        {
            $this->result(0, 'formid为空');
        }else{
            pdo_insert('kh_small_yx_formid', $data);
            $cid = pdo_insertid();
            $this->result(0, '获取成功',$_GPC);
        }

    }





public function doPageTestPay() {
        global $_GPC, $_W;
        //获取订单号，保证在业务模块中唯一即可
        $orderid = intval($_GPC['orderid']);
        //构造支付参数
        $order = array(
            'tid' => $orderid,
            'user' => $_W['openid'], //用户OPENID
            'fee' => floatval($fee), //金额
            'title' => '小程序支付示例',
        );
        //生成支付参数，返回给小程序端
        $pay_params = $this->pay($order);
         var_dump($pay_params );
        if (is_error($pay_params)) {
            return $this->result(1, '支付失败，请重试');
        }
        return $this->result(0, '', $pay_params);
    }




    function wx_get_token() {
        global $_GPC, $_W;
        /*$appid=$_W['account']['key'];
        $AppSecret=$_W['account']['secret'];
        $res = file_get_contents('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$AppSecret);
        $res = json_decode($res, true);
        $token = $res['access_token'];*/
        $account_api = WeAccount::create();
        $token = $account_api->getAccessToken();
        return $token;
    }

    public function download_remote_pic($url){
        global $_W,$_GPC;
        $header = [     
            'User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:45.0) Gecko/20100101 Firefox/45.0',
            'Accept-Language: zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3',
            'Accept-Encoding: gzip, deflate'
        ];  
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);  
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_ENCODING, 'gzip');  
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $data = curl_exec($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);curl_close($curl);  
        if($code == 200){//把URL格式的图片转成base64_encode格式的！      
           $imgBase64Code = "data:image/png;base64," . base64_encode($data);  
        }  
        $img_content=$imgBase64Code;//图片内容  
        if(preg_match('/^(data:\s*image\/(\w+);base64,)/', $img_content, $result)){   
        $type = $result[2];//得到图片类型png?jpg?gif?   
        $new_file = time().rand(1,10000).".{$type}";
        if (file_put_contents(dirname(__FILE__)."/headpic/".$new_file, base64_decode(str_replace($result[1], ‘‘, $img_content)))) {  
                return $_W['siteroot']."addons/kh_small_yx/headpic/".$new_file; 
            } 
        } 
    }

    /*public function request_post($url, $data){
            $ch = curl_init();
            $header = "Accept-Charset: utf-8";
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $tmpInfo = curl_exec($ch);
            if (curl_errno($ch)) {
                return false;
            }else{
                return $tmpInfo;
            }
        }*/

    public function request_post($url, $data){
            $ch = curl_init();
            $header[] = "Content-type: text/xml"; // 改为数组解决
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            //curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $tmpInfo = curl_exec($ch);
            if (curl_errno($ch)) {
                return false;
            }else{
                return $tmpInfo;
            }
        }

}