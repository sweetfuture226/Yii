<li class="sub-menu"><a href="<?= Yii::app()->request->baseUrl ?>/atividadeExterna"
                        title=""><i class="icon-plus"></i><?php echo Yii::t("smith", 'Atividades externas') ?></a></li>
<li class="sub-menu">
    <a href="javascript:;">
        <i class="icon-bar-chart"></i>
        <span><?php echo Yii::t("smith",'Produtividade') ?></span>
    </a>
    <ul class="sub">
        <li><a href="<?= Yii::app()->request->baseUrl ?>/Produtividade/RelatorioEquipe"
               title=""><?php echo Yii::t("smith", 'Relatório por equipe') ?></a></li>
        <li><a href="<?= Yii::app()->request->baseUrl ?>/Produtividade/RelatorioIndividual"
               title=""><?php echo Yii::t("smith", 'Relatório individual (diário|mensal|anual)') ?></a></li>
        <li><a href="<?= Yii::app()->request->baseUrl ?>/programasSites/RelatorioGeral"
               title=""><?php echo Yii::t("smith", 'Relatório geral') ?></a></li>
        <li><a href="<?= Yii::app()->request->baseUrl ?>/programasSites/RelatorioIndividual"
               title=""><?php echo Yii::t("smith", 'Relatório individual') ?></a></li>
    </ul>
</li>
<li class="sub-menu">
    <a href="javascript:;">
        <i class="icon-suitcase"></i>
        <span><?php echo Yii::t("smith",'Contratos') ?></span>
    </a>
    <ul class="sub">
        <li><a href="<?= Yii::app()->request->baseUrl ?>/Contrato"
               title=""><?php echo Yii::t("smith", 'Gerenciar contratos') ?></a></li>
        <?php if (MetodosGerais::checkPermissionAccessContract()) { ?>
        <li><a href="<?= Yii::app()->request->baseUrl ?>/Contrato/create"
               title=""><?php echo Yii::t("smith", 'Cadastrar contrato') ?></a></li>
        <?php } ?>
        <li><a href="<?= Yii::app()->request->baseUrl ?>/contrato/relatorioIndividual"
               title=""><?php echo Yii::t("smith", 'Relatório individual') ?></a></li>
        <li><a href="<?= Yii::app()->request->baseUrl ?>/contrato/produtividadeColaborador"
               title=""><?php echo Yii::t("smith", 'Relatório de produtividade do colaborador') ?></a></li>
        <li><a href="<?= Yii::app()->request->baseUrl ?>/Contrato/custoEnergia"
               title=""><?php echo Yii::t("smith", 'Relatório de consumo de energia') ?></a></li>
        <li><a href="<?= Yii::app()->request->baseUrl ?>/disciplina" title=""><?php echo Yii::t("smith",'Disciplinas/Fases') ?></a></li>
        <li><a href="<?= Yii::app()->request->baseUrl ?>/documentoSemContrato" title=""><?php echo Yii::t("smith",'Documentos sem contrato') ?></a></li>

    </ul>
</li>
<li class="sub-menu">
    <a href="<?= Yii::app()->request->baseUrl ?>/help" target="_blank" title="">
        <i class="icon-book"></i>
        <span><?php echo Yii::t("smith",'Manual do usuário') ?></span>
    </a>
</li>
