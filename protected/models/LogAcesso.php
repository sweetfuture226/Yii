<?php

/**
 * This is the model class for table "log_acesso".
 *
 * The followings are the available columns in table 'log_acesso':
 * @property integer $id
 * @property string $modulo
 * @property string $acao
 * @property string $titulo
 * @property string $data_horario
 * @property double tempo_resposta
 * @property integer $fk_usuario
 * @property integer $fk_empresa
 */
class LogAcesso extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'log_acesso';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('acao, modulo,titulo,fk_usuario, fk_empresa', 'required'),
            array('fk_usuario, fk_empresa', 'numerical', 'integerOnly' => true),
            array('acao', 'length', 'max' => 255),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, acao, data_horario, fk_usuario, fk_empresa', 'safe', 'on' => 'search'),
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
            'modulo' => Yii::t('smith', 'Módulo'),
            'titulo' => Yii::t('smith', 'Título'),
            'acao' => Yii::t('smith', 'Ação'),
            'data_horario' => Yii::t('smith', 'Data'),
            'tempo_resposta' => Yii::t('smith', 'Tempo de resposta'),
            'fk_usuario' => Yii::t('smith', 'Usuário'),
            'fk_empresa' => Yii::t('smith', 'Cliente')
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

        $criteria->compare('id', $this->id);
        $criteria->compare('acao', $this->acao, true);
        $criteria->compare('modulo', $this->modulo, true);
        $criteria->compare('titulo', $this->titulo, true);
        $criteria->compare('data_horario', $this->data_horario, true);
        $criteria->compare('tempo_resposta', $this->tempo_resposta, true);
        $criteria->compare('fk_usuario', $this->fk_usuario);
        $criteria->compare('fk_empresa', $this->fk_empresa);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'data_horario DESC',
            ),
            'pagination' => array(
                'pageSize' => Yii::app()->user->getState('pageSize', 20),
            ),
        ));
    }

    public function searchIndex()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $this->tempo_resposta = (isset($_GET['LogAcesso']['tempo_resposta'])) ? $_GET['LogAcesso']['tempo_resposta'] : '';
        $criteria->compare('id', $this->id);
        $criteria->compare('acao', $this->acao, true);
        $criteria->compare('modulo', $this->modulo, true);
        $criteria->compare('titulo', $this->titulo, true);
        $criteria->compare('data_horario', $this->data_horario, true);
        // $criteria->compare('tempo_resposta', $this->tempo_resposta, true);
        $criteria->compare('fk_usuario', $this->fk_usuario);
        $criteria->compare('fk_empresa', $this->fk_empresa);
        $criteria->addCondition($this->filtroTempoResposta($this->tempo_resposta));

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'data_horario DESC',
            ),
            'pagination' => array(
                'pageSize' => Yii::app()->user->getState('pageSize', 20),
            ),
        ));
    }

    private function filtroTempoResposta($resposta)
    {
        switch ($resposta) {
            case '0':
                $criteria = 'tempo_resposta <= 0.001';
                break;
            case '1':
                $criteria = 'tempo_resposta BETWEEN 0.001 AND 0.01';
                break;
            case '2':
                $criteria = 'tempo_resposta BETWEEN 0.01 AND 0.1';
                break;
            case '3':
                $criteria = 'tempo_resposta BETWEEN 0.1 AND 1';
                break;
            case '4':
                $criteria = 'tempo_resposta BETWEEN 1 AND 10';
                break;
            case '5':
                $criteria = 'tempo_resposta >= 10';
                break;
            default:
                $criteria = 'tempo_resposta >= "0"';
                break;
        }
        return $criteria;
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return LogAcesso the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function saveAcesso($modulo, $acao, $titulo, $tempoResposta)
    {
        $model = new LogAcesso();
        $model->modulo = $modulo;
        $model->acao = $acao;
        $model->titulo = $titulo;
        $model->tempo_resposta = $tempoResposta;
        $model->fk_usuario = Yii::app()->user->id;
        $model->fk_empresa = MetodosGerais::getEmpresaId();
        $model->save();
    }
}
