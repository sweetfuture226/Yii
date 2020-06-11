<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!--        <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;">-->
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>

        <?php Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/css/main.css');?>
        <?php Yii::app()->clientScript->registerCssFile('http://fonts.googleapis.com/css?family=Cuprum');?>

        <?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
        <?//php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery-ui-1.8.17.custom.min.js');?>
        <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/ui/jquery-ui.js');?>
        <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/ui/jquery.ui.position.js');?>
        <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/ui/jquery.ui.datepicker.js');?>
        
        <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/forms/jquery.validationEngine-pt.js');?>
        <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/forms/jquery.validationEngine.js');?>
        <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/forms/jquery.maskMoney.js');?>
        <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/forms/jquery.maskedinput.min.js');?>
        <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/forms/chosen.jquery.js');?>
        <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/forms/jquery.ui.datepicker-pt-BR.js');?>
        <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/forms/autoNumeric.js');?>
                
        <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.smartWizard.js');?>
        <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.collapsible.min.js');?>
        <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.ToTop.js');?>
        <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.listnav.js');?>
        <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.boxy.js');?>
        <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/accounting.js');?>

        <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.MyThumbnail.js');?>
        <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/lightbox-2.6.min.js');?>
        
        <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/init.js');?>
        
    </head>

    <body>

        <!-- Top navigation bar -->
        <div id="topNav">
            <div class="fixed_position">
                <div class="wrapper">
                    <div class="welcome"><a href="#" title=""><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/userPic.png" alt=""></a><span>Olá, <?= Yii::app()->user->name ?></span></div>
                    <div class="userNav">
                        <ul>
                            <li><a href="<?php echo Yii::app()->createUrl('userGroups/user/profile', array('id'=>Yii::app()->user->id)); ?>" title=""><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/icons/topnav/profile.png" alt=""><span>Perfil</span></a></li>
                            <li><a href="<?php echo Yii::app()->baseUrl; ?>/userGroups/user/logout" title=""><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/icons/topnav/logout.png" alt=""><span>Sair</span></a></li>
                        </ul>
                    
                </div>
            </div>
        </div>

        <!-- Content wrapper -->
        <div class="wrapper">

                <!-- Left navigation -->
            <div class="leftNav">
                <div class="logo"><a href="<?php echo Yii::app()->getHomeUrl(); ?>" title="Página Inicial"><img src="<?php echo Yii::app()->theme->baseUrl   ; ?>/images/logo_decore.png" width="150px" alt="" /></a></div>
                    <ul id="menu">
                        <?php switch (Yii::app()->user->groupName) {
                                case 'funcionario': ?>
                                    <li class=""><a href="#" title="" class="exp inactive collapse-close"><span>Decoração</span></a>
                                        <ul class="sub" style="display: none;">
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/decorProjeto/create" title="">Criar Projeto</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/decorProjeto" title="">Ver Projetos</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/decorCotacao" title="">Cotações</a></li>
                                        </ul>
                                    </li>
                                    <li class=""><a href="#" title="" class="exp inactive collapse-close"><span>Escritório</span></a>
                                        <ul class="sub" style="display: none;">
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/contaPagar" title="">Contas a Pagar</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/contaReceber" title="">Contas a Receber</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/notaFiscal" title="">Notas Fiscais</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/contaFinanceira" title="">Contas financeiras</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/patrimonio" title="">Patrimônio</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/funcionario" title="">Funcionários</a></li>
                                        </ul>
                                    </li>
                                    <li class=""><a href="#" title="" class="exp inactive collapse-close"><span>Cadastros</span></a>
                                        <ul class="sub" style="display: none;">
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/cliente" title="">Clientes</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/fornecedor" title="">Fornecedores</a></li>
                                        </ul>
                                    </li>
                                    <li class=""><a href="#" title="" class="exp inactive collapse-close"><span>Configurações</span></a>
                                        <ul class="sub" style="display: none; ">
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/decorTipoAmbiente" title="">Ambientes</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/cargo" title="">Cargos</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/centroCusto" title="">Centros de Custo</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/usuario" title="">Gerenciar Usuários</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/ramoAtuacao" title="">Ramos de atuação</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/decorSegmento" title="">Segmentos</a></li>     
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/subcategoriaCentroCusto" title="">Subcategorias de Centro de Custo</a></li>                                            
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/decorTipoTipologia" title="">Tipologias</a></li>                                                                                                                               
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/tipoDocumento" title="">Tipos de documento</a></li>
                                        </ul>
                                    </li>
                        <?php break;
                                case 'diretoria': ?>
                                    <li class=""><a href="#" title="" class="exp inactive collapse-close"><span>Decoração</span></a>
                                        <ul class="sub" style="display: none;">
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/decorProjeto/create" title="">Criar Projeto</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/decorProjeto" title="">Ver Projetos</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/decorCotacao" title="">Cotações</a></li>
                                        </ul>
                                    </li>
                                    <li class=""><a href="#" title="" class="exp inactive collapse-close"><span>Escritório</span></a>
                                        <ul class="sub" style="display: none;">
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/contaPagar" title="">Contas a Pagar</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/contaReceber" title="">Contas a Receber</a></li>                                            
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/contaFinanceira" title="">Contas financeiras</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/funcionario" title="">Funcionários</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/notaFiscal" title="">Notas Fiscais</a></li>                                           
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/patrimonio" title="">Patrimônio</a></li>                                            
                                        </ul>
                                    </li>
                                        <li class=""><a href="#" title="" class="exp inactive collapse-close"><span>Relatórios</span></a>
                                        <ul class="sub" style="display: none; ">
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/contaReceber/acompanhamentoParcelas" title="">Acompanhamento de parcelas</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/decorProjeto/relatorio" title="">Faturamento por Projeto</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/contaFinanceira/fluxoCaixa" title="">Fluxo de Caixa</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/patrimonio/relatorio/1" title="" target="_blank">Itens do patrimônio</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/pagamento/relatorio" title="">Pagamentos</a></li>
                                            
                                        </ul>
                                    </li>
                                    <li class=""><a href="#" title="" class="exp inactive collapse-close"><span>Cadastros</span></a>
                                        <ul class="sub" style="display: none;">
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/cliente" title="">Clientes</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/fornecedor" title="">Fornecedores</a></li>
                                        </ul>
                                    </li>
                                    <li class=""><a href="#" title="" class="exp inactive collapse-close"><span>Configurações</span></a>
                                        <ul class="sub" style="display: none; ">
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/decorTipoAmbiente" title="">Ambientes</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/cargo" title="">Cargos</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/centroCusto" title="">Centros de Custo</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/usuario" title="">Gerenciar Usuários</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/ramoAtuacao" title="">Ramos de atuação</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/decorSegmento" title="">Segmentos</a></li>     
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/subcategoriaCentroCusto" title="">Subcategorias de Centro de Custo</a></li>                                            
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/decorTipoTipologia" title="">Tipologias</a></li>                                                                                                                               
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/tipoDocumento" title="">Tipos de documento</a></li>
                                            
                                            
                                        </ul>
                                    </li>
                        <?php break;
                                case 'decoreadmin':
                                case 'root': ?>
                                    <li class=""><a href="#" title="" class="exp inactive collapse-close"><span>Decoração</span></a>
                                        <ul class="sub" style="display: none;">
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/decorProjeto/create" title="">Criar Projeto</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/decorProjeto" title="">Ver Projetos</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/decorCotacao" title="">Cotações</a></li>
                                        </ul>
                                    </li>
                                    <li class=""><a href="#" title="" class="exp inactive collapse-close"><span>Escritório</span></a>
                                        <ul class="sub" style="display: none;">
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/contaPagar" title="">Contas a Pagar</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/contaReceber" title="">Contas a Receber</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/notaFiscal" title="">Notas Fiscais</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/contaFinanceira" title="">Contas financeiras</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/patrimonio" title="">Patrimônio</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/funcionario" title="">Funcionários</a></li>
                                        </ul>
                                    </li>
                                        <li class=""><a href="#" title="" class="exp inactive collapse-close"><span>Relatórios</span></a>
                                        <ul class="sub" style="display: none; ">
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/contaReceber/acompanhamentoParcelas" title="">Acompanhamento de parcelas</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/contaFinanceira/fluxoCaixa" title="">Fluxo de Caixa</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/patrimonio/relatorio/1" title="" target="_blank">Itens do patrimônio</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/pagamento/relatorio" title="">Pagamentos</a></li>
                                        </ul>
                                    </li>
                                    <li class=""><a href="#" title="" class="exp inactive collapse-close"><span>Cadastros</span></a>
                                        <ul class="sub" style="display: none;">
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/cliente" title="">Clientes</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/fornecedor" title="">Fornecedores</a></li>
                                        </ul>
                                    </li>
                                    <li class=""><a href="#" title="" class="exp inactive collapse-close"><span>Configurações</span></a>
                                        <ul class="sub" style="display: none; ">
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/decorTipoAmbiente" title="">Ambientes</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/cargo" title="">Cargos</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/centroCusto" title="">Centros de Custo</a></li>                                            
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/usuario" title="">Gerenciar Usuários</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/ramoAtuacao" title="">Ramos de atuação</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/decorSegmento" title="">Segmentos</a></li>     
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/subcategoriaCentroCusto" title="">Subcategorias de Centro de Custo</a></li>                                            
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/decorTipoTipologia" title="">Tipologias</a></li>                                                                                                                               
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/tipoDocumento" title="">Tipos de documento</a></li>
                                        </ul>
                                    </li>
                                    <li class=""><a href="#" title="" class="exp inactive collapse-close"><span>Decore</span></a>
                                        <ul class="sub" style="display: none; ">
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/configuracoesGerais" title="">Configurações Gerais</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/empresa" title="">Empresas</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/decorProduto" title="">Produtos</a></li>
                                            
                                        </ul>
                                    </li>
                        <?php } ?>
                    </ul>
            </div>

            <!-- Content -->
            <div class="content">
                <div id="flash_message" style="margin: 11px 0px;">
                    <?php if(Yii::app()->user->hasFlash('error')): ?>
                        <div class="nNote nFailure hideit">
                            <p><?php echo Yii::app()->user->getFlash('error'); ?></p>
                        </div>
                    <?php endif; ?>

                    <?php if(Yii::app()->user->hasFlash('success')): ?>
                        <div class="nNote nSuccess hideit">
                            <p><?php echo Yii::app()->user->getFlash('success'); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="title">
                    <h5>
                        <?php if(isset($this->breadcrumbs)):?>
                            <?php if ($this->breadcrumbs) {?>
                                 <?php $this->widget('zii.widgets.CBreadcrumbs', array(
                                        'links'=>$this->breadcrumbs,
                                )); ?><!-- breadcrumbs -->
                             <?php }
                             else{
                                 echo CHtml::encode($this->pageTitle);
                             }?>

                        <?php endif?>
                    </h5>
                </div>
                <?php if ((Yii::app()->user->groupName=='diretoria' || Yii::app()->user->groupName=='decoreadmin') && ($this->getAction()->getController()->getRoute()=='diretoria/index')){ ?>
                    <?php echo $content; ?>
                    <div class="fix"></div>
                <?php } else {?>
                    <div class="widget first">
                       <div class="head"><h5 class="iList"><?= $this->title_action ?></h5></div>
                       <?php echo $content; ?>
                       <div class="fix"></div>
                    </div>
                <?php }?>
            
        </div>

        <!-- Footer -->
        <div id="footer">
                <div class="wrapper">
                <span>Copyright &copy; <?php echo date('Y'); ?> por Viva Inovação. Todos os direitos reservados.</span>
            </div>
        </div>

        <a href="#" id="toTop" style="display: none; ">
            <span id="toTopHover"></span>To Top
        </a>
        <div id="loader_image"></div>
    </body>
</html>
<?php
    if (Yii::app()->user->groupName=='diretoria'){
        $saldo_total = ContaFinanceira::model()->getSaldoTotal();
        $caixa = ContaFinanceira::model()->getSaldoCaixa();
        $banco = $saldo_total - $caixa;
        
        
        Yii::app()->clientScript->registerScript('saldo_caixa', '
            $("div.title").prepend(\'<div class="saldo_diario" style="float: right;"><h5>Saldo em Caixa: R$ '.number_format($caixa, 2, ',', '.').'</h5></div>\');
        ');
        Yii::app()->clientScript->registerScript('saldo_banco', '
            $("div.title").prepend(\'<div class="saldo_diario" style="float: right;"><h5>Saldo em Banco: R$ '.number_format($banco, 2, ',', '.').'</h5></div>\');
        ');
        Yii::app()->clientScript->registerScript('saldo_diario', '
            $("div.title").prepend(\'<div class="saldo_diario" style="float: right;"><h5>Total: R$ '.number_format($saldo_total, 2, ',', '.').'</h5></div>\');
        ');
    }
?>
