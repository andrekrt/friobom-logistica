<?php

session_start();
require("../conexao.php");

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

    <!-- select02 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

</head>
<body>
    <div class="container-fluid corpo">
        <?php require('../menu-lateral.php') ?>
        <!-- Tela com os dados -->
        <div class="tela-principal">
            <div class="menu-superior">
                <div class="icone-menu-superior">
                    <img src="../assets/images/icones/icone-fusion.png" alt="">
                </div>
                <div class="title">
                    <h2>Viagens Fusion Finalizadas</h2>
                </div>
                <div class="menu-mobile">
                    <img src="../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                </div>
            </div>
            <!-- dados exclusivo da página-->
            <div class="menu-principal">
                <div class="icon-exp">
                    <a href="fusion-csv.php"><img src="../assets/images/excel.jpg" alt=""></a>
                </div>
                <div class="table-responsive">
                    <table id='tableFusion' class='table table-striped table-bordered nowrap text-center' style="width: 100%;">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center text-nowrap" >Filial </th>
                                <th scope="col" class="text-center text-nowrap" >Data Saída </th>
                                <th scope="col" class="text-center text-nowrap" >Finalizou Rota</th>
                                <th scope="col" class="text-center text-nowrap" >Chegada Empresa</th>
                                <th scope="col" class="text-center text-nowrap">Carregamento</th>
                                <th scope="col" class="text-center text-nowrap">Veículo</th>
                                <th scope="col" class="text-center text-nowrap">Motorista</th>
                                <th scope="col" class="text-center text-nowrap" >Rota </th>
                                <th scope="col" class="text-center text-nowrap" >Nº Entregas</th>
                                <th scope="col" class="text-center text-nowrap">Entregas Realizadas</th>
                                <th scope="col" class="text-center text-nowrap">Erros Fusion</th>
                                <th scope="col" class="text-center text-nowrap">Nº Devoluções</th>
                                <th scope="col" class="text-center text-nowrap" >Entregas Líquidas </th>
                                <th scope="col" class="text-center text-nowrap" >% Uso Fusion</th>
                                <th scope="col" class="text-center text-nowrap">% Check-List</th>
                                <th scope="col" class="text-center text-nowrap">% Km/L </th>
                                <th scope="col" class="text-center text-nowrap">% Devolução</th>
                                <th scope="col" class="text-center text-nowrap">% Dias em Rota</th>
                                <th scope="col" class="text-center text-nowrap">% Velocidade Máxima</th>
                                <th scope="col" class="text-center text-nowrap">Prêmio Máximo</th>
                                <th scope="col" class="text-center text-nowrap">Prêmio Real</th>
                                <th scope="col" class="text-center text-nowrap">% Prêmio</th>
                                <th scope="col" class="text-center text-nowrap">Situção</th>
                                <th scope="col" class="text-center text-nowrap">Usuário</th>
                                <!-- <th scope="col" class="text-center text-nowrap">Ações</th> -->
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/menu.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.10.25/af-2.3.7/date-1.1.0/r-2.2.9/rg-1.1.3/sc-2.0.4/sp-1.3.0/datatables.min.js"></script>
    
    <script>
        $(document).ready(function(){
            $('#tableFusion').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url':'pesq_fusion_finalizadas.php'
                },
                'columns': [
                    { data: 'filial'},
                    { data: 'saida'},
                    { data: 'termino_rota' },
                    { data: 'chegada_empresa' },
                    { data: 'carregamento'},
                    { data: 'placa' },
                    { data: 'motorista'},
                    { data: 'rota' },
                    { data: 'num_entregas' },
                    { data: 'entregas_feitas' },
                    { data: 'erros_fusion'},
                    { data: 'num_dev' },
                    { data: 'entregas_liq'},
                    { data: 'uso_fusion' },
                    { data: 'checklist' },
                    { data: 'media_km' },
                    { data: 'devolucao' },
                    { data: 'dias_rota'},
                    { data: 'vel_max' },
                    { data: 'premio_possivel'},
                    { data: 'premio_real' },
                    { data: 'premio_alcancado' },
                    { data: 'situacao' },
                    { data: 'nome_usuario' },
                    // { data: 'acoes'},
                ],
                "language":{
                    "url":"//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Portuguese-Brasil.json"
                },
                "order":[[1,"desc"]]
            });
        });
    </script>

    
    </body>
</html>