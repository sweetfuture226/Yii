
<li class="sub-menu">
    <?php $this->widget('ext.widgets.loading.LoadingWidget'); ?>
    <a href="<?= Yii::app()->request->baseUrl ?>/logAtividade" title=""
       onClick = "Loading.show(); return true;">
        <i class="icon-bell"></i>
        <span><?php echo Yii::t("smith",'Atividades em tempo real') ?></span>
    </a>
</li>
<li class="sub-menu">
    <a href="javascript:;">
        <i class="icon-bar-chart"></i>
        <span><?php echo Yii::t("smith",'Produtividade') ?></span>
    </a>
    <ul class="sub">
        <li><a href="<?= Yii::app()->request->baseUrl ?>/Produtividade/RelatorioEquipe"
               title=""><?php echo Yii::t("smith", 'Relatório por equipe') ?></a></li>
        <li><a href="<?= Yii::app()->request->baseUrl ?>/Produtividade/RelatorioIndividual"
               title=""><?php echo Yii::t("smith", 'Relatório individual diário') ?></a></li>
        <li><a href="<?= Yii::app()->request->baseUrl ?>/produtividade/RelatorioIndividualDias"
               title=""><?php echo Yii::t("smith", 'Relatório individual em dias') ?></a>
        <li><a href="<?= Yii::app()->request->baseUrl ?>/produtividade/RelatorioCusto"
               title=""><?php echo Yii::t("smith", 'Relatório por custo') ?></a></li>
        <li><a href="<?= Yii::app()->request->baseUrl ?>/produtividade/RelatorioRanking"
               title=""><?php echo Yii::t("smith", 'Relatório de ranking') ?></a></li>
        <li><a href="<?= Yii::app()->request->baseUrl ?>/produtividade/RelatorioHoraExtra"
               title=""><?php echo Yii::t("smith", 'Relatório de hora extra') ?></a></li>
        <li><a href="<?= Yii::app()->request->baseUrl ?>/produtividade/RelatorioPonto"
               title=""><?php echo Yii::t("smith", 'Relatório de ponto') ?></a></li>
        <li><a href="<?= Yii::app()->request->baseUrl ?>/ColaboradorSemProdutividade/index" title=""><?php echo Yii::t("smith",'Relatório de dias sem produtividade') ?></a></li>
    </ul>
</li>
<li class="sub-menu">
    <a href="javascript:;">
        <i class="icon-archive"></i>
        <span><?php echo Yii::t("smith",'Programas e Sites') ?></span>
    </a>
    <ul class="sub">
        <li><a href="<?= Yii::app()->request->baseUrl ?>/programasSites/RelatorioGeral"
               title=""><?php echo Yii::t("smith", 'Relatório geral') ?></a></li>
        <li><a href="<?= Yii::app()->request->baseUrl ?>/programasSites/RelatorioIndividual"
               title=""><?php echo Yii::t("smith", 'Relatório individual') ?></a></li>
        <li><a href="<?= Yii::app()->request->baseUrl ?>/ListaNegraPrograma/"
               title=""><?php echo Yii::t("smith", 'Relatório de programas blacklist') ?></a></li>
        <li><a href="<?= Yii::app()->request->baseUrl ?>/listaNegraSite" title=""><?php echo Yii::t("smith",'Relatório de sites blacklist') ?></a></li>
    </ul>
</li>

<li class="sub-menu">
    <a href="javascript:;">
        <i class="icon-reorder"></i>
        <span><?php echo Yii::t("smith",'Métricas') ?></span>
    </a>
    <ul class="sub">
        <li><a href="<?= Yii::app()->request->baseUrl ?>/metrica"
               title=""><?php echo Yii::t("smith", 'Gerenciar métricas') ?></a></li>
        <li><a href="<?= Yii::app()->request->baseUrl ?>/metrica/create" title=""><?php echo Yii::t("smith",'Cadastrar métrica') ?></a></li>
    </ul>
</li>

<?php if ($tipo_empresa == "projetos"){ ?>
    <li class="sub-menu">
        <a href="javascript:;">
            <i class="icon-suitcase"></i>
            <span><?php echo Yii::t("smith",'Contratos') ?></span>
        </a>
        <ul class="sub">
            <li><a href="<?= Yii::app()->request->baseUrl ?>/contrato"
                   title=""><?php echo Yii::t("smith", 'Gerenciar contratos') ?></a></li>
            <li><a href="<?= Yii::app()->request->baseUrl ?>/contrato/create"
                   title=""><?php echo Yii::t("smith", 'Cadastrar contrato') ?></a></li>
            <!--<li><a href="<?= Yii::app()->request->baseUrl ?>/Contrato/andamentoObra" title=""><?php echo Yii::t("smith", 'Andamento') ?></a></li>-->
            <li><a href="<?= Yii::app()->request->baseUrl ?>/contrato/relatorioIndividual"
                   title=""><?php echo Yii::t("smith", 'Relatório individual') ?></a></li>
            <li><a href="<?= Yii::app()->request->baseUrl ?>/contrato/produtividadeColaborador"
                   title=""><?php echo Yii::t("smith", 'Relatório de produtividade do colaborador') ?></a></li>
            <li><a href="<?= Yii::app()->request->baseUrl ?>/contrato/relatorioGeral"
                   title=""><?php echo Yii::t("smith", 'Relatório geral') ?></a></li>
            <li><a href="<?= Yii::app()->request->baseUrl ?>/contrato/custoEnergia"
                   title=""><?php echo Yii::t("smith", 'Relatório de consumo de energia') ?></a></li>
            <li><a href="<?= Yii::app()->request->baseUrl ?>/atividadeExterna" title=""><?php echo Yii::t("smith",'Atividades externas') ?></a></li>

            <li><a href="<?= Yii::app()->request->baseUrl ?>/disciplina" title=""><?php echo Yii::t("smith",'Disciplinas/Fases') ?></a></li>

        </ul>
    </li>
<?php } ?>

<li class="sub-menu">
    <a href="javascript:;" >
        <i class="icon-cogs"></i>
        <span><?php echo Yii::t("smith",'Configurações') ?></span>
    </a>
    <ul class="sub">
        <li><a href="<?= Yii::app()->request->baseUrl ?>/colaborador"
               title=""><?php echo Yii::t("smith", 'Colaboradores') ?></a></li>

        <li><a href="<?= Yii::app()->request->baseUrl ?>/equipe" title=""><?php echo Yii::t("smith",'Equipes') ?></a></li>
        <?php if ($tipo_empresa == "projetos"){ ?>
            <li><a href="<?= Yii::app()->request->baseUrl ?>/usuario" title=""><?php echo Yii::t("smith",'Coordenadores') ?></a></li>
        <?php } ?>
        <li><a href="<?= Yii::app()->request->baseUrl ?>/programaPermitido" title=""><?php echo Yii::t("smith",'Programas permitidos') ?></a></li>
        <li><a href="<?= Yii::app()->request->baseUrl ?>/sitePermitido" title=""><?php echo Yii::t("smith",'Sites permitidos') ?></a></li>

        <li><a href="<?= Yii::app()->request->baseUrl ?>/empresaHasParametro"
               title=""><?php echo Yii::t("smith", 'Parâmetros de empresa') ?></a></li>
        <li><a href="<?= Yii::app()->request->baseUrl ?>/empresaHasParametro/instalador"
               title=""><?php echo Yii::t("smith", 'Baixar instalador') ?></a></li>
        <?php if ($usuario->fk_empresa == 2) { ?>
            <li><a href="<?= Yii::app()->request->baseUrl ?>/empresa" title=""><?php echo Yii::t("smith",'Listar Empresas') ?></a></li>
        <?php }?>
    </ul>
</li>
