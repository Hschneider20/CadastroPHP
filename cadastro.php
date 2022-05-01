<?php
//Chamando a conexão com banco de dados e a função conectar
include("conexao.php");
$conn = conectar();

//Recuperando os dados dos formulário com método POST
$professor = $_POST['professor'];
$nome     = $_POST['nome'];
$turma    = $_POST['turma'];
$cgold    = $_POST['cgold'];


//Preparando os dados com pseudo-nome para cadastrar de forma segurando banco de dados
$cadastro = $conn->prepare("INSERT INTO aluno(NomeAlu, TurmaAlu, cgold, professor) VALUES(:NomeAlu, :TurmaAlu, :cgold, :professor)");

//Passando os valores das variáveis para os pseudo-nomes através do método bindValue
$cadastro->bindValue(":NomeAlu", $nome);
$cadastro->bindValue(":TurmaAlu", $turma);
$cadastro->bindValue(":cgold", $cgold);
$cadastro->bindValue(":professor", $professor);


//Verifcando se ja existe um e-mail cadastrado
$verificar = $conn->prepare("SELECT * FROM aluno WHERE NomeAlu=?");

$verificar->execute(array($nome));

if ($verificar->rowCount() == 0) :
    //Executando o cadastro com função execute
    $cadastro->execute();
    echo "Nome Cadastrado com sucesso";
    header('Location: http://localhost/CedaspyGold/tabelaaluno.php');
else :
    echo "Nome já cadastrado";
endif;
