<?php
include_once('config.php');

class AnimalRepository
{

  private $idUsuario;
  private $mysql;

  public function __construct() {
    $this->idUsuario = unserialize($_SESSION['usuario'])->id;
    $this->mysql = new Mysql();
  }

  public function getAll(){
    $sql = "select ani.id, ani.descricao, esp.descricao as dsEspecie, ani.ativo 
      from animal ani inner join especie esp on esp.id = ani.id_especie 
      where ani.id_usuario = :idUsuario ;";

    $stmt = $this->mysql->PDO->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $stmt->execute(array(':idUsuario' => $this->idUsuario));
    $rows = $stmt->fetchAll( PDO::FETCH_ASSOC );

    $retorno = array();

    foreach ($rows as $r) {
      $animal = (object) array('id' => $r['id'], 'descricao' => $r['descricao'], 'dsEspecie' => $r['dsEspecie'], 
        'ativo' => $r['ativo'] ? "Sim" : "Não");

      array_push($retorno, $animal);
    }

    return $retorno;
  }

  public function getById($id){
    $sql = "select id, descricao, id_especie, ativo 
      from animal where id_usuario = :idUsuario and id = :id ;";

    $stmt = $this->mysql->PDO->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $stmt->execute(array(':idUsuario' => $this->idUsuario, ':id' => $id));
    $rows = $stmt->fetchAll( PDO::FETCH_ASSOC );

    $animal = null;

    foreach ($rows as $r) {
      $animal = (object) array('id' => $r['id'], 'descricao' => $r['descricao'], 'idEspecie' => $r['id_especie'],
       'ativo' => $r['ativo'] == 1 ? "true" : "false");

      break;
    }

    return $animal;
  }

  public function save($animal){
    $sql = "insert into animal (id_especie, id_usuario, descricao, ativo) 
      values (:idEspecie, :idUsuario, :descricao, :ativo ) ;";

    $stmt = $this->mysql->PDO->prepare($sql);
    $stmt->bindParam(':idEspecie', $animal->idEspecie);
    $stmt->bindParam(':idUsuario', $this->idUsuario);
    $stmt->bindParam(':descricao', $animal->descricao);
    $stmt->bindParam(':ativo', $animal->ativo);

    $stmt->execute();
    $lastRow = $this->mysql->PDO->lastInsertId('animal');

    return 'Sucesso';
  }

  public function update($animal){
    $sql = "update animal set id_especie = :idEspecie, descricao = :descricao, 
      ativo = :ativo where id_usuario = :idUsuario and id = :id ;";

    $stmt = $this->mysql->PDO->prepare($sql);
    $stmt->bindParam(':idEspecie', $animal->idEspecie);
    $stmt->bindParam(':descricao', $animal->descricao);
    $stmt->bindParam(':ativo', $animal->ativo);
    //where
    $stmt->bindParam(':idUsuario', $this->idUsuario);
    $stmt->bindParam(':id', $animal->id);

    $stmt->execute();

    return 'Sucesso';
  }

  public function delete($id){
    $sql = "delete from vacina where id_animal = :id ;";
    $stmt = $this->mysql->PDO->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    $sql = "delete from animal where id = :id and id_usuario = :idUsuario ;";
    $stmt = $this->mysql->PDO->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':idUsuario', $this->idUsuario);
    $stmt->execute();

    return 'Sucesso';
  }

}