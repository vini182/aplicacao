app.controller('cadcuiCtrl', function ($scope, $rootScope, $routeParams, $location, $http, Data) {
    //initially set those objects to null to avoid undefined error

 $scope.cadcui = {};

    $scope.cadcui = {cpf:'', temperatura:'', pressao:'', medicam:'', recom:''};
    

    $scope.cadCui = function (customer) {
        $scope.cadcui['uid'] =  $scope.uid;
        
        Data.post('cadCui', {
            customer: customer
        }).then(function (results) {
            Data.toast(results);
 $scope.conspac = results.consulta;
            if (results.status == "success") {
                $location.path('dashboard');

                 Data.post('enviaCui', {
            customer: customer
        }).then(function (results) {
        });

            }
        });
  
  };
   $scope.btndash = function(){
$location.path('dashboard');
}  

});