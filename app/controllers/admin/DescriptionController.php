<?php

class DescriptionController extends AdminController {

  public function index() {
    $this->descriptions = Description::all();
  }

//  public function new() {}

  public function create() {

    $description = Description::create($_POST);
    $this->redirect_to("/admin/description/show/" . $description->id);
  }

  public function show() {

    if (!$this->description = Description::find($this->id))
      return $this->redirect_to("/admin/description/index");

  }

  public function edit() {

    if (!$this->description = Description::find($this->id))
      return $this->redirect_to("/admin/description/index");
  }

  public function update() {
    $id = $_POST["id"];
    Description::update($id, $_POST);
    $this->redirect_to("/admin/description/show/" . $id);
  }

  public function destroy() {
    $id = $_POST["id"];
    Description::delete($id);
    return $this->redirect_to("/admin/description/index");
  }

}

?>