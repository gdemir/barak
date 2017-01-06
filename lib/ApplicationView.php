<?php

class ApplicationView {

  const LAYOUTPATH = "app/views/layouts/";
  const VIEWPATH   = "app/views/";

  private $_content;

  private $_layout;
  private $_template;

  private $_view;
  private $_action;

  private $_text;
  private $_file;

  // "/home/index"
  // "/home/show"
  // "/admin/show"
  // ["layout"=>"home", "view" => "home", "action" => "index"]
  // ["layout" => false]
  // ["view" => "admin", "action" => "index"]
  // ["view" => "admin", "action" => "index"]
  // ["layout" => "admin", "view" => "home", "action" => "show"]

  public function __construct() {}

  public function set($_render) {

    if (is_array(($_render))) {

      foreach ($_render as $key => $value) {
        switch ($key) {
          case "text":     $this->_text     = $value; break;
          case "layout":   $this->_layout   = $value; break;
          case "view":     $this->_view     = $value; break; // default kesin
          case "action":   $this->_action   = $value; break; // default kesin
          case "template": $this->_template = $value; break;
          default:
          throw new ViewNotFoundException("Render fonksiyonunda bilinmeyen parametre", $key);
        }
      }

    } elseif (is_string($_render)) {

      $url = explode("/", trim($_render, "/"));
      $this->_template = (isset($url[1])) ? $_render : $this->_view . "/" . $url[0];

    } else {
      throw new ViewNotFoundException("Render fonksiyonun bilinmeyen değişken tipi", $this->_render);
    }
  }

  public function run($params = null) {

    if (isset($this->_text)) {

      return self::text_display();
    }

    // if (isset($this->_file)) {
    //   $this->content = self::template_content($this->_file);
    // }

    if (!isset($this->_template))
      $this->_template = $this->_view . "/" . $this->_action;

    // Where is the LAYOUT ?
    if (isset($this->_layout)) { // is set ?

      if ($this->_layout) { // is not false?

        $this->_layout .= "_layout";
        $this->_content = self::page_content();

      } else { // is false ?

        $this->_content = self::template_content();

      }

    } else { // not set ?

      $this->_layout = $this->_view . "_layout";
      $this->_content = self::page_content();
    }

    self::content_display($params);
  }

  // merge LAYOUT and TEMPLATE content
  private function page_content() {
    return str_replace("{yield}", self::template_content(), self::layout_content());
  }

  private function layout_content() {
    $layout_path = self::LAYOUTPATH . $this->_layout . ".php";

    if (!file_exists($layout_path))
      throw new FileNotFoundException("Layout dosyası mevcut değil", $layout_path);

    return file_get_contents($layout_path);
  }

  private function template_content($path = self::VIEWPATH) {

    $template_path = $path . trim($this->_template, "/") . ".php";

    if (!file_exists($template_path))
      throw new FileNotFoundException("Template dosyası mevcut değil", $template_path);
    return file_get_contents($template_path);
  }

  private function content_display($params = null) {

    if (!is_null($params)) {
      // controller'in paramslarını yükle
      foreach ($params as $param => $value) {
        $$param = $value;
      }
    }

    // http://stackoverflow.com/questions/1184628/php-equivalent-of-include-using-eval)
    $file_name = 'tmp/' . time() . '.php';
    if (!($fp = fopen($file_name, 'a')))
      throw new FileNotFoundException("File does not exist", $file_name);

    fwrite($fp, $this->_content);
    fclose($fp);

    unset($this->_content);
    unset($fp);

    include($file_name);

    unlink($file_name);
  }

  private function text_display() {
    echo $this->_text;
  }

}
?>