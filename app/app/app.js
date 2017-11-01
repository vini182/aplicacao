var app = angular.module('myApp', ['ngRoute', 'ngAnimate', 'toaster']);

app.config(['$routeProvider',
  function ($routeProvider) {
        $routeProvider.
        when('/login', {
            title: 'Login',
            templateUrl: 'partials/login.html',
            controller: 'authCtrl'
        })
            .when('/logout', {
                title: 'Logout',
                templateUrl: 'partials/login.html',
                controller: 'logoutCtrl'
            })

		.when('/resetsenha', {
                title: 'Resetsenha',
                templateUrl: 'partials/reset.html',
                controller: 'authCtrl'
	    })


        .when('/init', {
                title: 'Init',
                templateUrl: 'partials/cadpac.html',
            
            })

         .when('/acomp', {
                title: 'Acomp',
                templateUrl: 'partials/acomp.html',
                 controller: 'acompCtrl'
            
            })

        .when('/cadpac', {
                title: 'Cadpac',
                templateUrl: 'partials/cadpac.html',
                 controller: 'cadpacCtrl'
            })

        .when('/conspac', {
                title: 'Conspac',
                templateUrl: 'partials/conspac.html',
                 controller: 'conspacCtrl'
            })

  
        .when('/cadcui', {
                title: 'Cadcui',
                templateUrl: 'partials/cadcui.html',
                 controller: 'cadcuiCtrl'
            })
         .when('/relat', {
                title: 'Relat',
                templateUrl: 'partials/relat.html',
                 controller: 'authCtrl'
            })


              .when('/conscui', {
                title: 'Conscui',
                templateUrl: 'partials/conscui.html',
                 controller: 'conscuiCtrl'
            })

               .when('/altsenha', {
                title: 'Altsenha',
                templateUrl: 'partials/altsenha.html',
                 controller: 'authCtrl'
            })


            .when('/signup', {
                title: 'Signup',
                templateUrl: 'partials/signup.html',
                controller: 'authCtrl'
            })
            .when('/dashboard', {
                title: 'Dashboard',
                templateUrl: 'partials/dashboard.html',
                controller: 'authCtrl'
            })
            .when('/', {
                title: 'Login',
                templateUrl: 'partials/login.html',
                controller: 'authCtrl',
                role: '0'
            })
            .otherwise({
                redirectTo: '/login'
            });
  }])
    .run(function ($rootScope, $location, Data) {
        $rootScope.$on("$routeChangeStart", function (event, next, current) {
            $rootScope.authenticated = false;
            Data.get('session').then(function (results) {
                if (results.uid) {
                    $rootScope.authenticated = true;
                    $rootScope.uid = results.uid;
                    $rootScope.name = results.name;
                    $rootScope.email = results.email;
                } else {
                    var nextUrl = next.$$route.originalPath;
                    if (nextUrl == '/signup' || nextUrl == '/login' || nextUrl == '/resetsenha') {

                    } else {
                        $location.path("/login");
                    }
                }
            });
        });
    });