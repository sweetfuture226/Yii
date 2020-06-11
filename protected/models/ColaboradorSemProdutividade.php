<?php

/**
 * This is the model class for table "colaborador_sem_produtividade".
 *
 * The followings are the available columns in table 'colaborador_sem_produtividade':
 * @property integer $id
 * @property string $nome
 * @property string $data
 * @property integer $fk_empresa
 */
class ColaboradorSemProdutividade extends CActiveRecord
{
    public $equipe, $total, $data_inicial, $data_final;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ColaboradorSemProdutividade the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'colaborador_sem_produtividade';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('fk_empresa', 'required'),
			array('fk_empresa', 'numerical', 'integerOnly' => true),
			array('nome', 'length', 'max'=>255),
			array('data', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, nome, data, fk_empresa', 'safe', 'on' => 'search'),
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
			'nome' => Yii::t("smith",'Nome'),
			'data' => Yii::t("smith",'Data'),
			'fk_empresa' => Yii::t("smith", 'Empresa'),
            'equipe'=>Yii::t("smith",'Equipe'),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.
            
        !empty($this->data) ? $data = date('Y-m-d', CDateTimeParser::parse($this->data, 'dd/MM/yyyy')) : $data = '';
		$this->fk_empresa = MetodosGerais::getEmpresaId();
		(!empty($_GET['ColaboradorSemProdutividade']['equipe']))?$this->equipe = $_GET['ColaboradorSemProdutividade']['equipe']: '';
		$criteria=new CDbCriteria;
        $criteria->select = "t.nome, t.data, eq.nome as equipe";
		$criteria->join = "INNER JOIN colaborador as p ON t.fk_colaborador = p.id ";
		$criteria->join .= "INNER JOIN equipe as eq ON eq.id = p.fk_equipe";
		$criteria->compare('t.id',$this->id);
		$criteria->compare('t.nome',$this->nome,true);
		$criteria->compare('t.data',$data,true);
		$criteria->compare('eq.nome',$this->equipe,true);
		$criteria->addCondition("p.fk_empresa = $this->fk_empresa");
		$criteria->addCondition("t.fk_empresa = $this->fk_empresa");
        $criteria->addCondition("WEEKDAY(t.data) !=5");
        $criteria->addCondition("WEEKDAY(t.data) !=6");
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            'sort'=>array('defaultOrder'=> 'data DESC'),
			'pagination' => array(
			   'pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize']),
			),
		));
	}

    public function getTotalColSemProd($fkEmpresa,$data){
        $criteria = new CDbCriteria();
        $criteria->select = 'count(*) as total';
		$criteria->addCondition('fk_empresa=' . $fkEmpresa);
        $criteria->addCondition("data='$data'");
        $criteria->addCondition('date_format(data,"%W") != "Saturday"');
        $criteria->addCondition('date_format(data,"%W") != "Sunday"');
        return $this->find($criteria);
    }

    public function getTotalColSemProdFromColaborador($fkEmpresa, $fk_colaborador)
    {
        $criteria = new CDbCriteria();
        $criteria->select = 't.id as id, p.nome as nome, p.sobrenome as sobrenome, t.data';
        $criteria->join = "INNER JOIN colaborador as p ON t.fk_colaborador = p.id ";
        $criteria->addCondition('p.id =' . $fk_colaborador);
        $criteria->addCondition('t.justificativa is null');
        $criteria->addCondition('t.fk_empresa =' . $fkEmpresa);
        $criteria->addCondition("WEEKDAY(t.data) !=5");
        $criteria->addCondition("WEEKDAY(t.data) !=6");
        return $this->findAll($criteria);
    }

	public function getTotalColSemProdByDatas($fkEmpresa, $dataInicio, $dataFim)
	{
		$criteria = new CDbCriteria();
		$criteria->select = 'count(*) as total';
		$criteria->join = "INNER JOIN colaborador as p ON t.fk_colaborador = p.id ";
		$criteria->join .= "INNER JOIN equipe as eq ON eq.id = p.fk_equipe";
		$criteria->addCondition('p.fk_empresa =' . $fkEmpresa);
		$criteria->addCondition('t.fk_empresa =' . $fkEmpresa);
		$criteria->addBetweenCondition('data', $dataInicio, $dataFim);
		$criteria->addCondition("WEEKDAY(t.data) !=5");
		$criteria->addCondition("WEEKDAY(t.data) !=6");
		return $this->find($criteria);
	}
}
