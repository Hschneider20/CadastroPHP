<?php
//Iniciando a sessão
session_start();

//Chamando a conexão com o banco de dados e a função conectar
include("conexao.php");
$conn=conectar();

//Criando uma Query para verificar se o usuario e a senha estao cadastrados no banco de dados e chamando o "ID" para a página dinâmica
$idAlu=$_GET['id'];
$query = $conn->prepare("SELECT * FROM aluno WHERE id_aluno = :id");
$query->bindValue(":id",$idAlu);

//Executandp a consulta com o método execute
$query->execute();

//Armazenando em uma variável o retorno da consulta no banco de dados
$row=$query->rowCount();//Função para contar linhas
$dados=$query->fetch(PDO::FETCH_ASSOC);

if($row == 1){
    $_SESSION['idalu'] = $dados['id'];
    header('Location:editar.php');
    exit();
}
else{
    $_SESSION['nao_autenticado'] = true;
    header('Location: http://localhost/CedaspyGold/tabelaaluno.php');
    exit();
}
?>