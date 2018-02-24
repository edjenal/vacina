<?php
include 'banco/usuarioRepository.php';

class UsuarioService
{
  
  private $usuarioRepository;

  public function __construct() {
    $this->usuarioRepository = new UsuarioRepository();
  }

  private function retorno($retorno){
    header('Content-Type: application/json');
    echo json_encode($retorno);
  }
  
  public function getById($id){
    $retorno = $this->usuarioRepository->getById($id);
    return $this->retorno($retorno);
  }

  public function getByEmail($email){
    return $this->usuarioRepository->getByEmail($email);
  }

  public function getByLogin($usuario){
    $usuario->senha = md5($usuario->senha);
    $retorno = $this->usuarioRepository->getByLogin($usuario);
    return $retorno;
  }

  public function save($obj){
    $retorno = 'Obj nulo';

    if ($obj!= null) {
      $usuario = (object) array('email' => $obj->email, 'senha' => md5($obj->senha));
      $retorno = $this->usuarioRepository->save($usuario);
    }
    
    return $this->retorno($retorno); 
  }

  public function delete($id){
    $retorno = $this->usuarioRepository->delete($id);
    return $this->retorno($retorno); 
  }
}
