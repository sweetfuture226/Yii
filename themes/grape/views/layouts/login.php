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

        <?php Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/main.css'); ?>
        <?php Yii::app()->clientScript->registerCssFile('//fonts.googleapis.com/css?family=PT+Sans'); ?>

        <?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>

        <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/libs/modernizr-2.0.6.min.js'); ?>
        <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/main.js'); ?>
        
        <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/forms/jquery.validationEngine-pt.js');?>
        <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/forms/jquery.validationEngine.js');?>
        
        <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/init.js');?>
    </head>
    <body class="special-page">
        <div id="container">
            <div id="logo"><center><img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Logo_Vivasmith_3.png" alt="<?= Yii::app()->name ?>" title="<?= Yii::app()->name ?>"></center></div>
            <section id="login-box">
                <div class="block-border">
                    <div class="block-header">
                        <h1>Login</h1>
                    </div>
                    <?php echo $content; ?>
                </div>
                <?php if(Yii::app()->user->hasFlash('login')): ?>
    
                    <div class="alert error">
                        <?php echo Yii::app()->user->getFlash('login'); ?>
                    </div>

                <?php endif; ?>
            </section>
        </div>
        <!--[if lt IE 7 ]>
        <script src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.3/CFInstall.min.js"></script>
        <script>window.attachEvent("onload",function(){CFInstall.check({mode:"overlay"})});</script>
        <![endif]-->
    </body>
</html>