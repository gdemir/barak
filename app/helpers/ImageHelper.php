<?php

class ImageHelper {

  public static function upload_path() {
    return $_SERVER["DOCUMENT_ROOT"];
  }

  // example file_upload
  //
  // HTML
  //<form method="post" enctype="multipart/form-data">
  //  <input type="file" id="file" name="file" multiple class="form-control"/>
  //  <button type="submit" class="btn btn-primary">Send</button>
  //</form>
  //
  // PHP
  // $file = $_FILES['file'];
  //
  // ImageHelper::file_upload($file, "/upload", "logo");
  // return "/upload/logo.png";
  // ImageHelper::file_upload($file, "/upload", "file");
  // return "/upload/file.txt";
  // ImageHelper::file_upload($file, "/upload/users", "logo");
  // return /upload/users/logo.png";
  // ImageHelper::file_upload($file, "/upload/texts", "file");
  // return "/upload/texts/file.txt";
  //
  // NOTE
  // $_SERVER["DOCUMENT_ROOT"] owner should be www-data
  // sudo chown www-data:www-data /var/www/
  // sudo chown www-data:www-data /var/www/PROJECT

  public static function file_upload($file, $upload_directory, $upload_file) {

    $temp_file = $file["tmp_name"];
    $path_parts = pathinfo($file["name"]);
    $upload_filename = $upload_file . "." . strtolower($path_parts["extension"]);

    $upload_directory_path = ImageHelper::upload_path() . $upload_directory;
    if (!is_dir($upload_directory_path))
      mkdir($upload_directory_path, 0777, true);
    $upload_file_path = $upload_directory_path . "/" . $upload_filename;
    move_uploaded_file($temp_file, $upload_file_path);
    return $upload_directory . "/" . $upload_filename;
  }

  // example file_copy
  //
  // ImageHelper::file_copy("/assets/img/default.png", "/upload/agendas", "$agenda_id.png");
  // return "/upload/agendas/$agenda_id.png";

  public static function file_copy($source_file, $destination_directory, $destination_file) {
    $destination_directory_path = ImageHelper::upload_path() . $destination_directory;

    if (!is_dir($destination_directory_path))
      mkdir($destination_directory_path, 0777, true);

    $destination_file_path = $destination_directory_path . "/" . $destination_file;
    copy(ImageHelper::upload_path() . $source_file, $destination_file_path);

    return $destination_directory . "/" . $destination_file;
  }

  // example file_remove
  //
  // ImageHelper::file_remove("/upload/logo.png");
  // ImageHelper::file_remove("/upload/users/logo.png");

  public static function file_remove($filename) {
    $file_path = ImageHelper::upload_path() . "/$filename";
    if (is_file($file_path))
      unlink($file_path);
  }

  public static function file_update($filename, $file, $upload_directory, $upload_file) {
    ImageHelper::file_remove($filename);
    return ImageHelper::file_upload($file, $upload_directory, $upload_file);
  }

}

?>