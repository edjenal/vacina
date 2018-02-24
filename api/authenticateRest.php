<?php
require 'baseRest.php';
include 'usuarioService.php';

if ($_SERVER['REQUEST_METHOD'] == "POST"){
  $retorno = (object) array('status' => false, 'msg' => 'Login ou senha inválidos');

  $obj = json_decode(file_get_contents("php://input"));

  if ($obj->email != null && $obj->senha != null ) {

    $usuario = (new UsuarioService())->getByLogin(
      (object) array('email' => $obj->email, 'senha' => $obj->senha));
    
    if ($usuario != null){
      $usuario->senha = md5($obj->senha);
      (new BaseRest())->jogaUsuarioNaSessao($usuario);

      $retorno = (object) array('status' => true, 'chave' => $usuario->senha);
    } 
  } 

  header('Content-Type: application/json');
  echo json_encode($retorno);

} else if ($_SERVER['REQUEST_METHOD'] == "GET"){
  
  $logado = (new BaseRest())->validaUsuario();
  header('Content-Type: application/json');
  echo json_encode($logado);

} else {
  //Verificar servicor php
  echo 'oi';
}