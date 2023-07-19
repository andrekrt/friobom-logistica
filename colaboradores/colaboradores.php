<?php

session_start();
require("../conexao.php");

$idModudulo = 5;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

   
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
                    <h2>Colaboradores</h2>
                </div>
                <div class="menu-mobile">
                    <img src="../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                </div>
            </div>
            <!-- dados exclusivo da página-->
            <div class="menu-principal">
                <div class="icon-exp">
                    <div class="area-opcoes-button">
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#newColaborador" data-whatever="@mdo" name="">Novo Colaborador</button>
                    </div>
                    <a href="colaboradores-xls.php" ><img src="../assets/images/excel.jpg" alt=""></a>    
                </div>
                <div class="table-responsive">
                    <table id='tableColab' class='table table-striped table-bordered nowrap text-center' style="width: 100%;">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center"> CPF </th>
                                <th scope="col" class="text-center"> Nome  </th>
                                <th scope="col" class="text-center"> Salário </th>
                                <th scope="col" class="text-center"> Extra </th>
                                <th scope="col" class="text-center"> Função </th>
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
            $('#tableColab').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url':'proc_pesq_colab.php'
                },
                'columns': [
                    { data: 'cpf_colaborador'},
                    { data: 'nome_colaborador' },
                    { data: 'salario_colaborador' },
                    { data: 'extra' },
                    { data: 'cargo_colaborador' },
                    { data: 'acoes'},
                ],
                "language":{
                    "url":"//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Portuguese-Brasil.json"
                }
            });
        });

        //abrir modal edtiar
        $('#tableColab').on('click', '.editbtn', function(event){
            var table = $('#tableColab').DataTable();
            var trid = $(this).closest('tr').attr('id');
            var id = $(this).data('id');

            $('#modalEditar').modal('show');

            $.ajax({
                url:"get_single_colab.php",
                data:{id:id},
                type:'post',
                success: function(data){
                    var json = JSON.parse(data);
                    $('#id').val(json.idcolaboradores);
                    $('#nome').val(json.nome_colaborador);
                    $('#cpfEdit').val(json.cpf_colaborador);
                    $('#salarioEdit').val(json.salario_colaborador);
                    $('#extraEdit').val(json.salario_extra);
                    $('#funcao').val(json.cargo_colaborador);
                }
            })
        });
    </script>

<!-- modal visualisar e editar -->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Colaborador</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="atualiza.php" method="post">
                    <input type="hidden" name="id" id="id" value="">
                    <input type="hidden" name="trid" id="trid" value="">
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="nome"   class="col-form-label">Nome </label>
                            <input type="text" required name="nome" id="nome" class="form-control"> 
                        </div>
                        <div class="form-group col-md-2">
                            <label for="cpf" class="col-form-label">CPF </label>
                            <input type="text" required name="cpf" class="form-control" id="cpfEdit" value="">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="salario" class="col-form-label">Salário</label>
                            <input type="text" required name="salario" class="form-control" id="salarioEdit" value="">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="extra" class="col-form-label">Extras</label>
                            <input type="text" required name="extra" class="form-control" id="extraEdit" value="">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="funcao" class="col-form-label">Função</label>
                            <select name="funcao" required id="funcao" class="form-control">
                                <option value=""></option>
                                <option value="Coord. de Transporte">Coord. de Transporte</option>
                                <option value="Mecânico">Mecânico</option>
                                <option value="Aux. de Serviços Gerais">Aux. de Serviços Gerais</option>
                                <option value="Téc. de Refrigeração">Téc. de Refrigeração</option>
                                <option value="Aux. de Refrigeração">Aux. de Refrigeração</option>
                                <option value="Eletricista">Eletricista</option>
                                <option value="Borracheiro">Borracheiro</option>
                                <option value="Enc. Manutenção">Enc. Manutenção</option>
                                <option value="Enc. de Pátio">Enc. de Pátio</option>
                                <option value="Auxiliar de Distribuição">Auxiliar de Distribuição</option>
                                <option value="Auxiliar de Rastreamento">Auxiliar de Rastreamento</option>
                                <option value="Auxiliar de Transporte">Auxiliar de Transporte</option>
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
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Novo Colaborador</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="add-colaborador.php" method="post">
                    <input type="hidden" name="id" id="id" value="">
                    <input type="hidden" name="trid" id="trid" value="">
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="nome"   class="col-form-label">Nome </label>
                            <input type="text" required name="nome" id="nome" class="form-control"> 
                        </div>
                        <div class="form-group col-md-2">
                            <label for="cpf" class="col-form-label">CPF </label>
                            <input type="text" required name="cpf" class="form-control" id="cpf" value="">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="salario" class="col-form-label">Salário</label>
                            <input type="text" required name="salario" class="form-control" id="salario" value="">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="extra" class="col-form-label">Extras</label>
                            <input type="text" required name="extra" class="form-control" id="extra" value="">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="funcao" class="col-form-label">Função</label>
                            <select name="funcao" required id="funcao" class="form-control">
                                <option value=""></option>
                                <option value="Coord. de Transporte">Coord. de Transporte</option>
                                <option value="Mecânico">Mecânico</option>
                                <option value="Aux. de Serviços Gerais">Aux. de Serviços Gerais</option>
                                <option value="Téc. de Refrigeração">Téc. de Refrigeração</option>
                                <option value="Aux. de Refrigeração">Aux. de Refrigeração</option>
                                <option value="Eletricista">Eletricista</option>
                                <option value="Borracheiro">Borracheiro</option>
                                <option value="Enc. Manutenção">Enc. Manutenção</option>
                                <option value="Enc. de Pátio">Enc. de Pátio</option>
                                <option value="Auxiliar de Distribuição">Auxiliar de Distribuição</option>
                                <option value="Auxiliar de Rastreamento">Auxiliar de Rastreamento</option>
                                <option value="Auxiliar de Transporte">Auxiliar de Transporte</option>
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
        $('#extra').mask("###0,00", {reverse: true});
        $('#extraEdit').mask("###0,00", {reverse: true});
        $('#salarioEdit').mask("###0,00", {reverse: true});
        $('#cpf').mask('000.000.000-00', {reverse: true});
        $('#cpfEdit').mask('000.000.000-00', {reverse: true});
    });
</script>
</body>
</html>