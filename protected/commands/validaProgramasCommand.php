<?php

/**
 * Class validaProgramasCommand
 *
 * CRON utilizado para validar o nome de alguns programas que o executavel não consegue identificar.
 */
class validaProgramasCommand extends CConsoleCommand {

    public function run() {

        $linx = "UPDATE log_atividade
                            SET programa = 'Linx DMS Apollo'
                            WHERE fk_empresa = 43 and descricao NOT LIKE  'Ocioso' and programa LIKE '' ";
        $command = Yii::app()->getDb()->createCommand($linx);
        $command->execute();

        /*$update_altab = "UPDATE log_atividade SET duracao = '00:00:02'
                        WHERE  programa like 'AltTab' 
                        AND serial_empresa like 'M1DI-65KZ-162K-7EKC'
                        AND duracao like '00:00:10'";
        $command = Yii::app()->getDb()->createCommand($update_altab);
        $command->execute();

        $ftool_atividade = "UPDATE log_atividade
                            SET programa = 'Ftool'
                            WHERE  descricao LIKE  '%Ftool%'";
        $command = Yii::app()->getDb()->createCommand($ftool_atividade);
        $command->execute();
        $ftool_atividade_consolida = "UPDATE log_atividade_consolidado
                                    SET programa = 'Ftool'
                                    WHERE  descricao LIKE   '%Ftool%'";
        $command = Yii::app()->getDb()->createCommand($ftool_atividade);
        $command->execute();


        $hydros_atividade = "UPDATE log_atividade
                            SET programa = 'AltoQi Hydros V4'
                            WHERE  descricao LIKE  '%AltoQi Hydros V4%'";
        $command = Yii::app()->getDb()->createCommand($hydros_atividade);
        $command->execute();
        $hydros_atividade_consolida = "UPDATE log_atividade_consolidado
                                    SET programa = 'AltoQi Hydros V4'
                                    WHERE  descricao LIKE   '%AltoQi Hydros V4%'";
        $command = Yii::app()->getDb()->createCommand($hydros_atividade_consolida);
        $command->execute();

        $tqs_atividade = "UPDATE log_atividade
                        SET programa = 'TQS'
                        WHERE  descricao LIKE  '%CAD/TQS%'";
        $command = Yii::app()->getDb()->createCommand($tqs_atividade);
        $command->execute();
        $tqs_atividade_consolida = "UPDATE log_atividade_consolidado
                                SET programa = 'TQS'
                                WHERE  descricao LIKE   '%CAD/TQS%'";
        $command = Yii::app()->getDb()->createCommand($tqs_atividade_consolida);
        $command->execute();


        $totvs_atividade = "UPDATE log_atividade
                                SET programa = 'TOTVS'
                                WHERE  programa LIKE  '%TOTVS%'";
        $command = Yii::app()->getDb()->createCommand($totvs_atividade);
        $command->execute();
        $totvs_atividade_consolida = "UPDATE log_atividade_consolidado
                                            SET programa = 'TOTVS'
                                            WHERE  programa LIKE   '%TOTVS%'";
        $command = Yii::app()->getDb()->createCommand($totvs_atividade_consolida);
        $command->execute();



        $word_atividade = "UPDATE log_atividade
                                SET programa = 'Microsoft Word'
                                WHERE  programa LIKE  '%Word%'";
        $command = Yii::app()->getDb()->createCommand($word_atividade);
        $command->execute();
        $word_atividade_consolida = "UPDATE log_atividade_consolidado
                                            SET programa = 'Microsoft Word'
                                            WHERE  programa LIKE   '%Word%'";
        $command = Yii::app()->getDb()->createCommand($word_atividade_consolida);
        $command->execute();

        $outlook_atividade = "UPDATE log_atividade
                                SET programa = 'Microsoft Outlook'
                                WHERE  programa LIKE  '%Outlook%'";
        $command = Yii::app()->getDb()->createCommand($outlook_atividade);
        $command->execute();
        $outlook_atividade_consolida = "UPDATE log_atividade_consolidado
                                            SET programa = 'Microsoft Outlook'
                                            WHERE  programa LIKE   '%Outlook%'";
        $command = Yii::app()->getDb()->createCommand($outlook_atividade_consolida);
        $command->execute();

        $excel_atividade = "UPDATE log_atividade
                                SET programa = 'Microsoft Excel'
                                WHERE  programa LIKE  '%Excel%'";
        $command = Yii::app()->getDb()->createCommand($excel_atividade);
        $command->execute();
        $excel_atividade_consolida = "UPDATE log_atividade_consolidado
                                            SET programa = 'Microsoft Excel'
                                            WHERE  programa LIKE   '%Excel%'";
        $command = Yii::app()->getDb()->createCommand($excel_atividade_consolida);
        $command->execute();



        $acrobat_atividade = "UPDATE log_atividade
                    SET programa = 'Adobe Acrobat'
                    WHERE  programa LIKE  '%.pdf%'";
        $command = Yii::app()->getDb()->createCommand($acrobat_atividade);
        $command->execute();

        $acrobat_atividade_consolida = "UPDATE log_atividade_consolidado
                    SET programa = 'Adobe Acrobat'
                    WHERE  programa LIKE  '%.pdf%'";
        $command = Yii::app()->getDb()->createCommand($acrobat_atividade_consolida);
        $command->execute();




        $tricalc_atividade = "UPDATE log_atividade
                    SET programa = 'Tricalc'
                    WHERE  descricao LIKE  '%Tricalc 8.1%'";
        $command = Yii::app()->getDb()->createCommand($tricalc_atividade);
        $command->execute();

        $tricalc_atividade_consoldia = "UPDATE log_atividade_consolidado
                                        SET programa = 'Tricalc'
                                        WHERE  programa LIKE  '%Tricalc 8.1%'
                                                        ";
        $command = Yii::app()->getDb()->createCommand($tricalc_atividade_consoldia);
        $command->execute();

        $autocad_atividade = "UPDATE log_atividade
                    SET programa = 'AutoCAD'
                    WHERE  programa LIKE  '%.dwg%'";
        $command = Yii::app()->getDb()->createCommand($autocad_atividade);
        $command->execute();

        $autocad_atividade_consoldia = "UPDATE log_atividade_consolidado
                                        SET programa = 'AutoCAD'
                                        WHERE  programa LIKE  '%.dwg%'
                                                        ";
        $command = Yii::app()->getDb()->createCommand($autocad_atividade_consoldia);
        $command->execute();

        $outlook_atividade = "UPDATE log_atividade
                                SET programa = 'Microsoft Outlook'
                                WHERE  programa LIKE  '%Mensagem (HTML)%'";
        $command = Yii::app()->getDb()->createCommand($outlook_atividade);
        $command->execute();
        $outlook_atividade_consolida = "UPDATE log_atividade_consolidado
                                            SET programa = 'Microsoft Outlook'
                                            WHERE  programa LIKE  '%Mensagem (HTML)%'";
        $command = Yii::app()->getDb()->createCommand($outlook_atividade_consolida);
        $command->execute();
        $skype_atividade = "UPDATE log_atividade
                                SET programa = 'Skype'
                                WHERE  descricao like '%Skype™%'";
        $command = Yii::app()->getDb()->createCommand($skype_atividade);
        $command->execute();
        $skype_atividade_consolida = "UPDATE log_atividade_consolidado
                                        SET programa = 'Skype'
                                        WHERE  descricao like '%Skype™%'";
        $command = Yii::app()->getDb()->createCommand($skype_atividade_consolida);
        $command->execute();

        $excel_atividade = "UPDATE log_atividade
                                SET programa = 'Microsoft Excel'
                                WHERE  programa LIKE  '%.xls%'";
        $command = Yii::app()->getDb()->createCommand($excel_atividade);
        $command->execute();
        $excel_atividade_consolida = "UPDATE log_atividade_consolidado
                                            SET programa = 'Microsoft Excel'
                                            WHERE  programa LIKE   '%.xls%'";
        $command = Yii::app()->getDb()->createCommand($excel_atividade_consolida);
        $command->execute();

        $player_atividade = "UPDATE log_atividade
                                SET programa = 'Media Player Classic Home Cinema'
                                WHERE  programa LIKE  '%.mkv%'";
        $command = Yii::app()->getDb()->createCommand($player_atividade);
        $command->execute();
        $player_atividade_consolida = "UPDATE log_atividade_consolidado
                                            SET programa = 'Media Player Classic Home Cinema'
                                            WHERE  programa LIKE   '%.mkv%'";
        $command = Yii::app()->getDb()->createCommand($player_atividade_consolida);
        $command->execute();

        $utorrent_atividade = "UPDATE log_atividade
                                SET programa = 'µTorrent 3.4.2 (build 34309) [32-bit]'
                                WHERE  programa LIKE  '%.bit]%'";
        $command = Yii::app()->getDb()->createCommand($utorrent_atividade);
        $command->execute();
        $utorrent_atividade_consolida = "UPDATE log_atividade_consolidado
                                            SET programa = 'µTorrent 3.4.2 (build 34309) [32-bit]'
                                            WHERE  programa LIKE   '%.bit]%'";
        $command = Yii::app()->getDb()->createCommand($utorrent_atividade_consolida);
        $command->execute();

        $word_atividade = "UPDATE log_atividade
                                SET programa = 'Microsoft Word'
                                WHERE  programa LIKE  '%Word%'";
        $command = Yii::app()->getDb()->createCommand($word_atividade);
        $command->execute();
        $word_atividade_consolida = "UPDATE log_atividade_consolidado
                                            SET programa = 'Microsoft Word'
                                            WHERE  programa LIKE   '%Word%'";
        $command = Yii::app()->getDb()->createCommand($word_atividade_consolida);
        $command->execute();

        $outlook_atividade = "UPDATE log_atividade
                                SET programa = 'Microsoft Outlook'
                                WHERE  programa LIKE  '%Outlook%'";
        $command = Yii::app()->getDb()->createCommand($outlook_atividade);
        $command->execute();
        $outlook_atividade_consolida = "UPDATE log_atividade_consolidado
                                            SET programa = 'Microsoft Outlook'
                                            WHERE  programa LIKE   '%Outlook%'";
        $command = Yii::app()->getDb()->createCommand($outlook_atividade_consolida);
        $command->execute();

        $excel_atividade = "UPDATE log_atividade
                                SET programa = 'Microsoft Excel'
                                WHERE  programa LIKE  '%Excel%'";
        $command = Yii::app()->getDb()->createCommand($excel_atividade);
        $command->execute();
        $excel_atividade_consolida = "UPDATE log_atividade_consolidado
                                            SET programa = 'Microsoft Excel'
                                            WHERE  programa LIKE   '%Excel%'";
        $command = Yii::app()->getDb()->createCommand($excel_atividade_consolida);
        $command->execute();*/
    }

}

?>