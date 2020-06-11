<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Mosaddek">
    <meta name="keyword" content="FlatLab, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
    <link rel="shortcut icon" href="<?php echo Yii::app()->theme->baseUrl.'/img/favicon.png' ?>">

    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
	<?php Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/assets/jquery-ui/jquery-ui-1.10.1.custom.css');?>
    <!-- Bootstrap core CSS -->
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/css/bootstrap.min.css');?>
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/css/bootstrap-reset.css');?>
    <!--external css-->
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/assets/font-awesome/css/font-awesome.css');?>
    <!-- Custom styles for this template -->
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/css/style.css');?>
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/css/style-responsive.css');?>
    <!-- customizations -->
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/css/custom.css');?>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
    <!--[if lt IE 9]>
        <script src="<?php echo Yii::app()->theme->baseUrl.'/js/html5shiv.js' ?>"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl.'/js/respond.min.js' ?>"></script>
    <![endif]-->
  </head>

  <body>
<?php
$data = gmdate("Y-m-d", time()-(3600*27));

$colaboradores = ColaboradorSemProdutividade::model()->findAll(array("condition" => "fk_empresa=2 AND data like '$data'"));
 //dd($colaboradores);


?>
  <section id="container" class="">
      <!--header start-->

      <!--header end-->
      <!--sidebar start-->

      <!--sidebar end-->
      <!--main content start-->

      <section id="main-content" style="margin: 0 auto">
          <section class="">
              <?php
      $action = Yii::app()->getController()->getAction()->id;
      $controller = Yii::app()->getController()->id;

      if ($action =='index' && $controller=='logAtividade'){
        //Overview Produtividade Mensal Corrente
          $prdGeral = LogAtividade::model()->getPrdGeralHome();
            $duracaoLog = LogAtividade::model()->getTempoPrdGeralHome();
            $prdMesEmpresa = round(($duracaoLog[0]['duracao_periodo']*100) / $prdGeral[0]['hora_dia'],0);

        //OverView quantidade de usuários
            $usuario = UserGroupsUser::model()->findByPk(Yii::app()->user->id);
          $qtdUsuarios = ProPessoa::model()->findAll(array("condition" => "fk_empresa=" . $usuario->fk_empresa));
            $qtdUsuarios = count($qtdUsuarios);


          //OverView Variação de Produtividade
          $prdGeralMesAnterior = LogAtividade::model()->getPrdGeralHome('variacao');
            $duracaoVar = LogAtividade::model()->getTempoPrdGeralHome('variacao');

          $varMes = round(($duracaoVar[0]['duracao_periodo'] * 100) / $prdGeralMesAnterior[0]['hora_dia'], 0);


          ?>
              <div class="row state-overview">
                  <div class="col-lg-3 col-sm-6">
                      <section class="panel">
                          <div class="symbol terques">
                              <i class="icon-book"></i>
                          </div>
                          <div class="value">
                              <h1 class="count"><?php echo $prdMesEmpresa.'%' ?></h1>
                              <p>Produtividade Mês Corrente</p>
                          </div>
                      </section>
                  </div>
                  <div class="col-lg-3 col-sm-6">
                      <section class="panel">
                          <div class="symbol blue">
                              <i class="icon-bar-chart"></i>
                          </div>
                          <div class="value">
                              <h1 class=" count4"><?php echo $prdMesEmpresa - $varMes .'%'; ?></h1>
                              <p>Variação de Produtividade</p>
                          </div>
                      </section>
                  </div>
                  <div class="col-lg-3 col-sm-6">
                      <section class="panel">
                          <div class="symbol red">
                              <i class="icon-tasks"></i>
                          </div>
                          <div class="value">
                              <h1 class=" count2"><?php echo $qtdUsuarios; ?></h1>
                              <p>Colaboradores Ativos</p>
                          </div>
                      </section>
                  </div>
                  <div class="col-lg-3 col-sm-6">
                      <section class="panel">
                          <div class="symbol yellow">
                              <i class="icon-ok-sign"></i>
                          </div>
                          <div class="value">
                              <h1 class=" count3">0</h1>
                              <p>Indicações</p>
                          </div>
                      </section>
                  </div>

              </div>
      <?php } ?>
              <div class="row">

              </div>
              <!-- page start-->
              <div class="row">
                    <?php if ((Yii::app()->user->groupName=='diretoria' || Yii::app()->user->groupName=='decoreadmin') && ($this->getAction()->getController()->getRoute()=='diretoria/index')){ ?>
                        <?php echo $content; ?>
                    <?php } else {?>
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
                    <?php }?>
              </div>
              <!-- page end-->
          </section>
      </section>
      <!--main content end-->
      <!--footer start-->

      <!--footer end-->
  </section>

<?php Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/assets/bootstrap-datepicker/css/datepicker.css'); ?>

    <!-- js placed at the end of the document so the pages load faster -->
    <?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
    <?php //Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.js', CClientScript::POS_END);?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/bootstrap.min.js', CClientScript::POS_END);?>
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
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/assets/bootstrap-datepicker/js/bootstrap-datepicker.js', CClientScript::POS_END);?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/forms/jquery.maskMoney.js', CClientScript::POS_END);?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/forms/jquery.maskedinput.min.js', CClientScript::POS_END);?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/forms/chosen.jquery.js', CClientScript::POS_END);?>
    <?php //Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/forms/jquery.ui.datepicker-pt-BR.js', CClientScript::POS_END);?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/forms/autoNumeric.js', CClientScript::POS_END); ?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.smartWizard.js', CClientScript::POS_END);?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.MyThumbnail.js', CClientScript::POS_END);?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.boxy.js', CClientScript::POS_END);?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/accounting.js', CClientScript::POS_END);?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/init.js', CClientScript::POS_END);?>
    <!--common script for all pages-->
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/common-scripts.js', CClientScript::POS_END);?>
    <!--customizations-->
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/custom-scripts.js', CClientScript::POS_END);?>

  </body>
</html>
<?php
//    if (Yii::app()->user->groupName=='diretoria'){
//        $saldo_total = ContaFinanceira::model()->getSaldoTotal();
//        $caixa = ContaFinanceira::model()->getSaldoCaixa();
//        $banco = $saldo_total - $caixa;
//
//
//        Yii::app()->clientScript->registerScript('saldo_caixa', '
//            $("div.title").prepend(\'<div class="saldo_diario" style="float: right; margin-right: 20px;"><h5>Saldo em Caixa: R$ '.number_format($caixa, 2, ',', '.').'</h5></div>\');
//        ');
//        Yii::app()->clientScript->registerScript('saldo_banco', '
//            $("div.title").prepend(\'<div class="saldo_diario" style="float: right; margin-right: 20px;"><h5>Saldo em Banco: R$ '.number_format($banco, 2, ',', '.').'</h5></div>\');
//        ');
//        Yii::app()->clientScript->registerScript('saldo_diario', '
//            $("div.title").prepend(\'<div class="saldo_diario" style="float: right; margin-right: 15px;"><h5>Total: R$ '.number_format($saldo_total, 2, ',', '.').'</h5></div>\');
//        ');
//    }
?>
