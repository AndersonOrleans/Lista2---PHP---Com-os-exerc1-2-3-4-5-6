<?php

 class Alunos {
  public $matricula;
  public $nome;
  public $mediaFinal;

  function receberDadosForm($conexao) {

   $this->matricula  = trim($conexao->escape_string($_POST["matric"]));
   $this->nome       = trim($conexao->escape_string($_POST["aluno"]));
   $this->mediaFinal = trim($conexao->escape_string($_POST["media"]));

   }

   function matriculaExiste($conexao, $nomeDaTabela) {

   $sql = "SELECT COUNT(*) FROM $nomeDaTabela WHERE matricula = '$this->matricula'";
   $resultado = $conexao->query($sql) or die($conexao->error);

   $vetorRegistro = $resultado->fetch_array();

   return $vetorRegistro[0] > 0;

  }

  function cadastrar($conexao, $nomeDaTabela) {

   $sql = "INSERT $nomeDaTabela VALUES(
            '$this->matricula',
            '$this->nome',
            $this->mediaFinal)";

   $conexao->query($sql) or die($conexao->error);

   }

  
  function tabularDados($conexao, $nomeDaTabela) {

   echo "<table>
          <caption> Dados dos alunos cadastrados no banco de dados </caption>
          <tr>
           <th> Matrícula </th>
           <th> Aluno </th>
           <th> Média de PRWII </th>
          </tr>";

   $sql = "SELECT * FROM $nomeDaTabela";

   $resultado = $conexao->query($sql) or die($conexao->error);

   while($vetorRegistro = $resultado->fetch_array()) {
    
    $matric = htmlentities($vetorRegistro[0], ENT_QUOTES, "UTF-8");
    $aluno  = htmlentities($vetorRegistro[1], ENT_QUOTES, "UTF-8");
    $media  = htmlentities($vetorRegistro[2], ENT_QUOTES, "UTF-8");

    echo "<tr>
           <td> $matric </td>
           <td> $aluno </td>
           <td> $media </td>
          </tr>";
    }

   echo "</table>";

   }


  function contarAprovados($conexao, $nomeDaTabela) {
   $sql = "SELECT COUNT(*) FROM $nomeDaTabela WHERE media >= 6";
   $resultado = $conexao->query($sql) or die($conexao->error);

   $vetorRegistro = $resultado->fetch_array();

   $numeroAlunosAprovados = htmlentities($vetorRegistro[0], ENT_QUOTES, "UTF-8");

   echo "<p> Número de alunos aprovados em PRWII e registrados no banco de dados = <span> $numeroAlunosAprovados </span> aluno(s) </p>";

   }
   
 }