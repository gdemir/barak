<?php
Schedule::Application.routes.draw do
Routes::draw(array(
  "get" => array("/home" => "home#index"),
));
?>
