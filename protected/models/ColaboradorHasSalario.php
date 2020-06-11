<?php

/**
 * This is the model class for table "colaborador_has_salario".
 *
 * The followings are the available columns in table 'colaborador_has_salario':
 * @property integer $id
 * @property double $valor
 * @property string $data_inicio
 * @property integer $fk_colaborador
 * @property integer $fk_empresa
 *
 * The followings are the available model relations:
 * @property Colaborador $fkColaborador
 * @property Empresa $fkEmpresa
 */
class ColaboradorHasSalario extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'colaborador_has_salario';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('valor, data_inicio, fk_colaborador, fk_empresa', 'required'),
			array('fk_colaborador, fk_empresa', 'numerical', 'integerOnly'=>true),
			array('valor', 'numerical'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, valor, data_inicio, fk_colaborador, fk_empresa', 'safe', 'on'=>'search'),
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
			'fkColaborador' => array(self::BELONGS_TO, 'Colaborador', 'fk_colaborador'),
			'fkEmpresa' => array(self::BELONGS_TO, 'Empresa', 'fk_empresa'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'valor' => 'Valor',
			'data_inicio' => 'Data Inicio',
			'fk_colaborador' => 'Fk Colaborador',
			'fk_empresa' => 'Fk Empresa',
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
    public function search($fk_empresa, $fk_colaborador)
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('valor',$this->valor);
		$criteria->compare('data_inicio',$this->data_inicio,true);
        $criteria->compare('fk_colaborador', $fk_colaborador);
        $criteria->compare('fk_empresa', $fk_empresa);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ColaboradorHasSalario the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
