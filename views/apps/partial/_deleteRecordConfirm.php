<?php

use yii\helpers\Url;

?>
<div class="modal fade" id="modal-delete-record" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= (isset($pesan)) ? $pesan : Yii::t('app', 'Apakah anda yakin akan menghapus ini?'); ?></h4>
            </div>
            <div class="modal-footer">
                <?= yii\helpers\Html::button(Yii::t('app', 'Ok'),['class'=>'btn red btn-outline ciptana-spin-btn','onclick'=>"deleteRecord()"]); ?>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php // $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-ui/jquery-ui.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>
<script>
function deleteRecord(){
	$.ajax({
		url    : '<?= Url::toRoute([(isset($actionname)?$actionname:'delete'),'id'=>$id]); ?>',
		type   : 'POST',
		data   : {deleteRecord:true},
		success: function (data) {
			$('#modal-delete-record').modal('hide');
			if(data.status){
				if(data.message){
                    cisAlert(data.message);
				}
				<?php if(isset($tableid)){ ?> 
					$('#<?= $tableid ?>').dataTable().fnClearTable(); 
				<?php } ?>
				if(data.callback){
					eval(data.callback);
				}else{
					<?php if(!isset($actionname)){ ?>
						$(".modals-place").children('.modal').hide();
						$(".modals-place").children('.modal').remove();
						$(".modals-place-2").children('.modal').hide();
						$(".modals-place-2").children('.modal').remove();
						$('.modal-backdrop').remove();
					<?php } ?>
				}
			}else{
				if(data.message){
                    if(data.message.errorInfo){
                        cisAlert(data.message.errorInfo[2]);
                    }else{
                        cisAlert(data.message);
                    }
				}
			}
			$('#modal-delete-record').find('.progress-success .bar').animate({'width':'0%'});
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
		progress: function(e) {
			if(e.lengthComputable) {
				var pct = (e.loaded / e.total) * 100;
				$('#modal-delete-record').find('.progress-success .bar').animate({'width':pct.toPrecision(3)+'%'});
			}else{
				console.warn('Content Length not reported!');
			}
		}
	});
}
</script>