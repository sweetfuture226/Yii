<?php

/**
 * This is the model class for table "atividade_externa".
 *
 * The followings are the available columns in table 'atividade_externa':
 * @property integer $id
 * @property string $usuario
 * @property string $programa
 * @property string $descricao
 * @property string $duracao
 * @property string $data
 * @property string $data_hora_servidor
 * @property string $title_completo
 * @property string $hora_host
 * @property string $nome_host
 * @property string $host_domain
 * @property string $serial_empresa
 */
class AtividadeExterna extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public $obra, $hora_saida;

	public function tableName()
	{
		return 'atividade_externa';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('usuario,descricao,data,hora_host,hora_saida', 'required'),
			array('programa, descricao', 'length', 'max'=>255),
			array('title_completo', 'length', 'max'=>1024),
			array('nome_host', 'length', 'max'=>45),
			array('host_domain', 'length', 'max'=>64),
			array('serial_empresa', 'length', 'max'=>36),
			array('duracao, data', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, usuario, programa, descricao, duracao, data, data_hora_servidor, title_completo, hora_host, nome_host, host_domain, serial_empresa', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
            'usuario' => Yii::t("smith", 'Colaborador (um ou mais)'),
			'programa' => Yii::t("smith", 'Programa'),
			'descricao' => Yii::t("smith", 'Descrição da atividade'),
			'duracao' => Yii::t("smith", 'Duração'),
			'data' => Yii::t("smith", 'Data'),
			'data_hora_servidor' => Yii::t("smith", 'Horário de chegada'),
			'title_completo' => Yii::t("smith", 'Title Completo'),
			'hora_host' => Yii::t("smith", 'Horário de chegada'),
			'nome_host' => Yii::t("smith", 'Nome Host'),
			'host_domain' => Yii::t("smith", 'Host Domain'),
			'serial_empresa' => 'Serial Empresa',
			'hora_saida' => Yii::t("smith", 'Horário de saída'),
			'obra' => Yii::t("smith", 'Contrato')
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
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('usuario',$this->usuario,true);
		$criteria->compare('programa',$this->programa,true);
		$criteria->compare('descricao',$this->descricao,true);
		$criteria->compare('duracao',$this->duracao,true);
		$criteria->compare('data',$this->data,true);
		$criteria->compare('data_hora_servidor',$this->data_hora_servidor,true);
		$criteria->compare('title_completo',$this->title_completo,true);
		$criteria->compare('hora_host',$this->hora_host,true);
		$criteria->compare('nome_host',$this->nome_host,true);
		$criteria->compare('host_domain',$this->host_domain,true);
		$criteria->compare('serial_empresa',$this->serial_empresa,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

        /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function searchExtra() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        !empty($this->data) ? $data = date('Y-m-d', CDateTimeParser::parse($this->data, 'dd/MM/yyyy')) : $data = '';
        !empty($this->duracao) ? $duracao = explode("AND", $this->duracao) : $duracao = '';
        !empty($this->data_hora_servidor) ? $hora_servidor = explode("AND", $this->data_hora_servidor) : $hora_servidor = '';


        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('usuario', $this->usuario, true);
        $criteria->compare('programa', $this->programa, true);
        $criteria->compare('descricao', $this->descricao, true);
        $criteria->compare('data', $data, true);
        $criteria->compare('title_completo', $this->title_completo, true);
        $criteria->compare('hora_host', $this->hora_host, true);

        !empty($this->duracao) ? $criteria->condition = "duracao BETWEEN '$duracao[0]' AND '$duracao[1]'" : $this->duracao = '';
        $today = date('Y-m-d');
        !empty($this->data_hora_servidor) ? $criteria->condition = "data_hora_servidor BETWEEN '$today $hora_servidor[0]' AND '$today $hora_servidor[1]'" : $this->data_hora_servidor = '';


        $serial = MetodosGerais::getSerial();
        $equipe = MetodosGerais::getEquipe();

		$criteria->addCondition("t.serial_empresa like '$serial'");
        $criteria->addCondition("t.atividade_extra = 1");

		if (Yii::app()->user->groupName == 'coordenador') {
			$criteria->join = 'INNER JOIN colaborador as p ON p.ad = t.usuario';
            $criteria->addCondition("p.fk_equipe = $equipe");
        }


		return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array('defaultOrder' => 'data DESC'),
            'pagination' => array(
                'pageSize' => Yii::app()->user->getState('pageSize', Yii::app()->params['defaultPageSize']),
            ),
        ));
    }

	public function getTempoAtividadeExterna($dataInicio, $dataFim, $opcao, $tipo)
	{
        $dataInicio = MetodosGerais::dataAmericana($dataInicio);
        $dataFim = MetodosGerais::dataAmericana($dataFim);
        $serialEmpresa = MetodosGerais::getSerial();
        $criteria = new CDbCriteria;
		$criteria->select = 'programa, sum(TIME_TO_SEC(duracao)) as duracao,t.descricao';
		$criteria->join = 'INNER JOIN colaborador as pe ON pe.ad = t.usuario';
		$criteria->join .= ' INNER JOIN equipe as eq ON pe.fk_equipe = eq.id';
        $criteria->addBetweenCondition('data', $dataInicio, $dataFim);
        $criteria->addCondition("t.serial_empresa = '$serialEmpresa'");
        if ($opcao != 'todos_colaboradores' && $opcao != 'todas_equipes' && $tipo == 'equipe')
            $criteria->addCondition("eq.id = {$opcao}");
        if ($opcao != 'todos_colaboradores' && $opcao != 'todas_equipes' && $tipo == 'colaborador')
            $criteria->addCondition("pe.id = {$opcao}");
		$criteria->group = 't.descricao';
		$criteria->order = 'duracao desc';
        return $this->findAll($criteria);
    }

    /**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AtividadeExterna the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
