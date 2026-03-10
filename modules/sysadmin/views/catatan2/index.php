<?php
$this->title = 'Catatan';

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
        'toggleDataOptions' => ['minCount' => 100],
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
            'heading' => Html::a('<i class="fa fa-home"></i> Home', ['index']).' &raquo; <i class="fa fa-edit"></i> Catatan2',
        ],
        'exportConfig' => [
            GridView::EXCEL =>  [
                'filename' => 'Catatan',
                'showPageSummary' => false,
            ],
            GridView::PDF =>  [
                'filename' => 'Catatan',
                'showPageSummary' => false,
            ],

        ],
        'toolbar' => [
            [
                'content'=>
                    Html::a('<i class="glyphicon glyphicon-plus"></i> Tambah Catatan',
                        ['create'],
                        [
                            'title'=>'Add', 
                            'class'=>'btn btn-default',
                        ]
                    ),
            ],
            '{export}',
            [
                'content'=>
                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', [''], ['data-pjax'=>1, 'class'=>'btn btn-default', 'title'=>'Refresh']),
            ],
        ],
        'options' => ['style' => 'font-size: 10px;'],
            'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'headerOptions' => ['style' => 'width:30px'],
            ],
            [
                'attribute' => 'tanggal',
                'headerOptions' => ['style' => 'width:85px'],
                'value'     => function ($model)
                {
                    if ($model->tanggal == 0) {
                        return "00-00-0000";
                    } else {
                        return Yii::$app->formatter->asDate($model->tanggal, 'php:d-m-Y');;
                    }
                }
            ],
            'judul',
            [
                'attribute' => 'keterangan',
                'headerOptions' => ['style' => ''],
                'value' => 'keterangan',
                /* 'value' => function ($model) {
                    return Html::a($model->catatan_gambar, ['/sysadmin/catatan2/image', 'id' => $model->catatan_id], ['id' => 'image-modal-link']);
                }, */
            ], 
            /*[
                'attribute' => 'catatan_gambar',
                'headerOptions' => ['style' => 'width:60px'],
                'format' => 'html',    
                'value' => function ($data) {
                    return Html::img(Yii::getAlias('@web').'/uploads/catatan/'. $data['catatan_gambar'], ['width'=>'50px', 'class'=>'text-center', 'alt'=>'-', 'style'=>'text-align-center']);
                },
                /* 'value' => function ($model) {
                    return Html::a($model->catatan_gambar, ['/sysadmin/catatan2/image', 'id' => $model->catatan_id], ['id' => 'image-modal-link']);
                }, */
            /*],*/            
            /*[
                'attribute' => 'user_id',
                'headerOptions' => ['style' => 'width:100px'],
                'value' => 'user.username',
            ],*/
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Actions',
                'headerOptions' => ['style' => 'color:#337ab7;', 'width' => '30px'],
                'template' => '&nbsp;{view}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::button('<span><i class="fa fa-eye"></i></span>', [ 'class' => 'btn btn-default', 'onclick' => 'js:{ info("'.$model->catatan_id.'"); return false;}' ]);
                    },
                ],
            ],
        ],


    ]); ?>
</div>

<?php $this->registerJs("
    $('.dropdown-toggle').click(function(){
        $('.dropdown-menu-default').toggle();
    })
", yii\web\View::POS_READY); 
?>

<script>
function info(id){
    openModal('<?= \yii\helpers\Url::toRoute(['/sysadmin/catatan2/info','id'=>'']) ?>'+id,'modal-catatan-info');
}
</script>