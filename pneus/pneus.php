<?php

session_start();
require("../conexao.php");

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario'])==false && $_SESSION['tipoUsuario'] == 3 || $_SESSION['tipoUsuario'] == 4 || $_SESSION['tipoUsuario'] == 99) {

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
                    <img src="../assets/images/icones/pneu.png" alt="">
                </div>
                <div class="title">
                    <h2>Pneus</h2>
                </div>
                <div class="menu-mobile">
                    <img src="../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                </div>
            </div>
            <!-- dados exclusivo da página-->
            <div class="menu-principal">
                <div class="icon-exp">
                    <a href="pneus-xls.php"><img src="../assets/images/excel.jpg" alt=""></a>
                </div>
                <div class="table-responsive">
                    <table id='tablePneus' class='table table-striped table-bordered nowrap' style="width: 100%;">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center text-nowrap">Nº Fogo</th>
                                <th scope="col" class="text-center text-nowrap">Cadastrado</th>
                                <th scope="col" class="text-center text-nowrap">Medida</th>
                                <th scope="col" class="text-center text-nowrap">Calibragem Máxima</th>
                                <th scope="col" class="text-center text-nowrap">Marca</th>
                                <th scope="col" class="text-center text-nowrap">Modelo</th>
                                <th scope="col" class="text-center text-nowrap">Nº Série</th>
                                <th scope="col" class="text-center text-nowrap">Vida</th>
                                <th scope="col" class="text-center text-nowrap">Posição Início</th>
                                <th scope="col" class="text-center text-nowrap">Veículo</th>
                                <th scope="col" class="text-center text-nowrap">Km Rodado</th>
                                <th scope="col" class="text-center text-nowrap">Situação</th>
                                <th scope="col" class="text-center text-nowrap">Localização</th>
                                <th scope="col" class="text-center text-nowrap">Suco 01</th>
                                <th scope="col" class="text-center text-nowrap">Suco 02</th>
                                <th scope="col" class="text-center text-nowrap">Suco 03</th>
                                <th scope="col" class="text-center text-nowrap">Suco 04</th>
                                <th scope="col" class="text-center text-nowrap">Lançado por</th>
                                <th scope="col" class="text-center text-nowrap">Ações</th>
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
            $('#tablePneus').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url':'proc_pesq_pneu.php'
                },
                'columns': [
                    { data: 'num_fogo' },
                    { data: 'data_cadastro' },
                    { data: 'medida' },
                    { data: 'calibragem_maxima' },
                    { data: 'marca' },
                    { data: 'modelo' },
                    { data: 'num_serie' },
                    { data: 'vida' },
                    { data: 'posicao_inicio' },
                    { data: 'veiculo' },
                    { data: 'km_rodado'},
                    { data: 'situacao'},
                    { data: 'localizacao'},
                    { data: 'suco01'},
                    { data: 'suco02'},
                    { data: 'suco03'},
                    { data: 'suco04'},
                    { data: 'usuario'},
                    { data: 'acoes'},
                ],
                "language":{
                    "url":"//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Portuguese-Brasil.json"
                },
            });
        });

        //abrir modal
        $('#tablePneus').on('click', '.editbtn', function(event){
            var table = $('#tablePneus').DataTable();
            var trid = $(this).closest('tr').attr('id');
            var id = $(this).data('id');

            $('#modalEditar').modal('show');

            $.ajax({
                url:"get_single_pneu.php",
                data:{id:id},
                type:'post',
                success: function(data){
                    var json = JSON.parse(data);
                    $('#idpneu').val(json.idpneus);
                    $('#dataCadastro').val(json.data_cadastro);
                    $('#nFogo').val(json.num_fogo);
                    $('#medida').val(json.medida);
                    $('#calibMax').val(json.calibragem_maxima);
                    $('#marca').val(json.marca);
                    $('#modelo').val(json.modelo);
                    $('#nSerie').val(json.num_serie);
                    $('#vida').val(json.vida);
                    $('#posicao').val(json.posicao_inicio);
                    $('#veiculo').val(json.veiculo);
                    $('#kmVeiculo').val(json.km_inicial);
                    $('#kmRodado').val(json.km_rodado);
                    $('#situacao').val(json.situacao);
                    $('#localizacao').val(json.localizacao);
                    $('#suco01').val(json.suco01);
                    $('#suco02').val(json.suco02);
                    $('#suco03').val(json.suco03);
                    $('#suco04').val(json.suco04);
                }
            })
        });

        //abrir modal descartar
        $('#tablePneus').on('click', '.deleteBtn', function(event){
            var table = $('#tablePneus').DataTable();
            var trid = $(this).closest('tr').attr('id');
            var id = $(this).data('id');

            $('#modalDesctar').modal('show');

            $.ajax({
                url:"get_single_pneu02.php",
                data:{id:id},
                type:'post',
                success: function(data){
                    var json = JSON.parse(data);
                    $('#idpneus').val(json.idpneus);
                }
            })
        });
    </script>

<!-- modal visualisar e editar -->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Pneu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="atualiza-pneu.php" method="post">
                    <input type="hidden" name="id" id="id" value="">
                    <input type="hidden" name="trid" id="trid" value="">
                    <div class="form-row">
                        <input type="hidden" name="idpneu" id="idpneu">
                        <div class="form-group col-md-3">
                            <label for="dataCadastro">Data do Cadastro</label>
                            <input type="text" name="dataCadastro" id="dataCadastro" readonly class="form-control" value="">
                        </div>
                        <div class="form-group col-md-1">
                            <label for="nFogo">Nº Fogo</label>
                            <input type="text" name="nFogo" id="nFogo" class="form-control" value="">
                        </div>
                        <div class="form-group col-md-2 ">
                            <label for="medida">Medida</label>
                            <input type="text" name="medida" id="medida" class="form-control" required value="">
                        </div>
                        <div class="form-group col-md-2 ">
                            <label for="calibMax">Calibragem Máxima</label>
                            <input type="text" required name="calibMax" id="calibMax" class="form-control" value="">
                        </div>
                        <div class="form-group col-md-2  ">
                            <label for="marca">Marca</label>
                            <input type="text" required name="marca" id="marca" class="form-control" value="">
                        </div>
                        <div class="form-group col-md-2  ">
                            <label for="modelo">Modelo</label>
                            <input type="text" required name="modelo" id="modelo" class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-2  ">
                            <label for="nSerie">Nº Série</label>
                            <input type="text" required name="nSerie" id="nSerie" class="form-control" value="">
                        </div>
                        <div class="form-group col-md-1  ">
                            <label for="vida">Vida </label>
                            <input type="text" required name="vida" id="vida" class="form-control" value="">
                        </div>
                        <div class="form-group col-md-2 ">
                            <label for="posicao">Posição Início</label>
                            <input type="text" required name="posicao" class="form-control" id="posicao" value="">
                        </div>
                        <div class="form-group col-md-2 ">
                            <label for="veiculo">Veículo</label>
                            <select class="form-control" name="veiculo" required id="veiculo">
                                <?php
                                $sql = $db->query("SELECT placa_veiculo FROM veiculos ORDER BY placa_veiculo ASC");
                                $dados = $sql->fetchAll();
                                foreach ($dados as $veiculo):
                                ?>
                                <option value=<?=$veiculo['placa_veiculo']?>><?= $veiculo['placa_veiculo']?>  </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group col-md-1">
                            <label for="kmVeiculo">Km Veículo</label>
                            <input type="text" required name="kmVeiculo" class="form-control" id="kmVeiculo" value="">
                        </div>
                        <div class="form-group col-md-1">
                            <label for="kmRodado">Km Rodado</label>
                            <input type="text" required name="kmRodado" class="form-control" readonly id="kmRodado" value="">
                        </div>
                        <div class="form-group col-md-2 ">
                            <label for="situacao">Situação</label>
                            <input type="text" required name="situacao" class="form-control" id="situacao" value="">
                        </div>                                                
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-2 ">
                            <label for="localizacao">Localização</label>
                            <input type="text" required name="localizacao" class="form-control" id="localizacao" value="">
                        </div>
                        <div class="form-group col-md-1 ">
                            <label for="suco01">Suco 01</label>
                            <input type="text" required name="suco01" class="form-control" id="suco01" value="">
                        </div>
                        <div class="form-group col-md-1 ">
                            <label for="suco02">Suco 02</label>
                            <input type="text" required name="suco02" class="form-control" id="suco02" value="">
                        </div>
                        <div class="form-group col-md-1 ">
                            <label for="suco03">Suco 03</label>
                            <input type="text" required name="suco03" class="form-control" id="suco03" value="" >
                        </div>
                        <div class="form-group col-md-1 ">
                            <label for="suco04">Suco 04</label>
                            <input type="text" required name="suco04" class="form-control" id="suco04" value="">
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                    <div class="text-center">
                            <button type="submit" class="btn btn-primary">Atualizar</button>
                        </div>
                    <button type="button"  class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </form> 
            </div>
        </div>
    </div>
</div>

<!-- modal descartar -->
<div class="modal fade" id="modalDesctar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Desativar Pneu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="desativar-pneu.php" method="post">
                    <input type="hidden" name="id" id="id" value="">
                    <input type="hidden" name="trid" id="trid" value="">
                    <div class="form-row">
                        <input type="hidden" name="idpneu" id="idpneus">
                        <div class="form-group col-md-3">
                            <label for="kmFinal">Km final do Veículo</label>
                            <input type="text" name="kmFinal" id="kmFinal" required class="form-control" >
                        </div>
                        <div class="form-group col-md-3">
                            <label for="motivo">Motivo do Descarte</label>
                            <input type="text" name="motivo" id="motivo" required class="form-control" >
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                    <div class="text-center">
                            <button type="submit" class="btn btn-primary">Atualizar</button>
                        </div>
                    <button type="button"  class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </form> 
            </div>
        </div>
    </div>
</div>
</body>
</html>