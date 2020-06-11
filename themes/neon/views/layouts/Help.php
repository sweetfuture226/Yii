<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <link rel="shortcut icon" href="<?php echo Yii::app()->theme->baseUrl.'/img/favicon.png' ?>">

    <title>Viva Smith - <?php echo CHtml::encode($this->pageTitle); ?></title>


    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/assets/css/font-icons/entypo/css/entypo.css');?>
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic">
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/assets/css/bootstrap.css');?>
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/assets/css/neon-core.css');?>
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/assets/css/neon-theme.css');?>
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/assets/css/neon-forms.css');?>
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/assets/css/custom.css');?>
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/assets/css/sticky-footer-navbar.css');?>

    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/assets/js/select2/select2-bootstrap.css');?>
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/assets/js/select2/select2.css');?>
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/assets/js/selectboxit/jquery.selectBoxIt.css');?>
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/assets/js/daterangepicker/daterangepicker-bs3.css');?>
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/assets/js/icheck/skins/minimal/_all.css');?>
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/assets/js/icheck/skins/square/_all.css');?>
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/assets/js/icheck/skins/flat/_all.css');?>
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/assets/js/icheck/skins/futurico/futurico.css');?>
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/assets/js/icheck/skins/polaris/polaris.css');?>

    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/assets/js/jvectormap/jquery-jvectormap-1.2.2.css');?>
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/assets/js/rickshaw/rickshaw.min.css');?>
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/assets/js/sliptree-tokenfield/dist/css/tokenfield-typeahead.css'); ?>
    <!-- Tokenfield CSS -->
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/assets/js/sliptree-tokenfield/dist/css/bootstrap-tokenfield.css'); ?>
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/assets/js/sliptree-tokenfield/docs-assets/css/pygments-manni.css'); ?>
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/assets/js/sliptree-tokenfield/docs-assets/css/docs.css'); ?>
    <?php Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/assets/js/sweetalert-master/dist/sweetalert.css'); ?>


    
    <script>//$.noConflict();</script>

    <!--[if lt IE 9]><script src="assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->


</head>
<body class="page-body  page-fade" >

<?php
    $data = gmdate("Y-m-d", time()-(3600*27));
    $usuario = UserGroupsUser::model()->findByPk(Yii::app()->user->id);
?>

<div class="page-container horizontal-menu with-sidebar fit-logo-with-sidebar" style="height: 100%;">
    <!-- add class "sidebar-collapsed" to close sidebar by default, "chat-visible" to make chat appear always -->
    <header class="navbar navbar-fixed-top"><!-- set fixed position by adding class "navbar-fixed-top" -->

        <div class="navbar-inner">

            <!-- logo -->
            <div class="navbar-brand">
                <a href="<?php echo Yii::app()->getHomeUrl(); ?>" class="logo"
                   title="<?= Yii::t('smith', 'Página inicial') ?>">
                    <img src="<?php echo Yii::app()->theme->baseUrl . '/assets/images/logo-smith.png' ?>" width="120"
                         style="margin-top: -19px;" alt=""/>
                </a>
            </div>
            <!--<ul class="navbar-nav" id="mobile" style="display: none;">
                <?php /*$tipo_empresa = EmpresaHasParametro::model()->findByAttributes(array("fk_empresa" => $usuario->fk_empresa));
                $tipo_empresa = $tipo_empresa->tipo_empresa;
                $logo = Yii::getPathOfAlias('webroot') .'/'. Empresa::model()->findByPk($usuario->fk_empresa)->logo;
                */ ?>

                <?php
            /*                switch(Yii::app()->controller->id){
                                case 'help':
                                    $this->renderPartial('//site/menuHelp');
                                    break;
                                case 'termosdeuso':
                                    $this->renderPartial('//site/menuTermos');
                                    break;
                                case 'politicaprivacidade':
                                    $this->renderPartial('//site/menuPoliticaPrivacidade');
                                    break;
                            }
            
                            */ ?>
            </ul>-->
            <!-- notifications and other links -->
            <ul class="nav navbar-right pull-right">
                <!-- dropdowns -->
                <li class="dropdown language-selector" style="margin-top: 4px;  ">

                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-close-others="true">
                        <?php
                        $portuguese = "";
                        $english = "";
                        $espanhol = "";
                        ?>
                        <?php switch (Yii::app()->language) {
                            case 'pt':
                            case 'pt_br':
                                echo '<img src="' . Yii::app()->theme->baseUrl . '/assets/images/flag-br.png" alt="br" title="Português" />';
                                $portuguese = "active";
                                break;
                            case 'en':
                                echo '<img src="' . Yii::app()->theme->baseUrl . '/assets/images/flag-uk.png" alt="br" title="Inglês" />';
                                $english = "active";
                                break;
                            case 'es':
                                echo '<img src="' . Yii::app()->theme->baseUrl . '/assets/images/flag-es.png" alt="br" title="Espanhol" />';
                                $espanhol = "active";
                                break;
                        } ?>
                    </a>
                    <ul class="dropdown-menu" style="width: 120px; margin-left: 42.666667px;">
                        <li>
                            <a href="<?php echo Yii::app()->baseUrl; ?>/translate/translate/set/lang/pt">
                                <img src="<?php echo Yii::app()->theme->baseUrl . '/assets/images/flag-br.png' ?>"/>
                                Português

                            </a>
                        </li>
                        <li>
                            <a href="<?php echo Yii::app()->baseUrl; ?>/translate/translate/set/lang/en">
                                <img src="<?php echo Yii::app()->theme->baseUrl . '/assets/images/flag-uk.png' ?> "/>
                                Inglês

                            </a>
                        </li>
                        <li>
                            <a href="<?php echo Yii::app()->baseUrl; ?>/translate/translate/set/lang/es">
                                <img src="<?php echo Yii::app()->theme->baseUrl . '/assets/images/flag-es.png' ?>"/>
                                Espanhol

                            </a>
                        </li>
                    </ul>

                </li>

                <li class="sep"></li>


                <!-- mobile only -->
                <li class="visible-xs">

                    <!-- open/close menu icon (do not remove if you want to enable menu on mobile devices) -->
                    <div class="horizontal-mobile-menu visible-xs">
                        <a href="#" class="with-animation"><!-- add class "with-animation" to support animation -->
                            <i class="entypo-menu"></i>
                        </a>
                    </div>

                </li>

            </ul>

        </div>

    </header>

    <div class="sidebar-menu">

        <div class="sidebar-menu-inner">


            <ul id="main-menu" class="main-menu">
                <!-- add class "multiple-expanded" to allow multiple submenus to open -->
                <!-- class "auto-inherit-active-class" will automatically add "active" class for parent elements who are marked already with class "active" -->
                <?php $tipo_empresa = EmpresaHasParametro::model()->findByAttributes(array("fk_empresa" => $usuario->fk_empresa));
                $tipo_empresa = $tipo_empresa->tipo_empresa;
                $logo = Yii::getPathOfAlias('webroot') . '/' . Empresa::model()->findByPk($usuario->fk_empresa)->logo;
                ?>
                <!--MENUS-->
                <?php
                switch (Yii::app()->controller->id) {
                    case 'help':
                        $this->renderPartial('//site/menuHelp');
                        break;
                    case 'termosdeuso':
                        $this->renderPartial('//site/menuTermos');
                        break;
                    case 'politicaprivacidade':
                        $this->renderPartial('//site/menuPoliticaPrivacidade');
                        break;
                }

                ?>
            </ul>
        </div>
    </div>

    <div class="main-content">


        <!--breadcrumbs start -->
            <?php if(isset($this->breadcrumbs)):?>
                <?php if ($this->breadcrumbs) {?>
                    <?php $this->widget('zii.widgets.CBreadcrumbs', array(
                        'links'=>$this->breadcrumbs,
                        'tagName'=>'ol',
                        'htmlOptions' => array('class' => 'breadcrumb bc-3'),
                        'homeLink'=>'<li><a href="'.Yii::app()->homeUrl.'"><i class="entypo-home"></i> '. Yii::t('smith','Página Inicial').'</a></li>',
                        'activeLinkTemplate'=>'<li><a href="{url}">{label}</a></li>',
                        'separator' => '',
                        'inactiveLinkTemplate'=>'<li class="active">{label}</li>'
                    )); ?>
                <?php }
                else{
                    echo '<div class="breadcrumb">'.CHtml::encode($this->pageTitle).'</div>';
                }?>
            <?php endif?>
            <?php
            $action = Yii::app()->getController()->getAction()->id;
            $controller = Yii::app()->getController()->id;
            if ($action == 'index' && $controller == 'dashboard') { ?>
                <div class="row" style="margin-top: 15px">
                    <?php $this->renderPartial('/metrica/menuTop'); ?>
                </div>
            <?php } ?>

        <br />


        <div class="content-body">
            <?php echo $content; ?>
        </div>
        <!-- Footer -->
        <footer class="footer">
            <div class="container2">
                <p class="text-muted">Copyright &copy; <?php echo date('Y'); ?> <?php echo Yii::t("smith", 'por') ?> <a target="_blank" href="http://vivainovacao.com">Viva
                        Inovação</a>. <span><a target="_blank" href="<?php echo Yii::app()->baseUrl; ?>/termosDeUso"><?php echo Yii::t('smith', 'Termos de uso'); ?></a></span>
                    - <span><a target="_blank" href="<?php echo Yii::app()->baseUrl; ?>/politicaPrivacidade"><?php echo Yii::t('smith', 'Política de privacidade'); ?></a></span> <?php echo Yii::t("smith", 'Todos os direitos reservados') ?></p>
            </div>
        </footer>
    </div>
</div>



    <?php Yii::app()->clientScript->registerScript('helpers', '
    actionContato = '.CJSON::encode(Yii::app()->createUrl('usuario/contato')).';
    baseUrl = '.CJSON::encode(Yii::app()->baseUrl).';', CClientScript::POS_HEAD);?>



    <!-- Imported styles on this page -->
<?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
<?php Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/assets/js/bootstrap-datepicker/css/bootstrap-datepicker.css');?>

    <!-- Bottom scripts (common) -->
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/assets/js/gsap/main-gsap.js', CClientScript::POS_END); ?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/assets/js/jquery-ui-1.10.1.custom.min.js', CClientScript::POS_END); ?>
    <?php //Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/assets/js/bootstrap.js', CClientScript::POS_END); ?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/assets/js/joinable.js', CClientScript::POS_END); ?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/assets/js/resizeable.js', CClientScript::POS_END); ?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/assets/js/neon-api.js', CClientScript::POS_END); ?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/assets/js/nestable/jquery.nestable.js', CClientScript::POS_END);?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/assets/js/jvectormap/jquery-jvectormap-1.2.2.min.js', CClientScript::POS_END); ?>
    

    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/assets/js/gsap/main-gsap.js', CClientScript::POS_END);?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/assets/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js', CClientScript::POS_END);?>
    <?php //Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/assets/js/bootstrap.js', CClientScript::POS_END);?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/assets/js/joinable.js', CClientScript::POS_END);?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/assets/js/resizeable.js', CClientScript::POS_END);?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/assets/js/neon-api.js', CClientScript::POS_END);?>

<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/assets/js/bootstrap-datepicker/js/bootstrap-datepicker.js', CClientScript::POS_END);?>
    <!-- Imported scripts on this page -->
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/assets/js/select2/select2.min.js', CClientScript::POS_END);?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/assets/js/bootstrap-tagsinput.min.js', CClientScript::POS_END);?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/assets/js/typeahead.min.js', CClientScript::POS_END);?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/assets/js/selectboxit/jquery.selectBoxIt.min.js', CClientScript::POS_END);?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/assets/js/bootstrap-timepicker.min.js', CClientScript::POS_END);?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/assets/js/bootstrap-colorpicker.min.js', CClientScript::POS_END);?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/assets/js/daterangepicker/moment.min.js', CClientScript::POS_END);?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/assets/js/daterangepicker/daterangepicker.js', CClientScript::POS_END);?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/assets/js/jquery.multi-select.js', CClientScript::POS_END);?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/assets/js/icheck/icheck.min.js', CClientScript::POS_END);?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/assets/js/neon-chat.js', CClientScript::POS_END);?>


    <!-- Imported scripts on this page -->
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/assets/js/jvectormap/jquery-jvectormap-europe-merc-en.js', CClientScript::POS_END); ?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/assets/js/jquery.sparkline.min.js', CClientScript::POS_END); ?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/assets/js/rickshaw/vendor/d3.v3.js', CClientScript::POS_END); ?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/assets/js/rickshaw/rickshaw.min.js', CClientScript::POS_END); ?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/assets/js/raphael-min.js', CClientScript::POS_END); ?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/assets/js/morris.min.js', CClientScript::POS_END); ?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/assets/js/toastr.js', CClientScript::POS_END); ?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/assets/js/neon-chat.js', CClientScript::POS_END); ?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/assets/js/highcharts/data.js', CClientScript::POS_END); ?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/assets/js/highcharts/exporting.js', CClientScript::POS_END); ?>

    <!-- JavaScripts initializations and stuff -->
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/assets/js/neon-custom.js', CClientScript::POS_END); ?>

    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/assets/js/sliptree-tokenfield/dist/bootstrap-tokenfield.js', CClientScript::POS_END); ?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/assets/js/jquery.maskMoney.js', CClientScript::POS_END); ?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/assets/js/jquery.mask.js', CClientScript::POS_END);?>
    <!-- Demo Settings -->
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/assets/js/neon-demo.js', CClientScript::POS_END); ?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/assets/js/code-prettify-master/loader/run_prettify.js', CClientScript::POS_END); ?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/assets/js/sweetalert-master/dist/sweetalert.min.js', CClientScript::POS_END); ?>
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/assets/js/init.js', CClientScript::POS_END);?>
<div id="loader_image"></div>
</body>
</html>
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