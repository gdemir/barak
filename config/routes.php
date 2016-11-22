<?php

ApplicationRoutes::draw(

  get("/admin", "admin#home"),

  get("/lang/en"),
  get("/lang/tr"),

  get("/", "home#index"),
  get("/home/index"),
  get("/home/about"),
  get("/home/service_policy"),
  get("/home/our_focus"),
  get("/home/human_resources"),
  get("/home/contact"),


  get("/admin/login"),
  post("/admin/login"),
  get("/admin/logout"),

  get("/admin/index"),

  //resources("/category", "admin"),
  scope("admin",
    resources("/category")

    )

  );

?>