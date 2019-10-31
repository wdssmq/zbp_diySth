<?php
#注册插件
RegisterPlugin("diySth", "ActivePlugin_diySth");
$diySth_FuncFile = __DIR__ . "/usr/function.php";
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
  $templates['header'] = str_replace('{$header}', '{$header}  	<link rel="stylesheet" href="' . diySth_Path("u-css", "host") . '" media="screen" />', $templates['header']);
  $templates['footer'] = str_replace('{$footer}', '{$footer}<script src="' . diySth_Path("u-js", "host") . '"></script>', $templates['footer']);
}
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
    foreach (array("php", "js", "css") as $ext) {
      $file = "{$path}{$name}.{$ext}";
      if (!is_file($file)) {
        file_put_contents($file, "/* {$name} */");
        if ($ext === "php") {
          file_put_contents($file, str_replace("-name-", $name, $php));
        }
      }
      diySth_Build($file, $ext, $n);
    }
    $n++;
  }
}
function diySth_Build($file, $ext, $i)
{
  $lock = diySth_Path("usr/lock.ini");
  if (!is_file($lock)) {
    return;
  }
  if ($ext === "php") {
    $rltFile = diySth_Path("u-fun");
  } else {
    $rltFile = diySth_Path("u-{$ext}");
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
function diySth_Path($file, $t = 'path')
{
  global $zbp;
  $result = $zbp->$t . 'zb_users/plugin/diySth/';
  switch ($file) {
    case 'u-js':
      return $result . 'usr/script.js';
      break;
    case 'u-css':
      return $result . 'usr/style.css';
      break;
    case 'u-fun':
      return $result . 'usr/function.php';
      break;
    case 'v-fun':
      return $result . 'var/function.php';
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
  $dir = diySth_Path("usr");
  if (!is_dir($dir)) {
    @mkdir($dir, 0755);
    copy(diySth_Path("v-fun"), diySth_Path("u-fun"));
  }
  if (!$zbp->HasConfig('diySth')) {
    $zbp->Config('diySth')->version = 1.1;
    // $zbp->Config('diySth')->value= "";
    $zbp->SaveConfig('diySth');
  }
  $filesList = array("css", "js");
  foreach ($filesList as $value) {
    $uFile = diySth_Path("u-{$value}");
    if (!is_file($uFile)) {
      file_put_contents($uFile, "");
    }
  }
  diySth_Gen($dir);
  $zbp->BuildTemplate();
}
function UninstallPlugin_diySth()
{ }
