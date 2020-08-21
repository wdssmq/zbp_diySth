<?php
#注册插件
RegisterPlugin("diySth", "ActivePlugin_diySth");
$diySth_FuncFile = __DIR__ . "/bin/function.php";
if (is_file($diySth_FuncFile)) {
  require $diySth_FuncFile;
}
function ActivePlugin_diySth()
{
  Add_Filter_Plugin('Filter_Plugin_Zbp_BuildTemplate', 'diySth_BuildTemp');
  // Add_Filter_Plugin('Filter_Plugin_Admin_Header', 'InstallPlugin_diySth');
  if (function_exists("diy_Add_Filter_Plugin")) {
    diy_Add_Filter_Plugin();
  }
}
function diySth_BuildTemp(&$templates)
{
  $templates['header'] = str_replace('{$header}', '{$header}  	<link rel="stylesheet" href="' . diySth_Path("b-css", "host") . '?v=2020-08-21" media="screen" />', $templates['header']);
  $templates['footer'] = str_replace('{$footer}', '{$footer}<script src="' . diySth_Path("b-js", "host") . '?v=2020-08-21"></script>', $templates['footer']);
}
// 遍历usr文件夹并生成内容
function diySth_Gen($dir)
{
  global $zbp;
  $php = <<<php
<?php
// ----
// -name-_Filter


// ----
// -name-_Function
php;
  $subDirs = GetDirsInDir($dir);
  $n = 0;
  foreach ($subDirs as  $name) {
    if ($name === "doc" || $name === ".git") {
      continue;
    }
    $path = "{$dir}{$name}/";
    if (is_file("{$path}{$name}.close")) {
      continue;
    }
    foreach (array("php", "js", "css", "md") as $ext) {
      $file = "{$path}{$name}.{$ext}";
      if (!is_file($file)) {
        file_put_contents($file, "/* {$name} */");
        if ($ext === "php") {
          file_put_contents($file, str_replace("-name-", $name, $php));
        }
      }
      if ($ext !== "md")
        diySth_Build($file, $ext, $n);
    }
    $n++;
  }
  // $zbp->SetHint("good", "操作成功[diySth]");
}
function diySth_Build($file, $ext, $i)
{
  global $zbp;
  $lock = diySth_Path("usr/lock.ini");
  if (!is_file($lock)) {
    $main = diySth_Path("main", "host");
    if ($ext === "php")
      $zbp->SetHint('tips', "未设置lock.ini文件" . diySth_a($main, "点击查看说明", "点击查看说明"));
    return;
  }
  if ($ext === "php") {
    $rltFile = diySth_Path("b-fun");
  } else {
    $rltFile = diySth_Path("b-{$ext}");
  }
  if ($i === 0) {
    $contents = $ext === "php" ? file_get_contents(diySth_Path("v-fun")) : "";
  } else {
    $contents = file_get_contents($rltFile);
  }
  if ($ext === "php") {
    $arryPHP = explode("\n// ----\n", file_get_contents($file));
    $contents = str_replace("// diy_Add_Filter", "// diy_Add_Filter\n" . $arryPHP[1], $contents);
    $contents = str_replace("// diy_Add_Function", "// diy_Add_Function\n" . $arryPHP[2], $contents);
  } else {
    $contents .= file_get_contents($file);
    $contents .= "\n";
  }
  file_put_contents($rltFile, $contents);
}
function diySth_a($href, $title, $text = "")
{
  if (empty($text)) {
    $text = $href;
  }
  return "<a href=\"{$href}\" title=\"{$title}\">$text</a>";
}
function diySth_Path($file, $t = 'path')
{
  global $zbp;
  $result = $zbp->$t . 'zb_users/plugin/diySth/';
  switch ($file) {
    case 'b-js':
      return $result . 'bin/script.js';
      break;
    case 'b-css':
      return $result . 'bin/style.css';
      break;
    case 'b-fun':
      return $result . 'bin/function.php';
      break;
    case 'v-fun':
      return $result . 'var/function.php';
      break;
    case 'bin':
      return $result . 'bin/';
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
  global $zbp;
  $dir = diySth_Path("bin");
  if (!is_dir($dir)) {
    @mkdir($dir, 0755);
    copy(diySth_Path("v-fun"), diySth_Path("b-fun"));
  }
  $dir = diySth_Path("usr");
  if (!is_dir($dir)) {
    @mkdir($dir, 0755);
  }
  if (!$zbp->HasConfig('diySth')) {
    $zbp->Config('diySth')->version = 1.1;
    // $zbp->Config('diySth')->value= "";
    $zbp->SaveConfig('diySth');
  }
  $filesList = array("css", "js");
  foreach ($filesList as $value) {
    $uFile = diySth_Path("b-{$value}");
    if (!is_file($uFile)) {
      file_put_contents($uFile, "");
    }
  }
  diySth_Gen($dir);
  $zbp->BuildTemplate();
}
function UninstallPlugin_diySth()
{
}
