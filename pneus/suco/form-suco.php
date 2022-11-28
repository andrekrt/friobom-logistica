<?php

session_start();
require("../../conexao.php");

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && $_SESSION['tipoUsuario'] != 3 && $_SESSION['tipoUsuario'] != 4) {

    $idUsuario = $_SESSION['idUsuario'];
    $nomeUsuario = $_SESSION['nomeUsuario'];
    $tipoUsuario = $_SESSION['tipoUsuario'];
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
    <title>Registrar Suco</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <link rel="apple-touch-icon" sizes="180x180" href="../../assets/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../../assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../../assets/favicon/favicon-16x16.png">
    <link rel="manifest" href="../../assets/favicon/site.webmanifest">
    <link rel="mask-icon" href="../../assets/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
</head>

<body>
    <div class="container-fluid corpo">
        <?php require('../../menu-lateral02.php') ?>
        <!-- Tela com os dados -->
        <div class="tela-principal">
            <div class="menu-superior">
                <div class="icone-menu-superior">
                    <img src="../../assets/images/icones/pneu.png" alt="">
                </div>
                <div class="title">
                    <h2>Registrar Suco</h2>
                </div>
                <div class="menu-mobile">
                    <img src="../../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                </div>
            </div>
            <!-- dados exclusivo da página-->
            <div class="menu-principal">
                <form action="add-suco.php" class="despesas" enctype="multipart/form-data" method="post" id="">
                    <div id="formulario">
                        <div class="form-row">
                            <div class="form-group col-md-3 espaco">
                                <label for="veiculo">Veículo</label>
                                <select required name="veiculo" id="veiculo" class="form-control">
                                    <option value=""></option>
                                    <?php
                                    $veiculos = $db->query("SELECT DISTINCT(veiculo) FROM pneus");
                                    $veiculos = $veiculos->fetchAll();
                                    foreach($veiculos as $veiculo):
                                    ?>
                                    <option value="<?=$veiculo['veiculo']?>"><?=$veiculo['veiculo']?></option>
                                    <?php
                                    endforeach;
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-md-2 espaco"> 
                                <label for="kmVeiculo">Km Veículo</label> 
                                <input type="text" name="kmVeiculo" id="kmVeiculo" class="form-control" required> 
                            </div>
                        </div>
                        <div id="pneus_veiculos">
                            
                        </div>
                                           
                    </div>
                    <div class="form-group">
                        <button type="submit" name="entradas" class="btn btn-primary"> Registrar Suco</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../../assets/js/menu.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#veiculo').select2();
        });
        $(function(){
            $('#veiculo').change(function(){
                if($(this).val()){
                    $.getJSON('pesq_pneus.php?search=',{veiculo: $(this).val(), ajax: 'true'}, function(j){
                        
                        var options = '';
                        for(var i = 0; i < j.length; i++){
                           
                            options += '<input type="hidden" value="' + j[i].idpneu + '" name="idpneu[]"> <input type="hidden" value="' + j[i].km + '" name="kmPneu[]">   <div class="form-row"> <div class="form-grupo col-md-1 espaco"> <label for="pneu">Nº Fogo</label> <input type="text" id="fogo" class="form-control" value="' + j[i].fogo + '" readonly name="fogo[]"> </div> <div class="form-group col-md-2 espaco"> <label for="carcaca">Avalição por Carcaça</label> <input type="text" required name="carcaca[]" id="carcaca" class="form-control"> </div> <div class="form-group col-md-1 espaco"> <label for="suco01">Suco 01</label> <input type="text" required name="suco01[]" class="form-control" id="suco01"> </div> <div class="form-group col-md-2 espaco"> <label for="suco02">Suco 02</label> <input type="text" required name="suco02[]" class="form-control" id="suco02"> </div> <div class="form-group col-md-2 espaco"> <label for="suco03">Suco 03</label> <input type="text" required name="suco03[]" class="form-control" id="suco03">  </div> <div class="form-group col-md-1 espaco"> <label for="suco04">Suco 04</label> <input type="text" required name="suco04[]" class="form-control" id="suco04"> </div> <div class="form-group col-md-2 espaco"> <label for="calibragem">Calibragem</label> <input type="text" name="calibragem[]" class="form-control" id="calibragem">  </div> </div> '
                        }
                        $('#pneus_veiculos').empty();
                        $('#pneus_veiculos').append(options);
                    });
                }
            });
        });
    </script>

</body>

</html>