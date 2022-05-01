<?php
//Criando uma função conectar() para utilizar em outros arquivos
function conectar(){

    //Conectando o Banco de Dados com PDO e Tratando Excessões com try/catch
    try{
        $conn = new PDO("mysql:host=localhost; dbname=cgold", "root", "");
    }catch(PDOException $e){
        echo $e->getMessage(); 
    }

    return $conn;//Retornando a variável de conexão.
}
?>




