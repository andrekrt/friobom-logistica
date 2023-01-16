<?php

session_start();
require("../conexao.php");

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && $_SESSION['tipoUsuario'] != 3 && $_SESSION['tipoUsuario'] != 4) {

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
                    <img src="../assets/images/icones/almoxerifado.png" alt="">
                </div>
                <div class="title">
                    <h2>Saídas</h2>
                </div>
                <div class="menu-mobile">
                    <img src="../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                </div>
            </div>
            <!-- dados exclusivo da página-->
            <div class="menu-principal">
                <div class="icon-exp">
                    <!-- <div class="area-opcoes-button">
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalEntrada" data-whatever="@mdo" name="idpeca">Nova Saída</button>
                    </div> -->
                    <a href="saidas-xls.php" ><img src="../assets/images/excel.jpg" alt=""></a>
                </div>
                <div class="table-responsive">
                    <table id='tableSaida' class='table table-striped table-bordered nowrap text-center' style="width: 100%;">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center text-nowrap">Nº Saída</th>
                                <th scope="col" class="text-center text-nowrap">Data Saída</th>
                                <th scope="col" class="text-center text-nowrap">Qtd</th>
                                <th scope="col" class="text-center text-nowrap">Peça</th>
                                <th scope="col" class="text-center text-nowrap"> Solicitante </th>
                                <th scope="col" class="text-center text-nowrap"> Placa </th>
                                <th scope="col" class="text-center text-nowrap"> Observações </th>
                                <th scope="col" class="text-center text-nowrap"> Serviço </th>
                                <th scope="col" class="text-center text-nowrap"> Requisição </th>
                                <th scope="col" class="text-center text-nowrap"> OS </th>
                                <th scope="col" class="text-center text-nowrap"> Usuário que Lançou </th>
                               
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.10.25/af-2.3.7/date-1.1.0/r-2.2.9/rg-1.1.3/sc-2.0.4/sp-1.3.0/datatables.min.js"></script>
    
    <script>
        $(document).ready(function(){
            $('#tableSaida').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url':'proc_pesq_saida.php'
                },
                'columns': [
                    { data: 'idsaida_estoque' },
                    { data: 'data_saida' },
                    { data: 'qtd' },
                    { data: 'descricao_peca' },
                    { data: 'solicitante' },
                    { data: 'placa' },
                    { data: 'obs' },
                    { data: 'servico' },
                    { data: 'requisicao' },
                    { data: 'os' },
                    { data: 'nome_usuario' },
                ],
                "language":{
                    "url":"//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Portuguese-Brasil.json"
                }
            });
        });

    </script>

<!-- modal visualisar e editar -->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Saida</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="atualiza-saida.php" method="post">
                    <input type="hidden" name="id" id="id" value="">
                    <input type="hidden" name="trid" id="trid" value="">
                    <div class="form-row">
                        <div class="form-group col-md-1">
                            <label for="id" class="col-form-label">ID</label>
                            <input type="text" readonly name="id" class="form-control" id="idsaida" value="">
                        </div>
                        <div class="form-group col-md-2 ">
                            <label for="veiculo" class="col-form-label">Data</label>
                            <input type="date" name="data" class="form-control" id="data" value="">
                        </div>
                        <div class="form-group col-md-1">
                            <label for="qtd"  class="col-form-label">Quantidade</label>
                            <input type="text" name="qtd" class="form-control" id="qtd" value="">
                        </div>
                        <div class="form-group col-md-5">
                            <label for="peca"  class="col-form-label">Peça</label>
                            <select required name="peca" id="pecaEdit" class="form-control">
                                <?php $pecas = $db->query("SELECT * FROM peca_estoque");
                                $pecas = $pecas->fetchAll();
                                foreach($pecas as $peca):
                                ?>
                                <option value="<?=$peca['idpeca']?>"><?=$peca['descricao_peca']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="solicitante" class="col-form-label">Solicitante</label>
                            <input type="text" class="form-control" name="solicitante" id="solicitante" value="">
                        </div>
                    </div>    
                    <div class="form-row">
                        <div class="form-group col-md-8">
                            <label for="obs" class="col-form-label">Observações</label>
                            <input type="text" class="form-control" name="obs" id="obs" value="">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="placa" class="col-form-label">Placa</label>
                            <select required name="placa" id="placaEdit" class="form-control">
                                <?php $veiculos = $db->query("SELECT * FROM veiculos");
                                $placas = $veiculos->fetchAll();
                                foreach($placas as $placa):
                                ?>
                                <option value="<?=$placa['placa_veiculo']?>"><?=$placa['placa_veiculo']?></option>
                                <?php endforeach; ?>
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


<!-- MODAL lançamento de saída -->
<div class="modal fade" id="modalEntrada" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Lançar Saída</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="add-saida.php" method="post">
                    <div class="form-row">
                        <div class="form-group col-md-4 espaco ">
                            <label for="dataSaida"> Data Saída</label>
                            <input type="date" required  name="dataSaida" class="form-control" id="dataSaida">
                        </div>
                        <div class="form-group col-md-4 espaco ">
                            <label for="qtd">Quantidade</label>
                            <input type="text" required  name="qtd" class="form-control" id="qtd">
                        </div>
                        <div class="form-group col-md-4 espaco ">
                            <label for="peca"> Solicitante </label>
                            <input type="text" required name="solicitante" id="solicitante" class="form-control">
                        </div>
                    </div>    
                    <div class="form-row">
                        <div class="form-group col-md-12 espaco ">
                            <label for="peca"> Peça</label>
                            <select required name="peca" id="pecaSa" class="form-control">
                                <option value=""></option>
                                <?php $pecas = $db->query("SELECT * FROM peca_estoque");
                                $pecas = $pecas->fetchAll();
                                foreach($pecas as $peca):
                                ?>
                                <option value="<?=$peca['idpeca']?>"><?= $peca['idpeca']." - ". $peca['descricao_peca']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4 espaco">
                            <label for="placa"> Placa </label>
                            <select required name="placa" id="placaSa" class="form-control">
                                <option value=""></option>
                                <option value="Oficina">Oficina</option>
                                <?php $pecas = $db->query("SELECT * FROM veiculos");
                                $pecas = $pecas->fetchAll();
                                foreach($pecas as $peca):
                                ?>
                                <option value="<?=$peca['placa_veiculo']?>"><?=$peca['placa_veiculo']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group col-md-8 espaco">
                            <label for="obs"> Observações </label>
                            <input type="text" name="obs" class="form-control" id="obs">
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
<!-- FIM MODAL lançamento de saida-->

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function(){
        $('#pecaSa').select2({
            width: '100%',
            dropdownParent:"#modalEntrada"
        });
        $('#placaSa').select2({
            width: '100%',
            dropdownParent:"#modalEntrada"
        });
        $('#pecaEdit').select2({
            width: '100%',
            dropdownParent:"#modalEntrada"
        });
        $('#placaEdit').select2({
            width: '100%',
            dropdownParent:"#modalEntrada"
        });
    });
</script>
</body>
</html>