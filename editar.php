<?php

    //Limpando o Buffer de saida durante o redirecionamento.
    ob_start(); 

    //Chamando a conexão com banco de dados e a função conectar
    include ("conexao.php");
    $conn=conectar();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <title>tabela</title>

    <style>
        .cabecalho{
           background-color: black;
       }
       html {
           position: relative;
           min-height: 100%;
       }
       body {
           margin-bottom: 60px;
       }
       .footer {
           position: absolute;
           bottom: 0;
           width: 100%;
           height: 70px;
           background-color: #000000;  
       }      
   </style>

</head>
<body class="bg-light">

<?php
    //2 - Receber o ID do usuário
    $idAlu = $_SESSION['idalu'];
        // 3 - Verificando se o id é diferente de vazio
        if(empty($idAlu)){
            $_SESSION['msg'] = "<p style='color:#f00;'>Usuário não encontrado</p>";
            header("location: tabelaaluno.php");
            exit();
        }

    // 5 - Pesquisando no Banco de Dados pelo id do usuário
    $query_alu = "SELECT * FROM aluno WHERE id_aluno = $idAlu LIMIT 1";

    // 6 - Preparando a query (consulta)
    $result_alu = $conn->prepare($query_cliente);

    // 7 - Executando a query
    $result_alu->execute();
    
    // 8 - Verificando se a query encontrou usuarios no banco de dados. Caso contrario redirecionar para o tabela.php
    if (($result_alu) and ($result_alu->rowCount() !=0)){
        $row_alu = $result_alu->fetch(PDO::FETCH_ASSOC);
    }else{
        $_SESSION['msg']="<p style='color: #f00;'>Erro: Usuário não encontrado</p>";
        header("location: ../tabela.php");
        exit();
    }
?>

    <section><!--Inicio da Seção Edição-->
        <div class="container pt-5">
            <div class="row  pt-2">
                <div class="col-12 text-center">
                    <h1>Tabela de Edição</h1>
                </div>
            </div>
        </div>
        <!-- 12 - Recebendo os dados do formulário-->
<?php
//12.1 - Recebendo os dados do formulário com o método POST e armazenando em uma variável de vetor
$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

// 13 - Verificando se o usuário clicou no botão "Atualizar"
if (!empty($dados['EditAlu'])){
    $empty_input = false;

    //Realizando algumas validações

    //14.1 - Retirando espaços em branco no início e no final
    array_map('trim', $dados);

    // 15 - Verificando se não há erro. Caso verdadeiro implementar no Banco de dados
    if(!$empty_input){
        //15.1 - Realizando o UPDATE no banco de dados
        $query_up_usuario = "UPDATE aluno SET cgold=:cgold  WHERE id_aluno =:id";

        $edit_usuario = $conn->prepare($query_up_usuario);//Preparando a query

        // 16 - Passando os valores das variáveis para os pseudo-nomes através do método bindParam
        $edit_usuario->bindParam(':cgold', $dados['cgold'], PDO::PARAM_STR);
      

        //17 - Verificando se a execução da Quey foi com sucesso
        if($edit_usuario->execute()){
            $_SESSION['msg'] = "<p style='color: green;'>Usuário atualizado com sucesso!</p>";
            header("location: ../tabela.php");
        }else{
            echo "<p style='color: #f00;'>Erro: Usuário não atualizado com sucesso!</p>";
        }
    }   
}
?>      
            <form method="POST" class="container pb-5" action="" id="edit-cliente">
                <div class="row justify-content-center">

                    <div class="col-12 pb-2">CGOLD:<input type="number" class="form-control text-center" name="cgold" id="cgold" value="<?php
                    //13 - Verificando se veio dados do usuário pelo formulario. Se veio é pra manter os dados
                        if(isset($dados['cgold'])){
                        echo $dados['cgold'];
                        } 

                    //11.1 - Mostrando no campo Matrícula a matrícula que veio do Banco de Dados
                        elseif(isset($row_cliente['cgold'])){
                        echo $row_cliente['cgold'];
                        }
                    ?>"></div>


                    <div class="col-12 pb-2"><input type="submit" value="Atualizar" class="form-control btn btn-primary" name="EditAlu"></div>

                </div>
            </form>
    </section><!--Fim da Seção Edição-->
</body>
</html>