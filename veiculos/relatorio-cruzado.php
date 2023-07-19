<?php

session_start();
require("../conexao-on.php");

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && $_SESSION['tipoUsuario'] != 4 ) {

   
   
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
                    <h2>Despesas por Veículos</h2>
                </div>
                <div class="menu-mobile">
                    <img src="../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                </div>
            </div>
            <!-- dados exclusivo da página-->
            <div class="menu-principal">
                <div class="icon-exp">
                    <a href="relatorio-xls.php"><img src="../assets/images/excel.jpg" alt=""></a>
                </div>
                <?php
                    if(!isset($_POST['submit']) || !isset($_POST['mesAno']) || empty($_POST['mesAno'])){
                ?>
                <form action="" class="despesas" enctype="multipart/form-data" method="post" id="">
                    <div id="formulario">
                        <div class="form-row">
                            <div class="form-group col-md-2 espaco">
                                <label for="mesAno">Mês/Ano</label>
                                <input type="month" class="form-control" name="mesAno" id="mesAno" required>
                            </div>
                        </div>
                        
                    </div>
                    <div class="form-group">
                        <button type="submit" name="submit" class="btn btn-primary"> Cadastrar</button>
                    </div>
                </form>
                <?php
                    }
                    else{
                        $mesAno = filter_input(INPUT_POST, 'mesAno');
                        $mesAno = explode("-",$mesAno);
                        $numDias = cal_days_in_month(CAL_GREGORIAN, $mesAno[1], $mesAno[0]);
                ?>
                <div class="table-responsive">
                    <table id='tableRelatorio' class='table table-striped table-bordered nowrap text-center' style="width: 100%;">
                        <thead>
                            <tr>
                                <?php 
                                echo '<th></th>';
                                for($i=1; $i<=$numDias; $i++){
                                    $data = sprintf('%04d-%02d-%02d', $mesAno[0],$mesAno[1], $i);
                                    
                                    echo' <th class="text-center text-nowrap">' . date("d/m/Y", strtotime($data)).'</th>';
                                    $datasMes[]=$data;
                                }
                                ?>
                               
                            </tr>
                           
                        </thead>
                        <?php
                            $veiculos = $db->query("SELECT placa_veiculo FROM veiculos WHERE ativo = 1 AND categoria = 'Mercedinha' OR categoria = 'Truck' OR categoria = 'Toco' ");
                            $arrayVeiculos = $veiculos->fetchAll();
                            foreach($arrayVeiculos as $veiculo){
                                
                                echo "<tr>";
                                echo ' <th class="text-center text-nowrap">' .$veiculo['placa_veiculo']. '</th>';
                                for($i=0;$i<count($datasMes);$i++){
                                    $viagem = $db->prepare("SELECT date(data_saida), date(data_chegada), placa_veiculo FROM viagem WHERE (:dataAtual >= DATE(data_saida) AND :dataAtual <=DATE(data_chegada)) AND placa_veiculo = :veiculo");
                                    $viagem->bindValue(':veiculo', $veiculo['placa_veiculo']);
                                    $viagem->bindValue(':dataAtual', $datasMes[$i]);
                                    $viagem->execute();
                                    $qtdViagem= $viagem->rowCount();
                            
                                    if($qtdViagem>0){
                                        echo "<th> SIM </th>";
                                    }else{
                                        echo "<th> NÃO </th>";
                                    }
                                   
                                }
                                echo "<tr>";
                            }

                            ?>
                    </table>
                </div>

            <?php } ?>
            </div>
        </div>
    </div>


    <script src="../assets/js/menu.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.10.25/af-2.3.7/date-1.1.0/r-2.2.9/rg-1.1.3/sc-2.0.4/sp-1.3.0/datatables.min.js"></script>
    
   
</body>
</html>