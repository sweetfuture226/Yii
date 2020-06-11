<?php

/**
 * Created by PhpStorm.
 * User: Lucas
 * Date: 13/05/2016
 * Time: 13:39
 */
class notificaDiasSemAcessoCommand extends CConsoleCommand
{
    public function run()
    {
        Yii::setPathOfAlias('userGroups.models.UserGroupsGroup', 
            'C:\xampp\htdocs\viva-smith\protected\modules\userGroups\models\UserGroupsGroup');
        Yii::setPathOfAlias('userGroups.models.UserGroupsAccess', 
            'C:\xampp\htdocs\viva-smith\protected\modules\userGroups\models\UserGroupsAccess');

        $empresas = Empresa::model()->findAllByAttributes(array('ativo' => 1));
        foreach ($empresas as $empresa) {
            $diretores =  $this->getDiretores($empresa->id);
            $coordenadores = $this->getCoordenadores($empresa->id, -6);

            if(!empty($coordenadores)){
                foreach ($coordenadores as  $value) {
                    echo $value->nome . "\n";
                    $this->send($value->nome, $value->email, false);
                }
            }
            $coordenadores = $this->getCoordenadores($empresa->id, -16);
            if(!empty($diretores)){
                if(!empty($coordenadores)){
                    foreach ($diretores as $diretor) {
                        foreach ($coordenadores as  $value) {
                            $this->send($value->nome, $value->email, true, $diretor->nome);
                        }
                    }
                }
            }
        }
    }


    public function getCoordenadores($id, $time)
    {
        return UserGroupsUser::model()->findAll(array('condition' => 'username NOT LIKE "admin%" and 
               TIMESTAMPDIFF(DAY, CURDATE(), last_login) = '.$time.' and group_id = 4 and fk_empresa = '.$id));
    }   

    public function getDiretores($id)
    {
        return UserGroupsUser::model()->findAll(array('condition' => 'username NOT LIKE "admin%" and group_id = 3 and 
            fk_empresa = '.$id));
    }  

    public function send($nomeCoordenador, $email, $toDiretor, $nomeDiretor = "")
    {

        $html = !$toDiretor ?  MetodosGerais::templateCoordenadorAusenteSeteDias($nomeCoordenador) :  MetodosGerais::templateCoordenadorAusenteQuinzeDias($nomeDiretor, $nomeCoordenador);
        SendMail::send('notificacao@vivainovacao.com', array("robsonferreira@vivainovacao.com"), 'Ausencia', $html, '');    
    }
}