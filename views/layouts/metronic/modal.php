<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;

\app\assets\MetronicAsset::register($this);
?>

<?php $this->beginPage() ?>
<?php $this->beginBody() ?>
<div class="modal fade" id="modal-info-nota" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Detail Informasi'); ?></h4>
            </div>
            <div class="modal-body">
                 <?php echo $content; ?>
            </div>
<!--			<div class="modal-footer" style="text-align: center;">
				<?php // echo \yii\helpers\Html::button( Yii::t('app', 'Print'),['id'=>'btn-print','class'=>'btn blue btn-outline ciptana-spin-btn','onclick'=>'printout('.$model->nota_penjualan_id.')']); ?>
			</div>-->
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php // $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-ui/jquery-ui.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>\
<?php $this->endBody() ?>
<script>
//function printout(id){
//	var caraPrint = "PRINT";
//	window.open("<?php // echo yii\helpers\Url::toRoute(['/marketing/notapenjualan/printNota','id'=>'']) ?>"+id+"&caraprint="+caraPrint,"",'location=_new, width=1200px, scrollbars=yes');
//}
</script>

<?php $this->endPage() ?>