app.controller('novaVacinaController', function($scope, $http, $route) {
  $scope.novaVacina = 'active';
  $scope.vacina = {};
  $scope.model = {};

  function init () {
    $http({
      url: 'api/vacinaRest.php',
      method: 'GET',
      params: {animal: true}
    })
    .then(function (retorno){
      $scope.model.animais = retorno.data;
    });
  }

  init();

  $scope.carregaTiposVacina = function () {
    var idEspecie = (JSON.parse($scope.vacina.animal)).idEspecie;
    if ($scope.vacina.animal) {
      $http({
        url: 'api/vacinaRest.php',
        method: 'GET',
        params: {idEspecie: idEspecie}
      })
      .then(function (retorno){
        $scope.model.tiposVacina = retorno.data;
      });
    }
  }

  $scope.limparIdTipoVacina = function () {
    $scope.vacina.idTipoVacina = undefined;
  }

  $scope.limparTipo = function () {
    $scope.vacina.tipo = undefined;
  }
  
  $scope.submitForm = function (argument) {

      var dtTomouArray = $scope.vacina.dtTomou.split("/");
      var dtValidadeArray = $scope.vacina.dtValidade.split("/");

      var dtTomou = new Date(dtTomouArray[2]+'-'+dtTomouArray[1]+'-'+dtTomouArray[0]);
      var dtValidade = new Date(dtValidadeArray[2]+'-'+dtValidadeArray[1]+'-'+dtValidadeArray[0]);

      if (dtTomou < dtValidade) {
        var vacina = {};
        vacina.idAnimal = (JSON.parse($scope.vacina.animal)).id;
        vacina.idTipoVacina = $scope.vacina.idTipoVacina;
        vacina.tipo = $scope.vacina.tipo;
        vacina.dtTomou = $scope.vacina.dtTomou;
        vacina.dtValidade = $scope.vacina.dtValidade;
        vacina.lote = $scope.vacina.lote;

        $http.post('api/vacinaRest.php', vacina)
        .then(function (retorno){

          alert(retorno.data);

          $route.reload();
        });
      } else {
        alert('Data de validade maior que a data que tomou.');
      }
  }
});

app.controller('edicaoVacinaController', function($scope, $routeParams, $http) {
  var id = $routeParams.id;
  $scope.vacina = {};
  $scope.model = {};

  function init () {
    $http({
      url: 'api/vacinaRest.php',
      method: 'GET',
      params: {id: id}
    })
    .then(function (retorno){
      $scope.model.animais = retorno.data.animais;
      $scope.model.tiposVacina = retorno.data.tiposVacina;
      $scope.vacina = retorno.data.vacina;
      
      for (var i = 0; i < $scope.model.animais.length; i++) {
        if ($scope.model.animais[i].id == $scope.vacina.idAnimal) {
          $scope.vacina.animal = JSON.stringify($scope.model.animais[i]);
          $scope.vacina.idAnimal = undefined;
          break;
        }
      }
    });
  }

  init();

  function carregaTiposVacina () {
    if ($scope.vacina.animal) {
      $http({
        url: 'api/vacinaRest.php',
        method: 'GET',
        params: {idEspecie: $scope.vacina.animal.idEspecie}
      })
      .then(function (retorno){
        $scope.model.tiposVacina = retorno.data;
      });
    }
  }

  $scope.limparIdTipoVacina = function () {
    $scope.vacina.idTipoVacina = undefined;
  }

  $scope.limparTipo = function () {
    $scope.vacina.tipo = undefined;
  }

  $scope.submitForm = function (argument) {
      var dtTomouArray = $scope.vacina.dtTomou.split("/");
      var dtValidadeArray = $scope.vacina.dtValidade.split("/");
          
      var dtTomou = new Date(dtTomouArray[2]+'-'+dtTomouArray[1]+'-'+dtTomouArray[0]);
      var dtValidade = new Date(dtValidadeArray[2]+'-'+dtValidadeArray[1]+'-'+dtValidadeArray[0]);

      if (dtTomou < dtValidade) {
        var vacina = {};
        vacina.id = id;
        vacina.idAnimal = (JSON.parse($scope.vacina.animal)).id;
        vacina.idTipoVacina = $scope.vacina.idTipoVacina;
        vacina.tipo = $scope.vacina.tipo;
        vacina.dtTomou = $scope.vacina.dtTomou;
        vacina.dtValidade = $scope.vacina.dtValidade;
        vacina.lote = $scope.vacina.lote;

        $http.put('api/vacinaRest.php', vacina)
        .then(function (retorno){
          alert(retorno.data);

          window.location = '#/';
        });
      } else {
        alert('Formulário inválido');
      }
  }
});