<li>
    <a href="<?= Yii::app()->request->baseUrl ?>/logAtividade" title=""
       onClick="Loading.show(); return true;">
        <i class="entypo-monitor"></i>
        <span class="title"><?php echo Yii::t("smith", 'Atividades em tempo real') ?></span>
    </a>
</li>
<li>
    <a href="<?= Yii::app()->request->baseUrl ?>/atividadeExterna" title="">
        <i class="entypo-plus"></i>
        <span class="title"><?php echo Yii::t("smith", 'Atividades externas') ?></span>
    </a>
</li>
<li>
    <a href="#">
        <i class="entypo-chart-bar"></i>
        <span class="title"><?php echo Yii::t("smith", 'Produtividade') ?></span>
    </a>
    <ul>
        <li>
            <a href="<?= Yii::app()->request->baseUrl ?>/Produtividade/RelatorioEquipe">
                <span class="title"><?php echo Yii::t("smith", 'Relatório por equipe') ?></span>
            </a>
        </li>
        <li>
            <a href="<?= Yii::app()->request->baseUrl ?>/Produtividade/RelatorioIndividual">
                <span class="title"><?php echo Yii::t("smith", 'Relatório individual (diário|mensal|anual)') ?></span>
            </a>
        </li>
        <li>
            <a href="<?= Yii::app()->request->baseUrl ?>/produtividade/RelatorioIndividualDias">
                <span class="title"><?php echo Yii::t("smith", 'Relatório individual em dias') ?></span>
            </a>
        </li>
        <li>
            <a href="<?= Yii::app()->request->baseUrl ?>/produtividade/RelatorioCusto" title="">
                <span class="title"> <?php echo Yii::t("smith", 'Relatório por custo') ?></span>
            </a>
        </li>
        <li>
            <a href="<?= Yii::app()->request->baseUrl ?>/produtividade/RelatorioRanking" title="">
                <span class="title"> <?php echo Yii::t("smith", 'Relatório de ranking') ?></span>
            </a>
        </li>
        <li>
            <a href="<?= Yii::app()->request->baseUrl ?>/produtividade/RelatorioHoraExtra" title="">
                <span class="title"> <?php echo Yii::t("smith", 'Relatório de hora extra') ?> </span>
            </a>
        </li>
        <li>
            <a href="<?= Yii::app()->request->baseUrl ?>/produtividade/RelatorioComparativo" title="">
                <span class="title"> <?php echo Yii::t("smith", 'Relatório comparativo') ?> </span>
            </a>
        </li>
        <li>
            <a href="<?= Yii::app()->request->baseUrl ?>/produtividade/RelatorioPonto" title="">
                <span class="title"> <?php echo Yii::t("smith", 'Relatório de ponto') ?> </span>
            </a>
        </li>
        <li>
            <a href="<?= Yii::app()->request->baseUrl ?>/ColaboradorSemProdutividade/index" title="">
                <span class="title"> <?php echo Yii::t("smith", 'Relatório de dias sem produtividade') ?> </span>
            </a>
        </li>
    </ul>
</li>
<li>
    <a href="#">
        <i class="entypo-archive"></i>
        <span class="title"><?php echo Yii::t("smith", 'Programas e Sites') ?></span>
    </a>
    <ul>
        <li>
            <a href="<?= Yii::app()->request->baseUrl ?>/programasSites/RelatorioGeral">
                <span class="title"><?php echo Yii::t("smith", 'Relatório geral') ?></span>
            </a>
        </li>
        <li>
            <a href="<?= Yii::app()->request->baseUrl ?>/programasSites/RelatorioIndividual">
                <span class="title"><?php echo Yii::t("smith", 'Relatório individual') ?></span>
            </a>
        </li>
    </ul>
</li>
<li>
    <a href="#">
        <i class="entypo-doc-text-inv"></i>
        <span class="title"><?php echo Yii::t("smith", 'Métricas') ?></span>
    </a>
    <ul>
        <li>
            <a href="<?= Yii::app()->request->baseUrl ?>/metrica">
                <span class="title"><?php echo Yii::t("smith", 'Gerenciar métricas') ?></span>
            </a>
        </li>
        <li>
            <a href="<?= Yii::app()->request->baseUrl ?>/metrica/create">
                <span class="title"><?php echo Yii::t("smith", 'Cadastrar métrica') ?></span>
            </a>
        </li>
    </ul>
</li>


<?php if ($tipo_empresa == "projetos") { ?>
    <li>
        <a href="javascript:;">
            <i class="entypo-suitcase"></i>
            <span class="title"><?php echo Yii::t("smith", 'Contratos') ?></span>
        </a>
        <ul>
            <li>
                <a href="<?= Yii::app()->request->baseUrl ?>/contrato" title="">
                    <span class="title"> <?php echo Yii::t("smith", 'Gerenciar contratos') ?> </span>
                </a>
            </li>
            <li>
                <a href="<?= Yii::app()->request->baseUrl ?>/contrato/create" title="">
                   <span class="title"> <?php echo Yii::t("smith", 'Cadastrar contrato') ?> </span>
                </a>
            </li>
            <!--<li><a href="<?= Yii::app()->request->baseUrl ?>/Contrato/andamentoObra" title=""><?php echo Yii::t("smith", 'Andamento') ?></a></li>-->
            <li>
                <a href="<?= Yii::app()->request->baseUrl ?>/contrato/relatorioIndividual" title="">
                   <span class="title"> <?php echo Yii::t("smith", 'Relatório individual') ?> </span>
                </a>
            </li>
            <li>
                <a href="<?= Yii::app()->request->baseUrl ?>/contrato/produtividadeColaborador" title="">
                   <span class="title"> <?php echo Yii::t("smith", 'Relatório de produtividade do colaborador') ?> </span>
                </a>
            </li>
            <li>
                <a href="<?= Yii::app()->request->baseUrl ?>/contrato/relatorioGeral" title="">
                   <span class="title"> <?php echo Yii::t("smith", 'Relatório geral') ?> </span>
                </a>
            </li>
            <li>
                <a href="<?= Yii::app()->request->baseUrl ?>/contrato/relatorioContratoFinalizado" title="">
                    <span class="title"> <?php echo Yii::t("smith", 'Relatório de contratos finalizados') ?></span>
                </a>
            </li>
            <li>
                <a href="<?= Yii::app()->request->baseUrl ?>/contrato/relatorioContratoEmAtraso" title="">
                   <span class="title"> <?php echo Yii::t("smith", 'Relatório de contratos em atraso') ?> </span>
                </a>
            </li>
            <li>
                <a href="<?= Yii::app()->request->baseUrl ?>/contrato/custoEnergia" title="">
                  <span class="title">  <?php echo Yii::t("smith", 'Relatório de consumo de energia') ?> </span>
                </a>
            </li>
            <li>
                <a href="<?= Yii::app()->request->baseUrl ?>/disciplina" title="">
                    <span class="title"> <?php echo Yii::t("smith", 'Disciplinas/Fases') ?> </span>
                </a>
            </li>
            <li>
                <a href="<?= Yii::app()->request->baseUrl ?>/documentoSemContrato" title="">
                   <span class="title"> <?php echo Yii::t("smith", 'Documentos sem contrato') ?> </span>
                </a>
            </li>
        </ul>
    </li>
<?php } ?>


<li>
    <a href="javascript:;">
        <i class="entypo-cog"></i>
        <span class="title"><?php echo Yii::t("smith", 'Configurações') ?></span>
    </a>
    <ul>
        <li><a href="<?= Yii::app()->request->baseUrl ?>/colaborador" title="">
              <span class="title">  <?php echo Yii::t("smith", 'Colaboradores') ?>  </span>
            </a>
        </li>

        <li>
            <a href="<?= Yii::app()->request->baseUrl ?>/equipe" title="">
               <span class="title"> <?php echo Yii::t("smith", 'Equipes') ?> </span>
            </a>
        </li>
        <?php if ($tipo_empresa == "projetos") { ?>
            <li>
                <a href="<?= Yii::app()->request->baseUrl ?>/usuario" title="">
                   <span class="title">  <?php echo Yii::t("smith", 'Coordenadores') ?> </span>
                </a>
            </li>
        <?php } ?>
        <li>
            <a href="<?= Yii::app()->request->baseUrl ?>/programaPermitido" title="">
              <span class="title">  <?php echo Yii::t("smith", 'Programas permitidos') ?> </span>
            </a>
        </li>
        <li>
            <a href="<?= Yii::app()->request->baseUrl ?>/ListaNegraPrograma/" title="">
              <span class="title">  <?php echo Yii::t("smith", 'Programas não permitidos') ?> </span>
            </a>
        </li>
        <li>
            <a href="<?= Yii::app()->request->baseUrl ?>/sitePermitido" title="">
               <span class="title"> <?php echo Yii::t("smith", 'Sites permitidos') ?> </span>
            </a>
        </li>
        <li>
            <a href="<?= Yii::app()->request->baseUrl ?>/listaNegraSite" title="">
               <span class="title">  <?php echo Yii::t("smith", 'Sites não permitidos') ?> </span>
            </a>
        </li>
        <li>
            <a href="<?= Yii::app()->request->baseUrl ?>/calendarioFeriados" title="">
                <span class="title">  <?php echo Yii::t("smith", 'Calendário de feriados') ?> </span>
            </a>
        </li>
        <li>
            <a href="<?= Yii::app()->request->baseUrl ?>/empresaHasParametro" title="">
                <span class="title">   <?php echo Yii::t("smith", 'Parâmetros de empresa') ?> </span>
            </a>
        </li>
        <li>
            <a href="<?= Yii::app()->request->baseUrl ?>/empresaHasParametro/instalador" title="">
               <span class="title"> <?php echo Yii::t("smith", 'Baixar instalador') ?> </span>
            </a>
        </li>
        <li>
            <a href="<?= Yii::app()->request->baseUrl ?>/LogCentralNotificacao/index" title="">
                <span class="title"> <?php echo Yii::t("smith", 'Log de notificações') ?> </span>
            </a>
        </li>
    </ul>
</li>
<li>
    <a href="<?= Yii::app()->request->baseUrl ?>/api/documentacao" title="">
        <i class="entypo-cloud"></i>
        <span class="title"><?php echo Yii::t("smith", 'API') ?></span>
    </a>
</li>
<li>
    <a href="<?= Yii::app()->request->baseUrl ?>/help" target="_blank" title="">
        <i class="entypo-book"></i>
        <span class="title"><?php echo Yii::t("smith", 'Manual do usuário') ?></span>
    </a>
</li>


