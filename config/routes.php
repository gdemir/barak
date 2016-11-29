<?php

ApplicationRoutes::draw(

  // AJAX

  post("/ajax/producttype"),

  // LANGUAGE

  get("/lang/en"),
  get("/lang/tr"),

  // HOME

  get("/", "home#home"),
  get("/home", "home#home"),

  get("/home/index"),
  get("/home/about"),
  get("/home/service_policy"),
  get("/home/our_focus"),
  get("/home/human_resources"),
  get("/home/contact"),

  scope("home",

    [
    get("/categories", "categorypage#index"),
    get("/categories/show/:id", "categorypage#show")
    ],

    [
    get("/products", "productpage#index"),
    get("/products/search", "productpage#search"),
    get("/products/show/:id", "productpage#show"),
    post("/products/find", "productpage#find")
    ],

    [
    get("/producttypes/show/:id", "producttypepage#show"),
    post("/producttypes/find", "producttypepage#find")
    ]

    ),

  // ADMIN

  get("/admin/login"),
  post("/admin/login"),
  get("/admin/logout"),

  get("/admin", "admin#index"),
  get("/admin/index"),

  // get("/category", "category#index", "admin"),
  // resources("/category", "admin"),
  // post("/category/ajax", false, "admin"),

  scope("admin",
    resources("/categories"),
    resources("/producttypes"),
    resources("/products"),
    resources("/users")
    )

  );

?>