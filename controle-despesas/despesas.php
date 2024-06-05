<?php

session_start();
require("../conexao.php");

$idModudulo = 7;
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
                    <img src="../assets/images/icones/despesas.png" alt="">
                </div>
                <div class="title">
                    <h2> Despesas Lançadas</h2>
                </div>
                <div class="menu-mobile">
                    <img src="../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                </div>
            </div>
            <!-- dados exclusivo da página-->
            <div class="menu-principal">
                <div class="icon-exp">
                    <a href="javascript:void();" id="modalData"><img src="../assets/images/excel.jpg" alt=""></a>
                </div>
                <div class="table-responsive">
                    <table id='tableDesp' class='table  table-bordered nowrap text-center' style="width: 100%;">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center">Filial </th>
                                <th scope="col" class="text-center">ID Despesas </th>
                                <th scope="col" class="text-center">Nº Carregamento </th>
                                <th scope="col" class="text-center"> Placa Veículo </th>
                                <th scope="col" class="text-center"> Rota </th>
                                <th scope="col" class="text-center"> Motorista </th>
                                <th scope="col" class="text-center"> Peso Carga (Kg) </th>
                                <th scope="col" class="text-center"> Média c/ Tk </th>
                                <th scope="col" class="text-center"> Média s/ Tk </th>
                                <th scope="col" class="text-center"> Data Carregamento </th>
                                <th scope="col" class="text-center"> Data Saída </th>
                                <th scope="col" class="text-center"> Data Retorno </th>
                                <th scope="col" class="text-center"> Dias em Rota </th>
                                <th scope="col" class="text-center">Diárias Motorista (QTD) </th>
                                <th scope="col" class="text-center"> Diárias Motorista (R$)</th>
                                <th scope="col" class="text-center">Diárias Ajudante (QTD) </th>
                                <th scope="col" class="text-center"> Diárias Ajudante (R$)</th>
                                <th scope="col" class="text-center"> Diárias Chapa (QTD)</th>
                                <th scope="col" class="text-center"> Diárias Chapa (R$)</th>
                                <th scope="col" class="text-center">Km Percorrido </th>
                                <th scope="col" class="text-center">Litros </th>
                                <th scope="col" class="text-center">Outros Gastos </th>
                                <th scope="col" class="text-center">Almoço </th>
                                <th scope="col" class="text-center">Passagem </th>
                                <th scope="col" class="text-center">Serviços </th>
                                <th scope="col" class="text-center"> Obs </th>
                                <th scope="col" class="text-center"> Status </th>
                                <th scope="col" class="text-center"> Ações </th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>



    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.10.25/af-2.3.7/date-1.1.0/r-2.2.9/rg-1.1.3/sc-2.0.4/sp-1.3.0/datatables.min.js"></script>
    <script src="../assets/js/menu.js"></script>
    <!-- sweert alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function(){
            $('#tableDesp').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url':'proc_pesq_desp.php'
                },
                'columns': [
                    { data: 'filial'},
                    { data: 'iddespesas' },
                    { data: 'num_carregemento'},
                    { data: 'placa_veiculo' },
                    { data: 'nome_rota' },
                    { data: 'nome_motorista' },
                    { data: 'peso_carga'},
                    { data: 'mediatk'},
                    { data: 'mediastk'},
                    { data: 'data_carregamento' },
                    { data: 'data_saida' },
                    { data: 'data_chegada' },
                    { data: 'dias_em_rota' },
                    { data: 'diarias_mot' },
                    { data: 'valor_mot' },
                    { data: 'diarias_ajud' },
                    { data: 'valor_ajud' },
                    { data: 'diarias_chapa' },
                    { data: 'valor_chapa' },
                    { data: 'km_rodado' },
                    { data: 'litros' },
                    { data: 'outros_gastos_ajudante'},
                    { data: 'tomada'},
                    { data: 'descarga'},
                    { data: 'outros_servicos'},
                    { data: 'obs' },
                    { data: 'status'},
                    { data: 'acoes'},
                ],
                "language":{
                    "url":"//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Portuguese-Brasil.json"
                },
                "aoColumnDefs":[
                    {'bSortable':false, 'aTargets':[27]}
                ],
                order: [[1,'desc']],
                "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull ){
                   
                    if(aData['status']==='Não Confirmado'){
                        $(nRow).css('background-color', 'red');
                    }
                    return nRow;
                }
            });
            
        });

        //abrir modal descartar
        $('#modalData').click(function(){
            $('#modalDate').modal('show');
        });

        // função confirmação sweert de assinatura
        function confirmaAssina(id){
            Swal.fire({
                title: 'Tem certeza?',
                text: 'Você realmente deseja Confirmar esta Despesa?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, Confirmar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Se o usuário confirmar, redirecione para a página de exclusão
                    window.location.href = 'confirmacao.php?id=' + id;
                }
            });
        }

        // função confirmação sweert de exclusao
        function confirmaDelete(id){
            Swal.fire({
                title: 'Tem certeza?',
                text: 'Você realmente deseja Excluir esta Despesa?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, Excluir!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Se o usuário confirmar, redirecione para a página de exclusão
                    window.location.href = 'excluir.php?id=' + id;
                }
            });
        }
        
    </script>

<!-- Modal escolher data -->
<div class="modal fade" id="modalDate" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Escolhar a Data para Gerar o Relatório </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="gerar-planilha.php" method="post">
                    <input type="hidden" name="id" id="id" value="">
                    <input type="hidden" name="trid" id="trid" value="">
                    <div class="form-row">
                        <input type="hidden" name="idpneu" id="idpneus">
                        <div class="form-group col-md-6">
                            <label for="inicio">Data Inicial</label>
                            <input type="date" name="inicio" id="inicio" required class="form-control" >
                        </div>
                        <div class="form-group col-md-6">
                            <label for="final">Data Final</label>
                            <input type="date" name="final" id="final" required class="form-control" >
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                    <div class="text-center">
                            <button type="submit" class="btn btn-success">Gerar</button>
                        </div>
                    <button type="button"  class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </form> 
            </div>
        </div>
    </div>
</div>
<!-- msg de sucesso ou erro -->
<?php
    // Verifique se há uma mensagem de confirmação na sessão
    if (isset($_SESSION['msg']) && $_SESSION['icon']) {
        // Exiba um alerta SweetAlert
        echo "<script>
                Swal.fire({
                  icon: '$_SESSION[icon]',
                  title: '$_SESSION[msg]',
                  showConfirmButton: true,
                });
              </script>";

        // Limpe a mensagem de confirmação da sessão
        unset($_SESSION['msg']);
        unset($_SESSION['status']);
    }
?>
</body>
</html>