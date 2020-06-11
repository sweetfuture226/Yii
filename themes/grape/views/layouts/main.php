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


        <title><?php echo CHtml::encode("Viva Smith - Gestão de Produtividade e Composição de Custos"); ?></title>
        <style type="text/css">
            #query{
                height: 16px !important;
            }
        </style>
        <link rel="dns-prefetch" href="//fonts.googleapis.com">
        <link rel="shortcut icon" href="favicon.ico">

        <?php Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/css/main.css');?>
        <?php Yii::app()->clientScript->registerCssFile('//fonts.googleapis.com/css?family=PT+Sans');?>

        <?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>

        <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/libs/modernizr-2.0.6.min.js');?>
        <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/main.js');?>
        <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/grape.js');?>

        <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/forms/jquery.validationEngine-pt.js');?>
        <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/forms/jquery.validationEngine.js');?>
        <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/forms/jquery.maskMoney.js');?>
        <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/forms/jquery.maskedinput.min.js');?>
        <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/forms/chosen.jquery.js');?>
        <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/forms/jquery.ui.datepicker-pt-BR.js');?>

        <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.boxy.js');?>

        <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/init.js');?>
    </head>
    <body id="top">
        <div id="container">
            <div id="header-surround"><header id="header">
                    <a href="<?php echo Yii::app()->getBaseUrl(true); ?>" title="Página Inicial">
                        <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Logo_VivaSmith_02.png" alt="<?= Yii::app()->name ?>" title="<?= Yii::app()->name ?>" class="logo">
                    </a>
                    <div class="divider-header divider-vertical"></div>
                    <a href="javascript:void(0);" onclick="$('#info-dialog').dialog({ modal: true });"><span class="btn-info"></span></a>
                    <div id="info-dialog" title="<?= Yii::app()->name ?>" style="display: none; text-align: justify">
                        <center><img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Logo_Vivasmith_3.png" alt="<?= Yii::app()->name ?>" title="<?= Yii::app()->name ?>"></center>
                        <p>A plataforma Viva Smith, um dos produtos oferecidos pela Viva Inovação, é a solução definitiva para a gestão da produtividade e composição de custos de projetos baseados em mão de obra que opera software.</p>
                        <p>Trata-se da única com foco em eficiência, voltada para empresas que visam produtividade operacional, redução de riscos e maximização de lucros.</p>
                        <p>O módulo de captura de informações, através do controle das ações em cada máquina, contabiliza o tempo dispendido em aplicativos e sites autorizados na whitelist. Os que não se encontram na whitelist são somados às pausas (mouse e teclado parados), resultando no tempo de ócio do usuário.</p>
                        <p>As informações de usuário são os insumos para o módulo de composição de custos de projetos. Em uma empresa de projetos de engenharia, por exemplo, a plataforma verifica o arquivo .dwg em que se está trabalhando no AutoCAD.<p/>

                        <p>Com os devidos mapeamentos e comparações, é possível saber se o tempo do colaborador está
                            sendo gasto com arquivos alheios à empresa. A partir deste ponto, já é possível verificar se
                            as horas despendidas nos projetos estão de acordo com o previsto, apoiando o controle e
                            incluive demissões e contratações.</p>

                        <p>O módulo de análise financeira faz uso das informações providas pelos módulos de captura de
                            informações e composição de custos.
                            A integração com de dados como salário e carga horária dos colaboradores resultam em
                            relatórios como taxa de produtividade real do colaborador (homem/hora), custo real do
                            colaborador, colaboradores mais e menos produtivos,
                        dentre diversos outros, sempre acessíveis por período de datas. A inclusão de informações referentes às despesas (viagens, hospedagem, alimentação) proverão uma visão macro do projeto, auxiliando na tomada rápida de decisões, como a solicitação de aditivos contratuais de tempo e/ou custo.</p>
                    </div>
                    <!--<ul class="toolbox-header">
                        <li>
                            <a rel="tooltip" class="toolbox-action" href="javascript:void(0);" original-title="Create a User"><span class="i-24-user-business"></span></a>
                            <div class="toolbox-content">
                                <div class="block-border">
                                    <div class="block-header small">
                                        <h1>Create a User</h1>
                                    </div>
                                    <form id="create-user-form" class="block-content form" action="" method="post">
                                        <div class="_100">
                                            <p><label for="username">Username</label><input id="username" name="username" class="required text" type="text" value=""></p>
                                        </div>
                                        <div class="_50">
                                            <p class="no-top-margin"><label for="firstname">Firstname</label><input id="firstname" name="firstname" class="required text" type="text" value=""></p>
                                        </div>
                                        <div class="_50">
                                            <p class="no-top-margin"><label for="lastname">Lastname</label><input id="lastname" name="lastname" class="required text" type="text" value=""></p>
                                        </div>
                                        <div class="clear"></div>
                                        <div class="block-actions">
                                            <ul class="actions-left">
                                                <li><a class="close-toolbox button red" id="reset" href="javascript:void(0);">Cancel</a></li>
                                            </ul>
                                            <ul class="actions-right">
                                                <li><input type="submit" class="button" value="Create the User"></li>
                                            </ul>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </li>
                        <li>
                            <a rel="tooltip" class="toolbox-action" href="javascript:void(0);" original-title="Write a Message"><span class="i-24-balloon"></span></a>
                            <div class="toolbox-content">
                                <div class="block-border">
                                    <div class="block-header small">
                                        <h1>Write a Message</h1>
                                    </div>
                                    <form id="write-message-form" class="block-content form" action="" method="post">
                                        <p class="inline-mini-label">
                                            <label for="recipient">Recipient</label>
                                            <input type="text" name="recipient" class="required text">
                                        </p>
                                        <p class="inline-mini-label">
                                            <label for="subject">Subject</label>
                                            <input type="text" name="subject" class="text">
                                        </p>
                                        <div class="_100">
                                            <p class="no-top-margin"><label for="message">Message</label><textarea id="message" name="message" class="required uniform" rows="5" cols="40"></textarea></p>
                                        </div>
                                        <div class="clear"></div>
                                        <div class="block-actions">
                                            <ul class="actions-left">
                                                <li><a class="close-toolbox button red" id="reset2" href="javascript:void(0);">Cancel</a></li>
                                            </ul>
                                            <ul class="actions-right">
                                                <li><input type="submit" class="button" value="Send Message"></li>
                                            </ul>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </li>
                        <li>
                            <a rel="tooltip" class="toolbox-action" href="javascript:void(0);" original-title="Create a Folder"><span class="i-24-folder"></span></a>
                            <div class="toolbox-content">
                                <div class="block-border">
                                    <div class="block-header small">
                                        <h1>Create a Folder</h1>
                                    </div>
                                    <form id="create-folder-form" class="block-content form" action="" method="post">
                                        <p class="inline-mini-label">
                                            <label for="folder-name">Name</label>
                                            <input type="text" name="folder-name" class="required text">
                                        </p>
                                        <div class="block-actions">
                                            <ul class="actions-left">
                                                <li><a class="close-toolbox button red" id="reset3" href="javascript:void(0);">Cancel</a></li>
                                            </ul>
                                            <ul class="actions-right">
                                                <li><input type="submit" class="button" value="Create Folder"></li>
                                            </ul>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </li>
                    </ul>-->
                    <div id="user-info">
                        <p>
                            <span class="messages">Olá, <a href="javascript:void(0);"><?= Yii::app()->user->name ?></a> <!--( <a href="javascript:void(0);"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/icons/packs/fugue/16x16/mail.png" alt="Messages"> 3 new messages</a> )--></span>
<!--                            <a href="javascript:void(0)" class="toolbox-action button">Settings</a>-->
                            <a href="<?php echo Yii::app()->baseUrl; ?>/site/logout" class="button red">Sair</a>
                        </p>
                    </div>
                </header></div>
            <div class="fix-shadow-bottom-height"></div>
            <aside id="sidebar">
                <div id="search-bar">
                    <form id="search-form" name="search-form" action="<?= Yii::app()->baseUrl; ?>/logAtividade/busca" method="post">
                        <?php
                            $pessoas = ProPessoa::model()->getAD();
                            $source = array();
                            foreach ($pessoas as $value) {
                                $source[] = $value['ad'];
                            }

                            $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                                'name'=>'query',
                                'id' =>'query',
                                'source'=>$source,
                                // additional javascript options for the autocomplete plugin
                                'options'=>array(
                                    'minLength'=>'2',
                                ),
                                'htmlOptions'=>array(
                                    'style'=>'height:20px;'
                                ),
                            ));
                        ?>
                    </form>
                </div>
                <section id="login-details">
                    <?php
                    //                          $dir = "/home/vivainovacao/smith.vivainovacao.com/themes/grape/img/users_avatars/".Yii::app()->user->id.".png";
                    $dir = "/var/www/smith.vivainovacao.com/themes/grape/img/users_avatars/" . Yii::app()->user->id . ".png";
                          $existe = file_exists($dir);
                          if (!$existe)
                            $dir = Yii::app()->theme->baseUrl . "/img/misc/avatar_small.png";
                        else {
                            $dir = "http://localhost.smith.vivainovacao.com/themes/grape/img/users_avatars/".Yii::app()->user->id.".png";
//                            $dir = "http://smith.vivainovacao.com/themes/grape/img/users_avatars/".Yii::app()->user->id.".png";
                        }
                    ?>
                    <img class="img-left framed" src="<?php echo $dir ?>" alt="Olá <?= Yii::app()->user->name ?>">
                    <h3>Logado como</h3>
                    <h2><a class="user-button" href="javascript:void(0);"><?= Yii::app()->user->name ?>&nbsp;<span class="arrow-link-down"></span></a></h2>
                    <ul class="dropdown-username-menu">
                    <li><a href="<?php echo Yii::app()->baseUrl; ?>/userGroups/user/profile">Perfil</a></li>
                    <!-- <li><a href="/userGroups/user/profile">Perfil</a></li> -->
                    <li><a href="<?php echo Yii::app()->baseUrl; ?>/site/logout">Sair</a></li>
                    </ul>
                    <div class="clearfix"></div>
                </section>
                <!--       MENU         -->
                <nav id="nav">
                    <ul class="menu collapsible shadow-bottom">
                    	<?php if(Yii::app()->user->groupName != "coordenador"){ ?>
                        <li><a href="javascript:void(0);" style="padding-left: 20px; padding-right: 0px; " >Gráficos</a>
                            <ul class="sub">
                               <li>
                                    <a href="<?php echo Yii::app()->baseUrl; ?>/logAtividade/produtivo" style="padding-left: 20px; padding-right: 0px; ">Produtividade </a>
                                </li>
                                <li>
                                    <a href="<?php echo Yii::app()->baseUrl; ?>/logAtividade/produzidoDia" style="padding-left: 20px; padding-right: 0px; ">Produtividade Diária </a>
                                </li>
<!--                                <li>
                                    <a href="<?php echo Yii::app()->baseUrl; ?>/logAtividade/produEquipeSemana" style="padding-left: 20px; padding-right: 0px; ">Produtividade por equipe na semana</a>
                                </li>
                                <li>
                                    <a href="<?php echo Yii::app()->baseUrl; ?>/logAtividade/produEquipePeriodo" style="padding-left: 20px; padding-right: 0px; ">Produtividade por equipe</a>
                                </li>-->
				<li>
                                    <a href="<?php echo Yii::app()->baseUrl; ?>/logAtividade/custo" style="padding-left: 20px; padding-right: 0px; ">Custo </a>
                                </li>
                              <!--  <li>
                                    <a href="<?php echo Yii::app()->baseUrl; ?>/logAtividade/equipeValorReal" style="padding-left: 20px; padding-right: 0px; ">Custo por equipe </a>
                                </li>
                                <li>
                                    <a href="<?php echo Yii::app()->baseUrl; ?>/logAtividade/funcionarioValorReal" style="padding-left: 20px; padding-right: 0px; ">Custo por funcionário </a>
                                </li>-->
<!--                                <li>
                                    <a href="<?php echo Yii::app()->baseUrl; ?>/logAtividade/appTempoProduzido" style="padding-left: 20px; padding-right: 0px; ">Tempo produzido</a>
                                </li>
                                <li>
                                    <a href="<?php echo Yii::app()->baseUrl; ?>/logAtividade/appTempoOcioso" style="padding-left: 20px; padding-right: 0px; ">Tempo ocioso</a>
                                </li>
-->
                                <li>
                                    <a href="<?php echo Yii::app()->baseUrl; ?>/logAtividade/appTempoPrdGeral" style="padding-left: 20px; padding-right: 0px; ">Tempo Produtividade Geral</a>
                                </li>
                                <li>
                                    <a href="<?php echo Yii::app()->baseUrl; ?>/logAtividade/rankingSemana" style="padding-left: 20px; padding-right: 0px; ">Ranking produtividade por período</a>
                                </li>

                                <li>
                                    <a href="<?php echo Yii::app()->baseUrl; ?>/logAtividade/TempoPessoaObraMensal" style="padding-left: 20px; padding-right: 0px; ">Tempo por contrato</a>
                                </li>
                                <li>
                                    <a href="<?php echo Yii::app()->baseUrl; ?>/Contrato/custoEnergia"
                                       style="padding-left: 20px; padding-right: 0px; ">Consumo de Energia por
                                        Contrato</a>
                                </li>

                                <li>
                                    <a href="<?php echo Yii::app()->baseUrl; ?>/logAtividade/ModoPromiscuo" style="padding-left: 20px; padding-right: 0px; ">Programas não reconhecidos</a>
                                </li>

<!--                                <li>
                                    <a href="<?php echo Yii::app()->baseUrl; ?>/logAtividade/all" style="padding-left: 20px; padding-right: 0px; ">Resumo</a>
                                </li>-->
                            </ul>
                        </li>


                            <li><a href="<?php echo Yii::app()->baseUrl; ?>/Contrato"
                                   style="padding-left: 20px; padding-right: 0px; ">Contratos</a></li>
                            <li><a href="<?php echo Yii::app()->baseUrl; ?>/Contrato/andamentoObra"
                                   style="padding-left: 20px; padding-right: 0px; ">Andamento</a></li>

                            <li><a href="javascript:void(0);" style="padding-left: 20px; padding-right: 0px; ">Configurações</a>
                            <ul class="sub">
                         	<?php if(Yii::app()->user->groupName != "coordenador"){ ?>
                                	<li><a href="<?php echo Yii::app()->baseUrl; ?>/proPessoa" style="padding-left: 20px; padding-right: 0px; ">Colaborador</a></li>
                          	<?php } ?>
					<li><a href="<?php echo Yii::app()->baseUrl; ?>/ColaboradorSemProdutividade/index" style="padding-left: 20px; padding-right: 0px; ">Colaborador Sem Produtividade</a></li>
				<?php if(Yii::app()->user->groupName != "coordenador"){ ?>
                                        <li><a href="<?php echo Yii::app()->baseUrl; ?>/usuario" style="padding-left: 20px; padding-right: 0px; ">Usuários</a></li>
                                        <?php } ?>
                        		<li><a href="<?php echo Yii::app()->baseUrl; ?>/programaPermitido" style="padding-left: 20px; padding-right: 0px; ">Programas Permitidos</a></li>
                                       <!-- <li><a href="<?php echo Yii::app()->baseUrl; ?>/sitePermitido" style="padding-left: 20px; padding-right: 0px; ">Sites Permitidos</a></li>
					--><li><a href="<?php echo Yii::app()->baseUrl; ?>/equipe" style="padding-left: 20px; padding-right: 0px; ">Equipes</a></li>

                    <li><a href="<?php echo Yii::app()->baseUrl; ?>/disciplina" style="padding-left: 20px; padding-right: 0px; ">Disciplinas</a></li>

                        		<li><a href="<?php echo Yii::app()->baseUrl; ?>/proTemplate" style="padding-left: 20px; padding-right: 0px; ">Templates</a></li>

                                <li><a href="<?php echo Yii::app()->baseUrl; ?>/EmpresaHasParametro/"
                                       style="padding-left: 20px; padding-right: 0px; ">Parâmetros</a></li>
<!--                                <li>
                                    <a href="<?php echo Yii::app()->baseUrl; ?>/logAtividade/all" style="padding-left: 20px; padding-right: 0px; ">Resumo</a>
                                </li>-->
                            </ul>
                        </li>
                         <?php } ?>

                        <?php if(Yii::app()->user->groupName == "coordenador"){ ?>
                            <li><a href="<?php echo Yii::app()->baseUrl; ?>/Contrato/andamentoObra"
                                   style="padding-left: 20px; padding-right: 0px; ">Andamento</a></li>
                         <?php } ?>
                    </ul>
                </nav>


                <!--                <div class="clear height-fix"></div>-->
                <!--/       MENU         -->
            </aside>
            <div id="main" role="main">
                <div id="title-bar">
                    <?php if(isset($this->breadcrumbs)):?>
                        <?php if ($this->breadcrumbs) {?>
                             <?php $this->widget('zii.widgets.CBreadcrumbs', array(
                                    'links'=>$this->breadcrumbs,
                                    'separator'=>' <span class="sep"></span> ',
                                    'htmlOptions'=>array(
                                        'id'=>'breadcrumbs'
                                    )
                            )); ?>
                         <?php }
                         else{
                             echo '<div id="breadcrumbs"><span>'.$this->pageTitle.'</span></div>';
                         }?>

                    <?php endif?>
                    <!--<ul id="breadcrumbs">
                        <li><a href="dashboard.html" title="Home"><span id="bc-home"></span></a></li>
                        <li class="no-hover">Caminho</li>
                    </ul>-->
                </div>
                <div class="shadow-bottom shadow-titlebar"></div>
                <div id="main-content">
                    <div class="container_12">
                        <div id="flash_message" style="">
                            <?php if(Yii::app()->user->hasFlash('error')): ?>
                                <div class="grid_12">
                                    <div class="alert error">
                                        <span class="hide">x</span>
                                        <?php echo Yii::app()->user->getFlash('error'); ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if(Yii::app()->user->hasFlash('success')): ?>
                                <div class="grid_12">
                                    <div class="alert success">
                                        <span class="hide">x</span>
                                        <?php echo Yii::app()->user->getFlash('success'); ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="grid_12">
                            <div class="block-border">
                                <div class="block-header">
                                    <h1><?php echo isset($this->title_action) ? $this->title_action : Yii::app()->name ?></h1><!--<span></span>-->
                                </div>
                                <div class="block-content">
                                    <?php echo $content; ?>
                                </div>
                                <!-- MODELO DO FORM  -->
                                <!-- <form id="validate-form" class="block-content form valid" action="" method="post">
                                    <div class="_25">
                                        <p>
                                            <label for="25">Label</label>
                                            <input id="25" type="text" value="" class="text validate[required]">
                                        </p>
                                    </div>
                                    <div class="_25">
                                        <p>
                                            <label for="25">Label</label>
                                            <input id="25" type="text" value="" class="text">
                                        </p>
                                    </div>
                                    <div class="_25">
                                        <p>
                                            <label for="25">Label</label>
                                            <input id="25" type="text" value="" class="text">
                                        </p>
                                    </div>
                                    <div class="_25">
                                        <p>
                                            <label for="25">Label</label>
                                            <input id="25" type="text" value="" class="text">
                                        </p>
                                    </div>
                                    <div class="clear"></div>
                                    <div class="block-actions">
                                        <ul class="actions-left">
                                        </ul>
                                        <ul class="actions-right">
                                            <li>
                                                <a class="button red" id="reset-validate-form" href="javascript:void(0);">Limpar</a>
                                            </li>
                                            <li>
                                                <input type="submit" class="button" value="Salvar">
                                            </li>
                                        </ul>
                                    </div>
                                </form>
                                <!--/ MODELO DO FORM  -->
                            </div>
                        </div>
                        <div class="clear height-fix"></div>
                    </div>
                </div>
            </div>
            <footer id="footer">
                <div class="container_12">
                    <div class="grid_12">
                        <div class="footer-icon align-center"><a class="top" href="#top" title="Ir para o topo"></a></div>
                    </div>
                </div>
            </footer>
        </div>
        <div class="modal"></div>
        <!--[if lt IE 7 ]>
            <script src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.3/CFInstall.min.js"></script>
            <script>window.attachEvent("onload",function(){CFInstall.check({mode:"overlay"})});</script>
        <![endif]-->
<!--        <div id="mixpanel" style="visibility: hidden; "></div>-->
    </body>
</html>
<script>hideIconsExportPrint();</script>
