<?php
// # TODO get/post

ApplicationRoutes::draw(

  get("/", "home#index"),
  get("/home/about"),
  get("/home/show"),

  get("/admin", "home#show"),
  get("/admin/home"),
  get("/admin/login"),

  resource("/user")

  // ["/", "home#index"],
  // ["/home/about", "home#about"],
  // ["/home/show", "home#show"]
  // ["/admin/home", "admin#home"],
  // ["/admin/login", "admin#login"]
);

?>