<?php

session_start();
require("../conexao.php");

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario'])==false && ($_SESSION['tipoUsuario']==2 || $_SESSION['tipoUsuario']==99 )) {

   
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
                    <h2> Dados Fusion </h2>
                </div>
                <div class="menu-mobile">
                    <img src="../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
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

    <script src="../assets/js/menu.js"></script>

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

        //abrir modal edtiar
        $('#tableFusion').on('click', '.editbtn', function(event){
            var table = $('#tableFusion').DataTable();
            var trid = $(this).closest('tr').attr('id');
            var id = $(this).data('id');

            $('#modalEditar').modal('show');

            $.ajax({
                url:"get_fusion.php",
                data:{id:id},
                type:'post',
                success: function(data){
                    var json = JSON.parse(data);
                    $('#saida').val(json.saida);
                    $('#termino').val(json.termino_rota);
                    $('#chegada').val(json.chegada_empresa);
                    $('#carregamento').val(json.carregamento);
                    $('#veiculo').val(json.veiculo);
                    $('#motorista').val(json.motorista);
                    $('#rota').val(json.rota);
                    $('#numEntregas').val(json.num_entregas);
                    $('#entregasFeita').val(json.entegas_feitas);
                    $('#erros').val(json.erros_fusion);
                    $('#numDev').val(json.num_dev);
                    $('#situacao').val(json.situacao);
                    $('#id').val(json.idfusion);
                }
            })
        });
    </script>

    <!-- modal atualiza -->
    <div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Fusion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="atualiza-fusion.php" method="post">
                        <input type="hidden" name="id" id="id" value="">
                        <div class="form-row">
                            <div class="form-group col-md-2 ">
                                <label for="saida">Data Saída</label>
                                <input type="date" class="form-control" required name="saida" id="saida">
                            </div>
                            <div class="form-group col-md-2 ">
                                <label for="carregamento">Carregamento</label>
                                <input type="text" class="form-control" required name="carregamento" id="carregamento">
                            </div>
                            <div class="form-group col-md-2 ">
                                <label for="veiculo">Veículo</label>
                                <select name="veiculo" id="veiculo" class="form-control" required>
                                    <option value=""></option>
                                    <?php
                                    $sqlVeiculos = $db->query("SELECT cod_interno_veiculo, placa_veiculo FROM veiculos ORDER BY placa_veiculo ASC");
                                    $veiculos=$sqlVeiculos->fetchAll(PDO::FETCH_ASSOC);
                                    foreach($veiculos as $veiculo):
                                    ?>
                                    <option value="<?=$veiculo['cod_interno_veiculo']?>"><?=$veiculo['placa_veiculo']?></option>
                                    <?php  endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group col-md-2 ">
                                <label for="rota">Rota</label>
                                <select name="rota" required id="rota" class="form-control">
                                    <option value=""></option>
                                    <?php
                                    $sqlRotas = $db->query("SELECT cod_rota, nome_rota FROM rotas ORDER BY nome_rota ASC");
                                    $rotas=$sqlRotas->fetchAll(PDO::FETCH_ASSOC);
                                    foreach($rotas as $rota):
                                    ?>
                                    <option value="<?=$rota['cod_rota']?>"><?=$rota['nome_rota']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group col-md-2 ">
                                <label for="motorista">Motorista</label>
                                <select name="motorista" required id="motorista" class="form-control">
                                    <option value=""></option>
                                    <?php
                                    $sqlMotoristas = $db->query("SELECT cod_interno_motorista, nome_motorista FROM motoristas ORDER BY nome_motorista ASC");
                                    $motoristas=$sqlMotoristas->fetchAll(PDO::FETCH_ASSOC);
                                    foreach($motoristas as $motorista):
                                    ?>
                                    <option value="<?=$motorista['cod_interno_motorista']?>"><?=$motorista['nome_motorista']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group col-md-2 ">
                                <label for="numEntregas"> Nº Entregas </label>
                                <input type="text" class="form-control"  name="numEntregas" id="numEntregas" >
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-2 ">
                                <label for="termino">Data Término Rota</label>
                                <input type="date" class="form-control" required name="termino" id="termino">
                            </div>
                            <div class="form-group col-md-3 ">
                                <label for="chegada">Data Chegada na Empresa</label>
                                <input type="date" class="form-control" required name="chegada" id="chegada">
                            </div>
                            <div class="form-group col-md-2 ">
                                <label for="entregasFeita"> Nº Entregas Feitas </label>
                                <input type="text" class="form-control"  required name="entregasFeita" id="entregasFeita">
                            </div>
                            <div class="form-group col-md-2 ">
                                <label for="erros"> Nº Erros Fusion </label>
                                <input type="text" class="form-control" required  name="erros" id="erros">
                            </div>
                            <div class="form-group col-md-2 ">
                                <label for="numDev"> Nº Devoluções </label>
                                <input type="text" class="form-control"  required name="numDev" id="numDev">
                            </div>
                            
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-2 ">
                                <label for="devolucao">Houve Devolução?</label>
                                <select name="devolucao" id="devolucao" required class="form-control">
                                    <option value=""></option>
                                    <option value="0">SIM</option>
                                    <option value="1">NÃO</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3 ">
                                <label for="diasRota">Chegou dentro do Prazo?</label>
                                <select name="diasRota" id="diasRota" required class="form-control">
                                    <option value=""></option>
                                    <option value="1">SIM</option>
                                    <option value="0">NÃO</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4 ">
                                <label for="velMax">Ficou Dentro da Velocidade Permitida?</label>
                                <select name="velMax" id="velMax" required class="form-control">
                                    <option value=""></option>
                                    <option value="1">SIM</option>
                                    <option value="0">NÃO</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3 ">
                                <label for="situacao">Situação</label>
                                <select name="situacao" id="situacao" required class="form-control">
                                    <option value=""></option>
                                    <option value="Pendente">Pendente</option>
                                    <option value="Finalizada">Finalizada</option>
                                </select>
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

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
       /* $(document).ready(function(){
        
        $('#veiculo').select2({
            width: '100%',
            dropdownParent:"#modalEditar"
        });   
        $('#rota').select2({
            width: '100%',
            dropdownParent:"#modalEditar"
        });     
        $('#motorista').select2({
            width: '100%',
            dropdownParent:"#modalEditar"
        }); 
    });*/
    </script>
    </body>
</html>