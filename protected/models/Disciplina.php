<?php

/**
 * This is the model class for table "disciplina".
 *
 * The followings are the available columns in table 'disciplina':
 * @property integer $id
 * @property string $codigo
 * @property string $nome
 * @property integer $fk_empresa
 */
class Disciplina extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Disciplina the static model class
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
		return 'disciplina';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('codigo', 'required'),
			array('codigo', 'length', 'max'=>64),
			array('nome', 'length', 'max'=>256),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, codigo, nome', 'safe', 'on'=>'search'),
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
			'codigo' => Yii::t("smith",'Código'),
			'nome' => Yii::t("smith",'Nome'),
			'fk_empresa' => Yii::t("smith", 'ID Empresa'),
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
        $user = UserGroupsUser::model()->findByPk(Yii::app()->user->id);
		if (isset($user->fk_empresa))
			$fk_empresa = $user->fk_empresa;

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('codigo',$this->codigo,true);
		$criteria->compare('nome',$this->nome,true);

        if($criteria->condition  == null){
           $criteria->condition  = " fk_empresa=".$fk_empresa;
        }else {
            $criteria->condition  .= " AND fk_empresa=".$fk_empresa;
        }

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                        'pagination' => array(
                            'pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize']),
                        ),
		));
	}

	public function has_relations()
	{
        foreach ($this->relations() as $relation => $attributes) {
            if ($this->getRelated($relation))
                return true;
        }
        return false;
    }


	public function getDisciplinasByContrato($fkContrato)
	{
		$criteria = new CDbCriteria();
		$criteria->select = 't.codigo,t.nome';
		$criteria->join = 'inner join documento as doc on doc.fk_disciplina = t.id';
		$criteria->addCondition('doc.fk_contrato = ' . $fkContrato);
		$criteria->group = 'doc.fk_disciplina';
		return $this->findAll($criteria);
	}
}
