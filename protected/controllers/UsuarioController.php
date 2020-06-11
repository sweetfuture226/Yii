<?php

class UsuarioController extends Controller {

    public $title_action = "Usuários";

    public function filters() {
        return array(
            'userGroupsAccessControl', // perform access control for CRUD operations
        );
    }

    public function accessRules() {
        return array(
            array('allow',  // allow all users to perform 'view' action
                'actions'=>array('recuperaSenha'),
                'users'=>array('*'),
            ),
            array('allow', // allow admin user to perform 'index' and 'delete' actions
                'actions' => array('index', 'create', 'update',
                    'delete', 'contato', 'wizard', 'buscarColaboradores',
                    'concluir', 'AlterarSenha', 'SalvarPasso', 'GetCsv','changeLido'),
                'groups' => array('empresa','demo'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionRecuperaSenha(){
        $usuario = UserGroupsUser::model()->find(array('condition'=>"email ='".$_POST['email']."'"));
        if($usuario != NULL){
            $novaSenha = MetodosGerais::geraSenha();
            $usuario->password= $novaSenha;
            $usuario->last_change_passwd = date('y-m-j');
            if($usuario->save()){
                //die($novaSenha);
                $mensagem = "<br> Segue a nova senha para acesso ao Viva Smith: $novaSenha
                <br><br><br> Qualquer dúvida estamos à disposição.";

                // Ex: MetodosGerais::emailSendGrid(de, para, título, corpo);
                SendMail::send($_POST['email'], array($_POST['email']), Yii::t('smith', 'Recuperação de senha'), $mensagem);
                echo "ok";
            }
        }
        else{
            echo "not";
        }

    }

    public function actionDelete($id) {
        if (Yii::app()->request->isPostRequest) {
            $start = MetodosGerais::inicioContagem();
            // we only allow deletion via POST request
            $this->loadModel($id)->delete();
            LogAcesso::model()->saveAcesso('Configurações', 'Deletar coordenador', 'Deletar', MetodosGerais::tempoResposta($start));
            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
        } else
            throw new CHttpException(400, Yii::t('smith', 'Requisição inválida. Por favor, não repita esta requisição novamente.'));
    }

    public function actionCreate()
    {
        $this->title_action = Yii::t('smith', "Criar coordenador");
        $this->pageTitle = Yii::t('smith', "Criar coordenador");
        $model = new UserGroupsUser;

        if (isset($_POST['UserGroupsUser'])) {
            $start = MetodosGerais::inicioContagem();
            $model->username = $_POST['UserGroupsUser']['username'];
            $model->nome = $_POST['UserGroupsUser']['nome'];
            $model->email = $_POST['UserGroupsUser']['email'];
            $model->password = $_POST['UserGroupsUser']['password'];
            $model->status = 4;
            $model->group_id = 4;
            $model->fk_empresa = MetodosGerais::getEmpresaId();
            $model->serial_empresa = LogAtividade::model()->getSerial();
            $model->creation_date = date('Y-m-d H:i:s');
            $model->fk_equipe = $_POST['UserGroupsUser']['fk_equipe'];

            if ($model->save()) {
                if (isset($_POST['contratos'])) {
                    foreach ($_POST['contratos'] as $contrato) {
                        $usuarioHasObra = new UsuarioHasContrato();
                        $usuarioHasObra->fk_contrato = $contrato;
                        $usuarioHasObra->fk_usergroups_user = $model->id;
                        $usuarioHasObra->save();
                    }
                }
                LogAcesso::model()->saveAcesso('Configurações', 'Criar coordenador', 'Criar', MetodosGerais::tempoResposta($start));
                Yii::app()->user->setFlash('success', Yii::t('smith', 'Usuário inserido com sucesso.'));
                $this->redirect(array('/usuario/index'));
            } else {
                Yii::app()->user->setFlash('error', Yii::t('smith', 'Usuário não pôde ser inserido.'));
            }
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionIndex() {
        $start = MetodosGerais::inicioContagem();
        $model = new UserGroupsUser('search');
        $this->title_action = Yii::t("smith","Coordenadores");
        $this->pageTitle = Yii::t("smith","Coordenadores");
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['UserGroupsUser']))
            $model->attributes = $_GET['UserGroupsUser'];
        LogAcesso::model()->saveAcesso('Configurações', 'Coordenadores', 'Coordenadores', MetodosGerais::tempoResposta($start));
        $this->render('index', array(
            'model' => $model));
    }

    public function actionUpdate($id) {
        $this->title_action = Yii::t('smith', "Atualizar Coordenador");
        $model = $this->loadModel($id);

        if (isset($_POST['UserGroupsUser'])) {
            $start = MetodosGerais::inicioContagem();
            $save = $model->saveAttributes(array(
                'username' => $_POST['UserGroupsUser']['username'],
                'nome' => $_POST['UserGroupsUser']['nome'],
                'email' => $_POST['UserGroupsUser']['email'],
                'fk_equipe' => $_POST['UserGroupsUser']['fk_equipe']
            ));

            if ($save) {
                LogAcesso::model()->saveAcesso('Configurações', 'Atualizar Coordenador', 'Atualizar', MetodosGerais::tempoResposta($start));
                Yii::app()->user->setFlash('success', Yii::t('smith', 'Usuário atualizado com sucesso.'));
                $this->redirect(array('/usuario/index'));
            } else {
                Yii::app()->user->setFlash('error', Yii::t('smith', 'Usuário não pôde ser atualizado.'));
            }
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    public function loadModel($id) {
        $model = UserGroupsUser::model()->findByPk((int) $id);
        if ($model === null)
            throw new CHttpException(404, Yii::t('smith', 'A página não existe.'));
        return $model;
    }

    public function actionContato() {
        if (isset($_POST['contato'])) {
            $mensagem = "<div>" . $_POST['contato'] . "</div>";
            SendMail::send('notificacao@vivainovacao.com', array('lucascardoso@vivainovacao.com', 'robsonferreira@vivainovacao.com'), Yii::t('smith', "Um novo erro foi reportado") . " - " . Yii::app()->user->name, $mensagem, null, array($_POST['anexo']));
        }
    }

    public function actionWizard() {
        $this->title_action = Yii::t('smith', "Instalação e Configuração ");
        $this->pageTitle = Yii::t('smith', "Viva Smith - Instalação e Configuração");
        $fk_empresa = MetodosGerais::getEmpresaId();
        $model = Colaborador::model()->findAllByAttributes(array("fk_empresa" => $fk_empresa));
        $modelEquipe = Equipe::model()->findAllByAttributes(array("fk_empresa" => $fk_empresa));
        $modelParametros = EmpresaHasParametro::model()->findByAttributes(array("fk_empresa" => $fk_empresa));
        $passoAtual = Empresa::model()->findByPk($fk_empresa)->passo_wizard;
        $this->layout = "main_wizard";
        $this->render('wizard', array("model" => $model,
            "modelEquipe" => $modelEquipe,
            "modelParametros" => $modelParametros,
            'passoAtual' => $passoAtual));
    }

    public function actionConcluir() {
        $user = UserGroupsUser::model()->findByPk(Yii::app()->user->id);
        $fk_empresa = $user->fk_empresa;
        $model = Empresa::model()->find(array("condition" => "id=$fk_empresa"));
        $model->wizard = 1;
        Colaborador::model()->updateAll(array("ativo" => "1"), "fk_empresa={$fk_empresa}");
        $model->save();
    }

    public function actionbuscarColaboradores() {
        $idUser = Yii::app()->user->id;

        $serial = UserGroupsUser::model()->findByAttributes(array("id" => $idUser));
        $serial = $serial->serial_empresa;

        $model = Colaborador::model()->findAllByAttributes(array("ativo" => 0, "serial_empresa" => $serial));

        foreach ($model as $colaborador) {
            echo '<span style="margin-left: 10px;">';
            echo CHtml::label($colaborador->ad, '');
            echo '</span>';
            echo '<br>';
        }
        echo "<hr>";
        echo "<span style='margin-left: 10px;' >Total: " . count($model) . "</span>";
    }

    public function actionAlterarSenha($senha)
    {

        $model = UserGroupsUser::model()->findByPk(Yii::app()->user->id);
        $model->password = $senha;
        $model->last_change_passwd = date('y-m-j');
        if ($model->save()){
            $modelNotificacao = new Notificacao();
            $modelNotificacao->action = "senha alterada";
            $modelNotificacao->notificacao = 'senha alterada';
            $modelNotificacao->fk_usuario = Yii::app()->user->id ;
            $modelNotificacao->tipo = 4;
            $modelNotificacao->save();
            echo json_encode(array("resposta"=>'sucesso'));
        }
        else
            echo json_encode('fora dos padroes');
    }

    public function actionSalvarPasso() {
        $passo = $_POST['passo'];
        $fk_empresa = MetodosGerais::getEmpresaId();
        $modelEmpresa = Empresa::model()->find(array("condition" => "id=$fk_empresa"));
        if ($modelEmpresa->passo_wizard < $passo) {
            $modelEmpresa->passo_wizard = $passo;
            $modelEmpresa->save();
            //echo "Sucesso";
        }
    }

    public function actionChangeLido(){
        if(isset($_POST)){
            foreach($_POST as $change){
                $model = new Notificacao;
                $model->notificacao = "change log";
                $model->fk_usuario = Yii::app()->user->id;
                $model->tipo = 3;
                $model->action = $change;
                $model->save();
            }
        }
    }

    public function actionGetCsv() {
        $idEmpresa = MetodosGerais::getEmpresaId();
        $equipes = Equipe::model()->findAll(
            array('order' => 'nome', 'condition' => 'fk_empresa =' . $idEmpresa));

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

        $equipesNome = '';
        $isPrimeiro = true;
        foreach ($equipes as $equipe){
            $equipesNome .= (!$isPrimeiro) ? ',' . $equipe->nome : $equipe->nome;
            $isPrimeiro = false;
        }

        $colaboradores = Colaborador::model()->findAllByAttributes(array("fk_empresa" => $idEmpresa));
        $index = 2;
        $phpExcel->getActiveSheet()->getProtection()->setSheet(true);
        foreach ($colaboradores as $colaborador) {
            $colabEquipe = Equipe::model()->findByAttributes(array('id' => $colaborador->fk_equipe, 'fk_empresa' => $colaborador->fk_empresa));
            $horas_semana = explode(':',$colaborador->horas_semana);
            $horas_semana = (isset($horas_semana[1]))? $horas_semana[0].':'.$horas_semana[1]: $colaborador->horas_semana;
            $strEquipe = (is_null($colabEquipe)) ? 'Selecione a equipe' : $colabEquipe->nome;
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

            $dropDown = $phpExcel->getActiveSheet()
                ->getCell('G' . $index)
                ->getDataValidation();
            $dropDown->setType( PHPExcel_Cell_DataValidation::TYPE_LIST )
                ->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_INFORMATION )
                ->setAllowBlank(false)
                ->setShowInputMessage(true)
                ->setShowErrorMessage(true)
                ->setShowDropDown(true)
                ->setErrorTitle('Error')
                ->setError(Yii::t('smith', 'Equipe não encontra-se cadastrada no sistema.'))
                ->setPromptTitle(Yii::t('smith', 'Escolha uma equipe'))
                ->setFormula1('"'. $equipesNome . '"');

            $phpExcel->getActiveSheet()
                ->getStyle('B' . $index . ':G' . $index)
                ->getProtection()
                ->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
            $index++;
        }

        try{
            $objWriter = PHPExcel_IOFactory::createWriter($phpExcel, 'Excel5');
            $filename = 'colaboradores.xls';
            $objWriter->save($filename);
        } catch (Exception $e) {
            Logger::saveError($e);
        }

        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        flush();
        readfile($filename);
        unlink($filename);
    }
}
