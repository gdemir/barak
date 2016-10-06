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

> *Generates the following routes:*

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

- Before Action

```php
class HomeController extends ApplicationController {

 protected $before_actions = [
                               ["login", "except" => ["login", "index"]],
                               ["notice_clear", "only" => ["index"]],
                               ["every_time"]
                             ];
  
  public function index() {
    echo "HomeIndex : Anasayfa görüntülerim. (get/post için login fonksiyonu çalışmaz, nitce_clear ve every_time çalışır)";
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

## Trailer

[![BarakTurkmens](https://img.youtube.com/vi/cYNnHN5w1ok/2.jpg)](https://www.youtube.com/watch?v=cYNnHN5w1ok)
[![BarakTurkmens#İskan](https://img.youtube.com/vi/haNqSJKs_j4/2.jpg)](https://www.youtube.com/watch?v=haNqSJKs_j4)
[![BarakTurkmens#YanıkKerem](https://img.youtube.com/vi/m21oNITMdyI/2.jpg)](https://www.youtube.com/watch?v=m21oNITMdyI)
[![BarakTurkmens#MürselBey](https://img.youtube.com/vi/uSoz28QpHRI/2.jpg)](https://www.youtube.com/watch?v=uSoz28QpHRI)
[![BarakTurkmens#VeledBey](https://img.youtube.com/vi/3RBtPGWRnsI/2.jpg)](https://www.youtube.com/watch?v=3RBtPGWRnsI)

## Sources

- [https://tr.wikipedia.org/wiki/Barak_T%C3%BCrkmenleri](https://tr.wikipedia.org/wiki/Barak_T%C3%BCrkmenleri)
