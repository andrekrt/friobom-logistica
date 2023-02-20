<?php

session_start();
require("../conexao-on.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario'])==false && ($_SESSION['tipoUsuario']==2 || $_SESSION['tipoUsuario']==99)){
    $idUsuario = $_SESSION[ 'idUsuario'];
    $tipoUsuario = $_SESSION['tipoUsuario'];

}else{
    echo "<script>alert('Acesso não permitido');</script>";
    echo "<script>window.location.href='../index.php'</script>";
}

?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Geolocalização</title>
        <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="../assets/css/style.css">
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
                        <img src="../assets/images/icones/ICONE-LOCALIZACAO.png" alt="">
                    </div>
                    <div class="title">
                        <h2>Geolocalização Supervisores</h2>
                    </div>
                    <div class="menu-mobile">
                        <img src="../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                    </div>
                </div>
                <!-- dados exclusivo da página-->
                <div class="menu-principal">
                    <!-- <div class="icon-exp">
                        <a href="rescisoes-xls.php"><img src="../assets/images/excel.jpg" alt=""></a>
                    </div> -->
                    <div class="table-responsive">
                        <table id='geo' class='table table-striped table-bordered nowrap text-center' style="width: 100%;">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center text-nowrap" > ID </th>
                                    <th scope="col" class="text-center text-nowrap" > Data  </th>
                                    <th scope="col" class="text-center text-nowrap" > Supervisor </th>
                                    <th scope="col" class="text-center text-nowrap">Código Cliente</th>
                                    <th scope="col" class="text-center text-nowrap"> RCA</th> 
                                    <th scope="col" class="text-center text-nowrap"> Localização</th>  
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>     

        
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.10.25/af-2.3.7/date-1.1.0/r-2.2.9/rg-1.1.3/sc-2.0.4/sp-1.3.0/datatables.min.js"></script>
        
        <!-- Moment.js: -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
 
        <!-- Brazilian locale file for moment.js-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/locale/pt-br.js"></script>
  
        <!-- Ultimate date sorting plug-in-->
        <script src="https://cdn.datatables.net/plug-ins/1.10.21/sorting/datetime-moment.js"></script>

        <script>
            $(document).ready(function(){
                moment.locale('pt-br');
                $.fn.dataTable.moment( 'L', 'pt-br' );
                $.fn.dataTable.moment( 'DD/MM/AAAA' );
                $('#geo').DataTable({
                    'processing':true,
                    'serverSide':true,
                    'serverMethod':'post',
                    'ajax':{
                        'url':'pesq_geo.php'
                    },
                    'columns':[
                        { data: 'id'},
                        { data: 'data_hora'},
                        { data: 'codigo_sup'},
                        { data: 'cod_cliente'},
                        { data: 'rca'},
                        { data: 'localizacao'},
                    ],
                    "language":{
                        "url":"//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Portuguese-Brasil.json"
                    },
                    "order":[
                        [0,"desc"]
                    ]
                });
            });

            
        </script>

        
    </body>

   
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/menu.js"></script>
    
</html>