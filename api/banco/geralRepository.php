<?php
include_once('config.php');

class GeralRepository
{

  private $idUsuario;
  private $mysql;

  public function __construct() {
    $this->idUsuario = unserialize($_SESSION['usuario'])->id;
    $this->mysql = new Mysql();
  }

  public function getComboAnimaisAtivos(){
    $sql = "select id, descricao, id_especie from animal where id_usuario = :idUsuario and ativo = 1 ;";

    $stmt = $this->mysql->PDO->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $stmt->execute(array(':idUsuario' => $this->idUsuario));
    $rows = $stmt->fetchAll( PDO::FETCH_ASSOC );

    $retorno = array();

    foreach ($rows as $r) {
      $animais = (object) array('id' => $r['id'], 'descricao' => $r['descricao'], 'idEspecie' => $r['id_especie']);

      array_push($retorno, $animais);
    }

    return $retorno;
  }

  public function getComboEspecie(){
    $sql = "select id, descricao from especie where ativo = 1 ;";

    $stmt = $this->mysql->PDO->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $stmt->execute();
    $rows = $stmt->fetchAll( PDO::FETCH_ASSOC );

    $retorno = array();

    foreach ($rows as $r) {
      $especie = (object) ['id' => $r['id'], 'descricao' => $r['descricao']];

      array_push($retorno, $especie);
    }

    return $retorno;
  }

  public function getComboTipoVacinaByEspecie($idEspecie){
    $sql = "select id, nome, descricao from tipo_vacina where id_especie = :idEspecie and ativo = 1 ;";

    $stmt = $this->mysql->PDO->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $stmt->execute(array(':idEspecie' => $idEspecie));
    $rows = $stmt->fetchAll( PDO::FETCH_ASSOC );

    $retorno = array();

    foreach ($rows as $r) {
      $tipoVacina = (object) array('id' => $r['id'], 'nome' => $r['nome'], 'descricao' => $r['descricao']);

      array_push($retorno, $tipoVacina);
    }

    return $retorno;
  }
}