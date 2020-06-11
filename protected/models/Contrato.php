<?php

/**
 * This is the model class for table "contrato".
 *
 * The followings are the available columns in table 'contrato':
 * @property integer $id
 * @property string $obra
 * @property string $codigo
 * @property string $fk_empresa
 * @property int $finalizada
 * @property string $data_inicio
 * @property string $data_final
 * @property float $valor
 * @property int $ativo
 * @property string $data_finalizacao
 * @property string $tempo_previsto
 *
 * The followings are the available model relations:
 * @property Empresa $empresa
 * @property Documento $documento
 *
 */
class Contrato extends CActiveRecord
{

    public $duracao;
    public $nome;
    public $id;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Contrato the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'contrato';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('nome, codigo', 'length', 'max' => 255),
            array('nome, codigo,coordenador,data_inicio,data_final, valor', 'required'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, nome, codigo, fk_empresa,finalizada,data_inicio,data_final,data_finalizacao, valor, tempo_previsto', 'safe'),
            array('id, nome, codigo, fk_empresa,finalizada,data_inicio', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'empresa' => array(self::BELONGS_TO, 'Empresa', 'fk_empresa'),
            'documento' => array(self::HAS_MANY, 'Documento', 'fk_contrato'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'nome' => Yii::t("smith", 'Nome do contrato'),
            'codigo' => Yii::t("smith", 'Código(s) do contrato'),
            'fk_empresa' => Yii::t("smith", 'Empresa'),
            'finalizada' => Yii::t("smith", 'Status'),
            'coordenador' => Yii::t("smith", 'Coordenador'),
            'data_inicio' => Yii::t("smith", 'Data de início do contrato'),
            'duracao' => Yii::t("smith", 'Horas gastas'),
            'data_final' => Yii::t("smith", 'Data de fim do contrato'),
            'valor' => Yii::t("smith", 'Valor previsto para o contrato'),
            'tempo_previsto' => Yii::t("smith", "Tempo Previsto"),
            'receber_email' => Yii::t("smith", "Quero receber um email semanal com o resumo deste contrato"),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $fk_empresa = MetodosGerais::getEmpresaId();
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('nome', $this->nome, true);
        $criteria->compare('codigo', $this->codigo, true);
        $criteria->compare('fk_empresa', $this->fk_empresa);
        $criteria->compare('finalizada', $this->finalizada, true);
        $criteria->compare('data_inicio', $this->data_inicio, true);
        $criteria->compare('data_finalizacao', $this->data_finalizacao, true);

        $criteria->addCondition("t.fk_empresa= $fk_empresa");
        $criteria->addCondition("t.ativo = 1");

        if (Yii::app()->user->groupName == 'coordenador')
            $criteria->addCondition('t.id in (SELECT fk_contrato FROM usuario_has_contrato WHERE fk_usergroups_user = ' . Yii::app()->user->id . ')');


        $sort = new CSort();
        $sort->defaultOrder = 'TRIM(nome) ASC';

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => $sort,
            'pagination' => array(
                'pageSize' => Yii::app()->user->getState('pageSize', Yii::app()->params['defaultPageSize']),
            ),
        ));
    }

    public function has_relations() {
        foreach ($this->relations() as $relation => $attributes) {
            if ($this->getRelated($relation))
                return true;
        }
        return false;
    }

    public function getDocumentos($fk_contrato)
    {
        $sql = "SELECT di.codigo disciplina ,d.nome documento, d.previsto, d.finalizado, d.id as documento_id
                FROM  disciplina di
                INNER JOIN documento d ON di.id = d.fk_disciplina
                WHERE fk_contrato = $fk_contrato";
        $command = Yii::app()->getDb()->createCommand($sql);
        return $command->queryAll();
    }

    public function findLogPorPadrao($padrao) {
        $serial = MetodosGerais::getSerial();
        $sql = "SELECT lc.programa , lc.descricao, lc.duracao, lc.data,lc.usuario, pe.nome
             FROM  log_atividade_consolidado lc
             INNER JOIN  colaborador AS pe ON lc.usuario = pe.ad

             WHERE lc.descricao like '$padrao' AND lc.serial_empresa like '$serial'";

        $command = Yii::app()->getDb()->createCommand($sql);
        return $command->queryAll();
    }

    public function findLogProjetoEclipse($projeto) {
        $sql = "SELECT lc.usuario, lc.descricao, lc.duracao as realizado
                       FROM  log_atividade_consolidado lc
                       INNER JOIN  colaborador AS pe ON lc.usuario = pe.ad
                       WHERE lc.descricao like '%- " . $projeto . "/%'";

        $command = Yii::app()->getDb()->createCommand($sql);
        return $command->queryAll();
    }

    public function findLogProjetoSotero($projeto) {
        $serial = MetodosGerais::getSerial();
        $sql = "SELECT DISTINCT lc.descricao as documento, lc.usuario, lc.duracao as realizado
                       FROM  log_atividade_consolidado lc
                       INNER JOIN  colaborador AS pe ON lc.usuario = pe.ad
                       WHERE lc.descricao like '%$projeto%' AND lc.serial_empresa like '$serial'";

        $command = Yii::app()->getDb()->createCommand($sql);
        return $command->queryAll();
    }

    public function getDataInicio($projeto) {
        $fk_empresa = MetodosGerais::getEmpresaId();
        if ($fk_empresa == 1)
            $sql = "SELECT distinct (data) FROM log_atividade_consolidado "
                    . "WHERE descricao like '%SArq.$projeto%'
                   ORDER BY id ASC LIMIT 1";
        else
            $sql = "SELECT distinct (data) FROM log_atividade_consolidado "
                    . "WHERE descricao like '%$projeto%'
                   ORDER BY id ASC LIMIT 1";


        $command = Yii::app()->getDb()->createCommand($sql);
        return $command->queryAll();
    }

    public function getObraByPrefixo($prefixo) {
        $idEmpresa = MetodosGerais::getEmpresaId();
        $criteria = new CDbCriteria;
        $criteria->addCondition("codigo = '$prefixo'");
        $criteria->addCondition('fk_empresa = ' . $idEmpresa);
        return Contrato::model()->find($criteria);
    }

    /**
     * Verifica se existe alguma registro de contrato da empresa
     * @param int $fk_empresa
     * @return boolean
     */
    public function hasObraByEmpresa($fk_empresa) {
        $model = $this->find(array(
            'condition' => 'fk_empresa = :fk_empresa',
            'params' => array(':fk_empresa' => $fk_empresa)
        ));

        if ($model != NULL)
            return TRUE;
        return FALSE;
    }

    public function afterFind(){
        parent::afterFind();
        $this->data_inicio = MetodosGerais::dataBrasileira($this->data_inicio);
        $this->data_final = MetodosGerais::dataBrasileira($this->data_final);
    }

    public function formatDataRelatorioGeralContratoOpContrato($data, $dataInicio, $dataFim)
    {
        $result = array();
        foreach ($data as $value) {
            if ($value->salario != null) {
                $equipe = Colaborador::model()->findByPk($value->fk_colaborador)->equipes;
                $contrato = Contrato::model()->findByPk($value->fk_obra);
                $result[$value->obra]['data']['codigo_obra'] = $value->codigo;
                $result[$value->obra]['data']['tempo_previsto'] = $value->tempo_previsto;
                $result[$value->obra]['data']['valor_previsto'] = $value->valor_previsto;
                if ($value->tempo_previsto == NULL) {
                    $result[$value->obra]['data']['tempo_previsto'] = "00:00:00";
                }
                $result[$value->obra][$value->colaborador]['tempo_trab'] = ($value->duracao / 3600);
                $result[$value->obra][$value->colaborador]['custo'] = Contrato::getCalculoCustoColaboradorByData($value->fk_colaborador, $contrato, $dataInicio, $dataFim, $value->duracao);
                $result[$value->obra][$value->colaborador]['equipe'] = (isset($equipe)) ? $equipe->nome : 'Equipe indefinida';
            }
        }
        return $result;
    }

    public function geraHtmlRelatorioGeralContratoOpContrato($dados){
        $html = "";
        foreach ($dados as $key=>$value){
            $totalHoras = 0;
            $totalValor = 0;
            $aux = mb_convert_case($key,MB_CASE_LOWER,mb_detect_encoding($key));

            $html .=  '<table  class="table_custom" border="1px">
                <tr>
                    <th colspan="4" style="text-align: left;"> Contrato: '.$value['data']['codigo_obra'] .
                        ' - '. ucwords($aux) .'
                    </th>
                </tr>
                <tr>
                    <th colspan="2" style="text-align: left;">Tempo previsto: '.($value['data']['tempo_previsto'] != "00:00:00" ? $value['data']['tempo_previsto'] : "Não informado").'</th>
                    <th colspan="2" style="text-align: left;">Orçamento previsto: '.($value['data']['valor_previsto'] ? "R$ ".MetodosGerais::float2real($value['data']['valor_previsto']) : "Não informado").'</th>
                </tr>
                <tr style="background-color: #CCC; text-decoration: bold;">
                    <th>' . Yii::t("smith", 'Equipe') . '</th>
                    <th>' . Yii::t("smith", 'Colaborador') . '</th>
                    <th>' . Yii::t("smith", 'Tempo realizado') . '</th>
                    <th>' . Yii::t("smith", 'Orçamento realizado') . '</th>
                </tr>';

            foreach ($value as $colaborador=>$valor){
                if($colaborador != "data"){
                    $totalHoras += $valor['tempo_trab'];
                    $totalValor += $valor['custo'];
                    $html .= '<tr><td style="width: 145px;">' . $valor['equipe'] . '</td>'
                        . '<td style="width: 300px;">' . $colaborador . '</td>'
                            . '<td style="text-align: center; width: 85px;">'.MetodosGerais::formataTempo($valor['tempo_trab']*3600).'</td>'
                            . '<td style="text-align: center; width: 85px;">R$ '.MetodosGerais::float2real($valor['custo']).'</td></tr>';
                }
            }
            $totalHoras = MetodosGerais::formataTempo($totalHoras*3600);
            $totalValor = MetodosGerais::float2real($totalValor);

            $html .= '<tr style="background-color: #CCC; text-decoration: bold;">'
                . '<th> </th>'
                . '<th>Total</th>'
                . '<th>'.$totalHoras.'</th>'
                . '<th>R$ '.$totalValor.'</th>'
                . '</tr>';
            $html .= "</table><br>";
        }
        return $html;
    }

    public function formatDataRelatorioGeralContratoOpEquipe($data, $dataInicio, $dataFim)
    {
        $dados = array();
        foreach ($data as $value) {
            if ($value->salario != null) {
                $equipe = Colaborador::model()->findByPk($value->fk_colaborador)->equipes;
                $equipe = (isset($equipe)) ? $equipe->nome : 'Equipe indefinida';
                $contrato = Contrato::model()->findByPk($value->fk_obra);
                if (!isset($dados[$equipe][$value->obra]['horas'])) {
                    $dados[$equipe][$value->obra]['horas'] = ($value->duracao / 3600);
                    $documento = Documento::model()->findByAttributes(array('fk_contrato' => $value->fk_obra));
                    if ($documento != NULL || !empty($documento)) {
                        $dados[$equipe][$value->obra]['tempoPrevisto'] = $documento->previsto;
                        $dados[$equipe][$value->obra]['valorPrevisto'] = round((($value->salario) * ($documento->previsto / 3600)), 2);
                    } else {
                        $dados[$equipe][$value->obra]['valorPrevisto'] = $value->valor_previsto;
                        if ($value->tempo_previsto == NULL) {
                            $dados[$equipe][$value->obra]['valorPrevisto'] = "0";
                        }
                    }
                } else {
                    $dados[$equipe][$value->obra]['horas'] += ($value->duracao / 3600);
                }
                (!isset($dados[$equipe][$value->obra]['valor_horas'])) ?
                    $dados[$equipe][$value->obra]['valor_horas'] = Contrato::getCalculoCustoColaboradorByData($value->fk_colaborador, $contrato, $dataInicio, $dataFim, $value->duracao) :
                    $dados[$equipe][$value->obra]['valor_horas'] += Contrato::getCalculoCustoColaboradorByData($value->fk_colaborador, $contrato, $dataInicio, $dataFim, $value->duracao);
                $dados[$equipe][$value->obra]['codigo_obra'] = $value->codigo;
            }
        }
        return $dados;
    }

    public function formatDataRelatorioGeralContratoOpColaborador($data, $dataInicio, $dataFim)
    {
        $dados = array();
        foreach ($data as $value) {
            $contrato = Contrato::model()->findByPk($value->fk_obra);
            if (!isset($dados[$value->colaborador][$value->obra]['horas'])) {
                $dados[$value->colaborador][$value->obra]['horas'] = ($value->duracao / 3600);
                $documento = Documento::model()->findByAttributes(array('fk_contrato' => $value->fk_obra));
                if ($documento != NULL || !empty($documento)) {
                    $dados[$value->colaborador][$value->obra]['tempoPrevisto'] = $documento->previsto;
                    $dados[$value->colaborador][$value->obra]['valorPrevisto'] = round((($value->salario) * ($documento->previsto / 3600)), 2);
                } else {
                    $dados[$value->colaborador][$value->obra]['valorPrevisto'] = $value->valor_previsto;
                    if($value->tempo_previsto == NULL){
                        $dados[$value->colaborador][$value->obra]['valorPrevisto'] = "0";
                    }
                }
            } else {
                $dados[$value->colaborador][$value->obra]['horas'] += ($value->duracao / 3600);
            }
            $dados[$value->colaborador][$value->obra]['valor_horas'] = round((($value->salario) * ($value->duracao / 3600)), 2);
            $dados[$value->colaborador][$value->obra]['valor_horas'] = Contrato::getCalculoCustoColaboradorByData($value->fk_colaborador, $contrato, $dataInicio, $dataFim, $value->duracao);
            $dados[$value->colaborador][$value->obra]['codigo_obra'] = $value->codigo;
        }
        return $dados;
    }

    public function geraHtmlRelatorioGeralContrato($dados, $opcao) {
        $html = "";
        foreach ($dados as $key=>$value){
            $totalHoras = 0;
            $totalPrevHoras = 0;
            $totalValor = 0;
            $totalPrevValor = 0;
            $aux = mb_convert_case($key,MB_CASE_LOWER,mb_detect_encoding($key));
            $html .=  '<table  class="table_custom" border="1px">
                <tr>
                    <th colspan="6" style="text-align: left;">' . ucfirst(Yii::t("smith", $opcao)) . ' : ' . ucwords($aux) . '  </th>
                </tr>
                <tr style="background-color: #CCC; text-decoration: bold;">
                    <th>' . Yii::t("smith", 'Contrato') . '</th>
                    <th style="text-align: center; width: 60px;">' . Yii::t("smith", 'Código') . '</th>
                    <th style="text-align: center; width: 56px;">' . Yii::t("smith", 'Tempo realizado') . '</th>
                    <th style="text-align: center; width: 56px;">' . Yii::t("smith", 'Tempo previsto') . '</th>
                    <th style="text-align: center; width: 56px;">' . Yii::t("smith", 'Orçamento realizado') . '</th>
                    <th style="text-align: center; width: 56px;">' . Yii::t("smith", 'Orçamento previsto') . '</th>
                </tr>';
            foreach ($value as $chave=>$valor){
                $totalHoras += $valor['horas'];
                if (isset($valor['tempoPrevisto'])) $totalPrevHoras += $valor['tempoPrevisto'];
                $totalValor += $valor['valor_horas'];
                if (isset($valor['valorPrevisto'])) $totalPrevValor += $valor['valorPrevisto'];
                $html .= '<tr> '
                        . '<td style="width: 200px;">'.$chave.'</td>'
                        . '<td style="text-align: center; width: 60px;">'.$valor['codigo_obra'].'</td>'
                        . '<td style="text-align: center; width: 85px;">'.MetodosGerais::formataTempo($valor['horas']*3600).'</td>'
                        .'<td style="text-align: center; width: 85px;">';
                $html .= (isset($valor['tempoPrevisto'])) ? MetodosGerais::formataTempo($valor['tempoPrevisto']*3600) : '-';
                $html .= '</td><td style="text-align: center; width: 85px;">R$ '.  MetodosGerais::float2real($valor['valor_horas']).'</td>'
                        . '<td style="text-align: center; width: 85px;">';
                $html .= (isset($valor['valorPrevisto'])) ? 'R$ ' . MetodosGerais::float2real($valor['valorPrevisto']) : '-';
                $html .= '</td></tr>';
            }
            $totalHoras = MetodosGerais::formataTempo($totalHoras*3600);
            if (isset($totalPrevHoras)) { $totalPrevHoras = MetodosGerais::formataTempo($totalPrevHoras*3600); } else { $totalPrevHoras = 0; };
            $totalValor = MetodosGerais::float2real($totalValor);
            if (isset($totalPrevValor)) { $totalPrevValor = 'R$ ' . MetodosGerais::float2real($totalPrevValor); } else { $totalPrevValor = 0; };
            $html .= '<tr style="background-color: #CCC; text-decoration: bold;">'
                . '<th colspan="2">Totais</th>'
                . '<th>'.$totalHoras.'</th>'
                . '<th>'.$totalPrevHoras.'</th>'
                . '<th>R$ '.$totalValor.'</th>'
                . '<th>'.$totalPrevValor.'</th>'
                . '</tr>';
            $html .= "</table><br>";
        }
        return $html;
    }


    /**
     * @param $fkColaborador
     * @param $objContrato
     * @param $tipo
     * @param $dataInicio
     * @param $dataFim
     * @param $tempoRealizado
     * @return float|int
     */
    public static function getCalculoCustoColaboradorByData($fkColaborador, $objContrato, $dataInicio, $dataFim, $tempoRealizado, $tipo = '')
    {
        $custo = $custoOcioso = 0;
        $colaborador = Colaborador::model()->with('hasSalario')->findByPk($fkColaborador);
        $arrayCusto = array();
        if (count($colaborador->hasSalario) > 1) {
            $i = 0;
            foreach ($colaborador->hasSalario as $item) {
                if ((strtotime($dataInicio) > strtotime($item->data_inicio) && isset($colaborador->hasSalario[$i + 1])) || (!$i && ((strtotime($dataInicio) < strtotime($item->data_inicio))))) {
                    if (strtotime($colaborador->hasSalario[$i + 1]->data_inicio) > strtotime($dataFim))
                        $data = $dataFim;
                    else
                        $data = date('Y-m-d', strtotime('-1 days', strtotime($colaborador->hasSalario[$i + 1]->data_inicio)));

                    $tempoRealizado = GrfProjetoConsolidado::model()->getProdutividadeContratoPorColaborador($tipo, $objContrato, $dataInicio, $data, $fkColaborador);
                    if (!empty($tempoRealizado)) {
                        $tempoRealizado = $tempoRealizado[0]->duracao;
                        $custoHora = $item->valor / ((MetodosGerais::time_to_seconds($colaborador->horas_semana) / 3600) * 4);
                        $custoParcial = round((($custoHora) * ($tempoRealizado / 3600)), 2);
                        $arrayCusto[$dataInicio . '-' . $data] = $custoParcial;
                    }
                } elseif (strtotime($item->data_inicio) < strtotime($dataFim)) {
                    if (!isset($colaborador->hasSalario[$i + 1]) || strtotime($colaborador->hasSalario[$i + 1]->data_inicio) > strtotime($dataFim))
                        $data = $dataFim;
                    else
                        $data = date('Y-m-d', strtotime('-1 days', strtotime($colaborador->hasSalario[$i + 1]->data_inicio)));
                    $dataInicio = $item->data_inicio;
                    $tempoRealizado = GrfProjetoConsolidado::model()->getProdutividadeContratoPorColaborador($tipo, $objContrato, $dataInicio, $data, $fkColaborador);
                    if (!empty($tempoRealizado)) {
                        $tempoRealizado = $tempoRealizado[0]->duracao;
                        $custoHora = $item->valor / ((MetodosGerais::time_to_seconds($colaborador->horas_semana) / 3600) * 4);
                        $custoParcial = round((($custoHora) * ($tempoRealizado / 3600)), 2);
                        $arrayCusto[$dataInicio . '-' . $dataFim] = $custoParcial;
                    }
                }
                $i++;
            }
        } else {
            $custoHora = $colaborador->salario / ((MetodosGerais::time_to_seconds($colaborador->horas_semana) / 3600) * 4);
            $custo = round((($custoHora) * ($tempoRealizado / 3600)), 2);
        }
        return $custo + array_sum($arrayCusto);
    }

    public function searchContractsWithDocuments($fk_empresa)
    {
        $criteria = new CDbCriteria;
        $criteria->select = "distinct(t.nome), t.id";
        $criteria->join = "join documento as d on t.id = d.fk_contrato";
        $criteria->order = "t.nome";
        $criteria->addCondition("t.fk_empresa = $fk_empresa");
        return $this->findAll($criteria);
    }

}
