<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>Document</title>
</head>
<body>
<?php

    //Chamando a conexão com banco de dados e a função conectar
    include ("conexao.php");
    $conn=conectar();

?>
<section class="pb-5"><!--Início da Seção Tabela de Registros-->
        <div class="container">
            <div class="col-12">
                <h1 class="text-center">REGISTROS</h1>
            </div>
        </div>   
        
        <p class="text-center">Aqui você pode visualizar os seus Alunos registrados!</p>

        <table class="table table-sm container col-6 text-center">
            <thead>
              <tr>
                <th scope="col">Nome:</th>
                <th scope="col">Turma:</th>
                <th scope="col">CGOLD:</th>
                <th scope="col">Professor:</th>
              </tr>
            </thead>
<?php
    //5 - Receber o numero da página através da URL
    $pagina_atual = filter_input(INPUT_GET,"page",FILTER_SANITIZE_NUMBER_INT);

    //6 - Verificando se o usuário não enviar a página pela URL, se isso acontecer receber pagina 1
    $pagina =(!empty($pagina_atual)) ? $pagina_atual : 1;

    //8 - Setar a quantidade de registros por pagina inicialmente e vamos criar uma variavel para colocar X registros por pagina
    $limite_resultado = 10;

    //9 - Calcular o inicio da visualização precisamos identificar a partir de qual registro o usuário quer começar a visualizar
    $inicio = ($limite_resultado * $pagina) - $limite_resultado;

    $tabela = $conn->prepare("SELECT * FROM aluno ORDER BY NomeAlu ASC LIMIT $inicio, $limite_resultado");
    $tabela->execute(); 
    while($lista = $tabela->fetch(PDO::FETCH_ASSOC)){//Abertura da estrutura de repetição
?>
            <tr>
                <!--Inserção das informações na lista-->
                <td><?php echo $lista['NomeAlu'];?></td>
                <td><?php echo $lista['TurmaAlu'];?></td>
                <td><?php echo $lista['cgold'];?></td>
                <td><?php echo $lista['professor'];?></td>
                <!--Fim Inserção das informações na lista-->
            </tr>
      
<?php
    }//Fechamento da estrutura de repetição

    //11- Agora queremos colocar a paginação na parte inferior da página de visualização de registros. Para isso precisamos contar a quantidade de registros no banco de dados

    //Contar a quantidade de registros no banco de dados
    $query_qnt_regitros  = "SELECT COUNT(id_aluno) AS num_result FROM aluno";//SQL ALIAS (AS) no MySQL; usamos a cláusula AS para dar um nome diferente (e mais amigável) a uma coluna ou tabela
    $result_qnt_registros = $conn->prepare($query_qnt_regitros);
    $result_qnt_registros->execute();

    $row_qnt_registros   = $result_qnt_registros->fetch(PDO::FETCH_ASSOC);//fetch(PDO::FETCH_ASSOC) Retorna um array associado

    //12- Agora que ja sabemos a quantidade de registros no banco de dados. Precisamos saber a quantidade de páginas. Para isso vamos utilizar a função CEIL

    //Quantidade de Páginas
    $qnt_pagina = ceil($row_qnt_registros['num_result'] / $limite_resultado);

    //16- Criando a variável para informar Maximo de links na página
    $maximo_link = 2;

    // 14 - Agora vamos implementar o link  para a primeira página.
    //mostrando o link: Primeira pagina
    echo " <a href='tabelaaluno.php?page=1'>Primeira</a>  ";

    //17 - Agora vamos implementar duas paginas anterior a página atual. para isso vamos precisar de um FOR

    //FOR para listar duas paginas anteriores a pagina atual.
    for($pagina_anterior = $pagina - $maximo_link; $pagina_anterior <= $pagina - 1; $pagina_anterior++){
            if($pagina_anterior >= 1) {
                echo " <a href='tabelaaluno.php?page=$pagina_anterior'>$pagina_anterior</a>  ";
            }
        }

    //13 - Agora que ja sabemos inicialmente a paginação primeiro vamos mostrar em que pagina o usuário está
    echo "$pagina";

    //18- Agora vamos implementar duas paginas Posterior a página atual. Para isso vamos precisar também de um FOR

    //FOR para listar duas páginas posterior a página atual.
    for($proxima_pagina = $pagina + 1; $proxima_pagina <= $pagina + $maximo_link; $proxima_pagina++){
        if($proxima_pagina <= $qnt_pagina){
            echo "<a href='tabelaaluno.php?page=$proxima_pagina'>$proxima_pagina</a>  ";
            }   
        }

        //15 - Agora vamos implementar o link para a ultima pagina
        //mostrando o link: Ultima pagina
        echo " <a href='tabelaaluno.php?page=$qnt_pagina'>Ultima</a> ";
?> 
        </table>
    </section><!--Fim da Seção Tabela de Registros-->
    <div class= "container input-group mt-4 " >
    <button type="button" class="btn btn-secondary" onclick="window.location.href = 'http://localhost/CedaspyGold'">Inicio</button>
    </div>
</body>
</html>