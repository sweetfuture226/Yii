<?php

/**
 * This is the model class for table "metrica_consolidada".
 *
 * The followings are the available columns in table 'metrica_consolidada':
 * @property integer $id
 * @property string $data
 * @property string $total
 * @property string $media
 * @property integer $entradas
 * @property integer $fk_metrica
 * @property integer fk_colaborador
 * @property integer fk_empresa
 * @property Metrica metrica
 */
class MetricaConsolidada extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'metrica_consolidada';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('data, total, media, entradas, fk_metrica', 'required'),
			//array('id, entradas, fk_metrica', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			//array('id, data, total, media, entradas, fk_metrica', 'safe', 'on'=>'search'),
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
			'metrica' => array(self::BELONGS_TO, 'Metrica', 'fk_metrica'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'data' => Yii::t("smith", 'Data'),
			'total' => Yii::t("smith", 'Total'),
			'media' => Yii::t("smith", 'Média'),
			'entradas' => Yii::t("smith", 'Entradas'),
			'fk_metrica' => 'Fk Metrica',
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
		$criteria->compare('data',$this->data,true);
		$criteria->compare('total',$this->total,true);
		$criteria->compare('media',$this->media,true);
		$criteria->compare('entradas',$this->entradas);
		$criteria->compare('fk_metrica',$this->fk_metrica);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function getEntradasMetricas($fkMetrica)
    {
		$dataInicio = date("Y-m-01");
		$dataFim = date("Y-m-d");
		$criteria = new CDbCriteria();
		$criteria->select = 'sum(entradas) as entradas';
		$criteria->addCondition("t.fk_empresa=".MetodosGerais::getEmpresaId());
		$criteria->addBetweenCondition("data", $dataInicio, $dataFim);
        $criteria->addCondition('fk_metrica='.$fkMetrica);
		return $this->find($criteria);
	}

	public function getSomaEntradasByDatas($fkEmpresa, $dataInicio, $dataFim)
	{
		$criteria = new CDbCriteria();
		$criteria->select = ' sum(entradas) as entradas , fk_metrica';
		$criteria->addCondition('t.fk_empresa =' . $fkEmpresa);
		$criteria->addBetweenCondition('data', $dataInicio, $dataFim);
		$criteria->group = 'fk_metrica';
		$criteria->with = 'metrica';
		return $this->findAll($criteria);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return MetricaConsolidada the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
