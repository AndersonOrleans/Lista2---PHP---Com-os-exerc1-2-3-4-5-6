<?php

class Livros {
  public $isbn;
  public $titulo;
  public $autor;
  public $preco;
  public $dataLancamento;
 
  function receberDadosForm($conexao) {
    $this->isbn           = trim($conexao->escape_string($_POST["isbn"]));
    $this->titulo         = trim($conexao->escape_string($_POST["titulo"]));
    $this->autor          = trim($conexao->escape_string($_POST["autor"]));
    $this->preco          = trim($conexao->escape_string($_POST["preco"]));
    $this->dataLancamento = trim($conexao->escape_string($_POST["data-lancamento"]));
  }

  function cadastrar($conexao, $nomeDaTabela) {
    $sql = "INSERT INTO $nomeDaTabela 
            (isbn, titulo, autor, preco, data_lancamento)
            VALUES (
              '$this->isbn',
              '$this->titulo',
              '$this->autor',
              $this->preco,
              '$this->dataLancamento'
            )";

    $conexao->query($sql) or die($conexao->error);
  }

  function alterarData($conexao, $nomeDaTabela) {
    $isbnPesquisado = trim($conexao->escape_string($_POST["isbn-pesquisado"]));
    $dataAlterada   = trim($conexao->escape_string($_POST["data-alterada"]));

    $sql = "UPDATE $nomeDaTabela 
            SET data_lancamento = '$dataAlterada' 
            WHERE isbn = '$isbnPesquisado'";

    $conexao->query($sql) or die($conexao->error);

    if($conexao->affected_rows == 0) {
      echo "<p class='erro'> ISBN não encontrado na base de dados. </p>";
    } else {
      echo "<p class='sucesso'> A data de lançamento do livro de ISBN <span>$isbnPesquisado</span> foi alterada com sucesso. </p>";
    }
  }

  function excluirLivrosAntigos($conexao, $nomeDaTabela) {
    $sql = "DELETE FROM $nomeDaTabela 
            WHERE data_lancamento < DATE_SUB(CURDATE(), INTERVAL 2 YEAR)";

    $conexao->query($sql) or die($conexao->error);

    $registrosExcluidos = $conexao->affected_rows;

    if($registrosExcluidos == 0) {
      echo "<p class='erro'> Nenhum livro lançado há mais de 2 anos foi encontrado. </p>";
    } else {
      echo "<p class='sucesso'> Foram excluídos <span>$registrosExcluidos</span> livro(s) lançado(s) há mais de 2 anos. </p>";
    }
  }

  function listarLivros($conexao, $nomeDaTabela) {
    $sql = "SELECT * FROM $nomeDaTabela ORDER BY titulo";

    $resultado = $conexao->query($sql) or die($conexao->error);

    if($resultado->num_rows == 0) {
      echo "<p class='erro'> Não há livros cadastrados. </p>";
    } else {
      echo "<table>";
      echo "<caption>Livros cadastrados</caption>";
      echo "<tr>
              <th>ISBN</th>
              <th>Título</th>
              <th>Autor</th>
              <th>Preço</th>
              <th>Data de lançamento</th>
            </tr>";

      while($livro = $resultado->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$livro['isbn']}</td>";
        echo "<td>{$livro['titulo']}</td>";
        echo "<td>{$livro['autor']}</td>";
        echo "<td>R$ {$livro['preco']}</td>";
        echo "<td>{$livro['data_lancamento']}</td>";
        echo "</tr>";
      }

      echo "</table>";
    }
  }
}