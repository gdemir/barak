<?php

ApplicationRoutes::draw(
  //get("/", "/default/index"), // for default install route

  //get("/:controller/:action"),

  get("/admin", "admin#home"),

  get("/lang/en"),
  get("/lang/tr"),

  get("/", "home#index"),
  get("/home/index"),
  get("/home/about"),
  get("/home/show"),

  get("/admin/login"),
  post("/admin/login"),
  get("/admin/logout"),

  get("/admin/home"),

  resource("/user")

);

?>