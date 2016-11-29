<?php

//User::create(["first_name" => "first_name", "department_id" => "1"]);


if (User::load()->count() == 0) {

  User::create(["first_name" => "Ramazan", "last_name" => "İhsanoğlu", "username" => "rihsanoglu", "password" => "97233e71ad1a600ef532d02edbbf805b", "boss" => true, "admin" => false]);
  User::create(["first_name" => "Sedat",  "last_name" => "Göksel", "username" => "sgoksel", "password" => "97233e71ad1a600ef532d02edbbf805b", "boss" => false, "admin" => true]);

}

//User::new(["first_name" => 2, "phone" => "123", "last_name" => "Demir", "username" => "tdemir", "password" => "123456", "department_id" => 3])->save();

// echo User::exists(1) ? "kayit var" : "kayit yok";

// User::load()->delete_all();
// Department::load()->delete_all();

?>