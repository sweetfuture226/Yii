<?php

/**
 * This is the model class for table "lista_negra_programa".
 *
 * The followings are the available columns in table 'lista_negra_programa':
 * @property integer $id
 * @property string $programa
 * @property double $porcentagem
 * @property integer $fk_empresa
 * @property double $tempo_absoluto 
 *
 * The followings are the available model relations:
 * @property Empresa $fkEmpresa
 */
class ListaNegraPrograma extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'lista_negra_programa';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('programa, fk_empresa', 'required'),
            array('fk_empresa', 'numerical', 'integerOnly'=>true),
            array('porcentagem', 'numerical'),
            array('programa', 'length', 'max'=>255),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, programa, porcentagem, fk_empresa,tempo_absoluto', 'safe', 'on'=>'search'),
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
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'programa' => Yii::t("smith", 'Programa'),
            'porcentagem' => Yii::t("smith", 'Porcentagem'),
            'tempo_absoluto'=> Yii::t("smith", 'Tempo absoluto em horas'),
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
        $criteria=new CDbCriteria;
        $criteria->compare('id',$this->id);
        $criteria->compare('programa',$this->programa,true);
        $criteria->compare('porcentagem',$this->porcentagem);
        $criteria->compare('fk_empresa',$this->fk_empresa);
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Retorna uma lista de models filtrados por empresa
     * @return CActiveDataProvider
     */
    public function searchBlackList()
    {
        $fkEmpresa = MetodosGerais::getEmpresaId();
        $criteria = new CDbCriteria;
        $criteria->addCondition("fk_empresa = $fkEmpresa");
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'sort' => array('defaultOrder' => 'tempo_absoluto DESC'),
            'pagination' => array(
                'pageSize' => Yii::app()->user->getState('pageSize', Yii::app()->params['defaultPageSize']),
            ),
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ListaNegraPrograma the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    /**
     * Deletar um programa não permitido pelo nome
     */
    public function deleteByNome($nome, $fkEmpresa)
    {
        $model = $this->findByAttributes(array('programa' => $nome, 'fk_empresa' => $fkEmpresa));
        $this->deleteByPk($model->id);
    }
}
