<?php

defined('IN_IA') or exit('Access Denied');

class mzhk_sunModuleWxapp extends WeModuleWxapp {
    
    
    public function doPageOpenid(){
        global $_W, $_GPC;
        $res=pdo_get('mzhk_sun_system',array('uniacid'=>$_W['uniacid']));
        $code=$_GPC['code'];
        $appid=$res['appid'];
        $secret=$res['appsecret'];
        $url="https://api.weixin.qq.com/sns/jscode2session?appid=".$appid."&secret=".$secret."&js_code=".$code."&grant_type=authorization_code";
        function httpRequest($url,$data = null){
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
            if (!empty($data)){
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            }
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $output = curl_exec($curl);
            curl_close($curl);
            return $output;
        }
        $re=httpRequest($url);
        print_r($re);
    }

    
    public function doPageLogin(){
        global $_GPC, $_W;
        $openid=$_GPC['openid'];
        $res=pdo_get('mzhk_sun_user',array('openid'=>$openid,'uniacid'=>$_W['uniacid']));
        if($openid and $openid!='undefined'){
            if($res){
                $user_id=$res['id'];
                $data['openid']=$_GPC['openid'];
                $data['img']=$_GPC['img'];
                $data['name']=$_GPC['name'];
                $res = pdo_update('mzhk_sun_user', $data, array('id' =>$user_id));
                $user=pdo_get('mzhk_sun_user',array('openid'=>$openid,'uniacid'=>$_W['uniacid']));
            }else{
                $data['openid']=$_GPC['openid'];
                $data['img']=$_GPC['img'];
                $data['name']=$_GPC['name'];
                $data['uniacid']=$_W['uniacid'];
                $data['time']=time();
                $res2=pdo_insert('mzhk_sun_user',$data);
                $user=pdo_get('mzhk_sun_user',array('openid'=>$openid,'uniacid'=>$_W['uniacid']));
            }

            echo json_encode($user);
        }
    }


    
    public function doPageTbbanner(){
        global $_GPC, $_W;
        $data = pdo_get('mzhk_sun_tbbanner',array('uniacid'=>$_W['uniacid']));
        if($data){
            $dataarr[] = array("bname"=>$data["bname"],"lb_imgs"=>$data["lb_imgs"]);
            $dataarr[] = array("bname"=>$data["bname1"],"lb_imgs"=>$data["lb_imgs1"]);
            $dataarr[] = array("bname"=>$data["bname2"],"lb_imgs"=>$data["lb_imgs2"]);
            $dataarr[] = array("bname"=>$data["bname3"],"lb_imgs"=>$data["lb_imgs3"]);
            $dataarr[] = array("bname"=>$data["bname4"],"lb_imgs"=>$data["lb_imgs4"]);
            echo json_encode($dataarr);
        }else{
            echo 2;
        }
    }

   ###################################################################################################
    
    
    public function doPageVIP(){
        global $_GPC, $_W;
        $data = pdo_getall('mzhk_sun_vip',array('uniacid'=>$_W['uniacid'],'status'=>1));
        $openid = $_GPC["openid"];
        $res=pdo_get('mzhk_sun_user',array('openid'=>$openid,'uniacid'=>$_W['uniacid']),array("telphone","money"));
        $return_data["vip"] = $data;
        $return_data["telphone"] = $res["telphone"];
        $return_data["money"] = $res["money"];
        echo json_encode($return_data);
    }
    
    public function doPagePayVIP(){
        global $_GPC, $_W;
        $openid = $_GPC['openid'];
        if(!empty($openid)){
            $vipid = intval($_GPC['id']);
            $uniacid = $_W['uniacid'];
            $data['viptype'] = $vipid;
            
            $res = pdo_get('mzhk_sun_vip',array('id'=>$vipid,'uniacid'=>$uniacid),array("title","price","day","prefix"));
            $data['addtime'] = time();
            $addday=$res['day'];
            
            $user = pdo_get('mzhk_sun_user',array('openid'=>$openid,'uniacid'=>$uniacid),array("addtime","viptype","endtime","id"));
            if($user["endtime"]>time()){
                $data['endtime']=  strtotime("+ $addday day ",$user["endtime"]);
            }else{
                $data['endtime']=  strtotime("+ $addday day ");
            }
            
            $data['telphone'] = $_GPC['phone'];
            $datas = pdo_update('mzhk_sun_user',$data,array('openid'=>$openid,'uniacid'=>$uniacid));
            
            
            $data_code = array();
            $data_code["vipid"] = $vipid;
            $data_code["vc_starttime"] = date("Y-m-d H:i:s");
            $data_code["vc_endtime"] = date("Y-m-d H:i:s");
            $data_code["uniacid"] = $uniacid;
            $data_code["vc_code"] = $res['prefix'].time().rand(100000,999999);
            $data_code["openid"] = $openid;
            $data_code["uid"] = $user["id"];
            $data_code["vc_isuse"] = 1;
            $data_code["money"] = $res['price'];
            $data_code["viptitle"] = $res['title'];
            $data_code["vipday"] = $res['day'];
            $res_insert=pdo_insert('mzhk_sun_vipcode',$data_code);
            
            $data_log = array();
            $data_log["vipid"] = $vipid;
            $data_log["viptitle"] = $res['title'];
            $data_log["activetype"] = 1;
            $data_log["uniacid"] = $uniacid;
            $data_log["vc_code"] = $data_code["vc_code"];
            $data_log["openid"] = $openid;
            $data_log["addtime"] = time();
            $data_log["money"] = $res['price'];
            $data_log["vipday"] = $res['day'];
            $res_insert=pdo_insert('mzhk_sun_vippaylog',$data_log);

            $payType = intval($_GPC["payType"]);

        }
        echo json_encode($datas);
    }

    
    public function doPageISVIP(){
        global $_GPC, $_W;

        $openid = $_GPC['openid'];
        $sql ="select img,money,name,time,viptype,endtime from ".tablename('mzhk_sun_user')." where openid='$openid' and uniacid=".$_W['uniacid'];
        $userdata=pdo_fetch($sql);
        
        if($userdata['endtime'] < time()){
            
            
            
            $userdata['viptype']=0;
            
        }else{
            $userdata['time2']=date('Y-m-d',$userdata['endtime']);
        }

        echo json_encode($userdata);
    }


    
    public function doPageMUMVIP(){
        global $_GPC, $_W;
        $openid = $_GPC['openid'];
        $jhm = $_GPC['jhm'];
        $uniacid = $_W['uniacid'];
        if(empty($openid)){
            return $this->result(1, '参数错误，激活失败！', array());
        }
        if(empty($jhm)){
            return $this->result(1, '激活码错误，激活失败！！', array());
        }
        $vipcode = pdo_get('mzhk_sun_vipcode',array('vc_code'=>$jhm,'uniacid'=>$uniacid),array("vc_isuse","vc_starttime","vc_endtime","vipid","money","vipday"));
        if($vipcode){
            if($vipcode["vc_isuse"]==1){
                return $this->result(1, '该激活码已经被使用，激活失败！！！', array());
            }
            if(strtotime($vipcode["vc_starttime"])>time()){
                return $this->result(1, '该激活码未到使用时间，请时间到了再使用！！！', array());
            }
            if(strtotime($vipcode["vc_endtime"])<time()){
                return $this->result(1, '该激活码已到期，激活失败！！！', array());
            }
            $res = pdo_get('mzhk_sun_vip',array('id'=>$vipcode["vipid"],'uniacid'=>$uniacid),array("status","title","price","day","prefix"));
            if(!$res){
                return $this->result(1, '该激活码无法使用！', array());
            }
            if($res["status"]==2){
                return $this->result(1, '该激活码无法使用！！！', array());
            }
            $data['addtime'] = time();
            $data['viptype'] = $vipcode["vipid"];
            $addday = $vipcode["vipday"]>0?$vipcode["vipday"]:$res['day'];
            
            $user = pdo_get('mzhk_sun_user',array('openid'=>$openid,'uniacid'=>$uniacid),array("addtime","viptype","endtime","id"));
            if($user["endtime"]>time()){
                $data['endtime']=  strtotime("+ $addday day ",$user["endtime"]);
            }else{
                $data['endtime']=  strtotime("+ $addday day ");
            }
            $data['telphone'] = $_GPC['phone'];
            $u_user = pdo_update('mzhk_sun_user',$data,array('openid'=>$openid,'uniacid'=>$uniacid));
            $u_vipcode = pdo_update('mzhk_sun_vipcode',array("vc_isuse"=>1,"openid"=>$openid,"uid"=>$user["id"]),array('vc_code'=>$jhm,'uniacid'=>$uniacid));
            
            $data_log = array();
            $data_log["vipid"] = $vipcode["vipid"];
            $data_log["viptitle"] = $res['title'];
            $data_log["activetype"] = 0;
            $data_log["uniacid"] = $uniacid;
            $data_log["vc_code"] = $jhm;
            $data_log["openid"] = $openid;
            $data_log["addtime"] = time();
            $data_log["money"] = $vipcode['money']>0?$vipcode['money']:$res['price'];
            $data_log["vipday"] = $addday;
            $res_insert=pdo_insert('mzhk_sun_vippaylog',$data_log);
            echo json_encode($u_user);
        }else{
            return $this->result(1, '激活码错误，激活失败！！！', array());
        }
    }
    ###################################################################################################
    
    
    public function doPageBanner(){
        global $_GPC, $_W;
        $data =pdo_get('mzhk_sun_banner',array('uniacid'=>$_W['uniacid']));

        $url = explode("$$$",$data['url']);
        $data['pop_urltype'] = $url[0]?$url[0]:'';
        $data['pop_urltxt'] = $url[1]?$url[1]:'';

        $url1 = explode("$$$",$data['url1']);
        $data['pop_urltype1'] = $url1[0]?$url1[0]:'';
        $data['pop_urltxt1'] = $url1[1]?$url1[1]:'';

        $url2 = explode("$$$",$data['url2']);
        $data['pop_urltype2'] = $url2[0]?$url2[0]:'';
        $data['pop_urltxt2'] = $url2[1]?$url2[1]:'';

        $url3 = explode("$$$",$data['url3']);
        $data['pop_urltype3'] = $url3[0]?$url3[0]:'';
        $data['pop_urltxt3'] = $url3[1]?$url3[1]:'';

        echo json_encode($data);
    }

    
    public function doPageActivity(){
        global $_GPC, $_W;
        $new = date('Y-m-d H:i:s',time());
        $time = time();
        $sql = "select g.lid,g.gname,g.num,g.bid,g.bname,g.pic,g.gid,g.astime,g.is_kjopen,g.is_ptopen,g.is_qgopen,g.is_jkopen,g.index_img,g.is_vip,g.kjprice,g.shopprice,g.ptprice,g.qgprice from ".tablename('mzhk_sun_goods')." as g left join ".tablename('mzhk_sun_brand')." as b on g.bid=b.bid where g.tid = 1 and g.status = 2 and g.uniacid=".$_W['uniacid']." and (g.lid=1 or (g.lid=2 and g.is_kjopen=1) or (g.lid=3 and g.is_ptopen=1) or (g.lid=4 and g.is_jkopen=1) or (g.lid=5 and g.is_qgopen=1) or (g.lid=6 and g.is_hyopen=1)) and g.isshelf=1 and g.num>0 and g.antime>='".$new."' and b.status=2 order by g.sort asc,g.gid desc ";
        $data = pdo_fetchall($sql);
        $activeList_two = array();
        if($data){
            foreach ($data as $k=>$v){
                if($v['lid'] == 1){
                    $data[$k]['type']= '普通';
                    $data[$k]['bindtap']= 'putongbon';
                    $data[$k]['price']= $v["shopprice"];
                    $activeList_two["general"][] = $v;
                }elseif($v['lid'] == 2){
                    $data[$k]['type']= '砍价';
                    $data[$k]['bindtap']= 'kjbon';
                    $data[$k]['price']= $v["kjprice"];
                    $activeList_two["bargain"][] = $v;
                }elseif($v['lid'] == 3){
                    $data[$k]['type']= '拼团';
                    $data[$k]['bindtap']= 'ptbon';
                    $data[$k]['price']= $v["ptprice"];
                    $activeList_two["group"][] = $v;
                }elseif($v['lid'] == 4){
                    $data[$k]['type']= '集卡';
                    $data[$k]['bindtap']= 'jkbon';
                    $data[$k]['price']= 0;
                    $activeList_two["card"][] = $v;
                }elseif($v['lid'] == 5){
                    $data[$k]['type']= '抢购';
                    $data[$k]['bindtap']= 'qgbon';
                    $data[$k]['price']= $v["qgprice"];
                    $activeList_two["buying"][] = $v;
                }elseif($v['lid'] == 6){
                    $data[$k]['type']= '免单';
                    $data[$k]['bindtap']= 'mdbon';
                    $data[$k]['price']= 0;
                    $activeList_two["free"][] = $v;
                }
                $re=strtotime($v['astime']);
                $data[$k]['selftime']=date('Y-m-d',$re);
            }
        }
        $result_data["activeList"] = $data;
        $result_data["activeList_two"] = $activeList_two;

        echo json_encode($result_data);
    }
    
    public function doPageFree(){
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $new = date("Y-m-d H:i:s");

        $pagesize = 3;
        $pageindex = intval($_GPC['page'])*$pagesize;

        
        $sql = 'select b.bname,c.title,c.astime,c.img,c.antime,c.allowance,c.id from '.tablename('mzhk_sun_coupon').' as c LEFT JOIN '.tablename('mzhk_sun_brand').' as b on b.bid = c.bid where c.showIndex = 1 and c.state = 1 and c.uniacid='.$uniacid.' and c.antime>='."'$new'".' and c.astime<='."'$new' and c.allowance>0 AND c.is_counp=1 AND c.isvip=1 limit ".$pageindex.",".$pagesize;
        
        $data = pdo_fetchall($sql);
        if($data){
            foreach ($data as $k=>$v){
                $data[$k]['type']= '会员专享';
                $data[$k]['astime']=date('Y-m-d',strtotime($v["astime"]));
                $data[$k]['antime']=date('Y-m-d',strtotime($v["antime"]));
            }
            echo json_encode($data);
        }else{
            echo 2;
        }

    }
    ###################################################################################################
    

    public function doPageActives(){
        global $_GPC, $_W;
        $new = date('Y-m-d H:i:s',time());
        
        $pagesize = 3;
        $pageindex = intval($_GPC['page'])*$pagesize;

        $gname = $_GPC["gname"];
        $where = '';
        if(!empty($gname)){
            $pagesize = 30;
            $where = " and g.gname like'%".$gname."%' ";
        }

        
        $sql = "select g.lid,g.gname,g.num,g.bid,g.bname,g.pic,g.gid,g.lid,g.astime,g.is_kjopen,g.is_ptopen,g.is_qgopen,g.is_jkopen,g.index_img,g.is_vip,g.kjprice,g.shopprice,g.ptprice,g.qgprice,g.selftime from ".tablename('mzhk_sun_goods')." as g left join ".tablename('mzhk_sun_brand')." as b on g.bid=b.bid where g.status = 2 and g.uniacid=".$_W['uniacid']." and ((g.lid=2 and g.is_kjopen=1) or (g.lid=3 and g.is_ptopen=1) or (g.lid=4 and g.is_jkopen=1) or (g.lid=5 and g.is_qgopen=1) or (g.lid=6 and g.is_hyopen=1)) and g.isshelf=1 and g.antime>='".$new."' and b.status=2 ".$where." order by g.sort asc,g.gid desc limit ".$pageindex.",".$pagesize;
        $data = pdo_fetchall($sql);

        if(is_array($data) && sizeof($data)>0){
            foreach ($data as $k=>$v){
                if($v['lid'] == 1){
                    $data[$k]['type']= '普通';
                    $data[$k]['price']= $v["shopprice"];
                    $data[$k]['bindtap']= 'putongbon';
                }elseif($v['lid'] == 2){
                    $data[$k]['type']= '砍价活动';
                    $data[$k]['bindtap']= 'kjbon';
                    $data[$k]['price']= $v["kjprice"];
                }elseif($v['lid'] == 3){
                    $data[$k]['type']= '拼团活动';
                    $data[$k]['bindtap']= 'ptbon';
                    $data[$k]['price']= $v["ptprice"];
                }elseif($v['lid'] == 4){
                    $data[$k]['type']= '集卡活动';
                    $data[$k]['bindtap']= 'jkbon';
                    $data[$k]['price']= 0;
                }elseif($v['lid'] == 5){
                    $data[$k]['type']= '抢购活动';
                    $data[$k]['bindtap']= 'qgbon';
                    $data[$k]['price']= $v["qgprice"];
                }elseif($v['lid'] == 6){
                    $data[$k]['type']= '免单活动';
                    $data[$k]['bindtap']= 'mdbon';
                    $data[$k]['price']= 0;
                }
                $data[$k]['selftime']=date('Y-m-d',strtotime($v['selftime']));
            }
            echo json_encode($data);
        }else{
            echo 2;
        }
        
    }
    ###################################################################################################
    

    public function doPageOldActives(){
        global $_GPC, $_W;
        $new = date('Y-m-d H:i:s',time());

        
        $pagesize = 3;
        $pageindex = intval($_GPC['page'])*$pagesize;
        $sql = 'select bid,pic,gname,num,selftime,gid,lid,bname,index_img from'.tablename('mzhk_sun_goods').' where tid = 1 and status = 2 and lid <> 1 and uniacid='.$_W['uniacid'].' and antime <'."'$new' limit ".$pageindex.",".$pagesize;
        $data = pdo_fetchall($sql);
        if(is_array($data) && sizeof($data)>0){
            foreach ($data as $k=>$v){
                if($v['lid'] == 1){
                    $data[$k]['type']= '普通';
                }elseif($v['lid'] == 2){
                    $data[$k]['type']= '砍价活动';
                }elseif($v['lid'] == 3){
                    $data[$k]['type']= '拼团活动';
                }elseif($v['lid'] == 4){
                    $data[$k]['type']= '集卡活动';
                }elseif($v['lid'] == 5){
                    $data[$k]['type']= '抢购活动';
                }elseif($v['lid'] == 6){
                    $data[$k]['type']= '免单活动';
                }
                $data[$k]['selftime']=date('Y-m-d',strtotime($v['selftime']));
            }
            echo json_encode($data);
        }else{
            echo 2;
        }
    }
    ###################################################################################################
    

    
    public function doPagePTactive(){
        global $_GPC, $_W;

        $uniacid = $_W['uniacid'];
        $new = date("Y-m-d H:i:s");

        $pagesize = 6;
        $pageindex = intval($_GPC['page'])*$pagesize;

        $sql = "select g.* from ".tablename('mzhk_sun_goods')." as g left join ".tablename('mzhk_sun_brand')." as b on g.bid=b.bid where g.lid = 3 and g.status = 2 and g.uniacid=".$uniacid." and g.antime>='".$new."' and g.is_ptopen=1 and g.isshelf=1 and b.status=2 order by g.sort asc,g.gid desc limit ".$pageindex.",".$pagesize;
        
        $data = pdo_fetchall($sql);
        if($data){
            foreach ($data as $k=>$v){
                $data[$k]['selftime']=date('Y-m-d',strtotime($v["selftime"]));
                $data[$k]['astime']=date('Y-m-d',strtotime($v["astime"]));
                $data[$k]['antime']=date('Y-m-d',strtotime($v["antime"]));
                $data[$k]["code_img"] = "";
            }
            echo json_encode($data);
        }else{
            echo 2;
        }
    }
    
    public function doPagePTClose(){
        global $_GPC, $_W;

        $uniacid = $_W['uniacid'];
        $new = date("Y-m-d H:i:s");

        $pagesize = 6;
        $pageindex = intval($_GPC['page'])*$pagesize;

        
        $sql = "select * from ".tablename('mzhk_sun_goods')." where lid = 3 and status = 2 and uniacid=".$uniacid." and (antime<='".$new."' or num=0 or isshelf=0) order by gid desc limit ".$pageindex.",".$pagesize;
        
        $data = pdo_fetchall($sql);
        if($data){
            foreach ($data as $k=>$v){
                $data[$k]['selftime']=date('Y-m-d',strtotime($v["selftime"]));
                $data[$k]['astime']=date('Y-m-d',strtotime($v["astime"]));
                $data[$k]['antime']=date('Y-m-d',strtotime($v["antime"]));
                $data[$k]["code_img"] = "";
            }
            echo json_encode($data);
        }else{
            echo 2;
        }

    }
    
    public function doPagePTdetails(){
        global $_GPC, $_W;
        $data = pdo_get('mzhk_sun_goods',array('uniacid'=>$_W['uniacid'],'gid'=>$_GPC['id']));
        if($data['is_ptopen']==1){
            
        }else{
            return $this->result(1,'拼团活动已经关闭', array());
        }
        $data['lb_imgs'] = explode(',',$data['lb_imgs']);
        $data['enftime']=strtotime($data['antime'])*1000;
        $data['clock']="";
        $data["code_img"] = "";
        
        $brand = pdo_get('mzhk_sun_brand',array('uniacid'=>$_W['uniacid'],'bid'=>$data['bid']),array("longitude","latitude","address","phone"));
        $data['longitude'] = $brand["longitude"];
        $data['latitude'] = $brand["latitude"];
        $data['address'] = $brand["address"];
        $data['phone'] = $brand["phone"];
        echo json_encode($data);
    }
     
    public function doPagePTing(){
        global $_GPC, $_W;
        $data = pdo_get('mzhk_sun_goods',array('uniacid'=>$_W['uniacid'],'gid'=>$_GPC['id']));
        $sql="select  a.gid,a.mch_id,a.id,b.img from".tablename('mzhk_sun_groups').'a left join '.tablename('mzhk_sun_user').'b on a.openid=b.openid  where  a.mch_id=0  and a.status=2 and a.gid= '.$_GPC['id'].' and a.buynum < '.$data['ptnum'];
        $res = pdo_fetchall($sql);

        echo json_encode($res);
    }
    
    
    public function doPagePTorder(){
        global $_GPC, $_W;
        $gid = intval($_GPC['id']);
        $sql = "select b.img,a.bname,b.phone,a.pic,a.gname,a.ship_type,a.ship_delivery_fee,a.ship_delivery_time,a.ship_delivery_way,a.ship_express_fee,b.address,a.gid,b.deliveryfee,b.deliverytime,b.deliveryaway,a.ptprice,a.shopprice from ".tablename('mzhk_sun_goods').' as a left join'.tablename('mzhk_sun_brand').' as b on b.bid=a.bid where a.uniacid='.$_W['uniacid'].' and a.gid='.$gid;
        $data = pdo_fetch($sql);
        $data['ship_type'] = $data["ship_type"]?explode(",",$data["ship_type"]):array(1);

        
        $sql = "select telNumber from".tablename('mzhk_sun_kjorder').' where uniacid='.$_W['uniacid']." and openid='".$_GPC['openid']."' and telNumber is not null order by addtime desc ";
        $order = pdo_fetch($sql);
        $data['telnumber']="";
        if($order){
            $data['telnumber'] = $order["telNumber"];
        }
        if(empty($data['telnumber'])){
            $sql = "select telnumber from".tablename('mzhk_sun_cardorder').' where uniacid='.$_W['uniacid']." and openid='".$_GPC['openid']."' and telnumber is not null order by addtime desc ";
            $order = pdo_fetch($sql);
            if($order){
                $data['telnumber'] = $order["telnumber"];
            }
            if(empty($data['telnumber'])){
                $sql = "select telNumber from".tablename('mzhk_sun_qgorder').' where uniacid='.$_W['uniacid']." and openid='".$_GPC['openid']."' and telNumber is not null order by addtime desc ";
                $order = pdo_fetch($sql);
                if($order){
                    $data['telnumber'] = $order["telNumber"];
                }
                if(empty($data['telnumber'])){
                    $sql = "select telnumber from".tablename('mzhk_sun_ptgroups').' where uniacid='.$_W['uniacid']." and openid='".$_GPC['openid']."' and telnumber is not null order by addtime desc ";
                    $order = pdo_fetch($sql);
                    if($order){
                        $data['telnumber'] = $order["telnumber"];
                    }
                    if(empty($data['telnumber'])){
                        $sql = "select telNumber from".tablename('mzhk_sun_hyorder').' where uniacid='.$_W['uniacid']." and openid='".$_GPC['openid']."' and telNumber is not null order by addtime desc ";
                        $order = pdo_fetch($sql);
                        if($order){
                            $data['telnumber'] = $order["telNumber"];
                        }
                    }
                    
                }
            }
        }
        
        echo json_encode($data);
    }

    
    public function doPageCheckGroupOrder(){
        global $_GPC, $_W;
        $order_id = intval($_GPC["order_id"]);
        
        $uniacid = $_W['uniacid'];
        $emptydata = array();
        $res=pdo_get('mzhk_sun_ptorders',array('id'=>$order_id,'uniacid'=>$uniacid),array("is_ok","buynum","neednum"));
        
        $emptydata = array();
        if($res["is_ok"]==1 || $res["buynum"]>=$res["neednum"]){
            return $this->result(1, '该团已满，请重新开团！！！', $emptydata);
        }else{
            return $this->result(0, '', $res);
        }
    }

    
    public function doPageCheckGoodsStatus(){
        global $_GPC, $_W;
        $gid = intval($_GPC["gid"]);
        $uniacid = $_W['uniacid'];
        $res=pdo_get('mzhk_sun_goods',array('gid'=>$gid,'uniacid'=>$uniacid),array("gid","gname","num","astime","antime","limitnum","isshelf","islottery","kjprice","mustlowprice","bid"));
        
        $emptydata = array();
        $ltype = intval($_GPC['ltype']);
        $openid = $_GPC['openid'];
        $brand=pdo_get('mzhk_sun_brand',array('bid'=>$res["bid"],'uniacid'=>$uniacid),array("bid","status"));
        if($brand["status"]!=2){
            return $this->result(1, '该活动已经关闭，无法下单！', $emptydata);
        }
        
        if($res["num"]<=0){
            return $this->result(1, '商品已售罄，无法下单！', $emptydata);
        }elseif(strtotime($res["astime"])>time()){
            return $this->result(1, '活动还未开始！！！', $emptydata);
        }elseif(strtotime($res["antime"])<time()){
            return $this->result(1, '该活动已结束！！！', $emptydata);
        }elseif($res["isshelf"]!=1){
            return $this->result(1, '活动时间已结束！！！', $emptydata);
        }elseif($res["limitnum"] > 0){
            
            if($ltype==1){
                $total=pdo_fetchcolumn("select count(id) as num from ".tablename('mzhk_sun_ptgroups')." where addtime between ".strtotime($res["astime"])." and ".strtotime($res["antime"])." and status > 1 and uniacid='".$uniacid."' and gid='".$gid."' AND openid='".$openid."' ");
            }elseif($ltype==2){
                $total=pdo_fetchcolumn("select count(oid) as num from ".tablename('mzhk_sun_kjorder')." where addtime between ".strtotime($res["astime"])." and ".strtotime($res["antime"])." and status > 1 and uniacid='".$uniacid."' and gid='".$gid."' AND openid='".$openid."' ");
            }elseif($ltype==3){
                $total=pdo_fetchcolumn("select count(id) as num from ".tablename('mzhk_sun_cardorder')." where addtime between ".strtotime($res["astime"])." and ".strtotime($res["antime"])." and status >= 0 and uniacid='".$uniacid."' and gid='".$gid."' AND openid='".$openid."' ");
            }elseif($ltype==4){
                
                $total_arr=pdo_fetch("select sum(num) as num from ".tablename('mzhk_sun_order')." where addtime between ".strtotime($res["astime"])." and ".strtotime($res["antime"])." and status > 1 and uniacid='".$uniacid."' and gid='".$gid."' AND openid='".$openid."' ");
                if($total_arr){
                    $total = $total_arr["num"];
                }else{
                    $total = 0;
                }
            }else{
                
                $total_arr=pdo_fetch("select sum(num) as num from ".tablename('mzhk_sun_qgorder')." where addtime between ".strtotime($res["astime"])." and ".strtotime($res["antime"])." and status > 1 and uniacid='".$uniacid."' and gid='".$gid."' AND openid='".$openid."' ");
                if($total_arr){
                    $total = $total_arr["num"];
                }else{
                    $total = 0;
                }
            }
            if($total>=$res["limitnum"]){
                return $this->result(1, '超过限购次数，该商品每人限购'.$res["limitnum"].'件！！！', $emptydata);
            }
        }

        if($ltype==2){
            $isbuy = intval($_GPC["isbuy"]);
            
            if($res["mustlowprice"]==1 && $isbuy==1){
                $sql="select nowprice,lowprice from ".tablename('mzhk_sun_cutself')." where uniacid='".$uniacid."' and gid ='".$gid."' and openid = '".$openid."' and is_buy=0 order by id desc ";
                $data_cutself = pdo_fetch($sql);
                if($res["kjprice"]!=$data_cutself["nowprice"]){
                    return $this->result(1, '该商品必须砍到底价才能购买', $emptydata);
                }
            }
        }elseif($ltype==6){
            if($res["islottery"]==1){
                return $this->result(1, '该商品已经开奖了，无法继续参与', $emptydata);
            }
            $total=pdo_fetchcolumn("select count(oid) as num from ".tablename('mzhk_sun_hyorder')." where gid='".$gid."' and uniacid='".$uniacid."' AND openid='".$openid."' ");
            if($total>=1){
                return $this->result(1, '你已经申请过这个免单了！！！', $emptydata);
            }
        }

        return $this->result(0, '', $res);
        
    }

    
    public function doPageAddptOrder(){
        global $_GPC, $_W;
        $gid=intval($_GPC['id']);
        $uniacid = $_W['uniacid'];
        $openid = $_GPC['openid'];
        $price = $_GPC['price'];
        $come_order_id=intval($_GPC['come_order_id']);
        $buytype=intval($_GPC['buytype']);
        $emptydata = array();
        $bigorderdata = $g_orderdata = array();

        $res=pdo_get('mzhk_sun_goods',array('gid'=>$gid,'uniacid'=>$uniacid),array("gid","gname","num","astime","antime","ptnum","pic","bid","bname","limittime","isshelf","stocktype","expirationtime"));
        
        if($res["num"]<=0){
            return $this->result(1, '商品已售罄，无法下单！！！', $emptydata);
        }elseif(strtotime($res["astime"])>time()){
            return $this->result(1, '活动还未开始！！！', $emptydata);
        }elseif(strtotime($res["antime"])<time()){
            return $this->result(1, '该商品已结束拼团！！！', $emptydata);
        }elseif($res["isshelf"]!=1){
            return $this->result(1, '该拼团活动已关闭！！！', $emptydata);
        }

        if($come_order_id > 0){
            
            $g_order=pdo_get('mzhk_sun_ptgroups',array('order_id'=>$come_order_id,'openid'=>$openid,'uniacid'=>$uniacid),array("id","order_id","status"));
            if($g_order){
                
                if($g_order["status"]>2){
                    return $this->result(1, '你已经在团中，请勿重复参团！！！', $emptydata);
                }
                
                echo json_encode(array("order_id"=>$come_order_id,"g_order_id"=>$g_order["id"]));
                exit;
            }
            
            $order=pdo_get('mzhk_sun_ptorders',array('id'=>$come_order_id,'uniacid'=>$uniacid));
            
            if($order["is_ok"]==1 || $order["buynum"]>=$order["neednum"] || $order["peoplenum"]>=$order["neednum"]){
                return $this->result(1, '该团已满，请重新开团！！！', $emptydata);
            }
            $order_id = $come_order_id;

            
            pdo_update('mzhk_sun_ptorders',array("peoplenum +="=>1),array('id'=>$come_order_id,'uniacid'=>$uniacid));

            
            if($res["limittime"]>0){
                $g_orderdata['endtime']=  strtotime("+ ".$res["limittime"]." hours ");
            }else{
                $g_orderdata["endtime"] = strtotime($res["antime"]);
            }

        }else{
            
            
            $ordernum = time() . mt_rand(100000, 999999);
            $bigorderdata["gid"] = $gid;
            $bigorderdata["gname"] = $res["gname"];
            $bigorderdata["openid"] = $openid;
            $bigorderdata["addtime"] = time();
            $bigorderdata["uniacid"] = $uniacid;
            $bigorderdata["money"] = $price;
            $bigorderdata["peoplenum"] = 1;
            
            if($buytype==1){
                $bigorderdata["neednum"] = 1;
            }else{
                $bigorderdata["neednum"] = $res["ptnum"];
            }

            $bigorderdata["ordernum"] = $ordernum;
            $bigorderdata["endtime"] = strtotime($res["antime"]);
            $res_bigorder=pdo_insert('mzhk_sun_ptorders',$bigorderdata);
            $order_id=pdo_insertid();
            if(intval($order_id)<=0){
                return $this->result(1,'数据提交失败，请重新提交', array());
            }
            
            $g_orderdata["is_lead"] = 1;

            
            $g_orderdata["endtime"] = strtotime($res["antime"]);
        }

        
        $g_orderdata["detailinfo"] = $_GPC["detailInfo"];
        $g_orderdata["telnumber"] = $_GPC["telNumber"];
        $g_orderdata["money"] = $price;
        $g_orderdata["openid"] = $openid;
        $g_orderdata["uniacid"] = $uniacid;
        $g_orderdata["countyname"] = $_GPC["countyName"];
        $g_orderdata["provincename"] = $_GPC["provinceName"];
        $g_orderdata["name"] = $_GPC["name"];
        $g_orderdata["cityname"] = $_GPC["cityName"];
        $g_orderdata["uremark"] = $_GPC["uremark"];
        $g_orderdata["sincetype"] = $_GPC["sincetype"];
        $g_orderdata["deliveryfee"] = $_GPC["deliveryfee"];
        $g_orderdata["gid"] = $gid;
        $g_orderdata["gname"] = $res["gname"];
        $g_orderdata['bid']=$res["bid"];
        $g_orderdata['bname']=$res["bname"];
        $g_orderdata['goodsimg']=$res["pic"];
        $g_orderdata['expirationtime']=$res["expirationtime"];

        $g_orderdata["order_id"] = $order_id;
        $g_orderdata["sincetype"] = $_GPC["sincetype"];
        $g_orderdata["groupordernum"] = time() . mt_rand(10000, 99999);
        $g_orderdata["addtime"] = time();
        $g_orderdata["status"] = 2;
        $res_g_order=pdo_insert('mzhk_sun_ptgroups',$g_orderdata);
        $g_order_id = pdo_insertid();
        if(intval($g_order_id)<=0){
            return $this->result(1,'数据提交失败，请重新提交,0002', array());
        }
        
        if($res["stocktype"]==0){
            pdo_update('mzhk_sun_goods',array("num -="=>1,"buynum +="=>1),array('gid'=>$gid,'uniacid'=>$uniacid));
        }else{
            pdo_update('mzhk_sun_goods',array("buynum +="=>1),array('gid'=>$gid,'uniacid'=>$uniacid));
        }


        echo json_encode(array("order_id"=>$order_id,"g_order_id"=>$g_order_id));

    }


    public function doPagePayptOrder(){
        global $_W, $_GPC;
        $emptydata = array();
        $openid = $_GPC["openid"];
        $uniacid = $_W['uniacid'];
        $order_id = intval($_GPC["order_id"]);
        $g_order_id = intval($_GPC["g_order_id"]);
        
        $orderinfo = pdo_get('mzhk_sun_ptorders',array('id'=>$order_id,'uniacid'=>$uniacid),array("gid","gname","buynum","neednum","is_ok","id"));
        
        $g_orderinfo = pdo_get('mzhk_sun_ptgroups',array('id'=>$g_order_id,'uniacid'=>$uniacid),array("status","order_id"));

        if($g_orderinfo["status"]==2 && $orderinfo["id"]==$g_orderinfo["order_id"]){
            
            
            
            
            
            
            $data = $datas = array();
            $data["buynum"] = $orderinfo["buynum"]+1;
            if($data["buynum"] == $orderinfo["neednum"]){
                $data["is_ok"] = 1;
                
                $datas['status'] = 4;
                $datas['paytime'] = time();
                $set_status = pdo_update('mzhk_sun_ptgroups',$datas, array('id' => $g_order_id, 'uniacid' => $uniacid));
                $set_status_all = pdo_update('mzhk_sun_ptgroups',array("status"=>4), array('order_id' => $order_id,'status' => 3, 'uniacid' => $uniacid));

                
                $res=pdo_get('mzhk_sun_ptgroups',array('id'=>$g_order_id,'uniacid'=>$uniacid),array("bid","groupordernum","id","gid"));
                if($res["bid"]>0){
                    $this->SendSms($res["bid"],0,$res["groupordernum"]);
                }
            }else{
                
                $datas['status'] = 3;
                $datas['paytime'] = time();
                $set_status = pdo_update('mzhk_sun_ptgroups',$datas, array('id' => $g_order_id, 'uniacid' => $uniacid));

                
                $res=pdo_get('mzhk_sun_ptgroups',array('id'=>$g_order_id,'uniacid'=>$uniacid),array("bid","groupordernum","id","gid"));
                if($res["bid"]>0){
                    $this->SendSms($res["bid"],0,$res["groupordernum"]);
                }
            }
            $set_order = pdo_update('mzhk_sun_ptorders',$data, array('id' => $order_id, 'uniacid' => $uniacid));
            if($set_status){
                $goods=pdo_get('mzhk_sun_goods',array('gid'=>$res['gid'],'uniacid'=>$uniacid),array("num","stocktype"));
                if($goods["stocktype"]==1){
                    pdo_update('mzhk_sun_goods',array("num -="=>1),array('gid'=>$res['gid'],'uniacid'=>$uniacid));
                }
            }
            echo "success";
        }else{
            echo "error";
        }
    }

    
    public function doPagedanpin(){
        global $_W, $_GPC;
        
        $openid=$_GPC['openid'];
        $gid = intval($_GPC['id']);
        $sql = "SELECT p.id,p.ordernum,p.buynum,p.neednum,p.gid,u.img,u.name,p.peoplenum FROM ". tablename('mzhk_sun_ptorders') ." as p left JOIN ".tablename('mzhk_sun_user')." as u on p.openid=u.openid where p.gid='".$gid."' AND p.is_ok = 0 AND p.buynum > 0 AND p.peoplenum<p.neednum order by p.id asc limit 5";
        $data= pdo_fetchall($sql);
        echo  json_encode($data);

    }


    public function doPageGroupsDetails(){
        global $_W, $_GPC;
        
        $openid=$_GPC['openid'];
        $uniacid = $_W['uniacid'];
        $id = intval($_GPC['id']);
        $gid = intval($_GPC['gid']);
        $data = array();
        
        $g_sql = "SELECT gname,shopprice,ptprice,ptnum,gid,probably,content,pic,biaoti FROM ".tablename('mzhk_sun_goods')." WHERE gid = '".$gid."' AND uniacid='".$uniacid."'";
        $data["goodsinfo"] = pdo_fetch($g_sql);
        
        $o_sql = "SELECT openid,is_ok,buynum,neednum,id,peoplenum FROM ".tablename('mzhk_sun_ptorders')." WHERE id = '".$id."' AND uniacid='".$uniacid."'";
        $data["orderinfo"] = pdo_fetch($o_sql);
        
        $now = time();
        $og_sql = "SELECT u.img,u.name,g.is_lead,g.openid FROM ".tablename('mzhk_sun_ptgroups')." as g LEFT JOIN ".tablename('mzhk_sun_user')." as u on g.openid=u.openid WHERE g.order_id = '".$id."' AND (g.status=3 or (g.status=2 and g.endtime >= ".$now.")) AND g.uniacid='".$uniacid."' order by g.id asc";
        $data["grouplist"] = pdo_fetchall($og_sql);
        echo  json_encode($data);
    }

    
    public function doPagegetGroupOrder(){
        global $_GPC, $_W;
        $orderstatus = intval($_GPC["orderstatus"]);
        $openid = $_GPC["openid"];

        $pagesize = 5;
        $pageindex = intval($_GPC['page'])*$pagesize;

        $where = " where o.uniacid='".$_W['uniacid']."' AND o.openid='".$openid."' ";
        if($orderstatus==4){
            $where .= " and o.status=:status and (o.isrefund=0 or o.isrefund=3) ";
            $fetchdata[":status"] = $orderstatus;
        }elseif($orderstatus==5){
            $where .= " and (o.status=:status or o.isrefund>0) ";
            $fetchdata[":status"] = $orderstatus;
        }elseif($orderstatus==0){
            
        }else{
            $where .= " and o.status=:status ";
            $fetchdata[":status"] = $orderstatus;
        }
        $sql = 'select o.status,o.num,o.isrefund,o.is_lead,o.money,o.id,o.gname as order_gname,o.goodsimg as order_pic,o.bname as order_bname,o.order_id,g.gname,g.pic,b.bname,o.gid from '.tablename('mzhk_sun_ptgroups').' as o left join '.tablename('mzhk_sun_goods').' as g on o.gid=g.gid left join '.tablename('mzhk_sun_brand').' as b on g.bid=b.bid '.$where." order by o.id desc limit ".$pageindex.",".$pagesize;
        
        $data = pdo_fetchall($sql,$fetchdata);
        if($data){
            echo json_encode($data);
        }else{
            echo 2;
        }

    }

    ###################################################################################################
    
    
    public function doPageQGactive(){
        global $_GPC, $_W;
        $showtype = intval($_GPC["showtype"]);
        $showtype = $showtype==0?5:$showtype;
        $uniacid = $_W['uniacid'];
        $new = date("Y-m-d H:i:s");
        $time = time();

        $pagesize = 5;
        $pageindex = intval($_GPC['page'])*$pagesize;

        $where = " where g.lid = ".$showtype." and g.status = 2 and g.uniacid='".$uniacid."' and g.isshelf=1 ";
        if($showtype==5){
            $where .= " and g.is_qgopen=1 and g.antime>='".$new."' ";
        }elseif($showtype==6){
            $where .= " and g.is_hyopen=1 ";
        }

        $sql = "select g.* from ".tablename('mzhk_sun_goods')." as g left join ".tablename('mzhk_sun_brand')." as b on g.bid=b.bid ".$where." and b.status=2 order by g.islottery asc,g.sort asc,g.gid desc limit ".$pageindex.",".$pagesize;
        
        $data = pdo_fetchall($sql);
        if($data){
            foreach ($data as $k=>$v){
                $data[$k]['selftime']=date('Y-m-d',strtotime($v["selftime"]));
                $data[$k]['astime']=date('Y-m-d',strtotime($v["astime"]));
                $data[$k]['antime']=date('Y-m-d',strtotime($v["antime"]));
                $data[$k]["code_img"] = "";
                
                if($showtype==6){
                    $data[$k]['lotterytime']=date('Y-m-d H:i:s',$v["lotterytime"]);
                    if((strtotime($v["antime"]) < $time && $v["lotterytime"] < $time) || $v["islottery"]==1){
                        $data[$k]['isOver'] = true;
                    }elseif(strtotime($v["antime"]) < $time && $v["lotterytime"] > $time){
                        $data[$k]['istobeawarded'] = true;
                    }
                }
            }
            echo json_encode($data);
        }else{
            echo 2;
        }
    }

    
    public function doPageQGClose(){
        global $_GPC, $_W;

        $uniacid = $_W['uniacid'];
        $new = date("Y-m-d H:i:s");

        $pagesize = 5;
        $pageindex = intval($_GPC['page'])*$pagesize;

        
        $sql = "select * from ".tablename('mzhk_sun_goods')." where lid = 5 and status = 2 and uniacid=".$uniacid." and (antime<='".$new."' or isshelf=0) order by gid desc limit ".$pageindex.",".$pagesize;
        
        $data = pdo_fetchall($sql);
        if($data){
            foreach ($data as $k=>$v){
                $data[$k]['selftime']=date('Y-m-d',strtotime($v["selftime"]));
                $data[$k]['astime']=date('Y-m-d',strtotime($v["astime"]));
                $data[$k]['antime']=date('Y-m-d',strtotime($v["antime"]));
                $data[$k]["code_img"] = "";
            }
            echo json_encode($data);
        }else{
            echo 2;
        }
    }

    
    public function doPageQGdetails(){
        global $_GPC, $_W;
        $gid = intval($_GPC['id']);
        $data = pdo_get('mzhk_sun_goods',array('uniacid'=>$_W['uniacid'],'gid'=>$gid));
        
        
        
                
        
        
        
        
        
        
        $showtype = intval($_GPC["showtype"]);
        
        if($showtype==6){
            
            if($data["islottery"]==1){
                $data['isOver'] = 1;
                
                $winorder = pdo_getall('mzhk_sun_hyorder',array('uniacid'=>$_W['uniacid'],'gid'=>$gid,'islottery'=>1));
                $sql = "select u.img,u.name from ".tablename('mzhk_sun_hyorder')." as h left join ".tablename('mzhk_sun_user')." as u on h.openid=u.openid where h.uniacid = ".$_W['uniacid']." and h.gid = ".$gid." and h.islottery=1 ";
                $winorder = pdo_fetchall($sql);
                if($winorder){
                    $data['winorder'] = $winorder;
                }
            }else{
                $data['isOver'] = 0;
            }
            
            $openid = $_GPC["openid"];
            $order = pdo_get('mzhk_sun_hyorder',array('uniacid'=>$_W['uniacid'],'gid'=>$gid,'openid'=>$openid));
            if($order){
                $data['isJoin'] = 1;
            }else{
                $data['isJoin'] = 0;
            }

            $data['lotterytime']=date("Y-m-d H:i:s",$data['lotterytime']);
        }

        $data['lb_imgs'] = explode(',',$data['lb_imgs']);
        $datas_s=strtotime($data['astime']);
        $data['astime']=date('Y-m-d',$datas_s);
        $datas_e=strtotime($data['antime']);
        $data['clocktime'] = $datas_e*1000;
        $data['antime']=date('Y-m-d',$datas_e);
        $data["code_img"] = "";
        
        
        
        if($showtype==0){
            $total_arr=pdo_fetch("select sum(num) as num from ".tablename('mzhk_sun_qgorder')." where status > 2 and not(isrefund=2) and gid = ".$gid." ");
            if($total_arr){
                $total = intval($total_arr["num"]);
            }else{
                $total = 0;
            }
            $data['allnum'] = intval($data['num'] + $total);
        }else{
            $data['allnum'] = intval($data['num'] + $data['buynum']);
        }
        
        

        
        
        $brand = pdo_get('mzhk_sun_brand',array('uniacid'=>$_W['uniacid'],'bid'=>$data['bid']),array("longitude","latitude","address","phone","img"));
        $data['longitude'] = $brand["longitude"];
        $data['latitude'] = $brand["latitude"];
        $data['address'] = $brand["address"];
        $data['phone'] = $brand["phone"];
        $data['img'] = $brand["img"];

        echo json_encode($data);
    }

    
    public function doPageAddqgOrder(){
        global $_GPC, $_W;
        $gid=$_GPC['id'];
        $uniacid = $_W['uniacid'];
        $openid = $_GPC['openid'];
        $typeid = intval($_GPC['typeid']);
        $goodsnum = intval($_GPC["goodsnum"]);
        $goodsnum = $goodsnum>0?$goodsnum:1;
        $res=pdo_get('mzhk_sun_goods',array('gid'=>$gid,'uniacid'=>$uniacid),array("gid","gname","num","astime","antime","pic","bid","bname","stocktype","expirationtime","limitnum"));
        if($res["num"]<=0){
           return $this->result(1,'该商品已经没货了！！！', array());
        }
        if($goodsnum > $res["num"]){
            return $this->result(1,'该商品库存剩余'.$res["num"].'个！！！', array());
        }
        if($typeid!=1){
            if(strtotime($res["astime"])>time()){
                return $this->result(1, '该商品不在活动时间内！！！', array());
            }elseif(strtotime($res["antime"])<time()){
                return $this->result(1, '该商品活动已结束！！！', array());
            }
            $total_arr=pdo_fetch("select sum(num) as num from ".tablename('mzhk_sun_qgorder')." where addtime between ".strtotime($res["astime"])." and ".strtotime($res["antime"])." and status > 1 and uniacid='".$uniacid."' and gid='".$gid."' AND openid='".$openid."' ");
            if($total_arr){
                $total = $total_arr["num"];
            }else{
                $total = 0;
            }
        }else{
            $total_arr=pdo_fetch("select sum(num) as num from ".tablename('mzhk_sun_order')." where addtime between ".strtotime($res["astime"])." and ".strtotime($res["antime"])." and status > 1 and uniacid='".$uniacid."' and gid='".$gid."' AND openid='".$openid."' ");
            if($total_arr){
                $total = $total_arr["num"];
            }else{
                $total = 0;
            }
        }
        if($res["limitnum"]>0){
            $lavenum = intval($res["limitnum"]-$total);
            if($lavenum < $goodsnum){
                return $this->result(1,'该商品你还能买'.$lavenum.'个，当前购买数量已超过！！！', array());
            }
        }

        $data['sincetype']=$_GPC['sincetype'];
        $data['cityName']=$_GPC['cityName'];
        $data['detailInfo']=$_GPC['detailInfo'];
        $data['time']=$_GPC['time'];
        $data['uremark']=$_GPC['uremark'];
        $data['countyName']=$_GPC['countyName'];
        $data['provinceName']=$_GPC['provinceName'];
        $data['name']=$_GPC['name'];
        $data['orderNum'] = time() . mt_rand(10000, 99999);
        $data['telNumber']=$_GPC['telNumber'];
        $data['openid']=$openid;
        $data['uniacid']=$uniacid;
        $data['addtime']=time();
        $data['money']=$_GPC['price'];
        $data['gid']=$gid;
        $data['gname']=$res["gname"];
        $data['bid']=$res["bid"];
        $data['bname']=$res["bname"];
        $data['goodsimg']=$res["pic"];
        $data['expirationtime']=$res["expirationtime"];
        $data['paytype']=intval($_GPC["paytype"]);  

        $data['status']=2;
        $data['num'] = $goodsnum;
        $data['deliveryfee']=$_GPC['deliveryfee'];
        if($typeid==1){
            $re=pdo_insert('mzhk_sun_order',$data);
        }else{
            $re=pdo_insert('mzhk_sun_qgorder',$data);
        }

        $orderid=pdo_insertid();
        if(intval($orderid)<=0){
            return $this->result(1,'数据提交失败，请重新提交', array());
        }
        
        
        if($res["num"]>0){
            if($res["stocktype"]==0){
                pdo_update('mzhk_sun_goods',array("num -="=>$goodsnum),array('gid'=>$gid,'uniacid'=>$uniacid));
            }
            pdo_update('mzhk_sun_goods',array("buynum +="=>$goodsnum),array('gid'=>$gid,'uniacid'=>$uniacid));
        }

        echo  $orderid;
    }

    public function doPagePayqgOrder(){
        global $_W, $_GPC;
        $typeid = intval($_GPC["typeid"]);
        $datas['status'] = 3;
        $datas['paytime'] = time();
        $uniacid = $_W['uniacid'];
        if($typeid==1){
            $res=pdo_get('mzhk_sun_order',array('oid'=>$_GPC['order_id'],'uniacid'=>$uniacid),array("bid","orderNum","oid","gid","status","num"));
            if($res["status"]==2){
                $orderinfo = pdo_update('mzhk_sun_order', $datas, array('oid' => $_GPC['order_id'], 'uniacid' => $_W['uniacid']));
            }
        }else{
            $res=pdo_get('mzhk_sun_qgorder',array('oid'=>$_GPC['order_id'],'uniacid'=>$uniacid),array("bid","orderNum","oid","gid","status","num"));
            if($res["status"]==2){
                $orderinfo = pdo_update('mzhk_sun_qgorder', $datas, array('oid' => $_GPC['order_id'], 'uniacid' => $_W['uniacid']));
            }
        }
        if($res["bid"]>0){
            $this->SendSms($res["bid"],0,$res["orderNum"]);
        }
        if($orderinfo){
            $goods=pdo_get('mzhk_sun_goods',array('gid'=>$res['gid'],'uniacid'=>$uniacid),array("num","stocktype"));
            if($goods["num"]>0){
                if($goods["stocktype"]==1){
                    if($goods["num"] >= $res["num"]){
                        pdo_update('mzhk_sun_goods',array("num -="=>intval($res["num"])),array('gid'=>$res['gid'],'uniacid'=>$uniacid));
                    }else{
                        pdo_update('mzhk_sun_goods',array("num"=>0),array('gid'=>$res['gid'],'uniacid'=>$uniacid));
                    }
                }
            }
            echo "success";
        }else{
            echo "error0001";
        }
    }

    
    public function doPagePayjkOrder(){
        global $_W, $_GPC;
        $datas['status'] = 1;
        $datas['paytime'] = time();
        $uniacid = $_W['uniacid'];
        $orderinfo = pdo_update('mzhk_sun_cardorder', $datas, array('id' => $_GPC['order_id'], 'uniacid' => $_W['uniacid']));

        $res=pdo_get('mzhk_sun_cardorder',array('id'=>$_GPC['order_id'],'uniacid'=>$uniacid),array("bid","id","ordernum"));
        if($res["bid"]>0){
            $this->SendSms($res["bid"],0,$res["ordernum"]);
        }

        echo json_encode($orderinfo);
    }

    
    public function doPagegetOrder(){
        global $_GPC, $_W;
        $orderstatus = intval($_GPC["orderstatus"]);
        $openid = $_GPC["openid"];
        $typeid = intval($_GPC["typeid"]);

        $pagesize = 5;
        $pageindex = intval($_GPC['page'])*$pagesize;

        $where = " where o.uniacid='".$_W['uniacid']."' AND o.openid='".$openid."' ";
        if($orderstatus==3){
            $where .= " and o.status=:status and (o.isrefund=0 or o.isrefund=3) ";
            $fetchdata[":status"] = $orderstatus;
        }elseif($orderstatus==5){
            $where .= " and (o.status=:status or o.isrefund>0) ";
            $fetchdata[":status"] = $orderstatus;
        }elseif($orderstatus==0){
            
        }else{
            $where .= " and o.status=:status ";
            $fetchdata[":status"] = $orderstatus;
        }
        if($typeid==1){
            $sql = 'select o.status,o.isrefund,o.num,o.money,o.oid,o.gname as order_gname,o.goodsimg as order_pic,o.bname as order_bname,g.gname,g.pic,b.bname from '.tablename('mzhk_sun_order').' as o left join '.tablename('mzhk_sun_goods').' as g on o.gid=g.gid left join '.tablename('mzhk_sun_brand').' as b on g.bid=b.bid '.$where." order by o.oid desc limit ".$pageindex.",".$pagesize;
        }else{
            $sql = 'select o.status,o.isrefund,o.num,o.money,o.oid,o.gname as order_gname,o.goodsimg as order_pic,o.bname as order_bname,g.gname,g.pic,b.bname from '.tablename('mzhk_sun_qgorder').' as o left join '.tablename('mzhk_sun_goods').' as g on o.gid=g.gid left join '.tablename('mzhk_sun_brand').' as b on g.bid=b.bid '.$where." order by o.oid desc limit ".$pageindex.",".$pagesize;
        }

        $data = pdo_fetchall($sql,$fetchdata);
        if($data){
            echo json_encode($data);
        }else{
            echo 2;
        }
    }

    
    public function doPageGetOrderDetail(){
        global $_GPC, $_W;
        $order_id = intval($_GPC["order_id"]);
        $ordertype = intval($_GPC["ordertype"]);
        $uniacid = $_W['uniacid'];
        $where = " where o.uniacid='".$uniacid."' ";
        $showqrcodestatus = 3;
        if($ordertype==1){
            $where .= " and o.id=:id ";
            $fetchdata[":id"] = $order_id;
            $sql = 'select o.gid,o.expirationtime,o.status,o.isrefund,o.sincetype,o.shipname,o.shipnum,o.name,o.countyName,o.provinceName,o.cityName,o.detailInfo,o.telNumber,o.isrefund,o.groupordernum as orderNum,o.num,o.money,o.id,o.paytime,o.addtime,o.gname as order_gname,o.goodsimg as order_pic,o.bname as order_bname,g.gname,g.pic,b.bname from '.tablename('mzhk_sun_ptgroups').' as o left join '.tablename('mzhk_sun_goods').' as g on o.gid=g.gid left join '.tablename('mzhk_sun_brand').' as b on g.bid=b.bid '.$where." order by o.id desc ";
            $showqrcodestatus = 4;
        }elseif($ordertype==2){
            $where .= " and o.oid=:oid ";
            $fetchdata[":oid"] = $order_id;
            $sql = 'select o.gid,o.expirationtime,o.status,o.isrefund,o.sincetype,o.shipname,o.shipnum,o.name,o.countyName,o.provinceName,o.cityName,o.detailInfo,o.telNumber,o.orderNum,o.num,o.money,o.oid,o.paytime,o.addtime,o.gname as order_gname,o.goodsimg as order_pic,o.bname as order_bname,g.gname,g.pic,b.bname from '.tablename('mzhk_sun_kjorder').' as o left join '.tablename('mzhk_sun_goods').' as g on o.gid=g.gid left join '.tablename('mzhk_sun_brand').' as b on g.bid=b.bid '.$where." order by o.oid desc ";
            $showqrcodestatus = 3;
        }elseif($ordertype==3){
            $where .= " and o.id=:id ";
            $fetchdata[":id"] = $order_id;
            $sql = 'select o.gid,o.expirationtime,o.status,o.isrefund,o.sincetype,o.shipname,o.shipnum,o.name,o.countyName,o.provinceName,o.cityName,o.detailInfo,o.telNumber,o.ordernum as orderNum,o.money,o.id,o.addtime as paytime,o.addtime,o.gname as order_gname,o.goodsimg as order_pic,o.bname as order_bname,g.gname,g.pic,b.bname from '.tablename('mzhk_sun_cardorder').' as o left join '.tablename('mzhk_sun_goods').' as g on o.gid=g.gid left join '.tablename('mzhk_sun_brand').' as b on g.bid=b.bid '.$where." order by o.id desc ";
            $showqrcodestatus = 0;
        }elseif($ordertype==4){
            $where .= " and o.oid=:oid ";
            $fetchdata[":oid"] = $order_id;
            $sql = 'select o.gid,o.expirationtime,o.status,o.isrefund,o.sincetype,o.shipname,o.shipnum,o.name,o.countyName,o.provinceName,o.cityName,o.detailInfo,o.telNumber,o.orderNum,o.num,o.money,o.oid,o.paytime,o.addtime,o.gname as order_gname,o.goodsimg as order_pic,o.bname as order_bname,g.gname,g.pic,b.bname from '.tablename('mzhk_sun_order').' as o left join '.tablename('mzhk_sun_goods').' as g on o.gid=g.gid left join '.tablename('mzhk_sun_brand').' as b on g.bid=b.bid '.$where." order by o.oid desc ";
            $showqrcodestatus = 3;
        }elseif($ordertype==6){
            $where .= " and o.oid=:oid ";
            $fetchdata[":oid"] = $order_id;
            $sql = 'select o.gid,o.expirationtime,o.status,o.isrefund,o.sincetype,o.islottery,o.shipname,o.shipnum,o.name,o.countyName,o.provinceName,o.cityName,o.detailInfo,o.telNumber,o.orderNum,o.num,o.money,o.oid,o.paytime,o.addtime,o.gname as order_gname,o.goodsimg as order_pic,o.bname as order_bname,g.gname,g.pic,b.bname from '.tablename('mzhk_sun_hyorder').' as o left join '.tablename('mzhk_sun_goods').' as g on o.gid=g.gid left join '.tablename('mzhk_sun_brand').' as b on g.bid=b.bid '.$where." order by o.oid desc ";
            $showqrcodestatus = 0;
        }else{
            $where .= " and o.oid=:oid ";
            $fetchdata[":oid"] = $order_id;
            $sql = 'select o.gid,o.expirationtime,o.status,o.isrefund,o.sincetype,o.shipname,o.shipnum,o.name,o.countyName,o.provinceName,o.cityName,o.detailInfo,o.telNumber,o.orderNum,o.num,o.money,o.oid,o.paytime,o.addtime,o.gname as order_gname,o.goodsimg as order_pic,o.bname as order_bname,g.gname,g.pic,b.bname from '.tablename('mzhk_sun_qgorder').' as o left join '.tablename('mzhk_sun_goods').' as g on o.gid=g.gid left join '.tablename('mzhk_sun_brand').' as b on g.bid=b.bid '.$where." order by o.oid desc ";
            $showqrcodestatus = 3;
        }
        $res = pdo_fetch($sql,$fetchdata);
        $res["paytime"] = $res["paytime"]?date("Y-m-d H:i:s",$res["paytime"]):"";
        $res["addtime"] = $res["addtime"]?date("Y-m-d H:i:s",$res["addtime"]):"";
        $res["expirationtime"] = $res["expirationtime"]?date("Y-m-d H:i:s",$res["expirationtime"]):"";
        $res["showqrcodestatus"] = $showqrcodestatus; 

        echo json_encode($res);
    }

    
    public function doPageSetOrderStatus(){
        global $_W, $_GPC;
        $ordertype = intval($_GPC["ordertype"]);
        $status = intval($_GPC["status"]);
        $order_id = intval($_GPC["order_id"]);
        $uniacid = $_W['uniacid'];
        $refund = intval($_GPC["refund"]);
        if($refund!=1 && $status==0){
            echo 2;
            exit;
        }
        $datas['status'] = $status;
        if($ordertype==1){
            $g_order_id = intval($_GPC["g_order_id"]);
            if($refund==1){
                $order = pdo_get('mzhk_sun_ptgroups',array('id'=>$g_order_id,'uniacid'=>$uniacid),array("bid","groupordernum","status"));
                if($order["status"]==5){
                    return $this->result(1, '该商品已经核销过，无法退款！！！', array("status"=>$order["status"]));
                }
                if($order["status"]!=4){
                    return $this->result(1, '该商品无法退款！！！', array("status"=>$order["status"]));
                }
                $res = pdo_update('mzhk_sun_ptgroups', array("isrefund"=>1), array('id' => $g_order_id, 'uniacid' => $uniacid));
                if($order["bid"]>0){
                    $this->SendSms($order["bid"],1,$order["groupordernum"]);
                }
            }elseif($refund==4){
                $res = pdo_update('mzhk_sun_ptgroups', array("isrefund"=>0), array('id' => $g_order_id, 'uniacid' => $uniacid));
            }else{
                $res = pdo_update('mzhk_sun_ptgroups', $datas, array('id' => $g_order_id, 'uniacid' => $uniacid));
                if($status==1){
                    
                    $g_order = pdo_get('mzhk_sun_ptgroups',array('id'=>$g_order_id,'uniacid'=>$uniacid),array("is_lead"));
                    if($g_order["is_lead"]==1){
                        
                        $ress = pdo_update('mzhk_sun_ptorders', array("is_ok"=>2), array('id' => $order_id, 'uniacid' => $uniacid));
                    }
                }
            }
                
        }elseif($ordertype==2){
            if($refund==1){
                $order = pdo_get('mzhk_sun_kjorder',array('oid'=>$order_id,'uniacid'=>$uniacid),array("bid","orderNum","status"));
                if($order["status"]==5){
                    return $this->result(1, '该商品已经核销过，无法退款！！！', array("status"=>$order["status"]));
                }
                if($order["status"]!=3){
                    return $this->result(1, '该商品无法退款！！！', array("status"=>$order["status"]));
                }
                $res = pdo_update('mzhk_sun_kjorder', array("isrefund"=>1), array('oid' => $order_id, 'uniacid' => $uniacid));
                if($order["bid"]>0){
                    $this->SendSms($order["bid"],1,$order["orderNum"]);
                }
            }elseif($refund==4){
                $res = pdo_update('mzhk_sun_kjorder', array("isrefund"=>0), array('oid' => $order_id, 'uniacid' => $uniacid));
            }else{
                $res = pdo_update('mzhk_sun_kjorder', $datas, array('oid' => $order_id, 'uniacid' => $uniacid));
            }
        }elseif($ordertype==3){
            if($refund==1){
                $order = pdo_get('mzhk_sun_cardorder',array('id'=>$order_id,'uniacid'=>$uniacid),array("bid","ordernum","status"));
                if($order["status"]==2){
                    return $this->result(1, '该商品已经核销过，无法退款！！！', array("status"=>$order["status"]));
                }
                if($order["status"]!=1){
                    return $this->result(1, '该商品无法退款！！！', array("status"=>$order["status"]));
                }
                $res = pdo_update('mzhk_sun_cardorder', array("isrefund"=>1), array('id' => $order_id, 'uniacid' => $uniacid));
                if($order["bid"]>0){
                    $this->SendSms($order["bid"],1,$order["ordernum"]);
                }
            }elseif($refund==4){
                $res = pdo_update('mzhk_sun_cardorder', array("isrefund"=>0), array('id' => $order_id, 'uniacid' => $uniacid));
            }else{
                $res = pdo_update('mzhk_sun_cardorder', $datas, array('id' => $order_id, 'uniacid' => $uniacid));
            }
        }elseif($ordertype==4){
            if($refund==1){
                $order = pdo_get('mzhk_sun_order',array('oid'=>$order_id,'uniacid'=>$uniacid),array("bid","orderNum","status"));
                if($order["status"]==5){
                    return $this->result(1, '该商品已经核销过，无法退款！！！', array("status"=>$order["status"]));
                }
                if($order["status"]!=3){
                    return $this->result(1, '该商品无法退款！！！', array("status"=>$order["status"]));
                }
                $res = pdo_update('mzhk_sun_order', array("isrefund"=>1), array('oid' => $order_id, 'uniacid' => $uniacid));
                if($order["bid"]>0){
                    $this->SendSms($order["bid"],1,$order["ordernum"]);
                }
            }elseif($refund==4){
                $res = pdo_update('mzhk_sun_order', array("isrefund"=>0), array('oid' => $order_id, 'uniacid' => $uniacid));
            }else{
                $res = pdo_update('mzhk_sun_order', $datas, array('oid' => $order_id, 'uniacid' => $uniacid));
            }
        }else{
            if($refund==1){
                $order = pdo_get('mzhk_sun_qgorder',array('oid'=>$order_id,'uniacid'=>$uniacid),array("bid","orderNum","status"));
                if($order["status"]==5){
                    return $this->result(1, '该商品已经核销过，无法退款！！！', array("status"=>$order["status"]));
                }
                if($order["status"]!=3){
                    return $this->result(1, '该商品无法退款！！！', array("status"=>$order["status"]));
                }
                $res = pdo_update('mzhk_sun_qgorder', array("isrefund"=>1), array('oid' => $order_id, 'uniacid' => $uniacid));
                if($order["bid"]>0){
                    $this->SendSms($order["bid"],1,$order["orderNum"]);
                }
            }elseif($refund==4){
                $res = pdo_update('mzhk_sun_qgorder', array("isrefund"=>0), array('oid' => $order_id, 'uniacid' => $uniacid));
            }else{
                $res = pdo_update('mzhk_sun_qgorder', $datas, array('oid' => $order_id, 'uniacid' => $uniacid));
            }
        }

        if($res){
            echo 1;
        }else{
            echo 2;
        }

    }

    
    public function doPageSetOrderFinish(){
        global $_W, $_GPC;
        $ordertype = intval($_GPC["ordertype"]);
        $status = intval($_GPC["status"]);
        $order_id = intval($_GPC["order_id"]);
        $uniacid = $_W['uniacid'];
        $openid = $_GPC["openid"];
        $ordername = "";

        if($ordertype==1){
            $g_order_id = intval($_GPC["g_order_id"]);
            
            $order = pdo_get('mzhk_sun_ptgroups',array('uniacid'=>$uniacid,'id'=>$g_order_id),array("money","bid","gid","groupordernum","status"));
            if($order["status"]==5){
                echo "1";
                exit;
            }
            $res=pdo_update('mzhk_sun_ptgroups',array('status'=>5,"finishtime"=>time()),array('id'=>$g_order_id,'status'=>6,'openid'=>$openid));
            if($res){
                $order["orderNum"] = $order["groupordernum"];
                $ordername = "拼团订单";
                $order_id = $g_order_id;
            }
        }elseif($ordertype==2){
            
            $order = pdo_get('mzhk_sun_kjorder',array('uniacid'=>$uniacid,'oid'=>$order_id),array("money","bid","gid","orderNum","status"));
            if($order["status"]==5){
                echo "1";
                exit;
            }
            $res=pdo_update('mzhk_sun_kjorder',array('status'=>5,"finishtime"=>time()),array('oid'=>$order_id,'status'=>4,'openid'=>$openid));
            if($res){
                $ordername = "砍价订单";
            }
        }elseif($ordertype==3){
            
            $order = pdo_get('mzhk_sun_cardorder',array('uniacid'=>$uniacid,'id'=>$order_id),array("money","bid","gid","ordernum","status"));
            if($order["status"]==2){
                echo "1";
                exit;
            }
            $res=pdo_update('mzhk_sun_cardorder',array('status'=>2,"finishtime"=>time()),array('id'=>$order_id,'openid'=>$openid));
            if($res){
                $order["orderNum"] = $order["ordernum"];
                $ordername = "集卡订单";
            }
        }elseif($ordertype==4){
            
            $order = pdo_get('mzhk_sun_order',array('uniacid'=>$uniacid,'oid'=>$order_id),array("money","bid","gid","orderNum","status"));
            if($order["status"]==5){
                echo "1";
                exit;
            }
            $res=pdo_update('mzhk_sun_order',array('status'=>5,"finishtime"=>time()),array('oid'=>$order_id,'status'=>4,'openid'=>$openid));
            if($res){
                $ordername = "普通订单";
            }
        }else{
            
            $order = pdo_get('mzhk_sun_qgorder',array('uniacid'=>$uniacid,'oid'=>$order_id),array("money","bid","gid","orderNum","status"));
            if($order["status"]==5){
                echo "1";
                exit;
            }
            $res=pdo_update('mzhk_sun_qgorder',array('status'=>5,"finishtime"=>time()),array('oid'=>$order_id,'status'=>4,'openid'=>$openid));
            if($res){
                
                $order = pdo_get('mzhk_sun_qgorder',array('uniacid'=>$uniacid,'oid'=>$order_id),array("money","bid","gid","orderNum"));
                $ordername = "抢购订单";
            }
        }

        if($res){
            $bid = intval($order['bid']);
            if($bid==0){
                $goods = pdo_get('mzhk_sun_goods',array('uniacid'=>$uniacid,'gid'=>$order['gid']),array("bid"));
                $bid = intval($goods['bid']);
            }
            
            $brand = pdo_get('mzhk_sun_brand',array('uniacid'=>$uniacid,'bid'=>$bid),array("totalamount","frozenamount","bname"));

            
            $branddata = array();
            $branddata["totalamount"] = $brand["totalamount"]+$order["money"];
            pdo_update('mzhk_sun_brand', $branddata, array('bid' => $bid));
            
            $data = array();
            $data["bid"] = $bid;
            $data["bname"] = $brand['bname'];
            $data["mcd_type"] = 1;
            $data["mcd_memo"] = $ordername."-用户确认收货-订单id：".$order_id.";订单号：".$order["orderNum"]."；";
            $data["addtime"] = time();
            $data["money"] = $order["money"];
            $data["order_id"] = $order_id;
            $data["uniacid"] = $uniacid;
            $data["status"] = 1;
            pdo_insert('mzhk_sun_mercapdetails', $data);
            echo 1;
        }else{
            echo 2;
        }

    }

    
    public function doPageSetBrandOrderStatus(){
        global $_W, $_GPC;
        $ordertype = intval($_GPC["ordertype"]);
        $status = intval($_GPC["status"]);
        $bid = intval($_GPC["bid"]);
        $order_id = intval($_GPC["order_id"]);
        $uniacid = $_W['uniacid'];
        $refund = intval($_GPC["refund"]);
        $ship = intval($_GPC["ship"]);
        $shipname = $_GPC["shipname"];
        $shipnum = $_GPC["shipnum"];
        
        $agreetorefund = false;
        
        
        
        
        
        if($ordertype==1){
            $g_order_id = intval($_GPC["g_order_id"]);

            if($refund==2){
                $orderdata=pdo_get("mzhk_sun_ptgroups",array('uniacid'=>$uniacid,'id'=>$g_order_id,'isrefund'=>1),array("id","out_trade_no","money","out_refund_no","gid","num","paytype","openid"));
                if($orderdata){
                    $orderdata["oid"] = $orderdata["id"];
                    $memo = "拼团订单退款，订单id：".$orderdata["oid"];
                    $agreetorefund = true;
                }
            }elseif($refund==3){
                $orderdata=pdo_get("mzhk_sun_ptgroups",array('uniacid'=>$uniacid,'id'=>$g_order_id),array("id","out_trade_no","money","out_refund_no"));
                if($orderdata){
                    pdo_update('mzhk_sun_ptgroups',array("isrefund"=>3),array('id'=>$orderdata["id"],'uniacid'=>$uniacid));
                    echo 1;
                    exit;
                }else{
                    return $this->result(1,'参数错误，拒绝失败！', array());
                    exit;
                }
            }
            if($ship==1){
                if(empty($shipname) || empty($shipnum)){
                    return $this->result(1,'请输入快递名称和快递单号！', array());
                }else{
                    $data_ship["status"] = 6;
                    $data_ship["shiptime"] = time();
                    $data_ship["shipname"] = $shipname;
                    $data_ship["shipnum"] = $shipnum;
                    $ress = pdo_update('mzhk_sun_ptgroups',$data_ship,array('id'=>$g_order_id,'uniacid'=>$uniacid));
                    if($ress){
                        echo 1;
                    }else{
                        echo 2;
                    }
                    exit;
                }
            }elseif($ship==2){
                $ress = pdo_update('mzhk_sun_ptgroups',array("status"=>6,"shiptime"=>time()),array('id'=>$g_order_id,'uniacid'=>$uniacid));
                if($ress){
                    echo 1;
                }else{
                    echo 2;
                }
                exit;
            }
                
        }elseif($ordertype==2){
            if($refund==2){
                $orderdata=pdo_get("mzhk_sun_kjorder",array('uniacid'=>$uniacid,'oid'=>$order_id,'isrefund'=>1),array("oid","out_trade_no","money","out_refund_no","gid","num","paytype","openid"));
                if($orderdata){
                    $memo = "砍价订单退款，订单id：".$orderdata["oid"];
                    $agreetorefund = true;
                }
            }elseif($refund==3){
                $orderdata=pdo_get("mzhk_sun_kjorder",array('uniacid'=>$uniacid,'oid'=>$order_id),array("oid","out_trade_no","money","out_refund_no"));
                if($orderdata){
                    pdo_update('mzhk_sun_kjorder',array("isrefund"=>3),array('oid'=>$orderdata["oid"],'uniacid'=>$uniacid));
                    echo 1;
                    exit;
                }else{
                    return $this->result(1,'参数错误，拒绝失败！', array());
                    exit;
                }
            }
            if($ship==1){
                if(empty($shipname) || empty($shipnum)){
                    return $this->result(1,'请输入快递名称和快递单号！', array());
                }else{
                    $data_ship["status"] = 4;
                    $data_ship["shiptime"] = time();
                    $data_ship["shipname"] = $shipname;
                    $data_ship["shipnum"] = $shipnum;
                    $ress = pdo_update('mzhk_sun_kjorder',$data_ship,array('oid'=>$order_id,'uniacid'=>$uniacid));
                    if($ress){
                        echo 1;
                    }else{
                        echo 2;
                    }
                    exit;
                }
            }elseif($ship==2){
                $ress = pdo_update('mzhk_sun_kjorder',array("status"=>4,"shiptime"=>time()),array('oid'=>$order_id,'uniacid'=>$uniacid));
                if($ress){
                    echo 1;
                }else{
                    echo 2;
                }
                exit;
            }
        }elseif($ordertype==3){

            if($refund==2){
                $orderdata=pdo_get("mzhk_sun_cardorder",array('uniacid'=>$uniacid,'id'=>$order_id,'isrefund'=>1),array("id","out_trade_no","money","out_refund_no","gid","num","paytype","openid"));
                if($orderdata){
                    $orderdata["oid"] = $orderdata["id"];
                    $memo = "集卡订单退款，订单id：".$orderdata["oid"];
                    $agreetorefund = true;
                }
            }elseif($refund==3){
                $ress = pdo_update('mzhk_sun_cardorder',array("isrefund"=>3),array('id'=>$order_id,'uniacid'=>$uniacid));
                if($ress){
                    echo 1;
                }else{
                    echo 2;
                }
                exit;
            }
            if($ship==1){
                if(empty($shipname) || empty($shipnum)){
                    return $this->result(1,'请输入快递名称和快递单号！', array());
                }else{
                    $data_ship["status"] = 3;
                    $data_ship["shiptime"] = time();
                    $data_ship["shipname"] = $shipname;
                    $data_ship["shipnum"] = $shipnum;
                    $ress = pdo_update('mzhk_sun_cardorder',$data_ship,array('id'=>$order_id,'uniacid'=>$uniacid));
                    if($ress){
                        echo 1;
                    }else{
                        echo 2;
                    }
                    exit;
                }
            }elseif($ship==2){
                $ress = pdo_update('mzhk_sun_cardorder',array("status"=>3,"shiptime"=>time()),array('id'=>$order_id,'uniacid'=>$uniacid));
                if($ress){
                    echo 1;
                }else{
                    echo 2;
                }
                exit;
            }
        }elseif($ordertype==4){
            if($refund==2){
                $orderdata=pdo_get("mzhk_sun_order",array('uniacid'=>$uniacid,'oid'=>$order_id,'isrefund'=>1),array("oid","out_trade_no","money","out_refund_no","gid","num","paytype","openid"));
                if($orderdata){
                    $memo = "普通订单退款，订单id：".$orderdata["oid"];
                    $agreetorefund = true;
                }
            }elseif($refund==3){
                $orderdata=pdo_get("mzhk_sun_order",array('uniacid'=>$uniacid,'oid'=>$order_id),array("oid","out_trade_no","money","out_refund_no"));
                if($orderdata){
                    pdo_update('mzhk_sun_order',array("isrefund"=>3),array('oid'=>$orderdata["oid"],'uniacid'=>$uniacid));
                    echo 1;
                    exit;
                }else{
                    return $this->result(1,'参数错误，拒绝失败！', array());
                    exit;
                }
            }
            if($ship==1){
                if(empty($shipname) || empty($shipnum)){
                    return $this->result(1,'请输入快递名称和快递单号！', array());
                }else{
                    $data_ship["status"] = 4;
                    $data_ship["shiptime"] = time();
                    $data_ship["shipname"] = $shipname;
                    $data_ship["shipnum"] = $shipnum;
                    $ress = pdo_update('mzhk_sun_order',$data_ship,array('oid'=>$order_id,'uniacid'=>$uniacid));
                    if($ress){
                        echo 1;
                    }else{
                        echo 2;
                    }
                    exit;
                }
            }elseif($ship==2){
                $ress = pdo_update('mzhk_sun_order',array("status"=>4,"shiptime"=>time()),array('oid'=>$order_id,'uniacid'=>$uniacid));
                if($ress){
                    echo 1;
                }else{
                    echo 2;
                }
                exit;
            }
        }else{
            if($refund==2){
                $orderdata=pdo_get("mzhk_sun_qgorder",array('uniacid'=>$uniacid,'oid'=>$order_id,'isrefund'=>1),array("oid","out_trade_no","money","out_refund_no","gid","num","paytype","openid"));
                if($orderdata){
                    $memo = "抢购订单退款，订单id：".$orderdata["oid"];
                    $agreetorefund = true;
                }
            }elseif($refund==3){
                $orderdata=pdo_get("mzhk_sun_qgorder",array('uniacid'=>$uniacid,'oid'=>$order_id),array("oid","out_trade_no","money","out_refund_no"));
                if($orderdata){
                    pdo_update('mzhk_sun_qgorder',array("isrefund"=>3),array('oid'=>$orderdata["oid"],'uniacid'=>$uniacid));
                    echo 1;
                    exit;
                }else{
                    return $this->result(1,'参数错误，拒绝失败！', array());
                    exit;
                }
            }
            if($ship==1){
                if(empty($shipname) || empty($shipnum)){
                    return $this->result(1,'请输入快递名称和快递单号！', array());
                }else{
                    $data_ship["status"] = 4;
                    $data_ship["shiptime"] = time();
                    $data_ship["shipname"] = $shipname;
                    $data_ship["shipnum"] = $shipnum;
                    $ress = pdo_update('mzhk_sun_qgorder',$data_ship,array('oid'=>$order_id,'uniacid'=>$uniacid));
                    if($ress){
                        echo 1;
                    }else{
                        echo 2;
                    }
                    exit;
                }
            }elseif($ship==2){
                $ress = pdo_update('mzhk_sun_qgorder',array("status"=>4,"shiptime"=>time()),array('oid'=>$order_id,'uniacid'=>$uniacid));
                if($ress){
                    echo 1;
                }else{
                    echo 2;
                }
                exit;
            }
        }

        if($agreetorefund && $orderdata){
            
            if($orderdata["paytype"]==2){
                $money = $orderdata['money'];
                
                $res_user = pdo_update('mzhk_sun_user', array('money +=' => $money), array('openid' => $orderdata['openid']));
                if($res_user){
                    
                    $data = array();
                    $data["openid"] = $orderdata['openid'];
                    $data["order_id"] = $orderdata['oid'];
                    $data["money"] = $money;
                    $data["addtime"] = time();
                    $data["rtype"] = 4;
                    $data["memo"] = $memo;
                    $data["uniacid"] = $uniacid;
                    $res=pdo_insert('mzhk_sun_rechargelogo',$data);
                    $result['result_code'] = 'SUCCESS';
                }else{
                    $result['result_code'] = 'ERROR';
                }
            }else{
                
                include_once IA_ROOT . '/addons/mzhk_sun/cert/WxPay.Api.php';
                load()->model('account');
                load()->func('communication');
                
                $res=pdo_get('mzhk_sun_system',array('uniacid'=>$uniacid));
                $appid=$res['appid'];
                $wxkey=$res['wxkey'];
                $mchid=$res['mchid']; 
                $path_cert = IA_ROOT . "/addons/mzhk_sun/cert/".$res['apiclient_cert'];
                $path_key = IA_ROOT . "/addons/mzhk_sun/cert/".$res['apiclient_key'];
                if(!$res['apiclient_cert'] || !$res['apiclient_key']){
                    return $this->result(1,'小程序配置错误，请联系平台管理员！', array());
                    exit;
                }
                $out_trade_no=$orderdata['out_trade_no'];
                $fee = $orderdata['money'] * 100;
                $out_refund_no = $orderdata['out_refund_no']?$orderdata['out_trade_no']:$mchid.rand(100,999).time().rand(1000,9999);
                $WxPayApi = new WxPayApi();
                $input = new WxPayRefund();
                $input->SetAppid($appid);
                $input->SetMch_id($mchid);
                $input->SetOp_user_id($mchid);
                $input->SetRefund_fee($fee);
                $input->SetTotal_fee($fee);
                $input->SetOut_refund_no($out_refund_no);
                $input->SetOut_trade_no($out_trade_no);
                $result = $WxPayApi->refund($input, 6, $path_cert, $path_key, $wxkey);
            }
            
            
            if ($result['result_code'] == 'SUCCESS') {
                $num = $orderdata["num"]>0?$orderdata["num"]:1;
                
                pdo_update('mzhk_sun_goods',array("num +="=>$num),array('gid'=>$orderdata['gid'],'uniacid'=>$uniacid));
                if($ordertype==1){
                    pdo_update('mzhk_sun_ptgroups',array("isrefund"=>2,"out_refund_no"=>$out_refund_no),array('id'=>$orderdata["id"],'uniacid'=>$uniacid));
                }elseif($ordertype==2){
                    pdo_update('mzhk_sun_kjorder',array("isrefund"=>2,"out_refund_no"=>$out_refund_no),array('oid'=>$orderdata["oid"],'uniacid'=>$uniacid));
                }elseif($ordertype==3){
                    pdo_update('mzhk_sun_cardorder',array("isrefund"=>2,"out_refund_no"=>$out_refund_no),array('id'=>$orderdata["id"],'uniacid'=>$uniacid));
                }elseif($ordertype==4){
                    pdo_update('mzhk_sun_order',array("isrefund"=>2,"out_refund_no"=>$out_refund_no),array('oid'=>$orderdata["oid"],'uniacid'=>$uniacid));
                }else{
                    pdo_update('mzhk_sun_qgorder',array("isrefund"=>2,"out_refund_no"=>$out_refund_no),array('oid'=>$orderdata["oid"],'uniacid'=>$uniacid));
                }
                echo 1;
            }else{
                if($ordertype==1){
                    pdo_update('mzhk_sun_ptgroups',array("out_refund_no"=>$out_refund_no),array('id'=>$orderdata["id"],'uniacid'=>$uniacid));
                }elseif($ordertype==2){
                    pdo_update('mzhk_sun_kjorder',array("out_refund_no"=>$out_refund_no),array('oid'=>$orderdata["oid"],'uniacid'=>$uniacid));
                }elseif($ordertype==3){
                    pdo_update('mzhk_sun_cardorder',array("out_refund_no"=>$out_refund_no),array('id'=>$orderdata["id"],'uniacid'=>$uniacid));
                }elseif($ordertype==4){
                    pdo_update('mzhk_sun_order',array("out_refund_no"=>$out_refund_no),array('oid'=>$orderdata["oid"],'uniacid'=>$uniacid));
                }else{
                    pdo_update('mzhk_sun_qgorder',array("out_refund_no"=>$out_refund_no),array('oid'=>$orderdata["oid"],'uniacid'=>$uniacid));
                }
                return $this->result(1,'同意退款失败！'.$result["err_code_des"], array());
            }
        }else{
            return $this->result(1,'参数错误！', array());
        }

    }


    ###################################################################################################
    
    
    public function doPageJKactive(){
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $new = date("Y-m-d H:i:s");

        $pagesize = 3;
        $pageindex = intval($_GPC['page'])*$pagesize;
        $sql = 'select g.gid,g.pic,g.gname,b.address,b.bname,b.phone from '.tablename('mzhk_sun_goods').' as g left join '.tablename('mzhk_sun_brand').' as b on g.bid=b.bid where g.lid = 4 and g.status = 2 and g.uniacid='.$_W['uniacid'].' and g.num>0 and g.antime>='."'$new'".' and b.status=2 and g.is_jkopen=1 and g.isshelf=1 order by g.sort asc,g.gid desc limit '.$pageindex.",".$pagesize;
        
        $data = pdo_fetchall($sql);
        if($data){
            foreach ($data as $k=>$v){
                $data[$k]['selftime']=date('Y-m-d',strtotime($v["selftime"]));
                $data[$k]['astime']=date('Y-m-d',strtotime($v["astime"]));
                $data[$k]['antime']=date('Y-m-d',strtotime($v["antime"]));
            }
            echo json_encode($data);
        }else{
            echo 2;
        }
    }

    
    public function doPageJKClose(){
        global $_GPC, $_W;

        $uniacid = $_W['uniacid'];
        $new = date("Y-m-d H:i:s");

        $pagesize = 6;
        $pageindex = intval($_GPC['page'])*$pagesize;

        
        $sql = "select * from ".tablename('mzhk_sun_goods')." where lid = 4 and status = 2 and uniacid=".$uniacid." and (antime<='".$new."' or num=0 or isshelf=0) order by gid desc limit ".$pageindex.",".$pagesize;
        
        $data = pdo_fetchall($sql);
        if($data){
            foreach ($data as $k=>$v){
                $data[$k]['selftime']=date('Y-m-d',strtotime($v["selftime"]));
                $data[$k]['astime']=date('Y-m-d',strtotime($v["astime"]));
                $data[$k]['antime']=date('Y-m-d',strtotime($v["antime"]));
                $data[$k]["code_img"] = "";
            }
            echo json_encode($data);
        }else{
            echo 2;
        }

    }

    
    public function doPageJKdetails(){
        global $_GPC, $_W;

        $gid = intval($_GPC['gid']);
        $uniacid = $_W['uniacid'];
        $openid = $_GPC["openid"];
        $data = pdo_get('mzhk_sun_goods',array('uniacid'=>$uniacid,'gid'=>$gid));
        $data["code_img"] = "";

        if($data['is_jkopen']==1){
            $time = time();
            
                
            
            
            
        }else{
            return $this->result(1,'该活动已经结束', array());
        }

        $data["card_son"] = pdo_getall('mzhk_sun_gift',array('uniacid'=>$uniacid,'gid'=>$gid),array("pic","id","probability","title"),'','sort asc');
        if(strtotime($data['astime'])<=$time && strtotime($data['antime'])>=$time){ 
            
            $usercardlist = pdo_get('mzhk_sun_cardcollect',array('uniacid'=>$uniacid,'gid'=>$gid,'openid'=>$openid,'endtime >='=>$time),array("card_str_id","allnum","usednum"));
            $data["isend"] = 0;
        }else{
            $data["isend"] = 1;
            
            $usercardlist = pdo_get('mzhk_sun_cardcollect',array('uniacid'=>$uniacid,'gid'=>$gid,'openid'=>$openid),array("card_str_id","allnum","usednum"));
        }
        if($usercardlist){
            
            if($usercardlist["card_str_id"]==0){
                foreach ($data["card_son"] as $key => $val) {
                    $data["card_son"][$key]["num"] = 0;
                }

                $data["lotterynum"] = $usercardlist["allnum"];
                $data["isJoin"] = 1;
            }else{
                $usercardlist_arr = explode(",",$usercardlist["card_str_id"]);
                
                $flag = 1;
                foreach ($data["card_son"] as $key => $val) {
                    $data["card_son"][$key]["num"] = 0;
                    foreach($usercardlist_arr as $k => $v){
                        if($val["id"]==$v){
                            $data["card_son"][$key]["status"] = 1;
                            $data["card_son"][$key]["num"] += 1;
                        }
                    }
                    
                    if(in_array($val["id"], $usercardlist_arr)){
                        continue;
                    }else{
                        $flag = 0;
                    }
                }
                if($flag==1){
                    $data["isOk"] = 1;
                }

                if($usercardlist["allnum"]>0){
                    if($usercardlist["allnum"] > $usercardlist["usednum"]){
                        $data["lotterynum"] = $usercardlist["allnum"] - $usercardlist["usednum"];
                    }else{
                        $data["lotterynum"] = 0;
                    }
                }else{
                    $data["lotterynum"] = $data["initialtimes"];
                }
                $data["isJoin"] = 1;
            }
        }else{
            foreach ($data["card_son"] as $key => $val) {
                $data["card_son"][$key]["num"] = 0;
            }
            $data["lotterynum"] = $data["initialtimes"];

            $data["isJoin"] = 0;
        }

        
        $daystarttime = strtotime(date("Y-m-d")." 00:00:00");
        $dayendtime = strtotime(date("Y-m-d")." 23:59:59");
        $sharetimes = pdo_get('mzhk_sun_cardshare',array('uniacid'=>$uniacid,'gid'=>$gid,'openid'=>$openid,'status'=>1,'addtime >='=>$daystarttime,'addtime <='=>$dayendtime),array("num"));
        if($sharetimes){
            $data["daysharenum"] = $sharetimes["num"];
        }else{
            $data["daysharenum"] = 0;
        }

        if($data['lb_imgs']){
            $data['lb_imgs'] = explode(',',$data['lb_imgs']);
        }
        $data['astime']=date('Y-m-d',strtotime($data['astime']));
        $data['antime']=date('Y-m-d',strtotime($data['antime']));

        echo json_encode($data);
    }

    
    public function doPageAddjkOrder(){
        global $_GPC, $_W;
        $gid=$_GPC['id'];
        $uniacid = $_W['uniacid'];
        $openid = $_GPC['openid'];
        $res=pdo_get('mzhk_sun_goods',array('gid'=>$gid,'uniacid'=>$uniacid),array("gid","gname","num","antime","pic","bid","bname","expirationtime"));
        if($res["num"]<=0){
            return $this->result(1,'该奖品已经被领完了', array());
        }

        
        $jkorder=pdo_get('mzhk_sun_cardorder',array('gid'=>$gid,'openid'=>$openid,'uniacid'=>$uniacid,'endtime >='=>time()),array("id"));
        if($jkorder){
            return $this->result(1,'你已经领取过该奖品了', array());
        }

        $data['uniacid']=$uniacid;
        $data['openid']=$openid;
        $data['gid']=$gid;
        $data['addtime']=time();
        $data['ordernum'] = time() . mt_rand(10000, 99999);
        $data['detailinfo']=$_GPC['detailInfo'];
        $data['telnumber']=$_GPC['telNumber'];
        $data['status']=0;
        $data['countyname']=$_GPC['countyName'];
        $data['provincename']=$_GPC['provinceName'];
        $data['name']=$_GPC['name'];
        $data['cityname']=$_GPC['cityName'];
        $data['sincetype']=$_GPC['sincetype'];
        $data['time']=$_GPC['time'];
        $data['uremark']=$_GPC['uremark'];
        $data['money']=$_GPC['price'];
        $data['gname']=$res["gname"];
        $data['bid']=$res["bid"];
        $data['bname']=$res["bname"];
        $data['goodsimg']=$res["pic"];
        $data['expirationtime']=$res["expirationtime"];
        $data['paytype']=intval($_GPC["paytype"]);

        $data['endtime']=strtotime($res["antime"]);
        

        
        
        $re=pdo_insert('mzhk_sun_cardorder',$data);

        $orderid=pdo_insertid();
        if(intval($orderid)<=0){
            return $this->result(1,'数据提交失败，请重新提交', array());
        }
        
        
        pdo_update('mzhk_sun_goods',array("num -="=>1),array('gid'=>$gid,'uniacid'=>$uniacid));
        echo  $orderid;
    }

    
    public function doPagegetjkOrder(){
        global $_GPC, $_W;
        $orderstatus = intval($_GPC["orderstatus"]);
        $openid = $_GPC["openid"];
        $showtype = intval($_GPC["showtype"]);

        $pagesize = 5;
        $pageindex = intval($_GPC['page'])*$pagesize;

        if($showtype==6){
            $where = " where uniacid='".$_W['uniacid']."' AND openid='".$openid."' ";
            if($orderstatus==2){
                $where .= " and islottery=2 ";
            }elseif($orderstatus==20){
                $where .= " and islottery=1 and status<2 ";
            }elseif($orderstatus==22){
                $where .= " and islottery=1 and status=2 ";
            }
            $sql = "select status,money,ordernum,oid,gname as order_gname,goodsimg as order_pic,bname as order_bname,islottery from ".tablename('mzhk_sun_hyorder')."".$where." order by oid desc limit ".$pageindex.",".$pagesize;
        }else{
            $where = " where o.uniacid='".$_W['uniacid']."' AND o.openid='".$openid."' ";
            if($orderstatus>0){
                $where .= " and o.status=:status ";
                $fetchdata[":status"] = $orderstatus;
            }
            $sql = 'select o.status,o.money,o.ordernum,o.id,o.gname as order_gname,o.goodsimg as order_pic,o.bname as order_bname,g.gname,g.pic,b.bname from '.tablename('mzhk_sun_cardorder').' as o left join '.tablename('mzhk_sun_goods').' as g on o.gid=g.gid left join '.tablename('mzhk_sun_brand').' as b on g.bid=b.bid '.$where." order by o.id desc limit ".$pageindex.",".$pagesize;
        }
        
        $data = pdo_fetchall($sql,$fetchdata);
        if($data){
            echo json_encode($data);
        }else{
            echo 2;
        }

    }

    
    public function doPageAddhyOrder(){
        global $_GPC, $_W;
        $gid=$_GPC['id'];
        $uniacid = $_W['uniacid'];
        $openid = $_GPC['openid'];
        $res=pdo_get('mzhk_sun_goods',array('gid'=>$gid,'uniacid'=>$uniacid),array("gid","gname","num","antime","pic","bid","bname","astime","islottery","lotterytype","lotterynum","expirationtime"));
        if(strtotime($res["astime"])>time()){
            return $this->result(1, '该商品不在活动时间内！！！', array());
        }elseif(strtotime($res["antime"])<time()){
            return $this->result(1, '该商品活动已结束！！！', array());
        }elseif($res["islottery"]==1){
            return $this->result(1, '该商品活动已结束！！！', array());
        }

        
        $order=pdo_get('mzhk_sun_hyorder',array('gid'=>$gid,'openid'=>$openid,'uniacid'=>$uniacid),array("oid"));
        if($order){
            return $this->result(1,'你已经参与过该活动了，请等待抽奖', array());
        }

        $data['sincetype']=$_GPC['sincetype'];
        $data['cityName']=$_GPC['cityName'];
        $data['detailInfo']=$_GPC['detailInfo'];
        $data['time']=$_GPC['time'];
        $data['uremark']=$_GPC['uremark'];
        $data['countyName']=$_GPC['countyName'];
        $data['provinceName']=$_GPC['provinceName'];
        $data['name']=$_GPC['name'];
        $data['orderNum'] = time() . mt_rand(10000, 99999);
        $data['telNumber']=$_GPC['telNumber'];
        $data['openid']=$openid;
        $data['uniacid']=$uniacid;
        $data['addtime']=time();
        $data['money']=$_GPC['price'];
        $data['gid']=$gid;
        $data['gname']=$res["gname"];
        $data['bid']=$res["bid"];
        $data['bname']=$res["bname"];
        $data['goodsimg']=$res["pic"];
        $data['expirationtime']=$res["expirationtime"];
        $data['paytype']=intval($_GPC["paytype"]);        

        $data['num']=1;
        $data['deliveryfee']=$_GPC['deliveryfee'];

        $re=pdo_insert('mzhk_sun_hyorder',$data);
        
        $orderid=pdo_insertid();
        if(intval($orderid)<=0){
            return $this->result(1,'数据提交失败，请重新提交,0002', array());
        }

        
        if($res["lotterytype"]==1){
            $total=pdo_fetchcolumn("select count(oid) as wname from ".tablename('mzhk_sun_hyorder')." where uniacid=".$uniacid." and gid=".$gid." ");
            if($total>=$res["lotterynum"]){
                $lotterynum = intval($res["num"]);
                
                $order=pdo_getall("mzhk_sun_hyorder",array('uniacid'=>$uniacid,'gid'=>$gid,"islottery"=>0),array("oid"));
                if($order){
                    $order_arr = $order_id_ar = array();
                    foreach($order as $key => $val){
                        $order_arr[] = $val["oid"];
                    }
                    $checkorderid = array_rand($order_arr,$lotterynum);
                    if(is_array($checkorderid)){
                        foreach($checkorderid as $k => $v){
                            $order_id_arr[] = $order_arr[$v];
                        }
                    }else{
                        $order_id_arr = $order_arr[$checkorderid];
                    }
                    if($order_id_arr){
                        $ores = pdo_update('mzhk_sun_hyorder',array('islottery'=>1,"time"=>time()),array('oid'=>$order_id_arr,'uniacid'=>$uniacid));
                        $gres = pdo_update('mzhk_sun_goods',array('islottery'=>1),array('gid'=>$gid,'uniacid'=>$uniacid));
                        if($ores){
                            $res=pdo_update('mzhk_sun_hyorder',array('islottery'=>2,"time"=>time()),array('islottery'=>0,'uniacid'=>$uniacid));
                            
                            
                            $lorreryorder = pdo_getall('mzhk_sun_hyorder',array('uniacid'=>$uniacid,'gid'=>$gid),array("openid","gname","bname","addtime","bid"));
                            if($lorreryorder){
                                $access_token = $this->getaccess_token();
                                foreach($lorreryorder as $k => $v){
                                    $this->sendtelmessage($access_token,$v['openid'],$v['gname'],$gid,$v['addtime']);
                                }
                            }
                        }
                    }
                }
            }
        }

        
        pdo_update('mzhk_sun_goods',array("buynum +="=>1),array('gid'=>$gid,'uniacid'=>$uniacid));
        echo  $orderid;
    }

    ###################################################################################################
    
    
    public function doPageKJactive(){
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $new = date("Y-m-d H:i:s");

        $pagesize = 5;
        $pageindex = intval($_GPC['page'])*$pagesize;

        
        $sql = "select g.* from ".tablename('mzhk_sun_goods')." as g left join ".tablename('mzhk_sun_brand')." as b on g.bid=b.bid where g.lid = 2 and g.status = 2 and g.uniacid=".$uniacid." and g.antime>='".$new."' and g.is_kjopen=1 and g.isshelf=1 and b.status=2 order by g.sort asc,g.gid desc limit ".$pageindex.",".$pagesize;
        
        $data = pdo_fetchall($sql);
        if($data){
            foreach ($data as $k=>$v){
                $data[$k]['selftime']=date('Y-m-d',strtotime($v["selftime"]));
                $data[$k]['astime']=date('Y-m-d',strtotime($v["astime"]));
                $data[$k]['antime']=date('Y-m-d',strtotime($v["antime"]));
                $data[$k]["code_img"] = "";
            }
            echo json_encode($data);
        }else{
            echo 2;
        }
    }

    
    public function doPageKJClose(){
        global $_GPC, $_W;

        $uniacid = $_W['uniacid'];
        $new = date("Y-m-d H:i:s");

        $pagesize = 5;
        $pageindex = intval($_GPC['page'])*$pagesize;

        
        $sql = "select * from ".tablename('mzhk_sun_goods')." where lid = 2 and status = 2 and uniacid=".$uniacid." and (antime<='".$new."' or isshelf=0) order by gid desc limit ".$pageindex.",".$pagesize;
        
        $data = pdo_fetchall($sql);
        if($data){
            foreach ($data as $k=>$v){
                $data[$k]['selftime']=date('Y-m-d',strtotime($v["selftime"]));
                $data[$k]['astime']=date('Y-m-d',strtotime($v["astime"]));
                $data[$k]['antime']=date('Y-m-d',strtotime($v["antime"]));
                $data[$k]["code_img"] = "";
            }
            echo json_encode($data);
        }else{
            echo 2;
        }

    }

    
    public function doPageKJdetails(){
        global $_GPC, $_W;
        $data = pdo_get('mzhk_sun_goods',array('uniacid'=>$_W['uniacid'],'gid'=>$_GPC['id']));
        $data['enftime'] = strtotime($data['antime'])*1000;
        $data['lb_imgs'] = explode(',',$data['lb_imgs']);
        $data['clock'] = "";
        $data["code_img"] = "";
        if($data['is_kjopen']==1){
            $time = time();
            
            
            
            
            $brand = pdo_get('mzhk_sun_brand',array('uniacid'=>$_W['uniacid'],'bid'=>$data['bid']),array("longitude","latitude","address","phone"));
            $data['longitude'] = $brand["longitude"];
            $data['latitude'] = $brand["latitude"];
            $data['address'] = $brand["address"];
            $data['phone'] = $brand["phone"];
            echo json_encode($data);
        }else{
            return $this->result(1,'砍价活动已经关闭', array());
        }
    }

    
    
    public function doPageZKanjia(){
        global $_GPC, $_W;
        $gid = intval($_GPC["gid"]);
        $openid = $_GPC["openid"];
        $uniacid = $_W["uniacid"];
        $iscut = true;
        
        
        $sql='select id as cs_id,gid,nowprice,lavenum,allnum,lowprice,shopprice from '.tablename('mzhk_sun_cutself').' where uniacid='.$_W['uniacid'].' and openid='."'$openid'".' and gid ='.$gid.' and is_buy = 0 order by id desc';
        $cutself = pdo_fetch($sql);

        if($cutself){
            
            $now = date("Y-m-d H:i:s");
            $sql_g = "select gid,cutnum,kjprice,shopprice from ".tablename('mzhk_sun_goods')." where astime<='".$now."' and antime>='".$now."' and is_kjopen=1 and isshelf=1 and gid=".$gid;
            $data_g = pdo_fetch($sql_g);
            if($data_g){
                if($data_g["cutnum"]!=$cutself["allnum"] || $data_g["shopprice"]!=$cutself["shopprice"] || $data_g["kjprice"]!=$cutself["lowprice"]){
                    
                }else{
                    return $this->result(1,'你已经为自己砍过了', array());
                }
            }else{
                return $this->result(1,'当前商品不在砍价活动时间', array());
            }
        }

        
        $goods = pdo_get('mzhk_sun_goods',array('uniacid'=>$uniacid,'gid'=>$gid),array("cutnum","kjprice","shopprice","astime","antime","is_kjopen"));
        if($goods["is_kjopen"]==1){
            $time = time();
            if(strtotime($goods['astime'])<=$time && strtotime($goods['antime'])>=$time){ 
                
                $total=$goods['shopprice'] - $goods['kjprice']; 
                $cutnum = $goods['cutnum']; 
                if($cutnum>1){
                    $min=0.01;     
                    $safe_total = ($total  - ($cutnum-1) * $min)/($cutnum-1);
                    $cutmoney = mt_rand($min * 100, $safe_total * 100) / 100;
                    $cutmoney = sprintf("%.2f", $cutmoney);   
                    $lavenum = $cutnum-1;
                }else{
                    $cutmoney = $total;   
                    $lavenum = 0;
                }
                $nowprice = $goods['shopprice'] - $cutmoney;
                
                $user = pdo_get('mzhk_sun_user',array('uniacid'=>$uniacid,'openid'=>$openid),array("img","name"));
                
                $data_self = array();
                $data_self["openid"] = $openid;
                $data_self["username"] = $user["name"];
                $data_self["uniacid"] = $uniacid;
                $data_self["gid"] = $gid;
                $data_self["shopprice"] = $goods["shopprice"];
                $data_self["nowprice"] = $nowprice;
                $data_self["lavenum"] = $lavenum;
                $data_self["allnum"] = $cutnum;
                $data_self["lowprice"] = $goods['kjprice'];
                $data_self["addtime"] = time();
                $res_self = pdo_insert('mzhk_sun_cutself',$data_self);
                $selfid=pdo_insertid();

                if($res_self){
                    $data_help = array();
                    
                    $data_help["openid"] = $openid;
                    $data_help["uniacid"] = $uniacid;
                    $data_help["username"] = $user["name"];
                    $data_help["cs_id"] = $selfid;
                    $data_help["isself"] = 1;
                    $data_help["gid"] = $gid;
                    $data_help["nowprice"] = $nowprice;
                    $data_help["cutprice"] = $cutmoney;
                    $data_help["addtime"] = time();
                    $res_help = pdo_insert('mzhk_sun_cuthelp',$data_help);
                    $helpid=pdo_insertid();
                    if($res_help){
                        pdo_update('mzhk_sun_goods',array("buynum +="=>1),array('gid'=>$gid,'uniacid'=>$uniacid));

                        $cutdata["nowprice"] = sprintf("%.2f", $nowprice);
                        $cutdata["allcutprice"] = sprintf("%.2f", $cutmoney);
                        $cutdata["lavenum"] = $lavenum;
                        $cutdata["cutprice"] = sprintf("%.2f", $cutmoney);
                        $cutdata["cs_id"] = $selfid;
                        $cutdata["gid"] = $gid;
                        $cutdata["cancutprice"] = sprintf("%.2f", $total);
                        $cutdata["laveprice"] = sprintf("%.2f", ($nowprice*100-$goods['kjprice']*100)/100);
                        $cutdata["isself"] = 1;
                        echo json_encode($cutdata);
                    }else{
                        return $this->result(1,'网络错误，砍价失败，请重试!', array());
                    }
                }else{
                    return $this->result(1,'网络错误，砍价失败，请重试', array());
                }
            }else{
                return $this->result(1,'当前砍价不在活动时间内', array());
            }
        }else{
            return $this->result(1,'该砍价活动已经结束', array());
        }
    }

    public function doPageISkanjia(){
        global $_GPC, $_W;
        
        $openid =$_GPC['openid'];
        $gid = intval($_GPC['id']);
        $sql='select id as cs_id,gid,nowprice,lavenum,allnum,lowprice,shopprice from '.tablename('mzhk_sun_cutself').' where uniacid='.$_W['uniacid'].' and openid='."'$openid'".' and gid ='.$gid.' and is_buy = 0 order by id desc';
        $data = pdo_fetch($sql);
        
        $now = date("Y-m-d H:i:s");
        $sql_g = "select gid,cutnum,kjprice,shopprice from ".tablename('mzhk_sun_goods')." where astime<='".$now."' and antime>='".$now."' and is_kjopen=1 and isshelf=1 and gid=".$gid;
        $data_g = pdo_fetch($sql_g);
        if($data_g){
            if($data_g["cutnum"]!=$data["allnum"] || $data_g["shopprice"]!=$data["shopprice"] || $data_g["kjprice"]!=$data["lowprice"]){
                $data['status']=0;
            }else{
                if($data){
                    
                    $sql='select u.img from '.tablename('mzhk_sun_cuthelp').' as h left join '.tablename('mzhk_sun_user').' as u on h.openid=u.openid where h.uniacid='.$_W['uniacid'].' and h.gid ='.$gid.' and h.cs_id = '.$data["cs_id"].' order by h.id desc limit 4 ';
                    $data_helpuser = pdo_fetchall($sql);
                    $data["helpuser"] = $data_helpuser;
                    $data["allcutprice"] = sprintf("%.2f", $data['shopprice'] - $data['nowprice']);
                    $data["laveprice"] = sprintf("%.2f", $data['nowprice'] - $data['lowprice']);
                    $data["cancutprice"] = sprintf("%.2f", $data['shopprice'] - $data['lowprice']);
                    $data['status']=1;
                }else{
                    $data['status']=0;
                }
            }
        }else{
            $data['status']=0;
        }

        echo json_encode($data);
    }

    

    
    public function doPageHelp(){
        global $_GPC, $_W;
        $openid = $_GPC['openid'];
        $cs_id = intval($_GPC['cs_id']);
        $gid = intval($_GPC['gid']);
        $uniacid = $_W["uniacid"];

        
        $cutself = pdo_get('mzhk_sun_cutself',array('uniacid'=>$uniacid,'id'=>$cs_id));
        if($cutself){
            
            if($cutself["openid"]==$openid){
                return $this->result(1,'你已经为自己砍过价了！', array());
            }
            
            if($cutself["is_buy"]==1){
                return $this->result(1,'你的好友已经下单了，无法再砍了！', array());
            }
            
            $cuthelp = pdo_get('mzhk_sun_cuthelp',array('uniacid'=>$uniacid,'cs_id'=>$cs_id,'openid'=>$openid));
            if($cuthelp){
                return $this->result(1,'你已经帮忙砍过了', array());
            }
            
            $goods = pdo_get('mzhk_sun_goods',array('uniacid'=>$uniacid,'gid'=>$gid),array("cutnum","kjprice","shopprice","astime","antime","is_kjopen"));
            if($goods["is_kjopen"]==1){
                $time = time();
                if(strtotime($goods['astime'])<=$time && strtotime($goods['antime'])>=$time){ 
                    if($cutself['nowprice'] <= $goods['kjprice']){
                        return $this->result(1,'已经砍到底了，无法再砍了', array());
                    }
                    
                    $total=$cutself['nowprice'] - $goods['kjprice']; 
                    $cutnum = $cutself['lavenum']; 
                    if($cutnum>1){
                        $min=0.01;     
                        $safe_total = ($total  - ($cutnum-1) * $min)/($cutnum-1);
                        $cutmoney = mt_rand($min * 100, $safe_total * 100) / 100;
                        $cutmoney = sprintf("%.2f", $cutmoney);   
                        $lavenum = $cutnum-1;
                    }else{
                        $cutmoney = $total;   
                        $lavenum = 0;
                    }
                    $nowprice = $cutself['nowprice'] - $cutmoney;

                    
                    $data_self = array();
                    $data_self["nowprice"] = $nowprice;
                    $data_self["lavenum"] = $lavenum;
                    $res_self = pdo_update('mzhk_sun_cutself', $data_self, array('id' => $cutself["id"], 'uniacid' => $uniacid));

                    $selfid=$cutself["id"];
                    if($res_self){
                        
                        $user = pdo_get('mzhk_sun_user',array('uniacid'=>$uniacid,'openid'=>$openid),array("img","name"));
                        $data_help = array();
                        
                        $data_help["openid"] = $openid;
                        $data_help["uniacid"] = $uniacid;
                        $data_help["username"] = $user["name"];
                        $data_help["cs_id"] = $selfid;
                        $data_help["isself"] = 0;
                        $data_help["gid"] = $gid;
                        $data_help["nowprice"] = $nowprice;
                        $data_help["cutprice"] = $cutmoney;
                        $data_help["addtime"] = time();
                        $res_help = pdo_insert('mzhk_sun_cuthelp',$data_help);
                        $helpid=pdo_insertid();
                        if($res_help){
                            $cutdata["help"] = array("img"=>$user["img"],"cutprice"=>$cutmoney);
                            $cutdata["cs_id"] = $selfid;
                            $cutdata["nowprice"] = $nowprice;
                            
                            
                            $cutdata["cutprice"] = $cutmoney;
                            
                            
                            
                            
                            
                            echo json_encode($cutdata);
                        }else{
                            return $this->result(1,'网络错误，砍价失败，请重试!', array());
                        }
                    }else{
                        return $this->result(1,'网络错误，砍价失败，请重试', array());
                    }
                }else{
                    return $this->result(1,'当前砍价不在活动时间内', array());
                }
            }else{
                return $this->result(1,'该砍价活动已经结束', array());
            }
        }else{
            return $this->result(1,'参数错误！！！', array());
        }

    }


    
    public function doPageIsHelp(){
        global $_GPC,$_W;
        $openid = $_GPC['openid'];
        $cs_id = intval($_GPC['cs_id']);
        $gid = intval($_GPC['id']);
        $uniacid = $_W['uniacid'];
        
        $sql='select id as cs_id,gid,nowprice,lavenum,allnum,lowprice,shopprice,openid from '.tablename('mzhk_sun_cutself').' where uniacid='.$uniacid.' and id = '.$cs_id.' ';
        $data = pdo_fetch($sql);
        if($data){
            
            $user = pdo_get('mzhk_sun_user',array('openid'=>$data['openid'],'uniacid'=>$uniacid),array("name",'img'));
            $data['hostname']=$user['name'];
            $data['hostimg']=$user['img'];
            $data_help = pdo_get('mzhk_sun_cuthelp',array('openid'=>$openid,'gid'=>$gid,'cs_id'=>$cs_id,'uniacid'=>$uniacid));
            if($data_help){
                $data['status']=1;
            }else{
                $data['status']=0;
            }
            $sql='select u.img,h.cutprice,u.name from '.tablename('mzhk_sun_cuthelp').' as h left join '.tablename('mzhk_sun_user').' as u on h.openid=u.openid where h.uniacid='.$uniacid.' and h.gid ='.$gid.' and h.cs_id = '.$cs_id.' order by h.id desc limit 15 ';
            $data_helpuser = pdo_fetchall($sql);
            $data["helpuser"] = $data_helpuser;
            $data["allcutprice"] = $data['shopprice'] - $data['nowprice'];
            $data["laveprice"] = $data['nowprice'] - $data['lowprice'];
            $data["cancutprice"] = $data['shopprice'] - $data['lowprice'];

            echo json_encode($data);
        }else{
            return $this->result(1,'参数错误！！！', array());
        }
        
    }


    #################################################################################################
    
    
    public function doPageCforder(){
        global $_GPC, $_W;
        $gid = intval($_GPC['id']);
        $uniacid = $_W['uniacid'];
        $openid = $_GPC["openid"];
        $sql = "select b.img,a.bname,b.phone,a.pic,a.gname,a.ship_type,a.ship_delivery_fee,a.ship_delivery_time,a.ship_delivery_way,a.ship_express_fee,b.address,a.gid,b.deliveryfee,b.deliverytime,b.deliveryaway,a.shopprice,a.lid,a.kjprice,a.limitnum,a.astime,a.antime from".tablename('mzhk_sun_goods').'a left join'.tablename('mzhk_sun_brand').'b on b.bid=a.bid where a.uniacid='.$_W['uniacid'].' and a.gid='.$gid;
        $data = pdo_fetch($sql);
        $data['ship_type'] = $data["ship_type"]?explode(",",$data["ship_type"]):array(1);
        
        if($data["lid"]==2){
            $sql="select nowprice,lowprice from ".tablename('mzhk_sun_cutself')." where uniacid='".$uniacid."' and gid ='".$gid."' and openid = '".$openid."' and is_buy=0 order by id desc ";
            $data_cutself = pdo_fetch($sql);
            if($data["kjprice"]==$data_cutself["nowprice"]){
                $data['nowprice'] = $data_cutself["nowprice"];
            }else{
                $data['nowprice'] = 0;
            }
        }elseif($data["lid"]==1 || $data["lid"]==5){
            
            if($data["limitnum"]>0){
                $total = 0;
                if($data["lid"]==1){
                    $total=pdo_fetch("select sum(num) as num from ".tablename('mzhk_sun_order')." where addtime between ".strtotime($data["astime"])." and ".strtotime($data["antime"])." and status > 1 and uniacid='".$uniacid."' and gid='".$gid."' AND openid='".$openid."' ");
                }else{
                    $total=pdo_fetch("select sum(num) as num from ".tablename('mzhk_sun_qgorder')." where addtime between ".strtotime($data["astime"])." and ".strtotime($data["antime"])." and status > 1 and uniacid='".$uniacid."' and gid='".$gid."' AND openid='".$openid."' ");
                }
                $data['total'] = intval($total["num"]);
            }
        }
        
        $sql = "select telNumber from".tablename('mzhk_sun_kjorder').' where uniacid='.$_W['uniacid']." and openid='".$_GPC['openid']."' and telNumber is not null order by addtime desc ";
        $order = pdo_fetch($sql);
        $data['telnumber']="";
        if($order){
            $data['telnumber'] = $order["telNumber"];
        }
        if(empty($data['telnumber'])){
            $sql = "select telnumber from".tablename('mzhk_sun_cardorder').' where uniacid='.$_W['uniacid']." and openid='".$_GPC['openid']."' and telnumber is not null order by addtime desc ";
            $order = pdo_fetch($sql);
            if($order){
                $data['telnumber'] = $order["telnumber"];
            }
            if(empty($data['telnumber'])){
                $sql = "select telNumber from".tablename('mzhk_sun_qgorder').' where uniacid='.$_W['uniacid']." and openid='".$_GPC['openid']."' and telNumber is not null order by addtime desc ";
                $order = pdo_fetch($sql);
                if($order){
                    $data['telnumber'] = $order["telNumber"];
                }
                if(empty($data['telnumber'])){
                    $sql = "select telnumber from".tablename('mzhk_sun_ptgroups').' where uniacid='.$_W['uniacid']." and openid='".$_GPC['openid']."' and telnumber is not null order by addtime desc ";
                    $order = pdo_fetch($sql);
                    if($order){
                        $data['telnumber'] = $order["telnumber"];
                    }
                    if(empty($data['telnumber'])){
                        $sql = "select telNumber from".tablename('mzhk_sun_hyorder').' where uniacid='.$_W['uniacid']." and openid='".$_GPC['openid']."' and telNumber is not null order by addtime desc ";
                        $order = pdo_fetch($sql);
                        if($order){
                            $data['telnumber'] = $order["telNumber"];
                        }
                    }
                    
                }
            }
        }
        
        
        echo json_encode($data);
    }
    
    public function doPageAddkjOrder(){
        global $_GPC, $_W;
        $gid=$_GPC['id'];
        $uniacid = $_W['uniacid'];
        $openid = $_GPC['openid'];
        $typeid = intval($_GPC['typeid']);
        $res=pdo_get('mzhk_sun_goods',array('gid'=>$gid,'uniacid'=>$uniacid),array("gid","gname","num","astime","antime","pic","bid","bname","stocktype","expirationtime"));
        
        if($res["num"]<=0){
            return $this->result(1, '该商品已经没货了！！！', array());
        }elseif(strtotime($res["astime"])>time()){
            return $this->result(1, '该商品不在活动时间内！！！', array());
        }elseif(strtotime($res["antime"])<time()){
            return $this->result(1, '该商品活动已结束！！！', array());
        }

        $data['sincetype']=$_GPC['sincetype'];
        $data['cityName']=$_GPC['cityName'];
        $data['detailInfo']=$_GPC['detailInfo'];
        $data['time']=$_GPC['time'];
        $data['uremark']=$_GPC['uremark'];
        $data['countyName']=$_GPC['countyName'];
        $data['provinceName']=$_GPC['provinceName'];
        $data['name']=$_GPC['name'];
        $data['orderNum'] = time() . mt_rand(10000, 99999);
        $data['telNumber']=$_GPC['telNumber'];
        $data['openid']=$openid;
        $data['uniacid']=$uniacid;
        $data['addtime']=time();
        $data['money']=$_GPC['price'];
        $data['gid']=$gid;
        $data['gname']=$res["gname"];
        $data['bid']=$res["bid"];
        $data['bname']=$res["bname"];
        $data['goodsimg']=$res["pic"];
        $data['expirationtime']=$res["expirationtime"];
        $data['paytype']=intval($_GPC["paytype"]);

        $data['status']=2;
        $data['num']=1;
        $data['deliveryfee']=$_GPC['deliveryfee'];
        $re=pdo_insert('mzhk_sun_kjorder',$data);

        $orderid=pdo_insertid();

        if(intval($orderid)<=0){
            return $this->result(1,'数据提交失败，请重新提交,0002', array());
        }
        
        if($res["num"]>0){
            if($res["stocktype"]==0){
                pdo_update('mzhk_sun_goods',array("num -="=>1),array('gid'=>$gid,'uniacid'=>$uniacid));
            }
        }

        
        pdo_update('mzhk_sun_cutself',array("is_buy"=>1),array('gid'=>$gid,'openid'=>$openid,'uniacid'=>$uniacid));

        echo  $orderid;
    }


    public function doPagePaykjOrder(){
        global $_W, $_GPC;
        $openid = $_GPC["openid"];
        $uniacid = $_W['uniacid'];
        if($openid){
            $res=pdo_get('mzhk_sun_kjorder',array('oid'=>$_GPC['order_id'],'uniacid'=>$uniacid),array("bid","gid","oid","orderNum","status"));
            if($res["bid"]>0){
                $this->SendSms($res["bid"],0,$res["orderNum"]);
            }
            if($res["status"]==2){
                $datas['status'] = 3;
                $datas['paytime'] = time();
                $orderinfo = pdo_update('mzhk_sun_kjorder', $datas, array('oid' => $_GPC['order_id'], 'uniacid' => $_W['uniacid']));
                if($orderinfo){
                    $goods=pdo_get('mzhk_sun_goods',array('gid'=>$res['gid'],'uniacid'=>$uniacid),array("num","stocktype"));
                    if($goods["num"]>0){
                        if($goods["stocktype"]==1){
                            pdo_update('mzhk_sun_goods',array("num -="=>1),array('gid'=>$res['gid'],'uniacid'=>$uniacid));
                        }
                    }
                    echo "success";
                }else{
                    echo "error0001";
                }
            }else{
                echo "error0002";
            }
        }else{
            echo "error0003";
        }
    }

    
    public function doPagegetCutOrder(){
        global $_GPC, $_W;
        $orderstatus = intval($_GPC["orderstatus"]);
        $openid = $_GPC["openid"];

        $pagesize = 5;
        $pageindex = intval($_GPC['page'])*$pagesize;

        $where = " where o.uniacid='".$_W['uniacid']."' AND o.openid='".$openid."' ";
        if($orderstatus==3){
            $where .= " and o.status=:status and (o.isrefund=0 or o.isrefund=3) ";
            $fetchdata[":status"] = $orderstatus;
        }elseif($orderstatus==5){
            $where .= " and (o.status=:status or o.isrefund>0) ";
            $fetchdata[":status"] = $orderstatus;
        }elseif($orderstatus==0){

        }else{
            $where .= " and o.status=:status ";
            $fetchdata[":status"] = $orderstatus;
        }
        $sql = 'select o.status,o.isrefund,o.num,o.money,o.oid,o.gname as order_gname,o.goodsimg as order_pic,o.bname as order_bname,g.gname,g.pic,b.bname from '.tablename('mzhk_sun_kjorder').' as o left join '.tablename('mzhk_sun_goods').' as g on o.gid=g.gid left join '.tablename('mzhk_sun_brand').' as b on g.bid=b.bid '.$where." order by o.oid desc limit ".$pageindex.",".$pagesize;
        $data = pdo_fetchall($sql,$fetchdata);
        if($data){
            echo json_encode($data);
        }else{
            echo 2;
        }

    }

##################################################################################################
    
    
    public function doPageShop(){
        global $_GPC, $_W;
        $typeid = intval($_GPC["typeid"]);
        $lat = floatval($_GPC["lat"]);
        $lon = floatval($_GPC["lon"]);
        $store_id = intval($_GPC["store_id"]);

        $pagesize = 15;
        $pageindex = intval($_GPC['page'])*$pagesize;
        $where = " where uniacid='".$_W['uniacid']."' AND status=2  ";
        if($store_id>0){
            $where .= " AND store_id='".$store_id."' ";
        }
        
        $bname = $_GPC["bname"];
        if(!empty($bname)){
            $where .= " AND bname like'%".$bname."%' ";
            $pagesize = 30;
        }

        if($typeid==1){
            
            
            
            if(empty($lat) && empty($lon)){
                echo 2;
                exit;
            }
            
            $sql = 'select *,SQRT(POW(111.2 * (latitude - '.$lat.'), 2) + POW(111.2 * ('.$lon.' - longitude) * COS(latitude / 57.3), 2)) AS distance from '.tablename('mzhk_sun_brand').' '.$where." order by distance asc limit ".$pageindex.",".$pagesize;
        }else{
            $sql = 'select * from '.tablename('mzhk_sun_brand').' '.$where." order by istop desc,sort asc,paytime desc limit ".$pageindex.",".$pagesize;
        }

        $data = pdo_fetchall($sql,$fetchdata);
        if($data){
            $ids = array();
            
            if(is_array($data) && sizeof($data)>0){
                foreach ($data as $v) {
                    $ids[] = $v['bid'];
                }
            }
            $ids = array_unique($ids);
            $ids = implode(',', $ids);
            $now = date('Y-m-d H:i:s');
            $sql = 'SELECT * FROM ' . tablename('mzhk_sun_coupon'). " WHERE `bid` IN ({$ids}) AND `uniacid` = '{$_W['uniacid']}' AND `astime` <= '{$now}' AND `antime` >= '{$now}' AND `state` = '1';";
            $coupons = pdo_fetchall($sql);
            
            $sql = 'SELECT * FROM ' . tablename('mzhk_sun_user_coupon'). " WHERE `uid` = '{$_GPC['openid']}' AND `uniacid` = '{$_W['uniacid']}' ";
            $userCoupons = pdo_fetchall($sql);
            $userCouponIds = array();   
            foreach ($userCoupons as $v) {
                $userCouponIds[] = $v['cid'];
            }

            if(is_array($data) && sizeof($data)>0){
                foreach ($data as $key => $val) {
                    $tmp = array();
                    foreach($coupons as $k => $v){
                        if ($v['bid'] == $val['bid']) {
                            $v["astime"] = date('Y-m-d',strtotime($v['astime']));
                            $v["antime"] = date('Y-m-d',strtotime($v['antime']));
                            $v['is_has'] = in_array($v['id'], $userCouponIds) ? true : false;
                            $data[$key]['coupons'][] = $v;
                        }
                    }
                    if($val["distance"]){
                        $data[$key]['distance'] = sprintf("%.2f",$val["distance"]);
                    }
                }
            }
            echo json_encode($data);
        }else{
            echo 2;
        }
    }
       
    public function doPageCounpadd(){
        global $_GPC, $_W;

        $id = intval($_GPC['id']);
        $uniacid = $_W['uniacid'];
        $openid = $_GPC['openid'];
        $res=pdo_get('mzhk_sun_coupon',array('id'=>$_GPC['id'],'uniacid'=>$uniacid));
        $data['uid'] = $openid;
        $data['cid'] = $id;
        $data['type'] = $res['type'];
        if($res['val']){
            $data['val'] = $res['val'];
        }else{
            $data['val'] = $res['mj'];
        }if($res['vab']){
            $data['vab'] = $res['vab'];
        }else{
            $data['vab'] = $res['md'];
        }
        $data['uniacid'] = $uniacid;
        $data['isused']=0;
        $data['createtime']=time();
        $data['limittime']=strtotime("+ ".intval($res["expiryDate"])." day");
        $userid = $openid;
        $datas['allowance'] = $res['allowance']>0?$res['allowance']-1:0;
        $rul="select * from".tablename('mzhk_sun_user_coupon'). " where cid=".$id." and uid="."'$userid'"." and uniacid = ".$uniacid;
        $retule=pdo_fetch($rul);
        if($retule!=false){
            $hh['status']=2;
        }else{
            $res2=pdo_insert('mzhk_sun_user_coupon',$data);
            $o = pdo_update('mzhk_sun_coupon',$datas,array('uniacid'=>$uniacid,'id'=>$id));
            $hh['status']=1;
        }

        echo json_encode($hh);
    }
    
    public function doPageshopXq(){

        global $_GPC, $_W;

        $data = pdo_get('mzhk_sun_brand', array('uniacid' => $_W['uniacid'],'bid'=>$_GPC['id']));
        if($data['logo']){
            $data['logo']=explode(',',$data['logo']);
        }
         
        $ids = $data['bid'];
        $now = date('Y-m-d H:i:s');
        $sql = 'SELECT * FROM ' . tablename('mzhk_sun_coupon'). " WHERE `bid` = '{$ids}' AND `uniacid` = '{$_W['uniacid']}' AND astime<='{$now}' AND `antime` >= '{$now}' AND `state` = '1';";
        $coupons = pdo_fetchall($sql);
        
        $sql = 'SELECT cid FROM ' . tablename('mzhk_sun_user_coupon'). " WHERE `uid` = '{$_GPC['openid']}';";
        $userCoupons = pdo_fetchall($sql);
        $userCouponIds = array();
        foreach ($userCoupons as $v) {
            $userCouponIds[] = $v['cid'];
        }

        if(is_array($coupons) && sizeof($coupons)>0){
            foreach($coupons as $k => $v){
                $coupons[$k]["astime"] = date('Y-m-d',strtotime($v['astime']));
                $coupons[$k]["antime"] = date('Y-m-d',strtotime($v['antime']));
                $coupons[$k]['is_has'] = in_array($v['id'], $userCouponIds) ? true : false;
            }
        }
        $tmp = $coupons;
        $data['coupons'] = $tmp;

        $newtime = date('Y-m-d H:i:s',time());
        
        $sql = 'SELECT gid,gname,astime,antime,is_vip,buynum,lid,pic FROM ' . tablename('mzhk_sun_goods'). " WHERE `bid` = '{$_GPC['id']}' and uniacid='{$_W['uniacid']}' and status=2 and ((lid=2 and is_kjopen=1) or (lid=3 and is_ptopen=1) or (lid=4 and is_jkopen=1) or (lid=5 and is_qgopen=1) or (lid=6 and is_hyopen=1) or lid=1) and isshelf=1 and num>0 and antime>='{$newtime}' and astime<='{$newtime}' order by sort asc,gid desc;";
        $goods = pdo_fetchall($sql);
        if($goods){
            foreach($goods as $k => $v){
                $v["astime"] =  date('Y-m-d',strtotime($v['astime']));
                $v["antime"] =  date('Y-m-d',strtotime($v['antime']));
                $data['goods'][$k] = $v;
            }
        }else{
            $data['goods'] = array();
        }

        
        echo json_encode($data);

    }

    
    public function doPagewelfare(){
        global $_GPC, $_W;
        $id=$_GPC['id'];
        $sql = "select b.bid,b.bname,b.starttime,b.endtime,b.phone,b.address,b.longitude,b.latitude,c.title,c.astime,c.antime,c.allowance,c.total,c.img,c.content,c.isvip,c.id from ".tablename('mzhk_sun_coupon')." as c LEFT JOIN ".tablename('mzhk_sun_brand').' as b on c.bid=b.bid where c.uniacid='.$_W['uniacid'].' and c.id='."'$id'";
        $data=pdo_fetch($sql);
        echo json_encode( $data);
    }

    
    public function doPageisLingqu(){
        global $_GPC, $_W;
        $id = intval($_GPC['id']);
        $openid = $_GPC['openid'];
        $datas ="select id from ".tablename('mzhk_sun_user_coupon')." where uniacid='".$_W['uniacid']."' AND uid='".$openid."' and cid='".$id."'";
        $res = pdo_fetch($datas);
        echo json_encode($res);
    }

    
    public function doPageGetUserCounpon(){
        global $_GPC, $_W;
        $orderstatus = intval($_GPC["status"]);
        $openid = $_GPC["openid"];

        $pagesize = 5;
        $pageindex = intval($_GPC['page'])*$pagesize;

        $where = " where o.uniacid='".$_W['uniacid']."' AND o.uid='".$openid."' ";
        if($orderstatus==1){
            $where .= " and o.isUsed=:isUsed ";
            $fetchdata[":isUsed"] = 0;
        }elseif($orderstatus==2){
            $where .= " and o.isUsed=:isUsed ";
            $fetchdata[":isUsed"] = 1;
        }elseif($orderstatus==3){
            $where .= " and o.limitTime <= :limitTime ";
            $fetchdata[":limitTime"] = time();
        }
        $sql = 'select c.title,o.limitTime,o.isUsed,c.img,o.id,b.bname from '.tablename('mzhk_sun_user_coupon').' as o left join '.tablename('mzhk_sun_coupon').' as c on c.id=o.cid left join '.tablename('mzhk_sun_brand').' as b on c.bid=b.bid '.$where." and c.id is not null and b.bid is not null order by o.id desc limit ".$pageindex.",".$pagesize;

        $data = pdo_fetchall($sql,$fetchdata);
        foreach($data as $k => $v){
            $data[$k]["limitTime"] = date("Y-m-d H:i:s",$v["limitTime"]);
        }
        if($data){
            echo json_encode($data);
        }else{
            echo 2;
        }

    }



    ###################################################################################################
    

    
    public function doPageACbanner(){
        global $_GPC, $_W;
        $data = pdo_get('mzhk_sun_acbanner',array('uniacid'=>$_W['uniacid']));
        $data['logo'] = explode(',',$data['logo']);
        echo json_encode($data);
    }



    public function doPageUrl(){
        global $_GPC, $_W;
        echo $_W['attachurl'];
    }

    public function doPageUrl2(){
        global $_W, $_GPC;
        echo $_W['siteroot'];
    }
 
    public function doPageIsFree(){
        global $_W, $_GPC;
        $openid= $_GPC['openid'];
        $sql ='select a.*,b.title from'.tablename('mzhk_sun_user').'a LEFT JOIN '.tablename('mzhk_sun_vip').'b on b.id=a.viptype where a.uniacid='.$_W['uniacid'].' and a.openid='."'$openid'";
        $data=pdo_fetch($sql);
        echo json_encode($data);
    }
    
    public  function doPageSystem(){
        global $_W, $_GPC;
        $data = pdo_get('mzhk_sun_system',array('uniacid'=>$_W['uniacid']));
        $data["tab_navdata"] = unserialize($data["tab_navdata"]);
        $data["attachurl"] = $_W['attachurl'];
        echo json_encode($data);
    }

     
    public function doPageOrderarr() {
        global $_W, $_GPC;
        
        $order_id = intval($_GPC['order_id']);
        $out_trade_no = rand(10000,99999) . time().rand(10000,99999).$order_id;
        $openid=$_GPC['openid'];
        if(empty($openid)){
            return $this->result(1, '参数错误，00001！', array());
        }
        
        $ordertype = intval($_GPC["ordertype"]);
        if($order_id>0){
            if($ordertype==1){
                $is_lead = intval($_GPC["is_lead"]);
                $g_order_id = intval($_GPC["g_order_id"]);
                
                $orderinfo = pdo_get('mzhk_sun_ptorders',array('id'=>$order_id,'uniacid'=>$_W['uniacid']),array("gid","gname","buynum","neednum","is_ok","id","money","peoplenum"));
                if($is_lead!=1){
                    
                    if($orderinfo["buynum"]>=$orderinfo["neednum"] && $orderinfo["is_ok"]==1){
                        
                        $set_status = pdo_update('mzhk_sun_ptgroups',array("status"=>1), array('id' => $g_order_id, 'uniacid' => $_W['uniacid']));
                        return $this->result(1, '该团已满，请重新开团！！！', $emptydata);
                    }
                }
                
                pdo_update('mzhk_sun_ptgroups', array('out_trade_no' => $out_trade_no,"paytype"=>1), array('id' => $g_order_id));
            }elseif($ordertype==3){
                $orderinfo = pdo_get('mzhk_sun_cardorder', array('id' => $order_id,'uniacid' => $_W['uniacid']),array("money"));
                
                pdo_update('mzhk_sun_cardorder', array('out_trade_no' => $out_trade_no,"paytype"=>1), array('id' => $order_id));
            }elseif($ordertype==4){
                $orderinfo = pdo_get('mzhk_sun_order', array('oid' => $order_id,'uniacid' => $_W['uniacid']),array("money"));
                
                pdo_update('mzhk_sun_order', array('out_trade_no' => $out_trade_no,"paytype"=>1), array('oid' => $order_id));
            }elseif($ordertype==5){
                $orderinfo = pdo_get('mzhk_sun_kjorder', array('oid' => $order_id,'uniacid' => $_W['uniacid']),array("money"));
                
                pdo_update('mzhk_sun_kjorder', array('out_trade_no' => $out_trade_no,"paytype"=>1), array('oid' => $order_id));
            }elseif($ordertype==6){
                $orderinfo = pdo_get('mzhk_sun_hyorder', array('id' => $order_id,'uniacid' => $_W['uniacid']),array("money"));
                
                pdo_update('mzhk_sun_hyorder', array('out_trade_no' => $out_trade_no,"paytype"=>1), array('id' => $order_id));
            }else{
                $orderinfo = pdo_get('mzhk_sun_qgorder', array('oid' => $order_id,'uniacid' => $_W['uniacid']),array("money"));
                
                pdo_update('mzhk_sun_qgorder', array('out_trade_no' => $out_trade_no,"paytype"=>1), array('oid' => $order_id));
            }
        }
        $paytype =  intval($_GPC["paytype"]);
        if($paytype>0){
            if($paytype==1){
                $id = intval($_GPC["id"]);
                $recharge = pdo_get('mzhk_sun_rechargecard', array('id' => $id,'uniacid' => $_W['uniacid']),array("money","status"));
                
                if($recharge){
                    if($recharge["status"]==0){
                        return $this->result(1, '该充值卡已无效！', array());
                    }
                    $orderinfo["money"] = $recharge["money"];
                }else{
                    return $this->result(1, '参数错误，充值卡获取失败，00002！', array());
                }
            }elseif($paytype==2){
                
                $bid = intval($_GPC['bid']);
                $brand = pdo_get('mzhk_sun_brand',array('uniacid'=>$_W['uniacid'],'bid'=>$bid),array("memdiscount","bname","bind_openid"));
                $data = array();
                $data["bid"] = $bid;
                $data["bname"] = $brand['bname'];
                $data["mcd_type"] = 3;
                $data["mcd_memo"] = "线下付款-等待用户付款";
                $data["addtime"] = time();
                $data["money"] = $_GPC['price'];
                $data["uniacid"] = $_W['uniacid'];
                $data["status"] = 0;
                $data["out_trade_no"] = $out_trade_no;
                $data["openid"] = $openid;
                $str = pdo_insert('mzhk_sun_mercapdetails', $data);
                $mer_id = pdo_insertid();
                $ordertype = 10;
            }
        }

        $total_fee = $orderinfo['money']<=0?$_GPC['price']:$orderinfo['money'];

        include IA_ROOT . '/addons/mzhk_sun/wxpay.php';
        $res = pdo_get('mzhk_sun_system', array('uniacid' => $_W['uniacid']));
        $appid = $res['appid'];
        $mch_id = $res['mchid'];
        $key = $res['wxkey'];
        
        if (empty($total_fee)) {
            $body = "付款";
            $total_fee = floatval(9900 * 100);
        } else {
            $body = "付款";
            $total_fee = floatval($total_fee * 100);
        }

        
        $notify_url = $_W['siteroot'].'addons/mzhk_sun/notify.php';
        $attach = $_W['uniacid']."@@@".$ordertype;

        $weixinpay = new WeixinPay($appid, $openid, $mch_id, $key, $out_trade_no, $body, $total_fee,$notify_url,$attach);
        $return = $weixinpay->pay();
        if($paytype==2){
            $return["mer_id"] = intval($mer_id);
        }
        echo json_encode($return);
    }

     
    public function doPageOrderarrYue() {
        global $_W, $_GPC;
        
        $order_id = intval($_GPC['order_id']);
        $openid = $_GPC['openid'];
        $uniacid = $_W['uniacid'];
        if(empty($openid)){
            return $this->result(1, '参数错误，支付失败，000001！', array());
        }
        
        $user = pdo_get('mzhk_sun_user',array('openid'=>$openid,'uniacid'=>$uniacid),array("id","name","money"));
        if(!$user){
            return $this->result(1, '参数错误，支付失败，000002！', array());
        }
        
        $ordertype = intval($_GPC["ordertype"]);
        if($order_id>0){
            $rtype = 3;
            if($ordertype==1){
                $is_lead = intval($_GPC["is_lead"]);
                $g_order_id = intval($_GPC["g_order_id"]);
                
                $orderinfo = pdo_get('mzhk_sun_ptorders',array('id'=>$order_id,'uniacid'=>$_W['uniacid']),array("gid","gname","buynum","neednum","is_ok","id","money","peoplenum"));
                if($is_lead!=1){
                    
                    if($orderinfo["buynum"]>=$orderinfo["neednum"] && $orderinfo["is_ok"]==1){
                        
                        $set_status = pdo_update('mzhk_sun_ptgroups',array("status"=>1), array('id' => $g_order_id, 'uniacid' => $_W['uniacid']));
                        return $this->result(1, '该团已满，请重新开团！！！', $emptydata);
                    }
                }
                
                pdo_update('mzhk_sun_ptgroups', array('out_trade_no' => $out_trade_no,"paytype"=>2), array('id' => $g_order_id));
                $order_id = $g_order_id;
                $memo = "拼团订单，订单id:".$order_id;
            }elseif($ordertype==3){
                $orderinfo = pdo_get('mzhk_sun_cardorder', array('id' => $order_id,'uniacid' => $_W['uniacid']),array("money"));
                
                pdo_update('mzhk_sun_cardorder', array('out_trade_no' => $out_trade_no,"paytype"=>2), array('id' => $order_id));
                $memo = "集卡订单，订单id:".$order_id;
            }elseif($ordertype==4){
                $orderinfo = pdo_get('mzhk_sun_order', array('oid' => $order_id,'uniacid' => $_W['uniacid']),array("money"));
                
                pdo_update('mzhk_sun_order', array('out_trade_no' => $out_trade_no,"paytype"=>2), array('oid' => $order_id));
                $memo = "普通订单，订单id:".$order_id;
            }elseif($ordertype==5){
                $orderinfo = pdo_get('mzhk_sun_kjorder', array('oid' => $order_id,'uniacid' => $_W['uniacid']),array("money"));
                
                pdo_update('mzhk_sun_kjorder', array('out_trade_no' => $out_trade_no,"paytype"=>2), array('oid' => $order_id));
                $memo = "砍价订单，订单id:".$order_id;
            }elseif($ordertype==6){
                $orderinfo = pdo_get('mzhk_sun_hyorder', array('id' => $order_id,'uniacid' => $_W['uniacid']),array("money"));
                
                pdo_update('mzhk_sun_hyorder', array('out_trade_no' => $out_trade_no,"paytype"=>2), array('id' => $order_id));
                $memo = "免单订单，订单id:".$order_id;
            }else{
                $orderinfo = pdo_get('mzhk_sun_qgorder', array('oid' => $order_id,'uniacid' => $_W['uniacid']),array("money"));
                
                pdo_update('mzhk_sun_qgorder', array('out_trade_no' => $out_trade_no,"paytype"=>2), array('oid' => $order_id));
                $memo = "抢购订单，订单id:".$order_id;
            }
        }
        $paytypes =  intval($_GPC["paytypes"]);
        $id = intval($_GPC["id"]);
        if($paytypes==1){
            $vipdata = pdo_get('mzhk_sun_vip',array('id'=>$id,'uniacid'=>$uniacid),array("title","price","day","prefix"));
            $order_id = $id;
            $memo = "购买会员卡,".$vipdata["title"].",".$vipdata["day"]."天,id:".$id;
            $rtype = 2;
        }
        
        $total_fee = $orderinfo['money']<=0?$_GPC['price']:$orderinfo['money'];
        if($user["money"]<$total_fee){
            return $this->result(1, '余额不足，请选择其他付款方式!！', array());
        }
        
        $res_user = pdo_update('mzhk_sun_user', array('money -=' => $total_fee), array('id' => $user["id"]));
        
        $data = array();
        $data["openid"] = $openid;
        $data["order_id"] = $order_id;
        $data["money"] = $total_fee;
        $data["addtime"] = time();
        $data["rtype"] = $rtype;
        $data["memo"] = $memo;
        $data["uniacid"] = $uniacid;
        $res=pdo_insert('mzhk_sun_rechargelogo',$data);
        if($res){
            echo 1;
        }else{
            echo 2;
        }

    }

    public function doPageOrderarrTwo() {
        global $_GPC,$_W;
        

        $openid = $_GPC['openid'];
        $out_trade_no = date('Ymd') . substr('' . time(), -4, 4).rand(1111,9999);
        $appData = pdo_get('mzhk_sun_system',array('uniacid'=>$_W['uniacid']));
        $appid = $appData['appid'];
        $mch_id = $appData['mchid'];
        $keys = $appData['wxkey'];
        $price = $_GPC['price'];
        if ($price==0) {
            $price = 99*100;
        }
        $order_url = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
        $data = array(
            'appid' => $appid,
            'mch_id' => $mch_id,
            'nonce_str' => '5K8264ILTKCH16CQ2502SI8ZNMTM67VS',
            'body' => "paymember",
            'out_trade_no' => $out_trade_no,
            'total_fee' => $price*100,
            'spbill_create_ip' => '120.79.152.105',
            'notify_url' => '120.79.152.105',
            'trade_type' => 'JSAPI',
            'openid' => $openid
        );
        
        ksort($data, SORT_ASC);
        $stringA = http_build_query($data);
        $signTempStr = $stringA . '&key='.$keys;
        $signValue = strtoupper(md5($signTempStr));
        $data['sign'] = $signValue;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $order_url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->arrayToXml($data));
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $result = curl_exec($ch);
        
        
        curl_close($ch);
        $result = xml2array($result);
        echo json_encode($this->createPaySign($result));

    }
    function createPaySign($result)
    {
        $appData = pdo_get('mzhk_sun_system',array('uniacid'=>$_W['uniacid']));
        $keys = $appData['wxkey'];
        $data = array(
            'appId' => $result['appid'],
            'timeStamp' => (string)time(),
            'nonceStr' => $result['nonce_str'],
            'package' => 'prepay_id=' . $result['prepay_id'],
            'signType' => 'MD5'
        );
        ksort($data, SORT_ASC);
        $stringA = '';
        foreach ($data as $key => $val) {
            $stringA .= "{$key}={$val}&";
        }
        $signTempStr = $stringA . 'key='.$keys;
        $signValue = strtoupper(md5($signTempStr));
        $data['paySign'] = $signValue;
        return $data;
    }

    function arrayToXml($arr)
    {
        $xml = "<xml>";
        foreach ($arr as $key=>$val)
        {
            if (is_numeric($val)){
                $xml.="<".$key.">".$val."</".$key.">";
            }else{
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml.="</xml>";
        return $xml;
    }

    
    public function doPageTouploadtwo(){
        $uptypes = array(
            'image/jpg',
            'image/jpeg',
            'image/png',
            'image/pjpeg',
            'image/gif',
            'image/bmp',
            'image/x-png'
        );
        $max_file_size = 4000000;     
        $destination_folder = "../attachment/"; 
        $watermark = 2;      
        $watertype = 1;      
        $waterposition = 1;     
        $waterstring = "666666";  
        
        $imgpreview = 1;      
        $imgpreviewsize = 1 / 2;    
        
        if (!is_uploaded_file($_FILES["file"]['tmp_name'])) {
            echo "图片不存在!";
            exit;
        }
        $file = $_FILES["file"];
        
        if ($max_file_size < $file["size"]) {
            echo "文件太大!";
            exit;
        }
        
        if (!in_array($file["type"], $uptypes)) {
            echo "文件类型不符!" . $file["type"];
            exit;
        }
        if (!file_exists($destination_folder)) {
            mkdir($destination_folder);
        }
        $filename = $file["tmp_name"];
        $image_size = getimagesize($filename);
        $pinfo = pathinfo($file["name"]);
        $ftype = $pinfo['extension'];
        $destination = $destination_folder . str_shuffle(rand(11111, 99999).time() . rand(111111, 999999)) . "." . $ftype;
        if (file_exists($destination) && $overwrite != true) {
            echo "同名文件已经存在了";
            exit;
        }
        if (!move_uploaded_file($filename, $destination)) {
            echo "移动文件出错";
            exit;
        }
        $pinfo = pathinfo($destination);
        $fname = $pinfo['basename'];
        if ($watermark == 1) {
            $iinfo = getimagesize($destination, $iinfo);
            $nimage = imagecreatetruecolor($image_size[0], $image_size[1]);
            $white = imagecolorallocate($nimage, 255, 255, 255);
            $black = imagecolorallocate($nimage, 0, 0, 0);
            $red = imagecolorallocate($nimage, 255, 0, 0);
            imagefill($nimage, 0, 0, $white);
            switch ($iinfo[2]) {
                case 1:
                    $simage = imagecreatefromgif($destination);
                    break;
                case 2:
                    $simage = imagecreatefromjpeg($destination);
                    break;
                case 3:
                    $simage = imagecreatefrompng($destination);
                    break;
                case 6:
                    $simage = imagecreatefromwbmp($destination);
                    break;
                default:
                    die("不支持的文件类型");
                    exit;
            }
            imagecopy($nimage, $simage, 0, 0, 0, 0, $image_size[0], $image_size[1]);
            imagefilledrectangle($nimage, 1, $image_size[1] - 15, 80, $image_size[1], $white);
            switch ($watertype) {
                case 1:   
                    imagestring($nimage, 2, 3, $image_size[1] - 15, $waterstring, $black);
                    break;
                case 2:   
                    $simage1 = imagecreatefromgif("xplore.gif");
                    imagecopy($nimage, $simage1, 0, 0, 0, 0, 85, 15);
                    imagedestroy($simage1);
                    break;
            }
            switch ($iinfo[2]) {
                case 1:
                    
                    imagejpeg($nimage, $destination);
                    break;
                case 2:
                    imagejpeg($nimage, $destination);
                    break;
                case 3:
                    imagepng($nimage, $destination);
                    break;
                case 6:
                    imagewbmp($nimage, $destination);
                    
                    break;
            }
            
            imagedestroy($nimage);
            imagedestroy($simage);
        }
        echo $fname;
        @require_once(IA_ROOT . '/framework/function/file.func.php');
        @$filename = $fname;
        @file_remote_upload($filename);
    }

    
    public function doPageGetstoreNotice() {
        global $_GPC,$_W;
        $openid = $_GPC["openid"];
        $res = pdo_get('mzhk_sun_system', array('uniacid' => $_W['uniacid']),array("store_open","store_in_notice"));
        
        if($res["store_open"]!=1){
            return $this->result(1, '商家入驻尚未开启！！！', $emptydata);
        }
        
        $res_brand = pdo_get('mzhk_sun_brand', array('uniacid' => $_W['uniacid'],'in_openid' => $openid,'status !=' => 2),array("bid"));
        if($res_brand){
            return $this->result(1, '您提交过入驻商家信息，请耐心等待管理员审核！！！', $emptydata);
        }
        
        $res_brand = pdo_get('mzhk_sun_brand', array('uniacid' => $_W['uniacid'],'bind_openid' => $openid,'status' => 2),array("bid"));
        if($res_brand){
            return $this->result(1, '您已经是入驻商家，直接从管理入口进入！！！', $emptydata);
        }

        return $this->result(0, '', array("notice"=>$res["store_in_notice"]));
    }

    
    public function doPageSaveStoreInfo() {
        global $_GPC,$_W;
        $openid = $_GPC['openid'];
        
        $res = pdo_get('mzhk_sun_brand', array('uniacid' => $_W['uniacid'],'in_openid' => $openid),array("bid"));

        $data["in_openid"]=$_GPC['openid'];
        $data["bind_openid"]=$_GPC['openid'];
        $data["bname"]=$_GPC['bname'];
        $data["uname"]=$_GPC['uname'];
        $data["phone"]=$_GPC['phone'];
        $data["starttime"]=$_GPC['starttime'];
        $data["endtime"]=$_GPC['endtime'];
        $data["address"]=$_GPC['address'];
        if(!empty($_GPC['coordinates'])){
            $data["coordinates"] = $_GPC['coordinates'];
            $coordinates=explode(",",$_GPC['coordinates']);
            $data["latitude"] = $coordinates[0];
            $data["longitude"] = $coordinates[1];
        }
        $data["type"]=$_GPC['storetype'];
        $data["feature"]=$_GPC['feature'];
        $data["price"]=$_GPC['price'];
        $data["deliveryfee"]=$_GPC['deliveryfee'];
        $data["deliverytime"]=$_GPC['deliverytime'];
        $data["deliveryaway"]=$_GPC['deliveryaway'];
        $data["lt_id"]=$_GPC['lt_id'];
        $data["lt_day"]=$_GPC['lt_day'];
        if(!empty($_GPC['PicTwo'])){
            $pictwo = explode(",",$_GPC['PicTwo']);
            $content = "<p>";
            foreach($pictwo as $k => $v){
                $content .= "<img src='".$_W['attachurl'].$v."'  />";
            }
            $content .= "</p>";
            $data["content"] = $content;
        }
        $data["logo"]=$_GPC['img'];
        $data["img"]=$_GPC['logo'];
        $data["facility"]=$_GPC['facility'];
        $data["store_id"]=$_GPC['store_id'];
        $data["store_name"]=$_GPC['store_name'];
        $data["settleintime"]=time();
        $data["status"]=1;

        if($res){
            $bid = $res["bid"];
            pdo_update('mzhk_sun_brand', $data, array('bid' => $bid));
        }else{
            $data["uniacid"]=$_W['uniacid'];
            $res = pdo_insert('mzhk_sun_brand',$data);
            $bid = pdo_insertid();
        }
        if($res){
            $data_log = array();
            $data_log["bid"] = $bid;
            $data_log["price"] = $_GPC['lt_money'];
            $data_log["uniacid"] = $_W['uniacid'];
            $data_log["openid"] = $_GPC['openid'];
            $res_log = pdo_insert('mzhk_sun_brandpaylog',$data_log);
            $bpl_id = pdo_insertid();
        }
        echo json_encode(array("bid"=>$bid,"bpl_id"=>$bpl_id));
    }

    public function doPageStoreIn() {
        global $_GPC,$_W;

        $openid = $_GPC['openid'];
        $bpl_id = intval($_GPC['bpl_id']);
        $out_trade_no = date('Ymd') . substr('' . time(), -4, 4).rand(1111, 9999);

        $appData = pdo_get('mzhk_sun_system',array('uniacid'=>$_W['uniacid']));
        $appid = $appData['appid'];
        $mch_id = $appData['mchid'];
        $keys = $appData['wxkey'];
        if($_GPC['price']){
            $price = $_GPC['price'];
        }else{
            $get_bpl = pdo_get('mzhk_sun_brandpaylog',array('id'=>$bpl_id),array("price"));
            $price = $get_bpl['price'];
        }
        if($price==0){
            $price = 99*100;
        }
        if($bpl_id>0){
            
            
        }
        
        
        
        
        
        

        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        


        $order_url = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
        $data = array(
            'appid' => $appid,
            'mch_id' => $mch_id,
            'nonce_str' => '5K8264ILTKCH16CQ2502SI8ZNMTM67VS',
            
            'body' => time(),
            'out_trade_no' => $out_trade_no,
            'total_fee' => $price * 100,

            'spbill_create_ip' => '120.79.152.105',
            'notify_url' => '120.79.152.105',
            'trade_type' => 'JSAPI',
            'openid' => $openid
        );
        ksort($data, SORT_ASC);
        $stringA = http_build_query($data);
        $signTempStr = $stringA . '&key=' . $keys;
        $signValue = strtoupper(md5($signTempStr));
        $data['sign'] = $signValue;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $order_url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->arrayToXml($data));
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $result = curl_exec($ch);
        curl_close($ch);
        $result = xml2array($result);
        echo json_encode($this->createPaySign($result));

    }

    public function doPagePayStoreIn(){
        global $_GPC,$_W;
        $bpl_id = intval($_GPC["bpl_id"]);
        $bid = intval($_GPC["bid"]);
        $openid = $_GPC["openid"];
        $res = pdo_get('mzhk_sun_brandpaylog', array('id' => $bpl_id),array("bid"));
        if($res["bid"]==$bid){
            pdo_update('mzhk_sun_brand', array('paytime' => time()), array('bid' => $bid));
            pdo_update('mzhk_sun_brandpaylog', array('paytime' => time(),'status' => 1), array('id' => $bpl_id));
        }
    }

    
    public function doPageGetStoreLimit() {
        global $_GPC,$_W;
        $uniacid = $_W["uniacid"];
        $sql ='select id,lt_name,lt_day,money from'.tablename('mzhk_sun_storelimit').' where uniacid='.$uniacid;
        $data['storelimit']=pdo_fetchall($sql);
        $sql ='select id,facilityname,selectedimg,unselectedimg,unselectedimg from'.tablename('mzhk_sun_storefacility').' where uniacid='.$uniacid;
        $data['storefacility']=pdo_fetchall($sql);
        echo json_encode($data);
    }

    
    public function doPageCheckBrandUser() {
        global $_GPC,$_W;
        $openid = $_GPC["openid"];
        $emptydata = array();
        $res = pdo_get('mzhk_sun_brand', array('bind_openid' => $openid,'uniacid' => $_W['uniacid']));
        
        if($res){
            
            return $this->result(0, '', $res);
        }else{
            if(!empty($_GPC["loginname"]) && !empty($_GPC["loginpassword"])){
                $loginname = $_GPC["loginname"];
                $loginpassword = $_GPC["loginpassword"];
                $res = pdo_get('mzhk_sun_brand', array('loginname' => $loginname,'loginpassword' => $loginpassword,'uniacid' => $_W['uniacid']));
                if($res){
                    return $this->result(0, '', $res);
                    
                }
            }
            return $this->result(1, '账号或密码错误', $emptydata);
        }

    }

    
    Public function doPageGetOrderNum(){
        global $_GPC,$_W;
        $bid = intval($_GPC["bid"]);
        $uniacid = $_W['uniacid'];
        $day_start = strtotime(date("Y-m-d")." 00:00:00");
        $day_end   = strtotime(date("Y-m-d")." 23:59:59");
        $yesterday = date("Y-m-d",strtotime("-1 day"));
        $yesterday_start = strtotime($yesterday." 00:00:00");
        $yesterday_end   = strtotime($yesterday." 23:59:59");
        $thismonth = date('Y-m-01', strtotime(date("Y-m-d")));
        $thismonth_start = strtotime($thismonth." 00:00:00");
        $thismonth_end   = strtotime(date('Y-m-d', strtotime("$thismonth +1 month -1 day"))." 23:59:59");

        $data = array();

        $where = " where uniacid=".$uniacid." and bid=".$bid." ";
        $day_where = $where." and status > 2 and addtime >= ".$day_start." and addtime <= ".$day_end." and not(isrefund = 2) ";
        $day_card_where = $where." and status >= 0 and addtime >= ".$day_start." and addtime <= ".$day_end." ";
        $day_finish_where = $where." and status > 2 and finishtime >= ".$day_start." and finishtime <= ".$day_end." and not(isrefund = 2) ";
        $day_finish_card_where = $where." and status >= 0 and finishtime >= ".$day_start." and finishtime <= ".$day_end." ";
        $sql = "select sum(count) as count,sum(money) as money from (select count(oid) as count,sum(money) as money from ".tablename('mzhk_sun_order')." ".$day_where." union all select count(id) as count,sum(money) as money from ".tablename('mzhk_sun_ptgroups')." ".$day_where." union all select count(oid) as count,sum(money) as money from ".tablename('mzhk_sun_qgorder')." ".$day_where." union all select count(id) as count,sum(money) as money from ".tablename('mzhk_sun_cardorder')." ".$day_card_where." union all select count(oid) as count,sum(money) as money from ".tablename('mzhk_sun_kjorder')." ".$day_where." union all select count(oid) as count,sum(money) as money from ".tablename('mzhk_sun_hyorder')." ".$day_card_where." ) as a ";
        $day_order = pdo_fetch($sql);
        $sql = "select sum(count) as count,sum(money) as money from (select count(oid) as count,sum(money) as money from ".tablename('mzhk_sun_order')." ".$day_finish_where." union all select count(id) as count,sum(money) as money from ".tablename('mzhk_sun_ptgroups')." ".$day_finish_where." union all select count(oid) as count,sum(money) as money from ".tablename('mzhk_sun_qgorder')." ".$day_finish_where." union all select count(id) as count,sum(money) as money from ".tablename('mzhk_sun_cardorder')." ".$day_finish_card_where." union all select count(oid) as count,sum(money) as money from ".tablename('mzhk_sun_kjorder')." ".$day_finish_where." union all select count(oid) as count,sum(money) as money from ".tablename('mzhk_sun_hyorder')." ".$day_finish_card_where." ) as a ";
        $day_finish_order = pdo_fetch($sql);

        $yesterday_where = $where." and status > 2 and addtime >= ".$yesterday_start." and addtime <= ".$yesterday_end." and not(isrefund = 2) ";
        $yesterday_card_where = $where." and status >=0 and addtime >= ".$yesterday_start." and addtime <= ".$yesterday_end."";
        $yesterday_finish_where = $where." and status > 2 and finishtime >= ".$yesterday_start." and finishtime <= ".$yesterday_end." and not(isrefund = 2) ";
        $yesterday_finish_card_where = $where." and status >=0 and finishtime >= ".$yesterday_start." and finishtime <= ".$yesterday_end."";
        $sql = "select sum(count) as count,sum(money) as money from (select count(oid) as count,sum(money) as money from ".tablename('mzhk_sun_order')." ".$yesterday_where." union all select count(id) as count,sum(money) as money from ".tablename('mzhk_sun_ptgroups')." ".$yesterday_where." union all select count(oid) as count,sum(money) as money from ".tablename('mzhk_sun_qgorder')." ".$yesterday_where." union all select count(id) as count,sum(money) as money from ".tablename('mzhk_sun_cardorder')." ".$yesterday_card_where." union all select count(oid) as count,sum(money) as money from ".tablename('mzhk_sun_kjorder')." ".$yesterday_where." union all select count(oid) as count,sum(money) as money from ".tablename('mzhk_sun_hyorder')." ".$yesterday_card_where." ) as a ";
        $yesterday_order = pdo_fetch($sql);
        $sql = "select sum(count) as count,sum(money) as money from (select count(oid) as count,sum(money) as money from ".tablename('mzhk_sun_order')." ".$yesterday_finish_where." union all select count(id) as count,sum(money) as money from ".tablename('mzhk_sun_ptgroups')." ".$yesterday_finish_where." union all select count(oid) as count,sum(money) as money from ".tablename('mzhk_sun_qgorder')." ".$yesterday_finish_where." union all select count(id) as count,sum(money) as money from ".tablename('mzhk_sun_cardorder')." ".$yesterday_finish_card_where." union all select count(oid) as count,sum(money) as money from ".tablename('mzhk_sun_kjorder')." ".$yesterday_finish_where." union all select count(oid) as count,sum(money) as money from ".tablename('mzhk_sun_hyorder')." ".$yesterday_finish_card_where." ) as a ";
        $yesterday_finish_order = pdo_fetch($sql);

        $thismonth_where = $where." and status > 2 and addtime >= ".$thismonth_start." and addtime <= ".$thismonth_end." and not(isrefund = 2)  ";
        $thismonth_card_where = $where." and status >= 0 and addtime >= ".$thismonth_start." and addtime <= ".$thismonth_end." ";
        $thismonth_finish_where = $where." and status > 2 and finishtime >= ".$thismonth_start." and finishtime <= ".$thismonth_end." and not(isrefund = 2)  ";
        $thismonth_finish_card_where = $where." and status >= 0 and finishtime >= ".$thismonth_start." and finishtime <= ".$thismonth_end." ";
        $sql = "select sum(count) as count,sum(money) as money from (select count(oid) as count,sum(money) as money from ".tablename('mzhk_sun_order')." ".$thismonth_where." union all select count(id) as count,sum(money) as money from ".tablename('mzhk_sun_ptgroups')." ".$thismonth_where." union all select count(oid) as count,sum(money) as money from ".tablename('mzhk_sun_qgorder')." ".$thismonth_where." union all select count(id) as count,sum(money) as money from ".tablename('mzhk_sun_cardorder')." ".$thismonth_card_where." union all select count(oid) as count,sum(money) as money from ".tablename('mzhk_sun_kjorder')." ".$thismonth_where." union all select count(oid) as count,sum(money) as money from ".tablename('mzhk_sun_hyorder')." ".$thismonth_card_where." ) as a ";
        $thismonth_order = pdo_fetch($sql);
        $sql = "select sum(count) as count,sum(money) as money from (select count(oid) as count,sum(money) as money from ".tablename('mzhk_sun_order')." ".$thismonth_finish_where." union all select count(id) as count,sum(money) as money from ".tablename('mzhk_sun_ptgroups')." ".$thismonth_finish_where." union all select count(oid) as count,sum(money) as money from ".tablename('mzhk_sun_qgorder')." ".$thismonth_finish_where." union all select count(id) as count,sum(money) as money from ".tablename('mzhk_sun_cardorder')." ".$thismonth_finish_card_where." union all select count(oid) as count,sum(money) as money from ".tablename('mzhk_sun_kjorder')." ".$thismonth_finish_where." union all select count(oid) as count,sum(money) as money from ".tablename('mzhk_sun_hyorder')." ".$thismonth_finish_card_where." ) as a ";
        $thismonth_finish_order = pdo_fetch($sql);

        $all_where = $where." and status > 2 and not(isrefund = 2)  ";
        $all_card_where = $where." and status >= 0 ";
        $all_finish_where = $where." and status > 2 and finishtime >= 1000000 and not(isrefund = 2)  ";
        $all_finish_card_where = $where." and status >= 0 and finishtime >= 1000000 ";
        $sql = "select sum(count) as count,sum(money) as money from (select count(oid) as count,sum(money) as money from ".tablename('mzhk_sun_order')." ".$all_where." union all select count(id) as count,sum(money) as money from ".tablename('mzhk_sun_ptgroups')." ".$all_where." union all select count(oid) as count,sum(money) as money from ".tablename('mzhk_sun_qgorder')." ".$all_where." union all select count(id) as count,sum(money) as money from ".tablename('mzhk_sun_cardorder')." ".$all_finish_card_where." union all select count(oid) as count,sum(money) as money from ".tablename('mzhk_sun_kjorder')." ".$all_where." union all select count(oid) as count,sum(money) as money from ".tablename('mzhk_sun_hyorder')." ".$all_finish_card_where." ) as a ";
        $all_order = pdo_fetch($sql);
        $sql = "select sum(count) as count,sum(money) as money from (select count(oid) as count,sum(money) as money from ".tablename('mzhk_sun_order')." ".$all_finish_where." union all select count(id) as count,sum(money) as money from ".tablename('mzhk_sun_ptgroups')." ".$all_finish_where." union all select count(oid) as count,sum(money) as money from ".tablename('mzhk_sun_qgorder')." ".$all_finish_where." union all select count(id) as count,sum(money) as money from ".tablename('mzhk_sun_cardorder')." ".$all_finish_card_where." union all select count(oid) as count,sum(money) as money from ".tablename('mzhk_sun_kjorder')." ".$all_finish_where." union all select count(oid) as count,sum(money) as money from ".tablename('mzhk_sun_hyorder')." ".$all_finish_card_where." ) as a ";
        $all_finish_order = pdo_fetch($sql);

        $data["count"] = array(
            $day_order["count"]?$day_order["count"]:0,
            $yesterday_order["count"]?$yesterday_order["count"]:0,
            $thismonth_order["count"]?$thismonth_order["count"]:0,
            $all_order["count"]?$all_order["count"]:0,
            $day_finish_order["count"]?$day_finish_order["count"]:0,
            $yesterday_finish_order["count"]?$yesterday_finish_order["count"]:0,
            $thismonth_finish_order["count"]?$thismonth_finish_order["count"]:0,
            $all_finish_order["count"]?$all_finish_order["count"]:0,
            $day_finish_order["money"]?$day_finish_order["money"]:0,
            $yesterday_finish_order["money"]?$yesterday_finish_order["money"]:0,
            $thismonth_finish_order["money"]?$thismonth_finish_order["money"]:0,
            $all_finish_order["money"]?$all_finish_order["money"]:0,
        );
        
        $brand = pdo_get('mzhk_sun_brand',array('uniacid'=>$_W['uniacid'],'bid'=>$bid),array("totalamount","frozenamount"));
        
        $data["totalamount"] = $brand["totalamount"];
        
        echo json_encode($data);

    }

    
    public function getaccess_token(){
        global $_W, $_GPC;
        $res=pdo_get('mzhk_sun_system',array('uniacid'=>$_W['uniacid']));
        $appid=$res['appid'];
        $secret=$res['appsecret'];
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secret."";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
        $data = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($data,true);
        return $data['access_token'];
    }
    
    public function doPageSendMessage(){
        global $_W, $_GPC;
        
        
        $access_token = $this->getaccess_token();
        $res2=pdo_get('mzhk_sun_sms',array('uniacid'=>$_W['uniacid']));
        $sql="select a.bname,a.settleintime,a.status,b.name as user_name from " . tablename("mzhk_sun_brand") . " a"  . " left join " . tablename("mzhk_sun_user") . " b on b.openid=a.in_openid WHERE a.bid=:bid";
        $res=pdo_fetch($sql,array(':bid'=>$_GPC['bid']));
        $type="待审核";
        $note="1-3日完成审核";
        $formwork ='{
            "touser": "'.$_GET["openid"].'",
            "template_id": "'.$res2["tid3"].'",
            "page":"mzhk_sun/pages/index/index",
            "form_id":"'.$_GET['form_id'].'",
            "data": {
                "keyword1": {
                    "value": "'.$res['bname'].'",
                    "color": "#173177"
                },
                "keyword2": {
                    "value":"'.date("Y-m-d H:i:s",$res['settleintime']).'",
                    "color": "#173177"
                },
                "keyword3": {
                    "value": "'.$type.'",
                    "color": "#173177"
                },
                "keyword4": {
                    "value": "'.$note.'",
                    "color": "#173177"
                },
                "keyword5": {
                    "value":  "'. $res['user_name'].'",
                    "color": "#173177"
                }
            }   
        }';
        
        
        $url = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=".$access_token."";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
        curl_setopt($ch, CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$formwork);
        $data = curl_exec($ch);
        curl_close($ch);
        
        echo $data;
    }

    
    public function doPageSendMessagePay(){
        global $_W, $_GPC;
        $id = $_GPC["id"];
        $price = $_GPC["price"];
        $order_id = $_GPC["order_id"];
        $openid = $_GPC["openid"];
        $form_id = str_replace("prepay_id=", "", $_GPC["form_id"]);
        $typeid = $_GPC["typeid"];
        
        
        $access_token = $this->getaccess_token();
        $res2=pdo_get('mzhk_sun_sms',array('uniacid'=>$_W['uniacid']));
        if($typeid==1){
            $sql="select orderNum,gname,addtime,money,bname from " . tablename("mzhk_sun_qgorder") . " WHERE oid=:oid";
            $data_sql[":oid"] = $order_id;
        }elseif($typeid==2){
            $sql="select groupordernum as orderNum,gname,addtime,money,bname from " . tablename("mzhk_sun_ptgroups") . " WHERE id=:id";
            $data_sql[":id"] = $order_id;
        }elseif($typeid==3){
            $sql="select ordernum as orderNum,gname,addtime,money,bname from " . tablename("mzhk_sun_cardorder") . " WHERE id=:id";
            $data_sql[":id"] = $order_id;
        }elseif($typeid==4){
            $sql="select orderNum,gname,addtime,money,bname from " . tablename("mzhk_sun_order") . " WHERE oid=:oid";
            $data_sql[":oid"] = $order_id;
        }elseif($typeid==5){
            $sql="select orderNum,gname,addtime,money,bname from " . tablename("mzhk_sun_kjorder") . " WHERE oid=:oid";
            $data_sql[":oid"] = $order_id;
        }elseif($typeid==6){
            $sql="select orderNum,gname,addtime,money,bname from " . tablename("mzhk_sun_hyorder") . " WHERE oid=:oid";
            $data_sql[":oid"] = $order_id;
        }

        $res=pdo_fetch($sql,$data_sql);
        $type="待收货";
        $note="查看小程序";
        $formwork ='{
            "touser": "'.$openid.'",
            "template_id": "'.$res2["tid2"].'",
            "page":"mzhk_sun/pages/index/index",
            "form_id":"'.$form_id.'",
            "data": {
                "keyword1": {
                    "value": "'.$res['gname'].'",
                    "color": "#173177"
                },
                "keyword2": {
                    "value":"'.date("Y-m-d H:i:s",$res['addtime']).'",
                    "color": "#173177"
                },
                "keyword3": {
                    "value": "'.$res['money'].'",
                    "color": "#173177"
                },
                "keyword4": {
                    "value": "'.$note.'",
                    "color": "#173177"
                },
                "keyword5": {
                    "value":  "'. $res['bname'].'",
                    "color": "#173177"
                },
                "keyword6": {
                    "value":  "'. $res['orderNum'].'",
                    "color": "#173177"
                }
            }   
        }';
        
        
        $url = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=".$access_token."";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
        curl_setopt($ch, CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$formwork);
        $data = curl_exec($ch);
        curl_close($ch);
        
        echo $data;
    }

    function sendtelmessage($access_token,$openid,$gname,$gid,$addtime,$tpltype=0){
        global $_W, $_GPC;

        if(empty($openid)){
            return ;
        }
        
        $delres=pdo_delete('mzhk_sun_userformid',array('time <='=>date('Y-m-d', strtotime('-7 days')),'uniacid'=>$_W['uniacid']));
        $delres=pdo_delete('mzhk_sun_userformid',array('form_id like'=>"mock",'uniacid'=>$_W['uniacid']));
        
        $res2=pdo_get('mzhk_sun_sms',array('uniacid'=>$_W['uniacid']));
        $now = date('Y-m-d', strtotime('-7 days'));
        $sql="select id,form_id from " . tablename("mzhk_sun_userformid") . " where openid='".$openid."' and time>='".$now."' order by id asc";
        $res=pdo_fetch($sql);
        if($res){
            $formwork ='{
                "touser": "'.$openid.'",
                "template_id": "'.$res2["tid4"].'",
                "page":"mzhk_sun/pages/index/freedet/freedet?id='.$gid.'",
                "form_id":"'.$res['form_id'].'",
                "data": {
                    "keyword1": {
                        "value": "免单活动",
                        "color": "#173177"
                    },
                    "keyword2": {
                        "value":"'.$gname.'",
                        "color": "#173177"
                    },
                    "keyword3": {
                        "value": "'.date("Y-m-d H:i:s").'",
                        "color": "#173177"
                    },
                    "keyword4": {
                        "value": "您参与的免单活动已开奖，点击进入查看",
                        "color": "#173177"
                    }
                }   
            }';
            
            
            $url = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=".$access_token."";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
            curl_setopt($ch, CURLOPT_POST,1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,$formwork);
            $data = curl_exec($ch);
            curl_close($ch);

            
            $delres=pdo_delete('mzhk_sun_userformid',array('id'=>$res["id"],'uniacid'=>$_W['uniacid']));
        }
    }

    
    public function doPageSaveWin(){
        global $_W, $_GPC;
        $openid = $_GPC["openid"];
        $id = intval($_GPC["id"]);
        $gid = intval($_GPC["gid"]);
        $uniacid = $_W["uniacid"];
        $time = time();
        if($id==0 || $gid==0){
            echo "error";
            exit;
        }
        
        $card_goods= pdo_get('mzhk_sun_goods',array('uniacid'=>$uniacid,'gid'=>$gid),array("initialtimes","antime"));
        
        $card_user = pdo_get('mzhk_sun_cardcollect',array('uniacid'=>$uniacid,'gid'=>$gid,'openid'=>$openid,'endtime >='=>$time),array("card_str_id","allnum","usednum","id"));
        $data = array();
        
        if($card_user){
            if($card_user["allnum"]>$card_user["usednum"]){
                $data["card_str_id"] = $card_user["card_str_id"]?$card_user["card_str_id"].",".$id:$id;
                $data["usednum"] = $card_user["usednum"] + 1;
                $res = pdo_update('mzhk_sun_cardcollect',$data,array('id' => $card_user['id'],'uniacid'=>$uniacid));
            }
        }else{
            $data["card_str_id"] = $id;
            $data["openid"] = $openid;
            $data["gid"] = $gid;
            $data["addtime"] = time();
            $data["uniacid"] = $uniacid;
            $data["allnum"] = $card_goods["initialtimes"];
            $data["usednum"] = 1;
            $data["endtime"] = strtotime($card_goods["antime"]);
            $res = pdo_insert('mzhk_sun_cardcollect', $data);
        }
        echo json_encode($res);
    }

    
    public function doPageSaveCardsShare(){
        global $_W, $_GPC;
        $openid = $_GPC["openid"];
        $clickopenid = $_GPC["clickopenid"];
        $gid = intval($_GPC["gid"]);
        $uniacid = $_W["uniacid"];
        $time = time();
        if(empty($openid)){
            echo "error";
            exit;
        }
        
        $daystarttime = strtotime(date("Y-m-d")." 00:00:00");
        $dayendtime = strtotime(date("Y-m-d")." 23:59:59");
        $sharetimes = pdo_get('mzhk_sun_cardshare',array('uniacid'=>$uniacid,'gid'=>$gid,'openid'=>$openid,'status'=>1,'addtime >='=>$daystarttime,'addtime <='=>$dayendtime),array("num","id","click_user_str"));
        
        if($sharetimes['click_user_str']){
            $click_user_str = explode(",",$sharetimes['click_user_str']);
            foreach($click_user_str as $k => $v){
                if($clickopenid==$v){
                    echo "1";
                    exit;
                }
            }
        }
        $card_goods= pdo_get('mzhk_sun_goods',array('uniacid'=>$uniacid,'gid'=>$gid),array("initialtimes","charnum","charaddnum","antime"));
        
        $card_user = pdo_get('mzhk_sun_cardcollect',array('uniacid'=>$uniacid,'gid'=>$gid,'openid'=>$openid,'endtime >='=>$time),array("card_str_id","allnum","usednum","id"));
        if(!$card_user){
            $data = array();
            $data["openid"] = $openid;
            $data["gid"] = $gid;
            $data["addtime"] = time();
            $data["uniacid"] = $uniacid;
            $data["allnum"] = $card_goods["initialtimes"];
            $data["usednum"] = 0;
            $data["endtime"] = strtotime($card_goods["antime"]);
            $res_cc = pdo_insert('mzhk_sun_cardcollect', $data);
            
            $card_user["allnum"] = $card_goods["initialtimes"];
        }
        if($sharetimes){
            
            if($sharetimes["num"] < $card_goods["charnum"]){
                $data = array();
                $data["click_user_str"] = $sharetimes['click_user_str']?$sharetimes['click_user_str'].",".$clickopenid:$clickopenid;
                $data["num"] = $sharetimes["num"] + 1;
                $res_cs = pdo_update('mzhk_sun_cardshare',$data,array('id' => $sharetimes['id'],'uniacid'=>$uniacid));
                
                if($res_cs){
                    $data_cc["allnum"] = intval($card_user["allnum"]) + intval($card_goods["charaddnum"]);
                    $res = pdo_update('mzhk_sun_cardcollect',$data_cc,array('uniacid'=>$uniacid,'gid'=>$gid,'openid'=>$openid,'endtime >='=>$time));
                }else{
                    
                }
            }else{
                
            }
        }else{
            $data = array();
            $data["openid"] = $openid;
            $data["gid"] = $gid;
            $data["addtime"] = time();
            $data["uniacid"] = $uniacid;
            $data["num"] = 1;
            $data["status"] = 1;
            $data["click_user_str"] = $clickopenid;
            $res_cs = pdo_insert('mzhk_sun_cardshare', $data);
            
            if($res_cs){
                $data_cc["allnum"] = intval($card_user["allnum"]) + intval($card_goods["charaddnum"]);
                $res = pdo_update('mzhk_sun_cardcollect',$data_cc,array('uniacid'=>$uniacid,'gid'=>$gid,'openid'=>$openid,'endtime >='=>$time));
            }else{
                
            }
        }

        echo json_encode(array("allnum"=>$data_cc["allnum"]));
    }

    
    public function doPageCheckGift(){
        global $_W, $_GPC;
        $openid = $_GPC["openid"];
        $gid = intval($_GPC["gid"]);
        $uniacid = $_W["uniacid"];
        $card_order = pdo_get('mzhk_sun_cardorder',array('uniacid'=>$uniacid,'gid'=>$gid,'openid'=>$openid),array("id"));
        if($card_order){
            return $this->result(1, '你已经领取过该奖品了！！！', array());
        }else{
            return $this->result(0, '', array());
        }
    }

    
    public function doPageGetStoreCate(){
        global $_W, $_GPC;
        $uniacid = $_W["uniacid"];
        $storecate = pdo_getall('mzhk_sun_storecate',array('uniacid'=>$uniacid),array("store_name","store_img","id"),'','sort asc');
        if($storecate){
            echo json_encode($storecate);
        }else{
            echo "2";
        }
    }

    
    public function doPageUpdateGoods(){
        global $_W, $_GPC;
        $uniacid = $_W["uniacid"];
        $gid = intval($_GPC["id"]);
        $typeid = intval($_GPC["typeid"]);
        if($typeid==2){
            $goods = pdo_get('mzhk_sun_goods',array('uniacid'=>$uniacid,'gid'=>$gid),array("sharenum","viewnum"));
            if($goods["viewnum"]>$goods["sharenum"]){
                $res = pdo_update('mzhk_sun_goods',array("sharenum +="=>1),array('uniacid'=>$uniacid,'gid'=>$gid));
            }
        }elseif($typeid==3){
            $goods = pdo_get('mzhk_sun_goods',array('uniacid'=>$uniacid,'gid'=>$gid),array("buynum","viewnum"));
            if($goods["viewnum"]>$goods["buynum"]){
                $res = pdo_update('mzhk_sun_goods',array("buynum +="=>1),array('uniacid'=>$uniacid,'gid'=>$gid));
            }
        }else{
            $res = pdo_update('mzhk_sun_goods',array("viewnum +="=>1),array('uniacid'=>$uniacid,'gid'=>$gid));
        }
        echo json_encode($res);
    }

    
    public function doPageGetActiveLog(){
        global $_W, $_GPC;
        $uniacid = $_W["uniacid"];
        $sql="select a.viptitle,a.activetype,b.img,b.name from " . tablename("mzhk_sun_vippaylog") . " a" . " left join " . tablename("mzhk_sun_user") . " b on b.openid=a.openid WHERE a.uniacid=:uniacid order by a.id desc limit 20";
        $res=pdo_fetchall($sql,array(':uniacid'=>$uniacid));
        if($res){
            echo json_encode($res);
        }else{
            echo 2;
        }
    }

    
    public function doPageGetStoreInlog(){
        global $_W, $_GPC;
        $uniacid = $_W["uniacid"];
        $sql="select bname,img from " . tablename("mzhk_sun_brand") . " WHERE status=2 and uniacid=:uniacid order by bid desc limit 20";
        $res=pdo_fetchall($sql,array(':uniacid'=>$uniacid));
        if($res){
            echo json_encode($res);
        }else{
            echo 2;
        }
    }

    
    public function doPageGetwxCode(){
        global $_W, $_GPC;

        $access_token = $this->getaccess_token();
        $scene = $_GPC["scene"];
        $page = $_GPC["page"];
        $width = $_GPC["width"]?$_GPC["width"]:430;
        $auto_color = $_GPC["auto_color"]?$_GPC["auto_color"]:false;
        $line_color = $_GPC["line_color"]?$_GPC["line_color"]:'{"r":"0","g":"0","b":"0"}';
        $is_hyaline = $_GPC["is_hyaline"]?$_GPC["is_hyaline"]:true;

        $gid = intval($_GPC["gid"]);
        $uniacid = $_W["uniacid"];
        if($gid>0){
            $goods = pdo_get('mzhk_sun_goods',array('uniacid'=>$uniacid,'gid'=>$gid),array("code_img"));
        }

        
        $url = 'https://api.weixin.qq.com/wxa/getwxacode?access_token='.$access_token;
        
        
        
        $data["path"] = $page;
        $data["width"] = $width;
        
        
        
        
        
        
        
        
        
        $json_data = json_encode($data);
        
        if(!empty($goods["code_img"])){
            $return = $goods["code_img"];
        }else{
            $return = $this->request_post($url,$json_data);
            $res = pdo_update('mzhk_sun_goods',array("code_img"=>$return),array('uniacid'=>$uniacid,'gid'=>$gid));
        }

        
        $imgname = time().rand(10000,99999).'.jpg';
        
        file_put_contents("../attachment/".$imgname,$return);
        
        echo json_encode($imgname);
    }

    public function doPageDelwxCode(){
        global $_W, $_GPC;
        $imgurl = $_GPC["imgurl"];
        $filename = '../attachment/'.$imgurl;
        if(file_exists($filename)){
            $info ='删除成功';
            unlink($filename);
        }else{
            $info ='没找到:'.$filename;
        }
        echo $info;
    }

    public function request_post($url, $data){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
        curl_setopt($ch, CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
        $tmpInfo = curl_exec($ch);
        $error = curl_errno($ch);
        curl_close($ch);
        if ($error) {
            return false;
        }else{
            return $tmpInfo;
        } 
    }
    
    
    public function doPageGetOrderInfo(){
        global $_W, $_GPC;
        $id = intval($_GPC["id"]);
        $ordertype = intval($_GPC["ordertype"]);
        $bid = intval($_GPC["bid"]);
        $uniacid = $_W['uniacid'];
        $where = " where o.uniacid='".$uniacid."' ";
        $status = 0;
        if($ordertype==10){
            $where .= " and o.id=:id and b.bid=:bid ";
            $sql = 'select o.id as oid,o.isUsed,o.limitTime,g.is_counp,g.title as order_gname,g.img as order_pic,b.bname as order_bname from '.tablename('mzhk_sun_user_coupon').' as o left join '.tablename('mzhk_sun_coupon').' as g on o.cid=g.id left join '.tablename('mzhk_sun_brand').' as b on g.bid=b.bid '.$where." order by o.id desc ";
            $fetchdata[":id"] = $id;
            $fetchdata[":bid"] = $bid;
            $res = pdo_fetch($sql,$fetchdata);
            if($res){
                if($res["isUsed"]==1){
                    return $this->result(1, '该优惠券已经使用过了！', array());
                }
                if($res["limitTime"]<time()){
                    return $this->result(1, '该优惠券已经过了使用期限！', array());
                }
                $res["limitTime"] = date("Y-m-d H:i:s",$res["limitTime"]);
                $res["iscou"] = 1;
                $res["num"] = 1;
                echo json_encode($res);
            }else{
                return $this->result(1, '该优惠券无法使用，参数错误！', array());
            }
        }else{
            if($ordertype==1){
                $where .= " and o.id=:id and b.bid=:bid ";
                $sql = 'select o.status,o.num,o.expirationtime,o.isrefund,o.groupordernum as orderNum,o.num,o.money,o.id as oid,o.paytime,o.addtime,o.gname as order_gname,o.goodsimg as order_pic,o.bname as order_bname,g.gname,g.pic,b.bname from '.tablename('mzhk_sun_ptgroups').' as o left join '.tablename('mzhk_sun_goods').' as g on o.gid=g.gid left join '.tablename('mzhk_sun_brand').' as b on g.bid=b.bid '.$where." order by o.id desc ";
                $status = 4;
            }elseif($ordertype==2){
                $where .= " and o.oid=:id and b.bid=:bid ";
                $sql = 'select o.status,o.num,o.expirationtime,o.isrefund,o.orderNum,o.num,o.money,o.oid,o.paytime,o.addtime,o.gname as order_gname,o.goodsimg as order_pic,o.bname as order_bname,g.gname,g.pic,b.bname from '.tablename('mzhk_sun_kjorder').' as o left join '.tablename('mzhk_sun_goods').' as g on o.gid=g.gid left join '.tablename('mzhk_sun_brand').' as b on g.bid=b.bid '.$where." order by o.oid desc ";
                $status = 3;
            }elseif($ordertype==3){
                $where .= " and o.id=:id and b.bid=:bid ";
                $sql = 'select o.status,o.num,o.expirationtime,o.isrefund,o.ordernum as orderNum,o.money,o.id as oid,o.addtime as paytime,o.addtime,o.gname as order_gname,o.goodsimg as order_pic,o.bname as order_bname,g.gname,g.pic,b.bname from '.tablename('mzhk_sun_cardorder').' as o left join '.tablename('mzhk_sun_goods').' as g on o.gid=g.gid left join '.tablename('mzhk_sun_brand').' as b on g.bid=b.bid '.$where." order by o.id desc ";
                $status = 1;
            }elseif($ordertype==4){
                $where .= " and o.oid=:id and b.bid=:bid ";
                $sql = 'select o.status,o.num,o.expirationtime,o.isrefund,o.orderNum,o.num,o.money,o.oid,o.paytime,o.addtime,o.gname as order_gname,o.goodsimg as order_pic,o.bname as order_bname,g.gname,g.pic,b.bname from '.tablename('mzhk_sun_order').' as o left join '.tablename('mzhk_sun_goods').' as g on o.gid=g.gid left join '.tablename('mzhk_sun_brand').' as b on g.bid=b.bid '.$where." order by o.oid desc ";
                $status = 3;
            }elseif($ordertype==6){
                $where .= " and o.oid=:id and b.bid=:bid ";
                $sql = 'select o.status,o.num,o.expirationtime,o.isrefund,o.islottery,o.orderNum,o.num,o.money,o.oid,o.paytime,o.addtime,o.gname as order_gname,o.goodsimg as order_pic,o.bname as order_bname,g.gname,g.pic,b.bname from '.tablename('mzhk_sun_hyorder').' as o left join '.tablename('mzhk_sun_goods').' as g on o.gid=g.gid left join '.tablename('mzhk_sun_brand').' as b on g.bid=b.bid '.$where." order by o.oid desc ";
                $status = 3;
            }else{
                $where .= " and o.oid=:id and b.bid=:bid ";
                $sql = 'select o.status,o.num,o.expirationtime,o.isrefund,o.orderNum,o.num,o.money,o.oid,o.paytime,o.addtime,o.gname as order_gname,o.goodsimg as order_pic,o.bname as order_bname,g.gname,g.pic,b.bname from '.tablename('mzhk_sun_qgorder').' as o left join '.tablename('mzhk_sun_goods').' as g on o.gid=g.gid left join '.tablename('mzhk_sun_brand').' as b on g.bid=b.bid '.$where." order by o.oid desc ";
                $status = 3;
            }
            $fetchdata[":id"] = $id;
            $fetchdata[":bid"] = $bid;
            $res = pdo_fetch($sql,$fetchdata);
            
            
            if($res){

                if($res["expirationtime"]>0 && $res["expirationtime"]<time()){
                    return $this->result(1, '该订单已经过期了！', array());
                }
                if($res["isrefund"]==1){
                    return $this->result(1, '该订单正在申请退款中，不符合核销状态！', array());
                }elseif($res["isrefund"]==2){
                    return $this->result(1, '该订单已申请退款，不符合核销状态！', array());
                }
                if($ordertype==3){
                    if($res["status"]==2){
                        return $this->result(1, '该订单已经核销过了！', array());
                    }
                }elseif($ordertype==6){
                    if($res["islottery"]!=1){
                        return $this->result(1, '该订单在免单申请中没有中奖，无法核销！', array());
                    }
                    if($res["status"]==2){
                        return $this->result(1, '该订单已经核销过了！', array());
                    }
                }else{
                    if($res["status"]!=$status){
                        return $this->result(1, '该订单不符合核销状态！', array());
                    }
                }
                echo json_encode($res);
            }else{
                return $this->result(1, '该订单不符合核销状态！！！', array());
            }
        }
    }

    
    Public function doPageSetBrandOrder(){
        global $_GPC,$_W;
        $bid = intval($_GPC["bid"]);
        $ordernum = $_GPC["ordernum"];

        
        $brand = pdo_get('mzhk_sun_brand',array('uniacid'=>$_W['uniacid'],'bid'=>$bid),array("totalamount","frozenamount","bname"));

        
        $sql ="select o.oid,o.money,o.orderNum from ".tablename('mzhk_sun_qgorder')." as o LEFT JOIN ".tablename('mzhk_sun_goods')." as g on o.gid=g.gid where o.uniacid=".$_W['uniacid']." and g.bid=".$bid." AND o.orderNum ='".$ordernum."' AND o.status=3 ";
        $qgorder=pdo_fetch($sql);
        if($qgorder){
            pdo_update('mzhk_sun_qgorder', array('status' => 5,"finishtime"=>time()), array('oid' => $qgorder["oid"]));
            
            
            $branddata = array();
            $branddata["totalamount"] = $brand["totalamount"]+$qgorder["money"];
            pdo_update('mzhk_sun_brand', $branddata, array('bid' => $bid));
            
            $data = array();
            $data["bid"] = $bid;
            $data["bname"] = $brand['bname'];
            $data["mcd_type"] = 1;
            $data["mcd_memo"] = "抢购订单-订单id：".$qgorder["oid"].";订单号：".$qgorder["orderNum"]."；";
            $data["addtime"] = time();
            $data["money"] = $qgorder["money"];
            $data["order_id"] = $qgorder["oid"];
            $data["uniacid"] = $_W['uniacid'];
            $data["status"] = 1;
            pdo_insert('mzhk_sun_mercapdetails', $data);
            
            return $this->result(0, '', $branddata);
        }
        
        $sql ="select o.id,o.money,o.groupordernum from ".tablename('mzhk_sun_ptgroups')." as o LEFT JOIN ".tablename('mzhk_sun_goods')." as g on o.gid=g.gid where o.uniacid=".$_W['uniacid']." and g.bid=".$bid." AND o.groupordernum ='".$ordernum."' AND o.status=4 ";
        $ptorder=pdo_fetch($sql);
        if($ptorder){
            pdo_update('mzhk_sun_ptgroups', array('status' => 5,"finishtime"=>time()), array('id' => $ptorder["id"]));

            
            $branddata = array();
            $branddata["totalamount"] = $brand["totalamount"]+$ptorder["money"];
            pdo_update('mzhk_sun_brand', $branddata, array('bid' => $bid));
            
            $data = array();
            $data["bid"] = $bid;
            $data["bname"] = $brand['bname'];
            $data["mcd_type"] = 1;
            $data["mcd_memo"] = "拼团订单-订单id：".$ptorder["id"].";订单号：".$ptorder["groupordernum"]."；";
            $data["addtime"] = time();
            $data["money"] = $ptorder["money"];
            $data["order_id"] = $ptorder["id"];
            $data["uniacid"] = $_W['uniacid'];
            $data["status"] = 1;
            pdo_insert('mzhk_sun_mercapdetails', $data);

            return $this->result(0, '', $branddata);
        }
        
        $sql ="select o.id,o.money,o.ordernum from ".tablename('mzhk_sun_cardorder')." as o LEFT JOIN ".tablename('mzhk_sun_goods')." as g on o.gid=g.gid where o.uniacid=".$_W['uniacid']." and g.bid=".$bid." AND o.ordernum ='".$ordernum."' AND o.status<2 ";
        $jkorder=pdo_fetch($sql);
        if($jkorder){
            pdo_update('mzhk_sun_cardorder', array('status' => 2,"finishtime"=>time()), array('id' => $jkorder["id"]));

            
            $branddata = array();
            $branddata["totalamount"] = $brand["totalamount"]+$jkorder["money"];
            pdo_update('mzhk_sun_brand', $branddata, array('bid' => $bid));
            
            $data = array();
            $data["bid"] = $bid;
            $data["bname"] = $brand['bname'];
            $data["mcd_type"] = 1;
            $data["mcd_memo"] = "集卡订单-订单id：".$jkorder["id"].";订单号：".$jkorder["ordernum"]."；";
            $data["addtime"] = time();
            $data["money"] = $jkorder["money"];
            $data["order_id"] = $jkorder["id"];
            $data["uniacid"] = $_W['uniacid'];
            $data["status"] = 1;
            pdo_insert('mzhk_sun_mercapdetails', $data);

            return $this->result(0, '', $branddata);
        }
        
        $sql ="select o.oid,o.money,o.orderNum from ".tablename('mzhk_sun_kjorder')." as o LEFT JOIN ".tablename('mzhk_sun_goods')." as g on o.gid=g.gid where o.uniacid=".$_W['uniacid']." and g.bid=".$bid." AND o.orderNum ='".$ordernum."' AND o.status=3 ";
        $kjorder=pdo_fetch($sql);
        if($kjorder){
            pdo_update('mzhk_sun_kjorder', array('status' => 5,"finishtime"=>time()), array('oid' => $kjorder["oid"]));

            
            $branddata = array();
            $branddata["totalamount"] = $brand["totalamount"]+$kjorder["money"];
            pdo_update('mzhk_sun_brand', $branddata, array('bid' => $bid));
            
            $data = array();
            $data["bid"] = $bid;
            $data["bname"] = $brand['bname'];
            $data["mcd_type"] = 1;
            $data["mcd_memo"] = "砍价订单-订单id：".$kjorder["oid"].";订单号：".$kjorder["orderNum"]."；";
            $data["addtime"] = time();
            $data["money"] = $kjorder["money"];
            $data["order_id"] = $kjorder["oid"];
            $data["uniacid"] = $_W['uniacid'];
            $data["status"] = 1;
            pdo_insert('mzhk_sun_mercapdetails', $data);

            return $this->result(0, '', $branddata);
        }
        
        $sql ="select o.oid,o.money,o.orderNum from ".tablename('mzhk_sun_order')." as o LEFT JOIN ".tablename('mzhk_sun_goods')." as g on o.gid=g.gid where o.uniacid=".$_W['uniacid']." and g.bid=".$bid." AND o.orderNum ='".$ordernum."' AND o.status=3 ";
        $order=pdo_fetch($sql);
        if($order){
            pdo_update('mzhk_sun_order', array('status' => 5,"finishtime"=>time()), array('oid' => $order["oid"]));

            
            $branddata = array();
            $branddata["totalamount"] = $brand["totalamount"]+$order["money"];
            pdo_update('mzhk_sun_brand', $branddata, array('bid' => $bid));
            
            $data = array();
            $data["bid"] = $bid;
            $data["bname"] = $brand['bname'];
            $data["mcd_type"] = 1;
            $data["mcd_memo"] = "普通订单-订单id：".$order["oid"].";订单号：".$order["orderNum"]."；";
            $data["addtime"] = time();
            $data["money"] = $order["money"];
            $data["order_id"] = $order["oid"];
            $data["uniacid"] = $_W['uniacid'];
            $data["status"] = 1;
            pdo_insert('mzhk_sun_mercapdetails', $data);

            return $this->result(0, '', $branddata);
        }

        return $this->result(1, '该订单不符合核销状态！', array());
    }

    
    Public function doPageSaoBrandOrder(){
        global $_GPC,$_W;
        $bid = intval($_GPC["bid"]);
        $ordertype = intval($_GPC["ordertype"]);
        $id = intval($_GPC["id"]);
        $ordertrue = false;

        
        $brand = pdo_get('mzhk_sun_brand',array('uniacid'=>$_W['uniacid'],'bid'=>$bid),array("totalamount","frozenamount","bname"));

        if($ordertype==1){
            
            $sql ="select o.id,o.isrefund,o.money,o.groupordernum,o.expirationtime from ".tablename('mzhk_sun_ptgroups')." as o LEFT JOIN ".tablename('mzhk_sun_goods')." as g on o.gid=g.gid where o.uniacid=".$_W['uniacid']." and g.bid=".$bid." AND o.id ='".$id."' AND o.status=4 ";
            $order=pdo_fetch($sql);
            if($order){
                if($order["expirationtime"]>0 && $order["expirationtime"]<time()){
                    return $this->result(1, '该订单已经过期，无法核销！', array());
                }
                if($order["isrefund"]==1){
                    return $this->result(1, '该订单正在申请退款中，不符合核销状态！', array());
                }elseif($order["isrefund"]==2){
                    return $this->result(1, '该订单已申请退款，不符合核销状态！', array());
                }
                pdo_update('mzhk_sun_ptgroups', array('status' => 5,"finishtime"=>time()), array('id' => $order["id"]));
                $order["oid"] = $order["id"];
                $order["orderNum"] = $order["groupordernum"];
                $ordertitle = "拼团订单";
                $ordertrue = true;
            }
        }elseif($ordertype==2){
            
            $sql ="select o.oid,o.isrefund,o.money,o.orderNum,o.expirationtime from ".tablename('mzhk_sun_kjorder')." as o LEFT JOIN ".tablename('mzhk_sun_goods')." as g on o.gid=g.gid where o.uniacid=".$_W['uniacid']." and g.bid=".$bid." AND o.oid ='".$id."' AND o.status=3 ";
            $order=pdo_fetch($sql);
            if($order){
                if($order["expirationtime"]>0 && $order["expirationtime"]<time()){
                    return $this->result(1, '该订单已经过期，无法核销！', array());
                }
                if($order["isrefund"]==1){
                    return $this->result(1, '该订单正在申请退款中，不符合核销状态！', array());
                }elseif($order["isrefund"]==2){
                    return $this->result(1, '该订单已申请退款，不符合核销状态！', array());
                }
                pdo_update('mzhk_sun_kjorder', array('status' => 5,"finishtime"=>time()), array('oid' => $order["oid"]));
                $ordertitle = "砍价订单";
                $ordertrue = true;
            }
        }elseif($ordertype==3){
            
            $sql ="select o.id,o.isrefund,o.money,o.ordernum,o.expirationtime from ".tablename('mzhk_sun_cardorder')." as o LEFT JOIN ".tablename('mzhk_sun_goods')." as g on o.gid=g.gid where o.uniacid=".$_W['uniacid']." and g.bid=".$bid." AND o.id ='".$id."' AND o.status < 2 ";
            $order=pdo_fetch($sql);
            if($order){
                if($order["expirationtime"]>0 && $order["expirationtime"]<time()){
                    return $this->result(1, '该订单已经过期，无法核销！', array());
                }
                if($order["isrefund"]==1){
                    return $this->result(1, '该订单正在申请退款中，不符合核销状态！', array());
                }elseif($order["isrefund"]==2){
                    return $this->result(1, '该订单已申请退款，不符合核销状态！', array());
                }
                pdo_update('mzhk_sun_cardorder', array('status' => 2,"finishtime"=>time()), array('id' => $order["id"]));
                $order["oid"] = $order["id"];
                $order["orderNum"] = $order["ordernum"];
                $ordertitle = "集卡订单";
                $ordertrue = true;
            }
        }elseif($ordertype==4){
            
            $sql ="select o.oid,o.isrefund,o.money,o.orderNum,o.expirationtime from ".tablename('mzhk_sun_order')." as o LEFT JOIN ".tablename('mzhk_sun_goods')." as g on o.gid=g.gid where o.uniacid=".$_W['uniacid']." and g.bid=".$bid." AND o.oid ='".$id."' AND o.status=3 ";
            $order=pdo_fetch($sql);
            if($order){
                if($order["expirationtime"]>0 && $order["expirationtime"]<time()){
                    return $this->result(1, '该订单已经过期，无法核销！', array());
                }
                if($order["isrefund"]==1){
                    return $this->result(1, '该订单正在申请退款中，不符合核销状态！', array());
                }elseif($order["isrefund"]==2){
                    return $this->result(1, '该订单已申请退款，不符合核销状态！', array());
                }
                pdo_update('mzhk_sun_order', array('status' => 5,"finishtime"=>time()), array('oid' => $order["oid"]));
                $ordertitle = "普通订单";
                $ordertrue = true;
            }
        }elseif($ordertype==6){
            
            $sql ="select o.oid,o.isrefund,o.money,o.orderNum,o.islottery,o.expirationtime from ".tablename('mzhk_sun_hyorder')." as o LEFT JOIN ".tablename('mzhk_sun_goods')." as g on o.gid=g.gid where o.uniacid=".$_W['uniacid']." and g.bid=".$bid." AND o.oid ='".$id."' AND o.status < 2 ";
            $order=pdo_fetch($sql);
            if($order){
                if($order["expirationtime"]>0 && $order["expirationtime"]<time()){
                    return $this->result(1, '该订单已经过期，无法核销！', array());
                }
                if($order["isrefund"]==1){
                    return $this->result(1, '该订单正在申请退款中，不符合核销状态！', array());
                }elseif($order["isrefund"]==2){
                    return $this->result(1, '该订单已申请退款，不符合核销状态！', array());
                }
                if($order["islottery"]!=1){
                    return $this->result(1, '该订单在免单活动中没有中奖，无法核销！', array());
                }

                pdo_update('mzhk_sun_hyorder', array('status' => 2,"finishtime"=>time()), array('oid' => $order["oid"]));
                $ordertitle = "免单订单";
                $ordertrue = true;
            }
        }elseif($ordertype==10){
            
            $sql ="select o.id,o.isUsed,o.limitTime from ".tablename('mzhk_sun_user_coupon')." as o LEFT JOIN ".tablename('mzhk_sun_coupon')." as g on o.cid=g.id where o.uniacid=".$_W['uniacid']." and g.bid=".$bid." AND o.id ='".$id."' ";
            
            $coupon=pdo_fetch($sql);
            if($coupon){
                if($coupon["isUsed"]==1){
                    return $this->result(1, '该优惠券已经使用过了', array());
                }
                if($coupon["limitTime"]<time()){
                    return $this->result(1, '该优惠券已经过了使用期限！', array());
                }
                pdo_update('mzhk_sun_user_coupon', array('isUsed' => 1,'useTime' => time()), array('id' => $coupon["id"]));
                return $this->result(0, '', $coupon);
            }else{
                return $this->result(1, '该优惠券无法使用', array());
            }
        }else{
            
            $sql ="select o.oid,o.isrefund,o.money,o.orderNum,o.expirationtime from ".tablename('mzhk_sun_qgorder')." as o LEFT JOIN ".tablename('mzhk_sun_goods')." as g on o.gid=g.gid where o.uniacid=".$_W['uniacid']." and g.bid=".$bid." AND o.oid ='".$id."' AND o.status=3 ";
            $order=pdo_fetch($sql);
            if($order){
                if($order["expirationtime"]>0 && $order["expirationtime"]<time()){
                    return $this->result(1, '该订单已经过期，无法核销！', array());
                }
                if($order["isrefund"]==1){
                    return $this->result(1, '该订单正在申请退款中，不符合核销状态！', array());
                }elseif($order["isrefund"]==2){
                    return $this->result(1, '该订单已申请退款，不符合核销状态！', array());
                }
                pdo_update('mzhk_sun_qgorder', array('status' => 5,"finishtime"=>time()), array('oid' => $order["oid"]));
                $ordertitle = "抢购订单";
                $ordertrue = true;
            }
        }

        if($ordertrue){
            
            $branddata = array();
            $branddata["totalamount"] = $brand["totalamount"]+$order["money"];
            pdo_update('mzhk_sun_brand', $branddata, array('bid' => $bid));
            
            $data = array();
            $data["bid"] = $bid;
            $data["bname"] = $brand['bname'];
            $data["mcd_type"] = 1;
            $data["mcd_memo"] = $ordertitle."-订单id：".$order["oid"].";订单号：".$order["orderNum"]."；";
            $data["addtime"] = time();
            $data["money"] = $order["money"];
            $data["order_id"] = $order["oid"];
            $data["uniacid"] = $_W['uniacid'];
            $data["status"] = 1;
            pdo_insert('mzhk_sun_mercapdetails', $data);
            return $this->result(0, '', $branddata);
        }else{
            return $this->result(1, '该订单不符合核销状态！', array());
        }
    }

    
    Public function doPageGetBrandMoney(){
        global $_GPC,$_W;
        $bid = intval($_GPC["bid"]);
        $uniacid = $_W['uniacid'];

        
        $brand = pdo_get('mzhk_sun_brand',array('uniacid'=>$uniacid,'bid'=>$bid),array("totalamount","frozenamount","commission"));
        $brand["canuseamount"] = sprintf("%.0f", ($brand["totalamount"] - $brand["frozenamount"]));
        $data = $brand;

        
        $withdrawset = pdo_get('mzhk_sun_withdrawset',array('uniacid'=>$uniacid));
        $wd_type = array(1=>"微信",2=>"支付宝",3=>"银行卡");
        $wd_rates = array(1=>$withdrawset["wd_wxrates"],2=>$withdrawset["wd_alipayrates"],3=>$withdrawset["wd_bankrates"]);
        if($withdrawset["wd_type"]){
            $wd_type_arr = explode(",",$withdrawset["wd_type"]);
            foreach($wd_type_arr as $k=>$v){
                $data["wd_type"][$k]["name"] = $wd_type[$v];
                $data["wd_type"][$k]["wd_rates"] = $wd_rates[$v];
                $data["wd_type"][$k]["id"] = $v;
            }
        }else{
            $data["wd_type"][0]["name"] = $wd_type[1];
            $data["wd_type"][0]["wd_rates"] = $wd_rates[1];
            $data["wd_type"][0]["id"] = 1;
        }

        $data["min_money"] = $withdrawset["min_money"];
        $data["wd_rates"] = $withdrawset["wd_rates"];
        $data["wd_content"] = $withdrawset["wd_content"];
        $data["is_open"] = $withdrawset["is_open"];
        if($brand["commission"]<=0){
            $data["commission"] = $withdrawset["cms_rates"];
        }

        echo json_encode($data);
    }

    
    Public function doPageSaveWithDraw(){
        global $_GPC,$_W;
        $uniacid = $_W['uniacid'];
        $openid = $_GPC["openid"];
        $bid = intval($_GPC["bid"]);
        $wd_type = intval($_GPC["wd_type"]);
        $money = $_GPC["money"];
        $account = $_GPC["account"];
        $uname = $_GPC["uname"];
        $phone = $_GPC["phone"];
        $isauto = false;

        if($money<=0){
            return $this->result(1, '提现金额必须大于0！', array());
        }

        
        $withdrawset = pdo_get('mzhk_sun_withdrawset',array('uniacid'=>$uniacid));
        if($withdrawset["is_open"]==2){
            return $this->result(1, '提现已经关闭，请联系平台管理员！', array());
        }
        if($withdrawset["min_money"]>$money && $withdrawset["min_money"]>0){
            return $this->result(1, '最低提现金额为'.$withdrawset["min_money"].'，请重新提交！', array());
        }
        
        $brand = pdo_get('mzhk_sun_brand',array('uniacid'=>$uniacid,'bid'=>$bid),array("commission","totalamount","frozenamount","bname"));
        
        $canusemoney = $brand["totalamount"] - $brand["frozenamount"];
        if($canusemoney<$money){
            return $this->result(1, '提现的金额必须比可提现余额少或相等！', array());
        }

        
        
        if($brand["commission"]>0){
            $commission = $brand["commission"];
        }else{
            $commission = $withdrawset["cms_rates"];
        }
        $paycommission = $money * $commission / 100;
        $paycommission = round($paycommission,2);
        if($wd_type==1){
            $rates = $withdrawset["wd_wxrates"];
        }elseif($wd_type==2){
            $rates = $withdrawset["wd_alipayrates"];
        }elseif($wd_type==3){
            $rates = $withdrawset["wd_bankrates"];
        }
        
        $lavemoney = round(($money - $paycommission),2);
        
        if($rates>0){
            $ratesmoney = round(($lavemoney*$rates/100),2);
        }else{
            $ratesmoney = 0;
        }
        
        
        $realmoney = round(($lavemoney - $ratesmoney),2);
        
        

        if($withdrawset["avoidmoney"]>=$money){
            $isauto = true;
        }
        
        if($wd_type==1 && $isauto){
            include IA_ROOT . '/addons/mzhk_sun/wxfirmpay.php';
            $appData = pdo_get('mzhk_sun_system', array('uniacid' => $uniacid));
            $mch_appid = $appData['appid'];
            $mchid = $appData['mchid'];
            $key = $appData['wxkey'];
            $openid = $openid;
            $partner_trade_no = $mchid.time().rand(100000,999999);
            $re_user_name = $uname;
            $desc = "提现自动打款";
            $amount = $realmoney*100;
            $apiclient_cert = IA_ROOT . "/addons/mzhk_sun/cert/".$appData['apiclient_cert'];
            $apiclient_key = IA_ROOT . "/addons/mzhk_sun/cert/".$appData['apiclient_key'];

            if($appData["appid"]=='' || $appData["mchid"]=='' || $appData["wxkey"]=='' || $appData["apiclient_cert"]=='' || $appData["apiclient_key"]==''){
                return $this->result(1, '小程序配置错误，请联系平台管理员！', $return);
            }

            $weixinfirmpay = new WeixinfirmPay($mch_appid, $mchid, $key, $openid,$partner_trade_no,$re_user_name,$desc,$amount,$apiclient_cert,$apiclient_key);
            $return = $weixinfirmpay->pay();

            if($return['result_code']=='SUCCESS'){
                
                $data_brand_up["totalamount"] = $brand["totalamount"] - $money;
                pdo_update('mzhk_sun_brand', $data_brand_up, array('bid' => $bid));

                
                $wd_data = array();
                $wd_data["bid"] = $bid;
                $wd_data["bname"] = $brand['bname'];
                $wd_data["openid"] = $openid;
                $wd_data["money"] = $money;
                $wd_data["wd_type"] = $wd_type;
                $wd_data["wd_account"] = $account;
                $wd_data["wd_name"] = $uname;
                $wd_data["wd_phone"] = $phone;
                $wd_data["status"] = 3;
                $wd_data["realmoney"] = $realmoney;
                $wd_data["paycommission"] = $paycommission;
                $wd_data["ratesmoney"] = $ratesmoney;
                $wd_data["uniacid"] = $uniacid;
                $res = pdo_insert('mzhk_sun_withdraw', $wd_data);
                $wd_id = pdo_insertid();

                
                $data = array();
                $data["bid"] = $bid;
                $data["bname"] = $brand['bname'];
                $data["mcd_type"] = 2;
                $data["mcd_memo"] = "商家提现-自动-提现总金额:".$money."元；支付佣金:".$paycommission."元；支付手续费:".$ratesmoney."元；实际提现金额:".$realmoney."元";
                $data["addtime"] = time();
                $data["money"] = $money;
                $data["paycommission"] = $paycommission;
                $data["ratesmoney"] = $ratesmoney;
                $data["wd_id"] = $wd_id;
                $data["uniacid"] = $uniacid;
                $data["status"] = 1;
                pdo_insert('mzhk_sun_mercapdetails', $data);
            }else{
                return $this->result(1, '提现失败，平台绑定的微信商户号余额不足，请联系平台管理员！', array());
            }
        }else{
            
            $data_brand_up["frozenamount"] = $brand["frozenamount"] + $money;
            pdo_update('mzhk_sun_brand', $data_brand_up, array('bid' => $bid));

            $wd_data = array();
            $wd_data["bid"] = $bid;
            $wd_data["bname"] = $brand['bname'];
            $wd_data["openid"] = $openid;
            $wd_data["money"] = $money;
            $wd_data["wd_type"] = $wd_type;
            $wd_data["wd_account"] = $account;
            $wd_data["wd_name"] = $uname;
            $wd_data["wd_phone"] = $phone;
            $wd_data["status"] = 0;
            $wd_data["realmoney"] = $realmoney;
            $wd_data["paycommission"] = $paycommission;
            $wd_data["ratesmoney"] = $ratesmoney;
            $wd_data["uniacid"] = $uniacid;
            $res = pdo_insert('mzhk_sun_withdraw', $wd_data);
            $wd_id = pdo_insertid();
        }

        return $this->result(0, '', $data);

    }

    
    public function doPageGetStoreInfo(){
        global $_W, $_GPC;
        $uniacid = $_W["uniacid"];
        $bid = intval($_GPC["bid"]);
        $sql="select bname,img,address,longitude,latitude,memdiscount from " . tablename("mzhk_sun_brand") . " WHERE status=2 and uniacid=:uniacid and bid=:bid ";
        $res=pdo_fetch($sql,array(':uniacid'=>$uniacid,':bid'=>$bid));
        if($res){
            echo json_encode($res);
        }else{
            echo 2;
        }
    }

    
    public function doPagePayOffline(){
        global $_W, $_GPC;
        $uniacid = $_W["uniacid"];
        $bid = intval($_GPC["bid"]);
        $price = $_GPC["price"];
        $mer_id = intval($_GPC["mer_id"]);

        
        echo 2;
        exit;

        
        $mercapdetails = pdo_get('mzhk_sun_mercapdetails',array('uniacid'=>$uniacid,'id'=>$mer_id));
        if($mercapdetails["status"]==1){
            echo 2;
            exit;
        }

        
        $brand = pdo_get('mzhk_sun_brand',array('uniacid'=>$uniacid,'bid'=>$bid),array("memdiscount","bname","bind_openid"));

        include IA_ROOT . '/addons/mzhk_sun/wxfirmpay.php';
        $appData = pdo_get('mzhk_sun_system', array('uniacid' => $uniacid));
        $mch_appid = $appData['appid'];
        $mchid = $appData['mchid'];
        $key = $appData['wxkey'];
        $openid = $brand["bind_openid"];
        $partner_trade_no = $mchid.time().rand(100000,999999);
        $re_user_name = $brand["bname"];
        $desc = "线下付款-自动打款";
        $offlinefee = 0;
        
        if($appData["offlinefee"]>0){
            $offlinefee = $price*$appData["offlinefee"]/100;
            $amount = sprintf("%.2f", ($price - $offlinefee));
            $amount = $amount*100;
        }else{
            $amount = $price*100;
        }

        $apiclient_cert = IA_ROOT . "/addons/mzhk_sun/cert/".$appData['apiclient_cert'];
        $apiclient_key = IA_ROOT . "/addons/mzhk_sun/cert/".$appData['apiclient_key'];

        if($appData["appid"]=='' || $appData["mchid"]=='' || $appData["wxkey"]=='' || $appData["apiclient_cert"]=='' || $appData["apiclient_key"]==''){
            return $this->result(1, '小程序配置错误，请联系平台管理员！', $return);
        }
        if(empty($openid)){
            return $this->result(1, '当前商家没有绑定微信号，请联系平台管理员！', $return);
        }

        $weixinfirmpay = new WeixinfirmPay($mch_appid, $mchid, $key, $openid,$partner_trade_no,$re_user_name,$desc,$amount,$apiclient_cert,$apiclient_key);
        $return = $weixinfirmpay->pay();

        if($return['result_code']=='SUCCESS'){
            
            
            
            
            
            
            
            
            
            
            
            $data = array();
            $data["status"] = 1;
            $data["mcd_memo"] = "线下付款-直接打款给商家-支付金额:".$price."元,商家实收".($amount/100)."元，收取手续费".$offlinefee."元";
            pdo_update('mzhk_sun_mercapdetails', $data, array('id' => $mer_id));
            return $this->result(0, '', array());
        }else{
            $data = array();
            $data["status"] = 2;
            $data["mcd_memo"] = "线下付款-直接打款给商家-支付金额:".$price."元,用户付款到平台微信商户号成功，由于绑定微信商户号问题导致无法付款给商家；错误代码".$return['result_code']."-错误信息:".$return['return_msg'].";（".$return['err_code_des']."）";
            pdo_update('mzhk_sun_mercapdetails', $data, array('id' => $mer_id));
            return $this->result(1, '用户支付成功，然而平台绑定的微信商户号余额不足或者超过当日限额，无法支付给商家，请联系平台管理员！', array());
        }
    }

    public function doPageGetBrandOrder(){
        global $_W, $_GPC;
        $orderstatus = intval($_GPC["orderstatus"]);
        $bid = intval($_GPC["bid"]);
        $ordertype = intval($_GPC["ordertype"]);
        $fetchdata = array();

        $pagesize = 5;
        $pageindex = intval($_GPC['page'])*$pagesize;

        $where = " where o.uniacid='".$_W['uniacid']."' AND b.bid='".$bid."' ";
        if($ordertype==1){
            if($orderstatus==43){
                $where .= " and o.status=4 and (o.sincetype like'%送货上门%' or o.sincetype like'%快递%') and (o.isrefund=0 or o.isrefund=3)";
            }elseif($orderstatus==41){
                $where .= " and (o.status=4 and o.isrefund=1) ";
            }elseif($orderstatus==4){
                $where .= " and o.status=:status and (o.isrefund=0 or o.isrefund=3) and (o.sincetype like'%到店消费%' or o.sincetype like'%上门自提%') ";
                $fetchdata[":status"] = $orderstatus;
            }elseif($orderstatus==5){
                $where .= " and (o.isrefund=2 or (o.status=:status and o.isrefund=0)) ";
                $fetchdata[":status"] = $orderstatus;
            }else{
                $where .= " and o.status=:status and o.isrefund=0";
                $fetchdata[":status"] = $orderstatus;
            }
        }elseif($ordertype==3){
            if($orderstatus==13){
                $where .= " and o.status=0 and (o.sincetype like'%送货上门%' or o.sincetype like'%快递%') and (o.isrefund=0 or o.isrefund=3)";
            }elseif($orderstatus==1){
                $where .= " and o.status=0 and (o.isrefund=0 or o.isrefund=3) and (o.sincetype like'%到店消费%' or o.sincetype like'%上门自提%') ";
                $fetchdata[":status"] = $orderstatus;
            }elseif($orderstatus==11){
                $where .= " and o.status=1 and (o.isrefund=0 or o.isrefund=3)";
                $fetchdata[":status"] = $orderstatus;
            }elseif($orderstatus==2){
                $where .= " and (o.isrefund=2 or (o.status=:status and o.isrefund=0))";
                $fetchdata[":status"] = $orderstatus;
            }else{
                $where .= " and o.status=:status and o.isrefund=0";
                $fetchdata[":status"] = $orderstatus;
            }
        }elseif($ordertype==6){
            if($orderstatus==2){
                $where .= " and o.islottery=2";
            }elseif($orderstatus==10){
                $where .= " and o.status < 2 and o.islottery=1 ";
                $fetchdata[":status"] = $orderstatus;
            }elseif($orderstatus==12){
                $where .= " and o.status = 2 and o.islottery=1";
                $fetchdata[":status"] = $orderstatus;
            }else{
                $where .= " and o.status=:status and o.isrefund=0";
                $fetchdata[":status"] = $orderstatus;
            }
        }else{
            if($orderstatus==33){
                $where .= " and o.status=3 and (o.sincetype like'%送货上门%' or o.sincetype like'%快递%') and (o.isrefund=0 or o.isrefund=3)";
            }elseif($orderstatus==31){
                $where .= " and (o.status=3 and o.isrefund=1) ";
            }elseif($orderstatus==3){
                $where .= " and o.status=:status and (o.isrefund=0 or o.isrefund=3) and (o.sincetype like'%到店消费%' or o.sincetype like'%上门自提%') ";
                $fetchdata[":status"] = $orderstatus;
            }elseif($orderstatus==5){
                $where .= " and (o.isrefund=2 or (o.status=:status and o.isrefund=0)) ";
                $fetchdata[":status"] = $orderstatus;
            }else{
                $where .= " and o.status=:status and o.isrefund=0";
                $fetchdata[":status"] = $orderstatus;
            }
        }

        if($ordertype==1){
            $sql = 'select o.status,o.name,o.telnumber,o.addtime,o.sincetype,o.isrefund,o.num,o.money,o.id as oid,o.gname as order_gname,o.goodsimg as order_pic,o.bname as order_bname,g.gname,g.pic,b.bname from '.tablename('mzhk_sun_ptgroups').' as o left join '.tablename('mzhk_sun_goods').' as g on o.gid=g.gid left join '.tablename('mzhk_sun_brand').' as b on g.bid=b.bid '.$where." order by o.id desc limit ".$pageindex.",".$pagesize;
        }elseif($ordertype==2){
            $sql = 'select o.status,o.name,o.telnumber,o.addtime,o.sincetype,o.isrefund,o.num,o.money,o.oid,o.gname as order_gname,o.goodsimg as order_pic,o.bname as order_bname,g.gname,g.pic,b.bname from '.tablename('mzhk_sun_kjorder').' as o left join '.tablename('mzhk_sun_goods').' as g on o.gid=g.gid left join '.tablename('mzhk_sun_brand').' as b on g.bid=b.bid '.$where." order by o.oid desc limit ".$pageindex.",".$pagesize;
        }elseif($ordertype==3){
            $sql = 'select o.status,o.name,o.telnumber,o.addtime,o.sincetype,o.isrefund,o.num,o.money,o.id as oid,o.gname as order_gname,o.goodsimg as order_pic,o.bname as order_bname,g.gname,g.pic,b.bname from '.tablename('mzhk_sun_cardorder').' as o left join '.tablename('mzhk_sun_goods').' as g on o.gid=g.gid left join '.tablename('mzhk_sun_brand').' as b on g.bid=b.bid '.$where." order by o.id desc limit ".$pageindex.",".$pagesize;
        }elseif($ordertype==4){
            $sql = 'select o.status,o.name,o.telnumber,o.addtime,o.sincetype,o.isrefund,o.num,o.money,o.oid,o.gname as order_gname,o.goodsimg as order_pic,o.bname as order_bname,g.gname,g.pic,b.bname from '.tablename('mzhk_sun_order').' as o left join '.tablename('mzhk_sun_goods').' as g on o.gid=g.gid left join '.tablename('mzhk_sun_brand').' as b on g.bid=b.bid '.$where." order by o.oid desc limit ".$pageindex.",".$pagesize;
        }elseif($ordertype==6){
            $sql = 'select o.status,o.name,o.telnumber,o.islottery,o.addtime,o.sincetype,o.isrefund,o.num,o.money,o.oid,o.gname as order_gname,o.goodsimg as order_pic,o.bname as order_bname,g.gname,g.pic,b.bname from '.tablename('mzhk_sun_hyorder').' as o left join '.tablename('mzhk_sun_goods').' as g on o.gid=g.gid left join '.tablename('mzhk_sun_brand').' as b on g.bid=b.bid '.$where." order by o.oid desc limit ".$pageindex.",".$pagesize;
        }else{
            $sql = 'select o.status,o.name,o.telnumber,o.addtime,o.sincetype,o.isrefund,o.num,o.money,o.oid,o.gname as order_gname,o.goodsimg as order_pic,o.bname as order_bname,g.gname,g.pic,b.bname from '.tablename('mzhk_sun_qgorder').' as o left join '.tablename('mzhk_sun_goods').' as g on o.gid=g.gid left join '.tablename('mzhk_sun_brand').' as b on g.bid=b.bid '.$where." order by o.oid desc limit ".$pageindex.",".$pagesize;
        }
        

        $data = pdo_fetchall($sql,$fetchdata);
        foreach($data as $k => $v){
            $data[$k]["addtime"] = date("Y-m-d H:i:s",$v["addtime"]);
        }
        if($data){
            echo json_encode($data);
        }else{
            echo 2;
        }

    }

    
    public function doPageGetOtherApplets(){
        global $_W, $_GPC;
        $uniacid=$_W['uniacid'];
        $position = intval($_GPC['position']);
        $id = intval($_GPC['id']);
        if($id>0){
            $res=pdo_get('mzhk_sun_wxappjump',array('id'=>$id,'uniacid'=>$uniacid));
        }else{
            $res=pdo_get('mzhk_sun_wxappjump',array('position'=>$position,'uniacid'=>$uniacid));
        }

        if($position==1){
            
            $goods=pdo_get('mzhk_sun_goods',array('uniacid'=>$uniacid,'is_hyopen'=>1),array("is_hyopen"));
            if($goods){
                $data["is_hyopen"] = $goods["is_hyopen"];
            }else{
                $data["is_hyopen"] = 2;
            }
        }
        $data["wxappjump"] = $res;
        if($data){
            echo json_encode($data);
        }else{
            echo "2";
            
        }
    }

    
    public function doPageGetadData(){
        global $_W, $_GPC;
        $uniacid=$_W['uniacid'];
        $position = intval($_GPC['position']);
        $inpos = $_GPC["inpos"];
        if($position){
            $sql = 'select pop_title,pop_urltype,pop_urltxt,pop_img,position,unselectimg from '.tablename('mzhk_sun_popbanner').' where uniacid='.$uniacid.' and position ='.$position.' and isshow=1 order by sort asc,id DESC ';
            $popbanner = pdo_fetchall($sql);

        }elseif(!empty($inpos)){
            $inid_in = explode(",",$inpos);
            $inid_str = implode(",",$inid_in);
            $popbanner_data = pdo_getall('mzhk_sun_popbanner',array('uniacid'=>$uniacid,'position'=>$inid_in,'isshow'=>1),array("pop_title","pop_urltype","pop_urltxt","pop_img","position","unselectimg"),"",array("sort asc","id DESC"));
            
            $popbanner = array();
            if($popbanner_data){
                $pop_list = array(
                    1=>"pop",
                    2=>"flash",
                    3=>"cut",
                    4=>"collect",
                    5=>"timebuy",
                    6=>"groupbuy",
                    7=>"free",
                    8=>"tbbanner",
                    9=>"tabbar",
                    10=>"adone",
                    11=>"adtwo",
                    12=>"circle",
                    13=>"homebuoy",
                    14=>"mybanner",
                );
                foreach($popbanner_data as $k => $v){
                    $popbanner[$pop_list[$v["position"]]][] = $v;
                }
            }
        }else{
            $popbanner = pdo_getall('mzhk_sun_popbanner',array('uniacid'=>$uniacid,'isshow'=>1),array("pop_title","pop_urltype","pop_urltxt","pop_img","position","unselectimg"),"",array("sort asc","id DESC"));
        }
        if($popbanner){
            echo json_encode($popbanner);
        }else{
            echo 2;
        }
    }

    
    public function doPageSaveCircle(){
        global $_W, $_GPC;
        $data['uid'] = intval($_GPC['user_id']);
        $data['content'] = $_GPC['content'];
        $data['openid'] = $_GPC['openid']; 
        $data['img'] = $_GPC['pic']; 
        $data['addtime'] = time();
        $data['uniacid'] = $_W['uniacid'];
        if(empty($data['content']) && empty($data['img'])){
            return $this->result(1, '内容和图片不能为空！', array());
        }
        if(empty($data['openid'])){
            return $this->result(1, '参数错误，00001！', array());
        }
        
        $sinfo=pdo_get('mzhk_sun_system',array('uniacid'=>$_W['uniacid']),array("is_open_circle"));
        if($sinfo["is_open_circle"]==1){
            $data['isshow'] = 0; 
        }

        $res=pdo_insert('mzhk_sun_circle',$data);
        if($res){
            
            $datas['user_id']=$_GPC['user_id'];
            $datas['form_id']=$_GPC['form_id'];
            $datas['openid']=$_GPC['openid']; 
            $datas['time']=date('Y-m-d H:i:s');
            $datas['uniacid']=$_W['uniacid'];
            $res=pdo_insert('mzhk_sun_userformid',$datas);
            echo "1";
        }else{
            return $this->result(1, '参数错误，00002！', array());
        }
    }

    
    public function doPageSaveCircleComment(){
        global $_W, $_GPC;
        $data['uid'] = intval($_GPC['user_id']);
        $data['content'] = $_GPC['content'];
        $data['openid'] = $_GPC['openid']; 
        $data['cid'] = $_GPC['cid']; 
        $data['uname'] = $_GPC['uname']; 
        $data['uimg'] = $_GPC['uimg']; 
        $data['addtime'] = time();
        $data['uniacid'] = $_W['uniacid'];

        if(empty($data['content'])){
            return $this->result(1, '评论内容不能为空！', array());
        }
        if(empty($data['openid'])){
            return $this->result(1, '参数错误，00001！', array());
        }
        $res=pdo_insert('mzhk_sun_circlecomment',$data);
        if($res){
            
            pdo_update('mzhk_sun_circle',array("commentnum +="=>1),array('id'=>$data['cid'],'uniacid'=>$_W['uniacid']));
            
            $datas['user_id']=$_GPC['user_id'];
            $datas['form_id']=$_GPC['form_id'];
            $datas['openid']=$_GPC['openid']; 
            $datas['time']=date('Y-m-d H:i:s');
            $datas['uniacid']=$_W['uniacid'];
            $res=pdo_insert('mzhk_sun_userformid',$datas);
            echo "1";
        }else{
            return $this->result(1, '参数错误，00002！', array());
        }
    }

    
    public function doPageSaveCircleLike(){
        global $_W, $_GPC;
        $data['uid'] = intval($_GPC['user_id']);
        $data['openid'] = $_GPC['openid']; 
        $data['cid'] = $_GPC['cid']; 
        $data['uimg'] = $_GPC['uimg']; 
        $data['addtime'] = time();
        $data['uniacid'] = $_W['uniacid'];
        $islike = intval($_GPC['islike']); 

        if(empty($data['openid'])){
            return $this->result(1, '参数错误，00001！', array());
        }
        if($islike==1){
            $res=pdo_delete('mzhk_sun_circlelike',array('cid'=>$data['cid'],'uniacid'=>$_W['uniacid'],'openid'=>$data['openid']));
            if($res){
                $circle = pdo_get('mzhk_sun_circle',array('uniacid'=>$_W['uniacid'],'id'=>$data['cid']),array("likenum"));
                if($circle["likenum"]>0){
                    
                    pdo_update('mzhk_sun_circle',array("likenum -="=>1),array('id'=>$data['cid'],'uniacid'=>$_W['uniacid']));
                }
                echo "1";
            }else{
                return $this->result(1, '参数错误，00002！', array());
            }
        }else{
            $circlelike = pdo_get('mzhk_sun_circlelike',array('uniacid'=>$_W['uniacid'],'cid'=>$data['cid'],'openid'=>$data['openid']),array("id"));
            if($circlelike){
                return $this->result(1, '您已经点过赞了！', array());
            }else{
                $res=pdo_insert('mzhk_sun_circlelike',$data);
                if($res){
                    
                    pdo_update('mzhk_sun_circle',array("likenum +="=>1),array('id'=>$data['cid'],'uniacid'=>$_W['uniacid']));
                    echo "1";
                }else{
                    return $this->result(1, '参数错误，00002！', array());
                }
            }
        }

        
    }

    
    public function doPageGetCircle(){
        global $_W, $_GPC;
        $uniacid = $_W["uniacid"];
        $openid=$_GPC['openid'];

        $pagesize = 3;
        $pageindex = intval($_GPC['page'])*$pagesize;

        $sql = "select c.id,c.content,c.img,c.addtime,c.commentnum,c.likenum,u.img as userimg,u.name from ".tablename('mzhk_sun_circle')." as c left join ".tablename('mzhk_sun_user')." as u on c.openid=u.openid where c.uniacid=".$uniacid." and c.isshow=1 order by c.id desc limit ".$pageindex.",".$pagesize;
        
        $data = pdo_fetchall($sql);
        if($data){
            include IA_ROOT . '/addons/mzhk_sun/inc/func/func.php';
            foreach ($data as $k=>$v){
                $data[$k]['addtime'] = time_tran($v["addtime"]);
                if(!empty($v["img"])){
                    $data[$k]['img'] = explode(",",$v["img"]);
                }else{
                    $data[$k]['img'] = array();
                }
                
                $havelike = pdo_get('mzhk_sun_circlelike',array('uniacid'=>$uniacid,'cid'=>$v["id"],'openid'=>$openid),array("id"));
                if($havelike){
                    $data[$k]['islike'] = 1;
                }else{
                    $data[$k]['islike'] = 0;
                }
                
                $like = pdo_getall('mzhk_sun_circlelike',array('uniacid'=>$uniacid,'cid'=>$v["id"]),array("id","uimg","openid"));
                if($like){
                    $data[$k]['like'] = $like;
                }else{
                    $data[$k]['like'] = array();
                }
                
                $comment = pdo_getall('mzhk_sun_circlecomment',array('uniacid'=>$uniacid,'cid'=>$v["id"]),array("id","uimg","uname","content"),"",array("id DESC"), array(0,5));
                $data[$k]['compage'] = 1;
                if($comment){
                    $data[$k]['comment'] = $comment;
                }else{
                    $data[$k]['comment'] = array();
                }
                
                if ($v["commentnum"]>5) {
                    $data[$k]['showcommore'] = 1;
                }else{
                    $data[$k]['showcommore'] = 0;
                }

            }
            echo json_encode($data);
        }else{
            echo 2;
        }
    }

    
    public function doPageGetIndexCircle(){
        global $_W, $_GPC;
        $uniacid = $_W["uniacid"];
        $openid=$_GPC['openid'];

        $sql = "select c.id,c.content,c.img,c.addtime,c.commentnum,c.likenum,u.img as userimg,u.name from ".tablename('mzhk_sun_circle')." as c left join ".tablename('mzhk_sun_user')." as u on c.openid=u.openid where (c.content is not null and
         c.content !='') and c.uniacid=".$uniacid." and c.isshow=1 order by c.id desc limit 10";
        
        $data = pdo_fetchall($sql);
        if($data){
            include IA_ROOT . '/addons/mzhk_sun/inc/func/func.php';
            foreach ($data as $k=>$v){
                $data[$k]['addtime'] = time_tran($v["addtime"]);
                if(!empty($v["img"])){
                    $data[$k]['img'] = explode(",",$v["img"]);
                }else{
                    $data[$k]['img'] = array();
                }
            }
            echo json_encode($data);
        }else{
            echo 2;
        }
    }

    
    public function doPageGetCircleComment(){
        global $_W, $_GPC;
        $uniacid = $_W["uniacid"];
        $openid=$_GPC['openid'];
        $cid = intval($_GPC["cid"]);

        $pagesize = 5;
        $pageindex = intval($_GPC['page'])*$pagesize;

        $sql = "select id,uimg,uname,content from ".tablename('mzhk_sun_circlecomment')." where uniacid=".$uniacid." and cid=".$cid." order by id desc limit ".$pageindex.",".$pagesize;
        
        $data = pdo_fetchall($sql);
        if($data){
            echo json_encode($data);
        }else{
            echo 2;
        }

    }

    
    public function doPageGetRechargeCard(){
        global $_W, $_GPC;
        $uniacid = $_W["uniacid"];
        $sql = "select id,title,money,lessmoney from ".tablename('mzhk_sun_rechargecard')." where uniacid=".$uniacid." and status=1 order by sort asc,id desc ";
        $data = pdo_fetchall($sql);
        if($data){
            echo json_encode($data);
        }else{
            echo 2;
        }
    }

    
    public function doPageBuyRechargeCard(){
        global $_W, $_GPC;
        $uniacid = $_W["uniacid"];
        $openid=$_GPC['openid'];
        if(empty($openid)){
            return $this->result(1, '参数错误，00001！', array());
        }
        $id=intval($_GPC['id']);
        if($id>0){
            $sql = "select id,title,money,lessmoney,status from ".tablename('mzhk_sun_rechargecard')." where uniacid=".$uniacid." and id=".$id." ";
            $res = pdo_fetch($sql);
            if($res){
                if($res["status"]==0){
                    return $this->result(1, '该充值卡已无效！', array());
                }
                $allmoney = $res["money"] + $res["lessmoney"];
                $money = $res["money"];
                $lessmoney = $res["lessmoney"];
                $rtype = 1;
                $memo = "购买充值卡充值，id：".$id;
            }else{
                return $this->result(1, '参数错误，充值卡获取失败，00002！', array());
            }
        }else{
            $allmoney = $_GPC["price"];
            $money = $_GPC["price"];
            $lessmoney = $res["lessmoney"];
            $rtype = 0;
            $memo = "直接充值";
        }
        if($allmoney>0){
            pdo_update("mzhk_sun_user",array("money +="=>$allmoney),array("openid"=>$openid));

            $data["openid"] = $openid;
            $data["rc_id"] = $id;
            $data["money"] = $money;
            $data["addmoney"] = $lessmoney;
            $data["addtime"] = time();
            $data["rtype"] = $rtype;
            $data["memo"] = $memo;
            $data["uniacid"] = $uniacid;
            $res=pdo_insert('mzhk_sun_rechargelogo',$data);
            echo json_encode($allmoney);
        }else{
            return $this->result(1, '参数错误，充值失败，00003！', array());
        }
        
    }

    
    public function doPageSaveFormid(){
        global $_W, $_GPC;
        $data['user_id']=$_GPC['user_id'];
        $data['form_id']=$_GPC['form_id'];
        $data['openid']=$_GPC['openid']; 
        $data['time']=date('Y-m-d H:i:s');
        $data['uniacid']=$_W['uniacid'];
        $res=pdo_insert('mzhk_sun_userformid',$data);
        if($res){
            echo  '1';
        }else{
            echo  '2';
        }
    }

    
    public function doPageSendSms(){
        global $_W, $_GPC;
        $order_id = $_GPC['order_id'];
        $uniacid = $_W['uniacid'];
        $ordertype = intval($_GPC['ordertype']);
        if($ordertype==3){
            $res=pdo_get('mzhk_sun_cardorder',array('id'=>$order_id,'uniacid'=>$uniacid),array("bid","ordernum"));
        }elseif($ordertype==6){
            $res=pdo_get('mzhk_sun_hyorder',array('oid'=>$order_id,'uniacid'=>$uniacid),array("bid","ordernum"));
        }
        if($res["bid"]>0){
            $this->SendSms($res["bid"],0,$res["ordernum"]);
        }
    }

    
    public function SendSms($bid=0,$smstype=0,$ordernum=0){
        global $_W, $_GPC;
        $bid = $bid;
        $uniacid = $_W['uniacid'];
        $smstype = $smstype;
        $res=pdo_get('mzhk_sun_brand',array('uniacid'=>$uniacid,'bid'=>$bid),array("phone"));
        $phone = $res["phone"]?$res["phone"]:0;

        $sms=pdo_get('mzhk_sun_sms',array('uniacid'=>$uniacid));
        if($sms){
            if($sms["is_open"]==1){
                if($sms["smstype"]==1){
                    $msg = $smstype==1?$sms["ytx_orderrefund"]:$sms["ytx_order"];
                    if($msg!=''){
                        $params = $phone.",".$ordernum;
                        $this->SendYtxSms($msg,$sms,$phone);
                    }
                }elseif($sms["smstype"]==2){
                    $sendid = $smstype==1?$sms["order_refund_tplid"]:$sms["order_tplid"];
                    if($sendid<=0){
                        echo "短信模板id为空，不发送";
                    }else{
                        $this->SendJuheSms($phone,$sendid,$sms);
                    }
                }elseif($sms["smstype"]==3){
                    include_once IA_ROOT . '/addons/mzhk_sun/api/aliyun-dysms/sendSms.php';
                    set_time_limit(0);
                    header('Content-Type: text/plain; charset=utf-8');
                    $sendid = $smstype==1?$sms["aly_orderrefund"]:$sms["aly_order"];
                    if($sendid!=""){
                        $return = sendSms($sms["aly_accesskeyid"], $sms["aly_accesskeysecret"],$phone, $sms["aly_sign"],$sendid);
                        echo json_encode($return);
                    }
                }
            }
        }else{
            echo "短信发送没开";
        }
    }

    
    public function SendYtxSmsbl($sendid='',$sms=array(),$params=''){
        global $_W, $_GPC;
        $postArr = array (
            'account'  => $sms["ytx_apiaccount"],
            'password' => $sms["ytx_apipass"],
            'msg' => $sendid,
            'params' => $params,
            'report' => 'true'
        );
        
        $url = "http://smssh1.253.com/msg/variable/json";
        $result = $this->curlPost($url, $postArr);
        echo $result;
    }

    
    public function SendYtxSms($sendid='',$sms=array(),$mobile=''){
        global $_W, $_GPC;
        $postArr = array (
            'account'  => $sms["ytx_apiaccount"],
            'password' => $sms["ytx_apipass"],
            'msg' => $sendid,
            'phone' => $mobile,
            'report' => 'true'
        );
        
        $url = "http://smssh1.253.com/msg/send/json";
        $result = $this->curlPost($url, $postArr);
        echo $result;
    }

    
    public function SendJuheSms($phone=0,$sendid=0,$sms=array()){
        global $_W, $_GPC;
        header('content-type:text/html;charset=utf-8');
        $sendUrl = 'http://v.juhe.cn/sms/send';
        $smsConf = array(
            'key'   => $sms["appkey"], 
            'mobile'    => $phone, 
            'tpl_id'    => $sendid, 
            'tpl_value' =>'#code#=1234&#company#=聚合数据' 
        );
        $content = $this->juhecurl($sendUrl,$smsConf,1); 
        if($content){
            $result = json_decode($content,true);
            $error_code = $result['error_code'];
            if($error_code == 0){
                
                echo "短信发送成功,短信ID：".$result['result']['sid'];
            }else{
                
                $msg = $result['reason'];
                echo "短信发送失败(".$error_code.")：".$msg;
            }
        }else{
            
            echo "请求发送短信失败";
        }
    }


    function juhecurl($url,$params=false,$ispost=0){
        $httpInfo = array();
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_1 );
        curl_setopt( $ch, CURLOPT_USERAGENT , 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.172 Safari/537.22' );
        curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT , 30 );
        curl_setopt( $ch, CURLOPT_TIMEOUT , 30);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER , true );
        if( $ispost ){
            curl_setopt( $ch , CURLOPT_POST , true );
            curl_setopt( $ch , CURLOPT_POSTFIELDS , $params );
            curl_setopt( $ch , CURLOPT_URL , $url );
        }
        else
        {
            if($params){
                curl_setopt( $ch , CURLOPT_URL , $url.'?'.$params );
            }else{
                curl_setopt( $ch , CURLOPT_URL , $url);
            }
        }
        $response = curl_exec( $ch );
        if ($response === FALSE) {
            
            return false;
        }
        $httpCode = curl_getinfo( $ch , CURLINFO_HTTP_CODE );
        $httpInfo = array_merge( $httpInfo , curl_getinfo( $ch ) );
        curl_close( $ch );
        return $response;
    }

    private function curlPost($url,$postFields){
        $postFields = json_encode($postFields);
        
        $ch = curl_init ();
        curl_setopt( $ch, CURLOPT_URL, $url ); 
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json; charset=utf-8'   
            )
        );
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4); 
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt( $ch, CURLOPT_POST, 1 );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt( $ch, CURLOPT_TIMEOUT,60); 
        curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0);
        $ret = curl_exec ( $ch );
        if (false == $ret) {
            $result = curl_error(  $ch);
        } else {
            $rsp = curl_getinfo( $ch, CURLINFO_HTTP_CODE);
            if (200 != $rsp) {
                $result = "请求状态 ". $rsp . " " . curl_error($ch);
            } else {
                $result = $ret;
            }
        }
        curl_close ( $ch );
        return $result;
    }

    
    public function doPageGetNews(){
        global $_W, $_GPC;
        $uniacid = $_W["uniacid"];

        $pagesize = 5;
        $pageindex = intval($_GPC['page'])*$pagesize;

        $sql = "select * from ".tablename('mzhk_sun_specialtopic')." where uniacid=".$uniacid." and isshow=1 order by istop desc,sort asc,gid desc limit ".$pageindex.",".$pagesize;
        
        $data = pdo_fetchall($sql);
        if($data){
            foreach ($data as $k=>$v){
                $data[$k]['addtime']=date('Y-m-d',$v["addtime"]);
            }
            echo json_encode($data);
        }else{
            echo 2;
        }

    }

    
    public function doPageGetNewsinfo(){
        global $_W, $_GPC;
        $uniacid = $_W["uniacid"];
        $id = intval($_GPC["id"]);
        $openid = $_GPC["openid"];

        $sql = "select * from ".tablename('mzhk_sun_specialtopic')." where uniacid=".$uniacid." and isshow=1 and id=".$id."  ";
        $data = pdo_fetch($sql);
        
        pdo_update('mzhk_sun_specialtopic',array("seenum +="=>1),array('id'=>$id,'uniacid'=>$uniacid));
        if($data){
            $data['addtime']=date('Y-m-d',$data["addtime"]);
            
            $brand = pdo_get('mzhk_sun_brand', array('uniacid' => $uniacid,'bid' =>intval($data["bid"])),array("bname","img","bid"));
            $data['brand'] = $brand;
            
            $goods = pdo_get('mzhk_sun_goods', array('uniacid' => $uniacid,'gid' =>intval($data["gid"])),array("gname","index_img","pic","is_vip","gid","lid"));
            $goods['img'] = $goods["index_img"]?$goods["index_img"]:$goods["pic"];
            $data['goods'] = $goods;
            
            $stlike = pdo_get('mzhk_sun_stlike', array('uniacid' => $uniacid,'stid' =>$id,"openid"=>$openid),array("id"));
            if($stlike){
                $data['stlike'] = 1;
            }else{
                $data['stlike'] = 0;
            }
            echo json_encode($data);
        }else{
            echo 2;
        }
    }

    
    public function doPageaddNewslike(){
        global $_W, $_GPC;
        $uniacid = $_W["uniacid"];
        $id = intval($_GPC["id"]);
        $openid = $_GPC["openid"];
        $data["openid"] = $openid;
        $data["stid"] = $id;
        $data["uniacid"] = $uniacid;
        $data["addtime"] = time();
        $res=pdo_insert('mzhk_sun_stlike',$data);
        if($res){
            
            pdo_update('mzhk_sun_specialtopic',array("likenum +="=>1),array('id'=>$id,'uniacid'=>$uniacid));
            echo 1;
        }else{
            return $this->result(1, '点赞失败，参数错误！', array());
        }
    }

    
    public function doPageCheckGroup(){
        global $_W, $_GPC;

        $uniacid = $_W["uniacid"];
        $now = time();
        $returntitle = "";
        
        $orderdata = pdo_getall('mzhk_sun_ptgroups', array('uniacid' => $uniacid,'status' =>2,'endtime <' =>$now),array("id","order_id"));
        if($orderdata){
            $returntitle = "参团内容返回";
            $id_str = '';
            foreach ($orderdata as $key => $value) {
                $id_str = !empty($id_str)?$id_str.",".$value["id"]:$value["id"];
                pdo_update('mzhk_sun_ptorders',array("peoplenum -="=>1),array('id'=>$value["order_id"],'uniacid'=>$uniacid));
                $returntitle .= "退团id-总团人数".$value["order_id"];
            }
            
            if(!empty($id_str)){
                $result = pdo_query("UPDATE ".tablename('mzhk_sun_ptgroups')." SET status=1 WHERE id in(".$id_str.")");
            }
            $returntitle .= "退团id".$id_str;
            
        }

        
        $sql="select g.id,g.order_id,g.out_trade_no,g.money,g.out_refund_no from " . tablename("mzhk_sun_ptgroups") . " as g left JOIN " . tablename("mzhk_sun_ptorders") . " as o on g.order_id=o.id WHERE g.status=3 and g.uniacid=:uniacid and g.endtime < :now and o.is_ok=0 and o.buynum < o.neednum ";
        $orderdata_pay=pdo_fetchall($sql,array(':uniacid'=>$uniacid,':now'=>$now));

        if($orderdata_pay){
            
            include_once IA_ROOT . '/addons/mzhk_sun/cert/WxPay.Api.php';
            load()->model('account');
            load()->func('communication');
            
            $res=pdo_get('mzhk_sun_system',array('uniacid'=>$uniacid));
            $appid=$res['appid'];
            $wxkey=$res['wxkey'];
            $mchid=$res['mchid']; 
            $path_cert = IA_ROOT . "/addons/mzhk_sun/cert/".$res['apiclient_cert'];
            $path_key = IA_ROOT . "/addons/mzhk_sun/cert/".$res['apiclient_key'];
            if(!$res['apiclient_cert'] || !$res['apiclient_key']){
                echo "apiclient_cert或者apiclient_key没有上传";
                exit;
            }
            foreach ($orderdata_pay as $key => $value) {
                $returntitle .= "退团退款id".$value["id"];
                $out_trade_no=$value['out_trade_no'];
                $fee = $value['money'] * 100;
                $out_refund_no = $value['out_refund_no']?$value['out_trade_no']:$mchid.rand(100,999).time().rand(1000,9999);
                $WxPayApi = new WxPayApi();
                $input = new WxPayRefund();
                $input->SetAppid($appid);
                $input->SetMch_id($mchid);
                $input->SetOp_user_id($mchid);
                $input->SetRefund_fee($fee);
                $input->SetTotal_fee($fee);
                $input->SetOut_refund_no($out_refund_no);
                $input->SetOut_trade_no($out_trade_no);
                $result = $WxPayApi->refund($input, 6, $path_cert, $path_key, $wxkey);
                
                if ($result['result_code'] == 'SUCCESS') {
                    pdo_update('mzhk_sun_ptgroups',array("isrefund ="=>2,"out_refund_no ="=>$out_refund_no),array('id'=>$value["id"],'uniacid'=>$uniacid));
                    pdo_update('mzhk_sun_ptorders',array("is_ok ="=>2),array('id'=>$value["order_id"],'uniacid'=>$uniacid));
                }else{
                    pdo_update('mzhk_sun_ptgroups',array("out_refund_no ="=>$out_refund_no),array('id'=>$value["id"],'uniacid'=>$uniacid));
                }
            }
        }

        echo json_encode($returntitle);
    }

    public function doPageCheckLottery(){
        global $_W, $_GPC;
        $uniacid = $_W["uniacid"];
        $now = time();
        
        $sql="select gid,num,lotterytype,lotterynum,lotterytime from " . tablename("mzhk_sun_goods") . " where uniacid='".$uniacid."' and lid=6 and islottery=0 and winway=0 and lotterytime <= '".$now."' and lotterytype=0 ";
        $goods=pdo_fetchall($sql);
        if($goods){
            foreach($goods as $k => $v){
                $gid = intval($v["gid"]);
                $lotterynum = intval($v["num"]);
                
                $total=pdo_fetchcolumn("select count(oid) as wname from ".tablename('mzhk_sun_hyorder')." where uniacid=".$uniacid." and gid=".$gid." ");
                if($v["num"] >= $total){
                    $ores = pdo_update('mzhk_sun_hyorder',array('islottery'=>1),array('gid'=>$gid,'uniacid'=>$uniacid));
                    $gres = pdo_update('mzhk_sun_goods',array('islottery'=>1),array('gid'=>$gid,'uniacid'=>$uniacid));
                }else{
                    
                    $order=pdo_getall("mzhk_sun_hyorder",array('uniacid'=>$uniacid,'gid'=>$gid,"islottery"=>0),array("oid"));
                    if($order){
                        $order_arr = $order_id_ar = array();
                        foreach($order as $key => $val){
                            $order_arr[] = $val["oid"];
                        }
                        $checkorderid = array_rand($order_arr,$lotterynum);
                        if(is_array($checkorderid)){
                            foreach($checkorderid as $k => $v){
                                $order_id_arr[] = $order_arr[$v];
                            }
                        }else{
                            $order_id_arr = $order_arr[$checkorderid];
                        }
                        if($order_id_arr){
                            $ores = pdo_update('mzhk_sun_hyorder',array('islottery'=>1,"time"=>time()),array('oid'=>$order_id_arr,'uniacid'=>$uniacid));
                            $gres = pdo_update('mzhk_sun_goods',array('islottery'=>1),array('gid'=>$gid,'uniacid'=>$uniacid));
                            if($ores){
                                $res=pdo_update('mzhk_sun_hyorder',array('islottery'=>2,"time"=>time()),array('islottery'=>0,'uniacid'=>$uniacid));
                                
                                
                                $lorreryorder = pdo_getall('mzhk_sun_hyorder',array('uniacid'=>$uniacid,'gid'=>$gid),array("openid","gname","bname","addtime","bid"));
                                if($lorreryorder){
                                    $access_token = $this->getaccess_token();
                                    foreach($lorreryorder as $k => $v){
                                        $this->sendtelmessage($access_token,$v['openid'],$v['gname'],$gid,$v['addtime']);
                                    }
                                }
                            }
                        }
                        echo "yes";
                    }else{
                        echo "none";
                    }
                }
            }
        }else{
            echo "none";
        }
    }

}