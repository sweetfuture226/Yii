<?php

/**
 * This is the model class for table "colaborador_has_ferias".
 *
 * The followings are the available columns in table 'colaborador_has_ferias':
 * @property integer $id
 * @property string $data_inicio
 * @property string $data_fim
 * @property integer $fk_colaborador
 * @property integer $fk_empresa
 */
class ColaboradorHasFerias extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'colaborador_has_ferias';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('data_inicio, data_fim, fk_colaborador, fk_empresa', 'required'),
			array('fk_colaborador, fk_empresa', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, data_inicio, data_fim, fk_colaborador, fk_empresa, descricao', 'safe', 'on'=>'search'),
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
			'data_inicio' => Yii::t("smith", 'Início das férias'),
			'data_fim' => Yii::t("smith", 'Retorno das férias'),
			'fk_colaborador' => 'Fk Colaborador',
			'fk_empresa' => 'Fk Empresa',
			'descricao' => Yii::t("smith", 'Descrição'),
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
	public function search($colaborador)
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('data_inicio',$this->data_inicio,true);
		$criteria->compare('data_fim',$this->data_fim,true);
		$criteria->compare('fk_colaborador',$this->fk_colaborador);
		$criteria->compare('fk_empresa',$this->fk_empresa);
		$criteria->compare('descricao',$this->descricao);
        $criteria->addCondition("fk_empresa =".MetodosGerais::getEmpresaId());
        $criteria->addCondition("fk_colaborador =".$colaborador);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ColaboradorHasFerias the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
