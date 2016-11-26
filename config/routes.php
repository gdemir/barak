<?php

ApplicationRoutes::draw(

  // AJAX

  post("/ajax/producttype"),

  // LANGUAGE

  get("/lang/en"),
  get("/lang/tr"),


  // HOME
  get("/", "home#index"),
  get("/home", "home#index"),

  get("/home/index"),
  get("/home/about"),
  get("/home/service_policy"),
  get("/home/our_focus"),
  get("/home/human_resources"),
  get("/home/contact"),

  scope("home",

    [
    get("/categorypage", "categorypage#index"),
    get("/categorypage/show/:id")
    ],

    [
    get("/productpage", "productpage#index"),
    get("/productpage/search"),
    get("/productpage/show/:id"),
    post("/productpage/find")
    ],

    [
    get("/producttypepage", "producttypepage#index"),
    get("/producttypepage/show/:id"),
    post("/producttypepage/find")
    ]

    ),

  // ADMIN

  get("/admin/login"),
  post("/admin/login"),
  get("/admin/logout"),

  get("/admin", "admin#index"),
  get("/admin/index"),

  // get("/category", "category#index", "admin"),
  //resources("/category", "admin"),

//  post("/category/ajax", false, "admin"),

  scope("admin",
    resources("/category"),
    resources("/producttype"),
    resources("/product"),
    resources("/productfeature"),
    resources("/user")
    )

  );

?>