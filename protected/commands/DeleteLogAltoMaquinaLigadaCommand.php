<?php

/**
 * Created by PhpStorm.
 * User: Lucas
 * Date: 31/03/2016
 * Time: 16:36
 */
class DeleteLogAltoMaquinaLigadaCommand extends CConsoleCommand
{
    public function run()
    {
        $empresas = Empresa::model()->findAll();
        foreach ($empresas as $key => $value) {
            $colaboradores = array();
            $coordenadores = array();
            $logs = $this->getData("10:00:00", $value->id);
            foreach ($logs as $log) {
                // Buscando de qual usuário foi realizado o log 
                $colaborador = Colaborador::model()->findByAttributes(array('ad' => $log->usuario, 'fk_empresa' => $log->fk_empresa));

                if ($colaborador != NULL) {
                    if ($colaborador->fk_equipe != NULL) {
                        // Buscando o coordenador desta equipe
                        $coordenador = UserGroupsUser::model()->findByAttributes(array('fk_equipe' => $colaborador->fk_equipe, 'group_id' => 4, 'fk_empresa' => $log->fk_empresa));
                        // Verificando se a equipe tem coordenador
                        if ($coordenador != NULL) {
                            // Se tem vou enviar o e-mail para ele
                            $coordenadores[$coordenador->id]['coordenador']['nome'] = $coordenador->nome;
                            $coordenadores[$coordenador->id]['coordenador']['email'] = $coordenador->email;
                            $coordenadores[$coordenador->id]['colaboradores'][] = $colaborador->nomeCompleto;
                        } else {
                            // Se não tiver vou enviar pro diretor da empresa
                            $diretor = UserGroupsUser::model()->find(array("condition" => "username NOT LIKE 'admin%' AND group_id = 3 AND fk_empresa = " . $log->fk_empresa));
                            $coordenadores[$diretor->id]['coordenador']['nome'] = $diretor->nome;
                            $coordenadores[$diretor->id]['coordenador']['email'] = $diretor->email;
                            $coordenadores[$diretor->id]['colaboradores'][] = $colaborador->nomeCompleto;

                        }
                    }
                }
                $log->delete();
            }

            foreach ($coordenadores as $coordenador) {
                sort($coordenador['colaboradores']);
                SendMail::send('notificacao@vivainovacao.com', array($coordenador['coordenador']['email']),
                    'Computadores passaram a noite ligada', $this->templateGestor($coordenador['coordenador']
                    ['nome'], $coordenador['colaboradores']));
            }
        }
    }

    public function getData($duracao, $fk_empresa)
    {
        $logs = LogAtividade::model()->findAll(array(
            'condition' => 'data = :data and duracao > :duracao and fk_empresa = :fk_empresa',
            'params' => array(
                ':data' => date('Y-m-d'),
                ':duracao' => $duracao,
                ':fk_empresa' => $fk_empresa
            ),
            'group' => 'data,usuario,descricao',
            'order' => 'id ASC'
        ));
        return $logs;
    }

    public function templateGestor($to, $usuario)
    {
        $html = 'Olá ' . explode(" ", $to)[0] . ',';
        $html .= '<br/><br/>';
        $html .= 'O Viva Smith detectou que os colaboradores abaixo tiveram indícios de computador ligado durante a noite: 
        <br/><br/>';
        $i = 1;

        foreach ($usuario as $key => $value) {
            $html .= "{$i}. <strong>" . $value . "</strong>;</br><br>";
            $i++;
        }
        $html .= "<br/>";
        $html .= "Visando atender à política de sustentabilidade e redução de custos, sugerimos verificar se esta ação ocorreu devido uma demanda interna.";
        $html .= "<br/>";
        $html .= "<br/>";
        $html .= "Atenciosamente,";
        $html .= "<br/><br/>";
        $html .= "Equipe Smith";
        return $html;
    }
}