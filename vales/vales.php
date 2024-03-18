<?php

session_start();
require("../conexao.php");

$idModudulo = 19;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo, PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result > 0)) {

    $nomeUsuario = $_SESSION['nomeUsuario'];
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
    <link href="https://cdn.datatables.net/v/bs4/jszip-3.10.1/dt-2.0.1/b-3.0.0/b-html5-3.0.0/b-print-3.0.0/datatables.min.css" rel="stylesheet">

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
                    <img src="../assets/images/icones/icon-vale.png" alt="">
                </div>
                <div class="title">
                    <h2>Vales</h2>
                </div>
                <div class="menu-mobile">
                    <img src="../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                </div>
            </div>
            <!-- dados exclusivo da página-->
            <div class="menu-principal">
                <div class="icon-exp">
                    <div class="area-opcoes-button">
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalVale" data-whatever="@mdo" name="idpeca">Lançar Vale</button>
                    </div>
                    <a href="vales-xls.php"><img src="../assets/images/excel.jpg" alt=""></a>
                </div>
                <div class="table-responsive">
                    <table id='tableVale' class='table table-striped table-bordered nowrap' style="width: 100%;">
                        <thead>
                            <tr>
                                <th> Nº </th>
                                <th> Data </th>
                                <th>Motorista</th>
                                <th> Rota </th>
                                <th> Valor </th>
                                <th>Carregamento</th>
                                <th>Status</th>
                                <th>Usuário</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <script src="../assets/js/jquery.js"></script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/v/bs4/jszip-3.10.1/dt-2.0.1/b-3.0.0/b-html5-3.0.0/b-print-3.0.0/datatables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#tableVale').DataTable({
                
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url': 'pesq_vales.php'
                },
                'columns': [
                    {
                        data: 'idvale'
                    },
                    {
                        data: 'data_lancamento'
                    },
                    {
                        data: 'motorista'
                    },
                    {
                        data: 'rota'
                    },
                    {
                        data: 'valor'
                    },
                    {
                        data: 'carregamento'
                    },
                    {
                        data: 'situacao'
                    },
                    {
                        data: 'usuario'
                    },
                    {
                        data: 'acoes'
                    }
                ],
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Portuguese-Brasil.json"
                },
                order: [0, 'desc'],
                "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull ){
                   
                   if(aData['situacao']==='Não Resgatado'){
                       $(nRow).css('background-color', 'red');
                       $(nRow).css('color', 'white')
                   }
                   return nRow;
               },
            });

        });

        //abrir modal
        $('#tableVale').on('click', '.editbtn', function(event) {
            var table = $('#tableVale').DataTable();
            var trid = $(this).closest('tr').attr('id');
            var id = $(this).data('id');

            $('#modalEditar').modal('show');

            $.ajax({
                url: "get_vale.php",
                data: {
                    id: id
                },
                type: 'post',
                success: function(data) {
                    var json = JSON.parse(data);
                    $('#idvale').val(json.idvale);
                    $('#motoristaEdit').val(json.motorista);
                    $('#rotaEdit').val(json.rota);
                    $('#valorEdit').val(json.valor);
                    $('#status').val(json.situacao);
                    $('#carregamento').val(json.carregamento);
                }
            })
        });
    </script>

    <!-- MODAL lançamento de vale -->
    <div class="modal fade" id="modalVale" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Registro de Vale</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="add-vale.php" method="post">
                        <div class="form-row">
                            <div class="form-group col-md-4 espaco ">
                                <label for="motorista"> Motorista</label>
                                <select name="motorista" required id="motorista" class="form-control">
                                    <option value=""></option>
                                    <?php
                                    $motoristas = $db->prepare("SELECT cod_interno_motorista, nome_motorista FROM motoristas WHERE ativo=1 ORDER BY nome_motorista ASC");
                                    $motoristas->execute();
                                    $motoristas = $motoristas->fetchAll();
                                    foreach ($motoristas as $motorista) :
                                    ?>
                                        <option value="<?= $motorista['cod_interno_motorista'] ?>"><?= $motorista['nome_motorista'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group col-md-4 espaco ">
                                <label for="rota"> Rota</label>
                                <select name="rota" required id="rota" class="form-control">
                                    <option value=""></option>
                                    <?php
                                    $rotas = $db->prepare("SELECT cod_rota, nome_rota FROM rotas ORDER BY nome_rota ASC");
                                    $rotas->execute();
                                    $rotas = $rotas->fetchAll();
                                    foreach ($rotas as $rota) :
                                    ?>
                                        <option value="<?= $rota['cod_rota'] ?>"><?= $rota['nome_rota'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group col-md-4 espaco removivel">
                                <label for="valor"> Valor (R$)</label>
                                <input type="text" required name="valor" class="form-control" id="valor">
                            </div>
                        </div>
                        <div class="form-row" id="add">

                        </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="analisar" class="btn btn-primary">Lançar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- FIM MODAL lançamento de vale-->

    <!-- MODAL edição de abastecimento -->
    <div class="modal fade" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Editar Vale</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="atualiza-vale.php" method="post">
                        <div class="form-row">
                            <input type="hidden" name="idvale" value="" id="idvale">
                            <div class="form-group col-md-4 espaco ">
                                <label for="motorista"> Motorista</label>
                                <select name="motorista" required id="motoristaEdit" class="form-control">
                                    <option value=""></option>
                                    <?php
                                    $motoristas = $db->prepare("SELECT cod_interno_motorista, nome_motorista FROM motoristas WHERE ativo=1 ORDER BY nome_motorista ASC");
                                    $motoristas->execute();
                                    $motoristas = $motoristas->fetchAll();
                                    foreach ($motoristas as $motorista) :
                                    ?>
                                        <option value="<?= $motorista['cod_interno_motorista'] ?>"><?= $motorista['nome_motorista'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>                          
                            <div class="form-group col-md-2 espaco ">
                                <label for="rota"> Rota</label>
                                <select name="rota" required id="rotaEdit" class="form-control">
                                    <option value=""></option>
                                    <?php
                                    $rotas = $db->prepare("SELECT cod_rota, nome_rota FROM rotas ORDER BY nome_rota ASC");
                                    $rotas->execute();
                                    $rotas = $rotas->fetchAll();
                                    foreach ($rotas as $rota) :
                                    ?>
                                        <option value="<?= $rota['cod_rota'] ?>"><?= $rota['nome_rota'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group col-md-2 espaco removivel">
                                <label for="valor"> Valor (R$)</label>
                                <input type="text" required name="valor" class="form-control" id="valorEdit">
                            </div>
                            <div class="form-group col-md-2 espaco removivel">
                                <label for="carregamento">Carregamento</label>
                                <input type="text" name="carregamento" class="form-control" id="carregamento">
                            </div>
                            <div class="form-group col-md-2 espaco removivel">
                                <label for="status">Status</label>
                                <select name="status" id="status" required class="form-control">
                                    <option value=""></option>
                                    <option value="Não Resgatado">Não Resgatado</option>
                                    <option value="Resgatado">Resgatado</option>
                                </select>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="analisar" class="btn btn-primary">Salvar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- FIM MODAL edição de abastecimento-->

    <script src="../assets/js/jquery.mask.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        jQuery(function($) {
            $("#valor").mask("#.##0,00", {
                reverse: true
            });
            $("#valorEdit").mask('###0,00', {
                reverse: true
            });
        })

        $(document).ready(function() {

            $('#motorista').select2({
                width: '100%',
                dropdownParent: "#modalVale"
            });
            $('#rota').select2({
                width: '100%',
                dropdownParent: "#modalVale"
            });

        });
    </script>
</body>

</html>