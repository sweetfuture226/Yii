<?php

/**
 * This is the model class for table "colaborador_has_parametro".
 *
 * The followings are the available columns in table 'colaborador_has_parametro':
 * @property integer $fk_colaborador
 * @property integer $fk_empresa
 * @property string $horario_entrada
 * @property string $horario_saida
 */
class ColaboradorHasParametro extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'colaborador_has_parametro';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('fk_colaborador, fk_empresa, horario_entrada, horario_saida', 'required'),
            array('fk_colaborador, fk_empresa', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('fk_colaborador, fk_empresa, horario_entrada, horario_saida', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array();
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'fk_colaborador' => 'Fk Colaborador',
            'fk_empresa' => 'Fk Empresa',
            'horario_entrada' => Yii::t('smith', 'Horário de entrada'),
            'horario_saida' => Yii::t('smith', 'Horario de saída'),
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

        $criteria = new CDbCriteria;

        $criteria->compare('fk_colaborador', $this->fk_colaborador);
        $criteria->compare('fk_empresa', $this->fk_empresa);
        $criteria->compare('horario_entrada', $this->horario_entrada, true);
        $criteria->compare('horario_saida', $this->horario_saida, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ColaboradorHasParametro the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
