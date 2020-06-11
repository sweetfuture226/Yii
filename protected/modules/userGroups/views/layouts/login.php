<!DOCTYPE html>

<!--[if lt IE 7]><html class="no-js ie6 oldie" lang=en><![endif]-->
<!--[if IE 7]><html class="no-js ie7 oldie" lang=en><![endif]-->
<!--[if IE 8]><html class="no-js ie8 oldie" lang=en><![endif]-->
<!--[if gt IE 8]><!-->
<html class=" js flexbox canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms no-csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths" lang="en">
    <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta name="description" content="">
        <meta name="author" content="">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

        <title><?php echo CHtml::encode(Yii::app()->name); ?> - Login</title>

        <link rel="dns-prefetch" href="//fonts.googleapis.com">
        <link rel="shortcut icon" href="favicon.ico">

        <?php Yii::app()->clientScript->registerCssFile('themes/flatlab'.'/css/bootstrap.min.css');?>
        <?php Yii::app()->clientScript->registerCssFile('themes/flatlab'.'/css/bootstrap-reset.css');?>
        <!-- Custom styles for this template -->
        <?php Yii::app()->clientScript->registerCssFile('themes/flatlab'.'/css/style.css');?>
        <?php Yii::app()->clientScript->registerCssFile('themes/flatlab'.'/css/style-responsive.css');?>
        <!-- customizations -->
        <?php Yii::app()->clientScript->registerCssFile('themes/flatlab'.'/css/custom.css');?>
        
        <?php Yii::app()->clientScript->registerCssFile('themes/grape' . '/css/bootstrap.css'); ?>
        <?php Yii::app()->clientScript->registerCssFile('themes/grape' . '/css/boostbox.css'); ?>
        <?php Yii::app()->clientScript->registerCssFile('themes/grape' . '/css/boostbox_responsive.css'); ?> 
        <?php Yii::app()->clientScript->registerCssFile('themes/grape' . '/css/font-awesome.min.css'); ?>
        <?php Yii::app()->clientScript->registerCssFile('themes/flatlab' . '/assets/font-awesome/css/font-awesome.css'); ?>
        <?php Yii::app()->clientScript->registerCssFile('//fonts.googleapis.com/css?family=Open+Sans:400italic,300,400,600,700,800'); ?>

        <?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
        
        

        <?php Yii::app()->clientScript->registerScriptFile('themes/grape' . '/js/libs/modernizr-2.0.6.min.js'); ?>
        <?php Yii::app()->clientScript->registerScriptFile('themes/grape' . '/js/main.js'); ?>  
        <?php Yii::app()->clientScript->registerScriptFile('themes/grape' . '/js/jquery-1.11.0.min.js'); ?>
        <?php Yii::app()->clientScript->registerScriptFile('themes/grape' . '/js/jquery-migrate-1.2.1.min.js'); ?> 
        <?php Yii::app()->clientScript->registerScriptFile('themes/grape' . '/js/BootstrapFixed.js'); ?> 
        <?php Yii::app()->clientScript->registerScriptFile('themes/grape' . '/js/bootstrap.min.js'); ?> 
        <?php Yii::app()->clientScript->registerScriptFile('themes/grape' . '/js/spin.min.js'); ?> 
        <?php Yii::app()->clientScript->registerScriptFile('themes/grape' . '/js/jquery.slimscroll.min.js'); ?>
        <?php Yii::app()->clientScript->registerScriptFile('themes/grape' . '/js/App.js'); ?>
        <?php Yii::app()->clientScript->registerScriptFile('themes/grape' . '/js/Demo.js'); ?>
        
        
        
        <?php Yii::app()->clientScript->registerScriptFile('themes/grape'.'/js/forms/jquery.validationEngine-pt.js');?>
        <?php Yii::app()->clientScript->registerScriptFile('themes/grape'.'/js/forms/jquery.validationEngine.js');?>
        <?php Yii::app()->clientScript->registerScriptFile('themes/grape'.'/js/forms/jquery.validationEngine.js');?>
        <?php Yii::app()->clientScript->registerScriptFile('themes/grape'.'/js/forms/jquery.maskMoney.js');?>
        <?php Yii::app()->clientScript->registerScriptFile('themes/grape'.'/js/forms/jquery.maskedinput.min.js');?>
        <?php Yii::app()->clientScript->registerScriptFile('themes/grape'.'/js/forms/chosen.jquery.js');?>
        <?php Yii::app()->clientScript->registerScriptFile('themes/grape'.'/js/forms/jquery.ui.datepicker-pt-BR.js');?>
        
        <?php Yii::app()->clientScript->registerScriptFile('themes/grape'.'/js/jquery.boxy.js');?>
            
        
        <?php Yii::app()->clientScript->registerScriptFile('themes/grape'.'/js/init.js');?>
    </head>
    <body class="body-dark">
	
<!-- START LOGIN BOX -->
<div class="box-type-login">
	<div class="box text-center">
		<div class="box-head">
			<h2 class="text-light text-white">Viva<strong>Smith</strong> </h2>
			<!--<h4 class="text-light text-inverse-alt">Ease your output with BoostBox</h4>-->
		</div>

		<div class="box-body box-centered style-inverse">
                    <div style="float: right">    
                            <a href="<?php echo Yii::app()->baseUrl; ?>/translate/translate/set/lang/pt"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/br.png" class="lang" alt="br" title="Português" /></a>
                            <a style="margin-left: 3px" href="<?php echo Yii::app()->baseUrl; ?>/translate/translate/set/lang/en"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/en.png" class="lang" alt="en" title="English" /></a>
                            <a style="margin-left: 3px" href="<?php echo Yii::app()->baseUrl; ?>/translate/translate/set/lang/es"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/es.png" class="lang" alt="es" title="Spanish" /></a>
                        </div>
			<h2 class="text-light"><?php echo Yii::t("smith","Acesse sua conta");  ?></h2>
			<br/>
                    <?php echo $content; ?>
                </div>
                <?php if(Yii::app()->user->hasFlash('login')): ?>
    
                    <div class="alert error">
                        <?php echo Yii::app()->user->getFlash('login'); ?>
                    </div>

                <?php endif; ?>
        </div><!--end .box-body -->
		 
	</div>

        <!--[if lt IE 7 ]>
        <script src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.3/CFInstall.min.js"></script>
        <script>window.attachEvent("onload",function(){CFInstall.check({mode:"overlay"})});</script>
        <![endif]-->
    </body>
</html>
