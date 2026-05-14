<!DOCTYPE html>
<html lang="pt-BR">
<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title> Fundamentos de Banco de Dados com PHP </title>
 <link rel="stylesheet" href="formata-banco.css?v=2">
</head>

<body>
 <h1> PHP + MySQL - exercício 4 </h1>

 <form action="exerc4.php" method="post">
  <fieldset>
   <legend> Módulo de cadastro dos médicos </legend>

   <label> Nome: </label> <br>
   <input type="text" name="nome-medico" autofocus> <br> <br>

   <label> CRM: </label> <br>
   <input type="text" name="crm"> 
  </fieldset>

  <fieldset>
   <legend> Módulo de cadastro dos pacientes </legend>

   <label> ID do paciente: </label> <br>
   <input type="number" name="id-paciente" min="1"> <br> <br>

   <label> Nome: </label> <br>
   <input type="text" name="nome-paciente"> <br> <br>

   <label> CRM do médico responsável: </label> <br>
   <input type="text" name="crm-atendimento"> <br> <br>

   <label> Data da internação: </label> <br>
   <input type="date" name="data"> <br>
  </fieldset>

  <fieldset>
   <legend> Módulo de pesquisa por médico </legend>

   <label> Nome do médico pesquisado: </label> <br>
   <input type="text" name="nome-pesquisa-medico"> 
  </fieldset>

  <div>
   <label> Selecione uma operação: </label> <br>

   <label>
    <input type="radio" name="operacao" value="1"> Cadastro de médico
   </label>

   <label>
    <input type="radio" name="operacao" value="2"> Cadastro de paciente
   </label>

   <label>
    <input type="radio" name="operacao" value="3"> Pesquisar médico e contar pacientes atendidos por ele
   </label>

   <button name="executar-operacao"> Executar operação selecionada </button>
  </div>
 </form> 

 <?php
  require "criar-classe-banco-de-dados.inc.php";
  require "criar-classe-clinica.inc.php";

  $objBanco = new BancoDeDados("localhost", "root", "", "CTDS", "clinica");

  $conexao = $objBanco->criarConexao();

  $objBanco->criarBanco($conexao);
  $objBanco->abrirBanco($conexao);
  $objBanco->definirCharset($conexao);
  $objBanco->criarTabela($conexao); 

  $objClinica = new Clinica();

  if(isset($_POST["executar-operacao"])) {

   if(empty($_POST["operacao"])) {
    echo "<p class='erro'> Selecione uma operação! </p>";
   } else {

    $operacao = $_POST["operacao"];

    if($operacao == "1") {

     if(empty($_POST["nome-medico"]) || empty($_POST["crm"])) {
      echo "<p class='erro'> Preencha os dados do médico! </p>";
     } else {
      $objClinica->receberDadosMedico($conexao);
      $objClinica->cadastrarMedico($conexao);
     }

    }

    if($operacao == "2") {

     if(
      empty($_POST["id-paciente"]) ||
      empty($_POST["nome-paciente"]) ||
      empty($_POST["crm-atendimento"]) ||
      empty($_POST["data"])
     ) {
      echo "<p class='erro'> Preencha os dados do paciente! </p>";
     } else {
      $objClinica->receberDadosPaciente($conexao);
      $objClinica->cadastrarPaciente($conexao);
     }

    }

    if($operacao == "3") {

     if(empty($_POST["nome-pesquisa-medico"])) {
      echo "<p class='erro'> Informe o nome do médico pesquisado! </p>";
     } 
     
     else {
      $objClinica->pesquisarMedico($conexao);
     }

    }

   }

  }
 
  $objBanco->desconectar($conexao); 
 ?>
</body>
</html>