app.controller('homeController', function($scope, $http, NgTableParams) {
  $scope.home = 'active';

  function carregarLista(){
    $http.get("api/vacinaRest.php")
    .then(function (retorno) {
      var data = retorno.data;
      $scope.lista = data;
      $scope.tableParams = createUsingFullOptions(data);
    });
  };

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

  carregarLista();

  $scope.deletar = function (id) {
    
    var obj = {};
    obj.id = id;
    obj.type = 'delete';

    $http.post('api/vacinaRest.php', obj)
    .then(function (retorno){
      carregarLista();
    });
  }
  
});