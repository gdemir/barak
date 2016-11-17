<?php

class ApplicationConfig {

  const DATABASEFILE = "config/database.ini";
  const ROUTEFILE    = "config/routes.php";
  const LOCALEDIR    = "config/locales/";

  public $time_zone;
  public $encoding;
  public $seed;

  public function __construct() {}

  public function set() {

    if ($this->time_zone)      date_default_timezone_set($this->time_zone);
    // for message of ApplicationException on html page
    if ($this->display_errors) ini_set("display_errors", $this->display_errors);
  }

  public static function database() {

    if (!file_exists(self::DATABASEFILE))
      throw new FileNotFoundException("Yapılandırma ayar dosyası mevcut değil", self::DATABASEFILE);

    return parse_ini_file(self::DATABASEFILE);
  }

  public static function route() {

    if (!file_exists(self::ROUTEFILE))
      throw new FileNotFoundException("Yönlendirme ayar dosyası mevcut değil", self::ROUTEFILE);

    // configuration routes load and route action dispatch
    include self::ROUTEFILE;
  }
}

?>