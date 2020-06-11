<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Mosaddek">
    <meta name="keyword" content="FlatLab, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
    <!-- encontrar qual caminho do Yii acessa o favicon.png -->
    <link rel="shortcut icon" href="<?php echo Yii::app()->theme->baseUrl.'/img/favicon.png' ?>">

    <title>Login - Decore</title>

    <!-- Bootstrap core CSS -->
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/css/bootstrap.min.css');?>
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/css/bootstrap-reset.css');?>
    <!--external css-->
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/assets/font-awesome/css/font-awesome.css');?>
    <!-- Custom styles for this template -->
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/css/style.css');?>
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/css/style-responsive.css');?>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
    <!--[if lt IE 9]>
        <script src="<?php echo Yii::app()->theme->baseUrl.'/js/html5shiv.js' ?>"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl.'/js/respond.min.js' ?>"></script>
    <![endif]-->
</head>

  <body class="login-body">
    <div class="container">
        <div class="loginLogo" style="max-width: 330px; margin: 75px auto -75px;">
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/logo_decore.png" alt="Logo"  style="max-width: 330px;"/>
        </div>
        <?php echo $content; ?> <!-- Vem do Controller site Action login (site/login)-->
    </div>

    <!-- js placed at the end of the document so the pages load faster -->
    <?php //Yii::app()->clientScript->registerCoreScript('jquery'); ?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.js');?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/bootstrap.min.js');?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.validate.min.js');?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.validate.messages_pt_BR.js');?>
    
    <script type="text/javascript">
        $(function() {
            //===== Form validation =====//
                $("#login-form").validate();
        });
    </script>
  </body>
</html>
