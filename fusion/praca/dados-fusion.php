<?php

session_start();
require("../../conexao.php");

$idModudulo = 16;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

   
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
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <link rel="apple-touch-icon" sizes="180x180" href="../../assets/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../../assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../../assets/favicon/favicon-16x16.png">
    <link rel="manifest" href="../../assets/favicon/site.webmanifest">
    <link rel="mask-icon" href="../../assets/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    <!-- arquivos para datatable -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/dt-1.10.25/af-2.3.7/date-1.1.0/r-2.2.9/rg-1.1.3/sc-2.0.4/sp-1.3.0/datatables.min.css"/>

</head>
<body>
    <div class="container-fluid corpo">
        <?php require('../../menu-lateral02.php') ?>
        <!-- Tela com os dados -->
        <div class="tela-principal">
            <div class="menu-superior">
                <div class="icone-menu-superior">
                    <img src="../../assets/images/icones/icone-fusion.png" alt="">
                </div>
                <div class="title">
                    <h2> Dados Fusion </h2>
                </div>
                <div class="menu-mobile">
                    <img src="../../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                </div>
            </div>
            <!-- dados exclusivo da página-->
            <div class="menu-principal">
                <div class="table-responsive">
                    <table id='tableDados' class='table table-striped table-bordered nowrap text-center' style="width: 100%;">
                        <thead>
                            <tr>

                                <th scope="col" class="text-center text-nowrap" >Mês/Ano Saída</th>
                                <th scope="col" class="text-center text-nowrap" >Nº Viagens</th>
                                <th scope="col" class="text-center text-nowrap" >Nº Entregas</th>
                                <th scope="col" class="text-center text-nowrap">Nº Devoluções</th>
                                <th scope="col" class="text-center text-nowrap">Total Premiação Possível</th>
                                <th scope="col" class="text-center text-nowrap">Total Pago</th>
                                <th scope="col" class="text-center text-nowrap" >% Alcançado </th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="../../assets/js/menu.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.10.25/af-2.3.7/date-1.1.0/r-2.2.9/rg-1.1.3/sc-2.0.4/sp-1.3.0/datatables.min.js"></script>
    
    <script>
        $(document).ready(function(){
            $('#tableDados').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url':'pesq_dados.php'
                },
                'columns': [
                    { data: 'saida'},
                    { data: 'qtd' },
                    { data: 'entregas' },
                    { data: 'devolucao'},
                    { data: 'premiacao' },
                    { data: 'pago'},
                    { data: 'percPremio' },
                ],
                "language":{
                    "url":"//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Portuguese-Brasil.json"
                },
                "order":[[0,"desc"]]
            });
        });

    </script>
    </body>
</html>