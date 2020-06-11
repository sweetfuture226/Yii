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
    <!-- Custom styles for this template -->
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/css/style.css');?>
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/css/style-responsive.css');?>
    <?php //Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/css/jquery.gritter.css');?>
    <!-- customizations -->
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/css/custom.css');?>

  </head>

  <body id="top">

<?php
$data = gmdate("Y-m-d", time()-(3600*27));
$usuario = UserGroupsUser::model()->findByPk(Yii::app()->user->id);
?>
<section id="container" class="">
    <!--header start-->
    <header class="header white-bg">
        <!--logo start-->
        <a href="<?php echo Yii::app()->getHomeUrl(); ?>" class="logo" title="<?= Yii::t('smith', 'Página inicial') ?>" >Viva<span>Smith</span></a>
        <!--logo end-->
    </header>
      <!--header end-->
      <!--main content start-->
    <section id="main-content" style="margin-left:0px;">
        <section class="wrapper site-min-height">
            <div class="row">
                <div class="col-lg-12 title">
                      <!--breadcrumbs start -->

                    <?php $this->widget('zii.widgets.CBreadcrumbs', array(
                        'links'=>array(""),
                        'tagName'=>'ul',
                        'htmlOptions' => array('class' => 'breadcrumb'),
                        'homeLink'=>'<li><i class="icon-home"></i> '. Yii::t('smith','Página em manutenção').'</li>',
                        'separator' => '',
                    )); ?>
                  </div>
              </div>
              <!-- page start-->
              <div class="row">
                <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading">
                            <div class="task-progress">
                                <h1>Servidor em manutenção</h1>
                            </div>
                        <div style="clear:both"></div>
                        </header>
                        <div class="panel-body">
                            <div class="container">
                                <section class="error-wrapper">
                                    <i class="icon-500"></i>
                                    <h2><?= Yii::t('smith','Servidor em manutenção!') ?></h2>
                                    <p> <?= Yii::t('smith','Em breve restabeleceremos o serviço.') ?></br></p>
                                </section>
                            </div>
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
               Copyright &copy; <?php echo date('Y'); ?> <?php echo Yii::t("smith",'por') ?> <a style="color: #FFFFFF"  target="_blank" href="http://vivainovacao.com">Viva Inovação</a>. <span><a style="color: #FFFFFF"  target="_blank" href="<?=Yii::app()->getBaseUrl(false)?>/public/documentos/Termos e Condições.doc"><?php echo Yii::t('smith', 'Termos de uso'); ?></a></span> - <span><a style="color: #FFFFFF"  target="_blank" href="<?=Yii::app()->getBaseUrl(false)?>/public/documentos/Política de Privacidade.doc"><?php echo Yii::t('smith', 'Políticas de privacidade'); ?></a></span><br> <?php echo Yii::t("smith",'Todos os direitos reservados') ?>.
              <a href="#" class="go-top">
                  <i class="icon-angle-up"></i>
              </a>
          </div>
      </footer>
      <!--footer end-->
  </section>

    <?php Yii::app()->clientScript->registerScript('helpers', '
        actionContato = '.CJSON::encode(Yii::app()->createUrl('usuario/contato')).';
        baseUrl = '.CJSON::encode(Yii::app()->baseUrl).';');?>
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/assets/bootstrap-datepicker2/css/bootstrap-datepicker.css');?>

    <!-- js placed at the end of the document so the pages load faster -->
    <?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
    <?php //Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.js', CClientScript::POS_END);?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/bootstrap.min.js', CClientScript::POS_END);?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/sliders.js', CClientScript::POS_END);?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.dcjqaccordion.2.7.js', CClientScript::POS_END, array('class'=>'include'));?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.scrollTo.min.js', CClientScript::POS_END);?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.nicescroll.js', CClientScript::POS_END);?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/respond.min.js', CClientScript::POS_END);?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.validate.min.js', CClientScript::POS_END);?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.validate.messages_pt_BR.js', CClientScript::POS_END);?>
    <!--scripts utilizados anteriormente-->
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/ui/jquery-ui.js', CClientScript::POS_END);?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/ui/jquery.ui.position.js', CClientScript::POS_END);?>
    <?php //Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/ui/jquery.ui.datepicker.js', CClientScript::POS_END);?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/assets/bootstrap-datepicker2/js/bootstrap-datepicker.js', CClientScript::POS_END);?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/forms/jquery.maskMoney.js', CClientScript::POS_END);?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/forms/jquery.maskedinput.min.js', CClientScript::POS_END);?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/forms/chosen.jquery.js', CClientScript::POS_END);?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/forms/autoNumeric.js', CClientScript::POS_END);?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.smartWizard.js', CClientScript::POS_END);?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.MyThumbnail.js', CClientScript::POS_END);?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.boxy.js', CClientScript::POS_END);?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/accounting.js', CClientScript::POS_END);?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/init.js', CClientScript::POS_END);?>
    <!--common script for all pages-->
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/common-scripts.js', CClientScript::POS_END);?>
    <!--customizations-->
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/custom-scripts.js', CClientScript::POS_END);?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/floatThead/dist/jquery.floatThead.js', CClientScript::POS_END); ?>
    <div id="loader_image"></div>
  </body>
</html>
