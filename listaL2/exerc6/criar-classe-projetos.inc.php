<?php

class Projetos {
  public $idProjeto;
  public $orcamento;
  public $dataInicio;
  public $horas;

  function receberDadosForm($conexao) {
    $this->idProjeto  = trim($conexao->escape_string($_POST["id_projeto"]));
    $this->orcamento  = trim($conexao->escape_string($_POST["orcamento"]));
    $this->dataInicio = trim($conexao->escape_string($_POST["data_inicio"]));
    $this->horas      = trim($conexao->escape_string($_POST["horas"]));
  }

  function cadastrar($conexao, $nomeDaTabela) {

    $sqlVerifica = "SELECT id_projeto
                    FROM $nomeDaTabela
                    WHERE id_projeto = '$this->idProjeto'";

    $resultado = $conexao->query($sqlVerifica) or die($conexao->error);

    if($resultado->num_rows > 0) {

      echo "<p class='erro'> Caro usuário(a), já existe um projeto com este número. </p>";

    } else {

      $sql = "INSERT INTO $nomeDaTabela
              (id_projeto, orcamento, data_inicio, horas)
              VALUES (
                '$this->idProjeto',
                $this->orcamento,
                '$this->dataInicio',
                $this->horas
              )";

      $conexao->query($sql) or die($conexao->error);

      echo "<p class='sucesso'> Projeto cadastrado com sucesso na base de dados. </p>";
    }
  }

  function listarProjetos($conexao, $nomeDaTabela) {
    $sql = "SELECT id_projeto, orcamento FROM $nomeDaTabela ORDER BY id_projeto";

    $resultado = $conexao->query($sql) or die($conexao->error);

    if($resultado->num_rows == 0) {
      echo "<p class='erro'> Não há projetos cadastrados. </p>";
    } else {
      echo "<table>";
      echo "<caption>Projetos cadastrados</caption>";
      echo "<tr>
              <th>ID do projeto</th>
              <th>Orçamento</th>
            </tr>";

      while($projeto = $resultado->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$projeto['id_projeto']}</td>";
        echo "<td>R$ {$projeto['orcamento']}</td>";
        echo "</tr>";
      }

      echo "</table>";
    }
  }

  function contarProjetosPosteriores($conexao, $nomeDaTabela) {
    $sql = "SELECT COUNT(*) AS total
            FROM $nomeDaTabela
            WHERE data_inicio > '2020-01-01'";

    $resultado = $conexao->query($sql) or die($conexao->error);

    $linha = $resultado->fetch_assoc();

    if($linha["total"] == 0) {
      echo "<p class='erro'> Nenhum projeto possui data de início posterior a 01/01/2020. </p>";
    } else {
      echo "<p class='sucesso'> Existem <span>{$linha['total']}</span> projeto(s) com data de início posterior a 01/01/2020. </p>";
    }
  }

  function excluirProjetos($conexao, $nomeDaTabela) {
    $sql = "DELETE FROM $nomeDaTabela
            WHERE horas < 100
            AND orcamento < 1000";

    $conexao->query($sql) or die($conexao->error);

    $registrosExcluidos = $conexao->affected_rows;

    if($registrosExcluidos == 0) {
      echo "<p class='erro'> Nenhum projeto com menos de 100 horas e orçamento inferior a R$1000,00 foi encontrado. </p>";
    } else {
      echo "<p class='sucesso'> Foram excluídos <span>$registrosExcluidos</span> projeto(s). </p>";
    }
  }
}

?>