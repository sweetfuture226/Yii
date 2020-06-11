<?php

/**
 * This is the model class for table "colaborador_has_equipe".
 *
 * The followings are the available columns in table 'colaborador_has_equipe':
 * @property integer $id
 * @property integer $fk_colaborador
 * @property integer $fk_empresa
 * @property integer $fk_equipe
 * @property integer $data_inicio
 */
class ColaboradorHasEquipe extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'colaborador_has_equipe';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('fk_colaborador, fk_empresa, fk_equipe', 'required'),
            array('fk_colaborador, fk_empresa, fk_equipe', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, fk_colaborador, fk_empresa, fk_equipe, data_inicio', 'safe', 'on' => 'search'),
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
            'equipes' => array(self::BELONGS_TO, 'Equipe', 'fk_equipe'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'fk_colaborador' => 'Fk Colaborador',
            'fk_empresa' => 'Fk Empresa',
            'fk_equipe' => 'Fk Equipe',
            'data_inicio' => 'Data Inicio',
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

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('fk_colaborador', $fk_colaborador);
        $criteria->compare('fk_empresa', $fk_empresa);
        $criteria->compare('fk_equipe', $this->fk_equipe);
        $criteria->compare('data_inicio', $this->data_inicio);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function saveRelation($fkColaborador, $fkEquipe)
    {
        $colaboradorHasEquipe = new ColaboradorHasEquipe();
        $colaboradorHasEquipe->fk_colaborador = $fkColaborador;
        $colaboradorHasEquipe->fk_equipe = $fkEquipe;
        $colaboradorHasEquipe->fk_empresa = MetodosGerais::getEmpresaId();
        $colaboradorHasEquipe->data_inicio = date('Y-m-d');
        $colaboradorHasEquipe->save();
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ColaboradorHasEquipe the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
