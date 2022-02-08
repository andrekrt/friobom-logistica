<?php

session_start();
require("../conexao.php");

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false&& $_SESSION['tipoUsuario'] != 2 && $_SESSION['tipoUsuario'] != 3) {

   
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
                    <img src="../assets/images/icones/motoristas.png" alt="">
                </div>
                <div class="title">
                    <h2>Auxiliares de Rota</h2>
                </div>
                <div class="menu-mobile">
                    <img src="../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                </div>
            </div>
            <!-- dados exclusivo da página-->
            <div class="menu-principal">
                <div class="icon-exp">
                    <div class="area-opcoes-button">
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#newColaborador" data-whatever="@mdo" name="">Novo Auxiliar de Rota</button>
                    </div>
                    <a href="auxiliares-xls.php" ><img src="../assets/images/excel.jpg" alt=""></a> 
                </div>
                <div class="table-responsive">
                    <table id='tableAux' class='table table-striped table-bordered nowrap text-center' style="width: 100%;">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center"> CPF </th>
                                <th scope="col" class="text-center"> Nome  </th>
                                <th scope="col" class="text-center"> Salário </th>
                                <th scope="col" class="text-center"> Rota </th>
                                <th scope="col" class="text-center"> Ações</th>
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
            $('#tableAux').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url':'proc_pesq_aux.php'
                },
                'columns': [
                    { data: 'cpf_auxiliar'},
                    { data: 'nome_auxiliar'},
                    { data: 'salario_auxiliar'},
                    { data: 'rota'},
                    { data: 'acoes'},
                ],
                "language":{
                    "url":"//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Portuguese-Brasil.json"
                }
            });
        });

        //abrir modal edtiar
        $('#tableAux').on('click', '.editbtn', function(event){
            var table = $('#tableAux').DataTable();
            var trid = $(this).closest('tr').attr('id');
            var id = $(this).data('id');

            $('#modalEditar').modal('show');

            $.ajax({
                url:"get_single_aux.php",
                data:{id:id},
                type:'post',
                success: function(data){
                    var json = JSON.parse(data);
                    $('#id').val(json.idauxiliares);
                    $('#nome').val(json.nome_auxiliar);
                    $('#cpfEdit').val(json.cpf_auxiliar);
                    $('#salarioEdit').val(json.salario_auxiliar);
                    $('#rota').val(json.rota);
                }
            })
        });
    </script>

<!-- modal visualisar e editar -->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Auxiliar de Rota</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="atualiza-auxiliar.php" method="post">
                    <input type="hidden" name="id" id="id" value="">
                    <input type="hidden" name="trid" id="trid" value="">
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="nome"   class="col-form-label">Nome </label>
                            <input type="text" required name="nome" id="nome" class="form-control"> 
                        </div>
                        <div class="form-group col-md-3">
                            <label for="cpf" class="col-form-label">CPF </label>
                            <input type="text" required name="cpf" class="form-control" id="cpfEdit" value="">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="salario" class="col-form-label">Salário</label>
                            <input type="text" required name="salario" class="form-control" id="salarioEdit" value="">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="rota" class="col-form-label">Rota</label>
                            <select name="rota" id="rota" class="form-control">
                                <option value=""></option>
                            <?php 
                            $select = $db->query("SELECT * FROM rotas ");
                            $rotas = $select->fetchAll();
                            foreach($rotas as $rota):
                            ?>
                                <option value="<?=$rota['cod_rota']?>"><?=$rota['nome_rota']?></option>
                            <?php
                            endforeach;
                            ?>
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

<!-- modal cadastrar colaborador -->
<div class="modal fade" id="newColaborador" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Novo Auxiliar de Rota</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="add-auxiliar.php" method="post">
                    <input type="hidden" name="id" id="id" value="">
                    <input type="hidden" name="trid" id="trid" value="">
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="nome"   class="col-form-label">Nome </label>
                            <input type="text" required name="nome" id="nome" class="form-control"> 
                        </div>
                        <div class="form-group col-md-3">
                            <label for="cpf" class="col-form-label">CPF </label>
                            <input type="text" required name="cpf" class="form-control" id="cpf" value="">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="salario" class="col-form-label">Salário</label>
                            <input type="text" required name="salario" class="form-control" id="salario" value="">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="rota" class="col-form-label">Rota</label>
                            <select name="rota" id="rota" class="form-control">
                                <option value=""></option>
                            <?php 
                            $select = $db->query("SELECT * FROM rotas ");
                            $rotas = $select->fetchAll();
                            foreach($rotas as $rota):
                            ?>
                                <option value="<?=$rota['cod_rota']?>"><?=$rota['nome_rota']?></option>
                            <?php
                            endforeach;
                            ?>
                            </select>
                        </div>
                    </div>  
            </div>
            <div class="modal-footer">
                    <div class="text-center">
                            <button type="submit" class="btn btn-success">Cadastrar</button>
                        </div>
                </form> 
            </div>
        </div>
    </div>
</div>
<script src="../assets/js/jquery.mask.js"></script>
<script >
    $(document).ready(function(){
        $('#salario').mask("###0,00", {reverse: true});
        $('#salarioEdit').mask("###0,00", {reverse: true});
        $('#cpf').mask('000.000.000-00', {reverse: true});
        $('#cpfEdit').mask('000.000.000-00', {reverse: true});
    });
</script>
</body>
</html>