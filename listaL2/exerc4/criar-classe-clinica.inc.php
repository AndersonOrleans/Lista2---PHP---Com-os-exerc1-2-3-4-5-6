<?php

class Clinica {
  public $crm;
  public $nomeMedico;

  public $idPaciente;
  public $nomePaciente;
  public $crmAtendimento;
  public $dataInternacao;


  function receberDadosMedico($conexao) {
    $this->nomeMedico = trim($conexao->escape_string($_POST["nome-medico"]));
    $this->crm        = trim($conexao->escape_string($_POST["crm"]));
  }


  function cadastrarMedico($conexao) {

    // 🔴 VALIDA CRM DUPLICADO
    $sqlCheck = "SELECT * FROM medicos WHERE crm = '$this->crm'";
    $resultado = $conexao->query($sqlCheck);

    if($resultado->num_rows > 0) {
      echo "<p class='erro'> CRM já cadastrado! </p>";
      return;
    }

    $sql = "INSERT INTO medicos (crm, nome)
            VALUES (
              '$this->crm',
              '$this->nomeMedico'
            )";

    $conexao->query($sql) or die($conexao->error);

    echo "<p class='sucesso'> Médico cadastrado com sucesso. </p>";
  }


  function receberDadosPaciente($conexao) {
    $this->idPaciente     = trim($conexao->escape_string($_POST["id-paciente"]));
    $this->nomePaciente   = trim($conexao->escape_string($_POST["nome-paciente"]));
    $this->crmAtendimento = trim($conexao->escape_string($_POST["crm-atendimento"]));
    $this->dataInternacao = trim($conexao->escape_string($_POST["data"]));
  }


  function cadastrarPaciente($conexao) {

    // 🔴 VALIDA SE CRM EXISTE
    $sqlCheck = "SELECT * FROM medicos WHERE crm = '$this->crmAtendimento'";
    $resultado = $conexao->query($sqlCheck);

    if($resultado->num_rows == 0) {
      echo "<p class='erro'> CRM do médico não existe! </p>";
      return;
    }

    $sql = "INSERT INTO pacientes (id, nome, crm_medico, data_internacao)
            VALUES (
              $this->idPaciente,
              '$this->nomePaciente',
              '$this->crmAtendimento',
              '$this->dataInternacao'
            )";

    $conexao->query($sql) or die($conexao->error);

    echo "<p class='sucesso'> Paciente cadastrado com sucesso. </p>";
  }


  function pesquisarMedico($conexao) {
    $nomePesquisado = trim($conexao->escape_string($_POST["nome-pesquisa-medico"]));

    // 🔴 MELHORADO COM LIKE
    $sql = "SELECT 
              medicos.nome,
              COUNT(pacientes.id) AS total_pacientes
            FROM medicos
            LEFT JOIN pacientes
            ON medicos.crm = pacientes.crm_medico
            WHERE medicos.nome LIKE '%$nomePesquisado%'
            GROUP BY medicos.crm, medicos.nome";

    $resultado = $conexao->query($sql) or die($conexao->error);

    if($resultado->num_rows == 0) {
      echo "<p class='erro'> Médico não encontrado. </p>";
    } else {
      $dados = $resultado->fetch_assoc();

      echo "<p class='sucesso'> O médico <span>{$dados['nome']}</span> atendeu <span>{$dados['total_pacientes']}</span> paciente(s). </p>";
    }
  }
}