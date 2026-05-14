<!DOCTYPE html>
<html lang="pt-BR">
<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title> Fundamentos de Banco de Dados com PHP </title>
 <link rel="stylesheet" href="formata-banco.css?v=2">
</head>

<body>
 <h1> PHP + MySQL - exercício 2 </h1>

 <form action="exerc2.php" method="post">
  <fieldset>
   <legend> Dados cadastrais do produto </legend>

   <label> ID do produto: </label> <br>
   <input type="text" name="id" autofocus> <br> <br>

   <label> Preço unitário: </label> <br>
   <input type="number" name="preco" min="0" step="0.01"> <br> <br>

   <label> Estoque: </label> <br>
   <input type="number" name="estoque" min="0"> <br> <br>

   <label> Classificação do produto: </label> <br>

   <label>
        <input type="radio" name="classific" value="Perecível"> Produto perecível
   </label><br>

   <label>
        <input type="radio" name="classific" value="Não-Perecível"> Produto não-perecível
    </label><br><br>

   <label> Descrição detalhada do produto: </label> <br>
   <textarea name="descricao" maxlength="500"></textarea> 

   <div>
    <label> Escolha uma das operações a seguir: </label>
    <select name="operacoes">
     <option value="1"> Cadastrar produto no banco de dados </option>
     <option value="2"> Tabular dados dos produtos perecíveis </option>
     <option value="3"> Mostrar descrição (menor estoque) </option>
     <option value="4"> calcular faturamento com venda de não-perecíveis </option>     
    </select>

    <button name="enviar-operacao"> Executar operação selecionada </button>
   </div>
  </fieldset>
 </form> 

 <?php

require "criar-classe-banco-de-dados.inc.php";
require "criar-classe-produtos.inc.php";  

$objBanco = new BancoDeDados("localhost", "root", "", "CTDS", "produtos");

$conexao = $objBanco->criarConexao();

$objBanco->criarBanco($conexao);
$objBanco->abrirBanco($conexao);
$objBanco->definirCharset($conexao);
$objBanco->criarTabela($conexao); 

$objProduto = new Produtos(); 


if (isset($_POST['enviar-operacao'])) {

    $operacao = $_POST["operacoes"];

    if ($operacao == "1") {

        if (
        empty($_POST["id"]) ||
        empty($_POST["preco"]) ||
        empty($_POST["estoque"]) ||
        empty($_POST["classific"]) ||
        empty($_POST["descricao"])
) {
    echo "<p class='erro'> Preencha todos os campos! </p>";
} else {
    $objProduto->receberDadosForm($conexao);
    $objProduto->cadastrar($conexao, $objBanco->nomeDaTabela);

    echo "<p class='sucesso'> Dados do produto cadastrados com sucesso na base de dados. </p>";
}
    }

    if ($operacao == "2") {
        $objProduto->tabularDados($conexao, $objBanco->nomeDaTabela);
    }

    if ($operacao == "3") {
        $objProduto->mostrarDescricao($conexao, $objBanco->nomeDaTabela);
    }

    if ($operacao == "4") {
        $objProduto->calcularFaturamento($conexao, $objBanco->nomeDaTabela);
    }
}

$objBanco->desconectar($conexao);

?>

</body>
</html>