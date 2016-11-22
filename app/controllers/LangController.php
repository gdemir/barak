<?php
class LangController extends ApplicationController {

  public function en() {
    $_SESSION["i18n"]->locale = "en";
    return $this->redirect_to("/home/index");
  }

  public function tr() {
    $_SESSION["i18n"]->locale = "tr";
    $this->redirect_to("/home/index");
  }

}
?>