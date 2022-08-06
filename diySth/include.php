<?php
// 注册插件
RegisterPlugin("diySth", "ActivePlugin_diySth");

// 判断文件是否存在并引入
$diySth_FuncFile = __DIR__ . "/bin/function.php";
if (is_file($diySth_FuncFile)) {
  require $diySth_FuncFile;
}

// 接口挂载
function ActivePlugin_diySth()
{
  Add_Filter_Plugin('Filter_Plugin_Zbp_BuildTemplate', 'diySth_BuildTemp');
  if (function_exists("diySth_ActiveUsrMod")) {
    diySth_ActiveUsrMod();
  }
}

// 模板编译调用
function diySth_BuildTemp(&$templates)
{
  $templates['header'] = str_replace('{$header}', '{$header}  	<link rel="stylesheet" href="' . diySth_Path("b-css", "host") . '?v=2021100610" media="screen" />', $templates['header']);
  $templates['footer'] = str_replace('{$footer}', '{$footer}<script src="' . diySth_Path("b-js", "host") . '?v=2021100610"></script>', $templates['footer']);
}

// 遍历 usr 文件夹并生成内容
function diySth_Gen()
{
  global $zbp;
  $php = <<<php
<?php
// ----
// -name-_Filter


// ----
// -name-_Function
php;
  $dir = diySth_Path("usr");
  $subDirs = GetDirsInDir($dir);
  // 用于判断循环子级调用 diySth_Build() 是否首次执行
  $n = 0;
  foreach ($subDirs as  $name) {
    if ($name === "doc" || $name === ".git") {
      continue;
    }
    // diySth/usr/autoLoad/
    $path = "{$dir}{$name}/";
    // diySth/usr/autoLoad/autoLoad.close
    if (is_file("{$path}{$name}.close")) {
      continue;
    }
    foreach (array("php", "js", "css", "md") as $ext) {
      $file = "{$path}{$name}.{$ext}";
      if (!is_file($file)) {
        if ($ext === "php") {
          file_put_contents($file, str_replace("-name-", $name, $php));
        } elseif ($ext === "md") {
          file_put_contents($file, "## {$name}");
        } else {
          file_put_contents($file, "/* {$name} */");
        }
      }
      if ($ext !== "md")
        diySth_Build($file, $ext, $n);
    }
    // 计数
    $n++;
  }
  // $zbp->SetHint("good", "操作成功[diySth]");
}

function diySth_Build($file, $ext, $i)
{
  // 2021-10-06
  global $zbp;
  $lock = diySth_Path("usr/lock.ini");
  if (!is_file($lock)) {
    $zbp->SetHint("tips hint_always", "未放置 usr/lock.ini 文件，请放置后重试或联系作者");
    return;
  }
  // 2021-10-06 ↑
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
    $arrPHP = explode("\n// ----\n", file_get_contents($file));
    $contents = str_replace("// diySth_Add_Filter", "// diySth_Add_Filter\n" . $arrPHP[1], $contents);
    $contents = str_replace("// diySth_Add_Function", "// diySth_Add_Function\n" . $arrPHP[2], $contents);
    // 加入注释以区分 bin 文件
    $contents = preg_replace("/([;}])\n{2,}/", "$1\n\n// {$i} ${file}\n", $contents);
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
  // 2021-10-06 ↓
  if (!$zbp->HasConfig('diySth')) {
    file_put_contents(diySth_Path("usr/lock.ini"), "");
  }
  // 2021-10-06 ↑
  if (!$zbp->HasConfig('diySth')) {
    $zbp->Config('diySth')->version = 1.1;
    $zbp->SaveConfig('diySth');
  }
  $filesList = array("css", "js");
  foreach ($filesList as $value) {
    $uFile = diySth_Path("b-{$value}");
    if (!is_file($uFile)) {
      file_put_contents($uFile, "");
    }
  }
  diySth_Gen();
  $zbp->BuildTemplate();
}

function UninstallPlugin_diySth()
{
}
