<?php
// $routes = new ApplicationRoutes2();
// # TODO get/post

ApplicationRoutes::draw(

  ApplicationRoutes::get("/", "home#index"),
  ApplicationRoutes::get("/admin", "home#index")
  // ["/", "home#index"],
  // ["/home/about", "home#about"],
  // ["/home/show", "home#show"]
  // ["/admin/home", "admin#home"],
  // ["/admin/login", "admin#login"]
);

?>