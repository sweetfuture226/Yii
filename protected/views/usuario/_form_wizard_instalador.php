<!--<div class="form">-->


<div class="form-group  col-lg-8">
    <span><?= Yii::t('smith', 'Clique no botão abaixo para fazer o download do instalador para as máquinas a serem monitoradas pelo Unofoco') ?>.</span>
	<br>

	<p>
		<a href="../public/instalador/Smith-2.0.exe" class="btn btn-success" style="text-align: center; margin-top: 30px;"><?= Yii::t('smith', 'Baixar Instalador') ?></a>
	</p>
</div>

<fieldset>
    <legend><?= Yii::t('smith', 'Acompanhamento da Instalação') ?></legend>
    <p style="margin-left: 10px;"><?= Yii::t('smith', 'Os seguintes computadores já foram instalados') ?>: </p>
    <div style="font-size: 14px; font-weigh: bold" id="novos">
        <img src= "http://localhost/unofoco/app.unofoco.com/themes/flatlab/img/loading.gif" id="loading" alt="loading" style="display:none;" />
    </div>
</fieldset>

<!--<p class="note">Campos com <span class="required">*</span> são obrigatórios.</p>






<!--</div><!-- form -->
<script>
    /*function buscarCliente(string) {
        var sugestoes = $("#sugestoes_clientes");
        if (string == '') {
            sugestoes.fadeOut();
        }
        else {
            $.post("/cliente/getClienteDinamico",
                    {string: "" + string + ""
                    }
            , function(data) { // Do an AJAX call        
                sugestoes.html(data).fadeIn();

            });
        }
    }

    function carregarCliente(id, string) {
        string = string.trim();
        var cliente_nome = $('#cliente_nome')
        $('#cliente_nome').val(string);
        $('#DecorProjeto_cliente_id').val(id);
        var sugestoes = $("#sugestoes_clientes");
        sugestoes.fadeOut();
        //cliente_nome.prop('disabled',true);  
        cliente_nome.addClass('nome_click');
        $('#DecorProjeto_nome').focus();
    }
    
    function criarCliente(){
        $('#Cliente_nome').val($('#cliente_nome').val());
        var dialogo = $("#mydialog2");
        
        dialogo.dialog('open');
        dialogo.css({'z-index':'100'});
        var dialogo2 = $(".ui-dialog");
        dialogo2.css({'z-index':'100'});
    }
    $(document).ready(function() {
        $('#cliente_nome').click(function() {
            $('#cliente_nome').val('');
            $('#DecorProjeto_cliente_id').val('');
            var sugestoes = $("#sugestoes_clientes");
            sugestoes.fadeOut();
        });
    });*/

(function($)
{
    $(document).ready(function()
    {
        $.ajaxSetup(
        {
            cache: false,
            beforeSend: function() {
                $('#novos').hide();
                $('#loading').show();
            },
            complete: function() {
                $('#loading').hide();
                $('#novos').show();
            },
            success: function() {
                $('#loading').hide();
                $('#novos').show();
            }
        });
        var $container = $("#novos");
        $container.load("buscarColaboradores");
        var refreshId = setInterval(function()
        {
            $container.load('buscarColaboradores');
            
        }, 9000);
    });
})(jQuery);
</script>
