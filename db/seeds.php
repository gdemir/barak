<?php

if (Department::load()->count() == 0) {
	Department::create(["name" => "Bilgisayar Mühendisliği"]);
	Department::create(["name" => "Elektirk-Elektronik Mühendisliği"]);
	Department::create(["name" => "Gıda Mühendisliği"]);
}

if (User::load()->count() == 0) {

	User::create(["first_name" => "Gökhan", "last_name" => "Demir", "username" => "gdemir", "password" => "123456", "department_id" => 1]);
	User::create(["first_name" => "Gökçe",  "last_name" => "Demir", "username" => "gcdemir", "password" => "123456", "department_id" => 1]);
	User::create(["first_name" => "Göktuğ", "last_name" => "Demir", "username" => "gtdemir", "password" => "123456", "department_id" => 2]);
	User::create(["first_name" => "Atilla", "last_name" => "Demir", "username" => "ademir", "password" => "123456", "department_id" => 2]);
	User::create(["first_name" => "Altay",  "last_name" => "Demir", "username" => "aldemir", "password" => "123456", "department_id" => 3]);
	User::create(["first_name" => "Başbuğ", "last_name" => "Demir", "username" => "bdemir", "password" => "123456", "department_id" => 3]);
	User::create(["first_name" => "Tarkan", "last_name" => "Demir", "username" => "tdemir", "password" => "123456", "department_id" => 3]);
}

// echo User::exists(1) ? "kayit var" : "kayit yok";

// User::load()->delete_all();
// Department::load()->delete_all();

?>