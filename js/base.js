'use strict';

var app = angular.module('app', ['ngMessages', 'ngRoute', 'ngStorage', 'ngTable']);

app.config(['$routeProvider', function($routeProvider) {
  $routeProvider.
    when('/', {
      templateUrl: 'pages/home.html',
      controller: 'homeController'
    }).
    when('/vacina', {
      templateUrl: 'pages/vacina/modify.html',
      controller: 'novaVacinaController'
    }).
    when('/vacina/:id', {
      templateUrl: 'pages/vacina/modify.html',
      controller: 'edicaoVacinaController'
    }).
    when('/animal', {
      templateUrl: 'pages/animal/lista.html',
      controller: 'listaAnimalController'
    }).
    when('/animal/novo', {
      templateUrl: 'pages/animal/modify.html',
      controller: 'novoAnimalController'
    }).
    when('/animal/:id', {
      templateUrl: 'pages/animal/modify.html',
      controller: 'edicaoAnimalController'
    }).
    when('/login', {
      templateUrl: 'login.html',
      controller: 'loginController as vm'
    });
}]);

app.directive('ngConfirmClick', [
  function(){
    return {
      priority: -1,
      restrict: 'A',
      link: function(scope, element, attrs){
        element.bind('click', function(e){
          var message = attrs.ngConfirmClick;
          if(message && !confirm(message)){
            e.stopImmediatePropagation();
            e.preventDefault();
          }
        });
      }
    }
  }
]);

app.factory('AuthenticationService', 
  function ($http, $localStorage) {
    var service = {};

    service.Login = Login;
    service.Logout = Logout;

    return service;

    function Login(email, senha, callback) {

      $http.post('api/authenticateRest.php', {email:email, senha:senha})
      .then(function (response){
        //console.log(response.data);
        // login successful if there's a token in the response
        if (response.data && response.data.status) {
            // store username and token in local storage to keep user logged in between page refreshes
            $localStorage.currentUser = 
            { nome: response.data.nome, token: response.data.chave };

            // add jwt token to auth header for all requests made by the $http service
            $http.defaults.headers.common.Authorization =
             'Bearer ' + response.data.chave;

            // execute callback with true to indicate successful login
            callback(true);
        } else {
            // execute callback with false to indicate failed login
            callback(false);
        }
        
      });

    }

    function Logout() {
        // remove user from local storage and clear http auth header
        delete $localStorage.currentUser;
        $http.defaults.headers.common.Authorization = '';

        $http({
          url: 'api/clearSession.php',
          method: 'GET',
          params: {}
        })
        .then(function (retorno){
          
        });
    }
});

app.run(
  function ($rootScope, $http, $location, $localStorage) {
    // redirect to login page if not logged in and trying to access a restricted page
    $rootScope.$on('$locationChangeStart', function (event, next, current) {
        var publicPages = ['/login'];
        var restrictedPage = publicPages.indexOf($location.path()) === -1;
        if (restrictedPage && !$localStorage.currentUser) {
            $location.path('/login');
        }
    });

});

app.factory('httpRequestInterceptor', function ($localStorage) {
  return {
    request: function (config) {

      // use this to destroying other existing headers
      if ($localStorage.currentUser) {
        config.headers = {'token':$localStorage.currentUser.token}
      }

      return config;
    }
  };
});

//http://stackoverflow.com/questions/25979033/handle-angular-401-responses
app.factory('httpErrorRequestInterceptor', function ($q, $rootScope, $location) {
    return {
        request: function (config) {
            return config || $q.when(config)
        },
        response: function (response) {
            return response || $q.when(response);
        },
        responseError: function (response) {
            if (response.status === 401) {
                //here I preserve login page 
                $location.path('/login');
            }
            return $q.reject(response);
        }
    };
})

app.config(function ($httpProvider) {
  $httpProvider.interceptors.push('httpErrorRequestInterceptor');
  $httpProvider.interceptors.push('httpRequestInterceptor');
});