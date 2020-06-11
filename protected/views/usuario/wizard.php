<?php if($passoAtual == 1){ ?>
<script type="text/javascript">
        
        $(window).load(function() {
            document.getElementById("frase").innerHTML = "<?=Yii::t('wizard','Vamos iniciar a configuração inicial para ter acesso completo ao sistema.')?>";
            document.getElementById("iniciar").innerHTML = "<?=Yii::t('wizard','Iniciar')?>";
            $('#bem-vindo').modal('show');
        });
</script>
<?php }
        else{
?>

<script type="text/javascript">
        $(window).load(function() {
            document.getElementById("frase").innerHTML = "<?=Yii::t('wizard','Vamos retomar a configuração do sistema para ter acesso completo.')?>";
            document.getElementById("iniciar").innerHTML = "<?=Yii::t('wizard','Continuar')?>";
            $('#bem-vindo').modal('show');
        });
</script>
        <?php } ?>
<!-- INICIO MODAL -->
<div class="modal fade" id="bem-vindo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                
                <h4 class="modal-title"><?=Yii::t('wizard','Seja Bem Vindo!')?></h4>
            </div>
            <div class="modal-body">
                <p>
                    <?=Yii::t('wizard','Olá')?> <?php echo Yii::app()->user->name; ?>! <span id="frase"> </span>
                </p>
   
            </div>

            <div style="clear: both"></div>
            <div class="modal-footer">
                
                <a data-dismiss="modal"  class="btn btn-success" type="button" id="iniciar"></a>
               
            </div>
        </div>
    </div>
</div>
<!-- FIM MODAL -->

<!-- INICIO MODAL -->
<div class="modal fade" id="salvarPasso" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                
                <h4 class="modal-title"><?=Yii::t('wizard','Wizard - Configuração do Viva Smith')?></h4>
            </div>
            <div class="modal-body">
                <p>
                    <?php echo Yii::app()->user->name; ?>, <span id="frase"><?=Yii::t('wizard','as configurações foram salvas. Favor retornar ao Wizard o mais breve possível para concluir a configuração e poder usurfruir do Viva Smith')?> </span>
                </p>
   
            </div>

            <div style="clear: both"></div>
            <div class="modal-footer">
                
                <a  class="btn btn-success" type="button" id="sair">Sair</a>
               
            </div>
        </div>
    </div>
</div>
<!-- FIM MODAL -->

<!-- INICIO MODAL -->
<div class="modal fade" id="sucessoWizard" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                
                <h4 class="modal-title"><?=Yii::t('wizard','Wizard - Configuração do Viva Smith')?></h4>
            </div>
            <div class="modal-body">
                <p>
                    <?php echo Yii::app()->user->name; ?>, <span id="frase"><?=Yii::t('wizard','as configurações foram realizadas com sucesso.')?>  </span>
                </p>
   
            </div>

            <div style="clear: both"></div>
            <div class="modal-footer">
                
                <a data-dismiss="modal"  class="btn btn-success" type="button" id="acessar"><?=Yii::t('wizard','Acessar o sistema')?></a>
               
            </div>
        </div>
    </div>
</div>
<!-- FIM MODAL -->
<?php echo $this->renderPartial('modalProgramaSites');?>

<div id="wizard" class="swMain projeto">

    <ul class="anchor">
        <li>
            <a id="step1" href="#step-1" class="bordLeft">
               <!-- <label class="stepNumber">1</label>-->
                <span class="stepDesc">
                    <?=Yii::t('wizard','Passo 1')?><br />
                    <small><?=Yii::t('wizard','Alterar senha do perfil')?></small>
                </span>
            </a>
        </li>
        
        <li>
            <a id="step2" href="#step-2" class="bordLeft">
                <!--<label class="stepNumber">3</label>-->
                <span class="stepDesc">
                    <?=Yii::t('wizard','Passo 2')?><br />
                    <small><?=Yii::t('wizard','Programas e Sites')?></small>
                </span>
            </a>
        </li>
        <li>
            <a id="step3" href="#step-3" class="bordLeft">
              <!--  <label class="stepNumber">4</label> -->
                <span class="stepDesc">
                    <?=Yii::t('wizard','Passo 3')?><br />
                    <small><?=Yii::t('wizard','Parâmetros gerais')?></small>
                </span>
            </a>
        </li>
	<li>
            <a id="step4" href="#step-4" class="bordLeft">
                <!--<label class="stepNumber">5</label>-->
                <span class="stepDesc">
                    <?=Yii::t('wizard','Passo 4')?><br />
                    <small><?=Yii::t('wizard','Cadastrar equipes')?></small>
                </span>
            </a>
        </li>
	<li>
            <a id="step5" href="#step-5" class="bordLeft">
               <!-- <label class="stepNumber">6</label>-->
                <span class="stepDesc">
                    <?=Yii::t('wizard','Passo 5')?><br />
                    <small><?=Yii::t('wizard','Cadastrar colaboradores')?></small>
                </span>
            </a>
        </li>
    </ul>


    <div class="stepContainer">
        <div id="step-1" class="wContent" style="display: block; left: 0px; ">	
            <h2 class="StepTitle"><?=Yii::t('wizard','Alterar senha do perfil Administrador')?></h2><br />
            <?php
            echo $this->renderPartial('_form_wizard_alterar_senha');
            ?>

        </div>
        <div id="step-2" class="wContent" style="display: none; ">
            <h2 class="StepTitle"> <?=Yii::t('wizard','Programas e Sites')?></h2><br />
            <?php
            echo $this->renderPartial('_form_wizard_p_s_permitidos');
            ?>
        </div>
        <div id="step-3" class="wContent" style="display: none; ">
            <h2 class="StepTitle"> <?=Yii::t('wizard','Parâmetros gerais')?></h2><br />
            <?php
            echo $this->renderPartial('_form_wizard_parametros' , array('modelParametros'=>$modelParametros));
            ?>
        </div>
        
        <div id="step-4" class="wContent" style="display: none; height: ">
            <h2 class="StepTitle"><?=Yii::t('wizard','Cadastro de equipes')?></h2><br />
            <?php
            echo $this->renderPartial('_form_wizard_equipe',array("model"=>$modelEquipe));
            ?>
        </div>
        <div id="step-5" class="wContent" style="display: none; height:  ">
            <h2 class="StepTitle"> <?=Yii::t('wizard','Cadastro de colaboradores')?></h2><br />
            <?php
            echo $this->renderPartial('_form_wizard_colaboradores',array("model"=>$model));
            ?>
        </div>
        
    </div>
</div>

<?php $this->widget('ext.widgets.loading.LoadingWidget'); ?>



<script>

    $('#cliente-cadastro-form').submit(function(e) {
        e.preventDefault(); //CANCELA SUBMIT DO FORM
        var nome = $('#Cliente_nome').val();
        var tipo = $('#Cliente_tipo').val();
        if ($.trim(nome) != '') {
            $.post("/cliente/create4Projeto/",
                    {Cliente: "" + nome + "",
                        Tipo: "" + tipo + ""
                    }
            , function(id) { // Do an AJAX call      
                if (id != 'error') {
                    carregarCliente(id, nome);
                    $(".close").click();
                }
            });
        }
        else{
            document.getElementById('message').innerHTML = "O campo nome está vazio!";
            $('#btn_modal_open').click();
        }

    });
    
    $('#sair').click(function(){
        window.location.href = baseUrl + '/userGroups/user/logout';
    });

    $('#acessar').click(function(){
        window.location.href = baseUrl + '/metrica';
        Loading.show();
    });


</script>
<?php

?>
<?php
//Formulário de controle




?>

<?php
Yii::app()->clientScript->registerScript('projeto', 'baseUrl = ' . CJSON::encode(Yii::app()->baseUrl) . ';
        passo_atual = ' . ($passoAtual - 1) . ';
            $("body").addClass("not_show_loading");', CClientScript::POS_BEGIN);
$cs = Yii::app()->clientScript;
$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/js/decor_wizard/gerenciar_passos.js', CClientScript::POS_BEGIN);
$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/js/decor_wizard/wizard_comportamento.js', CClientScript::POS_BEGIN);
$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/js/decor_wizard/template_produto.js', CClientScript::POS_BEGIN);
$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/js/decor_wizard/template_projeto_has_ambiente.js', CClientScript::POS_BEGIN);
?>

