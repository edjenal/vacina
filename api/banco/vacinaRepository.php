<?php
include_once('config.php');

class VacinaRepository
{

  private $idUsuario;
  private $mysql;

  public function __construct() {
    $this->idUsuario = unserialize($_SESSION['usuario'])->id;
    $this->mysql = new Mysql();
  }

  private function dataToString($data){
    return str_replace("-", "/", date_format(date_create($data), "d-m-Y"));
  }

  public function getAll(){

    $sql = "select vac.id as 'id', ani.descricao as 'ds_animal', 
      esp.descricao as 'ds_especie', tp_vac.nome as 'nm_vacina', 
      vac.tipo as 'tipo', vac.dt_tomou as 'dt_tomou', vac.lote as 'lote',
      vac.dt_validade as 'dt_validade'
      from animal ani 
      inner join especie esp on esp.id = ani.id_especie
      inner join vacina vac on vac.id_animal = ani.id
      left join tipo_vacina tp_vac on tp_vac.id = vac.id_tipo_vacina
      where ani.ativo = 1 and ani.id_usuario = :idUsuario ;";

    $stmt = $this->mysql->PDO->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $stmt->execute(array(':idUsuario' => $this->idUsuario));
    $rows = $stmt->fetchAll( PDO::FETCH_ASSOC );

    $retorno = array();

    foreach ($rows as $r) {      
      $vacina = (object) array('id' => $r['id'], 'dsAnimal' => $r['ds_animal'], 
        'dsEspecie' => $r['ds_especie'], 'dsTpVacina' => isset($r['nm_vacina']) ? $r['nm_vacina'] : $r['tipo'], 
        'lote' => $r['lote'], 'dtTomou' => $this->dataToString($r['dt_tomou']),
        'dtValidade' => $this->dataToString($r['dt_validade']));

      array_push($retorno, $vacina);
    }

    return $retorno;
  }

  public function getById($id){
    $sql = "select ani.id as 'id_animal', vac.id_tipo_vacina, vac.tipo, vac.dt_tomou, 
      vac.dt_validade, vac.lote
      from animal ani 
      inner join vacina vac on vac.id_animal = ani.id
      where ani.id_usuario = :idUsuario and vac.id = :id ;";

    $stmt = $this->mysql->PDO->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $stmt->execute(array(':idUsuario' => $this->idUsuario, ':id' => $id));
    $rows = $stmt->fetchAll( PDO::FETCH_ASSOC );

    $vacina = null;

    foreach ($rows as $r) {
      $vacina = (object) array('id' => $r['id'], 'idAnimal' => $r['id_animal'], 
        'idTipoVacina' => $r['id_tipo_vacina'], 'lote' => $r['lote'], 
        'tipo' => $r['tipo'], 'dtTomou' => $this->dataToString($r['dt_tomou']),
        'dtValidade' => $this->dataToString($r['dt_validade']));

      break;
    }

    return $vacina;
  }

  public function save($vacina){
    //$idTipoVacina = isset($vacina->idTipoVacina) ? "'$vacina->idTipoVacina'" : "NULL";
    
    $sql = "insert into vacina 
        (id_animal, id_tipo_vacina, tipo, dt_tomou, dt_validade, lote)
      values (:idAnimal, :idTipoVacina, :tipo, :dtTomou, :dtValidade, :lote) ;";

    $stmt = $this->mysql->PDO->prepare($sql);
    $stmt->bindParam(':idAnimal', $vacina->idAnimal);
    $stmt->bindParam(':idTipoVacina', $vacina->idTipoVacina);
    $stmt->bindParam(':tipo', $vacina->tipo);
    $stmt->bindParam(':dtTomou', $vacina->dtTomou);
    $stmt->bindParam(':dtValidade', $vacina->dtValidade);
    $stmt->bindParam(':lote', $vacina->lote);

    $stmt->execute();
    $lastRow = $this->mysql->PDO->lastInsertId('vacina');

    return 'Sucesso';
  }

  public function update($vacina){
    //$idTipoVacina = isset($vacina->idTipoVacina) ? "'$vacina->idTipoVacina'" : "NULL";

    $sql = "update vacina set 
        id_animal = :idAnimal, id_tipo_vacina = :idTipoVacina, 
        tipo = :tipo, dt_tomou = :dtTomou, 
        dt_validade = :dtValidade, lote = :lote where id = :id ;";

    $stmt = $this->mysql->PDO->prepare($sql);
    $stmt->bindParam(':idAnimal', $vacina->idAnimal);
    $stmt->bindParam(':idTipoVacina', $vacina->idTipoVacina);
    $stmt->bindParam(':tipo', $vacina->tipo);
    $stmt->bindParam(':dtTomou', $vacina->dtTomou);
    $stmt->bindParam(':dtValidade', $vacina->dtValidade);
    $stmt->bindParam(':lote', $vacina->lote);
    //where
    $stmt->bindParam(':id', $vacina->id);

    $stmt->execute();

    return 'Sucesso';
  }

  public function delete($id){
    $sql = "delete from vacina where id = :id ;";

    $stmt = $this->mysql->PDO->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    return 'Sucesso';
  }
}