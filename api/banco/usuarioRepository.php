<?php
include_once('config.php');

class UsuarioRepository {

  private $mysql;

  function __construct() {
    $this->mysql = new Mysql();
  }

  private function dataToString($data){
    return str_replace("-", "/", date_format(date_create($data), "d-m-Y"));
  }

  public function getByLogin($usuario){
    $sql = "select id, email, date_created, ativo from usuario where email = :email and senha = :senha ;";

    $stmt = $this->mysql->PDO->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $stmt->execute(array(':email' => $usuario->email, ':senha' => $usuario->senha));
    $rows = $stmt->fetchAll( PDO::FETCH_ASSOC );

    $usuario = null;

    foreach ($rows as $r) {
      $usuario = (object) array('id' => $r['id'], 'email' => $r['email'], 
        'dtCriacao' => $this->dataToString($r['date_created']), 'ativo' => $r['ativo']);
        
      break;
    }

    return $usuario;
  }

  public function getByEmail($email){
    $sql = "select id, email, date_created, ativo from usuario where email = :email ;";

    $stmt = $this->mysql->PDO->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $stmt->execute(array(':email' => $email));
    $rows = $stmt->fetchAll( PDO::FETCH_ASSOC );

    $usuario = null;

    foreach ($rows as $r) {
      $usuario = (object) array('id' => $r['id'], 'email' => $r['email'], 
        'dtCriacao' => $this->dataToString($r['date_created']), 'ativo' => $r['ativo']);
        
      break;
    }

    return $usuario;
  }

  public function save($usuario){
    $sql = "insert into usuario (email, senha) values (:email, :senha ) ;";  

    $stmt = $this->mysql->PDO->prepare($sql);
    $stmt->bindParam(':email', $usuario->email);
    $stmt->bindParam(':senha', $usuario->senha);

    $stmt->execute();
    $lastRow = $this->mysql->PDO->lastInsertId('usuario');

    return 'Sucesso';
  }

  public function updateEmail($usuario){
    $sql = "update usuario set email = :email where id = id ;";

    $stmt = $this->mysql->PDO->prepare($sql);
    $stmt->bindParam(':email', $usuario->email);
    $stmt->bindParam(':id', $usuario->id);

    $stmt->execute();

    return 'Sucesso';
  }

}