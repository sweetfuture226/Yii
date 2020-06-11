<li>
    <a href="javascript:;">
        <i class="entypo-briefcase"></i>
        <span><?php echo Yii::t("smith", 'Empresa') ?></span>
    </a>
    <ul>
        <li><a href="<?= Yii::app()->request->baseUrl ?>/empresa"
               title=""><?php echo Yii::t("smith", 'Visualizar Empresas') ?></a></li>
        <li><a href="<?= Yii::app()->request->baseUrl ?>/empresa/indexPoc"
               title=""><?php echo Yii::t("smith", 'Visualizar POC\'s') ?></a></li>
        <li><a href="<?= Yii::app()->request->baseUrl ?>/empresa/create"
               title=""><?php echo Yii::t("smith", 'Nova Empresa') ?></a></li>
        <li><a href="<?= Yii::app()->request->baseUrl ?>/empresa/contrato"
               title=""><?php echo Yii::t("smith", 'Contratos das empresas') ?></a></li>
        <li><a href="<?= Yii::app()->request->baseUrl ?>/empresa/usuarios"
               title=""><?php echo Yii::t("smith", 'Colaboradores a mais') ?></a></li>
        <li><a href="<?= Yii::app()->request->baseUrl ?>/painelControle/empresaSemCaptura"
               title=""><?php echo Yii::t("smith", 'Empresas sem captura') ?></a></li>
        <li><a href="<?= Yii::app()->request->baseUrl ?>/painelControle/baixarBackup"
               title=""><?php echo Yii::t("smith", 'Baixar backup') ?></a></li>
        <li><a href="<?= Yii::app()->request->baseUrl ?>/painelControle/avaliacaoGlobal"
               title=""><?php echo Yii::t("smith", 'Avaliação global') ?></a></li>
    </ul>
</li>
<li>
    <a href="javascript:;">
        <i class="entypo-chart-bar"></i>
        <span><?php echo Yii::t("smith", 'Estatísticas') ?></span>
    </a>
    <ul>
        <li><a href="<?= Yii::app()->request->baseUrl ?>/logAcesso"
                                title=""><?php echo Yii::t("smith", 'Acesso aos relatórios') ?></a></li>
        <li><a href="<?= Yii::app()->request->baseUrl ?>/painelControle/altasMedicoes"
                                title=""><?php echo Yii::t("smith", 'Medições altas') ?></a></li>
        <li><a href="<?= Yii::app()->request->baseUrl ?>/painelControle/OcioAposExpediente"
                                title=""><?php echo Yii::t("smith", 'Ócio após expediente') ?></a></li>
        <li><a href="<?= Yii::app()->request->baseUrl ?>/painelControle/DocumentoFinalizado"
                                title=""><?php echo Yii::t("smith", 'Documentos finalizados') ?></a></li>
        <li><a href="<?= Yii::app()->request->baseUrl ?>/painelControle/TentativaDesinstalacao"
                                title=""><?php echo Yii::t("smith", 'Tentativa desinstalação') ?></a></li>

    </ul>
</li>

<li>
    <a href="javascript:;">
        <i class="entypo-cog"></i>
        <span><?php echo Yii::t("smith", 'Configurações') ?></span>
    </a>
    <ul>
        <li><a href="<?= Yii::app()->request->baseUrl ?>/painelControle/TraducaoColaborativa"
                                title=""><?php echo Yii::t("smith", 'Tradução colaborativa') ?></a></li>
        <li><a href="<?= Yii::app()->request->baseUrl ?>/empresa/parametrizar"
                                title=""><?php echo Yii::t("smith", 'Parametrização colaboradores') ?></a></li>
        <li><a href="<?= Yii::app()->request->baseUrl ?>/empresa/povoarTabelas"
                                title=""><?php echo Yii::t("smith", 'Povoar tabelas consolidadas') ?></a></li>
        <li><a href="<?= Yii::app()->request->baseUrl ?>/empresa/redefinirSenha"
                                title=""><?php echo Yii::t("smith", 'Redefinição de senha') ?></a></li>

    </ul>
</li>
<li><a href="<?= Yii::app()->request->baseUrl ?>/help" target="_blank" title="">
        <i class="entypo-book"></i>
        <span><?php echo Yii::t("smith", 'Manual do usuário') ?></span>
    </a>
</li>
