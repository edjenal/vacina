<?php
  include 'baseRest.php';
  include 'usuarioService.php';

  if ($_SERVER['REQUEST_METHOD'] == "POST"){
    $retorno = (object) array('status' => false, 'msg' => 'Dados inválidos');

    $obj = json_decode(file_get_contents("php://input"));

    if ($obj->email != null && $obj->senha != null) {
      $usuario = (new UsuarioService())->getByEmail($obj->email);

      if ($usuario == null || $usuario->id == null) {
        return (new UsuarioService())->save($obj);
      }
    }
  } else if ($_SERVER['REQUEST_METHOD'] == "PUT"){
    //
  } else if ($_SERVER['REQUEST_METHOD'] == "GET"){
    
    if (isset($_GET['email'])) {
      
      $usuario = (new UsuarioService())->getByEmail($_GET['email']);
      
      if ($usuario == null) {
        $retorno = (object) array('status'=>false, 'msg'=>'Email disponível');
      } else {
        $retorno = (object) array('status'=>true, 'msg'=>'Email em uso');
      }

      header('Content-Type: application/json');
      echo json_encode($retorno);
    } 
  } 