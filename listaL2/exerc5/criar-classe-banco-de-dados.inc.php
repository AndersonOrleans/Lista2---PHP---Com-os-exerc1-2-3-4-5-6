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

   $sql = "CREATE TABLE IF NOT EXISTS $this->nomeDaTabela (
            isbn VARCHAR(20) PRIMARY KEY,
            titulo VARCHAR(150) NOT NULL,
            autor VARCHAR(100) NOT NULL,
            preco DECIMAL(10,2) NOT NULL,
            data_lancamento DATE NOT NULL
           ) ENGINE=innoDB;";

   $conexao->query($sql) OR die($conexao->error);

   }

  function desconectar($conexao) {
   $conexao->close();
   }
   
  }