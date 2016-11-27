<?php

class User extends ApplicationModel {

  public function full_name() {
    echo $this->first_name . " " . $this->last_name;
  }

}

?>