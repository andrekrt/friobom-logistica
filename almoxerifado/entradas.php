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
                    <h2>Entradas</h2>
                </div>
                <div class="menu-mobile">
                    <img src="../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                </div>
            </div>
            <!-- dados exclusivo da página-->
            <div class="menu-principal">
                <div class="icon-exp">
                    <div class="area-opcoes-button">
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalEntrada" data-whatever="@mdo" name="idpeca">Nova Entrada</button>
                    </div>
                    <a href="entradas-xls.php" ><img src="../assets/images/excel.jpg" alt=""></a>
                </div>
                <div class="table-responsive">
                    <table id='tableEntrada' class='table table-striped table-bordered nowrap text-center' style="width: 100%;">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center text-nowrap">ID</th>
                                <th scope="col" class="text-center text-nowrap">Data NF</th>
                                <th scope="col" class="text-center text-nowrap">Nº NF</th>
                                <th scope="col" class="text-center text-nowrap">Nº Pedido</th>
                                <th scope="col" class="text-center text-nowrap">Peça</th>
                                <th scope="col" class="text-center text-nowrap"> Preço </th>
                                <th scope="col" class="text-center text-nowrap"> Quantidade </th>
                                <th scope="col" class="text-center text-nowrap"> Desconto </th>
                                <th scope="col" class="text-center text-nowrap"> Observações </th>
                                <th scope="col" class="text-center text-nowrap"> Fornecedor </th>
                                <th scope="col" class="text-center text-nowrap">  Valor Total  </th>
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
            $('#tableEntrada').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url':'proc_pesq_entrada.php'
                },
                'columns': [
                    { data: 'identrada_estoque' },
                    { data: 'data_nf' },
                    { data: 'num_nf' },
                    { data: 'num_pedido' },
                    { data: 'descricao_peca' },
                    { data: 'preco_custo' },
                    { data: 'qtd' },
                    { data: 'desconto' },
                    { data: 'obs' },
                    { data: 'apelido' },
                    { data: 'vl_total_comprado' },
                    { data: 'nome_usuario' },
                    { data: 'acoes' },
                ],
                "language":{
                    "url":"//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Portuguese-Brasil.json"
                },
                "aoColumnDefs":[
                    {'bSortable':false, 'aTargets':[12]}
                ],
            });
        });

        //abrir modal
        $('#tableEntrada').on('click', '.editbtn', function(event){
            var table = $('#tableEntrada').DataTable();
            var trid = $(this).closest('tr').attr('id');
            var id = $(this).data('id');

            $('#modalEditar').modal('show');

            $.ajax({
                url:"get_single_entrada.php",
                data:{id:id},
                type:'post',
                success: function(data){
                    var json = JSON.parse(data);
                    $('#identrada').val(json.identrada_estoque);
                    $('#dataNf').val(json.data_nf);
                    $('#numNf').val(json.num_nf);
                    $('#numPedido').val(json.num_pedido);
                    $('#pecaEdit').val(json.peca_idpeca);
                    $('#preco').val(json.preco_custo);
                    $('#totalComprado').val(json.vl_total_comprado);
                    $('#qtd').val(json.qtd);
                    $('#desconto').val(json.desconto);
                    $('#obs').val(json.obs);
                    $('#fornecedor').val(json.fornecedor);                    
                }
            })
        });
    </script>

<!-- modal visualisar e editar -->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Entrada</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="atualiza-entrada.php" method="post">
                    <input type="hidden" name="id" id="id" value="">
                    <input type="hidden" name="trid" id="trid" value="">
                    <div class="form-row">
                        <div class="form-group col-md-1">
                            <label for="identrada" class="col-form-label">ID</label>
                            <input type="text" readonly name="identrada" class="form-control" id="identrada" value="">
                        </div>
                        <div class="form-group col-md-2 ">
                            <label for="dataNf" class="col-form-label">Data NF</label>
                            <input type="date" name="dataNf" class="form-control" id="dataNf" value="">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="numNf"  class="col-form-label">Nº NF</label>
                            <input type="text" name="numNf" class="form-control" id="numNf" value="">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="numPedido"  class="col-form-label">Nº Pedido</label>
                            <input type="text" name="numPedido" class="form-control" id="numPedido" value="">
                        </div>
                        <div class="form-group col-md-5">
                            <label for="peca" class="col-form-label"> Peça </label>
                            <select required name="peca" id="pecaEdit" class="form-control">
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
                        <div class="form-group col-md-3">
                            <label for="preco" class="col-form-label">Preço</label>
                            <input type="text" class="form-control" name="preco" id="preco" value="">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="qtd" class="col-form-label">Quantidade</label>
                            <input type="text" class="form-control" name="qtd" id="qtd" value="">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="desconto" class="col-form-label">Desconto</label>
                            <input type="text" class="form-control" name="desconto" id="desconto" value="">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="totalComprado" class="col-form-label">Total</label>
                            <input type="text" readonly class="form-control" name="totalComprado" id="totalComprado" value="">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="obs" class="col-form-label">Observações</label>
                            <input type="text" class="form-control" name="obs" id="obs" value="">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="fornecedor" class="col-form-label">Fornecedor</label>
                            <select required name="fornecedor" id="fornecedorEdit" class="form-control">
                                <?php $fornecedores = $db->query("SELECT * FROM fornecedores");
                                $fornecedores = $fornecedores->fetchAll();
                                foreach($fornecedores as $fornecedor):
                                ?>
                                <option value="<?=$fornecedor['id']?>"><?=$fornecedor['apelido']?></option>
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


<!-- MODAL lançamento de entrada -->
<div class="modal fade" id="modalEntrada" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Lançar Entrada</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="add-entrada.php" method="post">
                    <div class="form-row">
                        <div class="form-group col-md-4 espaco ">
                            <label for="dataNota"> Data Nota</label>
                            <input type="date"  name="dataNota" class="form-control" id="dataNota">
                        </div>
                        <div class="form-group col-md-4 espaco ">
                            <label for="numNF"> Nº NF</label>
                            <input type="text"  name="numNF" class="form-control" id="numNF">
                        </div>
                        <div class="form-group col-md-4 espaco ">
                            <label for="pedido"> Nº Pedido</label>
                            <input type="text"  name="pedido" class="form-control" id="pedido">
                        </div>
                    </div>    
                    <div class="form-row">
                        <div class="form-group col-md-12 espaco ">
                            <label for="peca"> Peça </label>
                            <select required name="peca" id="pecaCad" class="form-control">
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
                            <label for="preco"> Preço de Custo </label>
                            <input type="text" required name="preco" class="form-control" id="preco02">
                        </div>
                        <div class="form-group col-md-4 espaco">
                            <label for="qtd"> Quantidade </label>
                            <input type="text" required name="qtd" class="form-control" id="qtd02">
                        </div>
                        <div class="form-group col-md-4 espaco">
                            <label for="desconto"> Desconto </label>
                            <input type="text" required name="desconto" class="form-control" id="desconto02">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6 espaco">
                            <label for="obsEntrada"> Observações </label>
                            <input type="text"  name="obsEntrada" class="form-control" id="obsEntrada">
                        </div>
                        <div class="form-group col-md-6 espaco">
                            <label for="fornecedor"> Fornecedor </label>
                            <select required name="fornecedor" id="fornecedorCad" class="form-control">
                                <option value=""></option>
                                <?php $fornecedores = $db->query("SELECT * FROM fornecedores");
                                $fornecedores = $fornecedores->fetchAll();
                                foreach($fornecedores as $fornecedor):
                                ?>
                                <option value="<?=$fornecedor['id']?>"><?=$fornecedor['apelido']?></option>
                                <?php endforeach; ?>
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
<!-- FIM MODAL lançamento de entrada-->

<script src="../assets/js/jquery.mask.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    jQuery(function($){
        $("#preco").mask('###0,00', {reverse: true});
        $("#desconto").mask('###0,00', {reverse: true});
        $("#qtd").mask('###0,00', {reverse: true});
        $("#preco02").mask('###0,00', {reverse: true});
        $("#desconto02").mask('###0,00', {reverse: true});
        $("#qtd02").mask('###0,00', {reverse: true});
    })

    $(document).ready(function(){
        $('#pecaCad').select2({
            width: '100%',
            dropdownParent:"#modalEntrada"
        });
        $('#fornecedorCad').select2({
            width: '100%',
            dropdownParent:"#modalEntrada"
        });
       
    });
</script>
</body>
</html>