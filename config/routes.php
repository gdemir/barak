<?php
// $routes = new ApplicationRoutes2();
// # TODO get/post

ApplicationRoutes::draw(

  ApplicationRoutes::get("/", "home#index"),
  ApplicationRoutes::get("/admin/home"),
  ApplicationRoutes::get("/admin/login"),
  ApplicationRoutes::get("/admin", "home#index"),
  ApplicationRoutes::resource("/user")

  // ["/", "home#index"],
  // ["/home/about", "home#about"],
  // ["/home/show", "home#show"]
  // ["/admin/home", "admin#home"],
  // ["/admin/login", "admin#login"]
);

?>