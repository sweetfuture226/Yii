<?php

/**
 * Class enviaEmailModificarPasswdCommand
 *
 * CRON utilizado para verificar se a senha dos clientes têm mais de 90 dias sem alterar; caso positivo solicitar que
 * o cliente altere a senha por questões de segurança.
 */
class enviaEmailModificarPasswdCommand extends CConsoleCommand {
    
    public function run() {
        Yii::setPathOfAlias('userGroups.models.UserGroupsGroup', '/var/www/viva-smith/protected/modules/userGroups/models/UserGroupsGroup');
        Yii::setPathOfAlias('userGroups.models.UserGroupsAccess', '/var/www/viva-smith/protected/modules/userGroups/models/UserGroupsAccess');
        
        $noventaDias = mktime(0, 0, 0, date("m")  , date("d")-90, date("Y"));
        $noventaDias = date('Y-m-d', $noventaDias);
        $usuarios = UserGroupsUser::model()->findAll(array(
                            'condition'=>'last_change_passwd <= :date',
                            'params'=>array(':date'=>$noventaDias)
        ));
        
        foreach ($usuarios as $usuario){
            $mensagem = 'Sua senha possui mais de 90 dias. Sugerimos que seja modificada para uma maior segurança.';
            // SendMail::send('Alteração de Senha', $usuario->email, $usuario->nome, $mensagem);
            // Ex: MetodosGerais::emailSendGrid(de, para, título, corpo);
            SendMail::send($usuario->email, array($usuario->email), 'Alteração de Senha', $mensagem);
        }
    }
}

