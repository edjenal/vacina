<?php
  include 'baseRest.php';
  include 'animalService.php';
  
  if ( (new BaseRest())->validaUsuario() ){
    if ($_SERVER['REQUEST_METHOD'] == "GET"){
      
      if (isset($_GET['especie'])) {
		    return (new AnimalService())->getComboEspecie();
      } else if (isset($_GET['id'])) {
        return (new AnimalService())->getById($_GET['id']);
      } else {
        return (new AnimalService())->getAll();
      }
      
    } else if ($_SERVER['REQUEST_METHOD'] == "POST"){
      $obj = json_decode(file_get_contents("php://input"));

      if ( !empty($obj->type) && $obj->type == "delete") {
        return (new AnimalService())->delete($obj->id);

      } else {
        return (new AnimalService())->save($obj);
      }

    } else if ($_SERVER['REQUEST_METHOD'] == "PUT"){
      
      $obj = json_decode(file_get_contents("php://input"));
      return (new AnimalService())->update($obj);

    }
  }