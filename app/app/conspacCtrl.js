app.controller('conspacCtrl', function ($scope, $rootScope, $routeParams, $location, $http, Data) {
    //initially set those objects to null to avoid undefined error

    
    $scope.conspac = {cpf:'', nome:'', idade:'', created:'', uid:''};
    $scope.conspac2 = {idade:''};
  //  $scope.conspac3 = {created:''};

 $scope.conspac3 =0;

    $scope.consPac = function (customer) {
       $scope.conspac3 = 0;
         $scope.uidcad =  $scope.uid;

        Data.post('consPac', {
         customer: customer
        }).then(function (results) {
            Data.toast(results);
   
            $scope.conspac = results.consulta;
            $scope.conspac2.idade = results.consulta2.idade;

           

          //$scope.conspac3 = results.consulta3;
          
         //    $scope.conscui = {temperatura:results.temperatura, pressao:'results.pressao', medicam:'results.medicam', recom:'results.recom'};

      
        });
  
  };

      $scope.consPac2 = function (customer) {
              $scope.conspac = 0;
            $scope.conspac2.idade = 0;
      
        Data.post('consPac2', {
         customer: customer
        }).then(function (results) {
            Data.toast(results);
      
             $scope.conspac3 = results.consulta;
          //$scope.conspac3 = results.consulta3;
          
         //    $scope.conscui = {temperatura:results.temperatura, pressao:'results.pressao', medicam:'results.medicam', recom:'results.recom'};

      
        });
        };


          $scope.excPac2 = function (customer) {

        Data.post('excPac2', {
         customer: customer
        }).then(function (results) {
            Data.toast(results);
            if (results.status != "error") {
               $scope.conspac3 = results.consulta;       }  

            // }
        });
  
  };
  
  
 $scope.excPac = function (customer) {

        Data.post('excPac', {
         customer: customer
        }).then(function (results) {
            Data.toast(results);
             $scope.conspac = {};
              $scope.conspac2 = {};
});

};
 
    $scope.btndash = function(){
$location.path('dashboard');
}  

});