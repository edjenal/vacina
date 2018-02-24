<?php
  include 'baseRest.php';
  include 'vacinaService.php';

  if ( (new BaseRest())->validaUsuario() ){
    if ($_SERVER['REQUEST_METHOD'] == "GET"){
    
      if (isset($_GET['animal'])) {
        return (new VacinaService())->getComboAnimaisAtivos();
      } else if (isset($_GET['idEspecie'])) {
        return (new VacinaService())->getComboTipoVacinaByEspecie($_GET['idEspecie']);
      } else if (isset($_GET['id'])) {
        return (new VacinaService())->getById($_GET['id']);
      } else {
        return (new VacinaService())->getAll();
      }
      
    } else if ($_SERVER['REQUEST_METHOD'] == "POST"){
      $obj = json_decode(file_get_contents("php://input"));

      if (!empty($obj->type) && $obj->type == "delete") {
        return (new VacinaService())->delete($obj->id);

      } else {
        return (new VacinaService())->save($obj);
      }

    } else if ($_SERVER['REQUEST_METHOD'] == "PUT"){
      
      $obj = json_decode(file_get_contents("php://input"));
      return (new VacinaService())->update($obj);

    }
  }