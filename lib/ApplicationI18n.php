<?php

class ApplicationI18n {

  const LOCALESDIR = "config/locales/";

  private $_words;

  public $locale;
  public $uri;

  public function __construct($_request_uri, $default_locale) {

    if (!file_exists(self::LOCALESDIR))
      throw new FileNotFoundException("Yerel ayar dizini mevcut değil", self::LOCALESDIR);

    foreach(glob(self::LOCALESDIR . "*.php") as $localename)
      $locales[] = basename($localename, ".php");

    $_request_uri_list = explode("/", $_request_uri);
    $locale = $_request_uri_list[1];

    if (in_array($locale, $locales)) {
      $this->locale = $locale;
      $this->uri = "/". implode("/", array_slice($_request_uri_list, 2));
    } else {
      $this->locale = $default_locale;
      $this->uri = $_request_uri;
    }

    $localefile = self::LOCALESDIR . $this->locale . ".php";

    if (!file_exists($localefile))
      throw new FileNotFoundException("Yerel ayar dosyası mevcut değil", $localefile);

    $this->_words = include $localefile;
    return $this;
  }

  public function __get($word) {
    if (!isset($this->_words[$word]))
      throw new I18nNotFoundException("Yerel ayar dosyasında böyle bir kelime mevcut değil", $word);

    return $this->_words[$word];
  }

}
?>