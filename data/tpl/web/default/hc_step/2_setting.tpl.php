<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<script src="/addons/hc_pdd/template/js/color.js"></script>
<ul class="nav nav-tabs">
	<li class="active"><a href="<?php  echo $this->createWebUrl('setting')?>">基础设置</a></li>
	<li><a href="<?php  echo $this->createWebUrl('guanzhu')?>">关注设置</a></li>
	<li><a href="<?php  echo $this->createWebUrl('shenhe')?>">审核设置</a></li>
	<li><a href="<?php  echo $this->createWebUrl('ad')?>">流量主</a></li>
	<li><a href="<?php  echo $this->createWebUrl('poster')?>">海报设置</a></li>
	<li><a href="<?php  echo $this->createWebUrl('message')?>">模板消息</a></li>
	<li><a href="<?php  echo $this->createWebUrl('signin')?>">签到设置</a></li>
</ul>
<div class="clearfixcon">
	<form action="" method="post" class="form-horizontal form" enctype="multipart/form-data" id="form1" style="display: block">
		<div class="panel panel-default con" id="tab3">
			<div class="panel-body">
				<div class="tab-pane active" id="tab_param2">
				        <div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">小程序名称</label>
							<div class="col-sm-8">
								<input type="text" name="xcx" class="form-control" value="<?php  echo $info['xcx'];?>">
							</div>
						</div>
						<div class="form-group">
					        <label class="col-xs-12 col-sm-3 col-md-2 control-label">小程序是否审核通过</label>
					        <div class="col-sm-9 col-xs-12">
						    <label class="radio-inline">
							   <input type="radio" name="shenhe" <?php  if($info['shenhe'] == 1) { ?> checked <?php  } ?> value="1">已通过
						    </label>
						    <label class="radio-inline">
							   <input type="radio" name="shenhe" value="0" <?php  if($info['shenhe'] != 1) { ?> checked <?php  } ?>>审核中
						    </label>
					        </div>
				        </div>
				        <div class="form-group">
					        <label class="col-xs-12 col-sm-3 col-md-2 control-label">联系我们是否显示</label>
					        <div class="col-sm-9 col-xs-12">
						    <label class="radio-inline">
							   <input type="radio" name="is_follow" <?php  if($info['is_follow'] == 1) { ?> checked <?php  } ?> value="1">显示
						    </label>
						    <label class="radio-inline">
							   <input type="radio" name="is_follow" value="0" <?php  if($info['is_follow'] != 1) { ?> checked <?php  } ?>>不显示
						    </label>
					        </div>
				        </div>
				        <div class="form-group">
					        <label class="col-xs-12 col-sm-3 col-md-2 control-label">邀请好友模式</label>
					        <div class="col-sm-9 col-xs-12">
						    <label class="radio-inline">
							   <input type="radio" name="invitetype" value="1" <?php  if($info['invitetype'] == 1) { ?> checked <?php  } ?>>邀请好友步数加成模式
						    </label>
						    <label class="radio-inline">
							   <input type="radio" name="invitetype" value="2" <?php  if($info['invitetype'] == 2) { ?> checked <?php  } ?>>邀请好友送币数模式
						    </label>
					        </div>
					        <div class="col-sm-9 col-xs-12">
						    <span class="help-block" style="padding-left: 83px;color:red;">邀请好友步数加成模式：邀请好友送步数与步数加成。邀请好友送币数模式：邀请新用户开礼包，送步数币。如果选择送币模式，请到左侧菜单“邀请送币营销功能”添加步数币礼包</span>
					        </div>
				        </div>
				        
						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">步数币余额长条背景图</label>
							<div class="col-sm-8">
								<?php  echo tpl_form_field_image('longbg',$info['longbg']);?>
								<div style="color: red !important;">建议尺寸：1080*150。此处为小程序标题与上半部背景图之前的小横条。
							    </div>
							</div>
						</div>
				        <div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">引导关注公众号图片</label>
							<div class="col-sm-8">
								<?php  echo tpl_form_field_image('followpic',$info['followpic']);?>
								<div style="color: red !important;">建议尺寸：500*800
							    </div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">首页右下角关注公众号小图</label>
							<div class="col-sm-8">
								<?php  echo tpl_form_field_image('followlogo',$info['followlogo']);?>
								<div style="color: red !important;">建议尺寸：317*140
							    </div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">客服教程图片</label>
							<div class="col-sm-8">
								<?php  echo tpl_form_field_image('kefupic',$info['kefupic']);?>
								<div style="color: red !important;">建议尺寸：500*800
							    </div>
							</div>
						</div>
				        <div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">小程序分享标题</label>
							<div class="col-sm-8">
								<input type="text" name="sharetitle" class="form-control" value="<?php  echo $info['sharetitle'];?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">首页“邀请好友得步数”按钮文字</label>
							<div class="col-sm-4">
								<input type="text" name="sharetext" class="form-control" value="<?php  echo $info['sharetext'];?>">
								<div style="color: red !important;">例：邀请好友得步数</div>
							</div>
							<div class="col-sm-6">
				            <?php  echo tpl_form_field_color('sharetextcolor', $value = $info['sharetextcolor'])?>
				            <span class="help-block"></span>
			                </div>
						</div>
						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">首页“邀请好友得步数”按钮下方说明文字</label>
							<div class="col-sm-4">
								<input type="text" name="shareinfo" class="form-control" value="<?php  echo $info['shareinfo'];?>">
								<div style="color: red !important;">例：每邀请一个好友可获赠2000步</div>
							</div>
							<div class="col-sm-6">
				            <?php  echo tpl_form_field_color('shareinfocolor', $value = $info['shareinfocolor'])?>
				            <span class="help-block"></span>
			                </div>
						</div>
						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">首页“邀请好友永久加成”文字</label>
							<div class="col-sm-8">
								<input type="text" name="upinfo" class="form-control" value="<?php  echo $info['upinfo'];?>">
								<div style="color: red !important;">例：邀请好友永久加成</div>
							</div>
						</div>						
						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">小程序分享图片</label>
							<div class="col-sm-8">
								<?php  echo tpl_form_field_image('sharepic',$info['sharepic']);?>
								<div style="color: red !important;">建议尺寸：600*600
							    </div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">授权登录图片</label>
							<div class="col-sm-8">
								<?php  echo tpl_form_field_image('loginpic',$info['loginpic']);?>
								<div style="color: red !important;">建议尺寸：780*720
							    </div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">开宝箱背景图</label>
							<div class="col-sm-8">
								<?php  echo tpl_form_field_image('boxpic',$info['boxpic']);?>
								<div style="color: red !important;">建议尺寸：750*677
							    </div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">首页上半部背景图</label>
							<div class="col-sm-8">
								<?php  echo tpl_form_field_image('indexbg',$info['indexbg']);?>
								<div style="color: red !important;">建议尺寸：1080*956
							    </div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">首页上半部兑换图</label>
							<div class="col-sm-8">
								<?php  echo tpl_form_field_image('indexbutton',$info['indexbutton']);?>
								<div style="color: red !important;">步数周围的背景圆图。建议尺寸：500*500
							    </div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">邀请好友气泡图</label>
							<div class="col-sm-8">
								<?php  echo tpl_form_field_image('inviteball',$info['inviteball']);?>
								<div style="color: red !important;">邀请好友产生的气泡背景图，建议尺寸：168*168
							    </div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">步数加成气泡图</label>
							<div class="col-sm-8">
								<?php  echo tpl_form_field_image('upball',$info['upball']);?>
								<div style="color: red !important;">步数加成产生的气泡背景图，建议尺寸：168*168
							    </div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">邀请好友头像框</label>
							<div class="col-sm-8">
								<?php  echo tpl_form_field_image('frame',$info['frame']);?>
								<div style="color: red !important;">邀请好友的头像框，建议尺寸：168*168
							    </div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">邀请好友按钮背景图</label>
							<div class="col-sm-8">
								<?php  echo tpl_form_field_image('buttonbg',$info['buttonbg']);?>
								<div style="color: red !important;">邀请好友按钮背景图，建议尺寸：300*80
							    </div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">步数币小图标</label>
							<div class="col-sm-8">
								<?php  echo tpl_form_field_image('coinpic',$info['coinpic']);?>
								<div style="color: red !important;">步数币小图标，建议尺寸：50*50
							    </div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">步数币名称</label>
							<div class="col-sm-8">
								<input type="text" name="coinname" class="form-control" value="<?php  echo $info['coinname'];?>">
								<div style="color: red !important;">例：动力币</div>
							</div>
						</div>						
						<div class="form-group">
							<label class="col-xs-6 col-sm-3 col-md-2 col-lg-2 control-label">1步可兑换多少</label>
							<div class="col-sm-8">
								<div class="input-group">
								<input type="text" name="rate" class="form-control" value="<?php  echo $info['rate'];?>">
								<div class='input-group-addon'>步数币</div>
								</div>
								<div style="color: red !important;">例：0.0006</div>
							</div>
						</div>
                        <div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">每日兑换步数上限</label>
							<div class="col-sm-8">
								<input type="text" name="maxstep" class="form-control" value="<?php  echo $info['maxstep'];?>">
								<div style="color: red !important;">例：30000。即每日用户最多有30000步数可以兑换成步数币。假如比率为0.0006，则代表用户每日最多兑换18个步数币</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-xs-6 col-sm-3 col-md-2 col-lg-2 control-label">邀请一位新用户获得</label>
							<div class="col-sm-8">
								<div class="input-group">
								<input type="text" name="sharestep" class="form-control" value="<?php  echo $info['sharestep'];?>">
								<div class='input-group-addon'>步</div>
								</div>
								<div style="color: red !important;">例：2000</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-xs-6 col-sm-3 col-md-2 col-lg-2 control-label">邀请一位新用户步数加成</label>
							<div class="col-sm-8">
								<div class="input-group">
								<input type="text" name="up" class="form-control" value="<?php  echo $info['up'];?>">
								<div class='input-group-addon'>%</div>
								</div>
								<div style="color: red !important;">例：2</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-xs-6 col-sm-3 col-md-2 col-lg-2 control-label">宝箱价格（步数币）</label>
							<div class="col-sm-8">
								<input type="text" name="boxprice" class="form-control" value="<?php  echo $info['boxprice'];?>">
								<div style="color: red !important;">例：10</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">规则说明图</label>
							<div class="col-sm-8">
								<?php  echo tpl_form_field_image('rulepic',$info['rulepic']);?>
							</div>
						</div>
						<div class="form-group" style="">
							<label class="col-xs-12 col-sm-3 col-md-2 control-label">消息通知</label>
							<div class="col-sm-9 col-xs-12">
								<textarea class="form-control" name="notice" id="advname" class="form-control" value="<?php  echo $info['notice'];?>"><?php  echo $info['notice'];?>
								</textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">0步兑换步数币时提示</label>
							<div class="col-sm-8">
								<input type="text" name="zerotip" class="form-control" value="<?php  echo $info['zerotip'];?>">
								<div style="color: red !important;">例：零步你想换点啥</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">步数币不够兑换商品时商品详情按钮文字</label>
							<div class="col-sm-8">
								<input type="text" name="poortip" class="form-control" value="<?php  echo $info['poortip'];?>">
								<div style="color: red !important;">例：燃力币不足，邀请好友赚取燃力币</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">邀请按钮与兑换按钮之间的灰色小字</label>
							<div class="col-sm-4">
								<input type="text" name="smalltip" class="form-control" value="<?php  echo $info['smalltip'];?>">
								<div style="color: red !important;">例：点击兑换动力币</div>
							</div>
							<div class="col-sm-6">
				            <?php  echo tpl_form_field_color('smalltipcolor', $value = $info['smalltipcolor'])?>
				            <span class="help-block"></span>
			                </div>
						</div>
						<div class="form-group">
			                <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">步数币数字颜色</label>
			                <div class="col-sm-8">
			                    <?php  echo tpl_form_field_color('cointextcolor', $value = $info['cointextcolor'])?>
			                    <span class="help-block"></span>
			                </div>
                        </div>
						<div class="form-group">
			                <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">气泡内数字颜色</label>
			                <div class="col-sm-8">
			                    <?php  echo tpl_form_field_color('balltextcolor', $value = $info['balltextcolor'])?>
			                    <span class="help-block"></span>
			                </div>
                        </div>
                        <div class="form-group">
			                <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">兑换按钮数字颜色</label>
			                <div class="col-sm-8">
			                    <?php  echo tpl_form_field_color('centercolor', $value = $info['centercolor'])?>
			                    <span class="help-block"></span>
			                </div>
                        </div>  
						<div class="form-group">
			                <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">小程序头部颜色</label>
			                <div class="col-sm-8">
			                    <?php  echo tpl_form_field_color('headcolor', $value = $info['headcolor'])?>
			                    <span class="help-block"></span>
			                </div>
                        </div>                      
					</div>
			</div>
		</div>
		<div class="form-group col-sm-12">
			<input name="submit" type="submit" value="提交" class="btn btn-primary col-lg-1" <?php  if($update_status == 1) { ?>disabled<?php  } ?>>
			<input type="hidden" name="act" value="add" />
		</div>
	</form>
</div>



<!-- <script>

	$(document).ready(function(){
		$(".nav-tabs li:first").addClass("active")
		$(".con").hide()
		$(".con:first").show()
		$(".nav-tabs li").click(function(){
			$(".nav-tabs li").removeClass("active")
			$(this).addClass("active")
			$(".con").hide()
			var href=$(this).find("a").attr("href")
			$(href).fadeIn()
			return false
		})
	})
	$('#form1').submit(function() {

	});

	function closeWindow(url) 
	{ 
		var iWidth=300;//弹出窗口的宽度; 
        var iHeight=150;//弹出窗口的高度; 
        var iTop = (window.screen.availHeight - 30 - iHeight) / 2; //获得窗口的垂直位置
        var iLeft = (window.screen.availWidth - 10 - iWidth) / 2; //获得窗口的水平位置
        myWindow = window.open(url,"myWindow",'height=' + iHeight + ',,innerHeight=' + iHeight + ',width=' + iWidth + ',innerWidth=' + iWidth + ',top=' + iTop + ',left=' + iLeft + ',status=no,toolbar=no,menubar=no,location=no,resizable=no,scrollbars=0,titlebar=no'); 
		//myWindow.document.write("运行中，请稍后...");
		//window.opener=null; 
		window.setTimeout(function(){
			myWindow.close();
			location.href="<?php  echo $this->createWebUrl('setting')?>"
		}, 1000); 

	} 
</script> -->

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>