app.controller('conscuiCtrl', function ($scope, $rootScope, $routeParams, $location, $http, Data) {
    //initially set those objects to null to avoid undefined error


    $scope.conscui = {cpf:'', nome:'', temperatura:'', pressao:'', medicam:'', recom:'', created:'', uid: ''};
    $scope.conscui2 = {nome:''};

    $scope.consCui = function (customer) {
           $scope.uidcad =  $scope.uid;

        Data.post('consCui', {
         customer: customer
        }).then(function (results) {
            Data.toast(results);
            $scope.conscui = results.consulta;

            if ($scope.conscui.uid == $scope.uidcad){
               $scope.uidcad == $scope.conscui.uid;
            } 
          
      
        });
  
  };

   $scope.excCui = function (customer) {

        Data.post('excCui', {
         customer: customer
        }).then(function (results) {
            Data.toast(results);
             $scope.conscui = {};
    
});

};
  

    $scope.btndash = function(){
$location.path('dashboard');
}

});