<?php

// lib/*.php files load
// app/controllers/*.php  files load

$directories = [
            'lib/',
            'app/controllers/'
];
foreach ($directories as $directory) {
    foreach(glob($directory . "*.php") as $class) {
        include_once $class;
    }
}

define("TEMP", "temp"); // render pages of folder
define("CONFIGFILE", "config/database.ini"); // configuration file

ini_set("display_errors", 1); // for message of ApplicationException on html page
date_default_timezone_set('Europe/Istanbul');

// /tmp : create render pages of folder if it doesn't exist

if (!file_exists(TEMP))
  mkdir(TEMP, 0777, true);

// /config/database.ini : configuration file load

if (!file_exists(CONFIGFILE))
  throw new FileNotFoundException("Yapılandırma dosyası mevcut değil", CONFIGFILE);

$database = parse_ini_file(CONFIGFILE);

// Database connection and model create of tables

$GLOBALS['db'] = new BARAK("mysql:host={$database['host']};dbname={$database['name']}", $database["user"], $database["pass"]);

foreach ($GLOBALS['db']->table_names() as $table_name) {
  eval("
    class $table_name extends ApplicationModel {
      protected static \$name = '$table_name';
      public function __construct(\$primary_key = false) {
        parent::__construct(\$primary_key);
      }
    }
    ");
}

// configuration routes load and route action dispatch
include 'config/routes.php';
?>
