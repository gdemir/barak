<?php

if (isset($GLOBALS['danger']))
  echo '<div class="alert alert-danger" role="alert">' . $GLOBALS['danger'] . '</div>';
if (isset($GLOBALS['warning']))
  echo '<div class="alert alert-warning" role="alert">' . $GLOBALS['warning'] . '</div>';
if (isset($GLOBALS['success']))
  echo '<div class="alert alert-success" role="alert">' . $GLOBALS['success'] . '</div>';
if (isset($GLOBALS['info']))
  echo '<div class="alert alert-info" role="alert">' . $GLOBALS['info'] . '</div>';
?>