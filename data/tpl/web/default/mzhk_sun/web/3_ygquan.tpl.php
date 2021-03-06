<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public/header', TEMPLATE_INCLUDEPATH)) : (include template('public/header', TEMPLATE_INCLUDEPATH));?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public/comhead', TEMPLATE_INCLUDEPATH)) : (include template('public/comhead', TEMPLATE_INCLUDEPATH));?>

<style type="text/css">
  .yg14{margin-top: 30px;}
  .addtabel1{margin-left: 20px;}
  .addtabel2{height: 34px;border: 1px solid #e5e5e5;}
  .quan1{background-color: #F8F8F8;padding: 15px;height: 85px;border-radius: 6px;margin-right: 20px;width: 170px;}
  .quan1:hover{background-color: #E8E8E8;}
  .quan1_img{
  	width: 55px;
  	height: 55px;
  	background-color: #FF6600;
  	border-radius: 6px;
  	font-size: 24px;
  	color: white;
  	line-height: 26px;
  	float: left;
  	overflow: hidden;
  }
  .quan1_img>img{width: 55px;height: 55px;}
  .quan_title{
  	border-left: 3px solid #444444;
  	margin-bottom: 15px;
  	font-weight: bold;
  	font-size: 15px;
  }
  .quan1_text{margin-left: 10px;float: left;}
  .quan1_text>p:nth-child(1){font-size: 14px;font-weight: bold;}
  .quan1_text>p:nth-child(2){font-size: 12px;color: #999;}
  .quan_mar{margin-top: 20px;}
  a:hover{color: #333;}
  .quanyg{padding: 0px;margin-bottom: 20px;width: 250px;}
</style>

  <div class="panel panel-default yg14">

        <div class="panel-heading">营销插件</div>

        <div class="panel-body">

	<div class="col-md-12 quan_title quan_mar">营销类</div>
	<div class="col-md-12">
		<a href="<?php  echo $this->createWebUrl('couponsing')?>">
			<div class="col-md-3 quanyg">
				<div class="quan1">
					<div class="quan1_img">
						<img src="../addons/mzhk_sun/template/images/hb.png">
					</div>
					<div class="quan1_text">
						<p>优惠券</p>
						<p>优惠券</p>
					</div>
				</div>
			</div>
		</a>
        <a href="<?php  echo $this->createWebUrl('ptsing')?>">
			<div class="col-md-3 quanyg">
				<div class="quan1">
					<div class="quan1_img">
						<img src="../addons/mzhk_sun/template/images/pintuan.png">
					</div>
					<div class="quan1_text">
						<p>拼团活动</p>
						<p>拼团活动</p>
					</div>
				</div>
			</div>
		</a>
		<a href="<?php  echo $this->createWebUrl('jksing')?>">
			<div class="col-md-3 quanyg">
				<div class="quan1">
					<div class="quan1_img">
						<img src="../addons/mzhk_sun/template/images/jika.png">
					</div>
					<div class="quan1_text">
						<p>集卡活动</p>
						<p>集卡活动</p>
					</div>
				</div>
			</div>
		</a>
		<a href="<?php  echo $this->createWebUrl('qgsing')?>">
			<div class="col-md-3 quanyg">
				<div class="quan1">
					<div class="quan1_img">
						<img src="../addons/mzhk_sun/template/images/qianggou.png">
					</div>
					<div class="quan1_text">
						<p>抢购活动</p>
						<p>抢购活动</p>
					</div>
				</div>
			</div>
		</a>
		<a href="<?php  echo $this->createWebUrl('kjsing')?>">
			<div class="col-md-3 quanyg">
				<div class="quan1">
					<div class="quan1_img">
						<img src="../addons/mzhk_sun/template/images/city.png">
					</div>
					<div class="quan1_text">
						<p>砍价活动</p>
						<p>砍价活动</p>
					</div>
				</div>
			</div>
		</a>
    <a href="<?php  echo $this->createWebUrl('hyopen')?>">
      <div class="col-md-3 quanyg">
        <div class="quan1">
          <div class="quan1_img">
            <img src="../addons/mzhk_sun/template/images/huiyuan.png">
          </div>
          <div class="quan1_text">
            <p>免单活动</p>
            <p>免单活动</p>
          </div>
        </div>
      </div>
    </a>
	</div>
	<div class="col-md-12">

		<form action="" method="post" class="form-horizontal form" enctype="multipart/form-data">
			<div class="panel-body">

                <div class="form-group">
                    <label for="lastname" class="col-sm-2 control-label">是否开启优惠券：</label>
                    <div class="col-sm-10">
                        <label class="radio-inline">
                            <input type="radio" id="emailwy1" name="is_counp" value="1" <?php  if($item['is_counp']==1 || empty($item['is_counp'])) { ?>checked<?php  } ?> />
                            <label for="emailwy1">开启</label>
                        </label>
                        <label class="radio-inline">
                            <input type="radio" id="emailwy2" name="is_counp" value="2" <?php  if($item['is_counp']==2) { ?>checked<?php  } ?> />
                            <label for="emailwy2">关闭</label>
                        </label>
                        <input type="hidden" name="is_counp_his" value="<?php  echo $item['is_counp'];?>" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="lastname" class="col-sm-2 control-label">是否开启拼团：</label>
                    <div class="col-sm-10">
                        <label class="radio-inline">
                            <input type="radio" id="emailwy3" name="is_ptopen" value="1" <?php  if($item['is_ptopen']==1 || empty($item['is_ptopen'])) { ?>checked<?php  } ?> />
                            <label for="emailwy3">开启</label>
                        </label>
                        <label class="radio-inline">
                            <input type="radio" id="emailwy4" name="is_ptopen" value="2" <?php  if($item['is_ptopen']==2) { ?>checked<?php  } ?> />
                            <label for="emailwy4">关闭</label>
                        </label>
                        <input type="hidden" name="is_ptopen_his" value="<?php  echo $item['is_ptopen'];?>" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="lastname" class="col-sm-2 control-label">是否开启集卡：</label>
                    <div class="col-sm-10">
                        <label class="radio-inline">
                            <input type="radio" id="emailwy5" name="is_jkopen" value="1" <?php  if($item['is_jkopen']==1 || empty($item['is_jkopen'])) { ?>checked<?php  } ?> />
                            <label for="emailwy5">开启</label>
                        </label>
                        <label class="radio-inline">
                            <input type="radio" id="emailwy6" name="is_jkopen" value="2" <?php  if($item['is_jkopen']==2) { ?>checked<?php  } ?> />
                            <label for="emailwy6">关闭</label>
                        </label>
                        <input type="hidden" name="is_jkopen_his" value="<?php  echo $item['is_jkopen'];?>" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="lastname" class="col-sm-2 control-label">是否开启抢购：</label>
                    <div class="col-sm-10">
                        <label class="radio-inline">
                            <input type="radio" id="emailwy7" name="is_qgopen" value="1" <?php  if($item['is_qgopen']==1 || empty($item['is_qgopen'])) { ?>checked<?php  } ?> />
                            <label for="emailwy7">开启</label>
                        </label>
                        <label class="radio-inline">
                            <input type="radio" id="emailwy8" name="is_qgopen" value="2" <?php  if($item['is_qgopen']==2) { ?>checked<?php  } ?> />
                            <label for="emailwy8">关闭</label>
                        </label>
                        <input type="hidden" name="is_qgopen_his" value="<?php  echo $item['is_qgopen'];?>" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="lastname" class="col-sm-2 control-label">是否开启砍价：</label>
                    <div class="col-sm-10">
                        <label class="radio-inline">
                            <input type="radio" id="emailwy9" name="is_kjopen" value="1" <?php  if($item['is_kjopen']==1 || empty($item['is_kjopen'])) { ?>checked<?php  } ?> />
                            <label for="emailwy9">开启</label>
                        </label>
                        <label class="radio-inline">
                            <input type="radio" id="emailwy10" name="is_kjopen" value="2" <?php  if($item['is_kjopen']==2) { ?>checked<?php  } ?> />
                            <label for="emailwy10">关闭</label>
                        </label>
                        <input type="hidden" name="is_kjopen_his" value="<?php  echo $item['is_kjopen'];?>" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="lastname" class="col-sm-2 control-label">是否开启免单：</label>
                    <div class="col-sm-10">
                        <label class="radio-inline">
                            <input type="radio" id="emailwy11" name="is_hyopen" value="1" <?php  if($item['is_hyopen']==1 || empty($item['is_hyopen'])) { ?>checked<?php  } ?> />
                            <label for="emailwy11">开启</label>
                        </label>
                        <label class="radio-inline">
                            <input type="radio" id="emailwy12" name="is_hyopen" value="2" <?php  if($item['is_hyopen']==2) { ?>checked<?php  } ?> />
                            <label for="emailwy12">关闭</label>
                        </label>
                        <input type="hidden" name="is_hyopen_his" value="<?php  echo $item['is_hyopen'];?>" />
                    </div>
                </div>

            </div>
            <div class="form-group">
	            <input type="submit" name="submit" value="提交" class="btn col-lg-3" style="color: white;background-color: #444444;"/>
	            <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
	        </div>
        </form>

	</div>


</div>

</div>
<script type="text/javascript">
    $(function(){
        $("#frame-9").show();
        $("#yframe-9").addClass("wyactive");
    })
</script>