<?php
 #注册插件
RegisterPlugin("diySth", "ActivePlugin_diySth");
$diySth_FuncFile = __DIR__ . "/usr/function.php";
if (is_file($diySth_FuncFile)) {
  require_once $diySth_FuncFile;
}
function ActivePlugin_diySth()
{
  Add_Filter_Plugin('Filter_Plugin_Zbp_BuildTemplate', 'diySth_BuildTemp');
  Add_Filter_Plugin('Filter_Plugin_Admin_Header', 'InstallPlugin_diySth');
  if (function_exists("diy_Add_Filter_Plugin")){
    diy_Add_Filter_Plugin();
  }
}
function diySth_BuildTemp(&$templates)
{
  $templates['header'] = str_replace('{$header}', '{$header}  	<link rel="stylesheet" href="' . diySth_Path("css", "host") . '" media="screen" />', $templates['header']);
  $templates['footer'] = str_replace('{$footer}', '{$footer}<script src="' . diySth_Path("js", "host") . '"></script>', $templates['footer']);
}
function diySth_Path($file, $t = 'path')
{
  global $zbp;
  $result = $zbp->$t . 'zb_users/plugin/diySth/';
  switch ($file) {
    case 'js':
      return $result . 'usr/script.js';
      break;
    case 'css':
      return $result . 'usr/style.css';
      break;
    case 'fun':
      return $result . 'usr/function.php';
      break;
    case 'usr':
      return $result . 'usr/';
      break;
    case 'var':
      return $result . 'var/';
      break;
    case 'main':
      return $result . 'main.php';
      break;
    default:
      return $result . $file;
  }
}
function InstallPlugin_diySth()
{
  $dir = diySth_Path("usr");
  if (!is_dir($dir)) {
    @mkdir($dir, 0755);
    copy(diySth_Path("var/function.php"), diySth_Path("fun"));
  }
  // file_put_contents(diySth_Path("fun"), fi);
  $filesList = array("css", "fun", "js");
  foreach ($filesList as $key => $value) {
    $uFile = diySth_Path($value);
    if (!is_file($uFile)) {
      file_put_contents($uFile, "");
    }
  }
}
function UninstallPlugin_diySth()
{ }
