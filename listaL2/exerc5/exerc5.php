<!DOCTYPE html>
<html lang="pt-BR">
<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title> Fundamentos de Banco de Dados com PHP </title>
 <link rel="stylesheet" href="formata-banco.css?v=10">
</head>

<body>

 <h1> PHP + MySQL - exercício 5 </h1>

 <form action="exerc5.php" method="post">

  <fieldset>
   <legend>Módulo de cadastro de livros</legend>

   <label>ISBN do livro:</label> <br>
   <input type="text" name="isbn" autofocus><br><br>

   <label>Título do livro:</label> <br>
   <input type="text" name="titulo"> <br> <br>

   <label>Autor do livro:</label> <br>
   <input type="text" name="autor"> <br> <br>

   <label>Preço de venda do livro:</label> <br>
   <input type="number" name="preco" min="0" step="0.01"> <br> <br>

   <label>Data de lançamento do livro:</label> <br>
   <input type="date" name="data-lancamento"> <br> <br>

   <button name="cadastrar"> Cadastrar livro </button>
  </fieldset>

  <fieldset>
   <legend>Módulo de alteração da data de lançamento</legend>

   <label>Forneça o ISBN do livro:</label> <br>
   <input type="text" name="isbn-pesquisado"> <br><br>

   <label>Forneça a nova data de lançamento:</label> <br>
   <input type="date" name="data-alterada"> <br><br>

   <button name="alterar"> Alterar data de lançamento </button>
  </fieldset>

  <fieldset>
   <legend>Módulo de exclusão para obras que tenham sido lançadas há mais de 2 anos</legend>

   <button name="excluir"> Excluir livros com mais de 2 anos </button>
  </fieldset>

  <fieldset>
   <legend>Módulo de listagem dos dados de todos os livros cadastrados no banco de dados</legend>

   <button name="listar"> Listar livros </button>
  </fieldset>
 
 </form> 

 <?php

  require "criar-classe-banco-de-dados.inc.php";
  require "criar-classe-livros.inc.php";  

  $objBanco = new BancoDeDados("localhost", "root", "", "CTDS", "livros");

  $conexao = $objBanco->criarConexao();

  $objBanco->criarBanco($conexao);
  $objBanco->abrirBanco($conexao);
  $objBanco->definirCharset($conexao);
  $objBanco->criarTabela($conexao); 

  $objLivro = new Livros(); 


  if(isset($_POST["cadastrar"])) {

   if(
    empty($_POST["isbn"]) ||
    empty($_POST["titulo"]) ||
    empty($_POST["autor"]) || 
    empty($_POST["preco"]) ||
    empty($_POST["data-lancamento"])
   ) {
    echo "<p class='erro'> Preencha todos os campos! </p>";
   } else {

    $objLivro->receberDadosForm($conexao);
    $objLivro->cadastrar($conexao, $objBanco->nomeDaTabela);

    echo "<p class='sucesso'> Livro cadastrado com sucesso na base de dados. </p>";
   }

  }


  if(isset($_POST['alterar'])) {

   if(empty($_POST["isbn-pesquisado"])) {

    echo "<p class='erro'> Informe o ISBN do livro! </p>";

   } else if(empty($_POST["data-alterada"])) {

    echo "<p class='erro'> Informe a nova data de lançamento! </p>";

   } else {

    $objLivro->alterarData($conexao, $objBanco->nomeDaTabela);

   }

  }


  if(isset($_POST['excluir'])) {

   $objLivro->excluirLivrosAntigos($conexao, $objBanco->nomeDaTabela);

  }


  if(isset($_POST['listar'])) {

   $objLivro->listarLivros($conexao, $objBanco->nomeDaTabela);

  }
 
  $objBanco->desconectar($conexao); 

 ?>
 
</body>
</html>