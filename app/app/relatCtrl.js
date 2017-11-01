app.controller('relatCtrl', function ($scope, $rootScope, $routeParams, $location, $http, Data) {
    //initially set those objects to null to avoid undefined error


    $scope.conspac = {uid:'', nome:'', idade:'', created:''};
  
    $scope.consPac = function (customer) {

        Data.post('consPac', {
         customer: customer
        }).then(function (results) {
            Data.toast(results);
   
            $scope.conspac = results.consulta;
           
         //    $scope.conscui = {temperatura:results.temperatura, pressao:'results.pressao', medicam:'results.medicam', recom:'results.recom'};
        });
  
  };
  
    $scope.btndash = function(){
$location.path('dashboard');
}  

$scope.btnrelat = function(){
$location.path('relat');
}


});
