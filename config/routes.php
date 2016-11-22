<?php

ApplicationRoutes::draw(

  // LANGUAGE

  get("/lang/en"),
  get("/lang/tr"),


  // HOME
//  get("/", "home#index"),
  get("/home/index"),
  get("/home/about"),
  get("/home/service_policy"),
  get("/home/our_focus"),
  get("/home/human_resources"),
  get("/home/contact"),

  scope("home",
    resources("/categorypage")
    ),

  // ADMIN

  get("/admin/login"),
  post("/admin/login"),
  get("/admin/logout"),

  get("/admin/index"),

  //resources("/category", "admin"),
  scope("admin",
    resources("/category"),
    resources("/producttype"),
    resources("/product"),
    resources("/description")

    )

  );

?>