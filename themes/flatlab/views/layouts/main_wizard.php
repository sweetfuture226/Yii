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

 $usuario = UserGroupsUser::model()->findByPk(Yii::app()->user->id);
 $colaboradores = ColaboradorSemProdutividade::model()->findAll(array("condition"=>"fk_empresa=$usuario->fk_empresa AND data like '$data'"));

 //dd($colaboradores);


?>
  <section id="container" class="">
      <!--header start-->
      <header class="header white-bg">
          <div class="sidebar-toggle-box">

          </div>
          <!--logo start-->
          <a href="<?php echo Yii::app()->getHomeUrl(); ?>" class="logo" title="Página inicial" >Viva<span>Smith</span></a>
          <!--logo end-->

<div class="nav notify-row" id="top_menu">
             <?php if( Yii::app()->user->groupName=='empresa'){?>
            <!--  notification start -->
            <ul class="nav top-menu">
              <!-- settings start -->

              <!-- inbox dropdown end -->
              <!-- notification dropdown start-->


                <!-- notification dropdown end -->
                <?php
                }
                      ?>
          </ul>
          </div>

          <!--notifications start-->
          <?php if (Yii::app()->user->hasFlash('error')): ?>
            <div class="notifications" style="float: left; width: 53%; margin: 5px 0 0 40px;">
                <div class="alert alert-block alert-danger fade in" style="margin-bottom: 0px !important">
                    <button data-dismiss="alert" class="close close-sm" type="button">
                        <i class="icon-remove"></i>
                    </button>
                    <strong><i class="icon-exclamation-sign"></i> ERRO:</strong> <?php echo Yii::app()->user->getFlash('error'); ?>
                </div>
            </div>
          <?php endif; ?>
          <?php if (Yii::app()->user->hasFlash('success')): ?>
            <div class="notifications" style="float: left; width: 60%; margin: 5px 0 0 80px;">
                <div class="alert alert-success fade in" style="margin-bottom: 0px !important">
                    <button data-dismiss="alert" class="close close-sm" type="button">
                        <i class="icon-remove"></i>
                    </button>
                    <strong><i class="icon-ok-sign"></i> SUCESSO!</strong> <?php echo Yii::app()->user->getFlash('success'); ?>
                </div>
            </div>
          <?php endif; ?>
          <!--notifications end-->
          <div class="top-nav ">

              <ul class="nav pull-right top-menu">

                 <!-- <li>
                      <div id="search-bar">
                      <form  id="search-form" name="search-form" action="<?= Yii::app()->baseUrl; ?>/logAtividade/busca" method="post">
                        <?php
                 $pessoas = Colaborador::model()->getAD();
                            $source = array();

                            foreach ($pessoas as $value) {
                                $source[] = $value['ad'];
                            }

                            $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                                'name'=>'query',
                                'id' =>'query',
                                'source'=>$source,

                                'options'=>array(
                                    'minLength'=>'2',
                                ),
                                'htmlOptions'=>array(
                                    'style'=>'height: 40px',
                                    'autocomplete'=> 'off',
                                    'class'=>'form-control search'
                                ),
                            ));
                        ?>
                    </form>
                  </div>
                    </li> -->


                  <!-- user login dropdown start-->
                  <li class="dropdown">
                     <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                          <img alt="" src="" style="display: none">

                          <span class="username"><?php echo Yii::t("smith",'Idiomas') ?></span>
                          <b class="caret"></b>
                     </a>
                     <ul class="dropdown-menu extended" style="width: 40px !important">
                          <div class="log-arrow-up"></div>
                          <li style="width: 100% ; text-align: center"> <a href="<?php echo Yii::app()->baseUrl; ?>/translate/translate/set/lang/pt">Português - <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/br.png" class="lang" alt="br" title="Português" /></a></li>
                          <li style="width: 100% ; text-align: center"><a href="<?php echo Yii::app()->baseUrl; ?>/translate/translate/set/lang/en">English - <img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/en.png" class="lang" alt="en" title="English" /></a></li>
                     </ul>
                 </li>
                  <!-- user login dropdown start-->
                  <li class="dropdown">
                      <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                          <img alt="" src="" style="display: none">

                          <span class="username"><?= Yii::app()->user->name ?></span>
                          <b class="caret"></b>
                      </a>
                      <ul class="dropdown-menu extended logout" style="width: 160px !important">
                          <div class="log-arrow-up"></div>
                          <li style="width: 100%">
				     <a  data-toggle="modal" href="#reportarErro">
                                        <i class=" icon-exclamation-sign"></i>Reportar erro
                                     </a>
                          </li>
                          <li style="width: 100%">
                              <a href="<?php echo Yii::app()->createUrl('userGroups/user/profile', array('id'=>Yii::app()->user->id)); ?>">
                                  <i class=" icon-suitcase"></i> Perfil
                              </a>
                          </li>
<!--                      <li><a href="#"><i class="icon-cog"></i> Settings</a></li>
                          <li><a href="#"><i class="icon-bell-alt"></i> Notification</a></li>-->
                          <li>
                              <a href="<?php echo Yii::app()->baseUrl; ?>/userGroups/user/logout">
                                  <i class="icon-key"></i> Sair
                              </a>
                          </li>
                      </ul>
                  </li>
                  <!-- user login dropdown end -->
              </ul>
          </div>
      </header>
      <!--header end-->
      <!--sidebar start-->
      <aside>
          <div id="sidebar" class="nav-collapse " tabindex="5000" style="overflow: hidden; outline: none; margin-left: -210px;">
              <!-- sidebar menu start-->
              <ul class="sidebar-menu" id="nav-accordion">

                  <?php switch (Yii::app()->user->groupName) {
                                case 'funcionario': ?>
                                    <li class="sub-menu">
                                        <a href="javascript:;">
                                            <i class="icon-archive"></i>
                                            <span>Gráficos</span>
                                        </a>
                                        <ul class="sub">
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/decorProjeto/create" title="">Criar Projeto</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/decorProjeto" title="">Ver Projetos</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/decorCotacao" title="">Cotações</a></li>
                                        </ul>
                                    </li>
                                    <li class="sub-menu">
                                        <a href="javascript:;" >
                                            <i class="icon-tasks"></i>
                                            <span>Escritório</span>
                                        </a>
                                        <ul class="sub">
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/contaPagar" title="">Contas a Pagar</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/contaReceber" title="">Contas a Receber</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/notaFiscal" title="">Notas Fiscais</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/contaFinanceira" title="">Contas Bancárias</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/patrimonio" title="">Patrimônio</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/funcionario" title="">Funcionários</a></li>
                                        </ul>
                                    </li>
                                    <li class="sub-menu">
                                        <a href="javascript:;">
                                            <i class="icon-book"></i>
                                            <span>Cadastros</span>
                                        </a>
                                        <ul class="sub">
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/cliente" title="">Clientes</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/fornecedor" title="">Fornecedores</a></li>
                                        </ul>
                                    </li>
                                    <li class="sub-menu">
                                        <a href="javascript:;">
                                            <i class="icon-cogs"></i>
                                            <span>Configurações</span>
                                        </a>
                                        <ul class="sub">
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/decorTipoAmbiente" title="">Ambientes</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/cargo" title="">Cargos</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/centroCusto" title="">Centros
                                                    de Custo</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/usuario" title="">Gerenciar Usuários</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/ramoAtuacao" title="">Ramos de atuação</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/decorSegmento" title="">Segmentos</a>
                                            </li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/subcategoriaCentroCusto"
                                                   title="">Sub. de Centro de Custo</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/decorTipoTipologia"
                                                   title="">Tipologias</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/tipoDocumento" title="">Tipos de documento</a></li>
                                        </ul>
                                    </li>
                        <?php break;
                                case 'empresa': ?>
                                    <li class="menu_body">
                                        <a href="<?= Yii::app()->request->baseUrl ?>/logAtividade/logTempoReal" title=""><i class="icon-bell"></i>Log em Tempo Real</a>
                                    </li>
                                    <li class="sub-menu">
                                        <a href="javascript:;">
                                            <i class="icon-bar-chart"></i>
                                            <span>Produtividade</span>
                                        </a>
                                        <ul class="sub">
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/logAtividade/produtivo" title="">Por período</a></li>
                                            <li>
                                                <a href="<?= Yii::app()->request->baseUrl ?>/Produtividade/RelatorioIndividual"
                                                   title="">Diária</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/logAtividade/custo" title="">Por custo</a></li>
                                            <li>
                                                <a href="<?= Yii::app()->request->baseUrl ?>/Produtividade/RelatorioRanking"
                                                   title="">Ranking</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/ColaboradorSemProdutividade/index" title="">Dias sem produtivdade</a></li>


                                        </ul>
                                    </li>

                                     <li class="sub-menu">
                                        <a href="javascript:;">
                                            <i class="icon-archive"></i>
                                            <span>Acesso a Programas</span>
                                        </a>
                                         <ul class="sub">
                                             <li>
                                                 <a href="<?= Yii::app()->request->baseUrl ?>/programasSites/RelatorioGeral"
                                                    title="">Produtividade geral</a></li>
                                             <li><a href="<?= Yii::app()->request->baseUrl ?>/logAtividade/TempoPessoaHorario" title="">Análise individual</a></li>
                                             <li><a href="<?= Yii::app()->request->baseUrl ?>/logAtividade/ModoPromiscuo" title="">Não reconhecidos</a></li>
                                         </ul>
                                     </li>
                                    <!-- <li class="sub-menu">
                                        <a href="javascript:;">
                                            <i class="icon-dollar"></i>
                                            <span>Financeiro</span>
                                        </a>
                                         <ul  class="sub">
                                             <li><a href="<?= Yii::app()->request->baseUrl ?>/usuario/financeiro" title="">Gerenciar conta </a></li>

                                         </ul>
                                     </li> -->
                                     <li class="sub-menu">
                                        <a href="javascript:;">
                                            <i class="icon-ok-sign"></i>
                                            <span>Indicações</span>
                                        </a>
                                         <ul class="sub">
                                             <li><a href="<?= Yii::app()->request->baseUrl ?>/usuario/convidar" title="">Convide seus amigos </a></li>
                                            <!-- <li><a href="<?= Yii::app()->request->baseUrl ?>/usuario/indicacoes" title="">Indicações Recebidas</a></li> -->
                                         </ul>
                                     </li>
                                    <li class="sub-menu">
                                        <a href="javascript:;" >
                                            <i class="icon-cogs"></i>
                                            <span>Configurações</span>
                                        </a>
                                        <ul class="sub">
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/colaborador" title="">Colaboradores</a>
                                            </li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/equipe" title="">Equipes</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/programaPermitido" title="">Programas Permitidos</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/sitePermitido" title="">Sites Permitidos</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/empresaHasParametro"
                                                   title="">Parâmetros</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/usuario/instalacao"  title="">Instalador</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/usuario/politicaSeguranca" title="">Política de Segurança</a></li>

                                        </ul>
                                    </li>


                                    <?php break;
                                case 'decoreadmin':
                                case 'root': ?>
                                    <li class="sub-menu">
                                        <a href="javascript:;">
                                            <i class="icon-archive"></i>
                                            <span>Gráficos</span>
                                        </a>
                                        <ul class="sub">
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/decorProjeto/create" title="">Criar Projeto</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/decorProjeto" title="">Ver Projetos</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/decorCotacao" title="">Cotações</a></li>
                                        </ul>
                                    </li>
                                    <li class="sub-menu">
                                        <a href="javascript:;" >
                                            <i class="icon-tasks"></i>
                                            <span>Configurações</span>
                                        </a>
                                        <ul class="sub">
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/contaPagar" title="">Contas a Pagar</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/contaReceber" title="">Contas a Receber</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/notaFiscal" title="">Notas Fiscais</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/contaFinanceira" title="">Contas Bancárias</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/patrimonio" title="">Patrimônio</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/funcionario" title="">Funcionários</a></li>
                                        </ul>
                                    </li>
                                    <li class="sub-menu">
                                        <a href="javascript:;">
                                            <i class=" icon-bar-chart"></i>
                                            <span>Relatórios</span>
                                        </a>
                                        <ul class="sub">
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/contaReceber/acompanhamentoParcelas" title="">Parcelas a receber</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/contaFinanceira/fluxoCaixa" title="">Fluxo de Caixa</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/patrimonio/relatorio/1" title="" target="_blank">Itens do patrimônio</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/pagamento/relatorio" title="">Pagamentos</a></li>
                                        </ul>
                                    </li>
                                    <li class="sub-menu">
                                        <a href="javascript:;">
                                            <i class="icon-book"></i>
                                            <span>Cadastros</span>
                                        </a>
                                        <ul class="sub">
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/cliente" title="">Clientes</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/fornecedor" title="">Fornecedores</a></li>
                                        </ul>
                                    </li>
                                    <li class="sub-menu">
                                        <a href="javascript:;">
                                            <i class="icon-cogs"></i>
                                            <span>Configurações</span>
                                        </a>
                                        <ul class="sub">
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/decorTipoAmbiente" title="">Ambientes</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/cargo" title="">Cargos</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/centroCusto" title="">Centros
                                                    de Custo</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/configuracoesGerais" title="">Configurações Gerais</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/usuario" title="">Gerenciar Usuários</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/ramoAtuacao" title="">Ramos de atuação</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/decorSegmento" title="">Segmentos</a>
                                            </li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/subcategoriaCentroCusto"
                                                   title="">Sub. de Centro de Custo</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/decorTipoTipologia"
                                                   title="">Tipologias</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/tipoDocumento" title="">Tipos de documento</a></li>
                                        </ul>
                                    </li>
                                    <li class="sub-menu">
                                        <a href="javascript:;">
                                            <i class="icon-cog"></i>
                                            <span>Decore</span>
                                        </a>
                                        <ul class="sub">
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/empresa" title="">Empresas</a></li>
                                            <li><a href="<?= Yii::app()->request->baseUrl ?>/decorProduto" title="">Produtos</a></li>
                                        </ul>
                                    </li>
                        <?php } ?>

                  <!--multi level menu start-->
<!--                  <li class="sub-menu">
                      <a href="javascript:;" >
                          <i class="icon-sitemap"></i>
                          <span>Multi level Menu</span>
                      </a>
                      <ul class="sub">
                          <li><a  href="javascript:;">Menu Item 1</a></li>
                          <li class="sub-menu">
                              <a  href="boxed_page.html">Menu Item 2</a>
                              <ul class="sub">
                                  <li><a  href="javascript:;">Menu Item 2.1</a></li>
                                  <li class="sub-menu">
                                      <a  href="javascript:;">Menu Item 3</a>
                                      <ul class="sub">
                                          <li><a  href="javascript:;">Menu Item 3.1</a></li>
                                          <li><a  href="javascript:;">Menu Item 3.2</a></li>
                                      </ul>
                                  </li>
                              </ul>
                          </li>
                      </ul>
                  </li>-->
                  <!--multi level menu end-->

              </ul>
              <!-- sidebar menu end-->
          </div>
      </aside>
      <!--sidebar end-->
      <!--main content start-->

      <section id="main-content" style="margin-left: 0px;">


          <section class="wrapper site-min-height">
              <?php
      $action = Yii::app()->getController()->getAction()->id;
      $controller = Yii::app()->getController()->id;

              if ($action == 'index' && $controller == 'logAtividade') {
        //Overview Produtividade Mensal Corrente
                  $prdGeral = LogAtividade::model()->getPrdGeralHome();
            $duracaoLog = LogAtividade::model()->getTempoPrdGeralHome();

            if($duracaoLog[0]['duracao_periodo'] == NULL || $prdGeral[0]['hora_dia'] == NULL )
            $prdMesEmpresa = 0 ;
            else
            $prdMesEmpresa = round(($duracaoLog[0]['duracao_periodo']*100) / $prdGeral[0]['hora_dia'],0);

                  //OverView quantidade de usuários
            $usuario = UserGroupsUser::model()->findByPk(Yii::app()->user->id);
          $qtdUsuarios = Colaborador::model()->findAll(array("condition" => "fk_empresa=" . $usuario->fk_empresa));
            $qtdUsuarios = count($qtdUsuarios);


                  //OverView Variação de Produtividade
                  $prdGeralMesAnterior = LogAtividade::model()->getPrdGeralHome('variacao');
            $duracaoVar = LogAtividade::model()->getTempoPrdGeralHome('variacao');
	    if($duracaoVar[0]['duracao_periodo'] == NULL || $prdGeralMesAnterior[0]['hora_dia'] == NULL)
	    $varMes = 0;
	    else
            $varMes = round(($duracaoVar[0]['duracao_periodo']*100) / $prdGeralMesAnterior[0]['hora_dia'],0);


                  ?>
              <div class="row state-overview">
                  <div class="col-lg-3 col-sm-6">
                      <section class="panel">
                          <div class="symbol terques">
                              <i class="icon-book"></i>
                          </div>
                          <div class="value">
                              <h1 class="count"><?php echo $prdMesEmpresa ?></h1>
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
                              <h1 class=" count4"><?php echo $prdMesEmpresa - $varMes; ?></h1>
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
				  <form class="form valid" id="usuario-form" action="/usuario/contato" method="post">
				  <div class="modal fade" id="reportarErro" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                  <div class="modal-dialog">
                                      <div class="modal-content">
                                          <div class="modal-header">
                                              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                              <h4 class="modal-title">Contato</h4>
                                          </div>
                                          <div class="modal-body">

                                              <div class="form-group  col-lg-12">
                                                    <p>
                                                                                                              <textarea rows="4" cols="50"  class="form-control" name="contato" id="contato" type="text" placeholder="Digite aqui sua dúvida"> </textarea>                                                   </p>
                                                </div>
                                                <div style="clear: both"></div>




                                          </div>
                                          <div style="clear: both"></div>
                                          <div class="modal-footer">
                                              <button data-dismiss="modal" class="btn btn-default" type="button">Fechar</button>
                                              <input class="btn btn-info submitForm" type="submit" name="yt1" value="Enviar">                                          </div>
                                      </div>
                                  </div>
                              </div>
					  </form>
                  <div class="col-lg-12 title">
                      <!--breadcrumbs start -->
                      <?php if(isset($this->breadcrumbs)):?>
                            <?php if ($this->breadcrumbs) {?>
                                 <?php $this->widget('zii.widgets.CBreadcrumbs', array(
                                        'links'=>$this->breadcrumbs,
                                        'tagName'=>'ul',
                                        'htmlOptions' => array('class' => 'breadcrumb'),
                                        'homeLink'=>'<li><a href="'.Yii::app()->homeUrl.'"><i class="icon-home"></i> Página Inicial</a></li>',
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
      <footer class="site-footer">
          <div class="text-center">
              Copyright &copy; <?php echo date('Y'); ?> por Viva Inovação. Todos os direitos reservados.
              <a href="#" class="go-top">
                  <i class="icon-angle-up"></i>
              </a>
          </div>
      </footer>
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
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/count.js', CClientScript::POS_END);?>

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
