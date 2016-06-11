<?php
$routes = new ApplicationRoutes();
# TODO get/post

$routes->draw(
  ["", "home#index"],
  ["/", "home#index"]
  );

?>