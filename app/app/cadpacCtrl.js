app.controller('cadpacCtrl', function ($scope, $rootScope, $routeParams, $location, $http, Data) {
    //initially set those objects to null to avoid undefined error

 $scope.cadpac = {};
//$scope.cadpac.uid = $scope.uid;
    $scope.cadpac= {nome:'', idade:'', cpf:'', uid:''};

    $scope.cadPac = function (customer) {
         $scope.cadpac['uid'] =  $scope.uid;

        Data.post('cadPac', {
            customer: customer
        }).then(function (results) {
            Data.toast(results);

            if (results.status == "success") {
                $location.path('dashboard');
            }
        });

  };
 $scope.btndash = function(){
$location.path('dashboard');
}  

});