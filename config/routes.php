<?php
$routes = new ApplicationRoutes();
# TODO get/post

$routes->draw(
  ["", "home#index"],
  ["/", "home#index"],
  ["/home/about", "home#about"],
  ["/home/show", "home#show"]
  // ["/admin/home", "admin#home"],
  // ["/admin/login", "admin#login"]
);

?>