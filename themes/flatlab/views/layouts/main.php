<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Mosaddek">
    <meta name="keyword" content="FlatLab, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
    <link rel="shortcut icon" href="<?php echo Yii::app()->theme->baseUrl.'/img/favicon.ico' ?>">

    <title>Viva Smith - <?php echo CHtml::encode($this->pageTitle); ?></title>
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/assets/jquery-ui/jquery-ui-1.10.1.custom.css');?>
    <!-- Bootstrap core CSS -->
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/css/bootstrap.min.css');?>
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/css/bootstrap-reset.css');?>
    <!--external css-->
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/assets/font-awesome/css/font-awesome.css');?>
    <!--dynamic table-->
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/assets/advanced-datatable/media/css/demo_page.css'); ?>
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/assets/advanced-datatable/media/css/demo_table.css'); ?>
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/assets/data-tables/DT_bootstrap.css'); ?>
    <!-- Bootstrap styling for Typeahead -->
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/assets/sliptree-tokenfield/dist/css/tokenfield-typeahead.css'); ?>
    <!-- Tokenfield CSS -->
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/assets/sliptree-tokenfield/dist/css/bootstrap-tokenfield.css'); ?>
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/assets/sliptree-tokenfield/docs-assets/css/pygments-manni.css'); ?>
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/assets/sliptree-tokenfield/docs-assets/css/docs.css'); ?>
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/assets/nestable/jquery.nestable.css'); ?>
    <!-- Custom styles for this template -->
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/css/style.css');?>
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/css/style-responsive.css');?>
    <!-- customizations -->
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/css/custom.css');?>
    <!-- Jcrop styling -->
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/protected/vendors/Jcrop/css/jquery.Jcrop.min.css'); ?>

</head>

<body id="top">

<?php
$data = gmdate("Y-m-d", time() - (3600 * 27));
$usuario = UserGroupsUser::model()->findByPk(Yii::app()->user->id);
?>

<input type="hidden" name="grupo" value="<?php echo Yii::app()->user->groupName ?>">

<section id="container" class="">
    <!--header start-->
    <header class="header white-bg">
        <!--Topo esquerdo-->
        <?php $this->renderPartial('//site/topNavLeft'); ?>
        <!--flash messagest-->
        <?php $this->renderPartial('//site/flashMessages'); ?>
        <!--Topo direito-->
        <?php $this->renderPartial('//site/topNavRight'); ?>
    </header>
    <!--header end-->
    <!--sidebar start-->
    <aside>
        <div id="sidebar"  class="nav-collapse ">
            <!-- sidebar menu start-->
            <ul class="sidebar-menu" id="nav-accordion">
                <?php $tipo_empresa = EmpresaHasParametro::model()->findByAttributes(array("fk_empresa" => $usuario->fk_empresa));
                $tipo_empresa = $tipo_empresa->tipo_empresa;
                ?>
                <!--MENUS-->
                <?php switch (Yii::app()->user->groupName) {
                    case 'root':
                        $this->renderPartial('//site/menuRoot');
                        break;
                    case 'empresa':
                        $this->renderPartial('//site/menuEmpresa',array('tipo_empresa'=>$tipo_empresa,'usuario'=>$usuario));
                        break;
                    case 'demo':
                        $this->renderPartial('//site/menuDemo',array('tipo_empresa'=>$tipo_empresa,'usuario'=>$usuario));
                        break;
                    case 'coordenador':
                        $this->renderPartial('//site/menuCoordenador',array('tipo_empresa'=>$tipo_empresa,'usuario'=>$usuario));
                        break;
                } ?>
            </ul>
            <!-- sidebar menu end-->
        </div>
    </aside>
    <!--sidebar end-->
    <!--main content start-->

    <section id="main-content">
        <section class="wrapper site-min-height">

            <!--TODO: Descomentar para retornar o menu superior das metricas.-->
            <?php
            $action = Yii::app()->getController()->getAction()->id;
            $controller = Yii::app()->getController()->id;
            if ($action == 'index' && $controller == 'dashboard') { ?>
                <div class="row">
                    <?php $this->renderPartial('/metrica/menuTop'); ?>
                </div>
            <?php } ?>
            <div class="row">
                <!--MODALS DIVERSOS -->
                <?php $this->renderPartial('//site/modals'); ?>
                <!--MODAL CONTATO -->
                <?php $this->renderPartial('//site/modalContato'); ?>
                <!--MODAL CHANGELOG -->
                <?php /*$this->renderPartial('//site/modalChangeLog'); */ ?>
                <!--MODAL ALTERAR SENHA -->
                <?php $this->renderPartial('//site/modalAlterarSenha'); ?>
                <!-- MODAL DEFAUL -->
                <?php $this->renderPartial('//site/modalDefault'); ?>
                <!-- MODAL DELETAR -->
                <?php $this->renderPartial('//site/modalDelete'); ?>
                <div class="col-lg-12 title">
                    <!--breadcrumbs start -->
                    <?php if(isset($this->breadcrumbs)):?>
                        <?php if ($this->breadcrumbs) {?>
                            <?php $this->widget('zii.widgets.CBreadcrumbs', array(
                                'links'=>$this->breadcrumbs,
                                'tagName'=>'ul',
                                'htmlOptions' => array('class' => 'breadcrumb'),
                                'homeLink'=>'<li><a href="'.Yii::app()->homeUrl.'"><i class="icon-home"></i> '. Yii::t('smith','Página Inicial').'</a></li>',
                                'activeLinkTemplate'=>'<li><a href="{url}">{label}</a></li>',
                                'separator' => '',
                                'inactiveLinkTemplate'=>'<li class="active">{label}</li>'
                            )); ?>
                        <?php }
                        else{
                            echo '<div class="breadcrumb">'.CHtml::encode($this->pageTitle).'</div>';
                        }?>
                    <?php endif?>
                </div>
            </div>
            <!-- page start-->
            <div class="row">
                <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading">
                            <div class="task-progress">
                                <h1><?= $this->title_action ?></h1>
                            </div>
                            <div style="clear:both"></div>
                        </header>
                        <div class="panel-body">
                            <?php echo $content; ?>
                        </div>
                    </section>
                </div>
            </div>
            <!-- page end-->
        </section>
    </section>

    <!--main content end-->
    <!--footer start-->
    <footer class="site-footer">
        <div class="text-center">
            Copyright &copy; <?php echo date('Y'); ?> <?php echo Yii::t("smith", 'por') ?> <a style="color: #FFFFFF"
                                                                                              target="_blank"
                                                                                              href="http://vivainovacao.com">Viva
                Inovação</a>. <span><a style="color: #FFFFFF" target="_blank"
                                       href="<?php echo Yii::app()->baseUrl; ?>/termosDeUso"><?php echo Yii::t('smith', 'Termos de uso'); ?></a></span>
            - <span><a style="color: #FFFFFF" target="_blank"
                       href="<?php echo Yii::app()->baseUrl; ?>/politicaPrivacidade"><?php echo Yii::t('smith', 'Política de privacidade'); ?></a></span><br> <?php echo Yii::t("smith", 'Todos os direitos reservados') ?>
            .
            <a href="#" class="go-top">
                <i class="icon-angle-up"></i>
            </a>
        </div>
    </footer>
    <!--footer end-->
</section>

<script>


    function enviarEmail(){
        var contato = $("#contato").val();
        var anexo = $("#nameFile").val();

        if(contato == "" || anexo == ""){
            $('#error-reportar').show();
        }else{
            $('#error-reportar').hide();
            $.ajax({
                type: 'POST',
                //dataType: 'JSON',
                data: {contato: contato, anexo: anexo },
                url: actionContato,
                success: function(data){
                    $("#success-reportar").show();
                    setTimeout(function(){
                        $('.close').click();
                    },3000);
                }
            });
        }
    }
</script>

<!-- Set baseUrl and actionContato -->
<?php Yii::app()->clientScript->registerScript('helpers', '
    actionContato = '.CJSON::encode(Yii::app()->createUrl('usuario/contato')).';
    baseUrl = '.CJSON::encode(Yii::app()->baseUrl).';', CClientScript::POS_HEAD);?>

<?php Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/assets/bootstrap-datepicker2/css/bootstrap-datepicker.css');?>

<!-- js placed at the end of the document so the pages load faster -->
<?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
<?php //Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.js', CClientScript::POS_END);?>

<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/sliders.js', CClientScript::POS_END);?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.dcjqaccordion.2.7.js', CClientScript::POS_END, array('class'=>'include'));?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.scrollTo.min.js', CClientScript::POS_END);?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.nicescroll.js', CClientScript::POS_END);?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/assets/nestable/jquery.nestable.js', CClientScript::POS_END);?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/respond.min.js', CClientScript::POS_END);?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.validate.min.js', CClientScript::POS_END);?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.validate.messages_pt_BR.js', CClientScript::POS_END);?>
<!--scripts utilizados anteriormente-->
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/ui/jquery-ui.js', CClientScript::POS_END);?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/ui/jquery.ui.position.js', CClientScript::POS_END);?>
<!--Tokenfield import-->
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/assets/sliptree-tokenfield/dist/bootstrap-tokenfield.js', CClientScript::POS_END); ?>
<?php /*Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/assets/sliptree-tokenfield/docs-assets/js/scrollspy.js', CClientScript::POS_END); */ ?><!--
<?php /*Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/assets/sliptree-tokenfield/docs-assets/js/affix.js', CClientScript::POS_END); */ ?>
<?php /*Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/assets/sliptree-tokenfield/docs-assets/js/typeahead.bundle.min.js', CClientScript::POS_END); */ ?>
--><?php /*Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/assets/sliptree-tokenfield/docs-assets/js/docs.min.js', CClientScript::POS_END); */ ?>

<?php //Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/ui/jquery.ui.datepicker.js', CClientScript::POS_END);?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/assets/bootstrap-datepicker2/js/bootstrap-datepicker.js', CClientScript::POS_END);?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/assets/advanced-datatable/media/js/jquery.dataTables.js', CClientScript::POS_END); ?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/assets/data-tables/DT_bootstrap.js', CClientScript::POS_END); ?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/forms/jquery.maskMoney.js', CClientScript::POS_END);?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.mask.js', CClientScript::POS_END);?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/forms/chosen.jquery.js', CClientScript::POS_END);?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/forms/autoNumeric.js', CClientScript::POS_END);?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.smartWizard.js', CClientScript::POS_END);?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.MyThumbnail.js', CClientScript::POS_END);?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.boxy.js', CClientScript::POS_END);?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/accounting.js', CClientScript::POS_END);?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/dynamic-table.js', CClientScript::POS_END); ?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/init.js', CClientScript::POS_END);?>
<!--common script for all pages-->
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/common-scripts.js', CClientScript::POS_END);?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/nestable.js', CClientScript::POS_END);?>
<!--customizations-->
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/custom-scripts.js', CClientScript::POS_END); ?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/bootstrap.min.js', CClientScript::POS_END); ?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/highcharts/data.js', CClientScript::POS_END); ?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/highcharts/exporting.js', CClientScript::POS_END); ?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/code-prettify-master/loader/run_prettify.js', CClientScript::POS_END); ?>
<div id="loader_image"></div>
</body>
</html>
