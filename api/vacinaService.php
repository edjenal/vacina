<?php
include 'banco/vacinaRepository.php';
include 'banco/geralRepository.php';
include 'banco/animalRepository.php';

class VacinaService
{
  
  private $vacinaRepository;
  private $geralRepository;
  private $animalRepository;

  public function __construct() {
    $this->vacinaRepository = new VacinaRepository();
    $this->geralRepository = new GeralRepository();
    $this->animalRepository = new AnimalRepository();
  }

  private function formataParaData($texto){
    return preg_replace('/([0-9]+)\/([0-9]+)\/([0-9]+)/', '$3-$2-$1', $texto);
  }

  private function retorno($retorno){
    header('Content-Type: application/json');
    echo json_encode($retorno);
  }

  public function getComboAnimaisAtivos(){
    $retorno = $this->geralRepository->getComboAnimaisAtivos();
    return $this->retorno($retorno);
  }

  public function getComboTipoVacinaByEspecie($idEspecie){
    $retorno = $this->geralRepository->getComboTipoVacinaByEspecie($idEspecie);
    return $this->retorno($retorno); 
  }
  
  public function getAll(){
    $retorno = $this->vacinaRepository->getAll();
    return $this->retorno($retorno);
  }

  public function getById($id){
    $vacina = $this->vacinaRepository->getById($id);
    $animal = $this->animalRepository->getById($vacina->idAnimal);
    $retorno = (object) array('animais' => $this->geralRepository->getComboAnimaisAtivos(),
      'tiposVacina' => $this->geralRepository->getComboTipoVacinaByEspecie($animal->idEspecie),
      'vacina' => $vacina);
    return $this->retorno($retorno);
  }

  public function save($obj){
    $retorno = 'Obj nulo';

    if ($obj!= null) {

      $vacina = (object) array('idAnimal' => $obj->idAnimal, 
        'idTipoVacina' => $obj->idTipoVacina,
        'tipo' => $obj->idTipoVacina == null ? $obj->tipo : null, 
        'dtTomou' => $this->formataParaData($obj->dtTomou), 
        'dtValidade' => $this->formataParaData($obj->dtValidade), 
        'lote' => $obj->lote);
      
      $retorno = $this->vacinaRepository->save($vacina);
    }
    
    return $this->retorno($retorno); 
  }

  public function update($obj){
    $retorno = 'Obj nulo';

    if ($obj!= null) {
      
      $vacina = (object) array('id' => $obj->id,
        'idAnimal' => $obj->idAnimal, 
        'idTipoVacina' => $obj->idTipoVacina, 
        'tipo' => $obj->idTipoVacina == null ? $obj->tipo : null, 
        'dtTomou' => $this->formataParaData($obj->dtTomou), 
        'dtValidade' => $this->formataParaData($obj->dtValidade), 
        'lote' => $obj->lote);
      
      $retorno = $this->vacinaRepository->update($vacina);
    }
    
    return $this->retorno($retorno); 
  }

  public function delete($id){
    $retorno = $this->vacinaRepository->delete($id);
    return $this->retorno($retorno); 
  }
}
