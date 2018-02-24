<?php
include 'banco/animalRepository.php';
include 'banco/geralRepository.php';

class AnimalService
{
    private $animalRepository;
    private $geralRepository;

    function __construct()
    {
        $this->animalRepository = new AnimalRepository();
        $this->geralRepository = new GeralRepository();
    }

    private function retorno($retorno){
      header('Content-Type: application/json');
      echo json_encode($retorno);
    }

    public function getComboEspecie(){
      $retorno = $this->geralRepository->getComboEspecie();
      return $this->retorno($retorno);
    }

    public function getAll(){
      $retorno = $this->animalRepository->getAll();
      return $this->retorno($retorno);
    }

    public function getById($id){
      $retorno = (object) array('animal'=>$this->animalRepository->getById($id),
        'especies' => $this->geralRepository->getComboEspecie());
      return $this->retorno($retorno);
    }

    public function save($obj){
      $retorno = 'Obj nulo';

      if ($obj!= null) {

        $animal = (object) array('idEspecie' => $obj->idEspecie, 
          'descricao' => $obj->descricao, 'ativo' => $obj->ativo == "true" ? 1 : 0);
        
        $retorno = $this->animalRepository->save($animal);
      }
      
      return $this->retorno($retorno);
    }

    public function update($obj){
      $retorno = 'Obj nulo';

      if ($obj!= null) {
        
        $animal = (object) array('id' => $obj->id, 'idEspecie' => $obj->idEspecie, 
          'descricao' => $obj->descricao, 'ativo' => $obj->ativo == "true" ? 1 : 0);
        
        $retorno = $this->animalRepository->update($animal);
      }
      
      return $this->retorno($retorno);
    }

    public function delete($id){
      $retorno = $this->animalRepository->delete($id);
      return $this->retorno($retorno);
    }
}