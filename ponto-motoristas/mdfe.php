<?php

session_start();
require("../conexao.php");
include_once "winthor.php";

$idModudulo = 22;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

    $nomeUsuario = $_SESSION['nomeUsuario'];

    // função para pegar os mdfe no winthor e registrar no bd online
    registraMdfe();
    

} else {
    $_SESSION['msg'] = 'Acesso não permitido';
    $_SESSION['icon']='warning';
    header("Location: ../index.php");
    exit();
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
                    <img src="../assets/images/icones/icone-mdfe.png" alt="">
                </div>
                <div class="title">
                    <h2>MDFe's</h2>
                </div>
                <div class="menu-mobile">
                    <img src="../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                </div>
            </div>
            <!-- dados exclusivo da página-->
            <div class="menu-principal">
                <div class="table-responsive">
                    <table id='tableMdfe' class='table table-bordered nowrap' style="width: 100%;">
                        <thead>
                            <tr>
                                <th>MDFE</th>
                                <th>  Cargas </th>
                                <th>  Veículo </th>
                                <th>Motoristas</th>
                                <th>Situação </th>
                                <th>Data de Saída</th>
                                <th>Data de Retorno</th>
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
    <!-- sweert alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- bibliotecas momentes -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/locale/pt-br.min.js"></script>

    <script>
        $(document).ready(function(){
            $('#tableMdfe').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url':'pesq_mdfe.php'
                },
                'columns': [
                    { data: 'num_mdfe'},
                    { data: 'cargas' },
                    { data: 'veiculo' },
                    { data: 'motorista' },
                    { data: 'situacao'},
                    { data: 'data_saida' },
                    { data: 'data_retorno'},

                ],
                "language":{
                    "url":"//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Portuguese-Brasil.json"
                },
                order: [0, 'desc']
            });

            // função para clicar na linha e pegar o id 
            $('#tableMdfe tbody').on('click', 'tr', function(){
                

                // verificar se a linha ja estaa selecionado
                let selected = $(this).hasClass('selected');
                
                // remover a classes 'select' de todas as linha
                $('#tableMdfe tbody tr.selected').removeClass('selected');

                // se a linha clicada ja for selecionado remove
                if(selected){
                    $('.acao-btn').remove();
                    $('.acao-row').remove();
                    return;
                }

                // Adiciona a classe 'selected' apenas à linha clicada
                $(this).addClass('selected');

                // pegar os valores das celulas
                let cells = $(this).find('td');
                let rowData = [];

                cells.each(function() {
                    rowData.push($(this).text());
                });

                $('.acao-btn').remove();
                $('.acao-row').remove();

                let btn = $(' <a href="ponto.php?mdfe='+rowData[0]+'"> ').addClass('acao-btn btn btn-success').text(' Registrar Ponto ');
                let btnRegistros = $('<button>').addClass('acao-btn btn btn-primary ').text('Registros');

                btnRegistros.on('click', function(){
                    $('#modalPontos').modal('show');
                    
                    $.ajax({
                        url: 'get_pontos.php',
                        data: {mdfe: rowData[0]},
                        type: 'post',
                        success: function(data){
                            var json = JSON.parse(data);
                            if(json.length>0){
                                $('#mdfe').val(json[0].mdfe);
                                $('#motorista').val(json[0].motorista);
                                $('#carga').val(json[0].cargas);

                                $('#horas').empty();

                                let totalViagemMin=0;
                                let totalParado = 0;
                                let totalLiq = 0;
                                json.forEach(function(dado){
                                    // horas trabalhadas
                                    let tempo = dado.hrs_trabalhada.split(':');
                                    let hrTrabalhado = parseInt(tempo[0]);
                                    let minTrabalhado = parseInt(tempo[1]);
                                    totalViagemMin += hrTrabalhado * 60 + minTrabalhado;

                                    // total tempo parado
                                    let tempoParado = dado.hrs_parada.split(':');
                                    let hrParado = parseInt(tempoParado[0]);
                                    let minParado = parseInt(tempoParado[1]);
                                    totalParado += hrParado * 60 + minParado;

                                    // total trabalhado liquida
                                    let tempoLiq = dado.hrs_trabalhada_liq.split(':');
                                    let hrLiq = parseInt(tempoLiq[0]);
                                    let minLiq = parseInt(tempoLiq[1]);
                                    totalLiq += hrLiq * 60 + minLiq; 

                                    $('#horas').append(
                                        ' <div class="form-row">' +
                                            '<div class="form-group col-md-2 espaco ">' +
                                                '<label for="mdfe"> Data Ponto: </label>'+
                                                '<input type="date" name="mdfe" id="mdfe" class="form-control" readonly value="'+dado.data_ponto+'">'+
                                            '</div>'+   
                                            '<div class="form-group col-md-2 espaco ">' +
                                                '<label for="hrInicio"> Hora Início: </label>'+
                                                '<input type="time" name="hrInicio" id="hrInicio" class="form-control" readonly value="'+dado.hora_inicio+'">'+
                                            '</div>'+   
                                            '<div class="form-group col-md-2 espaco ">' +
                                                '<label for="hrFinal"> Hora Final: </label>'+
                                                '<input type="time" name="hrFinal" id="hrFinal" class="form-control" readonly value="'+dado.hora_final+'">'+
                                            '</div>'+  
                                            '<div class="form-group col-md-2 espaco ">' +
                                                '<label for="hrTrab">Horas Trabalhada : </label>'+
                                                '<input type="time" name="hrTrab" id="hrTrab" class="form-control" readonly value="'+dado.hrs_trabalhada+'">'+
                                            '</div>'+
                                            '<div class="form-group col-md-2 espaco ">' +
                                                '<label for="tpParado"> Tempo Parado: </label>'+
                                                '<input type="time" name="tpParado" id="tpParado" class="form-control" readonly value="'+dado.hrs_parada+'">'+
                                            '</div>'+         
                                            '<div class="form-group col-md-2 espaco ">' +
                                                '<label for="hrLiq">Horas Trabalhada Líq.: </label>'+
                                                '<input type="time" name="hrLiq" id="hrLiq" class="form-control" readonly value="'+dado.hrs_trabalhada_liq+'">'+
                                            '</div>'+  
                                            '<div class="form-group col-md-12 espaco ">' +
                                                '<label for="hrLiq">Paradas: </label>'+
                                                '<textarea class="form-control" readonly>'+ dado.tempo_parado.replace(/<br>/g, "\n")+ '</textarea>'+
                                            '</div>'+                                                  
                                        '</div><hr>'
                                    );
                                });
                                // console.log(('0' + Math.floor(totalViagemMin/60)).slice(-2) + ':' + ('0' + totalViagemMin%60).slice(-2));
                                $('#formulario').append(
                                    '<div class="form-row">'+
                                        '<div class="form-group col-md-4 espaco ">' +
                                            '<label for="">Total de Horas Trabalhada: </label>'+
                                            '<input type="time" class="form-control" readonly value="'+('0' + Math.floor(totalViagemMin/60)).slice(-2) + ':' + ('0' + totalViagemMin%60).slice(-2)+'">'+
                                        '</div>'+
                                        '<div class="form-group col-md-4 espaco ">' +
                                            '<label for="">Total de Horas Parado: </label>'+
                                            '<input type="time" class="form-control" readonly value="'+('0' + Math.floor(totalParado/60)).slice(-2) + ':' + ('0' + totalParado%60).slice(-2)+'">'+
                                        '</div>'+
                                        '<div class="form-group col-md-4 espaco ">' +
                                            '<label for="">Total de Horas Trabalhada Líq.: </label>'+
                                            '<input type="time" class="form-control" readonly value="'+('0' + Math.floor(totalLiq/60)).slice(-2) + ':' + ('0' + totalLiq%60).slice(-2)+'">'+
                                        '</div>'+
                                    '</div>'
                                );
                            }
                            
                        }
                    });
                });
            
                var actionRow = $('<tr>').addClass('acao-row text-center').append($('<td colspan="' + cells.length + '">').append(btn).append(btnRegistros));

                // Insere o botão e a linha de ação após a linha clicada
                $(this).after(actionRow);

                $.ajax({
                    url: 'winthor.php',
                    data:{
                        acao: 'qtdRegistros',
                        mdfe: rowData[0]
                    },
                    type: 'post',
                    success: function(response){
                        let qtd = JSON.parse(response);
                       
                        if(qtd<1){
                           $('.btn-primary').hide();
                        }
                    }
                });
            })
           
        });
    </script>

<!-- msg de sucesso ou erro -->
<?php
    // Verifique se há uma mensagem de confirmação na sessão
    if (isset($_SESSION['msg']) && isset($_SESSION['icon'])) {
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

<!-- modal listar registros de pontos -->
<div class="modal fade" id="modalPontos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Pontos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="add-tag.php" method="post">
                    <div id="formulario">
                        <div class="form-row">
                            <div class="form-group col-md-2 espaco ">
                                <label for="mdfe"> MDFE</label>
                                <input type="text" name="mdfe" id="mdfe" class="form-control" readonly>
                            </div>
                            <div class="form-group col-md-5 espaco ">
                                <label for="motorista"> Motorista</label>
                                <input type="text"  name="motorista" class="form-control" id="motorista" readonly>
                            </div>
                            <div class="form-group col-md-5 espaco ">
                                <label for="carga"> Carga </label>
                                <input type="text" id="carga" name="carga" class="form-control" readonly> 
                            </div>
                        </div>    
                        <div id="horas">

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
</body>
</html>