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
  <div class="divHeader"><?php echo $blogtitle; ?></div>
  <div class="SubMenu">
    <a href="main.php" title="首页"><span class="m-left m-now">首页</span></a>
    <?php require_once "about.php"; ?>
  </div>
  <div id="divMain2">
    <p>本插件初衷为不修改主题或其他插件的前提下实现对当前站点的部分自定义需求，比如插入 CSS，js 或者挂载接口；</p>
    <p><strong>usr/ 内的文件升级不会覆盖，但是备份绝对是个好习惯；</strong></p>
    <p>-----</p>

    <h3>定制用户</h3>
    <p>1、放置收到的文件到<code>/zb_users/plugin/diySth/usr/</code>文件夹中；<strong>demo 只是示例，不需要改名</strong>；</p>
    <p>2、回到本页「<a title="刷新" href="main.php">刷新</a>」以更新实际用于调用的 bin/ 文件；</p>
    <p><img src="doc/demo.png" alt="示例"></p>

    <h3>模块化方案：</h3>
    <p>1、按路径创建<strong>空文件夹</strong>：/zb_users/plugin/diySth/usr/<strong>floderName</strong>/，floderName 可自定义；</p>
    <p>2、回到本页「<a title="刷新" href="main.php">刷新</a>」；</p>
    <p>3、文件夹内会生成初始的 js, css, php 三个文件，在其中写入各自功能的实现；<!-- <span style="color: #6a9955;font-weight: 800;">// 订制用户可跳过或自行调整收到的文件<span> --></p>
    <p>4、再次回到本页「<a title="刷新" href="main.php">刷新</a>」，各模块的内容会被合并到下边文件中供系统实际调用；</p>
    <p><strong>5、我也觉得写使用说明好麻烦，所以为什么不直接找我定制？？</strong></p>
    <p>-----</p>
    <p>/zb_users/plugin/diySth/bin/script.js</p>
    <p>/zb_users/plugin/diySth/bin/style.css</p>
    <p>/zb_users/plugin/diySth/bin/function.php</p>
    <p>-----</p>
    <p>注：JS 和 CSS 的引入需要主题使用约定的 header.php 和 footer.php 模板，并且内部分别有 {$header} 和 {$footer} 标签；启用后将对全部调用这两个模板的页面生效；</p>
  </div>
</div>

<?php
require $blogpath . 'zb_system/admin/admin_footer.php';
RunTime();
?>
