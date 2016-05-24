<?php
class ApplicationController {
  public static before_filter = NULL;
  public function __call($method, $arguments) {
      if (method_exists($this, $method)) {
          call_user_func($before_filter);
          return call_user_func_array(array($this, $method), $arguments);
      }
  }
}
?>
