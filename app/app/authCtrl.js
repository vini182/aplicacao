app.controller('authCtrl', function ($scope, $rootScope, $routeParams, $location, $http, Data) {
    //initially set those objects to null to avoid undefined error
    $scope.login = {};
    $scope.signup = {};
    $scope.doLogin = function (customer) {
        Data.post('login', {
            customer: customer
        }).then(function (results) {
            Data.toast(results);
            $scope.login.password = "";
            if (results.status == "success") {
                $location.path('dashboard');
            }
        });
    };

    $scope.signup = {email:'',password:'',name:'',phone:'',address:''};
    $scope.signUp = function (customer) {
        Data.post('signUp', {
            customer: customer
        }).then(function (results) {
            Data.toast(results);
            if (results.status == "success") {
                $location.path('dashboard');
            }
        });
    };
    
    $scope.altsenha = {password:'', password2:''};
    $scope.altSenha = function (customer) {

        Data.post('altSenha', {
            customer: customer

        }).then(function (results) {
            Data.toast(results);
               if (results.status == "success") {
            $location.path('dashboard');
        } else{
             $scope.altsenha = {password:'', password2:''};
        }

        });
    };

 $scope.resetsenha = {email:''};
    $scope.resetSenha = function (customer) {

        Data.post('resetSenha', {
            customer: customer

        }).then(function (results) {
            Data.toast(results);
               if (results.status == "success") {
            $location.path('login');
        } else{
             $scope.altsenha = {email:''};
        }

        });
    };


    $scope.relat = {cpf:''};
    $scope.relaT = function (customer) {
         $scope.relat2 = 0;
          $scope.uidcad =  $scope.uid;

        Data.post('relaT', {
         customer: customer
        }).then(function (results) {
            Data.toast(results);
            $scope.relat1 = results.consulta;
             
      
        });
  
  };

    $scope.relaT2 = function (customer) {
         $scope.relat1 = 0;
          $scope.uidcad =  $scope.uid;

        Data.post('relaT2', {
         customer: customer
        }).then(function (results) {
            Data.toast(results);
            $scope.relat2 = results.consulta;
             
      
        });
  
  };

   $scope.excCui2 = function (customer) {
        Data.post('excCui2', {
         customer: customer
        }).then(function (results) {
            Data.toast(results);
            if (results.status != "error"){
               $scope.relat1 = results.consulta;         
           }
        });
  
  };

  $scope.excCui3 = function (customer) {
        Data.post('excCui3', {
         customer: customer
        }).then(function (results) {

            Data.toast(results);
             if (results.status != "error")
               $scope.relat2 = results.consulta;         
           
        });
  
  };


     $scope.Dashboard = function () {
           if (results.status == "success") {

        $location.path('dashboard');
    }
    }

    $scope.logout = function () {
        Data.get('logout').then(function (results) {
            Data.toast(results);
            $location.path('login');
        });
    }

 $scope.btncadpac = function(){
$location.path('cadpac');

}

$scope.btnconspac = function(){
$location.path('conspac');
}

$scope.btncadcui = function(){
$location.path('cadcui');
}
$scope.btnacomp = function(){
$location.path('acomp');
}

$scope.btnconscui = function(){
$location.path('conscui');
}
$scope.btnrelat = function(){
$location.path('relat');
}
 $scope.btndash = function(){
$location.path('dashboard');
}  
 $scope.btnalt = function(){
$location.path('altsenha');
}  
});