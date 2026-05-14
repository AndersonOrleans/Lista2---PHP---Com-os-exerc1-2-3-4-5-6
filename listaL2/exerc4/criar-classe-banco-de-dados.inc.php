<?php
 
 class BancoDeDados {
  public $nomeDoBanco;
  public $nomeDaTabela;
  public $servidor;
  public $usuario;
  public $senha;

  function __construct($servidorBanco, $usuarioBanco, $senhaBanco, $nomeBanco, $nomeTabela) {
   $this->servidor     = $servidorBanco;
   $this->usuario      = $usuarioBanco;
   $this->senha        = $senhaBanco;
   $this->nomeDoBanco  = $nomeBanco;
   $this->nomeDaTabela = $nomeTabela;
   }

  function criarConexao() {
   $conexao = new mysqli($this->servidor, $this->usuario, $this->senha) OR die($conexao->error);
   return $conexao;
   }

  function criarBanco($conexao) {
   $sql = "CREATE DATABASE IF NOT EXISTS $this->nomeDoBanco";
   $conexao->query($sql) or die($conexao->error);
   }

  function abrirBanco($conexao) {
   $conexao->select_db($this->nomeDoBanco);
   }

  function definirCharset($conexao) {
   $conexao->set_charset("utf8");
   }

  function criarTabela($conexao) {

   $sqlMedicos = "CREATE TABLE IF NOT EXISTS medicos (
                  crm VARCHAR(20) PRIMARY KEY,
                  nome VARCHAR(100) NOT NULL
                  ) ENGINE=innoDB;";

   $conexao->query($sqlMedicos) OR die($conexao->error);


   $sqlPacientes = "CREATE TABLE IF NOT EXISTS pacientes (
                    id INT PRIMARY KEY,
                    nome VARCHAR(100) NOT NULL,
                    crm_medico VARCHAR(20) NOT NULL,
                    data_internacao DATE NOT NULL,
                    FOREIGN KEY (crm_medico) REFERENCES medicos(crm)
                    ) ENGINE=innoDB;";

   $conexao->query($sqlPacientes) OR die($conexao->error);
   
   }

  function desconectar($conexao) {
   $conexao->close();
   }

  }