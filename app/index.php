<!DOCTYPE html>
<html lang="en" ng-app="myApp">

  <head>
    <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
 	<link rel="shortcut icon" href="favicon1.ico" type="image/x-icon"/>
        <meta name="viewport" content="width=device-width,initial-scale=1">
          <title>Aplicacao para Gestao dos Cuidados Medicos</title>
          <!-- Bootstrap -->
          <link href="css/bootstrap.min.css" rel="stylesheet">
            <link href="css/custom.css" rel="stylesheet">
              <link href="css/toaster.css" rel="stylesheet">
                <style>
                  a {
                  color: orange;
                  }
                </style>
                <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
                <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
                <!--[if lt IE 9]><link href= "css/bootstrap-theme.css"rel= "stylesheet" >

<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
              </head>

  <body ng-cloak="">
    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="row">
          <div class="navbar-header col-md-8">
            <button type="button" class="navbar-toggle" toggle="collapse" target=".navbar-ex1-collapse">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar">
              </span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" rel="home" title="Aplicacao para Gestao dos Cuidados Medicos" href="/Inicial">Voltar para Hotsite</a>
          </div>
          
 <!--   <div class="navbar-header col-md-3">
            <a class="navbar-brand" rel="home" title="Cadastro de Pacientes" href="#cadpac">Cadastrar Pacientes</a>
          </div>
           <div class="navbar-header col-md-1">
            <a class="navbar-brand" rel="home" title="Cadastro de Cuidados" href="#cadcui">Cuidados</a>
          </div>
-->
        </div>
      </div>
    </div>
    <div >
      <div class="container" style="margin-top:20px;">

        <div data-ng-view="" id="ng-view" class="slide-animation"></div>

      </div>
    </body>
  <toaster-container toaster-options="{'time-out': 3000}"></toaster-container>
  <!-- Libs -->
  <script src="js/angular.min.js"></script>
  <script src="js/angular-route.min.js"></script>
  <script src="js/angular-animate.min.js" ></script>
  <script src="js/toaster.js"></script>
  <script src="app/app.js"></script>
  <script src="app/data.js"></script>
  <script src="app/directives.js"></script>
  <script src="app/authCtrl.js"></script>
  <script src="app/cadpacCtrl.js"></script>
  <script src="app/cadcuiCtrl.js"></script>
  <script src="app/conscuiCtrl.js"></script>
  <script src="app/conspacCtrl.js"></script>
    <script src="app/acompCtrl.js"></script>

  <script>
    function formatar(mascara, documento){
      var i = documento.value.length;
      var saida = mascara.substring(0,1);
      var texto = mascara.substring(i)
  
      if (texto.substring(0,1) != saida){
        documento.value += texto.substring(0,1);
      }  
    }

     function formatar2(mascara, documento){
  if (!isNaN(String.fromCharCode(window.event.keyCode))) return true; 
  else return false;
      var i = documento.value.length;

      var saida = mascara.substring(0,1);
      var texto = mascara.substring(i)
  
      if (texto.substring(0,1) != saida){


        documento.value += texto.substring(0,1);
        }

    }

  </script>
  
<script language="javascript" >
function numerovirgula( obj , e )
{
    var tecla = ( window.event ) ? e.keyCode : e.which;
    if ( tecla == 8 || tecla == 0 )
        return true;
    if ( tecla != 44 && tecla < 48 || tecla > 57 )
        return false;
}

</script>


</html>

