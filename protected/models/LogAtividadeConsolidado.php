<?php

/**
 * This is the model class for table "log_atividade_consolidado".
 *
 * The followings are the available columns in table 'log_atividade_consolidado':
 * @property integer $id
 * @property string $usuario
 * @property string $programa
 * @property string $descricao
 * @property string $duracao
 * @property string $data
 * @property string $title_completo
 * @property string $nome_host
 * @property string $host_domain
 * @property string $serial_empresa
 * @property integer $num_logs
 * @property integer $fk_empresa
 */
class LogAtividadeConsolidado extends CActiveRecord
{

    public $total;
    public $obra;
    public $tempo_absoluto;
    public $tempoMedio;
    public $site;
    public $fk_colaborador;
    public $nome;
    public $sobrenome;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'log_atividade_consolidado';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('serial_empresa', 'required'),
            array('num_logs', 'numerical', 'integerOnly' => true),
            array('usuario, programa, descricao', 'length', 'max' => 255),
            array('title_completo', 'length', 'max' => 1024),
            array('nome_host', 'length', 'max' => 45),
            array('host_domain', 'length', 'max' => 64),
            array('serial_empresa', 'length', 'max' => 36),
            array('duracao, data,fk_empresa', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, usuario, programa, descricao, duracao, data, title_completo, nome_host, host_domain, serial_empresa, num_logs,fk_empresa', 'safe', 'on' => 'search'),
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
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'usuario' => Yii::t("smith", 'Colaborador'),
            'programa' => Yii::t("smith", 'Programa'),
            'descricao' => Yii::t("smith", 'Descrição'),
            'duracao' => Yii::t("smith", 'Duração'),
            'data' => Yii::t("smith", 'Data'),
            'title_completo' => Yii::t("smith", 'Title Completo'),
            'nome_host' => Yii::t("smith", 'Nome Host'),
            'host_domain' => Yii::t("smith", 'Host Domain'),
            'serial_empresa' => Yii::t("smith", 'Serial Empresa'),
            'num_logs' => Yii::t("smith", 'Num Logs'),
            'fk_empresa' => Yii::t("smith", 'Empresa'),
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

        $criteria->compare('id', $this->id);
        $criteria->compare('usuario', $this->usuario, true);
        $criteria->compare('programa', $this->programa, true);
        $criteria->compare('descricao', $this->descricao, true);
        $criteria->compare('duracao', $this->duracao, true);
        $criteria->compare('data', $this->data, true);
        $criteria->compare('title_completo', $this->title_completo, true);
        $criteria->compare('nome_host', $this->nome_host, true);
        $criteria->compare('host_domain', $this->host_domain, true);
        $criteria->compare('serial_empresa', $this->serial_empresa, true);
        $criteria->compare('num_logs', $this->num_logs);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function searchTentativas()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('usuario', $this->usuario, true);
        $criteria->compare('programa', $this->programa, true);
        $criteria->compare('descricao', $this->descricao, true);
        $criteria->compare('duracao', $this->duracao, true);
        $criteria->compare('data', $this->data, true);
        $criteria->compare('title_completo', $this->title_completo, true);
        $criteria->compare('nome_host', $this->nome_host, true);
        $criteria->compare('host_domain', $this->host_domain, true);
        $criteria->compare('serial_empresa', $this->serial_empresa, true);
        $criteria->compare('fk_empresa', $this->fk_empresa, true);
        $criteria->compare('num_logs', $this->num_logs);
        $criteria->addCondition('descricao like "Desinstalar smt" OR descricao like "Desinstalar smith" ');

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array('defaultOrder' => 'data DESC'),
            'pagination' => array(
                'pageSize' => Yii::app()->user->getState('pageSize', Yii::app()->params['defaultPageSize']),
            ),
        ));
    }

    public function searchOcioso2Horas($fk_empresa)
    {
        $criteria = new CDbCriteria;
        $criteria->select = "p.nome as nome, p.sobrenome as sobrenome, t.data, t.usuario, p.id as fk_colaborador, p.nome, t.id";
        $criteria->join = 'INNER JOIN colaborador as p ON p.fk_empresa = t.fk_empresa AND p.ad = t.usuario';
        $criteria->addCondition('t.fk_empresa = ' . $fk_empresa);
        $criteria->addCondition("descricao = 'Ocioso'");
        $criteria->addCondition("duracao > '02:00:00'");
        $criteria->addCondition("p.status = 1");
        $criteria->addCondition("MONTH(t.data) = MONTH(NOW())");
        $criteria->order = 'data DESC';
        $criteria->limit = '100';
        // $criteria->group = 't.usuario';
        return $this->findAll($criteria);
    }

    public function getCriterioDinamico($programa, $criterio, $sufixo, $site)
    {
        $criteria = new CDbCriteria;
        $criteria->select = "descricao";
        $criteria->addCondition("fk_empresa =".MetodosGerais::getEmpresaId());
        if ($site)
            $criteria->addCondition("descricao like '%$programa%'");
        else
            $criteria->addCondition("TRIM(programa) like '$programa'");
        ($sufixo == 1) ? $criteria->addCondition("descricao like '%$criterio%'") : $criteria->addCondition("descricao like '$criterio'");
        $criteria->order = 'duracao DESC';
        $criteria->limit = 10;
        return $this->findAll($criteria);
    }

    public function getPreVisualizacao($programa, $criterio, $sufixo, $site, $qtd)
    {
        $criteria = new CDbCriteria;
        $criteria->select = "descricao, CONCAT(p.nome,' ',p.sobrenome) as usuario, duracao, data";
        $criteria->join = 'INNER JOIN colaborador as p ON p.fk_empresa = t.fk_empresa AND p.ad = t.usuario';
        $criteria->addCondition("p.fk_empresa =" . MetodosGerais::getEmpresaId());
        if ($site)
            $criteria->addCondition("descricao like '%$programa%'");
        else
            $criteria->addCondition("TRIM(programa) like '$programa'");
        if ($criterio != '')
            ($sufixo == 1) ? $criteria->addCondition("descricao like '%$criterio%'") : $criteria->addCondition("descricao like '$criterio'");
        $criteria->order = 'duracao DESC';
        $criteria->limit = 20 + $qtd;
        return $this->findAll($criteria);
    }

    public function getTotalGridPreVisualizar($programa, $criterio, $sufixo, $site)
    {
        $criteria = new CDbCriteria;
        $criteria->select = "count(1) as total";
        $criteria->join = 'INNER JOIN colaborador as p ON p.fk_empresa = t.fk_empresa AND p.ad = t.usuario';
        $criteria->addCondition("p.fk_empresa =" . MetodosGerais::getEmpresaId());
        if ($site)
            $criteria->addCondition("descricao like '%$programa%'");
        else
            $criteria->addCondition("TRIM(programa) like '$programa'");

        ($sufixo == 1) ? $criteria->compare("descricao", $criterio, true) : $criteria->compare("descricao", $criterio, false);
        return $this->find($criteria);
    }

    public function getGridUpdateForm($programa, $criterio, $sufixo,$fk_empresa) {
        $criteria = new CDbCriteria;
        $criteria->select = "descricao, usuario, duracao, data";
        $criteria->compare("fk_empresa",$fk_empresa);
        $criteria->compare("TRIM(programa)", $programa);
        ($sufixo == 1) ? $criteria->compare("descricao" ,$criterio,true) : $criteria->compare("descricao" , $criterio);
        $criteria->order = 'duracao DESC';
        $criteria->limit = 20;
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,

        ));
    }

    public function getLogsMetricas($programa, $criterio, $sufixo, $data_inicio, $data_fim, $colaborador = '', $site)
    {
        $serial = (MetodosGerais::getEmpresaId()==41)? 'C75M-423X-983B-7E3I' : LogAtividade::model()->getSerial();
        $data_inicio = MetodosGerais::dataAmericana($data_inicio);
        $data_fim = MetodosGerais::dataAmericana($data_fim);
        $criteria = new CDbCriteria;
        $criteria->select = "usuario, data,descricao , SEC_TO_TIME(SUM(TIME_TO_SEC(duracao))) as duracao";
        $criteria->addCondition("serial_empresa like '$serial'");
        if ($site)
            $criteria->addCondition("descricao like '%$programa%'");
        else
            $criteria->addCondition("TRIM(programa) like '$programa'");
        ($sufixo == 1) ? $criteria->compare("descricao", $criterio, true) : $criteria->compare("descricao", $criterio, false);
        $criteria->addBetweenCondition("data", $data_inicio, $data_fim);
        if ($colaborador != 'todos_colaboradores')
            $criteria->addCondition("usuario like '$colaborador'");
        $criteria->group = "descricao";
        $criteria->order = 'data DESC, usuario ASC, descricao ASC, duracao DESC';
        return $this->findAll($criteria);
    }

    public function getLogsMetricasEquipe($programa, $criterio, $sufixo, $data_inicio, $data_fim, $equipe = '', $site)
    {
        $serial = (MetodosGerais::getEmpresaId()==41)? 'C75M-423X-983B-7E3I' : LogAtividade::model()->getSerial();
        $data_inicio = MetodosGerais::dataAmericana($data_inicio);
        $data_fim = MetodosGerais::dataAmericana($data_fim);
        $criteria = new CDbCriteria;
        $criteria->select = "usuario, data,descricao , SEC_TO_TIME(SUM(TIME_TO_SEC(duracao))) as duracao";
        $criteria->join = " INNER JOIN colaborador as p ON p.ad = usuario  ";
        $criteria->addCondition("t.serial_empresa like '$serial'");
        if ($site)
            $criteria->addCondition("descricao like '%$programa%'");
        else
            $criteria->addCondition("TRIM(programa) like '$programa'");
        ($sufixo == 1) ? $criteria->compare("descricao", $criterio, true) : $criteria->compare("descricao", $criterio, false);
        $criteria->addBetweenCondition("data", $data_inicio, $data_fim);
        if ($equipe != 'todas_equipes')
            $criteria->addCondition("p.fk_equipe = $equipe");
        $criteria->group = "descricao";
        $criteria->order = 'data DESC, usuario ASC, descricao ASC, duracao DESC';
        return $this->findAll($criteria);
    }

    public function getCronColaboradorMetrica($fkEmpresa,$data,$programa,$criterio){
        $criteria = new CDbCriteria;
        $criteria->select = 'usuario,p.id';
        $criteria->join = 'INNER JOIN colaborador AS p ON p.ad = t.usuario AND p.fk_empresa = t.fk_empresa';
        $criteria->addCondition("fk_empresa = $fkEmpresa");
        $criteria->addCondition("data = '$data'");
        $criteria->addCondition("programa = '$programa' ");
        $criteria->addCondition("descricao like '%$criterio%' ");
        $criteria->addCondition("usuario not in (select p.ad from colaborador as p inner join colaborador_has_metrica as c ON c.colaborador_id = p.id
                                where p.fk_empresa = $fkEmpresa) ");
        $criteria->group = 'usuario';
        return $this->findAll($criteria);
    }

    public function getConsolidaMetrica($obj, $data)
    {
        $criteria = new CDbCriteria();
        $criteria->select = 'usuario,count(*) as  num_logs,SEC_TO_TIME(SUM(duracao)) AS duracao,SEC_TO_TIME(SUM(duracao) / count(*)) AS tempoMedio';
        if ($obj->criterio == '' && $obj->programa != '') {
            $criteria->compare('descricao', $obj->programa, true);
        } else {
            $criteria->compare('programa', $obj->programa);
            ($obj->sufixo == 1) ? $criteria->compare('descricao', $obj->criterio, true) : $criteria->compare('descricao', $obj->criterio, false);
        }
        $criteria->compare('serial_empresa', $obj->serial_empresa);
        $criteria->compare('data', $data);

        $criteria->group = 'usuario';
        $criteria->order = 'duracao DESC';
        return $this->findAll($criteria);
    }

    public function getTempoOciosoByColaborador($fkEmpresa, $colaborador, $dataInicio, $dataFim)
    {
        $criteria = new CDbCriteria();
        $criteria->select = 'coalesce(sum(time_to_sec(duracao)),0) as duracao';
        $criteria->addCondition('fk_empresa =' . $fkEmpresa);
        $criteria->addBetweenCondition("data", $dataInicio, $dataFim);
        $criteria->addCondition('usuario = "' . $colaborador . '"');
        $criteria->addCondition("descricao like 'Ocioso' ");
        return $this->find($criteria);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return LogAtividadeConsolidado the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
}
