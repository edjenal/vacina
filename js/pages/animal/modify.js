app.controller('listaAnimalController', function($scope, $http, $route, NgTableParams) {
  $scope.listaAnimal = 'active';
  $scope.animais = [];

  function init () {
    $http({
      url: 'api/animalRest.php',
      method: 'GET',
      params: {}
    })
    .then(function (retorno){
      var data = retorno.data;
      $scope.lista = data;
      $scope.tableParams = createUsingFullOptions(data);
    });
  }

  function createUsingFullOptions(data) {
    var initialParams = {
      count: 5 // initial page size
    };
    var initialSettings = {
      // page size buttons (right set of buttons in demo)
      counts: [],
      // determines the pager buttons (left set of buttons in demo)
      paginationMaxBlocks: 13,
      paginationMinBlocks: 2,
      dataset: data
    };
    return new NgTableParams(initialParams, initialSettings);
  }

  $scope.getDatasource = function ($column){
    if ($column.title() === "Ativo") {
      return [{ id: "Sim", title: "Sim"}, { id: "Não", title: "Não"}];
    }
  }

  init();

  $scope.deletar = function (id) {  
    var obj = {};
    obj.id = id;
    obj.type = 'delete';

    $http.post('api/animalRest.php', obj)
    .then(function (retorno){
      init();
    });
  }

});

app.controller('novoAnimalController', function($scope, $http, $route) {
  $scope.novo = 'active';
  $scope.animal = {};
  $scope.animal.ativo= "true";
  $scope.model = {};

  function init () {
    $http({
      url: 'api/animalRest.php',
      method: 'GET',
      params: {especie: true}
    })
    .then(function (retorno){
      $scope.model.especies = retorno.data;
    });
  }

  init();

  $scope.submitForm = function () {
    $http.post('api/animalRest.php', $scope.animal)
    .then(function (retorno){

      alert(retorno.data);

      $route.reload();
    });
  }
});

app.controller('edicaoAnimalController', function($scope, $routeParams, $http) {
  var id = $routeParams.id;
  $scope.animal = {};
  $scope.model = {};

  function init () {
    $http({
      url: 'api/animalRest.php',
      method: 'GET',
      params: {id: id}
    })
    .then(function (retorno){
      $scope.model.especies = retorno.data.especies;
      $scope.animal = retorno.data.animal;
    });
  }

  init();

  $scope.submitForm = function (argument) {
    $http.put('api/animalRest.php', $scope.animal)
    .then(function (retorno){
      alert(retorno.data);

      window.location = '#/animal';
    });
  }

});