<?php

/**
 * This is the model class for table "grf_produtividade_consolidado".
 *
 * The followings are the available columns in table 'grf_produtividade_consolidado':
 * @property string $id
 * @property string $equipe
 * @property double $duracao
 * @property string $data
 * @property string $hora_total
 * @property string $fk_colaborador
 *
 */
class GrfProdutividadeConsolidado extends CActiveRecord {

    public $tempo_trabalhado, $horario_inicio, $horario_fim, $produtividade, $fk_equipe, $meta, $coeficiente, $dias_trabalhados;
    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'grf_produtividade_consolidado';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('equipe, duracao, data, fk_colaborador', 'required'),
            array('duracao', 'numerical'),
            array('equipe', 'length', 'max' => 255),
            array('fk_colaborador', 'length', 'max' => 11),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, equipe, duracao, data, fk_colaborador', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'equipe' => Yii::t("smith", 'Equipe'),
            'nome' => Yii::t("smith", 'Nome'),
            'duracao' => Yii::t("smith", 'Duracao'),
            'data' => Yii::t("smith", 'Data'),
            'produtividade'=>Yii::t("smith", 'Produtividade'),
            'meta' => Yii::t("smith", 'Meta'),
            'coeficiente' => Yii::t("smith", 'Coeficiente'),
            'fk_colaborador' => 'Fk Colaborador',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id, true);
        $criteria->compare('equipe', $this->equipe, true);
        $criteria->compare('duracao', $this->duracao);
        $criteria->compare('data', $this->data, true);
        $criteria->compare('fk_colaborador', $this->fk_colaborador, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return GrfProdutividadeConsolidado the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }


    public function produtividadeDiariaPorData($dataInicio,$dataFim,$fkColaborador){
        $fkEmpresa = MetodosGerais::getEmpresaId();
        $criteria = new CDbCriteria;
        $criteria->select = 'nome, duracao, hora_total, data';
        $criteria->addCondition("fk_empresa=".$fkEmpresa);
        $criteria->addCondition("fk_colaborador = $fkColaborador");
        $criteria->addBetweenCondition('data', $dataInicio, $dataFim);
        $criteria->order = 'data ASC';
        return $this->findAll($criteria);
    }

    public function produtividadeDiariaPorMesAno($mes,$ano,$fkColaborador){
        $fkEmpresa = (MetodosGerais::getEmpresaId()==41)? '22' : MetodosGerais::getEmpresaId(); //filtro ambiente demo
        $fkColaborador = Colaborador::model()->findByAttributes(array("id" => $fkColaborador, "fk_empresa" => $fkEmpresa))->id;
        $criteria = new CDbCriteria;
        $criteria->select = 'nome, duracao*3600 as duracao, data';
        $criteria->addCondition("fk_empresa=".$fkEmpresa);
        $criteria->addCondition("fk_colaborador = $fkColaborador");
        $criteria->addCondition("MONTH(data) = $mes");
        $criteria->addCondition("YEAR(data) = $ano");
        $criteria->order = 'data ASC';
        return $this->findAll($criteria);
    }

    public function produtividadeDiariaPorAno($ano,$fkColaborador){
        $fkEmpresa = (MetodosGerais::getEmpresaId()==41)? '22' : MetodosGerais::getEmpresaId(); //filtro ambiente demo
        $fkColaborador = Colaborador::model()->findByAttributes(array("id" => $fkColaborador, "fk_empresa" => $fkEmpresa))->id;
        $criteria = new CDbCriteria;
        $criteria->select = 'nome, (SUM(duracao))*3600 as duracao, MONTH(data) as data';
        $criteria->addCondition("fk_empresa=".$fkEmpresa);
        $criteria->addCondition("fk_colaborador = $fkColaborador");
        $criteria->addCondition("YEAR(data) = $ano");
        $criteria->group = "MONTH(data)";
        $criteria->order = 'data ASC';
        return $this->findAll($criteria);
    }

    public function produtividadeCronDiario($data){
        $criteria = new CDbCriteria;
        $criteria->select = 'equipe, nome, sum(duracao) AS duracao, hora_total, fk_empresa, fk_colaborador';
        //$criteria->addCondition("fk_empresa=".$fk_empresa);
        $criteria->addCondition("data = '$data'");
        $criteria->addCondition("fk_empresa != '2'");
        $criteria->group = 'nome';
        $criteria->order = 'equipe ASC';
        return $this->findAll($criteria);
    }

    public function graficoProdutividade($inicio, $fim, $fk_empresa) {
        $criteria = new CDbCriteria;
        $criteria->select = 'sum(duracao) AS duracao, equipe, hora_total, nome, fk_colaborador';
        $criteria->addCondition("fk_empresa=".$fk_empresa, 'AND');
        $criteria->addBetweenCondition('data', $inicio, $fim, 'AND');
        $criteria->group = 'fk_colaborador';
        $criteria->order = 'duracao ASC';
        return $this->findAll($criteria);
    }

    public function graficoProdutividadeByEquipe($inicio, $fim, $fk_empresa, $equipe) {
        $criteria = new CDbCriteria;
        $criteria->select = 'sum(duracao) AS duracao, equipe, hora_total, fk_colaborador, p.fk_equipe as fk_equipe';
        $criteria->join = 'INNER JOIN colaborador as p ON p.id = t.fk_colaborador';
        $criteria->addCondition("t.fk_empresa=".$fk_empresa, 'AND');
        $criteria->addBetweenCondition('data', $inicio, $fim, 'AND');
        if($equipe != '' && !empty($equipe)){
            $criteria->addCondition("p.fk_equipe =  $equipe ");
        }
        $criteria->addCondition("p.status = 1");
        $criteria->group = 'fk_colaborador';
        $criteria->order = 'duracao ASC';
        return $this->findAll($criteria);
    }

    public function ProdutividadeByEquipe($inicio, $fim, $fk_empresa, $equipe)
    {
        $criteria = new CDbCriteria;
        $criteria->select = 'FORMAT(sum(duracao),2) AS duracao, equipe, sum(hora_total) as hora_total, FORMAT((FORMAT(sum(duracao),2)*100) /sum(hora_total),2) as produtividade,
        p.fk_equipe as fk_equipe';
        $criteria->join = 'INNER JOIN colaborador as p ON p.id = t.fk_colaborador';
        $criteria->addCondition("t.fk_empresa=" . $fk_empresa, 'AND');
        $criteria->addBetweenCondition('data', $inicio, $fim, 'AND');
        if ($equipe != '' && !empty($equipe)) {
            $criteria->addCondition("p.fk_equipe =  $equipe ");
        }
        $criteria->addCondition("p.status = 1");
        $criteria->group = 'p.fk_equipe';
        $criteria->order = 'duracao ASC';
        return $this->findAll($criteria);
    }


    public function graficoProdutividadeCustoByEquipe($inicio, $fim, $fk_empresa, $equipe) {
        $criteria = new CDbCriteria;
        $criteria->select = 'sum(duracao) AS duracao, equipe, hora_total, fk_colaborador, p.fk_equipe as fk_equipe';
        $criteria->join = 'INNER JOIN colaborador as p ON p.id = t.fk_colaborador';
        $criteria->addCondition("t.fk_empresa=" . $fk_empresa);
        $criteria->addBetweenCondition('data', $inicio, $fim);
        if($equipe != 'todas_equipes'){
            $criteria->addCondition("p.fk_equipe =  $equipe ");
        }
        $criteria->group = 'p.fk_equipe';
        $criteria->order = 'duracao ASC';
        return $this->findAll($criteria);
    }

    public function graficoProdutividadeByColaborador($inicio, $fim, $fk_empresa, $colaborador) {
        $criteria = new CDbCriteria;
        $criteria->select = 'sum(duracao) AS duracao, equipe, hora_total, nome, fk_colaborador';
        $criteria->addCondition("fk_empresa=".$fk_empresa);
        $criteria->addBetweenCondition('data', $inicio, $fim);
        if($colaborador != '' && $colaborador != 'todos_colaboradores' ){
            $criteria->addCondition("fk_colaborador=".$colaborador);
        }
        $criteria->group = 'nome';
        $criteria->order = 'duracao ASC';
        return $this->findAll($criteria);
    }

    public function ranking($inicio, $fim, $fk_empresa, $qnt = 0) {
        $arrayRanking = array();
        $resultEquipe = GrfProdutividadeConsolidado::model()->graficoProdutividadeByEquipe($inicio, $fim, $fk_empresa, "");
        if (!empty($resultEquipe)) {
            $i = 0;
            foreach ($resultEquipe as $value) {
                $obj = GrfProdutividadeConsolidado::model()->getQuantidadeDiasTrabalhadosPorColaborador($value->fk_colaborador, $inicio, $fim);
                $colaboradorDuracao = $value->duracao;
                $porcentagemCol = round(($colaboradorDuracao * 100) / ($value->hora_total * $obj->dias_trabalhados), 2);
                $colaborador = Colaborador::model()->findByPk($value->fk_colaborador);
                $tempoOciosoColaborador = LogAtividadeConsolidado::model()->getTempoOciosoByColaborador($fk_empresa, $colaborador->ad, $inicio, $fim)->duracao;
                $porcentagemColOcioso = round((($tempoOciosoColaborador / 3600) * 100) / ($value->hora_total * $obj->dias_trabalhados), 2);
                array_push($arrayRanking, array(
                    'produtividade' => $porcentagemCol,
                    'ocioso' => $porcentagemColOcioso,
                    'equipe' => $colaborador->equipes->nome,
                    'nome' => $colaborador->nomeCompleto,
                    'meta' => $colaborador->equipes->meta . '%',
                    'coeficiente' => round($porcentagemCol / $colaborador->equipes->meta, 2),
                    'id' => $i++,
                ));
            }
        }
        arsort($arrayRanking);
        if ($qnt > 0 && !empty($arrayRanking)) {
            $arrayRanking = array_chunk($arrayRanking, 10);
            $arrayRanking = $arrayRanking[0];
        }
        $arrayDataProvider = new CArrayDataProvider($arrayRanking, array(
            'id' => 'id',
            'pagination' => array(
                'pageSize' => 200,
            ),
        ));
        return $arrayDataProvider;
    }

    public function relatorioRanking($inicio, $fim, $fk_empresa, $qnt = 0) {
        $criteria = new CDbCriteria;
        $dias_uteis = MetodosGerais::dias_uteis(strtotime($inicio), strtotime($fim));
        $criteria->select = "SUM((t.duracao))*100 AS duracao, "
            . "((t.hora_total))*$dias_uteis AS hora_total, "
            . "(SUM((t.duracao))*100)/((t.hora_total)*$dias_uteis) AS produtividade,"
            . "equipe, t.nome, fk_colaborador, pe.fk_equipe as fk_equipe, e.meta as meta, ((SUM((t.duracao))*100)/((t.hora_total)*$dias_uteis)/e.meta) as coeficiente";
        $criteria->join = "JOIN colaborador as pe ON pe.id = t.fk_colaborador";
        $criteria->join .= " JOIN equipe as e ON pe.fk_equipe = e.id";
        $criteria->condition = 't.fk_empresa=:fk_empresa';
        $criteria->params = array(':fk_empresa'=>$fk_empresa);
        $criteria->addBetweenCondition('data', $inicio, $fim);
        $criteria->group = 'pe.id';
        $criteria->order = 'coeficiente DESC';
        if($qnt > 0){
            $criteria->limit = $qnt;
        }

        return $this->findAll($criteria);
    }

    public function getProdutividadeColaborador($inicio, $fim, $fkColaborador)
    {
        $criteria = new CDbCriteria;
        $dias_uteis = MetodosGerais::dias_uteis(strtotime(MetodosGerais::dataAmericana($inicio)), strtotime(MetodosGerais::dataAmericana($fim)));
        $criteria->select = "SUM((t.duracao)) AS duracao, "
            . "((t.hora_total))*$dias_uteis AS hora_total, "
            . "equipe, t.nome, fk_colaborador";
        $criteria->addCondition('fk_colaborador =' . $fkColaborador);
        $criteria->addBetweenCondition('data', MetodosGerais::dataAmericana($inicio), MetodosGerais::dataAmericana($fim));
        return $this->find($criteria);
    }

    public static function formatarProdutividade($valor){
        $valor = round($valor, 2);
        $retorno = str_replace('.', ',', $valor);
        return $retorno . '%';
    }

    public function getMediaProdEquipeMes($fkEquipe, $mes, $ano)
    {
        $criteria = new CDbCriteria();
        $criteria->select = '(sum(t.duracao))/count(1) as duracao,  t.data';
        $criteria->join = 'inner join colaborador as c on t.fk_colaborador = c.id';
        $criteria->addCondition('c.fk_equipe = ' . $fkEquipe);
        $criteria->addCondition('t.fk_empresa = ' . MetodosGerais::getEmpresaId());
        $criteria->addCondition("t.data like '$ano-$mes%' ");
        $criteria->group = 't.data';
        return $this->findAll($criteria);
    }

    public function getMediaProdEquipeAno($fkEquipe, $ano)
    {
        $criteria = new CDbCriteria();
        $criteria->select = '(sum(t.duracao))/count(distinct(t.fk_colaborador)) as duracao,  MONTH(t.data) as data';
        $criteria->join = 'inner join colaborador as c on t.fk_colaborador = c.id';
        $criteria->addCondition('c.fk_equipe = ' . $fkEquipe);
        $criteria->addCondition('t.fk_empresa = ' . MetodosGerais::getEmpresaId());
        $criteria->addCondition("t.data like '$ano%' ");
        $criteria->group = 'MONTH(t.data)';
        return $this->findAll($criteria);
    }


    public function getCustoByEquipe($inicio, $fim, $fk_empresa, $equipe)
    {
        $criteria = new CDbCriteria;
        $criteria->select = 'sum(duracao) AS duracao, c.fk_equipe as equipe, hora_total, fk_colaborador';
        $criteria->join = 'INNER JOIN colaborador as c ON  t.fk_colaborador = c.id ';
        $criteria->addCondition("t.fk_empresa=" . $fk_empresa);
        $criteria->addCondition("c.fK_equipe IS NOT NULL");
        $criteria->addBetweenCondition('data', $inicio, $fim);
        if ($equipe != 'todas_equipes') {
            $nome_equipe = Equipe::model()->findByPk($equipe)->nome;
            $criteria->addCondition("equipe='" . $nome_equipe . "'");
        }
        $criteria->group = 'c.fk_equipe';
        $criteria->order = 'duracao ASC';
        return $this->findAll($criteria);
    }

    public function getSomaProdutividadeByData($inicio, $fim, $fk_empresa)
    {
        $criteria = new CDbCriteria();
        $criteria->select = '(SUM(t.duracao))  AS duracao';
        $criteria->addCondition("t.fk_empresa=" . $fk_empresa);
        $criteria->addBetweenCondition('data', $inicio, $fim);
        return $this->find($criteria);
    }

    public function getQuantidadeDiasTrabalhadosPorColaborador($fk_colaborador, $inicio, $fim)
    {
        $criteria = new CDbCriteria();
        $criteria->select = 'count(1) as dias_trabalhados';
        $criteria->addCondition('fk_colaborador = ' . $fk_colaborador);
        $criteria->addBetweenCondition('data', $inicio, $fim);
        return $this->find($criteria);
    }
}
