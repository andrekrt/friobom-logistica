$(document).ready(function(){
    $("input[name='codVeiculo']").blur(function(){
        var $tipoVeiculo = $("input[name='tipoVeiculo']");
        var $placaVeiculo = $("input[name='placaVeiculo']");
        var codVeiculo = $(this).val();

        $.getJSON('pesquisa.php', {codVeiculo},
            function(retorno){
                $tipoVeiculo.val(retorno.tipoVeiculo);
                $placaVeiculo.val(retorno.placaVeiculo);
            }
        );
    });
});