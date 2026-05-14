<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title> Fundamentos de Banco de Dados com PHP </title>
  <link rel="stylesheet" href="formata-banco.css">
</head>

<body>

  <h1> PHP + MySQL - exercício 1 </h1>

   <form action="exerc1.php" method="post">

    <fieldset>

      <legend> Dados cadastrais do aluno </legend>

      <label> Aluno: </label> <br>
      <input type="text" name="aluno" autofocus> <br> <br>

      <label> Matrícula: </label> <br>
      <input type="text" name="matric"> <br> <br>

      <label> Média final de PRWII: </label> <br>
      <input type="number" name="media" min="0" max="10" step="0.1"> <br> <br>

      <div>
        <button name="cadastrar"> Cadastrar aluno </button>
        <button name="tabular"> Mostrar dados dos alunos cadastrados </button>
        <button name="contar"> Mostrar número de alunos aprovados </button>
      </div>

    </fieldset>

  </form>

  <?php

    require "banco-de-dados.php";
    require "alunos.php";

    $objBanco = new BancoDeDados("localhost", "root", "", "CTDS", "alunos");

    $conexao = $objBanco->criarConexao();

    $objBanco->criarBanco($conexao);
    $objBanco->abrirBanco($conexao);
    $objBanco->definirCharset($conexao);
    $objBanco->criarTabela($conexao);

    $objAluno = new Alunos();

    if(isset($_POST["cadastrar"])) {

    $objAluno->receberDadosForm($conexao);

    if(empty($objAluno->matricula) || empty($objAluno->nome) || empty($objAluno->mediaFinal)) {
      echo "<p style='color:red;'> Preencha todos os campos! </p>";
    }

    else if($objAluno->matriculaExiste($conexao, $objBanco->nomeDaTabela)) {
      echo "<p style='color:red;'> Matrícula já cadastrada! </p>";
    }

    else {
      $objAluno->cadastrar($conexao, $objBanco->nomeDaTabela);
      echo "<p> Dados do aluno cadastrados com sucesso na base de dados. </p>";
    }

    }

    if(isset($_POST["tabular"])) {
      $objAluno->tabularDados($conexao, $objBanco->nomeDaTabela);
    }

    if(isset($_POST["contar"])) {
      $objAluno->contarAprovados($conexao, $objBanco->nomeDaTabela);
    }

    $objBanco->desconectar($conexao);

  ?>

</body>
</html>