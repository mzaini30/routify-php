<?php
// Autoload files using composer
require_once __DIR__ . '/vendor/autoload.php';

// Use this namespace
use Steampixel\Route;

// Add your first route
Route::add('/', function() {
  echo 'Welcome :-)';
});

// Add the first route
Route::add('/user/([0-9]*)/edit', function($id) {
  echo 'Edit user with id '.$id.'<br>';
}, 'get');

// Run the router
Route::run('/');

echo("<br><br>");

// Construct the iterator
$it = new RecursiveDirectoryIterator("pages");

// Loop through files
foreach(new RecursiveIteratorIterator($it) as $file) {
    if ($file->getExtension() == 'php') {
        echo $file . "<br>";
    }
}