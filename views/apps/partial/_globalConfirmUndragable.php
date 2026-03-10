<div class="modal fade" id="modal-global-confirm" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" id="close-btn-globalconfirm" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= (isset($pesan))? $pesan : '-'; ?></h4>
            </div>
            <div class="modal-footer text-align-center">
                <?= yii\helpers\Html::button(Yii::t('app', 'Yes'),['class'=>'btn hijau btn-outline ciptana-spin-btn','onclick'=>"yes()"]); ?>
                <?= yii\helpers\Html::button(Yii::t('app', 'No'),['class'=>'btn red btn-outline ciptana-spin-btn','data-dismiss'=>'modal']); ?>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<script>
function yes(){
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute([(isset($actionname)?$actionname:'-'),'id'=>$id]); ?>',
		type   : 'POST',
		data   : {updaterecord:true},
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