<?php
$this->title = 'Logger';

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\dialog\Dialog;
use yii\web\JsExpression;


/* @var $this yii\web\View */
/* @var $searchModel backend\models\KaryawanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//$this->title = 'Data';
//$this->params['breadcrumbs'][] = $this->title;
?>

<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', $this->title); ?></h1>
<!-- END PAGE TITLE-->

<?php
/*Yii::$app->mailer->compose()
     ->setFrom('it.ciptana@gmail.com')
     ->setTo('purwo.martono@gmail.com')
     ->setSubject('Email sent from Yii2-Swiftmailer')
     ->send();
*/
?>

<div class="raw">

    <?= 
    GridView::widget([
        'responsive'=>true, 
        'hover'=>true,
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'headerRowOptions' => ['class' => 'kartik-sheet-style'],
        'filterRowOptions' => ['class' => 'kartik-sheet-style'],
        'floatHeader'=>true,
        'floatHeaderOptions'=>['top'=>'50'],
        'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
        'persistResize' => false,
        //'toggleDataOptions' => ['minCount' => 100],
        'autoXlFormat'=>true,
        'toggleDataContainer' => ['class' => 'btn-group mr-2'],
            'export' => [
            'fontAwesome' => true
        ],
        'export'=>[
            'showConfirmAlert'=>false,
            'target'=>GridView::TARGET_BLANK
        ],
        'panel' => [
            'type' => GridView::TYPE_DEFAULT,
            'heading' => Html::a('<i class="fa fa-home"></i> Home', ['index']).' &raquo; <i class="fa fa-edit"></i> Logger',
        ],
        'exportConfig' => [
            GridView::EXCEL =>  [
                'filename' => 'Logger',
                'showPageSummary' => false,
            ],
            GridView::PDF =>  [
                'filename' => 'Logger',
                'showPageSummary' => false,
            ],

        ],
        'toolbar' => [
            '{export}',
            [
                'content'=>
                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', [''], ['data-pjax'=>1, 'class'=>'btn btn-default', 'title'=>'Refresh']),
            ],
        ],
        'options' => ['style' => 'font-size: 10px;'],
            'columns' => [
            //'id',
            //'level',
            //'category',
            [
                'attribute' => 'log_time',
                'headerOptions' => ['style' => 'width:150px'],
                'value'     => function ($model)
                {
                    if ($model->log_time == 0) {
                        return "00-00-0000";
                    } else {
                        return Yii::$app->formatter->asDate($model->log_time, 'php:d-m-Y H:i:s');;
                    }
                }
            ],
            'prefix',
            'message',
        ],


    ]); ?>
</div>

<?php $this->registerJs("
    $('.dropdown-toggle').click(function(){
        $('.dropdown-menu-default').toggle();
    })
", yii\web\View::POS_READY); 
?>

