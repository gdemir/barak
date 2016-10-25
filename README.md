# Barak

##  Requirements Packages and Versions

- MySQL

- Web server: apache2 or nginx (#TODO configuration file)

- Php Version : 7.0, - Php Database Access : [PDO](http://php.net/manual/tr/book.pdo.php)

- Install : [LAMP](http://gdemir.me/categories/linux/lamp/) or [LEMP](http://gdemir.me/categories/linux/lemp/)

## Guides

### Simple Usage
---

> `config/routes.php`

```php
    ApplicationRoutes::draw(
      get("/", "home#index")
    );
```

> `app/controller/HomeController.php`

```php
class HomeController extends ApplicationController {

  public function index() {
    $this->message = "Hello World";
  }

}
```

> `app/view/home/index.php`

```php
  echo "<h1>Home#Index</h1>";
  echo $message;
```

> `app/views/layouts/home_layout.php`

```html
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="tr" lang="tr">
  <head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title></title>
</head>
<body>
  {yield}
</body>
</html>
```

### Router (`config/routes.php`)
---

#### GET

- Simple

```php
  ApplicationRoutes::draw(
    get("/home/index")
  );
```

- Dynamical Segment

> `config/routes.php`

```php
  ApplicationRoutes::draw(
    get("/home/index/:id"),
  );
```

> `app/view/home/index.php`

```php
  echo "<h1>Home#Index</h1>";
  echo "id" . $params["id"];
```

#### POST

- Simple

```php
  ApplicationRoutes::draw(
    post("/admin/login")
  );
```

#### RESOURCE

```php
  ApplicationRoutes::draw(
    resource("/user")
  );
```

> *Aşağıdaki routes kümesini üretir:*

```php
  ApplicationRoutes::draw(
    get("/user/index"),           // all record
    get("/user/new"),             // new record form
    post("user/create"),          // new record create
    get("user/show/:id"),         // display record
    get("user/edit/:id"),         // edit record
    post("user/update"),          // update record
    post("user/destroy")          // destroy record
  );
```

### Controller (`app/controller/*.php`)
---

Her `config/routes.php` içerisinde tanımlanan `get` işlemi için `app/controller/*.php` dosyası içerisinde fonksiyon tanımlamak zorunlu değildir, tanımlanırsa bir değişken yükü/yükleri ilgili web sayfasına `$params[KEY]` şeklinde çekilebilir. Her `config/routes.php` içerisinde tanımlanan `post` için ilgili `app/controller/*.php` dosyası içerisinde fonksiyon tanımlamak zorunludur.

- Before Action

Before Action (`protected $before_actions`) özelliği, `app/controller/*.php` dosyası içerisinde her çalışacak get/post fonksiyonları için önceden çalışacak fonksiyonları belirtmeye yarayan özelliktir. Özelliğin etkisini ayarlamak için aşağıdaki 3 şekilde kullanılabilir:

1. `except` anahtarı ile nerelerde çalışmayacağını

2. `only` anahtarı ile nerelerde çalışacağını

3. Anahtar yok ise her yerde çalışacağını

```php
class HomeController extends ApplicationController {

 protected $before_actions = [
                               ["login", "except" => ["login", "index"]],
                               ["notice_clear", "only" => ["index"]],
                               ["every_time"]
                             ];

  public function index() {
    echo "HomeIndex : Anasayfa (bu işlem için login fonksiyonu çalışmaz, notice_clear ve every_time çalışır)";
  }

  public function login() {
    echo "Home#Login : Her işlem öncesi login oluyoruz. (get/post için /home/login, /home/index hariç)";
  }

  public function notice_clear() {
    echo "Home#NoticeClear : Duyular silindi. (get/post için sadece /home/index'de çalışır)";
  }

  public function every_time() {
    echo "Home#EveryTime : Her zaman get/post öncesi çalışırım.";
  }
```

- After Action

After Action (`protected $after_actions`) özelliği, `app/controller/*.php` dosyası içerisinde her çalışacak get/post fonksiyonları için sonradan çalışacak fonksiyonları belirtmeye yarayan özelliktir. Özelliğin etkisini ayarlamak için aşağıdaki 3 şekilde kullanılabilir:

1. `except` anahtarı ile nerelerde çalışmayacağını

2. `only` anahtarı ile nerelerde çalışacağını

3. Anahtar yok ise her yerde çalışacağını

`#TODO`

- Simple

> `config/routes.php`

```php
    ApplicationRoutes::draw(
      get("/admin/home"),
      get("/admin/login"),
      post("/admin/login")
    );
```

> `app/controller/AdminController.php`

```php
    class AdminController extends ApplicationController {
      protected $before_actions = [
                                      ["require_login", "except" => ["login"]]
                                  ];

      public function login() {
        if (isset($_SESSION['admin']))
          return $this->redirect_to("/admin/home");
        if (isset($_POST["username"]) and isset($_POST["password"])) {
          $user = User::where([
                    "username" => $_POST["username"],
                    "password" => $_POST["password"]
                    ]);
          if ($user) {
            echo "tebrikler";
            $_SESSION["admin"] = true;
            return $this->render("/admin/home");
          } else {
            echo "şifre veya parola hatalı";
          }
        }
        echo "otomatik render, login paneli gelmeli";
      }

      public function require_login() {
        echo "Her işlem öncesi login oluyoruz<br/>";
        if (!isset($_SESSION['admin']))
          return $this->redirect_to("/admin/login");
      }
    }
```

> `app/views/admin/login.php`

```php
    <div class="row">
      <div class="col-xs-3">
        <img src="/app/assets/img/default.png" class="img-thumbnail" />
      </div>
      <div class="col-xs-9">
        <form class="login-form" action="/admin/login" accept-charset="UTF-8" method="post">
          <input type="text" placeholder="Kullanıcı Adı" class="form-control" size="50" name="username" id="username" />
          <input type="password" placeholder="Parola" class="form-control" size="50" name="password" id="password" />
          <button type="submit" class="btn btn-primary" style="width:100%">SİSTEME GİRİŞ</button>
        </form>
      </div>
    </div>
```

> `app/views/admin/home.php`

```php
    echo "Admin#Home";
```

> `app/views/layouts/admin_layout.php`

```html
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="tr" lang="tr">
  <head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title></title>
</head>
<body>
  {yield}
</body>
</html>
```

### Views (`app/views/DIRECTORY/*.php`)
---

Her `get` işlemi için `config/routes.php` de yönlendirilen `controller` ve `action` adlarını alarak, `app/views/CONTROLLER/ACTION.php` html sayfası `app/views/layouts/CONTROLLER_layout.php` içerisine `{yield}` değişken kısmına gömülür ve görüntülenir.

> `app/views/DIRECTORY/*.php`

```html
<h1> Hello World </h1>
```

> `app/views/layouts/DIRECTORY_layout.php`

```html
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="tr" lang="tr">
  <head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title></title>
</head>
<body>
  {yield}
</body>
</html>
```

### Model
---

- Public Access Functions

> `new`, `save`, `destroy`, `delete_all`, `select`, `where`, `joins`, `order`, `group`, `limit`, `take`, `pluck`, `count`

- Static Access Functions

> `load`, `create`, `find`, `find_all`, `all`, `first`, `last`, `exists`, `delete`, `update`

#### CREATE ( `new`, `create` )


> `new`

```php
  // Ör. 1:

  $user = new User();
  $user->first_name = "Gökhan";
  $user->save();
  print_r($user); // otomatik id alır

  // Ör. 2:

  $user = new User(["first_name" => "Gökhan"]);
  $user->save();
  print_r($user); // otomatik id alır
```

> `create`

``` php
  $user = User::create(["first_name" => "Gökhan"]);
  print_r($user);
```

#### READ ( `load`, `select`, `where`, `order`, `group`, `limit`, `take`, `pluck`, `count`, `joins`, `find`, `find_all`, `all`, `first`, `last` )

> `load`


```php
  $users = User::load()->take();
  foreach ($user as $user)
    echo $user->first_name;
```

> `select`, `where`, `order`, `group`, `limit`, `take`

```php
  $users = User::load()
             ->where(["first_name" = "Gökhan"])
             ->select("first_name")
             ->order("id")
             ->limit("10")
             ->take();

  foreach ($user as $user)
    echo $user->first_name;
```

> `pluck`

```php
  // Ör. 1:
  $user_ids = User::load()->pluck("id");
  print_r($user_ids);
  // [1, 2, 3, 4, 66, 677, 678]


  // Ör. 2:

  $user_firstnames = User::load()->pluck("first_name");
  print_r($user_firstnames);
  // ["Gökhan", "Göktuğ", "Gökçe", "Gökay", "Atilla", "Altay", "Tarkan", "Başbuğ", "Ülkü"]
```

> `count`

```php
  // Ör. 1:
  echo User::load()->count();
  // 12


  // Ör. 2:

  echo User::load()->where(["first_name" => "Gökhan"])->count();
  // 5
```


> `joins`

```php
  // Department ["id", "name"], User ["id", "department_id", "first_name"], "Address" ["id", "user_id", "content"]

  $department = Department::load()
                  ->joins(["User", "Address"])
                  ->where(["User.id" => "1"])
                  ->select("User.first_name, Department.name, Address.content")
                  ->limit(1)->take();
  print_r($department);
```

> `find`

```php
  $user = User::find(1);
  echo $user->first_name;
```

> `find_all`

```php
  $users = User::find_all([1, 2, 3]);
  foreach ($users as $user)
    echo $user->first_name;
```

> `all`

```php
  $users = User::all();
  foreach ($users as $user)
    echo $user->first_name;
```

> `first`

```php
  // Ör. 1:

  $user = User::first();
  echo $user->first_name;

  // Ör. 2:
  $users = User::first(10);
  foreach ($users as $user)
    echo $user->first_name;
```

> `last`

```php
  // Ör. 1:

  $user = User::last();
  echo $user->first_name;

  // Ör. 2:

  $users = User::last(10);
  foreach ($users as $user)
    echo $user->first_name;
```

> `exists`

```php
  echo User::exists(1) ? "kayit var" : "kayit yok";
```

#### UPDATE ( `save`, `update` )

> `save`

```php
  // Ör. 1:

  $user = User::find(1);
  $user = User::first();
  $user = User::last();
  $user->first_name = "Gökhan";
  $user->save()
  print_r($user);

  // Ör. 2:

  $users = User::find_all([1, 2, 3]);
  $users = User::load()->take();
  $users = User::all();
  $users = User::load()
             ->where(["first_name" = "Gökhan"])
             ->select("first_name")
             ->order("id")
             ->limit("10")
             ->take();
  $users = User::first(10);
  foreach ($users as $user) {
    $user->first_name = "Göktuğ";
    $user->save();
  }
```

> `update`

```php
  // Ör. 1:

  User::update(1, array("first_name" => "Gökhan", "last_name" => "Demir"));

  // Ör. 2:

  $users = User::find_all([1, 2, 3]);
  $users = User::load()->take();
  $users = User::all();
  $users = User::load()
             ->where(["first_name" = "Gökhan"])
             ->select("first_name")
             ->order("id")
             ->limit("10")
             ->take();
  foreach ($users as $user)
    User::update($user->id, array("first_name" => "Göktuğ", "last_name" => "Demir"));
```

#### DELETE ( `destroy`, `delete`, `delete_all` )

> `destroy`

```php

  $user = User::find(1);
  $user = User::first();
  $user = User::last();
  $user->destroy();

```

> `delete`

```php
  User::delete(1);
```

> `delete_all`

```php
  User::load()->delete_all();
  User::load()->where(["first_name" => "Gökhan"])->delete_all();
  User::load()->where(["first_name" => "Gökhan"])->limit(10)->delete_all();
  User::load()->limit(10)->delete_all();
```

### Config and Database
---

> `config/database.ini` (database configuration file)

```ini
[database_configuration]
host  = localhost
user  = root
pass  = barak
name  = BARAK
```

> `db/seeds.php` (database seeds file)

```php
  if (User::load()->count() == 0) {
	User::create(["first_name" => "Gökhan", "last_name" => "Demir", "username" => "gdemir",  "password" => "123456"]);
	User::create(["first_name" => "Gökçe",  "last_name" => "Demir", "username" => "gcdemir", "password" => "123456"]);
	User::create(["first_name" => "Göktuğ", "last_name" => "Demir", "username" => "gtdemir", "password" => "123456"]);
	User::create(["first_name" => "Atilla", "last_name" => "Demir", "username" => "ademir",  "password" => "123456"]);
  }
```

## Trailer

[![BarakTurkmens](https://img.youtube.com/vi/cYNnHN5w1ok/2.jpg)](https://www.youtube.com/watch?v=cYNnHN5w1ok)
[![BarakTurkmens#İskan](https://img.youtube.com/vi/haNqSJKs_j4/2.jpg)](https://www.youtube.com/watch?v=haNqSJKs_j4)
[![BarakTurkmens#YanıkKerem](https://img.youtube.com/vi/m21oNITMdyI/2.jpg)](https://www.youtube.com/watch?v=m21oNITMdyI)
[![BarakTurkmens#MürselBey](https://img.youtube.com/vi/uSoz28QpHRI/2.jpg)](https://www.youtube.com/watch?v=uSoz28QpHRI)
[![BarakTurkmens#VeledBey](https://img.youtube.com/vi/3RBtPGWRnsI/2.jpg)](https://www.youtube.com/watch?v=3RBtPGWRnsI)

## Sources

- [https://tr.wikipedia.org/wiki/Barak_T%C3%BCrkmenleri](https://tr.wikipedia.org/wiki/Barak_T%C3%BCrkmenleri)
