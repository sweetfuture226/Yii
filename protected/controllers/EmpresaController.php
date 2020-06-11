<?php

class EmpresaController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $title_action = "";

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'userGroupsAccessControl', // perform access control for CRUD operations
            //'accessControl', // perform access control for CRUD operations
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
            array('allow',  // allow all users to perform 'view' action
                'actions' => array('index', 'update', 'create', 'delete', 'povoarTabelas', 'RedefinirSenha', 'ValidaNovaEmpresa', 'contrato', 'desativar', 'usuarios',
                    'createRevenda', 'createContato', 'loadContato', 'ViewInfoPoc', 'indexPoc'),
                'groups' => array('root'),
            ),
            array('allow',
                'actions' => array('getPlanilha', 'parametrizar'),
                'groups' => array('empresa', 'root')
            ),
            array('deny',  // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $this->title_action = 'Nova empresa';
        $this->pageTitle = 'Nova empresa';
        $model = new Empresa;
        $modelRevenda = new Revenda();
        $modelContato = new Contato();
        $modelRevendaHasPoc = new RevendaHasPoc();

        if (isset($_POST['Empresa'])) {

            $programasPadroes = array('Windows Explorer', 'WinRAR', 'Calculadora', 'Bloco de notas', 'Notas Autoadesivas', 'Atividade Externa');
            $loginExists = UserGroupsUser::model()->findByAttributes(array("email" => $_POST['Empresa']['email']));
            if (!isset($loginExists)) {
                $transaction = Yii::app()->db->beginTransaction();
                try {
                    $senha_empresa = MetodosGerais::geraSenha();
                    $creation_date = date('Y-m-d H:i:s');
                    $senha_empresa_admin = 'admin' . $_POST['Empresa']['nome'] . '@!';
                    $serial = $this->geraSerial();
                    $serialExist = Empresa::model()->findAll();
                    foreach ($serialExist as $valor) {
                        if ($serial == $valor->serial) {
                            $serial = $this->geraSerial();
                        }
                    }
                    $model->attributes = $_POST['Empresa'];
                    $model->email = $_POST['Empresa']['email'];
                    $model->nome = $_POST['nome_empresa'];
                    $model->colaboradores_previstos = $_POST['Empresa']['colaboradores_previstos'];
                    $model->serial = $serial;
                    $model->save();
                    $this->saveUserGroupsUser($model, $creation_date, $senha_empresa, $senha_empresa_admin);
                    $this->enviaEmailCredenciais($senha_empresa);
                    $this->saveParametro($model);
                    $this->savePoc($model);
                    $this->saveProgramasPadroes($programasPadroes, $model);
                    $transaction->commit();
                    Yii::app()->user->setFlash('success', Yii::t('smith', 'Empresa inserida com sucesso'));
                    $this->redirect(array('index'));
                } catch (Exception $e) {
                    $transaction->rollBack();
                    Yii::app()->user->setFlash('error', Yii::t('smith', 'Empresa nâo pôde ser inserida'));
                }
            } else {
                Yii::app()->user->setFlash('error', Yii::t('smith', 'Empresa já existe'));
                $this->redirect(array('index'));
            }
        }

        $this->render('create', array(
            'modelRevenda' => $modelRevenda,
            'modelRevendaHasPoc' => $modelRevendaHasPoc,
            'modelContato' => $modelContato,
            'model' => $model,
        ));
    }

    public function actionCreateRevenda()
    {
        if (!empty($_POST['revenda'])) {
            $model = new Revenda();
            $model->nome = $_POST['revenda'];
            if ($model->save()) {
                $revendas = Revenda::model()->findAll();
                echo "<option value=''>" . Yii::t('smith', 'Selecione') . "</option>";
                foreach ($revendas as $value) {
                    if ($value->id == $model->id)
                        echo "<option selected='selected' value='{$value->id}'>{$value->nome}</option>";
                    else
                        echo "<option value='{$value->id}'>{$value->nome}</option>";
                }
            }
        }
    }

    public function actionCreateContato()
    {
        if (!empty($_POST['Contato'])) {
            $model = new Contato;
            $model->attributes = $_POST['Contato'];
            if ($model->save()) {
                $modelRevendaHasContato = new RevendaHasContato();
                $modelRevendaHasContato->fk_contato = $model->id;
                $modelRevendaHasContato->fk_revenda = $_POST['Contato']['fk_revenda'];
                $modelRevendaHasContato->save();
                $objRevendaHasContato = RevendaHasContato::model()->with('contato')->findAllByAttributes(array('fk_revenda' => $_POST['Contato']['fk_revenda']));
                echo "<option value=''>" . Yii::t('smith', 'Selecione') . "</option>";
                foreach ($objRevendaHasContato as $value) {
                    if ($value->contato->id == $model->id)
                        echo "<option selected='selected' value='{$value->contato->id}'>{$value->contato->nome}</option>";
                    else
                        echo "<option value='{$value->contato->id}'>{$value->contato->nome}</option>";
                }
            }
        }
    }

    public function actionLoadContato()
    {
        $modelRevendaHasContato = RevendaHasContato::model()->with('contato')->findAllByAttributes(array('fk_revenda' => $_POST['id']));
        echo "<option value=''>" . Yii::t('smith', 'Selecione') . "</option>";
        foreach ($modelRevendaHasContato as $value) {
            if (!empty($_POST['contato'])) {
                if ($_POST['contato'] == $value->id)
                    echo "<option selected='selected' value='{$value->contato->id}'>{$value->contato->nome}</option>";
                else
                    echo "<option value='{$value->contato->id}'>{$value->contato->nome}</option>";
            } else
                echo "<option value='{$value->contato->id}'>{$value->contato->nome}</option>";
        }
    }

    public function actionViewInfoPoc()
    {
        if (isset($_POST['id'])) {
            $modelRevendaHasPoc = RevendaHasPoc::model()->with('contato')->findByAttributes(array('fk_empresa' => $_POST['id']));
            $empresa = Empresa::model()->findByPk($_POST['id']);
            $infoPoc = array(
                'revenda_nome' => isset($modelRevendaHasPoc->contato->contatoRevenda[0]->revenda->nome) ? $modelRevendaHasPoc->contato->contatoRevenda[0]->revenda->nome : 'Sem informação',
                'revenda_responsavel' => isset($modelRevendaHasPoc->contato->nome) ? $modelRevendaHasPoc->contato->nome : 'Sem informação',
                'revenda_email' => isset($modelRevendaHasPoc->contato->email) ? $modelRevendaHasPoc->contato->email : 'Sem informação',
                'revenda_telefone' => isset($modelRevendaHasPoc->contato->telefone) ? $modelRevendaHasPoc->contato->telefone : 'Sem informação',
                'quantidade_maquinas' => isset($empresa->colaboradores_previstos) ? $empresa->colaboradores_previstos : 'Sem informação',
                'duracao' => isset($modelRevendaHasPoc->duracao) ? $modelRevendaHasPoc->duracao . ' dias' : 'Sem informação',
                'empresa_responsavel' => isset($empresa->responsavel) ? $empresa->responsavel : 'Sem informação',
                'empresa_telefone' => isset($empresa->telefone) ? $empresa->telefone : 'Sem informação',
                'empresa_logo' => Yii::app()->baseUrl . '/' . $empresa->logo
            );
            echo json_encode($infoPoc);
        }
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $this->title_action = 'Atualizar empresa';
        $this->pageTitle = 'Atualizar empresa';
        $model = $this->loadModel($id);
        $modelRevendaHasPoc = RevendaHasPoc::model()->with('contato')->findByAttributes(array('fk_empresa' => $id));
        $modelRevenda = isset($modelRevendaHasPoc->contato->contatoRevenda[0]->revenda) ? $modelRevendaHasPoc->contato->contatoRevenda[0]->revenda
            : new Revenda();
        $modelContato = isset($modelRevendaHasPoc->contato) ? $modelRevendaHasPoc->contato
            : new Contato();
        if (isset($_POST['Empresa'])) {
            $model->attributes = $_POST['Empresa'];
            $model->email = $_POST['Empresa']['email'];
            $model->colaboradores_previstos = $_POST['Empresa']['colaboradores_previstos'];
            if ($model->save()) {
                $this->savePoc($model);
                Yii::app()->user->setFlash('success', Yii::t('smith', 'Empresa atualizada com sucesso'));
                $this->redirect(array('index'));
            }
        }
        $modelRevendaHasPoc = !empty($modelRevendaHasPoc) ? $modelRevendaHasPoc : new RevendaHasPoc();
        $this->render('update', array(
            'modelRevenda' => $modelRevenda,
            'modelRevendaHasPoc' => $modelRevendaHasPoc,
            'modelContato' => $modelContato,
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        $this->loadModel($id)->delete();
        EmpresaHasParametro::model()->findByAttributes(array("fk_empresa" => $id))->delete();
        UserGroupsUser::model()->deleteAllByAttributes(array("fk_empresa" => $id));
        ProgramaPermitido::model()->deleteAllByAttributes(array("fk_empresa" => $id));
        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $this->title_action = Yii::t('smith', 'Empresas');
        $this->pageTitle = Yii::t('smith', 'Empresas');
        $model = new Empresa('search');
        $model->unsetAttributes();  // clear any default values

        if (isset($_GET['Empresa']))
            $model->attributes = $_GET['Empresa'];

        $this->render('index', array(
            'model' => $model,
        ));
    }

    public function actionIndexPoc()
    {
        $this->title_action = Yii::t('smith', 'Visualizar POC\'s');
        $this->pageTitle = Yii::t('smith', 'Visualizar POC\'s');
        $model = new Empresa('searchPoc');
        $model->unsetAttributes();  // clear any default values

        if (isset($_GET['Empresa']))
            $model->attributes = $_GET['Empresa'];

        $this->render('indexPoc', array(
            'model' => $model,
        ));
    }

    public function actionPovoarTabelas()
    {
        $this->title_action = Yii::t("smith", "Povoar tabelas consolidadas");
        if (!empty($_POST)) {
            try {
                $startDate = MetodosGerais::dataAmericana($_POST['date_from']);
                $endDate = MetodosGerais::dataAmericana($_POST['date_to']);
                $ids = Empresa::model()->getEmpresasPovoar($_POST['fk_empresa']);
                $tabelas = ($_POST['tabela'][0] == "") ? array("programa_blacklist", "lista_negra_site", "produtividade", "programa", "colaborador", "projeto", "documentos_contrato") : $_POST['tabela'];
                foreach ($ids as $value) {
                    $idEmpresa = $value->id;
                    $serialEmpresa = ($idEmpresa != 41) ? $value->serial : 'EY3I-0DA4-Z6KD-BC9M';
                    (in_array('programa_blacklist', $tabelas)) ? ConsolidadorBlacklist::consolidar($idEmpresa, $serialEmpresa) : "";
                    (in_array('lista_negra_site', $tabelas)) ? ConsolidadorSitesBlacklist::consolidar($idEmpresa, $serialEmpresa) : "";
                    $data = date('Y-m-d', strtotime($startDate));
                    while (strtotime($data) <= strtotime($endDate)) {
                        d("empresa =" . $idEmpresa . ", no dia " . $data);
                        $this->apagarRegistros($tabelas, $data, $idEmpresa);
                        $this->povoar($tabelas, $data, $idEmpresa, $serialEmpresa);
                        $data = date("Y-m-d", strtotime("+1 day", strtotime($data)));
                    }
                }
            } catch (Exception $e) {
                Logger::sendException($e);
            }
        } else
            $this->render('povoarTabelas');
    }


    /**
     * @param $tabelas
     * @param $data
     * @param $idEmpresa
     * @param $serialEmpresa
     *
     * Método auxiliar utilizada para povoar as tabelas de consolidação
     */
    public function povoar($tabelas, $data, $idEmpresa, $serialEmpresa)
    {
        (in_array('produtividade', $tabelas)) ? ConsolidadorProdutividade::consolidar($data, $idEmpresa) : "";
        (in_array('programa', $tabelas)) ? ConsolidadorPrograma::consolidar($data, $idEmpresa, $serialEmpresa) : "";
        (in_array('colaborador', $tabelas)) ? ConsolidadorColaborador::consolidar($data, $idEmpresa, $serialEmpresa) : "";
        (in_array('projeto', $tabelas)) ? ConsolidadorProjeto::consolidar($data, $idEmpresa, $serialEmpresa) : "";
        (in_array('documentos_contrato', $tabelas)) ? ConsolidadorDocumentosSemContrato::consolidar($idEmpresa, $serialEmpresa, $data) : "";
    }

    /**
     * @param $tabelas
     * @param $data
     * @param $idEmpresa
     *
     * Método auxiliar para remover os registros consolidados antes de povoar.
     */
    public function apagarRegistros($tabelas, $data, $idEmpresa)
    {
        (in_array('produtividade', $tabelas)) ? GrfProdutividadeConsolidado::model()->deleteAll(array("condition" => "data like '$data' and fk_empresa = $idEmpresa")) : "";
        (in_array('programa', $tabelas)) ? GrfProgramaConsolidado::model()->deleteAll(array("condition" => "data like '$data' and fk_empresa = $idEmpresa")) : "";
        if (in_array('colaborador', $tabelas)) {
            GrfColaboradorConsolidado::model()->deleteAll(array("condition" => "data like '$data' and fk_empresa = $idEmpresa"));
            GrfHoraExtraConsolidado::model()->deleteAll(array("condition" => "data like '$data' and fk_empresa = $idEmpresa"));
        }
        (in_array('projeto', $tabelas)) ? GrfProjetoConsolidado::model()->deleteAll(array("condition" => "data like '$data' and fk_empresa = $idEmpresa")) : "";
        (in_array('documentos_contrato', $tabelas)) ? DocumentoSemContrato::model()->deleteAll(array("condition" => "data like '$data' and fk_empresa = $idEmpresa")) : "";
    }

    /**
     * Returns serial number
     * @return string
     */
    public function geraSerial()
    {
        $letras = array('A', 'B', 'K', 'D', 'W', 'X', 'Y', 'Z', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
        $padrao = array('I', 'E', 'M', 'C');
        $aux = $padrao;
        $padrao1 = array_rand($padrao, 1);
        unset($padrao[$padrao1]);
        $padrao2 = array_rand($padrao, 1);
        unset($padrao[$padrao2]);
        $padrao3 = array_rand($padrao, 1);
        unset($padrao[$padrao3]);
        $padrao4 = array_rand($padrao, 1);

        $parte1 = $aux[$padrao1] . $letras[array_rand($letras, 1)] . $letras[array_rand($letras, 1)] . $aux[$padrao2];
        $parte2 = $letras[array_rand($letras, 1)] . $letras[array_rand($letras, 1)] . $letras[array_rand($letras, 1)] . $letras[array_rand($letras, 1)];
        $parte3 = $letras[array_rand($letras, 1)] . $letras[array_rand($letras, 1)] . $letras[array_rand($letras, 1)] . $letras[array_rand($letras, 1)];
        $parte4 = $letras[array_rand($letras, 1)] . $aux[$padrao3] . $letras[array_rand($letras, 1)] . $aux[$padrao4];

        $serial = $parte1 . '-' . $parte2 . '-' . $parte3 . '-' . $parte4;
        return $serial;
    }


    public function actionParametrizar()
    {
        $this->title_action = Yii::t('smith', 'Parametrizar');
        $this->pageTitle = Yii::t('smith', 'Parametrizar');
        if (!empty($_POST)) {
            if (isset($_POST['nameFile'])) {
                $fullPath = Yii::getPathOfAlias('webroot') . '/public/' . $_POST['nameFile'];
                if ($this->salvarPlanilha($fullPath, $_POST['fk_empresa'])) {
                    Yii::app()->user->setFlash('success', Yii::t('smith', 'Dados da planilha foram parametrizados para a empresa.'));
                    Yii::app()->user->getGroupName() == 'root' ? $this->refresh() : Yii::app()->request->redirect(Yii::app()->baseUrl . '/colaborador');
                } else {
                    Yii::app()->user->setFlash('error', Yii::t('smith', 'Não foi possível salvar os dados da planilha.'));
                    Yii::app()->user->getGroupName() == 'root' ? $this->refresh() : Yii::app()->request->redirect(Yii::app()->baseUrl . '/colaborador');
                }
            }
        } else
            $this->render('parametrizacao');
    }

    public function actionGetPlanilha($id)
    {
        $idEmpresa = $id;
        require_once Yii::app()->basePath . '/extensions/PHPExcel/PHPExcel.php';
        require_once Yii::app()->basePath . '/extensions/PHPExcel/PHPExcel/Autoloader.php';
        require_once Yii::app()->basePath . '/extensions/PHPExcel/PHPExcel/Settings.php';
        require_once Yii::app()->basePath . '/extensions/PHPExcel/PHPExcel/IOFactory.php';

        $phpExcel = new PHPExcel();
        $phpExcel->getProperties()->setCreator("Smith")
            ->setLastModifiedBy("Smith")
            ->setTitle(Yii::t("smith", "Exportação dos colaboradores"));

        $phpExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', Yii::t('smith', 'Usuário'))
            ->setCellValue('B1', Yii::t('smith', 'Nome'))
            ->setCellValue('C1', Yii::t('smith', 'Sobrenome'))
            ->setCellValue('D1', Yii::t('smith', 'Email'))
            ->setCellValue('E1', Yii::t('smith', 'Salário Bruto'))
            ->setCellValue('F1', Yii::t('smith', 'Carga Horária Semanal (h:m)'))
            ->setCellValue('G1', Yii::t('smith', 'Equipe'));
        $phpExcel->getActiveSheet()->getStyle('A1:G1')->getFont()->setBold(true);

        $phpExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $phpExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $phpExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $phpExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $phpExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $phpExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $phpExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);

        $serial = Empresa::model()->findByPk($idEmpresa)->serial;
        $colaboradores = Colaborador::model()->findAllByAttributes(array("serial_empresa" => $serial));
        $index = 2;
        $phpExcel->getActiveSheet()->getProtection()->setSheet(true);
        foreach ($colaboradores as $colaborador) {
            $colabEquipe = Equipe::model()->findByAttributes(array('id' => $colaborador->fk_equipe, 'fk_empresa' => $colaborador->fk_empresa));
            $horas_semana = explode(':', $colaborador->horas_semana);
            $horas_semana = (isset($horas_semana[1])) ? $horas_semana[0] . ':' . $horas_semana[1] : $colaborador->horas_semana;
            $strEquipe = (is_null($colabEquipe)) ? '' : $colabEquipe->nome;
            $phpExcel->getActiveSheet()->getStyle('F' . $index)
                ->getNumberFormat()->setFormatCode("[h]:mm");
            $phpExcel->getActiveSheet()
                ->setCellValue('A' . $index, $colaborador->ad)
                ->setCellValue('B' . $index, $colaborador->nome)
                ->setCellValue('C' . $index, $colaborador->sobrenome)
                ->setCellValue('D' . $index, $colaborador->email)
                ->setCellValue('E' . $index, $colaborador->salario)
                ->setCellValue('F' . $index, $horas_semana)
                ->setCellValue('G' . $index, $strEquipe);
            $phpExcel->getActiveSheet()
                ->getStyle('B' . $index . ':G' . $index)
                ->getProtection()
                ->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
            $index++;
        }

        try {
            $objWriter = PHPExcel_IOFactory::createWriter($phpExcel, 'Excel5');
            $filename = 'colaboradores.xls';
            $objWriter->save($filename);
        } catch (Exception $e) {
            Logger::saveError($e);
        }

        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        flush();
        readfile($filename);
        unlink($filename);
    }

    /**
     * @param $fullPath
     * @param $idEmpresa
     * @return bool
     *
     * Método auxiliar para parametrizar a planilha de colaboradores importada pelo cliente
     */
    private function salvarPlanilha($fullPath, $idEmpresa)
    {
        $serial = Empresa::model()->findByPk($idEmpresa)->serial;
        require_once Yii::app()->basePath . '/extensions/PHPExcel/PHPExcel.php';
        require_once Yii::app()->basePath . '/extensions/PHPExcel/PHPExcel/Autoloader.php';
        require_once Yii::app()->basePath . '/extensions/PHPExcel/PHPExcel/Settings.php';
        require_once Yii::app()->basePath . '/extensions/PHPExcel/PHPExcel/IOFactory.php';

        try {
            $fileType = PHPExcel_IOFactory::identify($fullPath);
            $reader = PHPExcel_IOFactory::createReader($fileType);
            $objPHPExcel = $reader->load($fullPath);
            $sheet = $objPHPExcel->getSheet(0);
            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();
            for ($row = 2; $row <= $highestRow; $row++) {
                $ad = $sheet->getCell('A' . $row)->getValue();
                $colaborador = Colaborador::model()->findByAttributes(array('ad' => $ad, "serial_empresa" => $serial));
                if (!is_null($colaborador)) {
                    $colaborador->nome = $sheet->getCell('B' . $row)->getValue();
                    $colaborador->sobrenome = $sheet->getCell('C' . $row)->getValue();
                    $colaborador->email = $sheet->getCell('D' . $row)->getValue();

                    $salario = str_replace(",", ".", $sheet->getCell('E' . $row)->getValue());
                    $colaborador->salario = (float)$salario;

                    $cellF = $sheet->getCell('F' . $row)->getValue().':00';
                    $cellFType = $sheet->getCell('F' . $row)->getDataType();
                    $colaborador->horas_semana = $cellFType == 'null' ? '0' : MetodosCSV::strTempoByTipo($cellF, $cellFType);
                    $celulaEquipe = $sheet->getCell('G' . $row)->getValue();
                    $equipe = !empty($celulaEquipe) ?
                        Equipe::model()->findByAttributes(array('nome' => $celulaEquipe, 'fk_empresa' => $idEmpresa))
                        :
                        null;

                    if ($sheet->getCell('G' . $row)->getValue() != '' && empty($equipe)) {
                        $modelEquipe = new Equipe();
                        $modelEquipe->nome = $sheet->getCell('G' . $row)->getValue();
                        $modelEquipe->fk_empresa = $idEmpresa;
                        $modelEquipe->meta = 60;
                        $modelEquipe->save();
                        $colaborador->fk_equipe = $modelEquipe->id;
                    } else
                        $colaborador->fk_equipe = !empty($equipe) ? $equipe->id : null;

                    $horas = $cellFType == 'null' ? '0' : MetodosCSV::getTimeByTipo($cellF, $cellFType);
                    if ($horas != '0') {
                        $horaMensal = (float)($horas) * 4;
                        $total = (float)$colaborador->salario / $horaMensal;
                        $colaborador->valor_hora = round($total, 2);
                    } else
                        $colaborador->valor_hora = 0;
                    $colaborador->status = 1;
                    $colaborador->fk_empresa = $idEmpresa;
                    if (!$colaborador->save(false)) {
                        print_r($colaborador->errors);
                        return false;
                    }
                } else {
                    return false;
                }
            }
            unlink($fullPath);
            return true;
        } catch (Exception $e) {
            unlink($fullPath);
            Logger::sendException($e);
        }
    }

    public function actionRedefinirSenha(){
        $this->title_action = Yii::t('smith','Redefinir senha do cliente');
        $this->pageTitle = Yii::t('smith','Redefinir senha do cliente');
        if(!empty($_POST)){
            $modelUser = UserGroupsUser::model()->findByPk($_POST['username']);
            $novaSenha = MetodosGerais::geraSenha();
            $modelUser->password = $novaSenha;
            if($modelUser->save(false)) {
                $mensagem = "<br> Segue a nova senha do usuário {$modelUser->username} para acesso ao Viva Smith: $novaSenha
                <br><br><br> Qualquer dúvida estamos à disposição.";
                SendMail::send('smith@vivainovacao.com', array($_POST['email']), 'Recuperação de senha', $mensagem, 'lucascardoso@vivainovacao.com');
            }
            Yii::app()->user->setFlash('success', Yii::t('smith', 'Senha redefinida com sucesso'));
            $this->refresh();
        }
        else
            $this->render('redefinicaoSenha');
    }

    public function actionValidaNovaEmpresa()
    {
        if (isset($_POST['campo'])) {
            if ($_POST['tipo'] == 'username') {
                $usuario = UserGroupsUser::model()->findByAttributes(array('username' => $_POST['campo']));
                if (!isset($usuario))
                    return true;
                else
                    return false;
            } else {
                $usuario = UserGroupsUser::model()->findByAttributes(array('email' => $_POST['campo']));
                if (!isset($usuario))
                    return true;
                else
                    return false;
            }

        }
    }

    public function actionContrato()
    {
        $this->title_action = Yii::t('smith', 'Contratos das empresas');
        $this->pageTitle = Yii::t('smith', 'Contratos das empresas');
        $model = new Empresa('search');
        $model->unsetAttributes();  // clear any default values

        if (isset($_GET['Empresa']))
            $model->attributes = $_GET['Empresa'];

        $this->render('contratos', array(
            'model' => $model,
        ));
    }

    public function actionDesativar()
    {
        if (!empty($_POST)) {
            $modelEmpresa = $this->loadModel($_POST['id_empresa']);
            $modelEmpresa->ativo = $_POST['status'];
            $modelEmpresa->save(false);
            $usuarios = UserGroupsUser::model()->findAll(array('condition' => "fk_empresa = $modelEmpresa->id AND username not like 'admin%'"));
            foreach ($usuarios as $obj) {
                $status = ($_POST['status']) ? 4 : 0;
                $obj->saveAttributes(array('status' => $status));
            }
        }
    }

    public function getContratoLDP($data, $row)
    {
        $criteria = new CDbCriteria();
        $criteria->select = 'distinct(t.id)';
        $criteria->join = 'inner join documento as doc on doc.fk_contrato = t.id';
        $criteria->addCondition("doc.fk_empresa = " . $data->id);
        return count(Contrato::model()->findAll($criteria));
    }

    public function getContratoSemLDP($data, $row)
    {
        $criteria = new CDbCriteria();
        $criteria->select = 'distinct(t.id)';
        $criteria->join = 'inner join documento as doc on doc.fk_contrato = t.id';
        $criteria->addCondition("doc.fk_empresa = " . $data->id);
        $ldp = count(Contrato::model()->findAll($criteria));
        $total = count(Contrato::model()->findAllByAttributes(array("fk_empresa" => $data->id)));
        return $total - $ldp;
    }

    public function getContratoProdutivos($data, $row)
    {
        $criteria = new CDbCriteria();
        $criteria->select = 'distinct(t.id)';
        $criteria->join = 'inner join grf_projeto_consolidado as doc on doc.fk_obra = t.id';
        $criteria->addCondition("doc.fk_empresa = " . $data->id);
        return count(Contrato::model()->findAll($criteria));
    }

    public function actionUsuarios() {
        $model = new Empresa('search');
        $model->unsetAttributes();

        if (isset($_GET['Empresa']))
            $model->attributes = $_GET['Empresa'];

        $this->render('usuarios', array(
            'model' => $model,
        ));
    }

    public function getUltimaAtualizacao($data, $row) {
        $horario = GrfColaboradorConsolidado::model()->findBySql("SELECT sq.fk_colaborador, sq.nome, sq.data FROM
            (SELECT * FROM smith.grf_colaborador_consolidado WHERE fk_empresa = {$data->id} GROUP BY fk_colaborador ORDER BY data ASC) AS sq ORDER BY sq.data DESC");
        return (isset($horario)) ? MetodosGerais::dataBrasileira($horario->data) : 'Informação desconhecida';
    }


    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Empresa the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = Empresa::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Empresa $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'empresa-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
     * @param $empresas
     * @param $creation_date
     * @param $senha_empresa
     * @param $senha_empresa_admin
     * @return array
     */
    private function saveUserGroupsUser($empresas, $creation_date, $senha_empresa, $senha_empresa_admin)
    {
        $usuario = new UserGroupsUser();
        $usuario->username = $_POST['Empresa']['nome'];
        $usuario->email = $empresas->email;
        $usuario->group_id = 3;
        $usuario->status = 4;
        $usuario->creation_date = $creation_date;
        $usuario->password = $senha_empresa;
        $usuario->serial_empresa = $empresas->serial;
        $usuario->fk_empresa = $empresas->id;
        $usuario->nome = $_POST['nome_empresa'];
        $usuario->last_change_passwd = date('Y-m-d');
        $usuarioAdmin = new UserGroupsUser();
        $usuarioAdmin->username = 'admin' . $_POST['Empresa']['nome'];
        $usuarioAdmin->email = 'admin' . $empresas->email;
        $usuarioAdmin->group_id = 3;
        $usuarioAdmin->status = 4;
        $usuarioAdmin->creation_date = date('Y-m-d H:i:s');
        $usuarioAdmin->password = $senha_empresa_admin;
        $usuarioAdmin->serial_empresa = $empresas->serial;
        $usuarioAdmin->fk_empresa = $empresas->id;
        $usuarioAdmin->nome = 'LoginTesteViva';
        if ($usuario->save() && $usuarioAdmin->save())
            return true;

        else
            return false;
    }

    /**
     * @param $empresas
     * @return EmpresaHasParametro
     */
    private function saveParametro($empresas)
    {
        $parametro = new EmpresaHasParametro();
        $parametro->fk_empresa = $empresas->id;
        $parametro->andamento_obra = 'prefixo';
        $parametro->tipo_empresa = 'projetos';
        if ($parametro->save())
            return true;
        else
            return false;

    }

    /**
     * @param $programasPadroes
     * @param $empresas
     */
    private function saveProgramasPadroes($programasPadroes, $empresas)
    {
        foreach ($programasPadroes as $programa) {
            $modelPrograma = new ProgramaPermitido();
            $modelPrograma->nome = $programa;
            $modelPrograma->fk_empresa = $empresas->id;
            $modelPrograma->serial_empresa = $empresas->serial;
            $modelPrograma->save();
        }
    }

    /**
     * @param $senha_empresa
     */
    private function enviaEmailCredenciais($senha_empresa)
    {
        SendMail::send('smith@vivainonvacao.com', array('lucascardoso@vivainovacao.com'), 'Dados de acesso ao sistema',
            "Prezado gestor <br> Seu cadastro foi realizado com sucesso, os dados de acesso ao sistema são: <br> Login: " . $_POST['Empresa']['nome'] . "<br> Senha: $senha_empresa");
        if (isset($_POST['Empresa']['envia_email']))
            SendMail::send('smith@vivainonvacao.com', array($_POST['Empresa']['email']), 'Dados de acesso ao sistema',
                "Prezado gestor <br> Seu cadastro foi realizado com sucesso, os dados de acesso ao sistema são: <br>Login: " . $_POST['Empresa']['nome'] . "<br> Senha: $senha_empresa");
    }

    /**
     * @param $empresa
     */
    private function savePoc($empresa)
    {
        RevendaHasPoc::model()->deleteAllByAttributes(array('fk_empresa' => $empresa->id));
        $modelPoc = new RevendaHasPoc();
        $modelPoc->fk_contato = $_POST['responsavel'];
        $modelPoc->fk_empresa = $empresa->id;
        $modelPoc->duracao = $_POST['duracao'];
        $modelPoc->save();
    }
}
