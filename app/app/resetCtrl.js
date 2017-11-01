app.controller('resetCtrl', function ($scope, $rootScope, $routeParams, $location, $http, Data) {
   
 $scope.reset = {email:'', cpf:''};
    $scope.Reset = function (customer) {
        Data.post('Reset', {
            customer: customer
        }).then(function (results) {
            Data.toast(results);

            if (results.status == "success") {
                $location.path('login');
            }
        });
  
  };

});