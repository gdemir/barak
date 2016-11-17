<?php

ApplicationRoutes::draw(
  // get("/", "/default/index"), // for default install route

  //get("/:controller/:action"),

  get("/", "home#index"),
  get("/home/about"),
  get("/home/show"),

  get("/admin", "home#show"),

  get("/admin/login"),
  post("/admin/login"),
  get("/admin/logout"),

  get("/admin/home"),

  resource("/user")

);

?>