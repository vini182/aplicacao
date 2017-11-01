app.controller('acompCtrl', function ($scope, $rootScope, $routeParams, $location, $http, Data) {
    //initially set those objects to null to avoid undefined error

//$scope.cadpac.uid = $scope.uid;
    $scope.acomp= 0;

    $scope.acomP = function (customer) {

        Data.post('acomP', {
            customer: customer
        }).then(function (results) {
            Data.toast(results);

            if (results.status == "success") {
                $location.path('dashboard');
            }
        });

  };

   $scope.excAcomp = function (customer) {

        Data.post('excAcomp', {
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

$scope.btnacomp = function(){
$location.path('acomp');
}

});