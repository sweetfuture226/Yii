<?php

/**
 * This is the model class for table "grf_projeto_consolidado".
 *
 * The followings are the available columns in table 'grf_projeto_consolidado':
 * @property string $id
 * @property string $documento
 * @property double $duracao
 * @property string $data
 * @property integer $fk_empresa
 * @property integer $fk_colaborador
 * @property integer $fk_obra
 * @property integer $associado
 *
 * The followings are the available model relations:
 * @property Empresa $fkEmpresa
 * @property Colaborador $fkColaborador
 * @property Contrato $fkObra
 */
class GrfProjetoConsolidado extends CActiveRecord
{

    public $colaborador, $equipe, $salario, $obra, $nome, $mes, $ano, $codigo, $tempo_previsto, $valor_previsto;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'grf_projeto_consolidado';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('documento, duracao, data, fk_empresa, fk_colaborador, fk_obra', 'required'),
            array('fk_empresa, fk_colaborador, fk_obra', 'numerical', 'integerOnly' => true),
            array('duracao', 'numerical'),
            array('documento', 'length', 'max' => 255),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, documento, duracao, data, fk_empresa, fk_colaborador, fk_obra', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'fkEmpresa' => array(self::BELONGS_TO, 'Empresa', 'fk_empresa'),
            'fkColaborador' => array(self::BELONGS_TO, 'Colaborador', 'fk_colaborador'),
            'fkObra' => array(self::BELONGS_TO, 'Contrato', 'fk_obra'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'documento' => Yii::t("smith", 'Documento'),
            'duracao' => Yii::t("smith", 'Duração'),
            'data' => Yii::t("smith", 'Data'),
            'fk_empresa' => Yii::t('smith', 'Empresa'),
            'fk_colaborador' => Yii::t('smith', 'Colaborador'),
            'fk_obra' => Yii::t('smith', 'Contrato'),
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
    public function search()
    {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id, true);
        $criteria->compare('documento', $this->documento, true);
        $criteria->compare('duracao', $this->duracao);
        $criteria->compare('data', $this->data, true);
        $criteria->compare('fk_empresa', $this->fk_empresa);
        $criteria->compare('fk_colaborador', $this->fk_colaborador);
        $criteria->compare('fk_obra', $this->fk_obra);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function getProdutivideContratosCron($dataInicio, $dataFim)
    {

        $criteria = new CDbCriteria;
        $criteria->select = "sum(duracao) as duracao,fk_colaborador,fk_empresa,fk_obra, contrato.nome,CONCAT(colaborador.nome,colaborador.sobrenome) as colaborador , equipe.nome as equipe, FORMAT(colaborador.salario/((TIME_TO_SEC(colaborador.horas_semana)/3600)*4),2) as salario";
        $criteria->join = 'join contrato on contrato.id = fk_obra
                           join colaborador on colaborador.id = fk_colaborador
                           join equipe on equipe.id = colaborador.fk_equipe';
        $criteria->addBetweenCondition('data', $dataInicio, $dataFim);
        $criteria->group = 'fk_colaborador';
        $criteria->having = 'colaborador IS NOT NULL';
        $criteria->order = 'fk_empresa,fk_obra';
        return $this->findAll($criteria);
    }

    public function getTempoTotalContrato($idContrato, $dataInicio, $dataFim)
    {
        $empresa = MetodosGerais::getEmpresaId();
        $dataInicio = MetodosGerais::dataAmericana($dataInicio);
        $dataFim = MetodosGerais::dataAmericana($dataFim);
        $criteria = new CDbCriteria;
        $criteria->select = "fk_obra, MONTH(data) as mes , year(data) as ano , SUM(time_to_sec(duracao)) as duracao";
        $criteria->addCondition("fk_empresa = $empresa");
        $criteria->addBetweenCondition("data", $dataInicio, $dataFim);
        if ($idContrato != "")
            $criteria->addCondition("fk_obra = $idContrato");
        $criteria->group = 'mes , fk_obra';
        $criteria->order = "ano,mes";

        return $this->findAll($criteria);
    }

    public function getProdutividadeProjetosByAtt($opcao, $selecionado, $dataInicio, $dataFim)
    {
        $empresa = MetodosGerais::getEmpresaId();
        $criteria = new CDbCriteria;
        $criteria->select = "sum(duracao) as duracao, CONCAT(p.nome,' ',p.sobrenome) as colaborador,"
            . " p.salario/((TIME_TO_SEC(p.horas_semana)/3600)*4) as salario,"
            . " o.nome as obra, o.codigo as codigo, o.tempo_previsto as tempo_previsto,"
            . " o.valor as valor_previsto, fk_obra, fk_colaborador";
        $criteria->join = "INNER JOIN colaborador as p ON p.id = fk_colaborador ";
        $criteria->join .= "INNER JOIN contrato as o ON o.id = fk_obra";
        $criteria->addBetweenCondition('data', $dataInicio, $dataFim);
        $criteria->addCondition("t.fk_empresa = $empresa");

        if ($opcao != 'todos_colaboradores' && $opcao != 'todas_equipes' && $opcao != 'todos_contratos' && $selecionado == 'equipe') {
            $criteria->join .= " INNER JOIN equipe as e ON e.id = p.fk_equipe ";
            $criteria->addCondition("e.id = $opcao");
        }

        if ($opcao != 'todos_colaboradores' && $opcao != 'todas_equipes' && $opcao != 'todos_contratos' && $selecionado == 'colaborador')
            $criteria->addCondition("fk_colaborador = $opcao");

        if ($opcao != 'todos_colaboradores' && $opcao != 'todas_equipes' && $opcao != 'todos_contratos' && $selecionado == 'contrato')
            $criteria->addCondition("fk_obra = $opcao");

        if ($opcao == "contrato") {
            $criteria->group = "fk_colaborador";
        } else {
            $criteria->group = "fk_obra,fk_colaborador";
        }

        $criteria->order = "p.nome, o.nome ASC";
        return $this->findAll($criteria);
    }

    public function getProdutividadeColaboradorPorContrato($idColaborador, $dataInicial, $dataFinal)
    {
        $dataInicial = MetodosGerais::dataAmericana($dataInicial);
        $dataFinal = MetodosGerais::dataAmericana($dataFinal);
        $empresa = MetodosGerais::getEmpresaId();
        $criteria = new CDbCriteria;
        $criteria->select = "sum(duracao) as duracao,fk_colaborador,fk_obra";
        $criteria->addCondition("fk_obra in (select c.id from contrato as c where c.fk_empresa = $empresa)");
        $criteria->addBetweenCondition('data', $dataInicial, $dataFinal);
        $criteria->addCondition("fk_empresa = $empresa");
        $criteria->addCondition("fk_colaborador = $idColaborador");
        $criteria->group = "fk_obra";
        $criteria->order = "duracao DESC";
        return $this->findAll($criteria);
    }

    public function getProdutividadeContratoPorColaborador($finalizado, $obra, $dataInicial, $dataFinal, $fkColaborador = '')
    {
        $empresa = MetodosGerais::getEmpresaId();
        $criteria = new CDbCriteria;
        $criteria->select = "CONCAT(p.nome,' ',p.sobrenome) as colaborador, t.documento,sum(t.duracao) as duracao,t.fk_colaborador,p.fk_equipe as equipe, (p.salario)/((time_to_sec(p.horas_semana)/3600)*4) as salario";
        $criteria->join = 'INNER JOIN colaborador as p ON p.id = t.fk_colaborador';
        $criteria->addBetweenCondition('data', $dataInicial, $dataFinal);
        $criteria->addCondition("t.fk_empresa = $empresa");
        $criteria->addCondition("t.fk_obra = $obra->id");
        if ($fkColaborador != '')
            $criteria->addCondition("t.fk_colaborador = $fkColaborador");
        if ($obra->finalizada && $obra->data_finalizacao != NULL && !$finalizado)
            $criteria->addCondition('data < "' . $obra->data_finalizacao . '"');
        $criteria->group = "fk_colaborador";
        $criteria->order = "duracao DESC";
        return $this->findAll($criteria);
    }

    public function getLogsContrato($fkContrato, $padrao)
    {
        $idEmpresa = MetodosGerais::getEmpresaId();
        $criteria = new CDbCriteria;
        $criteria->select = 't.documento,t.duracao,t.data,t.fk_colaborador';
        $criteria->addCondition("documento like '$padrao'");
        $criteria->addCondition('fk_obra = ' . $fkContrato);
        $criteria->addCondition('fk_empresa = ' . $idEmpresa);
        return $this->findAll($criteria);
    }

    public function getDocumentosRelatorio($finalizado, $contrato, $data_inicio, $data_fim, $idEmpresa)
    {
        $criteria = new CDbCriteria;
        $criteria->select = 'sum(duracao) AS duracao, documento, data, fk_empresa, fk_colaborador, fk_obra';
        $criteria->addBetweenCondition('data', $data_inicio, $data_fim);
        $criteria->addCondition("fk_obra = $contrato->id");
        $criteria->addCondition('fk_empresa = ' . $idEmpresa);
        if ($contrato->finalizada && $contrato->data_finalizacao != NULL && !$finalizado)
            $criteria->addCondition('data < "' . $contrato->data_finalizacao . '"');
        $criteria->order = 'documento ASC, duracao ASC';
        $criteria->group = 'documento';
        return GrfProjetoConsolidado::model()->findAll($criteria);
    }

    public function getDocumentosLogAcompanhamento($idContrato, $idEmpresa)
    {
        $criteria = new CDbCriteria;
        $criteria->select = 'sum(duracao) AS duracao, documento, data, fk_empresa, fk_colaborador, fk_obra';
        $criteria->addCondition("fk_obra = $idContrato");
        $criteria->addCondition('fk_empresa = ' . $idEmpresa);
        $criteria->order = 'data, duracao ASC';
        $criteria->group = 'fk_colaborador,data';
        return $this->findAll($criteria);
    }

    public function findProjetosByPrefixo($documento, $data_inicio, $data_fim, $idEmpresa)
    {
        $criteria = new CDbCriteria;
        $criteria->select = 'sum(duracao) as duracao, documento, data, fk_empresa, fk_colaborador, fk_obra';
        $criteria->addCondition('fk_empresa = ' . $idEmpresa);
        $criteria->addBetweenCondition('data', $data_inicio, $data_fim);
        $criteria->addCondition("TRIM(documento) LIKE TRIM('$documento')");
        $criteria->order = 'data, duracao ASC';
        return $this->findAll($criteria);
    }

    public function getDuracaoDocumentoContrato($documento, $idEmpresa)
    {
        $criteria = new CDbCriteria;
        $criteria->select = 'sum(duracao) as duracao, documento, data, fk_empresa, fk_colaborador, fk_obra';
        $criteria->addCondition('fk_empresa = ' . $idEmpresa);
        $criteria->addCondition("documento LIKE TRIM('$documento')");
        $criteria->order = 'data, duracao ASC';
        return $this->findAll($criteria);
    }


    public function getDataInicioByPrefixo($prefixo, $idEmpresa)
    {
        $criteria = new CDbCriteria;
        $criteria->select = 'data';
        $criteria->addSearchCondition("documento", $prefixo);
        $criteria->addCondition('fk_empresa = ' . $idEmpresa);
        $criteria->order = 'data ASC';
        return GrfProjetoConsolidado::model()->find($criteria);
    }

    public function getDuracaoProjeto($data)
    {
        $idEmpresa = MetodosGerais::getEmpresaId();
        $criteria = new CDbCriteria;
        $criteria->select = 'SUM(duracao) as duracao';
        $criteria->addCondition('fk_obra = ' . $data->id);
        $criteria->addCondition('fk_empresa = ' . $idEmpresa);
        if ($data->finalizada && $data->data_finalizacao != NULL) {
            $criteria->addCondition('data < :data_finalizacao');
            $criteria->params = array(':data_finalizacao' => $data->data_finalizacao);
        }
        $criteria->group = 'fk_obra';
        return $this->find($criteria);
    }

    public function getDuracaoTotalContrato($contrato)
    {
        $idEmpresa = MetodosGerais::getEmpresaId();
        $criteria = new CDbCriteria;
        $criteria->select = 'SUM(duracao) as duracao';
        $criteria->addCondition('fk_obra = ' . $contrato);
        $criteria->addCondition('fk_empresa = ' . $idEmpresa);
        return $this->find($criteria);
    }

    public function getDuracaoTotalContratoApi($contrato, $fk_empresa)
    {
        $idEmpresa = $fk_empresa;
        $criteria = new CDbCriteria;
        $criteria->select = 'SUM(duracao) as duracao';
        $criteria->addCondition('fk_obra = ' . $contrato);
        $criteria->addCondition('fk_empresa = ' . $idEmpresa);
        return $this->find($criteria);
    }

    public function getContratosTop10($dataInicio, $dataFim)
    {
        $idEmpresa = MetodosGerais::getEmpresaId();
        $criteria = new CDbCriteria;
        $criteria->select = 'c.nome,c.codigo,SUM(duracao) as duracao,fk_obra';
        $criteria->join = 'inner join contrato as c on c.id = fk_obra';
        $criteria->addCondition('t.fk_empresa = ' . $idEmpresa);
        $criteria->addBetweenCondition('data', $dataInicio, $dataFim);
        $criteria->group = 'fk_obra';
        $criteria->order = 'duracao DESC';
        $criteria->limit = '10';
        return $this->findAll($criteria);
    }

    public function getTempoColaboradoresContratos($id, $dataInicio, $dataFim)
    {
        $idEmpresa = MetodosGerais::getEmpresaId();
        $criteria = new CDbCriteria;
        $criteria->select = 'SUM(duracao) as duracao,fk_obra, fk_colaborador';
        $criteria->addCondition('fk_obra = ' . $id);
        $criteria->addCondition('fk_empresa = ' . $idEmpresa);
        $criteria->addBetweenCondition('data', $dataInicio, $dataFim);
        $criteria->group = 'fk_colaborador';
        return $this->findAll($criteria);
    }


    public function getTempoDocumento($documento)
    {
        $criteria = new CDbCriteria;
        $criteria->select = 'sum(duracao) as duracao';
        $criteria->addCondition("documento LIKE TRIM('$documento')");
        return $this->find($criteria);
    }

    public function searchDocumentoFinalizado()
    {
        $criteria = new CDbCriteria;
        $criteria->compare('t.documento', $this->documento, true);
        $criteria->compare('t.data', $this->data, true);
        $criteria->compare('t.fk_empresa', $this->fk_empresa);
        $criteria->compare('t.fk_colaborador', $this->fk_colaborador);
        $criteria->compare('t.fk_obra', $this->fk_obra);
        $criteria->join = 'inner join documento as dc on dc.nome  = t.documento';
        $criteria->addCondition('dc.finalizado = 1');
        $criteria->addCondition('t.data > dc.data_finalizacao');

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function getDuracaoTotalContratoByDatas($fk_contrato, $dataInicio, $dataFim)
    {

        $criteria = new CDbCriteria;
        $criteria->select = 'SUM(duracao) as duracao';
        $criteria->addCondition('fk_obra = ' . $fk_contrato);
        $criteria->addBetweenCondition('data', $dataInicio, $dataFim);
        return $this->find($criteria);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return GrfProjetoConsolidado the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

}
