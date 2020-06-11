<?php

/**
 * Created by PhpStorm.
 * User: Lucas
 * Date: 13/05/2016
 * Time: 13:39
 */
class notificaPrimeiraCapturaCommand extends CConsoleCommand
{
    public function run()
    {
        Yii::setPathOfAlias('userGroups.models.UserGroupsGroup', 
            'C:\xampp\htdocs\viva-smith\protected\modules\userGroups\models\UserGroupsGroup');
        Yii::setPathOfAlias('userGroups.models.UserGroupsAccess', 
            'C:\xampp\htdocs\viva-smith\protected\modules\userGroups\models\UserGroupsAccess');

        $pocs = Empresa::model()->searchPoc()->getData();
        foreach ($pocs as $poc) {
            $dataInicioPoc = $this->getDataInicioPoc($this->getEmpresas($poc->id));
            $duracaoReal = MetodosGerais::DataDiff($dataInicioPoc, date('Y-m-d'));
            $coordenadores = $this->getPessoas($poc->id);
            echo $poc->nome. " - ". $duracaoReal . "\n";

            if($duracaoReal == 1){
                foreach ($coordenadores as $value) {
                    $this->send($value->nome, $value->username, $value->password, $value->email);
                }
            }
        }
    }

    public function getEmpresas($id)
    {
        return LogAtividade::model()->findByAttributes(array('fk_empresa' => $id));
    }

    public function getDataInicioPoc($dataInicioPoc)
    {
       return (!empty($dataInicioPoc)) ? MetodosGerais::dataAmericana($dataInicioPoc->data) : date("Y-m-d");
    }

    public function getPessoas($id)
    {
       return UserGroupsUser::model()->findAll(array('condition' => 'username NOT LIKE "admin%" and 
                fk_empresa = '.$id));
    }

    public function send($nome, $username, $password, $email)
    {
        $html = MetodosGerais::templateGestor($nome, $username, $password);
        SendMail::send('notificacao@vivainovacao.com', array('lucascardoso@vivainovacao.com', 'albertmoreira@vivainovacao.com', $email), 'Inicio da POC', $html, '');        
    }
}