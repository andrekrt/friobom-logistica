<?php

session_start();
require("../conexao.php");

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && $_SESSION['tipoUsuario'] ==99) {

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
    <title>Cadastro de Meta</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="apple-touch-icon" sizes="180x180" href="../assets/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/favicon/favicon-16x16.png">
    <link rel="manifest" href="../assets/favicon/site.webmanifest">
    <link rel="mask-icon" href="../assets/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>

<body>
    <div class="container-fluid corpo">
        <?php require('../menu-lateral.php') ?>
        <!-- Tela com os dados -->
        <div class="tela-principal">
            <div class="menu-superior">
                <div class="icone-menu-superior">
                    <img src="../assets/images/icones/icone-metas.png" alt="">
                </div>
                <div class="title">
                    <h2>Cadastrar Metas</h2>
                </div>
                <div class="menu-mobile">
                    <img src="../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                </div>
            </div>
            <!-- dados exclusivo da página-->
            <div class="menu-principal">
                <form action="add-metas.php" class="despesas" enctype="multipart/form-data" method="post" id="">
                    <div id="formulario">
                         
                        <div class="form-row">
                            <div class="form-grupo col-md-6 espaco">
                                <label for="tipoMeta">Tipo da Meta</label>
                                <select required class="form-control" name="tipoMeta" id="tipoMeta">
                                    <option value=""></option>
                                <?php 
                                    $tipos = $db->query("SELECT * FROM metas_tipo ORDER BY descricao_tipo"); 
                                    $tipos = $tipos->fetchAll();
                                    foreach($tipos as $tipo):
                                ?>
                                <option value="<?=$tipo['descricao_tipo']?>"><?=$tipo['descricao_tipo']?></option>
                                <?php
                                    endforeach;
                                ?>
                                </select>
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="mesAno">Mês/Ano</label>
                                <input type="month" class="form-control" name="mesAno" id="mesAno" required>
                            </div>
                        </div>
                        <div class="form-row" id="datas">
                        
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" name="entradas" class="btn btn-primary"> Cadastrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/menu.js"></script>
    <script>
        $(function(){
            $('#mesAno').change(function(){
                if($(this).val()){
                    var mes = $('#mesAno').val().split("-")[1];
                    var ano = $('#mesAno').val().split("-")[0]
                    var numDias = new Date(ano, mes, 0).getDate();

                    var diasSemana=[
                        "Domingo",
                        "Segunda",
                        "Terça",
                        "Quarta",
                        "Quinta",
                        "Sexta",
                        "Sábado"
                    ];
                    
                    var options = '';
                    for(let i=01; i<=numDias; i++){
                        var dataCompleta = i+"/"+mes+"/"+ano; 
                        var dataInversa=ano+"-"+mes+"-"+i;
                        var numDiaSemana = new Date(ano,mes-1,i).getDay();
                    
                        options += '<div class="form-group col-md-2 espaco"> <label for="data">'+diasSemana[numDiaSemana]+'</label> <input type="text" name="data[]" id="data" class="form-control" required value="'+dataCompleta+'" readonly> </div> <div class="form-group col-md-2 espaco"> <label for="meta">Meta '+dataCompleta+' </label> <input type="meta" name="meta[]" id="meta" class="form-control" required > </div> ';
                        var data = new Date(dataInversa);
                        console.log(new Intl.DateTimeFormat('pt-BR').format(data));
                        
                    }                   
        
                    $('#datas').empty();
                    $('#datas').append(options);
                    
                }
            });
        });
    </script>
</body>

</html>