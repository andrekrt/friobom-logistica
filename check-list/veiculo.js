$(document).ready(function(){
    $("input[name='placa']").blur(function(){
        var $tipoVeiculo = $("input[name='tipoVeiculo']");
        var placaVeiculo = $(this).val();

        $.getJSON('pesquisa.php', {placaVeiculo},
            function(retorno){
                $tipoVeiculo.val(retorno.tipoVeiculo);
            }
        );
    });
});