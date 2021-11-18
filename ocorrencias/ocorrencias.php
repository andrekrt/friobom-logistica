<?php

session_start();
require("../conexao.php");

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && $_SESSION['tipoUsuario'] == 1 || $_SESSION['tipoUsuario'] == 99 || $_SESSION['tipoUsuario'] == 4) {

   
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
                    <img src="../assets/images/icones/ocorrencia.png" alt="">
                </div>
                <div class="title">
                    <h2> Ocorrências</h2>
                </div>
                <div class="menu-mobile">
                    <img src="../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                </div>
            </div>
            <!-- dados exclusivo da página-->
            <div class="menu-principal">
                <div class="icon-exp">
                    <a href="ocorrencias-xls.php"><img src="../assets/images/excel.jpg" alt=""></a>
                </div>
                <div class="table-responsive">
                    <table id='tableOcor' class='table table-striped table-bordered nowrap text-center' style="width: 100%;">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center">Código </th>
                                <th scope="col" class="text-center"> Motorista </th>
                                <th scope="col" class="text-center"> Data Ocorrência </th>
                                <th scope="col" class="text-center"> Tipo Ocorrência </th>
                                <th scope="col" class="text-center"> Veículo </th>
                                <th scope="col" class="text-center"> Carregamento </th>
                                <th scope="col" class="text-center">Provas Ocorrência</th>
                                <th scope="col" class="text-center">Anexo Ocorrência</th>
                                <th scope="col" class="text-center">Anexo Laudo</th>
                                <th scope="col" class="text-center">Valor Total</th>
                                <th scope="col" class="text-center">Resolvido</th>
                                <th scope="col" class="text-center">Lançado por</th>
                                <th scope="col" class="text-center"> Ações</th>
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
            $('#tableOcor').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url':'proc_pesq_ocor.php'
                },
                'columns': [
                    { data: 'idocorrencia' },
                    { data: 'nome_motorista'},
                    { data: 'data_ocorrencia' },
                    { data: 'tipo_ocorrencia' },
                    { data: 'placa' },
                    { data: 'num_carregamento' },
                    { data: 'ocorrencias' },
                    { data: 'advertencias' },
                    { data: 'laudos' },
                    { data: 'vl_total' },
                    { data: 'situacao' },
                    { data: 'usuario' },
                    { data: 'acoes'},
                ],
                "language":{
                    "url":"//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Portuguese-Brasil.json"
                },
                "aoColumnDefs":[
                    {'bSortable':false, 'aTargets':[6]},
                    {'bSortable':false, 'aTargets':[7]},
                    {'bSortable':false, 'aTargets':[8]},
                    {'bSortable':false, 'aTargets':[9]},
                    {'bSortable':false, 'aTargets':[10]},
                    {'bSortable':false, 'aTargets':[11]},
                    {'bSortable':false, 'aTargets':[12]}
                ],
            });
        });

        //abrir modal edtiar
        $('#tableOcor').on('click', '.editbtn', function(event){
            var table = $('#tableOcor').DataTable();
            var trid = $(this).closest('tr').attr('id');
            var id = $(this).data('id');

            $('#modalEditar').modal('show');

            $.ajax({
                url:"get_single_ocor.php",
                data:{id:id},
                type:'post',
                success: function(data){
                    var json = JSON.parse(data);
                    $('#idOcorrencia').val(json.idocorrencia);
                    $('#motorista').val(json.cod_interno_motorista);
                    $('#data').val(json.data_ocorrencia);
                    $('#tipo').val(json.tipo_ocorrencia);
                    $('#placa').val(json.placa);
                    $('#carregamento').val(json.num_carregamento);
                    $('#advertencia').val(json.advertencia);
                    $('#laudo').val(json.laudo);
                    $('#descricaoProblema').val(json.descricao_problema);
                    $('#descricaoCusto').val(json.descricao_custos);
                    $('#vlTotal').val(json.vl_total_custos);
                    $('#situacao').val(json.situacao);
                    $('#ocorrenciaPadrao').val(json.img_ocorrencia);
                    $('#advertenciaPadrao').val(json.img_advertencia);
                    $('#laudoPadrao').val(json.img_laudo);
                }
            })
        });
    </script>

<!-- modal visualisar e editar -->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ocorrência</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="atualiza.php" method="post"  enctype="multipart/form-data" >
                    <input type="hidden" name="id" id="id" value="">
                    <input type="hidden" name="trid" id="trid" value="">
                    <div class="form-row">
                        <div class="form-group col-md-1">
                            <label for="idOcorrencia" >ID</label>
                            <input type="text" readonly name="idOcorrencia" class="form-control" id="idOcorrencia" value="">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="motorista" >Motorista</label>
                            <select name="motorista" id="motorista" class="form-control">
                                <option value="">  </option>
                                <?php
                                    $motoristas = $db->query("SELECT cod_interno_motorista, nome_motorista FROM motoristas ORDER BY nome_motorista ASC");
                                    if($motoristas->rowCount()>0){
                                        $motoristas = $motoristas->fetchAll();
                                        foreach($motoristas as $motorista):
                                ?>
                                    <option value="<?=$motorista['cod_interno_motorista']?>"><?=$motorista['nome_motorista']?></option>
                                <?php            
                                        endforeach;
                                    }

                                ?>
                            </select>
                        </div>
                        <div class="form-group col-md-2 ">
                            <label for="data" readonly  >Data da Ocorrência</label>
                            <input type="date" name="data" class="form-control" id="data" value="">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="grupo">Tipo Ocorrência</label>
                            <select name="tipo" required id="tipo" class="form-control">
                                <option value=""></option>
                                <option value="Má Condução">Má Condução</option>
                                <option value="Mau Comportamento">Mau Comportamento</option>
                            </select>
                        </div>
                        <div class="input-group mb-3 form-grupo col-md-3 centro-file">
                            <div class="custom-file">
                                <input type="file" name="anexoOcorrencia[]" multiple="multiple" class="custom-file-input" id="anexoOcorrencia" >
                                <label for="anexoOcorrencia" class="custom-file-label">Adicionar Ocorrências</label>
                                <input type="hidden" name="ocorrenciaPadrao" id="ocorrenciaPadrao" value="">
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-2">
                            <label for="placa">Veículo</label>
                            <select name="placa" id="placa" class="form-control">
                                <option value=""></option>
                                <?php
                                    $placas = $db->query("SELECT placa_veiculo FROM veiculos ORDER BY placa_veiculo ASC");
                                    if($placas->rowCount()>0){
                                        $placas = $placas->fetchAll();
                                        foreach($placas as $placa):
                                ?>
                                    <option value="<?=$placa['placa_veiculo']?>"><?=$placa['placa_veiculo']?></option>
                                <?php            
                                        endforeach;
                                    }

                                ?>
                            </select>
                        </div>
                        <div class="form-group  col-md-2">
                            <label for="carregamento">Carregamento</label>
                            <input type="text" name="carregamento" id="carregamento" class="form-control" value="">
                        </div>
                        <div class="form-group  col-md-2">
                            <label for="advertencia">Houve Advertência</label>
                            <select name="advertencia" required id="advertencia" class="form-control">
                                <option value=""></option>
                                <option value=1>SIM</option>
                                <option value=0>NÃO</option>
                            </select>
                        </div>
                        <div class="input-group mb-3 form-grupo col-md-3 centro-file">
                            <div class="custom-file">
                                <input  type="file" name="anexoAdvertencia[]" multiple="multiple" class="custom-file-input" id="anexoAdvertencia" >
                                <label for="anexoAdvertencia" class="custom-file-label">Adicionar Advertência</label>
                                <input type="hidden" name="advertenciaPadrao" id="advertenciaPadrao" value="">
                            </div>
                        </div>
                        <div class="form-group col-md-1 ">
                            <label for="laudo">Laudos</label>
                            <select name="laudo" required id="laudo" class="form-control">
                                <option value=""></option>
                                <option value=1>SIM</option>
                                <option value=0>NÃO</option>
                            </select>
                        </div>
                        <div class="input-group mb-3 form-grupo col-md-2 centro-file">
                            <div class="custom-file">
                                <input type="file" name="anexoLaudo[]" multiple="multiple" class="custom-file-input" id="anexoLaudo" >
                                <label for="anexoLaudo" class="custom-file-label">Adicionar Laudos</label>
                                <input type="hidden" name="laudoPadrao" id="laudoPadrao" value="">
                            </div>
                        </div>
                    </div> 
                    <div class="form-row">
                        <div class="form-group col-md-6 ">
                            <label for="descricaoProblema">Descrição do Problema</label>
                            <textarea name="descricaoProblema" id="descricaoProblema" class="form-control" rows="5"></textarea>
                        </div>
                        <div class="form-group col-md-6 ">
                            <label for="descricaoCusto">Descrição dos Custos</label>
                            <textarea name="descricaoCusto" id="descricaoCusto" class="form-control" rows="5"></textarea>
                        </div> 
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6 ">
                            <label for="vlTotal">Valor Total dos Custos</label>
                            <input type="text" name="vlTotal" id="vlTotal" class="form-control" value="">
                        </div> 
                        <div class="form-group col-md-6 ">
                            <label for="situacao">Resolvido</label>
                            <select name="situacao" class="form-control" id="situacao">
                                <option value=""></option>
                                <option value="SIM">SIM</option>
                                <option value="NÃo">NÃO</option>
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

<script src="../assets/js/jquery.mask.js"></script>
<script>
    jQuery(function($){
        $("#vlTotal").mask('###0,00', {reverse: true});
    })
</script>
</body>
</html>