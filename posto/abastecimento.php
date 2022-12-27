<?php

session_start();
require("../conexao.php");

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($_SESSION['tipoUsuario'] == 1 || $_SESSION['tipoUsuario'] == 99)) {

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
                    <img src="../assets/images/icones/combustivel-saida.png" alt="">
                </div>
                <div class="title">
                    <h2>Abastecimentos</h2>
                </div>
                <div class="menu-mobile">
                    <img src="../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                </div>
            </div>
            <!-- dados exclusivo da página-->
            <div class="menu-principal">
                <div class="icon-exp">
                    <div class="area-opcoes-button">
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalEntrada" data-whatever="@mdo" name="idpeca">Novo Abastecimento</button>
                    </div>
                    <a href="abastecimento-xls.php" ><img src="../assets/images/excel.jpg" alt=""></a>
                </div>
                <div class="table-responsive">
                    <table id='tableAbast' class='table table-striped table-bordered nowrap text-center' style="width: 100%;">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center text-nowrap">ID</th>
                                <th scope="col" class="text-center text-nowrap">Data Abastecimento</th>
                                <th scope="col" class="text-center text-nowrap">Litros Abastecido</th>
                                <th scope="col" class="text-center text-nowrap">Valor Médio</th>
                                <th scope="col" class="text-center text-nowrap">Valor Total</th>
                                <th scope="col" class="text-center text-nowrap">Carregamento</th>
                                <th scope="col" class="text-center text-nowrap">Km</th>
                                <th scope="col" class="text-center text-nowrap">Placa</th>
                                <th scope="col" class="text-center text-nowrap">Rota</th>
                                <th scope="col" class="text-center text-nowrap">Motorista</th>
                                <th scope="col" class="text-center text-nowrap">Tipo de Abastecimento</th>
                                <th scope="col" class="text-center text-nowrap"> Usuário que Lançou </th>
                                <th scope="col" class="text-center text-nowrap"> Ações  </th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/jquery.js"></script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/menu.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.10.25/af-2.3.7/date-1.1.0/r-2.2.9/rg-1.1.3/sc-2.0.4/sp-1.3.0/datatables.min.js"></script>
    
    <script>
        $(document).ready(function(){
            $('#tableAbast').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url':'proc_abastecimento.php'
                },
                'columns': [
                    { data: 'idcombustivel_saida'},
                    { data: 'data_abastecimento'},
                    { data: 'litro_abastecimento'},
                    { data: 'valor_medio'},
                    { data: 'valor_total'},
                    { data: 'carregamento'},
                    { data: 'km'},
                    { data: 'placa_veiculo'},
                    { data: 'rota'},
                    { data: 'motorista'},
                    { data: 'tipo_abastecimento'},
                    { data: 'nome_usuario'},
                    { data: 'acoes'},
                ],
                "language":{
                    "url":"//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Portuguese-Brasil.json"
                },
                "aoColumnDefs":[
                    {'bSortable':false, 'aTargets':[12]}
                ],
                "order":[
                    0, 'desc'
                ]
            });
        });
    </script>

<!-- MODAL lançamento de abastecimento -->
<div class="modal fade" id="modalEntrada" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Abastecimento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="add-abastecimento.php" method="post">
                    <div class="form-row">
                        
                        <div class="form-group col-md-3 espaco ">
                            <label for="placa"> Placa Veículo</label>
                            <input type="text" id="placa" name="placa" required class="form-control">
                        </div>
                        <div class="form-group col-md-3 espaco ">
                            <label for="rota"> Rota</label>
                            <select required name="rota" id="rota" class="form-control">
                                <option value=""></option>
                                <?php $rotas = $db->query("SELECT * FROM rotas");
                                $rotas = $rotas->fetchAll();
                                foreach($rotas as $rota):
                                ?>
                                <option value="<?=$rota['nome_rota']?>"><?= $rota['nome_rota']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group col-md-4 espaco ">
                            <label for="motorista"> Motorista</label>
                            <select required name="motorista" id="motorista" class="form-control">
                                <option value=""></option>
                                <?php $motoristas = $db->query("SELECT * FROM motoristas");
                                $motoristas = $motoristas->fetchAll();
                                foreach($motoristas as $motorista):
                                ?>
                                <option value="<?=$motorista['nome_motorista']?>"><?= $motorista['nome_motorista']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-2 espaco ">
                            <label for="carregamento"> Carregamento</label>
                            <input type="text" required name="carregamento" class="form-control" id="carregamento">
                        </div>
                        <div class="form-group col-md-2 espaco ">
                            <label for="litro"> Litros</label>
                            <input type="text" required  name="litro" class="form-control" id="litro">
                        </div>
                        <div class="form-group col-md-2 espaco ">
                            <label for="km"> Km</label>
                            <input type="text" required  name="km" class="form-control" id="km">
                        </div>
                        <div class="form-group col-md-2 espaco ">
                            <label for="tipo"> Tipo de Abastecimento</label>
                            <select name="tipo" required id="tipo" class="form-control">
                                <option value=""></option>
                                <option value="Saída">Saída</option>
                                <option value="Retorno">Retorno</option>
                            </select>
                        </div>
                    </div>    
            </div>
            <div class="modal-footer">
                <button type="submit" name="analisar" class="btn btn-primary">Lançar</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- FIM MODAL lançamento de abastecimento-->

<script src="../assets/js/jquery.mask.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    jQuery(function($){
        $("#litro").mask('###0,00', {reverse: true});
    })

    $(document).ready(function(){
       
        $('#motorista').select2({
            width: '100%',
            dropdownParent:"#modalEntrada"
        });
        $('#rota').select2({
            width: '100%',
            dropdownParent:"#modalEntrada"
        });
    });
  
</script>
</body>
</html>