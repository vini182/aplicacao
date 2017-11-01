<?php
$app->get('/session', function() {
    $db = new DbHandler();
    $session = $db->getSession();
    $response["uid"] = $session['uid'];
    $response["email"] = $session['email'];
    $response["name"] = $session['name'];
    echoResponse(200, $session);
});

$app->post('/login', function() use ($app) {
    require_once 'passwordHash.php';
    $r = json_decode($app->request->getBody());
    verifyRequiredParams(array('email', 'password'),$r->customer);
    $response = array();
    $db = new DbHandler();
    $password = $r->customer->password;
    $email = $r->customer->email;
	
   $user = $db->getOneRecord("select uid,name,password,email,created from usuarios where phone='$email' or email='$email'");
    if ($user != NULL) {
        if(passwordHash::check_password($user['password'],$password)){
        $response['status'] = "success";
        $response['message'] = 'Logado com sucesso';
        $response['name'] = $user['name'];
        $response['uid'] = $user['uid'];
        $response['email'] = $user['email'];
        $response['createdAt'] = $user['created'];
        if (!isset($_SESSION)) {
            session_start();
        }
        $_SESSION['uid'] = $user['uid'];
        $_SESSION['email'] = $email;
        $_SESSION['name'] = $user['name'];
        } else {
            $response['status'] = "error";
            $response['message'] = 'Erro no Login. Dados incorretos';
        }
    }else {
            $response['status'] = "error";
            $response['message'] = 'Usuário não registrado!';
        }
    echoResponse(200, $response);
});

$app->post('/signUp', function() use ($app) {
    $response = array();
    $r = json_decode($app->request->getBody());
    verifyRequiredParams(array('email', 'name', 'password'),$r->customer);
    require_once 'passwordHash.php';
    $db = new DbHandler();
    $phone = $r->customer->phone;
    $name = $r->customer->name;
    $email = $r->customer->email;
    $address = $r->customer->address;
    $password = $r->customer->password;
    $isUserExists = $db->getOneRecord("select 1 from usuarios where phone='$phone' or email='$email'");
    if(!$isUserExists){
        $r->customer->password = passwordHash::hash($password);
        $tabble_name = "usuarios";
        $column_names = array('phone', 'name', 'email', 'password', 'city', 'address');
        $result = $db->insertIntoTable($r->customer, $column_names, $tabble_name);
        if ($result != NULL) {
            $response["status"] = "success";
            $response["message"] = "Usuário cadastrado com sucesso!";
            $response["uid"] = $result;
            if (!isset($_SESSION)) {
                session_start();
            }
            $_SESSION['uid'] = $response["uid"];
            $_SESSION['phone'] = $phone;
            $_SESSION['name'] = $name;
            $_SESSION['email'] = $email;
            echoResponse(200, $response);
        } else {
            $response["status"] = "error";
            $response["message"] = "Falha ao cadastrar. Tente novamente";
            echoResponse(201, $response);
        }
    }else{
        $response["status"] = "error";
        $response["message"] = "Esse usuário já possui cadastro!";
        echoResponse(201, $response);
    }
});

$app->post('/altSenha', function() use ($app) {
    require_once 'passwordHash.php';
    $r = json_decode($app->request->getBody());

    $response = array();
    
    $db = new DbHandler();
    //$email = $r->customer->email;
    $session = $db->getSession();
    $email = $session['email'];
     
     $password = $r->customer->password;
     $password2 = $r->customer->password2;

    $r->customer->password2 = passwordHash::hash($password2);   
    $r->customer->password = passwordHash::hash($password);

    $user = $db->getOneRecord("select * from usuarios where email='$email'");

    if(passwordHash::check_password($user['password'],$password)){
    $password2 = $r->customer->password2;

    $alt = mysql_query("UPDATE test.usuarios SET password='$password2' where usuarios.email='$email'");
    if($alt){

    $response["status"] = "success";
    $response["message"] = "Senha alterada com sucesso!";
    echoResponse(200, $response);
  }
}
else {
$response["status"] = "error";
     $response["message"] = "Senha antiga incorreta!";
      echoResponse(201, $response);

}

});


$app->post('/resetSenha', function() use ($app) {
  require_once 'passwordHash.php';
   require_once("phpmailer/class.phpmailer.php");

    $r = json_decode($app->request->getBody());

    $response = array();
    
    $db = new DbHandler();

    $email = $r->customer->email;

    $user = $db->getOneRecord("select * from usuarios where email='$email'");
    if ($user){

        function geraSenha($tamanho = 8, $maiusculas = true, $numeros = true, $simbolos = false)
{
// Caracteres de cada tipo
$lmin = 'abcdefghijklmnopqrstuvwxyz';
$lmai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
$num = '1234567890';
$simb = '!@#$%*-';
// Variáveis internas
$retorno = '';
$caracteres = '';
// Agrupamos todos os caracteres que poderão ser utilizados
$caracteres .= $lmin;
if ($maiusculas) $caracteres .= $lmai;
if ($numeros) $caracteres .= $num;
if ($simbolos) $caracteres .= $simb;
// Calculamos o total de caracteres possíveis
$len = strlen($caracteres);
for ($n = 1; $n <= $tamanho; $n++) {
// Criamos um número aleatório de 1 até $len para pegar um dos caracteres
$rand = mt_rand(1, $len);
// Concatenamos um dos caracteres na variável $retorno
$retorno .= $caracteres[$rand-1];
}
return $retorno;
}
    // $password = rand(100000, 9999999); // gera senha numérica aleatória, com mínimo  6 digitos e máximo 7 dígitos.
    $password = geraSenha(); // gera senha aleatória através da função com letras e números
     $name = $user['name'];

    $mail = new PHPMailer();
    $mail->CharSet = 'UTF-8';
    $mail->IsSMTP(); // Define que a mensagem será SMTP
    $mail->SMTPDebug = false;       // Debugar: 1 = erros e mensagens, 2 = mensagens apenas
    $mail->SMTPAuth = true;     // Autenticação ativada
    $mail->SMTPSecure = 'tls';  // SSL REQUERIDO pelo GMail
    $mail->Host = 'smtp-mail.outlook.com'; // SMTP utilizado
    $mail->Port = 587; 
    $mail->Username = 'vini_willers@hotmail.com'; // Usuário do servidor SMTP
    $mail->Password = 'Cinidibuiador0503'; // Senha do servidor SMTP
    $mail->SetFrom('vini_willers@hotmail.com', 'Aplicação Gestão de Cuidados de Saúde');
    $mail->AddAddress($email, $name);
    $mail->IsHTML(true); // D
    $mail->Subject  = "Redefinição de Senha"; // Assunto da mensagem
    $mail->Body = "<p>Olá " .$name. ". </br> </br> Sua Senha foi redefinida para: <b>".$password."</b> </br></br> Obrigado! ";
 
  $enviado = $mail->Send();
if ($enviado) {

     $password2 = passwordHash::hash($password);   

    
   
    $alt = mysql_query("UPDATE test.usuarios SET password='$password2' where usuarios.email='$email'");
    
    


    $response["status"] = "success";
    $response["message"] = "Senha enviada para seu e-mail com sucesso!";
    echoResponse(200, $response);
  }

 else {
  $response["status"] = "error";
     $response["message"] = "Senha não alterada pois o e-mail não foi enviado!";
      echoResponse(201, $response);
     
}

}else {
$response["status"] = "error";
     $response["message"] = "E-mail não cadastrado!";
      echoResponse(201, $response);

}


});

$app->post('/enviaCui', function() use ($app) {
   require_once("phpmailer/class.phpmailer.php");
    $r = json_decode($app->request->getBody());
    $response = array();    
    $db = new DbHandler();
    $cpf = $r->customer->cpf;



$host = "localhost";
$db   = "test";
$user = "root";
$pass = "";
 $con = mysql_pconnect($host, $user, $pass) or trigger_error(mysql_error(),E_USER_ERROR); 
// seleciona a base de dados em que vamos trabalhar
    mysql_select_db($db, $con);

    $cons = sprintf("select a.email 
from test.acompanhamentos a
where a.cpf='$cpf'");

 $cons2 = sprintf("select p.nome 
from test.pacientes p
where p.cpf='$cpf'");

$nome = mysql_query($cons2, $con) or die(mysql_error());

$cons2= mysql_fetch_array($nome);
$cons2 = $cons2['nome'];

$dados = mysql_query($cons, $con) or die(mysql_error());

while ($linha = mysql_fetch_array($dados)){
// calcula quantos dados retornaram
$consulta[] = $linha;

}

      $total = mysql_num_rows($dados);  


    if($total >0){
foreach ($consulta as $email) {

      $email = $email['email'];

     // foreach($consulta as $email){
    $mail = new PHPMailer();
    $mail->CharSet = 'UTF-8';
    $mail->IsSMTP(); // Define que a mensagem será SMTP
    $mail->SMTPDebug = false;       // Debugar: 1 = erros e mensagens, 2 = mensagens apenas
    $mail->SMTPAuth = true;     // Autenticação ativada
    $mail->SMTPSecure = 'tls';  // SSL REQUERIDO pelo GMail
    $mail->Host = 'smtp-mail.outlook.com'; // SMTP utilizado
    $mail->Port = 587; 
    $mail->Username = 'vini_willers@hotmail.com'; // Usuário do servidor SMTP
    $mail->Password = 'Cinidibuiador0503'; // Senha do servidor SMTP
    $mail->SetFrom('vini_willers@hotmail.com', 'Aplicação Gestão de Cuidados de Saúde');
    $mail->AddAddress($email);
    $mail->IsHTML(true); // D
    $mail->Subject  = "Novo Cuidado Cadastrado"; // Assunto da mensagem
    $mail->Body = "<p>Olá.</br>Um novo cuidado foi cadastrado para a pessoa que você está acompanhando de nome: <b>".$cons2."</b> </br></br> Obrigado!";
 
  $enviado = $mail->Send();
}



if ($enviado) {
  $response['status'] = "sucess";
   $response['message'] = "E-mail enviado para quem está acompanhando";
    echoResponse(200, $response);
  }

 else {
    
     
}
     
}

else {



}

});





$app->post('/cadPac', function() use ($app) {

   $response = array();

    $r = json_decode($app->request->getBody());

    verifyRequiredParams(array('nome', 'idade', 'cpf'),$r->customer);

    $db = new DbHandler();
    $nome = $r->customer->nome;
    $idade = $r->customer->idade;
    $cpf = $r->customer->cpf;
    $uid = $r->customer->uid;
$idade=date('d/m/Y');

    $isUserExists = $db->getOneRecord("select 1 from pacientes where cpf='$cpf'");

if(!$isUserExists){

        $tabble_name = "pacientes";
        $column_names = array('nome', 'idade', 'cpf', 'uid');
        $result = $db->insertIntoTable($r->customer, $column_names, $tabble_name);
        if ($result != NULL) {
            $response["status"] = "success";
            $response["message"] = "Paciente cadastrado com sucesso!";
            $response["uid"] = $result;

            echoResponse(200, $response);
        } else {
            $response["status"] = "error";
            $response["message"] = "Falha ao cadastrar. Tente novamente";
            echoResponse(201, $response);
        }
    }else{
         $response["status"] = "error";
            $response["message"] = "Paciente já cadastrado";
            echoResponse(201, $response);
    }

});

    $app->post('/consCui', function() use ($app) {
     $response = array();
    $r = json_decode($app->request->getBody());
     $db = new DbHandler();
  $cpf = $r->customer->cpf;
  //  $cons = $db->getRecord("select * from cuidados where cpf='$cpf' order by created DESC");
    // $cons2 = $db->getRecord("select nome from pacientes where cpf='$cpf'");
	
	$cons = $db->getRecord("select c.id_cuid, c.temperatura, c.pressao, c.cpf, c.medicam, c.recom, c.created, c.uid, p.nome 
from cuidados c, pacientes p 
where c.cpf='$cpf' 
AND p.cpf='$cpf' 
order by created DESC limit 1"); 
    
	if($cons){
            $response["status"] = "success";
            $response["message"] = "Cuidado encontrado com sucesso!";
            $response["consulta"] = $cons;
           
                  
               echoResponse(200, $response);
        
} else{
         $response["status"] = "error";
            $response["message"] = "Cuidado não encontrado para o paciente selecionado";
            echoResponse(201, $response);
        

}
});

    $app->post('/acomP', function() use ($app) {

   $response = array();

    $r = json_decode($app->request->getBody());
    $db = new DbHandler();
    $cpf = $r->customer->cpf;
    $session = $db->getSession();
    $email = $session['email'];

    $r->customer->email = $email;

    $isUserExists = $db->getOneRecord("select 1 from pacientes where cpf='$cpf'");

      $isUserExists2 = $db->getOneRecord("select 1 from acompanhamentos where cpf='$cpf' and email='$email'");

 if ($isUserExists2){
   $response["status"] = "error";
            $response["message"] = "Acompanhamento já cadastrado para esse e-mail!";
            echoResponse(201, $response);
 } else{

if($isUserExists){

        $tabble_name = "acompanhamentos";
        $column_names = array('cpf', 'email');
       $result = $db->insertIntoTable($r->customer, $column_names, $tabble_name);

//  $result = mysql_query("INSERT INTO 'test.acompanhamentos' (cpf, email)
//VALUES ('$cpf', '$email'");

        if ($result != NULL) {
            $response["status"] = "success";
            $response["message"] = "Acompanhamento cadastrado com sucesso!";

            echoResponse(200, $response);
        } else {
            $response["status"] = "error";
            $response["message"] = "Falha ao cadastrar. Tente novamente";
            $response["resultado"] = $result;
            echoResponse(201, $response);
        }
    }else{
         $response["status"] = "error";
            $response["message"] = "Paciente não cadastrado";
            echoResponse(201, $response);
    }
  }

});

    $app->post('/consCui', function() use ($app) {
     $response = array();
    $r = json_decode($app->request->getBody());
     $db = new DbHandler();
  $cpf = $r->customer->cpf;
  //  $cons = $db->getRecord("select * from cuidados where cpf='$cpf' order by created DESC");
    // $cons2 = $db->getRecord("select nome from pacientes where cpf='$cpf'");
  
  $cons = $db->getRecord("select c.id_cuid, c.temperatura, c.pressao, c.cpf, c.medicam, c.recom, c.created, c.uid, p.nome 
from cuidados c, pacientes p 
where c.cpf='$cpf' 
AND p.cpf='$cpf' 
order by created DESC limit 1"); 
    
  if($cons){
            $response["status"] = "success";
            $response["message"] = "Cuidado encontrado com sucesso!";
            $response["consulta"] = $cons;
           
                  
               echoResponse(200, $response);
        
} else{
         $response["status"] = "error";
            $response["message"] = "Cuidado não encontrado para o paciente selecionado";
            echoResponse(201, $response);
        

}
});

$app->post('/excAcomp', function() use ($app) {

   $response = array();

    $r = json_decode($app->request->getBody());
    $db = new DbHandler();
    $cpf = $r->customer->cpf;
    $session = $db->getSession();
    $email = $session['email'];


   $host = "localhost";
   $db   = "test";
   $user = "root";
   $pass = "";


    $con = mysql_pconnect($host, $user, $pass) or trigger_error(mysql_error(),E_USER_ERROR); 
// seleciona a base de dados em que vamos trabalhar
    mysql_select_db($db, $con);

    $cons = sprintf("SELECT email FROM test.acompanhamentos where email='$email'");

    $existe = mysql_query($cons, $con) or die(mysql_error());

    $user = mysql_fetch_assoc($existe);

    if ($user){

    $del = sprintf("DELETE FROM test.acompanhamentos where cpf='$cpf' and email='$email'");

    $result = mysql_query($del, $con) or die(mysql_error());

        if ($result) {
            $response["status"] = "success";
            $response["message"] = "Acompanhamento excluído com sucesso!";
             $response["result"] = $result;

            echoResponse(200, $response);

          }

        } else {
            $response["status"] = "error";
            $response["message"] = "Acompanhamento não cadastrado!";
            echoResponse(201, $response);
        
  }

});

    $app->post('/consCui', function() use ($app) {
     $response = array();
    $r = json_decode($app->request->getBody());
     $db = new DbHandler();
  $cpf = $r->customer->cpf;
  //  $cons = $db->getRecord("select * from cuidados where cpf='$cpf' order by created DESC");
    // $cons2 = $db->getRecord("select nome from pacientes where cpf='$cpf'");
  
  $cons = $db->getRecord("select c.id_cuid, c.temperatura, c.pressao, c.cpf, c.medicam, c.recom, c.created, c.uid, p.nome 
from cuidados c, pacientes p 
where c.cpf='$cpf' 
AND p.cpf='$cpf' 
order by created DESC limit 1"); 
    
  if($cons){
            $response["status"] = "success";
            $response["message"] = "Cuidado encontrado com sucesso!";
            $response["consulta"] = $cons;
           
                  
               echoResponse(200, $response);
        
} else{
         $response["status"] = "error";
            $response["message"] = "Cuidado não encontrado para o paciente selecionado";
            echoResponse(201, $response);
        

}
});




      $app->post('/relaT', function() use ($app) {
     $response = array();
    $r = json_decode($app->request->getBody());
     $db = new DbHandler();
     $cpf = $r->customer->cpf;

     $host = "localhost";
$db   = "test";
$user = "root";
$pass = "";
    $con = mysql_pconnect($host, $user, $pass) or trigger_error(mysql_error(),E_USER_ERROR); 
// seleciona a base de dados em que vamos trabalhar
    mysql_select_db($db, $con);
// executa a query

    /*
"select c.id_cuid, c.temperatura, c.pressao, c.cpf, c.medicam, c.recom, c.created, p.nome 
from cuidados c, pacientes p 
where c.cpf='$cpf' 
AND p.cpf='$cpf' 
order by created DESC limit 1"
    */

  //  $cons = $db->getRecord("select * from cuidados where cpf='$cpf' order by created DESC");
    // $cons2 = $db->getRecord("select nome from pacientes where cpf='$cpf'");
    
    $cons = sprintf("select c.id_cuid, c.temperatura, c.pressao, c.cpf, c.medicam, c.recom, c.created, c.uid, p.nome 
from test.cuidados c, test.pacientes p 
where c.cpf='$cpf' 
AND p.cpf='$cpf' 
order by created");

 $usu = sprintf("select uid
from test.cuidados
where cpf='$cpf'");
    
     $usuario = mysql_query($usu, $con) or die(mysql_error());


    $dados = mysql_query($cons, $con) or die(mysql_error());

while ($linha = mysql_fetch_array($dados)){
// calcula quantos dados retornaram
$consulta[] = $linha;

}

while ($linha2 = mysql_fetch_object($usuario)){
// calcula quantos dados retornaram
$consulta2[] = $linha2;

}
$total = mysql_num_rows($dados);

  
    if($total > 0){
            $response["status"] = "success";
            $response["message"] = "Relatório gerado com sucesso!";
            $response["consulta"] = $consulta;
             $response["consulta2"] = $consulta2;
           
                  
               echoResponse(200, $response);
        
} else{
         $response["status"] = "error";
            $response["message"] = "Relatório não gerado para o paciente selecionado";
            echoResponse(201, $response);
        

}
});

 $app->post('/relaT2', function() use ($app) {
     $response = array();
    $r = json_decode($app->request->getBody());
     $db = new DbHandler();
     $session = $db->getSession();
     $uid = $session['uid'];

     $host = "localhost";
$db   = "test";
$user = "root";
$pass = "";
    $con = mysql_pconnect($host, $user, $pass) or trigger_error(mysql_error(),E_USER_ERROR); 
// seleciona a base de dados em que vamos trabalhar
    mysql_select_db($db, $con);
// executa a query

    /*
"select c.id_cuid, c.temperatura, c.pressao, c.cpf, c.medicam, c.recom, c.created, p.nome 
from cuidados c, pacientes p 
where c.cpf='$cpf' 
AND p.cpf='$cpf' 
order by created DESC limit 1"
    */

  //  $cons = $db->getRecord("select * from cuidados where cpf='$cpf' order by created DESC");
    // $cons2 = $db->getRecord("select nome from pacientes where cpf='$cpf'");
    
    $cons = sprintf("select c.id_cuid, c.temperatura, c.pressao, c.cpf, c.medicam, c.recom, c.created, c.uid, p.nome 
from test.cuidados c, test.pacientes p 
where c.cpf = p.cpf
AND c.uid='$uid' 
order by created");


    $dados = mysql_query($cons, $con) or die(mysql_error());

while ($linha = mysql_fetch_array($dados)){
// calcula quantos dados retornaram
$consulta[] = $linha;

}
$total = mysql_num_rows($dados);

  
    if($total > 0){
            $response["status"] = "success";
            $response["message"] = "Relatório gerado com sucesso!";
            $response["consulta"] = $consulta;
           //  $response["consulta2"] = $consulta2;
           
                  
               echoResponse(200, $response);
        
} else{
         $response["status"] = "error";
            $response["message"] = "Relatório não gerado para o paciente selecionado";
            echoResponse(201, $response);
        

}
});


      $app->post('/consPac2', function() use ($app) {
     $response = array();
    $r = json_decode($app->request->getBody());
     $db = new DbHandler();
     $session = $db->getSession();
        $uid = $session['uid'];

     $host = "localhost";
$db   = "test";
$user = "root";
$pass = "";
    $con = mysql_pconnect($host, $user, $pass) or trigger_error(mysql_error(),E_USER_ERROR); 
// seleciona a base de dados em que vamos trabalhar
    mysql_select_db($db, $con);
// executa a query

    $cons = sprintf("select * 
from test.pacientes 
where uid='$uid' 
order by created");

    $dados = mysql_query($cons, $con) or die(mysql_error());

while ($linha = mysql_fetch_assoc($dados)){
// calcula quantos dados retornaram
$consulta[] = $linha;
}
$total = mysql_num_rows($dados);

  
    if($total > 0){
            $response["status"] = "success";
            $response["message"] = "Relatório gerado com sucesso!";
            $response["consulta"] = $consulta;
           
                  
               echoResponse(200, $response);
        
} else{
         $response["status"] = "error";
            $response["message"] = "Relatório não gerado pois o usuário não possui paciente cadastrado!";
            echoResponse(201, $response);
        

}
});

      $app->post('/excPac2', function() use ($app) {
     $response = array();
    $r = json_decode($app->request->getBody());
    
    $pac = $r->customer->cpf;

  //order by created DESC limit 1

    //$exc = mysql_query("delete from test.cuidados where cpf='$cpf'");
  $exc = mysql_query("delete from test.pacientes where cpf='$pac'");
    if($exc){

$host = "localhost";
$db   = "test";
$user = "root";
$pass = "";
    $con = mysql_pconnect($host, $user, $pass) or trigger_error(mysql_error(),E_USER_ERROR); 
// seleciona a base de dados em que vamos trabalhar
    mysql_select_db($db, $con);
// executa a query
    $db = new DbHandler();
  $session = $db->getSession();
        $uid = $session['uid'];
    
    $consulta = mysql_query("select *
    FROM  test.pacientes 
where uid='$uid'  
order by created");

   // $dados = mysql_query($consulta, $con) or die(mysql_error());

    if ($consulta){

while ($linha = mysql_fetch_assoc($consulta)){
// calcula quantos dados retornaram
$consulta2[] = $linha;
}
$total = mysql_num_rows($consulta);

  
    if($consulta > 0){

            $response["status"] = "sucess";
            $response["message"] = "Paciente excluído com sucesso!";
             $response["consulta"] = $consulta2;

               echoResponse(200, $response);
           
      }
           }
            }else{

            $response["status"] = "error";
            $response["message"] = "Paciente não excluído por possuir cuidados!";
        
               echoResponse(201, $response);
             
}
           

       });



     $app->post('/consPac', function() use ($app) {
     $response = array();
    $r = json_decode($app->request->getBody());
     $db = new DbHandler();
    $cpf = $r->customer->cpf;
    //$cons = $db->getRecord("select * from pacientes where cpf='$cpf' DATE_FORMAT('created','dd/mm/yyyy hh:ii:ss')");
	$cons = $db->getRecord("select * from pacientes where cpf='$cpf'");

   $cons2 = $db->getRecord("select TIMESTAMPDIFF(YEAR, p.idade, CURDATE()) as idade from pacientes p where cpf='$cpf'");

  //$cons3 = $db->getRecord("SELECT DATE_FORMAT(created, '%d-%m-%Y %H:%m:%s') FROM pacientes where cpf='$cpf'");
      if($cons){
            $response["status"] = "success";
            $response["message"] = "Paciente encontrado com sucesso!";
            $response["consulta"] = $cons;
             $response["consulta2"] = $cons2;
              //  $response["consulta3"] = $cons3;
        
                  
               echoResponse(200, $response);
        
} else{
         $response["status"] = "error";
            $response["message"] = "Paciente não encontrado!";
            echoResponse(201, $response);
        

}
});

$app->post('/excPac', function() use ($app) {
     $response = array();
    $r = json_decode($app->request->getBody());
    
    $cpf = $r->customer->cpf;

    $exc = mysql_query("delete from test.pacientes where pacientes.cpf='$cpf'");

    if($exc){
            $response["status"] = "sucess";
            $response["message"] = "Paciente excluído com sucesso!";
      
               echoResponse(200, $response);
           }else{
            $response["status"] = "error";
            $response["message"] = "Paciente possui cuidado e não foi excluído!";
      
               echoResponse(201, $response);

           }

       });

$app->post('/excCui', function() use ($app) {
     $response = array();
    $r = json_decode($app->request->getBody());
    
    $cpf = $r->customer->cpf;
	//order by created DESC limit 1

    //$exc = mysql_query("delete from test.cuidados where cpf='$cpf'");
	$exc = mysql_query("delete from test.cuidados where cpf='$cpf' order by id_cuid DESC limit 1");
    if($exc){
            $response["status"] = "sucess";
            $response["message"] = "Cuidado excluído com sucesso!";
      
               echoResponse(200, $response);
           }else{
            $response["status"] = "error";
            $response["message"] = "Cuidado não excluído";
      
               echoResponse(201, $response);

           }

       });

$app->post('/excCui2', function() use ($app) {
     $response = array();
    $r = json_decode($app->request->getBody());
    
    $cuid = $r->customer->id_cuid;

  //order by created DESC limit 1

    //$exc = mysql_query("delete from test.cuidados where cpf='$cpf'");
  $exc = mysql_query("delete from test.cuidados where id_cuid='$cuid'");
    if($exc){
             $cpf = $r->customer->cpf;

     $host = "localhost";
$db   = "test";
$user = "root";
$pass = "";
    $con = mysql_pconnect($host, $user, $pass) or trigger_error(mysql_error(),E_USER_ERROR); 
// seleciona a base de dados em que vamos trabalhar
    mysql_select_db($db, $con);
// executa a query

    
    $cons = sprintf("select c.id_cuid, c.temperatura, c.pressao, c.cpf, c.medicam, c.recom, c.created, c.uid, p.nome 
from test.cuidados c, test.pacientes p 
where c.cpf='$cpf' 
AND p.cpf='$cpf' 
order by created");

    $dados = mysql_query($cons, $con) or die(mysql_error());

while ($linha = mysql_fetch_assoc($dados)){
// calcula quantos dados retornaram
$consulta[] = $linha;
}
$total = mysql_num_rows($dados);

  
    if($total > 0){

            $response["status"] = "sucess";
            $response["message"] = "Cuidado excluído com sucesso!";
             $response["consulta"] = $consulta;

               echoResponse(200, $response);
           } }else{
            $response["status"] = "error";
            $response["message"] = "Cuidado não excluído";
      
               echoResponse(201, $response);
}
           

       });


$app->post('/excCui3', function() use ($app) {
     $response = array();
    $r = json_decode($app->request->getBody());
        
    
        $cuid = $r->customer->id_cuid;

  //order by created DESC limit 1

    //$exc = mysql_query("delete from test.cuidados where cpf='$cpf'");
  $exc = mysql_query("delete from test.cuidados where id_cuid='$cuid'");

    if($exc){
      $db = new DbHandler();
        $session = $db->getSession();
        $uid = $session['uid'];
    
$host = "localhost";
$db   = "test";
$user = "root";
$pass = "";
    $con = mysql_pconnect($host, $user, $pass) or trigger_error(mysql_error(),E_USER_ERROR); 
// seleciona a base de dados em que vamos trabalhar
    mysql_select_db($db, $con);
// executa a query


     $dados = sprintf("select c.id_cuid, c.temperatura, c.pressao, c.cpf, c.medicam, c.recom, c.created, c.uid, p.nome 
from test.cuidados c, test.pacientes p 
where c.cpf = p.cpf
AND c.uid = '$uid' 
order by created");

$dados = mysql_query($dados, $con) or die(mysql_error());

while ($linha = mysql_fetch_assoc($dados)){
// calcula quantos dados retornaram
$consulta[] = $linha;
}


$total = mysql_num_rows($dados);
  
    if($total > 0){

            $response["status"] = "sucess";
            $response["message"] = "Cuidado excluído com sucesso!";
            $response["consulta"] = $consulta;

               echoResponse(200, $response);
           } 

         else{
            $response["status"] = "error";
            $response["message"] = "Relatório não gerado por Não possuir Cuidados";
      
               echoResponse(201, $response);
             }

           }else{
            $response["status"] = "error";
            $response["message"] = "Cuidado não excluído";
      
               echoResponse(201, $response);
            }
       


       });




$app->post('/cadCui', function() use ($app) {

   $response = array();
    $r = json_decode($app->request->getBody());

    $db = new DbHandler();
    $cpf = $r->customer->cpf;
    $temperatura = $r->customer->temperatura;
    $pressao = $r->customer->pressao;
    $medicam = $r->customer->medicam;
    $recom = $r->customer->recom;
    $uid = $r->customer->uid;
   // $cons2 = $db->getRecord("select id from pacientes where cpf='$cpf'");
   // $fk_pacientes = $r->customer->fk_pacientes;

   $cons = $db->getOneRecord("select * from pacientes where cpf='$cpf'");

if($cons){
        $tabble_name = "cuidados";
        $column_names = array('cpf', 'temperatura', 'pressao', 'medicam', 'recom', 'uid');
        $result = $db->insertIntoTable($r->customer, $column_names, $tabble_name);
        if ($result != NULL) {
            $response["status"] = "success";
            $response["message"] = "Cuidado cadastrado com sucesso!";
        
            echoResponse(200, $response);
        } else {
            $response["status"] = "error";
            $response["message"] = "Falha ao cadastrar. Tente novamente";
            echoResponse(201, $response);
        }
    }else {
        $response["status"] = "error";
            $response["message"] = "Paciente não cadastrado";
            echoResponse(201, $response);
    }

});



$app->get('/logout', function() {
    $db = new DbHandler();
    $session = $db->destroySession();
    $response["status"] = "info";
    $response["message"] = "Você saiu da aplicação";
    echoResponse(200, $response);
});
?>