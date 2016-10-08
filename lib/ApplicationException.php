<?php

class ApplicationException extends Exception {

  public function __construct($message, $request) {
    echo(sprintf(
      "
      <p style = '
      text-align: center;
      padding: 3px;
      color: #3072b3;
      padding: 8px;
      border: 6px solid #ddd;
      border-radius: 5px;
      box-shadow: 0px 0px 5px #ddd;
      background: #e5fcfd;
      '>

      <b style = 'color:rgba(201, 2, 92, 0.5);'> %s </b> → %s [<i style = 'color:rgba(201, 2, 92, 0.5);'> %s </i>]

      </p>
      ", static::class, $message, $request
      ));

    parent::__construct("$request : $message");
  }

};

// extends ApplicationException class define

class FileNotFoundException extends ApplicationException {};
class ConfigurationException extends ApplicationException {};
class FieldNotFoundException extends ApplicationException {};
class TableNotFoundException extends ApplicationException {};
class SQLException extends ApplicationException {};

?>
