<?php
require_once __DIR__ . '/vendor/autoload.php';
use Steampixel\Route;
$it = new RecursiveDirectoryIterator("pages");
$hasil = '';
// Loop through files
foreach(new RecursiveIteratorIterator($it) as $file) {
    if ($file->getExtension() == 'php') {
        $hasil .= $file . ',';
    }
}
$list = array_filter(explode(',', $hasil));
$url = $list;
$url = array_map(function($x){
	$x = preg_replace('/^pages/', '', $x);
	$x = str_replace('index.php', '', $x);
	$x = preg_replace('/\[[a-z0-9]*\]/', '([a-z0-9]*)', $x);
	$x = str_replace('.php', '', $x);
	return $x;
}, $url);
$parameter = $list;
$parameter = array_map(function($x){
	preg_match_all('/\[[a-z0-9]*\]/', $x, $x);
	$x = $x[0];
	$x = array_map(function($y){
		$y = str_replace('[', '$', $y);
		$y = str_replace(']', '', $y);
		return $y;
	}, $x);
	$x = join(', ', $x);
	return $x;
}, $parameter);

// echo json_encode($list);
/*
[
	"pages\/hai\/[nama].php",
	"pages\/[slug].php",
	"pages\/pages.php",
	"pages\/index.php",
	"pages\/[satu]\/[dua].php"
]
*/
// echo "<hr>";
// echo json_encode($url);
/*
[
	"\/hai\/([a-z0-9]*)",
	"\/([a-z0-9]*)",
	"\/pages",
	"\/",
	"\/([a-z0-9]*)\/([a-z0-9]*)"
]
*/
// echo "<hr>";
// echo json_encode($parameter);
/*
[
	"$nama",
	"$slug",
	"",
	"",
	"$satu, $dua"
]
*/
$teks = '';
foreach ($list as $n => $x) {
	$teks .= '
		Route::add("' . $url[$n] . '", function(' . $parameter[$n] . ') {
			include "' . $list[$n] . '";
		});
	';
}
// echo $teks;
// Route::add("/hai/([a-z0-9]*)", function($nama) { include "pages/hai/[nama].php"; }); Route::add("/([a-z0-9]*)", function($slug) { include "pages/[slug].php"; }); Route::add("/pages", function() { include "pages/pages.php"; }); Route::add("/", function() { include "pages/index.php"; }); Route::add("/([a-z0-9]*)/([a-z0-9]*)", function($satu, $dua) { include "pages/[satu]/[dua].php"; }); 
eval($teks);
// Route::add('/', function(){
// 	include 'pages/index.php';
// });
Route::run('/');