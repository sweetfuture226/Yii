<?php 
$this->widget('zii.widgets.grid.CGridView', array(
                        'id'=>'reuniao-participantes-grid',
                        'summaryText' => '',
                        'dataProvider'=>$dataProvider,
                        'pager' => array('cssFile' => Yii::app()->theme->baseUrl . '/css/gridView.css'),
                        'cssFile' => Yii::app()->theme->baseUrl . '/css/gridView.css',
                        'htmlOptions' => array('class' => 'grid-view rounded table-responsive'),
                        'afterAjaxUpdate' => 'afterAjax',
                        'columns'=>array(
                            'criterio',
                            ),
                    ));
                        
 ?>