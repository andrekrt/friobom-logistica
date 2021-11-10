<?php

session_start();
require("../conexao.php");

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && $_SESSION['tipoUsuario'] == 3 || $_SESSION['tipoUsuario'] == 99) {

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
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/dt-1.10.25/af-2.3.7/date-1.1.0/r-2.2.9/rg-1.1.3/sc-2.0.4/sp-1.3.0/datatables.min.css"/>

</head>
<body>
    <div class="container-fluid corpo">
        <?php require('../menu-lateral.php') ?>
        <!-- Tela com os dados -->
        <div class="tela-principal">
            <div class="menu-superior">
                <div class="icone-menu-superior">
                    <img src="../assets/images/icones/reparos.png" alt="">
                </div>
                <div class="title">
                    <h2>Peças/Serviços</h2>
                </div>
                <div class="menu-mobile">
                    <img src="../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                </div>
            </div>
            <!-- dados exclusivo da página-->
            <div class="menu-principal">
                <div class="icon-exp">
                    <div class="area-opcoes-button">
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalPecaServico" data-whatever="@mdo" name="pecaServico">Nova Peça/Serviço</button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id='tablePecas' class='table table-striped table-bordered nowrap text-center' style="width: 100%;">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center text-nowrap">Descrição</th>
                                <th scope="col" class="text-center text-nowrap">Categoria</th>
                                <th scope="col" class="text-center text-nowrap">Medida</th>
                                <th scope="col" class="text-center text-nowrap">Ações</th>
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
            $('#tablePecas').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url':'proc_pesq_pecas.php'
                },
                'columns': [
                    { data: 'descricao' },
                    { data: 'categoria' },
                    { data: 'un_medida' },
                    { data: 'acoes' },
                ],
                "language":{
                    "url":"//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Portuguese-Brasil.json"
                },
            });
        });

        //abrir modal
        $('#tablePecas').on('click', '.editbtn', function(event){
            var table = $('#tablePecas').DataTable();
            var trid = $(this).closest('tr').attr('id');
            var id = $(this).data('id');

            $('#modalEditar').modal('show');

            $.ajax({
                url:"get_single_peca.php",
                data:{id:id},
                type:'post',
                success: function(data){
                    var json = JSON.parse(data);
                    $('#id').val(json.id_peca_reparo);
                    $('#descricao').val(json.descricao);
                    $('#categoria').val(json.categoria);
                    $('#medida').val(json.un_medida);
                }
            })
        });
    </script>

<!-- modal visualisar e editar -->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Veículo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="atualiza-peca.php" method="post">
                    <input type="hidden" name="id" id="id" value="">
                    <input type="hidden" name="trid" id="trid" value="">
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="descricao" >Descrição</label>
                            <input type="text" name="descricao" class="form-control" id="descricao" value="">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="categoria" >Categoria</label>
                            <select name="categoria" id="categoria" class="form-control">
                                <option value="Terminais da Barra de Direção">Terminais da Barra de Direção</option>
                                <option value="Abraçadeira">Abraçadeira</option>
                                <option value="Molas">Molas</option>
                                <option value="Pino de Centro">Pino de Centro</option>
                                <option value="Rolamento">Rolamento</option>
                                <option value="Bicos Injetores">Bicos Injetores</option>
                                <option value="Motor de Partida">Motor de Partidada</option>
                                <option value="Pneu">Pneu</option>
                                <option value="Enbuchamentos">Enbuchamentos</option>
                                <option value="Alinhamento">Alinhamento</option>
                                <option value="Balanceamento">Balanceamento</option>
                                <option value="Buchas">Buchas</option>
                                <option value="Válvula de Pressão de Ar">Válvula de Pressão de Ar</option>
                                <option value="Sensores ABS">Sensores ABS</option>
                                <option value="Válvula de Distribuição">Válvula de Distribuição</option>
                                <option value="Freios">Freios</option>
                                <option value="Suspensão">Suspensão</option>
                                <option value="Soldas">Soldas</option>
                                <option value="Lanternagem">Lanternagem</option>
                                <option value="Serviços">Serviços</option>
                                <option value="Elétrica">Elétrica</option>
                                <option value="Graxas">Graxas</option>
                                <option value="Filtros">Filtros</option>
                                <option value="Óleo para Motor">Óleo para Motor</option>
                                <option value="Refrigeração">Refrigeração</option>
                                <option value="Arla 32">Arla 32</option>
                                <option value="Combustível">Combustível</option>
                                <option value="Motor">Motor</option>
                                <option value="Acessórios">Acessórios</option>
                                <option value="Baú">Baú</option>
                                <option value="Direção">Direção</option>
                                <option value="Outros">Outros</option>
                                <option value="Embreagem">Embreagem</option>
                                <option value="Diferencial">Diferencial</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="medida"  >Medida</label>
                            <select name="medida" id="medida" class="form-control">
                                <option value="Litros">Litros</option>
                                <option value="Und">Und.</option>
                                <option value="Metro">Metro</option>
                                <option value="Kg">Kg</option>
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


<!-- MODAL CADASTRO DE peça -->
<div class="modal fade" id="modalPecaServico" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Cadastro de Peça/Serviço</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="add-peca.php" method="post">
                    <div class="form-row">
                        <div class="form-group col-md-4 espaco ">
                            <label for="descricao"> Descrição</label>
                            <input type="text" required name="descricao" class="form-control" id="descricao">
                        </div>
                        <div class="form-group col-md-4 espaco ">
                            <label for="categoria"> Categoria </label>
                            <select name="categoria" id="categoria" class="form-control">
                                <option value=""></option>
                                <option value="Terminais da Barra de Direção">Terminais da Barra de Direção</option>
                                <option value="Abraçadeira">Abraçadeira</option>
                                <option value="Molas">Molas</option>
                                <option value="Pino de Centro">Pino de Centro</option>
                                <option value="Rolamento">Rolamento</option>
                                <option value="Bicos Injetores">Bicos Injetores</option>
                                <option value="Motor de Partida">Motor de Partidada</option>
                                <option value="Pneu">Pneu</option>
                                <option value="Enbuchamentos">Enbuchamentos</option>
                                <option value="Alinhamento">Alinhamento</option>
                                <option value="Balanceamento">Balanceamento</option>
                                <option value="Buchas">Buchas</option>
                                <option value="Válvula de Pressão de Ar">Válvula de Pressão de Ar</option>
                                <option value="Sensores ABS">Sensores ABS</option>
                                <option value="Válvula de Distribuição">Válvula de Distribuição</option>
                                <option value="Freios">Freios</option>
                                <option value="Suspensão">Suspensão</option>
                                <option value="Soldas">Soldas</option>
                                <option value="Lanternagem">Lanternagem</option>
                                <option value="Serviços">Serviços</option>
                                <option value="Elétrica">Elétrica</option>
                                <option value="Graxas">Graxas</option>
                                <option value="Filtros">Filtros</option>
                                <option value="Óleo para Motor">Óleo para Motor</option>
                                <option value="Refrigeração">Refrigeração</option>
                                <option value="Arla 32">Arla 32</option>
                                <option value="Combustível">Combustível</option>
                                <option value="Motor">Motor</option>
                                <option value="Acessórios">Acessórios</option>
                                <option value="Baú">Baú</option>
                                <option value="Direção">Direção</option>
                                <option value="Outros">Outros</option>
                                <option value="Embreagem">Embreagem</option>
                                <option value="Diferencial">Diferencial</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4 espaco ">
                            <label for="medida"> Unidade de Medida </label>
                            <select name="medida" id="medida" class="form-control">
                                <option value=""></option>
                                <option value="Litros">Litros</option>
                                <option value="Und">Und.</option>
                                <option value="Metro">Metro</option>
                                <option value="Kg">Kg</option>
                            </select>
                        </div>
                    </div>    
            </div>
            <div class="modal-footer">
                <button type="submit" name="analisar" class="btn btn-primary">Cadastrar</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- FIM MODAL CADASTRO DE local de reparo-->
</body>
</html>