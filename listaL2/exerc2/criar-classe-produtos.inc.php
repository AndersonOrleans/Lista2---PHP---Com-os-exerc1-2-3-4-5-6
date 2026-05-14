<?php

 class Produtos {
  public $id;
  public $preco;
  public $estoque;
  public $classificacao;
  public $descricao;

  function receberDadosForm($conexao) {
   $this->id         = trim($conexao->escape_string($_POST["id"]));
   $this->preco      = trim($conexao->escape_string($_POST["preco"]));
   $this->estoque    = trim($conexao->escape_string($_POST["estoque"]));
   $this->classificacao = trim($conexao->escape_string($_POST["classific"]));
   $this->descricao  = trim($conexao->escape_string($_POST["descricao"]));
   }

  function cadastrar($conexao, $nomeDaTabela) {
   $sql = "INSERT $nomeDaTabela VALUES(
            '$this->id',
            $this->preco,
            $this->estoque,
            '$this->classificacao',
            '$this->descricao')";

   $conexao->query($sql) or die($conexao->error);

   }

  function tabularDados($conexao, $nomeDaTabela) {
   echo "<table>
          <caption> Dados dos produtos perecíveis cadastrados no banco de dados </caption>
          <tr>
           <th> ID </th>
           <th> Preço </th>
           <th> Estoque </th>
           <th> Classificação </th>
           <th> Descrição </th>
          </tr>";

   $sql = "SELECT * FROM $nomeDaTabela WHERE classificacao = 'Perecível' ORDER BY estoque DESC";

   $resultado = $conexao->query($sql) or die($conexao->error);

   while($vetorRegistro = $resultado->fetch_array()) {

    $id        = htmlentities($vetorRegistro[0], ENT_QUOTES, "UTF-8");
    $preco     = htmlentities($vetorRegistro[1], ENT_QUOTES, "UTF-8");
    $estoque   = htmlentities($vetorRegistro[2], ENT_QUOTES, "UTF-8");
    $classific = htmlentities($vetorRegistro[3], ENT_QUOTES, "UTF-8");
    $descric   = htmlentities($vetorRegistro[4], ENT_QUOTES, "UTF-8");

    echo "<tr>
           <td> $id </td>
           <td> $preco </td>
           <td> $estoque </td>
           <td> $classific </td>
           <td> $descric </td>
          </tr>";
    
    }

   echo "</table>";

   }

 function mostrarDescricao($conexao, $nomeDaTabela) {

  $sql = "SELECT descricao FROM $nomeDaTabela WHERE estoque = (SELECT MIN(estoque) FROM $nomeDaTabela)";

  $resultado = $conexao->query($sql) or die($conexao->error);

  $vetorRegistro = $resultado->fetch_array();

  $descricao     = htmlentities($vetorRegistro[0], ENT_QUOTES, "UTF-8");

  echo "<p> Descrição do produto com a menor quantidade em estoque cadastrado: <span> $descricao </span> </p>";
  }

 function calcularFaturamento($conexao, $nomeDaTabela) {

  $sql = "SELECT SUM(estoque * preco) FROM $nomeDaTabela WHERE classificacao = 'Não-Perecível'";

  $resultado = $conexao->query($sql) or die($conexao->error);

  $vetorRegistro = $resultado->fetch_array();

  $faturamento     = htmlentities($vetorRegistro[0], ENT_QUOTES, "UTF-8");

  $faturamentoFormatado = number_format($faturamento, 2, ",", ".");

  echo "<p> Faturamento com a venda de todos os produtos não-perecíveis cadastrados na base de dados: <span> R$$faturamentoFormatado <span> </p>";

  }  

 }