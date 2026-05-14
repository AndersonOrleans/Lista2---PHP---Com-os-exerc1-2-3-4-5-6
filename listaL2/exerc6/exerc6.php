<!DOCTYPE html>
<html lang="pt-BR">
<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title> Fundamentos de Banco de Dados com PHP </title>
 <link rel="stylesheet" href="formata-banco.css?v=10">
</head>

<body>

 <h1> PHP + MySQL - exercício 6 </h1>

 <form action="exerc6.php" method="post">

  <fieldset>
   <legend>Módulo de cadastro de projetos</legend>

   <label>ID do projeto:</label> <br>
   <input type="number" name="id_projeto" min="1" autofocus> <br><br>

   <label>Orçamento do projeto:</label> <br>
   <input type="number" name="orcamento" min="0" step="0.01"> <br><br>

   <label>Data de início:</label> <br>
   <input type="date" name="data_inicio"> <br><br>

   <label>Número de horas necessárias:</label> <br>
   <input type="number" name="horas" min="0"> <br><br>

   <button name="cadastrar"> Cadastrar projeto </button>
  </fieldset>

  <fieldset>
   <legend>Listar ID e orçamento dos projetos cadastrados</legend>
   <button name="listar"> Listar projetos </button>
  </fieldset>

  <fieldset>
   <legend>Mostrar quantidade de projetos com data posterior a 01/01/2020</legend>
   <button name="contar"> Mostrar quantidade </button>
  </fieldset>

  <fieldset>
   <legend>Excluir projetos com menos de 100 horas e orçamento inferior a R$1000,00</legend>
   <button name="excluir"> Excluir projetos </button>
  </fieldset>

 </form>

 <?php

  require "criar-classe-banco-de-dados.inc.php";
  require "criar-classe-projetos.inc.php";

  $objBanco = new BancoDeDados("localhost", "root", "", "CTDS", "projetos");

  $conexao = $objBanco->criarConexao();

  $objBanco->criarBanco($conexao);
  $objBanco->abrirBanco($conexao);
  $objBanco->definirCharset($conexao);
  $objBanco->criarTabela($conexao);

  $objProjeto = new Projetos();

  if(isset($_POST["cadastrar"])) {

   if(
    empty($_POST["id_projeto"]) ||
    empty($_POST["orcamento"]) ||
    empty($_POST["data_inicio"]) ||
    empty($_POST["horas"])
   ) {
    echo "<p class='erro'> Preencha todos os campos! </p>";
   } else {

    $objProjeto->receberDadosForm($conexao);
    $objProjeto->cadastrar($conexao, $objBanco->nomeDaTabela);
   }
  }

  if(isset($_POST["listar"])) {
   $objProjeto->listarProjetos($conexao, $objBanco->nomeDaTabela);
  }

  if(isset($_POST["contar"])) {
   $objProjeto->contarProjetosPosteriores($conexao, $objBanco->nomeDaTabela);
  }

  if(isset($_POST["excluir"])) {
   $objProjeto->excluirProjetos($conexao, $objBanco->nomeDaTabela);
  }

  $objBanco->desconectar($conexao);

 ?>

</body>
</html>