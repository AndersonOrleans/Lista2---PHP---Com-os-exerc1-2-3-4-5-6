<?php
 class Produtos {
  public $id;
  public $preco;
  public $estoque;
 
  function receberDadosForm($conexao) {
   $this->id         = trim($conexao->escape_string($_POST["id"]));
   $this->preco      = trim($conexao->escape_string($_POST["preco"]));
   $this->estoque    = trim($conexao->escape_string($_POST["estoque"]));
   }

  function cadastrar($conexao, $nomeDaTabela) {

   $sql = "INSERT $nomeDaTabela VALUES(
            '$this->id',
            $this->preco,
            $this->estoque)";

   $conexao->query($sql) or die($conexao->error);

   }

 
 function alterarDados($conexao, $nomeDaTabela) {

  $idPesquisado = trim($conexao->escape_string($_POST["id-pesquisado"]));
  $novoPreco    = trim($conexao->escape_string($_POST["preco-alterado"]));

  $sql = "UPDATE $nomeDaTabela SET preco = $novoPreco WHERE id = '$idPesquisado'";

  $conexao->query($sql) or die($conexao->error);

  if($conexao->affected_rows == 0) {
   echo "<p> Caro usuário, o ID do produto fornecido não foi encontrado na base de dados. </p>";
   }

 else {
   echo "<p> O preço do produto de ID <span> $idPesquisado </span> foi alterado com sucesso no banco de dados. </p>";
   } 

  }

function excluirDados($conexao, $nomeDaTabela) {
  
  $estoqueMinimo = trim($conexao->escape_string($_POST["estoque-minimo"]));

  $sql = "DELETE FROM $nomeDaTabela WHERE estoque < $estoqueMinimo";

  $conexao->query($sql) or die($conexao->error);

  $registrosExcluidos = $conexao->affected_rows;

  if($registrosExcluidos == 0) {
   echo "<p> Caro usuário, não encontramos, na base de dados, nenhum produto com estoque abaixo de <span> $estoqueMinimo </span> unidades. </p>";
   }

  else {
   echo "<p> Caro usuário, com base no estoque mínimo fornecido, excluímos, da base de dados, um total de <span> $registrosExcluidos </span> produtos. </p>";
   } 

  }  

 }