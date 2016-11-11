<?php

class ApplicationView {

  private $_content;

  private $_layout;
  private $_template;

  private $_view;
  private $_action;

  // "/home/index"
  // "/home/show"
  // "/admin/show"
  // ["layout"=>"home", "view" => "home", "action" => "index"]
  // ["layout" => false]
  // ["view" => "admin", "action" => "index"]
  // ["view" => "admin", "action" => "index"]
  // ["layout" => "admin", "view" => "home", "action" => "show"]

  public function __construct() {}

  public function render($_render) {

    if (is_array(($_render))) {

      if (isset($_render["template"]) and (isset($_render["view"]) or isset($_render["action"])))
        throw new ViewNotFoundException("Render fonksiyonun Template parametresi ile birlikte view, action parametreleri kullanılamaz", $this->_template);

      foreach ($_render as $key => $value) {
        switch ($key) {
          case "layout":   $this->_layout   = $value; break;
          case "view":     $this->_view     = $value; break; // default kesin
          case "action":   $this->_action   = $value; break; // default kesin
          case "template": $this->_template = $value; break;
          default:
          throw new ViewNotFoundException("Render fonksiyonunda bilinmeyen parametre", $key);
        }
      }

      $this->_template = $this->_view . "/" . $this->_action; // default kesin

    } elseif (is_string($_render)) {

      $url = explode("/", trim($_render, "/"));

      $this->_template = (isset($url[1])) ? $_render : $this->_view . "/" . $url[0];

      if (!file_exists("app/views/" . $this->_template . ".php"))
        throw new FileNotFoundException("Template mevcut değil", $this->_template);

    } else {
      throw new ViewNotFoundException("Render fonksiyonun bilinmeyen değişken tipi", $this->_template);
    }
  }

  public function run($params) {

    // Where is the LAYOUT ?
    if (isset($this->_layout)) { // is set ?

      if ($this->_layout) { // is not false?

        $this->_layout .= "_layout";
        $this->_content = self::page_content();

      } else { // is false ?

        $this->_content = self::template_content();

      }

    } else { // not set ?

      $this->_layout = (file_exists("app/views/layouts/" . $this->_view . "_layout.php")) ? $this->_view . "_layout" : "default_layout";      
      $this->_content = self::page_content();
    }

    self::display($params);
  }

  // merge LAYOUT and TEMPLATE content
  private function page_content() {
    return str_replace("{yield}", self::template_content(), self::layout_content());
  }

  private function layout_content() {
    $layout_path = "app/views/layouts/" . $this->_layout . ".php";

    if (!file_exists($layout_path))
      throw new FileNotFoundException("Layout dosyası mevcut değil", $layout_path);
    return file_get_contents($layout_path);
  }

  private function template_content() {
    $template_path = "app/views/" . $this->_template . ".php";

    if (!file_exists($template_path))
      throw new FileNotFoundException("Template dosyası mevcut değil", $template_path);
    return file_get_contents($template_path);
  }

  private function display($params) {

    // controller'in paramslarını yükle
    foreach ($params as $param => $value) {
      $$param = $value;
    }

    // http://stackoverflow.com/questions/1184628/php-equivalent-of-include-using-eval)
    eval("?> " . $this->_content . "<?php");

    unset($this->_content);
  }
}
?>
