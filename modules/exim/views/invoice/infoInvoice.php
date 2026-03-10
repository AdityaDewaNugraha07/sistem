<style>
table{
	font-size: 1.2rem;
}
table#table-detail{
	font-size: 1.1rem;
}
table#table-detail tr td{
	vertical-align: top;
}
</style>
<div class="modal fade" id="modal-print" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Invoice Export'); ?></h4>
            </div>
            <div class="modal-body">
                <?= $this->render('@app/modules/exim/views/invoice/'.$viewPrint,['model'=>$model,'modDetails'=>$modDetails,'modOpEx'=>$modOpEx,'modPackinglist'=>$modPackinglist,'modContainer'=>$modContainer,'paramprint'=>$paramprint]) ?>
            </div>
			<div class="modal-footer" style="text-align: center;">
				<?php echo \yii\helpers\Html::button( Yii::t('app', 'Print'),['id'=>'btn-print','class'=>'btn blue btn-outline ciptana-spin-btn','onclick'=>'printout()']); ?>
			</div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php // $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-ui/jquery-ui.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>\

<script>
function printout(id){
	var caraPrint = "PRINT";
	window.open("<?= yii\helpers\Url::toRoute(['/marketing/notapenjualan/printNota','id'=>'']) ?>"+id+"&caraprint="+caraPrint,"",'location=_new, width=1200px, scrollbars=yes');
}
</script>