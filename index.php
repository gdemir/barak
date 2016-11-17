<?php

// lib/*.php files load
// app/controllers/*.php  files load

// system class files and controller class files
$directories = ['lib/', 'app/controllers/'];

foreach ($directories as $directory) {
  foreach(glob($directory . "*.php") as $class) {
    include_once $class;
  }
}

// Configuration : sets /TODO : must on config/*
$config = new ApplicationConfig();
$config->display_errors = true;
$config->time_zone      = 'Europe/Istanbul';
$config->set();

// Database : connect and global share
$db = ApplicationConfig::database();
$GLOBALS['db'] = new ApplicationDatabase($db["host"], $db["name"], $db["user"], $db["pass"]);

// model create auto
foreach (ApplicationSql::tablenames() as $tablename) {
  eval("
    class $tablename extends ApplicationModel {
      protected static \$name = '$tablename';
    }
    ");
}

// Database : seed // OPTIONAL
ApplicationDatabase::seed();

// Helper : get global functions // OPTIONAL
ApplicationHelper::extract();

// I18n : locale get end edit url // OPTIONAL
$GLOBALS['i18n'] = new ApplicationI18n($_SERVER["REQUEST_URI"], "tr");
$_SERVER["REQUEST_URI"] = $GLOBALS['i18n']->uri;
print_r($GLOBALS['i18n']);

// Route : run configration of route
ApplicationConfig::route();
?>