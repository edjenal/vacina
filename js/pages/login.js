app.controller('loginController', function($scope, AuthenticationService, $http) {
  
  var vm = this;

  function init() {
    AuthenticationService.Logout();   

    vm.emailJaCadastrado = false;

    vm.cabecalho = 'Criar conta / Fazer login';
    vm.error = undefined;
    //vm.email = 'teste@teste.com';
    //vm.senha = '1234';
  };

  init();

  $scope.verificaEmail = function (invalido) {
    if (!invalido){
      $http({
        url: 'api/usuarioRest.php',
        method: 'GET',
        params: {email: vm.email}
      })
      .then(function (retorno){
        vm.error = undefined;
        vm.emailJaCadastrado = retorno.data.status;
        if (vm.emailJaCadastrado) {
          vm.cabecalho = 'Fazer login';
        } else {
          vm.cabecalho = 'Criar conta';
        }
      });
    }
  }

  function efetuarLogin() {
    AuthenticationService.Login(vm.email, vm.senha, function (valido) {
        if (valido) {
            window.location = '#/';
        } else {
            vm.error = "Senha inválida!";
        }
      });
  }
  
  $scope.submitForm = function() {
    $scope.verificaEmail(false);

    if (vm.emailJaCadastrado) {
      efetuarLogin();
    } else {
      var usuario = {email: vm.email, senha: vm.senha};

      $http.post('api/usuarioRest.php', usuario)
      .then(function (retorno){
        efetuarLogin();
      });
    }
  };

});