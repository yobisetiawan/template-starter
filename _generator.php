<?php
function RemoveFolder($folder)
{
  if (file_exists($folder)) {
    $dir = realpath($folder);
    shell_exec("Rmdir /S /Q $dir");
  }
}

function CopyFolder($dir1, $dir2)
{
  shell_exec("Xcopy /E /I $dir1 $dir2");
}

$dist_folder = './dist';
$temp_pages_folder = './temp_pages';

RemoveFolder($dist_folder);
RemoveFolder($temp_pages_folder);

mkdir('dist/assets', 0777, true);
mkdir('temp_pages', 0777, true);

$assets = realpath('./assets');
$dist = realpath($dist_folder);
$dist_assets = realpath('./dist/assets');
$temp = realpath('./temp_pages');
$temp_folder = str_replace("\\", "/", $temp);
$base_url = 'http://localhost/template/template-starter/';


$pages = ['index.php', 'about.php'];

foreach ($pages as $page) {
  $html = file_get_contents($base_url . $page);
  $p = explode('.php', $page);
  $handle = fopen($temp_folder . '/' . $p[0] . '.html', 'w+');
  fwrite($handle, $html);
  fclose($handle);
}

shell_exec("cd $temp_folder && prettier --write .");
CopyFolder($assets, $dist_assets);
CopyFolder($temp, $dist);
RemoveFolder($temp_pages_folder);