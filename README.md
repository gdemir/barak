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

> `app/view/home/index.html`

```html

  Home#Index
  <?php echo $message; ?>
  
```

### Router (`config/routes.php`)
---

#### GET 

- Simple

```php
  ApplicationRoutes::draw(
    get("/", "home#index")
  );
```

- Dynamical Segment

> `config/routes.php`

```php
  ApplicationRoutes::draw(
    get("/home/index/:id"),
  );
```

> `app/view/home/index.html`

```html
  Home#Index
  <?php
    echo "id" . $params["id"];
  ?>
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

### Views (`app/views/DIRECTORY/*.php`)
---

Her `get` işlemi için `config/routes.php` de yönlendirilen `controller` ve `action` adlarını alarak, `app/views/CONTROLLER/action.php` html sayfası `app/views/layout/DIRECTORY_layouts.php` içerisine `{yield}` değişken kısmına gömülür ve görüntülenir. 

> `app/views/DIRECTORY/*.php`

```html
<h1> Hello World </h1>
```

> `app/views/layout/DIRECTORY_layouts.php`

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

## Trailer

[![BarakTurkmens](https://img.youtube.com/vi/cYNnHN5w1ok/2.jpg)](https://www.youtube.com/watch?v=cYNnHN5w1ok)
[![BarakTurkmens#İskan](https://img.youtube.com/vi/haNqSJKs_j4/2.jpg)](https://www.youtube.com/watch?v=haNqSJKs_j4)
[![BarakTurkmens#YanıkKerem](https://img.youtube.com/vi/m21oNITMdyI/2.jpg)](https://www.youtube.com/watch?v=m21oNITMdyI)
[![BarakTurkmens#MürselBey](https://img.youtube.com/vi/uSoz28QpHRI/2.jpg)](https://www.youtube.com/watch?v=uSoz28QpHRI)
[![BarakTurkmens#VeledBey](https://img.youtube.com/vi/3RBtPGWRnsI/2.jpg)](https://www.youtube.com/watch?v=3RBtPGWRnsI)

## Sources

- [https://tr.wikipedia.org/wiki/Barak_T%C3%BCrkmenleri](https://tr.wikipedia.org/wiki/Barak_T%C3%BCrkmenleri)
