<?php

ApplicationRoutes::draw(

  // get("/", "/default/index"), // for default install route

  //get("/:controller/:action"),
  get("/", "home#index"),
  get("/home/about"),
  get("/home/show"),

  get("/admin", "home#show"),
  get("/admin/home"),
  get("/admin/login"),

  post("/admin/login"),

  resource("/user")

);

?>