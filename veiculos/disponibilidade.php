<?php

session_start();
require("../conexao.php");

$idModudulo = 1;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

    $nomeUsuario = $_SESSION['nomeUsuario'];
    


    function contaVeic($condicao){
        include "../conexao.php";
        $filial = $_SESSION['filial'];
        $sqlCont = $db->prepare("SELECT COUNT(*) as qtd FROM veiculos WHERE situacao = :condicao AND ativo=1 AND filial = :filial" );
        $sqlCont->bindValue(':filial', $filial);
        $sqlCont->bindValue(':condicao', $condicao);
        if($sqlCont->execute()){
            $qtd = $sqlCont->fetch();
           return $qtd['qtd'];
        }else{
            print_r($sqlCont->errorInfo());
        }
    }

} else {
    echo "<script>alert('Acesso não permitido');</script>";
    echo "<script>window.location.href='../index.php'</script>";
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FRIOBOM - TRANSPORTE</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="apple-touch-icon" sizes="180x180" href="../assets/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/favicon/favicon-16x16.png">
    <link rel="manifest" href="../assets/favicon/site.webmanifest">
    <link rel="mask-icon" href="../assets/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff"> 

    <!-- arquivos para datatable -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/dt-1.10.25/af-2.3.7/date-1.1.0/r-2.2.9/rg-1.1.3/sc-2.0.4/sp-1.3.0/datatables.min.css"/>

</head>
<body>
    <div class="container-fluid corpo">
        <?php require('../menu-lateral.php') ?>
        <!-- Tela com os dados -->
        <div class="tela-principal">
            <div class="menu-superior">
                <div class="icone-menu-superior">
                    <img src="../assets/images/icones/veiculo.png" alt="">
                </div>
                <div class="title">
                    <h2>Disponibilidade da Frota</h2>
                </div>
            </div>
            <!-- dados exclusivo da página-->
            <div class="menu-principal">
                <div class="area-indice-val">
                    <div class="area-disp" id="disponivel">
                        <a href="disponivel.php?status=disponivel" class="nav-link" >
                            <div class="indice-ind-tittle">
                                <p>Disponível</p>
                            </div>
                            <div class="indice-qtde">
                                <img src="../assets/images/icones/disponivel.png" alt="">
                                <p class="qtde"> <?= contaVeic("Disponível") ?> </p>
                            </div>
                        </a>
                    </div>
                    <div class="area-disp" id="viagem" >
                        <a href="disponivel.php?status=viagem" class="nav-link" >
                            <div class="indice-ind-tittle">
                                <p>Em Viagem</p>
                            </div>
                            <div class="indice-qtde">
                                <img src="../assets/images/icones/viagem.png" alt="">
                                <p class="qtde"> <?= contaVeic("Em Viagem") ?> </p>
                            </div>
                        </a>
                    </div>
                    <div class="area-disp" id="interna">
                        <a href="disponivel.php?status=interna" class="nav-link" >
                            <div class="indice-ind-tittle">
                                <p>Manutenção Interna</p>
                            </div>
                            <div class="indice-qtde">
                                <img src="../assets/images/icones/manutencao.png" alt="">
                                <p class="qtde"> <?= contaVeic("Manutenção Interna")  ?> </p>
                            </div>
                        </a>
                    </div>
                    <div class="area-disp" id="externa">
                        <a href="disponivel.php?status=externa" class="nav-link" >
                            <div class="indice-ind-tittle">
                                <p>Manutenção Externa</p>
                            </div>
                            <div class="indice-qtde">
                                <img src="../assets/images/icones/manutencao.png" alt="">
                                <p class="qtde"> <?= contaVeic("Manutenção Externa")  ?> </p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="../assets/js/menu.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.10.25/af-2.3.7/date-1.1.0/r-2.2.9/rg-1.1.3/sc-2.0.4/sp-1.3.0/datatables.min.js"></script>
    
    <script>
        function carregarPagina(pagina){
            $(".tela-principal").load(pagina, function(){
                
            });
        }

        $('a.nav-link').click(function(event){
            event.preventDefault();

            var pagina = $(this).attr("href"); 

            carregarPagina(pagina);
            
        });
    </script>
    
</body>
</html>