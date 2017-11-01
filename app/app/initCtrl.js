app.controller('initCtrl', function ($scope, $rootScope, $routeParams, $location, $http, Data) {
    //initially set those objects to null to avoid undefined error

 //$scope.cadpac = {nome:'', idade:'', cpf:''};
    $scope.cadpac = {nome:'',idade:'',cpf:'')};
    $scope.cadPac = function (customer) {
        Data.post('cadPac', {
            customer: customer
        }).then(function (results) {
            Data.toast(results);

            if (results.status == "success") {
                $location.path('dashboard');
            }
        });
  
  };

});