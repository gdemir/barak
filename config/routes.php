<?php
Schedule::Application.routes.draw do
Routes::draw(
  "get" => array("/home" => "home#index"),
);

class Routes {
  public static function draw()
  {
    $arguments = func_get_args();
    $arguments_count = func_num_args();
    for ($i = 0; $i < $arguments_count; $i++) {
        echo "$i. değiştirge: " . $arguments[$i] . "<br />\n";
    }
  }
}
?>
