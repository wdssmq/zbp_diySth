<?php
require '../../../zb_system/function/c_system_base.php';
require '../../../zb_system/function/c_system_admin.php';
$zbp->Load();
$action = 'root';
if (!$zbp->CheckRights($action)) {
  $zbp->ShowError(6);
  die();
}
if (!$zbp->CheckPlugin('diySth')) {
  $zbp->ShowError(48);
  die();
}
InstallPlugin_diySth();

$blogtitle = 'diySth';
require $blogpath . 'zb_system/admin/admin_header.php';
require $blogpath . 'zb_system/admin/admin_top.php';
?>
<div id="divMain">
  <div class="divHeader"><?php echo $blogtitle; ?><small><a title="刷新" href="main.php" style="font-size: 16px;display: inline-block;margin-left: 5px;">刷新</a></small></div>
  <div class="SubMenu">
    <a href="main.php" title="首页"><span class="m-left m-now">首页</span></a>
    <?php require_once "about.php"; ?>
  </div>
  <div id="divMain2">
    <p>本插件初衷为不修改主题或其他插件的前提下实现对当前站点的部分自定义需求，比如插入CSS，js或者挂载接口；</p>
    <p><strong>usr/内的文件升级不会覆盖，但是备份绝对是个好习惯；</strong></p>
    <p>-----</p>
    <h3>模块化方案：</h3>
    <p>1、按路径创建<strong>空文件夹</strong>：/zb_users/plugin/diySth/usr/<strong>floderName</strong>/，floderName可自定义；</p>
    <p>2、按路径创建<strong>空文件</strong>：/zb_users/plugin/diySth/usr/lock.ini；</p>
    <p>3、回到本页<a title="刷新" href="main.php">刷新</a>；</p>
    <p>4、文件夹内会生成初始的js,css,php三个文件，在其中写入各自功能的实现；</p>
    <p>5、再次回到本页<a title="刷新" href="main.php">刷新</a>，各模块的内容会被合并到下边文件中供系统实际调用；</p>
    <p><strong>6、我也觉得写使用说明好麻烦，所以为什么不直接找我定制？？</strong></p>
    <p>-----</p>
    <p>/zb_users/plugin/diySth/usr/script.js</p>
    <p>/zb_users/plugin/diySth/usr/style.css</p>
    <p>/zb_users/plugin/diySth/usr/function.php（试验）</p>
    <p>-----</p>
    <p>注：JS和CSS的引入需要主题使用约定的header.php和footer.php模板，并且内部分别有{$header}和{$footer}标签；启用后将对全部调用这两个模板的页面生效；</p>
  </div>
</div>

<?php
require $blogpath . 'zb_system/admin/admin_footer.php';
RunTime();
?>
