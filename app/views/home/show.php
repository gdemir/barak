<?php
echo "Showwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwww";

  foreach ($users as $user) {
    echo $user->first_name . "<br/>";
  }
  //$render = "/home/index";
  $render = "index";
  $render = explode("/", trim($render, "/"));
  print_r($render);
  if (isset($render[1]))
    echo "evet";
  else
    echo "hayir";
?>
