<?php
session_start();

class BaseRest 
{
  
  private function parseRequestHeaders() {
    $headers = array();
    foreach($_SERVER as $key => $value) {
      if (substr($key, 0, 5) <> 'HTTP_') {
          continue;
      }
      $header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
      $headers[$header] = $value;
    }
    return $headers['Token'];
  }

  public function jogaUsuarioNaSessao($usuario){
    $_SESSION['usuario'] = serialize(
      (object) array('id'=> $usuario->id, 'token' => $usuario->senha));
  }

  public function validaUsuario(){
    $obj = unserialize($_SESSION['usuario']);

    $token = $this->parseRequestHeaders();
    if ($token != null && $obj != null && 
      $token == $obj->token){
      return true;
    } else {
      //return false;
      http_response_code(401);
    }
  }
}