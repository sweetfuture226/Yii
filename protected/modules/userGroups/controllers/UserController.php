<?php

class UserController extends Controller {

    public $title_action = "User group";

    /**
     * @var mixed tooltip for the permission menagement
     */
    public static $_permissionControl = array(
        'write' => 'can invite other users.',
        'admin' => 'can edit other users, ban them and approve their registrations.',
    );

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'userGroupsAccessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // just guest can perform 'activate', 'login' and 'passRequest' actions
                'actions' => array('activate', 'login', 'passRequest'),
                'ajax' => false,
                'users' => array('?'),
            ),
            array('allow', // captchas can be loaded just by guests
                'actions' => array('captcha'),
                'expression' => 'UserGroupsConfiguration::findRule("registration")',
                'users' => array('?'),
            ),
            array('allow', // just guest can perform registration actions and just if it is enabled
                'actions' => array('register'),
                'ajax' => false,
                'expression' => 'UserGroupsConfiguration::findRule("registration")',
                'users' => array('?'),
            ),
            array('allow', // actions users can access while in recovery mode
                'actions' => array('recovery', 'logout'),
                'users' => array('#'),
            ),
            array('allow', // allow authenticated user to perform 'logout' actions
                'actions' => array('logout', 'profile', 'edit'),
                'users' => array('@'),
            ),
            array('allow', // Adicao de Usuario pela interface do sistema
                'actions' => array('addUser', 'delete'),
                'groups' => array('root', 'financeiro', 'empresa'),
            ),
            array('allow', // allow logged user to access the userlist page if the have admin rights on users or if the list is public
                'actions' => array('index'),
                'expression' => 'UserGroupsConfiguration::findRule("public_user_list") || Yii::app()->user->pbac("userGroups.user.admin")',
                'users' => array('@'),
            ),
            array('allow', // allow guest to view users profiles according to the configuration
                'actions' => array('view'),
                'expression' => 'UserGroupsConfiguration::findRule("public_profiles")',
                'users' => array('?', '@'),
            ),
            array('allow', // allow logged user to view other users profiles according to the configuration and always their own
                'actions' => array('view'),
                'expression' => 'UserGroupsConfiguration::findRule("profile_privacy") || strtolower(Yii::app()->user->name) === (isset($_GET["u"]) ? strtolower($_GET["u"]) : strtolower(Yii::app()->user->name))',
                'users' => array('@'),
            ),
            array('allow', // allow user with user admin permission to view every profile, approve, ban and invite users
                'actions' => array('invite'),
                'pbac' => array('write'),
            ),
            array('allow', // allow user with user admin permission to view every profile, approve, ban and invite users
                'actions' => array('view', 'approve', 'ban', 'invite',),
                'pbac' => array('admin', 'admin.admin', 'empresa'),
            ),
            array('allow', // allow a user tu open an update view just on their own accounts
                'actions' => array('update'),
                'expression' => '$_GET["id"] == Yii::app()->user->id',
                'ajax' => true,
            ),
            array('allow', // allow user with admin permission to perform any action
                'pbac' => array('admin', 'admin.admin', 'empresa'),
                'ajax' => true,
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * override of the actions method to implement captcha
     */
    public function actions() {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
        );
    }

    /**
     * Lists all users.
     */
    public function actionIndex() {
        $model = new UserGroupsUser('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['UserGroupsUser']))
            $model->attributes = $_GET['UserGroupsUser'];

        if (Yii::app()->request->isAjaxRequest)
            $this->renderPartial('index', array('model' => $model,), false, true);
        else
            $this->render('index', array('model' => $model,), false, true);
    }

    /**
     * render a user profile
     */
    public function actionView() {
        // load the user profile according to the request
        if (isset($_GET['u'])) {
            // look for the right user criteria to use according to the viewer permissions
            if (Yii::app()->user->pbac(array('user.admin', 'admin.admin')))
                $criteria = array('username' => $_GET['u']);
            else
                $criteria = array('username' => $_GET['u'], 'status' => UserGroupsUser::ACTIVE);
            // load the profile
            $model = UserGroupsUser::model()->findByAttributes($criteria);
            if ($model === null || ($model->relUserGroupsGroup->level > Yii::app()->user->level && !UserGroupsConfiguration::findRule('public_profiles')))
                throw new CHttpException(404, Yii::t('userGroupsModule.general', 'The requested page does not exist.'));
        } else
            $model = $this->loadModel(Yii::app()->user->id);

        // load the profile extensions
        $profiles = array();
        $profile_list = Yii::app()->controller->module->profile;


        foreach ($profile_list as $p) {
            // check if the profile data exist on the current user, otherwise
            // create an instance of the profile extension
            $relation = "rel$p";

            if (!$model->$relation instanceof CActiveRecord)
                $p_instance = new $p;
            else
                $p_instance = $model->$relation;

            // check if the profile extension is supporting profile views
            $views = $p_instance->profileViews();
            if (isset($views[UserGroupsUser::VIEW])) {
                $profiles[] = array('view' => $views[UserGroupsUser::VIEW], 'model' => $p_instance);
            }
        }


        if (Yii::app()->request->isAjaxRequest || isset($_GET['_isAjax']))
            $this->renderPartial('view', array('model' => $model, 'profiles' => $profiles), false, true);
        else
            $this->render('view', array('model' => $model, 'profiles' => $profiles));
    }

    public function actionProfile() {

        $this->title_action = Yii::t('smith', "Atualizar Perfil");
        $model = $this->loadModel(Yii::app()->user->id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        if (isset($_POST['UserGroupsUser'])) {
            if (md5($_POST['current_password'] . $model->getSalt()) == $model->password) {
                //$model->username=$_POST['UserGroupsUser']['username'];
                $model->email = $_POST['UserGroupsUser']['email'];
                if ($_POST['UserGroupsUser']['password'] != "") {
                    if ($_POST['UserGroupsUser']['password'] == $_POST['UserGroupsUser']['password_again']) {
                        $model->password = $_POST['UserGroupsUser']['password'];
                        $model->last_change_passwd = date('y-m-j');
                    } else {
                        Yii::app()->user->setFlash('error', 'Nova senha está divergente.');
                        $this->refresh();
                    }
                }

                $flag = false;
                $allowedExts = array("jpg", "jpeg", "gif", "png");

                if (isset($_FILES['file'])) {
                    $extension = end(explode(".", $_FILES["file"]["name"]));
                    if ((($_FILES["file"]["type"] == "image/gif") || ($_FILES["file"]["type"] == "image/jpeg") || ($_FILES["file"]["type"] == "image/jpg") || ($_FILES["file"]["type"] == "image/png") || ($_FILES["file"]["type"] == "image/pjpeg")) && ($_FILES["file"]["size"] < 210000) && in_array($extension, $allowedExts)) {
                        if ($_FILES["file"]["error"] > 0)
                            Yii::app()->user->setFlash('error', 'Avatar não pode ser atualizados.');
                        else
                            $flag = true;
                    }
                }
                //$model->image = CUploadedFile::getInstance($model, 'image');

                if ($model->save()) {
                    if (isset($_FILES['company_image'])) {
                        $extension = end(explode(".", $_FILES["company_image"]["name"]));
                        if ((($_FILES["company_image"]["type"] == "image/gif") || ($_FILES["company_image"]["type"] == "image/jpeg") || ($_FILES["company_image"]["type"] == "image/jpg") || ($_FILES["company_image"]["type"] == "image/png") || ($_FILES["company_image"]["type"] == "image/pjpeg")) && in_array($extension, $allowedExts)) {
                            if ($_FILES["company_image"]["error"] > 0)
                                Yii::app()->user->setFlash('error', 'Logo da empresa não pôde ser atualizada.');
                            else
                                $flag = true;
                        }
                    }

                    if ($flag && isset($_FILES['company_image'])) {
                        $empresa = Empresa::model()->findByPk(MetodosGerais::getEmpresaId());
                        $extension = explode('/', $_FILES['company_image']['type'])[1];
                        $temporary = $_FILES["company_image"]["tmp_name"];
                        $path = 'public/avatar/' . str_replace(' ', '_', strtolower($empresa->nome)) . '.' . $extension;

                        $createFrom = 'imagecreatefrom' . $extension;
                        $img = $createFrom($temporary);
                        $new = imagecreatetruecolor($_POST['company_image']['w'], $_POST['company_image']['h']);
                        imagecopyresampled($new, $img, 0, 0, $_POST['company_image']['x'], $_POST['company_image']['y'], $_POST['company_image']['w'], $_POST['company_image']['h'], $_POST['company_image']['w'], $_POST['company_image']['h']);
                        $create = 'image' . $extension;

                        if (!$create($new, Yii::getPathOfAlias('webroot') . '/' . $path)) {
                            Yii::app()->user->setFlash('error', 'Logo da empresa não pôde ser atualizada.');
                            $this->refresh();
                        } else {
                            $empresa->logo = $path;
                            $empresa->save(false);

                            $flag = false;
                        }
                    }
                    //$model->image->saveAs('public/avatar/' . $model->image);
                    //$fk_empresa = MetodosGerais::getEmpresaId();
                    //$empresa = Empresa::model()->findByPk($fk_empresa);

                    //$empresa->logo = "public/avatar/" . $model->image;
                    //$empresa->save();
                    // if ($flag) {
                    //     $file = "/var/www/smith.vivainovacao.com/themes/grape/img/users_avatars/" . Yii::app()->user->id . ".png";

                    //     if (!move_uploaded_file($_FILES["file"]["tmp_name"], $file))
                    //         Yii::app()->user->setFlash('error', 'Avatar não pode ser atualizado.');
                    //     else {
                    //         $img = Yii::app()->simpleImage->load($file);
                    //         $img->resize(60, 60);
                    //         $img->save($file);

                    //         $flag = false;
                    //         Yii::app()->user->setFlash('success', 'Dados atualizados com sucesso.');
                    //     }
                    // }
                    Yii::app()->user->setFlash('success', 'Dados atualizados com sucesso.');
                    $this->refresh();
                } else
                    Yii::app()->user->setFlash('error', 'Dados não puderam ser atualizados.');
            } else
                Yii::app()->user->setFlash('error', 'Senha atual incorreta.');
        }

        $this->render('profile', array(
            'model' => $model,
        ));
    }

    /**
     * user registration
     */
    public function actionRegister() {
        $model = new UserGroupsUser('registration');

        // set the profile extension array
        $profiles = array();
        $profile_list = Yii::app()->controller->module->profile;

        foreach ($profile_list as $p) {
            // create an instance of the profile extension
            $p_instance = new $p('registration');
            // check if the profile extension is supporting registration
            $views = $p_instance->profileViews();
            if (isset($views[UserGroupsUser::REGISTRATION])) {
                $profiles[] = array('view' => $views[UserGroupsUser::REGISTRATION], 'model' => $p_instance);
            }
        }

        if (isset($_POST['UserGroupsUser'])) {
            $model->attributes = $_POST['UserGroupsUser'];
            // set validation for additional fields
            foreach ($profiles as &$p) {

                if (isset($_POST[get_class($p['model'])]))
                    $p['model']->attributes = $_POST[get_class($p['model'])];

                if (!$p['model']->validate())
                    $error = true;
            }
            if ($model->validate() && !isset($error)) {
                if ($model->save()) {
                    // save the related profile extensions
                    foreach ($profiles as $p) {
                        $p['model']->ug_id = $model->id;
                        $p['model']->save();
                    }
                    $this->redirect(Yii::app()->baseUrl . '/userGroups');
                }
            }
        }

        $this->render('register', array(
            'model' => $model,
            'profiles' => $profiles,
        ));
    }

    /**
     * user invite form
     */
    public function actionInvite() {
        $model = new UserGroupsUser('invitation');

        $this->performAjaxValidation($model);

        if (isset($_POST['UserGroupsUser'])) {
            $model->attributes = $_POST['UserGroupsUser'];
            if ($model->validate()) {
                if ($model->save()) {
                    $mail = new UGMail($model, UGMail::INVITATION);
                    $mail->send();
                } else
                    Yii::app()->user->setFlash('user', Yii::t('userGroupsModule.general', 'An Error Occurred. Please try later.'));
                $this->redirect(Yii::app()->baseUrl . '/userGroups');
            }
        }

        if (Yii::app()->request->isAjaxRequest)
            $this->renderPartial('invite', array('model' => $model,), false, true);
        else
            $this->render('invite', array('model' => $model,));
    }

    /**
     * Updates a user data
     * if the update is successfull the user profile will be reloaded
     * You can change password or mail indipendently
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $miscModel = $this->loadModel($id, 'changeMisc');
        $passModel = clone $miscModel;
        $passModel->setScenario('changePassword');
        $passModel->password = NULL;

        // pass the models inside the array for ajax validation
        $ajax_validation = array($miscModel, $passModel);

        // load additional profile models
        $profile_models = array();
        $profiles = $this->module->profile;
        foreach ($profiles as $p) {
            $external_profile = new $p;
            // check if the loaded profile has an update view
            $external_profile_views = $external_profile->profileViews();
            if (array_key_exists(UserGroupsUser::EDIT, $external_profile_views)) {
                // load the model data
                $loaded_data = $external_profile->findByAttributes(array('ug_id' => $id));
                $external_profile = $loaded_data ? $loaded_data : $external_profile;
                // set the scenario
                $external_profile->setScenario('updateProfile');
                // load the models inside both the ajax validation array and the profile models
                // array to pass it to the view
                $profile_models[$p] = $external_profile;
                $ajax_validation[] = $external_profile;
            }
        }

        // perform ajax validation
        $this->performAjaxValidation($ajax_validation);

        // check if an additional profile model form was sent
        if ($form = array_intersect_key($_POST, array_flip($profiles))) {
            $model_name = key($form);
            $form_values = reset($form);
            // load the form values into the model
            $profile_models[$model_name]->attributes = $form_values;
            $profile_models[$model_name]->ug_id = $id;

            // save the model
            if ($profile_models[$model_name]->save()) {
                Yii::app()->user->setFlash('user', Yii::t('userGroupsModule.general', 'Data Updated Successfully'));
                $this->redirect(Yii::app()->baseUrl . '/userGroups?_isAjax=1&u=' . $passModel->username);
            } else
                Yii::app()->user->setFlash('user', Yii::t('userGroupsModule.general', 'An Error Occurred. Please try later.'));
        }

        if (isset($_POST['UserGroupsUser']) && isset($_POST['formID'])) {
            // pass the right model according to the sended form and load the permitted values
            if ($_POST['formID'] === 'user-groups-password-form')
                $model = $passModel;
            else if ($_POST['formID'] === 'user-groups-misc-form')
                $model = $miscModel;

            $model->attributes = $_POST['UserGroupsUser'];

            if ($model->validate()) {
                if ($model->save()) {
                    Yii::app()->user->setFlash('user', Yii::t('userGroupsModule.general', 'Data Updated Successfully'));
                    $this->redirect(Yii::app()->baseUrl . '/userGroups?_isAjax=1&u=' . $model->username);
                } else
                    Yii::app()->user->setFlash('user', Yii::t('userGroupsModule.general', 'An Error Occurred. Please try later.'));
            }
        }

        $this->renderPartial('update', array('miscModel' => $miscModel, 'passModel' => $passModel, 'profiles' => $profile_models), false, true);
    }

    /**
     * user activation view
     */
    public function actionActivate() {
        $activeModel = new UserGroupsUser('activate');
        $requestModel = new UserGroupsUser('mailRequest');

        if (isset($_POST['UserGroupsUser']) || isset($_GET['UserGroupsUser'])) {
            if (isset($_GET['UserGroupsUser']) || $_POST['id'] === 'user-groups-activate-form')
                $model = $activeModel;
            else if (($_POST['id'] === 'user-groups-request-form'))
                $model = $requestModel;

            if (isset($_POST['UserGroupsUser']))
                $model->attributes = $_POST['UserGroupsUser'];
            else
                $model->attributes = $_GET['UserGroupsUser'];


            if ($model->validate()) {
                if (isset($_GET['UserGroupsUser']) || $_POST['id'] === 'user-groups-activate-form') {
                    $model->login('recovery');
                    $this->redirect(Yii::app()->baseUrl . '/userGroups/user/recovery');
                } else {
                    $userModel = UserGroupsUser::model()->findByAttributes(array('email' => $model->email));
                    $mail = new UGMail($userModel, UGMail::ACTIVATION);
                    $mail->send();
                    $this->redirect(Yii::app()->baseUrl . '/userGroups/user/activate');
                }
            }
        }



        $this->render('activate', array(
            'activeModel' => $activeModel,
            'requestModel' => $requestModel
        ));
    }

    /**
     * approve the user account
     */
    public function actionApprove() {
        if (isset($_POST['UserGroupsApprove'])) {
            $model = $this->loadModel((int) $_POST['UserGroupsApprove']['id']);
            $model->status = UserGroupsUser::ACTIVE;
            if ($model->save())
                Yii::app()->user->setFlash('user', Yii::t('userGroupsModule.admin', '{username}\'s account is now active.', array('{username}' => $model->username)));
            else
                Yii::app()->user->setFlash('user', Yii::t('userGroupsModule.general', 'An Error Occurred. Please try later.'));
            $this->redirect(Yii::app()->baseUrl . '/userGroups?u=' . $model->username);
        }
    }

    /**
     * form for new pass request
     */
    public function actionPassRequest() {
        $model = new UserGroupsUser('passRequest');

        if (isset($_POST['UserGroupsUser'])) {
            $model->attributes = $_POST['UserGroupsUser'];
            if ($model->validate()) {
                $model = UserGroupsUser::model()->findByAttributes(array('username' => $_POST['UserGroupsUser']['username']));
                $model->scenario = 'passRequest';
                if ($model->save()) {
                    $mail = new UGMail($model, UGMail::PASS_RESET);
                    $mail->send();
                } else
                    Yii::app()->user->setFlash('success', Yii::t('userGroupsModule.general', 'An Error Occurred. Please try later.'));
                $this->redirect(Yii::app()->baseUrl . '/userGroups');
            }
        }

        $this->render('passRequest', array('model' => $model));
    }

    /**
     * ban user from the system
     */
    public function actionBan() {
        // load the user data
        $model = $this->loadModel((int) $_POST['UserGroupsBan']['id'], 'ban');
        // check if you are trying to ban a user with an higher level
        if ($model->relUserGroupsGroup->level > Yii::app()->user->level)
            Yii::app()->user->setFlash('user', Yii::t('userGroupsModule.admin', 'You cannot ban a user with a level higher then yours.'));
        else {
            $model->ban = date('Y-m-d H:i:s', time() + ($_POST['UserGroupsBan']['period'] * 86400));
            $model->ban_reason = $_POST['UserGroupsBan']['reason'];
            $model->status = UserGroupsUser::BANNED;
            if ($model->save())
                Yii::app()->user->setFlash('user', Yii::t('userGroupsModule.admin', '{username}\'s account is banned untill {day}.', array('{username}' => $model->username, '{day}' => $model->ban)));
            else
                Yii::app()->user->setFlash('user', Yii::t('userGroupsModule.general', 'An Error Occurred. Please try later.'));
        }
        $this->redirect(Yii::app()->baseUrl . '/userGroups?u=' . $model->username);
    }

    public function actionDelete($id) {

        $model = $this->loadModel($id, 'ban');
        // check if you are trying to ban a user with an higher level
        if ($model->relUserGroupsGroup->level > Yii::app()->user->level)
            Yii::app()->user->setFlash('user', Yii::t('userGroupsModule.admin', 'You cannot ban a user with a level higher then yours.'));
        else {
            $model->status = UserGroupsUser::BANNED;
            if ($model->save())
            //Yii::app()->user->setFlash('user', Yii::t('userGroupsModule.admin','{username}\'s account is banned untill {day}.', array('{username}'=>$model->username, '{day}'=>$model->ban)));
                Yii::app()->user->setFlash('success', 'Usuário removido com sucesso');
            else
            //Yii::app()->user->setFlash('user', Yii::t('userGroupsModule.general','An Error Occurred. Please try later.'));
                Yii::app()->user->setFlash('error', 'Erro ao remover Usuário, por favor tente novamente');
        }
        $this->redirect(Yii::app()->baseUrl . '/usuario');
    }

    public function actionLogin() {
        $this->layout = 'login_new'; //added by Ivan
        $model = new UserGroupsUser('login');

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['UserGroupsUser'])) {
            $model->attributes = $_POST['UserGroupsUser'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login()) {
                $this->verificarNotificacoes($model);
                $this->redirect(Yii::app()->user->returnUrl);
            } else
                Yii::app()->user->setFlash('error', '');
        }

        // display the login form
        if (Yii::app()->request->isAjaxRequest || isset($_GET['_isAjax']))
            $this->renderPartial('/user/login_new', array('model' => $model));
        else
            $this->render('/user/login_new', array('model' => $model));
    }

    /**
     * login in recovery mode
     */
    public function actionRecovery() {
        $model = $this->loadModel(Yii::app()->user->id, 'recovery');

        // if user and password are already setted and so question and answer no form will be prompted
        if (strpos($model->username, '_user') !== 0 && $model->password && $model->salt && $model->question && $model->answer) {
            $model->scenario = 'swift_recovery';
            if (!$model->save())
                Yii::app()->user->setFlash('success', Yii::t('userGroupsModule.general', 'An Error Occurred. Please try later.'));
            $this->redirect(Yii::app()->baseUrl . '/userGroups/user/logout');
        }

        // empty the password field
        $model->password = NULL;

        $this->performAjaxValidation($model);

        if (isset($_POST['UserGroupsUser'])) {
            $model->attributes = $_POST['UserGroupsUser'];
            if ($model->validate()) {
                if (!$model->save())
                    Yii::app()->user->setFlash('success', Yii::t('userGroupsModule.general', 'An Error Occurred. Please try later.'));
                $this->redirect(Yii::app()->baseUrl . '/userGroups/user/logout');
            }
        }

        $this->render('recovery', array(
            'model' => $model,
        ));
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout() {
        // keep the flash messages flowing
        if (Yii::app()->user->hasFlash('success')) {
            $message = Yii::app()->user->getFlash('success');
            Yii::app()->request->cookies['success'] = new CHttpCookie('success', $message);
        }
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->baseUrl . '/userGroups');
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * Optionally sets a scenario
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     * @param string the scenario to apply to the model
     */
    public function loadModel($id, $scenario = false) {
        $model = UserGroupsUser::model()->findByPk((int) $id);
        if ($model === null || ($model->relUserGroupsGroup->level > Yii::app()->user->level && !UserGroupsConfiguration::findRule('public_profiles')))
            throw new CHttpException(404, Yii::t('userGroupsModule.general', 'The requested page does not exist.'));
        if ($scenario)
            $model->setScenario($scenario);
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax'])) {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    protected function verificarNotificacoes($model) {
        if($model->checkTempoPasswd())
            Notificacao::model()->mudarSenha(Yii::app()->user->id, 'Sua senha tem mais de 90 dias.');
        else
            Notificacao::model()->removerMudarSenha(Yii::app()->user->id);

        $novoUsuario = Colaborador::model()->findAll(array('condition' => "ativo = 0 AND status != 0"
            . " AND serial_empresa like '" .MetodosGerais::getSerial()."'"));

        if (!empty($novoUsuario))
            Notificacao::model()->preencherColaborador(Yii::app()->user->id, 'Alguns colaboradores estão com o perfil incompleto.');
        else
            Notificacao::model()->removerPreencherColaborador(Yii::app()->user->id);

        $colaboradores = Colaborador::model()->find(array('condition' => 'fk_empresa = '. MetodosGerais::getEmpresaId() .' AND (fk_equipe = "" OR nome = "" OR salario = "" OR horas_semana = "" OR sobrenome = "" OR valor_hora = "")'));
        $notificacao = Notificacao::model()->findByAttributes(array('fk_empresa' => MetodosGerais::getEmpresaId(), 'tipo' => 9));

        if (!empty($colaboradores) && empty($notificacao)) {
            $notificacao_modal = new Notificacao;
            $notificacao_modal->notificacao = 'Há colaboradores ativos com perfil incompleto. Por favor preencha os perfis ou inative os colaboradores.';
            $notificacao_modal->fk_usuario = Yii::app()->user->id;
            $notificacao_modal->tipo = 9;
            $notificacao_modal->action = 'Colaborador/index';
            $notificacao_modal->fk_empresa = MetodosGerais::getEmpresaId();
            $notificacao_modal->status = 0;
            $notificacao_modal->save();
        }

        $faltaColaborador  = ColaboradorHasFalta::model()->findByAttributes(array('fk_empresa'=>MetodosGerais::getEmpresaId()));
        if(!empty($faltaColaborador))
            Notificacao::model()->colaboradorHasFalta(Yii::app()->user->id,"Existe colaboradores com mais de 48 horas de inatividade<br> Por favor verifique a situação deles.");
        else
            Notificacao::model()->removerColaboradorHasFalta(Yii::app()->user->id);

        $colaboradorSemMetrica = ColaboradorSemMetrica::model()->findByAttributes(array('fk_empresa'=>MetodosGerais::getEmpresaId()));
        if(!empty($colaboradorSemMetrica))
            Notificacao::model()->colaboradorSemMetrica(Yii::app()->user->id,'Alguns colaboradores acessou métricas de outra equipe a qual não pertence, <br>deseja adiciona-los a equipe?');
        else
            Notificacao::model()->removerColaboradorSemMetrica(Yii::app()->user->id);

    }
    /*
      public function actionEdit($id)
      {

      $this->title_action = "Atualizar Dados do Usuário";

      $model = $this->loadModel($id);


      //$file_old_arquivo = $model->foto;

      if(isset($_POST['UserGroupsUser'])){

      $model->email = $_POST['UserGroupsUser']['email'];
      if ($_POST['UserGroupsUser']['nome']!="")
      $model->nome=$_POST['UserGroupsUser']['nome'];
      if ($_POST['UserGroupsUser']['sobrenome']!="")
      $model->sobrenome=$_POST['UserGroupsUser']['sobrenome'];

      //$instancia = CUploadedFile::getInstance($model,'foto');

      //if(!is_null($instancia)){
      //    $model->foto = $instancia;
      //}else{
      //    $model->foto=$file_old_arquivo;
      //}

      if($model->save()){
      //$this->upload_file($file_old_arquivo, $model);

      Yii::app()->user->setFlash('success','Dados atualizados com sucesso.');
      $this->redirect(array('//usuario'));
      }else {
      Yii::app()->user->setFlash('error','Dados não puderam ser atualizados.');
      }
      }

      $this->render('edit',array(
      'model'=>$model,
      ));
      }

      public function actionProfile()
      {

      $this->title_action = "Atualizar Dados";

      $model=$this->loadModel(Yii::app()->user->id);

      //$file_old_arquivo = $model->foto;

      // Uncomment the following line if AJAX validation is needed
      // $this->performAjaxValidation($model);

      if(isset($_POST['UserGroupsUser']))
      {
      if(md5($_POST['current_password'])==$model->password){
      //$model->username=$_POST['UserGroupsUser']['username'];
      $model->email=$_POST['UserGroupsUser']['email'];
      if ($_POST['UserGroupsUser']['password']!="")
      $model->password=md5($_POST['UserGroupsUser']['password']);
      if(isset($_POST['UserGroupsUser']['fk_empresa'])){
      if ($_POST['UserGroupsUser']['fk_empresa']!=$model->fk_empresa)
      $model->fk_empresa = $_POST['UserGroupsUser']['fk_empresa'];
      }
      if(isset($_POST['UserGroupsUser']['departamento_id'])){
      if($_POST['UserGroupsUser']['departamento_id']!="")
      $model->departamento_id=$_POST['UserGroupsUser']['departamento_id'];
      }

      //$instancia = CUploadedFile::getInstance($model,'foto');


      //if(!is_null($instancia))
      //{
      //    $model->foto = $instancia;
      //}
      //else
      //{
      //    $model->foto=$file_old_arquivo;
      //}

      if($model->save()){
      //$this->upload_file($file_old_arquivo, $model);

      Yii::app()->user->setFlash('success','Dados atualizados com sucesso.');
      $this->redirect(array($model->getRelated('relUserGroupsGroup')->home));
      }
      else {
      Yii::app()->user->setFlash('error','Dados não puderam ser atualizados.');
      }
      }
      else {
      Yii::app()->user->setFlash('error','Senha atual incorreta.');
      }
      }
      //$model_departamento = new Departamento;
      $this->render('profile',array(
      'model'=>$model,
      //'model_departamento'=>$model_departamento
      ));
      }

      public function upload_file($file_old, $model){
      $file_new = $model->foto;
      if (isset ($file_new) && is_object($file_new)) {
      if((isset ($file_old)) && ($file_old!=$file_new)){
      if($file_old!='no_user.png')//logo antiga
      unlink(Yii::app()->basePath . '/../public/user_foto/' . $file_old);
      }



      $file_new->saveAs(Yii::app()->basePath . '/../public/user_foto/' . $file_new);

      $file=Yii::app()->basePath . '/../public/user_foto/'.$file_new;
      $img = Yii::app()->simpleImage->load($file);
      $img->resizeToWidth(60);
      //mudar o nome, para nao substituir a imagem de ninguem
      $nome_arquivo = $model->id.$file_new;
      $model->foto = $nome_arquivo;

      $model->save();
      $img->save(Yii::app()->basePath . '/../public/user_foto/'.$model->foto);


      }
      }

      public function actionAddUser()
      {

      $this->title_action = "Adicionar Usuário";
      $model= new UserGroupsUser;

      // Uncomment the following line if AJAX validation is needed
      // $this->performAjaxValidation($model);
      //dd($_POST);
      if(isset($_POST['UserGroupsUser']))
      {
      //Gerar Senha
      $vogais = array('a','e','i','o','u');
      $consoantes = array('b','c','d','f','g','h','j','k','l','m','n','p','qu','r','s','t','v','w','x','z');
      $senha = '';
      for($i = 0;$i < 3;$i++){
      $senha.= $consoantes[rand(0,count($consoantes)-1)];
      $senha.= $vogais[rand(0,count($vogais)-1)];
      }

      $usuario = UserGroupsUser::model()->find(' email like \''.$_POST['UserGroupsUser']['email'].'\'');
      if(isset($usuario)){
      $model = new UserGroupsUser;
      $model->nome = $_POST['UserGroupsUser']['nome'];
      $model->sobrenome = $_POST['UserGroupsUser']['sobrenome'];
      $model->fk_empresa = $_POST['UserGroupsUser']['fk_empresa'];
      $model->departamento_id = $_POST['UserGroupsUser']['departamento_id'];
      $model->group_id = $_POST['UserGroupsUser']['group_id'];
      Yii::app()->user->setFlash('error','Um usuário com este email já foi inserido.');
      $this->render('addUser',array(
      'model'=>$model,
      ));
      }
      $model->password = $senha;
      $model->username = $_POST['UserGroupsUser']['email'];
      $model->nome = $_POST['UserGroupsUser']['nome'];
      $model->sobrenome = $_POST['UserGroupsUser']['sobrenome'];
      $model->email    = $_POST['UserGroupsUser']['email'];
      $model->group_id = $_POST['UserGroupsUser']['group_id'];
      $model->fk_empresa = $_POST['UserGroupsUser']['fk_empresa'];
      $model->departamento_id = $_POST['UserGroupsUser']['departamento_id'];
      $group = UserGroupsGroup::model()->findByPk($model->group_id);
      $model->home     = $group->home;
      $model->status   = 4;
      $model->creation_date = date('Y-m-d H:i:s');
      if($model->save()){
      //notificacao
      $notificacao_model = new Notificacao;
      $corpo_email = "Olá ".$model->nome.", <br> Sua conta no sistema Viva Inova foi criada.<br>
      Seu Login:  ".$model->email." <br>
      Sua Senha:  ".$senha." <br>
      Acesse <a href=".Yii::app()->getRequest()->getHostInfo().">aqui<a/> e comece a contribuir com a inovação em sua empresa!";
      //envio de notificacao
      $notificacao_model->mail($model->id,"criacao usuario",$corpo_email);

      Yii::app()->user->setFlash('success','Usuário criado com sucesso.'/*.$senha */ /* );
      $this->redirect(Yii::app()->baseUrl.'/userGroups/user/addUser');
      }else {
      Yii::app()->user->setFlash('error','Dados não puderam ser atualizados.');
      }

      }
      $this->render('addUser',array(
      'model'=>$model,
      ));
      } */
}
