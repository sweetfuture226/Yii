<?php

/**
 * This is the model class for table "documento_sem_contrato".
 *
 * The followings are the available columns in table 'documento_sem_contrato':
 * @property integer $id
 * @property string $documento
 * @property string $fk_colaborador
 * @property double $duracao
 * @property string $data
 * @property integer $fk_empresa
 * @property string $programa
 */
class DocumentoSemContrato extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'documento_sem_contrato';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('documento, fk_colaborador, duracao, data, fk_empresa', 'required'),
			array('fk_empresa', 'numerical', 'integerOnly'=>true),
			array('duracao', 'numerical'),
			array('documento, fk_colaborador', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, documento, fk_colaborador, duracao, data, fk_empresa,programa', 'safe', 'on'=>'search'),
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
            'colaborador' => array(self::BELONGS_TO, 'Colaborador', 'fk_colaborador'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
            'programa'=>Yii::t("smith",'Programa'),
			'documento' => Yii::t("smith",'Documento'),
			'fk_colaborador' => Yii::t("smith",'Colaborador'),
			'duracao' => Yii::t("smith",'Duração'),
			'data' => Yii::t("smith",'Data'),
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
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.
        $idEmpresa = MetodosGerais::getEmpresaId();
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('documento',$this->documento,true);
		$criteria->compare('fk_colaborador',$this->fk_colaborador,true);
		$criteria->compare('duracao',$this->duracao);
		$criteria->compare('data',$this->data,true);
		$criteria->compare('fk_empresa',$this->fk_empresa);
        $criteria->compare('programa',$this->programa,true);
        $criteria->addCondition("fk_empresa = $idEmpresa");

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            'sort'=>array('defaultOrder'=> 'duracao DESC'),
            'pagination' => array(
                'pageSize' => Yii::app()->user->getState('pageSize', Yii::app()->params['defaultPageSize']),
            ),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DocumentosSemContrato the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
