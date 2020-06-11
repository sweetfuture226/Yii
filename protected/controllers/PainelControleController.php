<?php

class PainelControleController extends Controller
{
    public $title_action = "";

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'userGroupsAccessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow', // allow all users to perform 'view' action
                'actions' => array('AltasMedicoes', 'TraducaoColaborativa', 'UpdateLiteral', 'TentativaDesinstalacao', 'DocumentoFinalizado', 'EmpresaSemCaptura',
                    'OcioAposExpediente', 'baixarBackup', 'avaliacaoGlobal'),
                'groups' => array('root'),
            ),
            array('allow',
                'actions' => array('TraducaoColaborativa', 'UpdateLiteral'),
                'groups' => array('tradutor')
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionDocumentoFinalizado()
    {
        $this->title_action = Yii::t('smith', 'Documentos finalizados');
        $this->pageTitle = Yii::t('smith', 'Documentos finalizados');
        $model = new GrfProjetoConsolidado('searchDocumentoFinalizado');
        $model->unsetAttributes();
        if (isset($_GET['GrfProjetoConsolidado']))
            $model->attributes = $_GET['GrfProjetoConsolidado'];

        $this->render('documentoFinalizado', array('model' => $model));
    }

    public function actionAltasMedicoes()
    {
        $this->title_action = Yii::t('smith', 'Medições acima de 1 hora');
        $this->pageTitle = Yii::t('smith', 'Medições acima de 1 hora');
        $model = new LogAtividade('searchAltasMedicoes');
        $model->unsetAttributes();
        if (isset($_GET['LogAtividade']))
            $model->attributes = $_GET['LogAtividade'];

        $this->render('altasMedicoes', array('model' => $model));
    }

    public function actionTraducaoColaborativa()
    {
        $this->title_action = Yii::t('smith', 'Tradução colaborativa');
        $this->pageTitle = Yii::t('smith', 'Tradução colaborativa');
        $modelLiterais = new TraducaoLiteral('search');
        $modelLiterais->unsetAttributes();
        if (isset($_GET['TraducaoLiteral']))
            $modelLiterais->attributes = $_GET['TraducaoLiteral'];
        $this->render('traducaoColaborativa', array('model' => $modelLiterais));
    }

    public function actionUpdateLiteral()
    {
        if (!empty($_POST['id'])) {
            $modelEn = Traducao::model()->findByAttributes(array('id' => $_POST['id'], 'language' => 'en'));
            $modelEs = Traducao::model()->findByAttributes(array('id' => $_POST['id'], 'language' => 'es'));
            $modelUk = Traducao::model()->findByAttributes(array('id' => $_POST['id'], 'language' => 'uk'));
            $this->salvarTraducao($modelEn, 'en', $_POST);
            $this->salvarTraducao($modelEs, 'es', $_POST);
            $this->salvarTraducao($modelUk, 'uk', $_POST);
        }

    }

    public function actionTentativaDesinstalacao()
    {
        $this->title_action = Yii::t('smith', 'Tentativas de desinstalação do Viva Smith');
        $this->pageTitle = Yii::t('smith', 'Tentativas de desinstalação do Viva Smith');
        $model = new LogAtividadeConsolidado('searchTentativas');
        $model->unsetAttributes();
        if (isset($_GET['LogAtividadeConsolidado']))
            $model->attributes = $_GET['LogAtividadeConsolidado'];

        $this->render('tentativaDesinstalacao', array('model' => $model));
    }

    public function actionEmpresaSemCaptura()
    {
        $this->title_action = Yii::t('smith', 'Empresas sem captura por mais de 48h');
        $this->pageTitle = Yii::t('smith', 'Empresas sem captura por mais de 48h');

        $model = new EmpresaSemCaptura('search');
        $model->unsetAttributes();  // clear any default values

        if (isset($_GET['EmpresaSemCaptura'])) $model->attributes = $_GET['EmpresaSemCaptura'];

        $this->render('empresaSemCaptura', array('model' => $model));
    }

    public function actionBaixarBackup() {
        $this->title_action = Yii::t('smith', 'Baixar backup de dados de uma empresa');
        $this->pageTitle = Yii::t('smith', 'Baixar backup de dados de uma empresa');

        $model = new Empresa('search');
        $model->unsetAttributes();  // clear any default values

        if (isset($_GET['BaixarBackup'])) $model->attributes = $_GET['BaixarBackup'];

        if (!empty($_POST)) {
            $root = Yii::getPathofAlias('webroot') . '/public/';
            $rootPath = realpath($root .'backupRelatorios/'. $_POST['empresa']);
            if (is_dir($rootPath)) {
                $relPath = dirname($root) . 'Backup_'. $_POST['empresa'] .'.zip';

                // Initialize archive object
                $zip = new ZipArchive();
                $zip->open($relPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

                // Create recursive directory iterator
                /** @var SplFileInfo[] $files */
                $files = new RecursiveIteratorIterator(
                    new RecursiveDirectoryIterator($rootPath),
                    RecursiveIteratorIterator::LEAVES_ONLY
                );

                foreach ($files as $name => $file) {
                    // Skip directories (they would be added automatically)
                    if (!$file->isDir()) {
                        // Get real and relative path for current file
                        $filePath = $file->getRealPath();
                        $relativePath = substr($filePath, strlen($rootPath) + 1);

                        // Add current file to archive
                        $zip->addFile($filePath, utf8_encode($relativePath));
                    }
                }

                // Zip archive will be created only after closing object
                $zip->close();

                header("Pragma: public");
                header("Expires: 0");
                header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                header("Cache-Control: public");
                header("Content-Description: File Transfer");
                header("Content-type: application/octet-stream");
                header('Content-Disposition: attachment; filename=Backup_'. $_POST['empresa'] .'.zip');
                header("Content-Transfer-Encoding: binary");
                header('Content-length: ' . filesize($relPath));
                readfile($relPath);
                flush();
                unlink($relPath);
            } else {
                Yii::app()->user->setFlash('error', Yii::t('smith', 'Não existe backup da empresa selecionada.'));
                $this->refresh();
            }
        }

        $this->render('baixarBackup', array('model' => $model));
    }

    public function actionOcioAposExpediente()
    {
        $this->title_action = Yii::t('smith', 'Indícios de ócio após expediente');
        $this->pageTitle = Yii::t('smith', 'Indícios de ócio após expediente');
        $model = new LogAtividade('searchOcioAposExpediente');
        $model->unsetAttributes();
        if (isset($_GET['LogAtividade']))
            $model->attributes = $_GET['LogAtividade'];

        $this->render('ocioAposExpediente', array('model' => $model));
    }

    public function actionAvaliacaoGlobal()
    {
        $this->title_action = Yii::t('smith', 'Avaliação global das empresas');
        $this->pageTitle = Yii::t('smith', 'Avaliação global das empresas');
        if (!empty($_POST)) {
            $empresa = Empresa::model()->findByPK($_POST['empresa']);
            $dataInicio = MetodosGerais::dataAmericana($_POST['date_from']);
            $dataFim = MetodosGerais::dataAmericana($_POST['date_to']);
            $diffDias = MetodosGerais::DataDiff($dataInicio, $dataFim);
            $dataIincioAnterior = date('Y-m-d', strtotime('-' . ($diffDias + 1) . ' days', strtotime($dataInicio)));
            $dataFimAnterior = date('Y-m-d', strtotime('-' . ($diffDias + 1) . ' days', strtotime($dataFim)));

            /*Tópico 1 - Produtividade*/
            $produtividadeEquipes = PainelControle::getProdutividadeEquipesRelGlobal($dataInicio, $dataFim, $dataIincioAnterior, $dataFimAnterior);
            $custo = PainelControle::getCustoRelGlobal($dataInicio, $dataFim, $dataIincioAnterior, $dataFimAnterior);
            $produtividadeColaborador = PainelControle::getProdutividadeColaboradorRelGlobal($dataInicio, $dataFim);
            $horaExtra = PainelControle::getHoraExtraRelGlobal($dataInicio, $dataFim, $dataIincioAnterior, $dataFimAnterior);
            $ausenciaColaborador = PainelControle::getAusenciaColaboradoresRelGlobal($dataInicio, $dataFim, $dataIincioAnterior, $dataFimAnterior);
            $mediaHorasTrabalhadas = PainelControle::getMediaHorasTrabalhadasRelGlobal($dataInicio, $dataFim, $dataIincioAnterior, $dataFimAnterior, $diffDias);
            $atividadesExternas = PainelControle::getAtividadesExternasRelGlobal($dataInicio, $dataFim);
            $arrayProdutividade = array(
                'produtividadeEquipe' => $produtividadeEquipes, 'custo' => $custo, 'produtividadeColaborador' => $produtividadeColaborador,
                'hora_extra' => $horaExtra, 'ausenciaColaborador' => $ausenciaColaborador, 'mediaHorasTrabalhadas' => $mediaHorasTrabalhadas,
                'atividadeExternas' => $atividadesExternas
            );

            /*Tópico 2 - Métrica*/
            $metricasMetaAlcancada = PainelControle::getMetaAlcancadaMetricaRelGlobal($dataInicio, $dataFim);
            $metricasMaximoLimite = PainelControle::getMetricaLimiteMaximoRelGlobal($dataInicio, $dataFim);
            $metricasMinimoLimite = PainelControle::getMetricaLimiteMinimoRelGlobal($dataInicio, $dataFim);
            $arrayMetrica = array(
                'metricaMetaAlcancada' => $metricasMetaAlcancada, 'metricaMaximoLimite' => $metricasMaximoLimite, 'metricaMinimoLimite' => $metricasMinimoLimite
            );

            /*Topico 3 - Projetos*/

            $projetosAdiantados = PainelControle::getContratosAdiantados($dataInicio, $dataFim, $diffDias);
            $projetosAtrasados = PainelControle::getContratosAtrasados();
            $arrayProjeto = array(
                'projetoAdiantado' => $projetosAdiantados, 'projetoAtrasado' => $projetosAtrasados
            );

            /*Topico 4 - Informações complementares*/
            $colaboradores = count(Colaborador::model()->findAllByAttributes(array('serial_empresa' => $empresa->serial)));
            $colaboradoresAtivos = count(Colaborador::model()->findAllByAttributes(array('fk_empresa' => $_POST['empresa'], 'status' => 1)));
            $equipes = count(Equipe::model()->findAllByAttributes(array('fk_empresa' => $_POST['empresa'])));
            $coordenadores = count(UserGroupsUser::model()->findAllByAttributes(array('fk_empresa' => $_POST['empresa'], 'group_id' => 4)));
            $programasNaoPermitidos = ListaNegraPrograma::model()->findAllByAttributes(array('fk_empresa' => $_POST['empresa']), array('limit' => 3, 'order' => 'porcentagem DESC'));
            $sitessNaoPermitidos = ListaNegraSite::model()->findAllByAttributes(array('fk_empresa' => $_POST['empresa']), array('limit' => 3, 'order' => 'porcentagem DESC'));
            $arrayInformacoes = array(
                'colaboradores' => $colaboradores, 'colaboradoresAtivos' => $colaboradoresAtivos, 'equipes' => $equipes,
                'coordenadores' => $coordenadores, 'programasNaoPermitidos' => $programasNaoPermitidos, 'sitesNaoPermitidos' => $sitessNaoPermitidos
            );
            PainelControle::geraRelGlobalPDF($arrayProdutividade, $arrayMetrica, $arrayProjeto, $arrayInformacoes, $empresa);
        } else
            $this->render('avaliacaoGlobal');
    }


    /**
     * @param $model
     * @param $language
     * @param $post
     *
     * Método auxiliar utilizado para salvar as traduções enviadas via Ajax
     */
    public function salvarTraducao($model, $language, $post)
    {
        if (isset($model)) {
            $model->translation = $post['traducao_' . $language];
            $model->save(false);
        } else {
            if (!empty($post['traducao_' . $language])) {
                $model = new Traducao();
                $model->id = $post['id'];
                $model->language = $language;
                $model->translation = $post['traducao_' . $language];
                $model->save();
            }
        }
    }
}