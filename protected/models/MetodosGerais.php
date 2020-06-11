<?php

class MetodosGerais {

    /**
     * @return mixed
     *
     * Método utilizado para iniciar contagem para calculo de perfomance dos gráficos
     */
    public static function inicioContagem()
    {
        $time = microtime();
        $time = explode(' ', $time);
        $time = $time[1] + $time[0];
        $start = $time;
        return $start;
    }

    /**
     * @param $start
     * @return float
     *
     * Método utilizado para retornar o resultado da perfomance dos gráficos
     */
    public static function tempoResposta($start)
    {
        $time = microtime();
        $time = explode(' ', $time);
        $time = $time[1] + $time[0];
        $finish = $time;
        $total_time = round(($finish - $start), 4);
        return $total_time;
    }

    /**
     * Função para gerar senhas aleatórias
     *
     * @param integer $tamanho Tamanho da senha a ser gerada
     * @param boolean $maiusculas Se terá letras maiúsculas
     * @param boolean $numeros Se terá números
     * @param boolean $simbolos Se terá símbolos
     *
     * @return string A senha gerada
     */
    public static function geraSenha($tamanho = 6, $maiusculas = true, $numeros = true, $simbolos = false)
    {
        $lmin = 'abcdefghijklmnopqrstuvwxyz';
        $lmai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $num = '1234567890';
        $simb = '!@#$%*-';
        $retorno = '';
        $caracteres = '';

        $caracteres .= $lmin;
        if ($maiusculas) $caracteres .= $lmai;
        if ($numeros) $caracteres .= $num;
        if ($simbolos) $caracteres .= $simb;

        $len = strlen($caracteres);
        for ($n = 1; $n <= $tamanho; $n++) {
            $rand = mt_rand(1, $len);
            $retorno .= $caracteres[$rand-1];
        }
        return $retorno;
    }


    /**
     * @param array $files
     * @param string $destination
     * @param bool|false $overwrite
     * @return bool
     *
     * Criar um arquivo zip.
     */
    public static function create_zip($files = array(), $destination = '', $overwrite = false) {
        //if the zip file already exists and overwrite is false, return false
        $src = dirname(Yii::app()->request->scriptFile) . '/public/';
        if (file_exists($src . $destination) && !$overwrite) {
            return false;
        }
        //vars

        $valid_files = array();
        //if files were passed in...
        if (is_array($files)) {
            //cycle through each file
            foreach ($files as $file) {
                //make sure the file exists

                if (file_exists($src . $file)) {
                    $valid_files[] = $file;
                }
            }
        }
        //if we have good files...
        if (count($valid_files)) {
            //create the archive
            $zip = new ZipArchive();
            if ($zip->open($src . $destination, $overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
                return false;
            }
            //add the files
            foreach ($valid_files as $file) {
                $zip->addFile($src . $file, $file);
            }
            //debug
            //echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;
            //close the zip -- done!
            $zip->close();

            //check to make sure the file exists
            return file_exists($src . $destination);
        } else {
            return false;
        }
    }

    public static function getEmpresaId() {
        return Yii::app()->user->getEmpresaId();
    }

    public static function getSerial() {
        return Yii::app()->user->getSerialEmpresa();
    }

    public static function getSerialApi($id) {
        $empresa = Empresa::model()->findByPk($id);
        return $empresa->serial;
    }

    public static function getEquipe() {
        return Yii::app()->user->getEquipe();
    }

    /**
     * @param $tempo
     * @return string
     *
     * Transformar duração em segundos para o formato HH:MM:SS
     */
    public static function formataTempo($tempo) {
        $seconds = $tempo;
        $h = (int) ($seconds / 3600);
        $m = (int) (($seconds - $h * 3600) / 60);
        $s = (int) ($seconds - $h * 3600 - $m * 60);
        return (($h) ? (($h < 10) ? ("0" . $h) : $h) : "00") . ":" . (($m) ? (($m < 10) ? ("0" . $m) : $m) : "00") . ":" . (($s) ? (($s < 10) ? ("0" . $s) : $s) : "00");
    }

    public static function geraTimestamp($data) {
        $partes = explode('/', $data);
        return mktime(0, 0, 0, $partes[1], $partes[0], $partes[2]);
    }

    /**
     * @param $data
     * @return string
     *
     * Método auxiliar para converter a hora do servidor para o horário brasileiro.
     */
    public static function getHoraServidor($data) {
        $dateTime = new DateTime($data, new DateTimeZone('America/New_York'));
        $dateTime->setTimezone(new DateTimeZone('America/Bahia'));
        //$dateTime->modify('-4 hour');
        return $dateTime->format('H:i:s');
    }

    /**
     * @param $data
     * @return string
     *
     * Método auxiliar para converter a hora do servidor para o horário brasileiro.
     */
    public static function getHoraServidor2($data) {
        $dateTime = new DateTime($data, new DateTimeZone('America/New_York'));
        $dateTime->setTimezone(new DateTimeZone('America/Bahia'));
        $dateTime->modify('-4 hour');
        return $dateTime->format('H:i:s');
    }

    /**
     * @param $data
     * @return string
     *
     * Método auxiliar para converter o horário brasileiro para o timezone do servidor.
     */
    public static function setHoraServidor($data) {
        $dateTime = new DateTime($data, new DateTimeZone('America/Bahia'));
        $dateTime->setTimezone(new DateTimeZone('America/New_York'));
        return $dateTime->format('H:i:s');
    }

    public $meses = array('01' => "Janeiro", '02' => "Fevereiro", '03' => "Março", '04' => "Abril", '05' => "Maio", '06' => "Junho", '07' => "Julho", '08' => "Agosto", '09' => "Setembro", '10' => "Outubro", '11' => "Novembro", '12' => "Dezembro");

    /**
     * Retorna o número de um mês
     * @param string $mes nome do mês
     */
    public static function mes($mes) {
        switch ($mes) {
            case 'Janeiro':
                return '01';
                break;
            case 'Fevereiro':
                return '02';
                break;
            case 'Março':
                return '03';
                break;
            case 'Abril':
                return '04';
                break;
            case 'Maio':
                return '05';
                break;
            case 'Junho':
                return '06';
                break;
            case 'Julho':
                return '07';
                break;
            case 'Agosto':
                return '08';
                break;
            case 'Setembro':
                return '09';
                break;
            case 'Outubro':
                return '10';
                break;
            case 'Novembro':
                return '11';
                break;
            case 'Dezembro':
                return '12';
                break;
        }
    }

    public static function mesString($mes) {
        switch ($mes) {
            case '1':
                return 'Janeiro';
                break;
            case '2':
                return 'Fevereiro';
                break;
            case '3':
                return 'Março';
                break;
            case '4':
                return 'Abril';
                break;
            case '5':
                return 'Maio';
                break;
            case '6':
                return 'Junho';
                break;
            case '7':
                return 'Julho';
                break;
            case '8':
                return 'Agosto';
                break;
            case '9':
                return 'Setembro';
                break;
            case '10':
                return 'Outubro';
                break;
            case '11':
                return 'Novembro';
                break;
            case '12':
                return 'Dezembro';
                break;
        }
    }

    /**
     * valor em R$ para Float : R$ 1.050,34 => 1050.34
     * @param string $valor
     */
    public static function real2float($valor) {
        return str_replace(",", ".", str_replace(".", "", $valor));
    }

    /**
     * Retorna o valor no formato R$ 9.999,99
     * @param float $valor
     *
     */
    public static function float2real($valor) {

        $decimal = explode(".", $valor);

        //Se tiver centavos transforma *.1 em *,10
        if (isset($decimal[1])) {
            if (strlen($decimal[1]) == 1) {
                $decimal[1] = $decimal[1] . '0';
            }
            $decimal[1] = ',' . $decimal[1];
            //Se não tiver centavos adiciona o padrão ,00
        } else {
            $decimal[1] = ',00';
        }

        if ($decimal[0] == '') {
            return '';
        }

        //Verifica se é negativo
        if ($decimal[0][0] == '-') {
            $negativo = true;
            $decimal[0] = ltrim($decimal[0], '-');
        } else
            $negativo = false;

        //Transforma 1450 em 1.450
        if (strlen($decimal[0]) > 3) {
            $string = str_split($decimal[0]);

            $chaves = array_keys($string);
            $posicao_ponto = end($chaves) - 2;
            $string[$posicao_ponto] = '.' . $string[$posicao_ponto];

            //Se for uma quantia milionária ;D
            if ($posicao_ponto > 3) {
                $string[$posicao_ponto - 3] = '.' . $string[$posicao_ponto - 3];
            }

            //Se for uma quantia bilionária ;DDDDDD
            if ($posicao_ponto > 6) {
                $string[$posicao_ponto - 6] = '.' . $string[$posicao_ponto - 6];
            }

            //Se for uma quantia trilionária \õ/
            if ($posicao_ponto > 9) {
                $string[$posicao_ponto - 9] = '.' . $string[$posicao_ponto - 9];
            }

            $decimal[0] = implode($string);
        }

        if ($negativo)
            $valor = implode(array('-', $decimal[0], $decimal[1]));
        else
            $valor = implode($decimal);

        return $valor;
    }

    /**
     * @param $data
     * @return bool|string
     *
     * Transformar formato brasileiro de data (10/02/2005) para formato americano (2005-02-10)
     */
    public static function dataAmericana($data) {
        $d = explode('/', $data);
        if (count($d) == 3)
            $data = date("Y-m-d", mktime(0, 0, 0, $d[1], $d[0], $d[2]));

        return $data;
    }

    public static function dataAmericana2($data) {
        $x = explode(' ', $data);
        $d = explode('/', $x[0]);

        if (count($d) == 3)
            $data = date("Y-m-d", mktime(0, 0, 0, $d[1], $d[0], $d[2]));

        return $data;
    }

    /**
     * @param $data
     * @return bool|string
     *
     * Transformar formato americano de data  (2005-02-10) para formato brasileiro (10/02/2005)
     */
    public static function dataBrasileira($data) {
        $d = explode('-', $data);
        if (count($d) == 3)
            $data = date("d/m/Y", mktime(0, 0, 0, $d[1], $d[2], $d[0]));

        return $data;
    }


    /**
     * @param $data
     * @return bool|string
     *
     * Transformar formato americano de data  (2005-02-10 10:00:00) para formato brasileiro (10/02/2005)
     */
    public static function dateTimeBrasileiro($data)
    {
        return date('d/m/Y', strtotime($data));
    }


    /**
     * @param $data
     * @return bool|string
     *
     * Concatena valores com uma porcetagem [%], ideal para usar em grid
     */
    public static function concatPorcetagem($value)
    {
        return $value . "%";
    }

    /**
     * @param $mes
     * @param $ano
     * @return array
     *
     * Função que retorna datas uteis no mês.
     */


    public static function datas_uteis_mes($mes, $ano)
    {
        $dias_no_mes = $num = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
        $arrayDatas = $arrayFeriados = array();
        for ($dia = 1; $dia <= $dias_no_mes; $dia++) {
            $timestamp = mktime(0, 0, 0, $mes, $dia, $ano);
            $semana = date("N", $timestamp);
            if ($semana < 6) {
                array_push($arrayDatas, date("d/m", $timestamp));
            }
        }
        $feriados = CalendarioFeriados::model()->findAll(array("condition" => "fk_empresa =" . MetodosGerais::getEmpresaId() . " AND data like '$ano-$mes%' AND ativo = 1"));
        foreach ($feriados as $feriado) {
            $arrayFeriados[] = date('d/m', strtotime($feriado->data));
        }
        $resultado = array_diff($arrayDatas, $arrayFeriados);
        return $resultado;
    }

    /**
     * @param $diaIni
     * @param $diaFim
     * @return int
     *
     * Calcular dias uteis em um intervalo de datas; excluindo os fim de semanas
     */
    public static function dias_uteis($diaIni, $diaFim) {
        $countUteis = 0;
        $feriados = CalendarioFeriados::model()->findAllByAttributes(array("fk_empresa" => MetodosGerais::getEmpresaId()));
        while ($diaIni <= $diaFim) {
            $dS = date("w", $diaIni);
            if ($dS != "0" && $dS != "6") {
                $countUteis++;
                foreach ($feriados as $feriado) {
                    $diaInicial = date("Y-m-d", $diaIni);
                    if ($feriado->data == $diaInicial) {
                        $countUteis--;
                        break;
                    }
                }
            }
            $diaIni += 86400;
        }
        return $countUteis;
    }

    /**
     * @param $diaIni
     * @param $diaFim
     * @param $ferias
     * @return int
     *
     * Calcular dias uteis em um intervalo de datas; excluindo os fim de semanas e datas que o colaborador está de férias
     */
    public static function diasUteisColaborador($diaIni, $diaFim, $ferias) {
        $countUteis = 0;
        $feriados = CalendarioFeriados::model()->findAllByAttributes(array("fk_empresa" => MetodosGerais::getEmpresaId()));

        while ($diaIni <= $diaFim) {
            $dS = date("w", $diaIni);
            if ($dS != "0" && $dS != "6") {
                $countUteis++;
                foreach ($feriados as $feriado) {
                    $diaInicial = date("Y-m-d", $diaIni);
                    if ($feriado->data == $diaInicial) {
                        $countUteis--;
                        break;
                    }
                }
                foreach ($ferias as $obj) {
                    if ($diaIni >= (strtotime($obj->data_inicio)) && $diaIni <= (strtotime($obj->data_fim))) {
                        $countUteis--;
                        break;
                    }
                }
            }
            $diaIni += 86400;
        }
        return $countUteis;
    }

    /**
     * @param $time
     * @return int
     *
     * Transformar tempo no formato HH:MM:SS para segundos
     */
    public static function time_to_seconds($time) {
        $seconds = 0;
        $arr = explode(':', $time);

        $seconds += $arr[0] * 3600;
        $seconds += $arr[1] * 60;
        $seconds += isset($arr[2]) ? $arr[2] : 0;
        return $seconds;
    }

    /**
     * @param $time
     * @return int
     *
     * Transformar tempo no formato HH:MM:SS para minutos
     */
    public static function time_to_minutes($time) {
        $minutes = 0;
        list($hour, $minute, $second) = explode(':', $time);
        $minutes += $hour * 60;
        $minutes += $minute;
        return $minutes;
    }

    /**
     * @param $time1
     * @param $time2
     * @return string
     *
     * Retonar somátorio de tempos no formato HH:MM:SS
     */
    public static function sum_the_time($time1, $time2) {
        $times = array($time1, $time2);
        $seconds = 0;
        foreach ($times as $time) {
            list($hour, $minute, $second) = explode(':', $time);
            $seconds += $hour * 3600;
            $seconds += $minute * 60;
            $seconds += $second;
        }
        $hours = floor($seconds / 3600);
        $seconds -= $hours * 3600;
        $minutes = floor($seconds / 60);
        $seconds -= $minutes * 60;
        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds); // Thanks to Patrick
    }

    public static function reduzirNome($nome) {
        $nomes = explode(" ", $nome);
        if (count($nomes) > 1) {
            $aux = count($nomes) - 1;
            $firstName = $nomes[0];
            $secondName = $nomes[$aux];
            $nome = $firstName . " " . $secondName;
        }
        return $nome;
    }


    public static function getStyleTable() {
        $style = "<style type='text/css'>
                .table_custom{
                    border-spacing: 0;
                    border: 0;
                    border-collapse:collapse;
                }

                .table_custom tbody{
                    padding: 0px 5px 5px 3px;
                    font-size: 10px;
                }

                .table_custom tr th{
                    font-size: 13px;
                    padding: 10px;
                    text-align:center;
                    border: 1px solid #CCC;

                }

                .table_custom  td{
                    font-size: 11px;
                    border: 1px solid #CCC;
                    padding: 4px;
                    vertical-align: middle;
                    white-space: nowrap;
                    text-align:left;
                    border-collapse:collapse;
                }

                .table_custom .cn{
                    text-align:left;
                    font-size: large;
                }

                .header_page{
                    height: 12mm;

                    width: 200mm;
                }
                .header_logo_page{
                    float: left;
                    width: 30mm;
                    height: 8mm;
                    border: 1px solid red;
                    padding-top: 1mm;
                }
                .header_title{
                    float: left;
                    width: 140mm;
                    height: 12mm;
                    font-size: 16px;
                    font-weight: bold;
                    text-align: center;
                    padding-top: 1mm;

                }
                .header_date{
                    position: absolute;
                    top:6mm;
                    left: 168mm;
                    width: 30mm;
                    height: 12mm;
                    padding-bottom: 0mm;
                    text-align:right;

                    font-size: 10px;
                }

                .sep_line{
                    width: 184mm;
                    float: left;
                }
            </style>";
        return $style;
    }

    public static function getStyleTableLand()
    {
        $style = "<style type='text/css'>
                .table_custom{
                    border-spacing: 0;
                    border: 0;
                    border-collapse:collapse;
                }

                .table_custom tbody{
                    padding: 0px 5px 5px 3px;
                    font-size: 10px;
                }

                .table_custom tr th{
                    font-size: 13px;
                    padding: 10px;
                    text-align:center;
                    border: 1px solid #CCC;

                }

                .table_custom  td{
                    font-size: 11px;
                    border: 1px solid #CCC;
                    padding: 4px;
                    vertical-align: middle;
                    white-space: nowrap;
                    text-align:left;
                    border-collapse:collapse;
                }

                .table_custom .cn{
                    text-align:left;
                    font-size: large;
                }

                .header_page{
                    height: 12mm;

                    width: 250mm;
                }
                .header_logo_page{
                    float: left;
                    width: 30mm;
                    height: 8mm;
                    border: 1px solid red;
                    padding-top: 1mm;
                }
                .header_title{
                    float: left;
                    width: 210mm;
                    height: 12mm;
                    font-size: 16px;
                    font-weight: bold;
                    text-align: center;
                    padding-top: 1mm;

                }
                .header_date{
                    position: absolute;
                    top:6mm;
                    left: 250mm;
                    width: 30mm;
                    height: 12mm;
                    padding-bottom: 0mm;
                    text-align:right;

                    font-size: 10px;
                }

                .sep_line{
                    width: 184mm;
                    float: left;
                }
            </style>";
        return $style;
    }

    public static function getRodapeTable() {
        $rodape = '<page_footer>
                        <div style="text-align: center ; font-size: 10px; color: #9C9C9C">
                            '.Yii::t('smith', 'Relatório gerado na plataforma Viva Smith').'
                        </div>
                    </page_footer>';
        return $rodape;
    }

    public static function setStartAndEndDate()
    {
        $date = date("Y-m-d");
        $startDate = date("01/m/Y");
        $today = date('d/m/Y');
        if (strtotime($today) == strtotime($startDate))
            $startDate = date('d/m/Y', strtotime('-1 months'));
        $endDate = date("d/m/Y", strtotime("-1 day", strtotime($date)));
        return array('start' => $startDate, 'end' => $endDate);
    }

    /**
        * Função para conversão de Real em Dólar ou Euro.
        *
        * Parâmetros recebidos
        * @property float $valor
        * @property string $moeda ('dolar' ou 'euro')

        * Formato de retorno da api:
        * {
        *     "bovespa":{
        *         "cotacao":"46955",
        *         "variacao":"-0.14"
        *     },
        *     "dolar": {
        *         "cotacao":"2.7500",
        *         "variacao":"+2.34"
        *     },
        *     "euro":{
        *         "cotacao":"3.4427",
        *         "variacao":"+2.65"
        *     },
        *     "atualizacao":"16\/12\/14   - 14:08"
        * }
    */

    public static function conversaoReal($valor, $moeda) {
        $api = json_decode(file_get_contents("http://developers.agenciaideias.com.br/cotacoes/json"));

        switch ($moeda) {
            case 'dolar':
                $conversao = round($valor / $api->dolar->cotacao, 2);
                break;
            case 'euro':
                $conversao = round($valor / $api->euro->cotacao, 2);
                break;
        }
        return $conversao;
    }

    /**
     * @param $dataInicio - formato americano
     * @param $dataFim - formato americano
     * @return string
     */
    public static function DataDiff($dataInicio, $dataFim)
    {
        $datetime1 = new DateTime($dataFim);
        $datetime2 = new DateTime($dataInicio);
        $interval = $datetime2->diff($datetime1);
        return $interval->format('%a');
    }

    // Retorna difereça entre datas em minutos
    public function DataDiffInMinutes($data_inicial, $data_final)
    {
        return substr(MetodosGerais::formataTempo(MetodosGerais::geraTimestamp($data_inicial) - MetodosGerais::geraTimestamp($data_final)), 2, 10);
    }

    public static function formatTable($th, $row)
    {
        $html = '<table  border="1px" class="table_custom" style="font-family: arial,sans-serif; border-spacing: 0;border: 0;border-collapse:collapse;">';
        $html .= '<tr style="font-size: 15px;padding: 10px;text-align:center;border: 1px solid #CCC;">';
        foreach ($th as $item) {
            $html .= '<th style="font-size: 13px;padding: 10px;text-align:center;border: 1px solid #CCC;text-align: center">' . $item . '</th>';
        }
        $html .= '</tr>';
        foreach ($row as $item) {
            $html .= '<tr style="font-size: 14px;padding: 10px;text-align:center;border: 1px solid #CCC;">';
            foreach ($item as $td) {
                $html .= '<td style="width: 150px;font-size: 12px;border: 1px solid #CCC;padding: 4px;vertical-align: middle;text-align:center;border-collapse:collapse;">' . $td . '</td>';
            }
            $html .= '</tr>';
        }
        $html .= '</table>';

        return $html;
    }

    public static function templateGestor($nome, $login, $senha)
    {
        $html = 'Prezado '.$nome.',';
        $html .= '<br/><br/>';
        $html .= 'A instalação do Viva Smith foi realizada com sucesso. Os primeiros dados já estão sendo capturados. Acesse https://app.vivasmith.com e visualize.';
        $html .= "<br/><br/>";
        $html .= "Atenciosamente,";
        $html .= "<br/><br/>";
        $html .= "Equipe Smith";
        return $html;
    }

    public static function templateGestorPerfilIncompleto($nome, $colaboradores)
    {
        $html = 'Prezado '.$nome.',';
        $html .= '<br/><br/>';
        $html .= 'O Viva Smith detectou usuários com perfil faltando preenchimento. São estes:';
        $html .= "<br/><br/>";
        $contador = 1;
        foreach ($colaboradores as $value) {
            $html .= $contador . " - " . $value->ad;
            $html .= "<br/>";
            $contador++;
        }
        $html .= "<br/><br/>";
        $html .= " Para assegurarmos uma captura adequada das informações, sugerimos que algum usuário autorizado proceda com esta pendência.";
        $html .= "<br/><br/>";
        $html .= "Em caso de dúvidas, envie um e-mail para smith@vivainovacao.com";
        $html .= "<br/><br/>";
        $html .= "Atenciosamente,";
        $html .= "<br/><br/>";
        $html .= "Equipe Smith";
        return $html;
    }

    public static function templateCoordenadorAusenteSeteDias($nome)
    {
        $html = 'Prezado '.$nome.',';
        $html .= '<br/><br/>';
        $html .= 'Tudo bem? O Viva Smith possui uma série de dados estratégicos para apoiar a gestão e prover uma tomada de decisão assertiva. <br/> <br/> Acesse: app.vivasmith.com.';
        $html .= '<br/><br/>';
        $html .= "Atenciosamente,";
        $html .= "<br/><br/>";
        $html .= "Equipe Smith";
        return $html;
    }

    public static function templateCoordenadorAusenteQuinzeDias($nomeDiretor, $nomeCoordenador)
    {
        $html = 'Prezado '.$nomeDiretor.',';
        $html .= '<br/><br/>';
        $html .= 'Tudo bem? O Viva Smith possui uma série de dados estratégicos para apoiar a gestão e prover uma tomada de decisão assertiva. Quando possível, converse com o coordenador <strong>'.$nomeCoordenador.'</strong> para incentivá-lo quanto ao uso.';
        $html .= '<br/><br/>';
        $html .= "Atenciosamente,";
        $html .= "<br/><br/>";
        $html .= "Equipe Smith";
        return $html;
    }


    public static function checkPermissionAccessContract()
    {
        $permissaoAcesso = EmpresaHasParametro::model()->findByAttributes(array('fk_empresa' => MetodosGerais::getEmpresaId()))->permissao_contrato;
        $isCoordenador = (Yii::app()->user->groupName == 'coordenador') ? 1 : 0;
        return (!$isCoordenador || $permissaoAcesso);
    }

    public static function updateLastLogin()
    {
        $usuario = UserGroupsUser::model()->findByPk(Yii::app()->user->id);
        $usuario->last_login = date("Y-m-d H:m:s");
        $usuario->save(false);
    }

}
