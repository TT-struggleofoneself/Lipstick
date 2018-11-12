<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<style>
	.dag,.dagtwo,.dagthree,.dagfor{
		display:none;
	}
</style>
<ul class="nav nav-tabs">

	<li><a href="<?php  echo $this->createWebUrl('kefu')?>">客服消息列表</a></li>

	<li class="active"><a href="<?php  echo $this->createWebUrl('kefu_post')?>">添加客服消息</a></li>

</ul>



<div class="clearfix">

	<form action="" method="post" class="form-horizontal form" enctype="multipart/form-data" onsubmit="return formcheck()">
		<input type="hidden" name="id" value="<?php  echo $info['id'];?>">
		<div class="panel panel-default">
			<div class="panel-heading">
				设置
			</div>
			<div class="panel-body">
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">客服消息推送设置</label>
                <div class="col-sm-9" style="color: red">
                    【务必完成这2点配置，再去填写下方内容】<br>
                    1.进入小程序公众平台后台->设置->开发设置 找到消息推送<br>
                    2.设置参数在-这个后台的 系统管理->微信小程序->本模块的管理设置->底部有一个消息推送配置。分别复制下来，粘贴到小程序公众平台消息推送位置。<br>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">回复关键词</label>
                <div class="col-sm-9">
                    <input class='form-control' name='kefu_keyword' value='<?php  echo $info["kefu_keyword"];?>'>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">关注图文标题</label>
                <div class="col-sm-9">
                    <input class='form-control' name='kefu_title' value='<?php  echo $info["kefu_title"];?>'>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">关注图文图片</label>
                <div class="col-sm-9">
                    <?php  echo tpl_form_field_image('kefu_img',$info['kefu_img'])?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">关注图文概述</label>
                <div class="col-sm-9">
                    <input class='form-control' name='kefu_gaishu' value='<?php  echo $info["kefu_gaishu"];?>'>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">关注图文链接</label>
                <div class="col-sm-9">
                    <input class='form-control' name='kefu_url' value='<?php  echo $info["kefu_url"];?>'>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">备注</label>
                <div class="col-sm-9">
                    <input class='form-control' name='beizhu' value='<?php  echo $info["beizhu"];?>'>
                </div>
            </div>
        </div>

		</div>

		<div class="form-group col-sm-12">

			<input type="submit" name="submit" value="提交" class="btn btn-primary col-lg-1">

			<?php  if(empty($info['id'])) { ?>

				<input type="hidden" name="act" value="add">

			<?php  } else { ?>

				<input type="hidden" name="act" value="edit">

			<?php  } ?>

		</div>

	</form>

</div>

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>
