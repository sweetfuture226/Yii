<?php

/**
 * This is the model class for table "view_metrica_consolidada".
 *
 * The followings are the available columns in table 'view_metrica_consolidada':
 * @property integer $MC_id
 * @property string $MC_data
 * @property string $MC_total
 * @property string $MC_media
 * @property integer $MC_entradas
 * @property integer $M_id
 * @property string $M_titulo
 * @property string $M_atuacao
 * @property string $M_descricao
 * @property string $M_programa
 * @property string $M_criterio
 * @property string $M_sufixo
 * @property integer $M_meta
 * @property integer $M_max
 * @property integer $M_min
 * @property string $M_tempo
 * @property integer $M_fk_empresa
 * @property string $M_serial_empresa
 */
class ViewMetricaConsolidada extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'view_metrica_consolidada';
    }

    public function getResultadoMetricaPorDia($metrica_id = NULL) {
        $fk_empresa = 17; //MetodosGerais::getEmpresaId();
        $metrica_id = 3;
        return $this->findAll('M_fk_empresa = :fk_empresa AND M_id = :metrica_id ORDER BY MC_data ASC', 
                array(':fk_empresa' => $fk_empresa, ':metrica_id' => $metrica_id));
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array('MC_data, MC_total, MC_media, MC_entradas, M_titulo, M_atuacao, M_programa, M_criterio, M_fk_empresa, M_serial_empresa', 'required'),
            array('MC_id, MC_entradas, M_id, M_meta, M_max, M_min, M_fk_empresa', 'numerical', 'integerOnly' => true),
            array('M_titulo, M_atuacao, M_descricao, M_programa, M_criterio, M_sufixo', 'length', 'max' => 255),
            array('M_serial_empresa', 'length', 'max' => 36),
            array('M_tempo', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('MC_id, MC_data, MC_total, MC_media, MC_entradas, M_id, M_titulo, M_atuacao, M_descricao, M_programa, M_criterio, M_sufixo, M_meta, M_tempo, M_max, M_min, M_fk_empresa, M_serial_empresa', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'MC_id' => 'Mc',
            'MC_data' => 'Mc Data',
            'MC_total' => 'Mc Total',
            'MC_media' => 'Mc Media',
            'MC_entradas' => 'Mc Entradas',
            'M_id' => 'M',
            'M_titulo' => 'M Titulo',
            'M_atuacao' => 'M Atuacao',
            'M_descricao' => 'M Descricao',
            'M_programa' => 'M Programa',
            'M_criterio' => 'M Criterio',
            'M_sufixo' => 'M Sufixo',
            'M_meta' => 'M Meta',
            'M_max' => 'M Max',
            'M_min' => 'M Min',
            'M_tempo' => 'M Tempo',
            'M_fk_empresa' => 'M Fk Empresa',
            'M_serial_empresa' => 'M Serial Empresa',
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
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('MC_id', $this->MC_id);
        $criteria->compare('MC_data', $this->MC_data, true);
        $criteria->compare('MC_total', $this->MC_total, true);
        $criteria->compare('MC_media', $this->MC_media, true);
        $criteria->compare('MC_entradas', $this->MC_entradas);
        $criteria->compare('M_id', $this->M_id);
        $criteria->compare('M_titulo', $this->M_titulo, true);
        $criteria->compare('M_atuacao', $this->M_atuacao, true);
        $criteria->compare('M_descricao', $this->M_descricao, true);
        $criteria->compare('M_programa', $this->M_programa, true);
        $criteria->compare('M_criterio', $this->M_criterio, true);
        $criteria->compare('M_sufixo', $this->M_sufixo, true);
        $criteria->compare('M_meta', $this->M_meta);
        $criteria->compare('M_max', $this->M_max);
        $criteria->compare('M_min', $this->M_min);
        $criteria->compare('M_tempo', $this->M_tempo, true);
        $criteria->compare('M_fk_empresa', $this->M_fk_empresa);
        $criteria->compare('M_serial_empresa', $this->M_serial_empresa, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ViewMetricaConsolidada the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
