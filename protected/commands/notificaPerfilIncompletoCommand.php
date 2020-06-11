<?php

/**
 * Created by PhpStorm.
 * User: Lucas
 * Date: 13/05/2016
 * Time: 13:39
 */
class notificaPerfilIncompletoCommand extends CConsoleCommand
{
    public function run()
    {
        Yii::setPathOfAlias('userGroups.models.UserGroupsGroup', 
            'C:\xampp\htdocs\viva-smith\protected\modules\userGroups\models\UserGroupsGroup');
        Yii::setPathOfAlias('userGroups.models.UserGroupsAccess', 
            'C:\xampp\htdocs\viva-smith\protected\modules\userGroups\models\UserGroupsAccess');

        $empresas = $this->getEmpresas();
        foreach ($empresas as $empresa) {
            $colaboradores = $this->getColaboradores($empresa->serial);
            $gestores = $this->getGestores($empresa->id);
            if(!empty($colaboradores)){
                foreach ($gestores as  $value) {
                    $this->send($value->nome, $colaboradores, $value->email);
                }
            }
        }
    }

    public function getEmpresas()
    {
        return Empresa::model()->findAllByAttributes(array('ativo' => 1));
    }

    public function getColaboradores($serial)
    {
       return Colaborador::model()->findAll(array('condition' => 'serial_empresa = "'.$serial.'" and ativo = 0 and status = 1'));
    }

    public function getGestores($id)
    {
        return UserGroupsUser::model()->findAll(array('condition' => 'username NOT LIKE "admin%" and 
                fk_empresa = '.$id));
    }    

    public function send($nome, $colaboradores, $email)
    {
        $html = MetodosGerais::templateGestorPerfilIncompleto($nome, $colaboradores);
        SendMail::send('notificacao@vivainovacao.com', array($email), 'Colaboradores com informações pendentes', $html, '');        
    }
}