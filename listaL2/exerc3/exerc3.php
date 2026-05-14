<!DOCTYPE html>
<html lang="pt-BR">
<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title> Fundamentos de Banco de Dados com PHP </title>
 <link rel="stylesheet" href="formata-banco.css?v=10">
</head>

<body>
 <h1> PHP + MySQL - exercício 3 </h1>

 <form action="exerc3.php" method="post">
  <fieldset>
   <legend> Módulo de cadastro do produto </legend>

   <label> ID do produto: </label> <br>
   <input type="text" name="id" autofocus> <br> <br>

   <label> Preço unitário: </label> <br>
   <input type="number" name="preco" min="0" step="0.01"> <br> <br>

   <label> Estoque: </label> <br>
   <input type="number" name="estoque" min="0"> <br> 

   <button name="cadastrar"> Cadastrar produto </button>
  </fieldset>

  <fieldset>
   <legend> Módulo de alteração de preço do produto </legend>

   <label> Forneça o ID do pesquisado: </label> <br>
   <input type="text" name="id-pesquisado"> <br> <br>

   <label> Forneça o novo preço do produto: </label> <br>
   <input type="number" name="preco-alterado" min="0" step="0.01"> <br>

   <button name="alterar"> Modificar preço do produto </button>
  </fieldset>

  <fieldset>
   <legend> Módulo de exclusão de produto </legend>

   <label> Forneça o estoque mínimo para exclusão: </label> <br>
   <input type="number" name="estoque-minimo" min="0"> <br>

   <button name="excluir"> Excluir produtos abaixo do estoque mínimo </button>
  </fieldset>
 </form> 

 <?php

  require "criar-classe-banco-de-dados.inc.php";
  require "criar-classe-produtos.inc.php";  

  $objBanco = new BancoDeDados("localhost", "root", "", "CTDS", "produtos_estoque");

  $conexao = $objBanco->criarConexao();

  $objBanco->criarBanco($conexao);

  $objBanco->abrirBanco($conexao);

  $objBanco->definirCharset($conexao);

  $objBanco->criarTabela($conexao); 

  $objProduto = new Produtos(); 

  if(isset($_POST["cadastrar"])) {

   if(
      empty($_POST["id"]) ||
      empty($_POST["preco"]) ||
      empty($_POST["estoque"])
   ) {
      echo "<p class='erro'> Preencha todos os campos! </p>";
   } else {

      $objProduto->receberDadosForm($conexao);
      $objProduto->cadastrar($conexao, $objBanco->nomeDaTabela);

      echo "<p class='sucesso'> Produto cadastrado com sucesso na base de dados. </p>";
   }

}

  if(isset($_POST['alterar'])) {

   if(empty($_POST["id-pesquisado"])) {

      echo "<p class='erro'> Informe um número de ID </p>";

   } else if(empty($_POST["preco-alterado"])) {

      echo "<p class='erro'> Informe o novo preço do produto </p>";

   } else {

      $objProduto->alterarDados($conexao, $objBanco->nomeDaTabela);

   }

}

  if(isset($_POST['excluir'])) {

   if(empty($_POST["estoque-minimo"])) {

      echo "<p class='erro'> Caro usuário(a), informe o estoque mínimo para exclusão </p>";

   } else {

      $objProduto->excluirDados($conexao, $objBanco->nomeDaTabela);

   }

}
 
  $objBanco->desconectar($conexao); 

 ?>
 
</body>
</html>