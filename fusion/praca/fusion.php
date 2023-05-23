<?php

session_start();
require("../../conexao.php");

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario'])==false && ($_SESSION['tipoUsuario']==2 || $_SESSION['tipoUsuario']==99 || $_SESSION['tipoUsuario']==1)) {

   
} else {
    echo "<script>alert('Acesso não permitido');</script>";
    echo "<script>window.location.href='../../index.php'</script>";
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

    <!-- select02 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

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
                    <h2>Viagens Praça Pendente</h2>
                </div>
                <div class="menu-mobile">
                    <img src="../../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                </div>
            </div>
            <!-- dados exclusivo da página-->
            <div class="menu-principal">
                <div class="icon-exp">
                    <a href="fusion-praca-csv.php"><img src="../../assets/images/excel.jpg" alt=""></a>
                </div>
                <div class="table-responsive">
                    <table id='tableFusion' class='table table-striped table-bordered nowrap text-center' style="width: 100%;">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center text-nowrap" >Data Saída </th>
                                <th scope="col" class="text-center text-nowrap" >Finalizou Rota</th>
                                <th scope="col" class="text-center text-nowrap" >Chegada Empresa</th>
                                <th scope="col" class="text-center text-nowrap">Carregamento</th>
                                <th scope="col" class="text-center text-nowrap">Veículo</th>
                                <th scope="col" class="text-center text-nowrap">Ajudante</th>
                                <th scope="col" class="text-center text-nowrap" >Rota </th>
                                <th scope="col" class="text-center text-nowrap" >Nº Entregas</th>
                                <th scope="col" class="text-center text-nowrap">Entregas Realizadas</th>
                                <th scope="col" class="text-center text-nowrap">Nº Devoluções</th>
                                <th scope="col" class="text-center text-nowrap" >Entregas Líquidas </th>
                                <th scope="col" class="text-center text-nowrap">Erros Fusion</th>
                                <th scope="col" class="text-center text-nowrap" >Nº Devoluções sem Avisar </th>
                                <th scope="col" class="text-center text-nowrap">% Devolução</th>
                                <th scope="col" class="text-center text-nowrap" >% Entegas</th>
                                <th scope="col" class="text-center text-nowrap">% Prestaçaõ de Contas</th>
                                <th scope="col" class="text-center text-nowrap">% Dias em Rota</th>
                                <th scope="col" class="text-center text-nowrap">Prêmio Máximo</th>
                                <th scope="col" class="text-center text-nowrap">Prêmio Pago</th>
                                <th scope="col" class="text-center text-nowrap">% Prêmio</th>
                                <th scope="col" class="text-center text-nowrap">Usuário</th>
                                <th scope="col" class="text-center text-nowrap">Situação</th>
                                <th scope="col" class="text-center text-nowrap">Ações</th>
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
            $('#tableFusion').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url':'pesq_fusion_praca.php'
                },
                'columns': [
                    { data: 'data_saida'},
                    { data: 'data_finalizacao' },
                    { data: 'data_chegada' },
                    { data: 'carga'},
                    { data: 'placa_veiculo' },
                    { data: 'nome_auxiliar'},
                    { data: 'nome_rota' },
                    { data: 'num_entregas' },
                    { data: 'entregas_ok' },
                    { data: 'num_devolucao' },
                    { data: 'entregas_liq'},
                    { data: 'num_uso_incorreto'},
                    { data: 'devolucao_sem_aviso'},
                    { data: 'perc_devolucao' },
                    { data: 'perc_entregas' },
                    { data: 'perc_contas' },
                    { data: 'perc_rota' },
                    { data: 'premio_possivel'},
                    { data: 'premio_real' },
                    { data: 'perc_premio' },
                    { data: 'nome_usuario' },
                    { data: 'situacao'},
                    { data: 'acoes'},
                ],
                "language":{
                    "url":"//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Portuguese-Brasil.json"
                },
                "aoColumnDefs":[
                    {'bSortable':false, 'aTargets':[22]}
                ],
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
                    $('#saida').val(json.data_saida);
                    $('#termino').val(json.data_finalizacao);
                    $('#chegada').val(json.data_chegada);
                    $('#carga').val(json.carga);
                    $('#veiculo').val(json.veiculo);
                    $('#ajudante').val(json.ajudante);
                    $('#rota').val(json.rota);
                    $('#numEntregas').val(json.num_entregas);
                    $('#entregasFeita').val(json.entregas_ok);
                    $('#numDev').val(json.num_devolucao);
                    $('#erros').val(json.num_uso_incorreto);
                    $('#devSemAvisar').val(json.devolucao_sem_aviso);
                    $('#idfusion').val(json.idfusion_praca);
                    $('#situacao').val(json.situacao);
                    $('#prestaConta').val(json.perc_contas);
                    $('#prazo').val(json.perc_rota);
                }
            })
        });
    </script>

    <!-- modal atualiza -->
    <div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Fusion Praça</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="atualiza-fusion.php" method="post">
                        <input type="hidden" name="idfusion" id="idfusion" value="">
                        <div class="form-row">
                            <div class="form-group col-md-2 ">
                                <label for="saida">Data Saída</label>
                                <input type="date" class="form-control" required name="saida" id="saida">
                            </div>
                            <div class="form-group col-md-2 ">
                                <label for="carga">Carregamento</label>
                                <input type="text" class="form-control" required name="carga" id="carga">
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
                                <label for="ajudante">Ajudante</label>
                                <select name="ajudante" required id="ajudante" class="form-control">
                                    <option value=""></option>
                                    <?php
                                    $sqlMotoristas = $db->query("SELECT idauxiliares, nome_auxiliar FROM auxiliares_rota ORDER BY nome_auxiliar ASC");
                                    $motoristas=$sqlMotoristas->fetchAll(PDO::FETCH_ASSOC);
                                    foreach($motoristas as $motorista):
                                    ?>
                                    <option value="<?=$motorista['idauxiliares']?>"><?=$motorista['nome_auxiliar']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group col-md-2 ">
                                <label for="numEntregas"> Nº Entregas </label>
                                <input type="text" class="form-control"  name="numEntregas" id="numEntregas" >
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3 ">
                                <label for="termino">Data Término Rota</label>
                                <input type="datetime-local" class="form-control" required name="termino" id="termino">
                            </div>
                            <div class="form-group col-md-3 ">
                                <label for="chegada">Data Chegada na Empresa</label>
                                <input type="datetime-local" class="form-control" required name="chegada" id="chegada">
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
                            <div class="form-group col-md-3 ">
                                <label for="devSemAvisar">Nº Devoluções sem Avisa</label>
                                <input type="text" class="form-control"  required name="devSemAvisar" id="devSemAvisar">
                            </div>
                            <div class="form-group col-md-3 ">
                                <label for="prestaConta">Prestou Conta dentro do Prazo?</label>
                                <select name="prestaConta" id="prestaConta" required class="form-control">
                                    <option value=""></option>
                                    <option value="1">SIM</option>
                                    <option value="0">NÃO</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3 ">
                                <label for="prazo">Finalizou no Prazo?</label>
                                <select name="prazo" id="prazo" required class="form-control">
                                    <option value=""></option>
                                    <option value="1">SIM</option>
                                    <option value="0">NÃO</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3 ">
                                <label for="situacao">Situação</label>
                                <select name="situacao" id="situacao" required class="form-control">
                                    <option value=""></option>
                                    <option value="Finalizado">Finalizado</option>
                                    <option value="Pendente">Pendente</option>
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
   
    </body>
</html>