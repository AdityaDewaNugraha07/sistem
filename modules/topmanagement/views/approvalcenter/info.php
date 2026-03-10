<div class="modal fade" id="modal-master-info" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Agreement Summary ').'<b>'.\app\components\DeltaGlobalClass::getBerkasNamaByBerkasKode($model->reff_no).'</b>'; ?></h4>
            </div>
			<div id="showdetails" id="showdetails">
                <?php
                $model = \app\models\TApproval::findOne($model->approval_id);
                switch (substr($model->reff_no,0,3)){
                    case "SPB":
                        $modReff = \app\models\TSpb::findOne(['spb_kode'=>$model->reff_no]);
                        $modDetail = \app\models\TSpbDetail::find()->where(['spb_id'=>$modReff->spb_id])->all();
                        echo $this->render('showDetailsSpb', ['model'=>$model,'modReff'=>$modReff,'modDetail'=>$modDetail]);
                        break;
                    case "SPO":
                        $modReff = \app\models\TSpo::findOne(['spo_kode'=>$model->reff_no]);
                        $modDetail = \app\models\TSpoDetail::find()->andWhere("spod_keterangan NOT ILIKE '%INJECT PENYESUAIAN TRANSAKSI%'")->where(['spo_id'=>$modReff->spo_id])->all();
                        $modSpo = \app\models\TSpo::findOne(['spo_kode'=>$model->reff_no]);
                        $nominallevels = \app\models\MApprovalNominallevel::find()->where(['active'=>true])->all();
                        $currentAccesibleActor = \app\models\MApprovalNominallevel::findOne(['pegawai_id'=>Yii::$app->user->identity->pegawai_id]);
                        $accessible_level = false;
                        if( Yii::$app->user->identity->user_group_id != \app\components\Params::USER_GROUP_ID_SUPER_USER ){
                            if(!empty($currentAccesibleActor)){
                                $totalspo = $modReff->spo_total;
                                if($currentAccesibleActor->pegawai_id == $model->assigned_to){
                                    $accessible_level = true;
                                }
                            }
                        }else{
                            $accessible_level = true;
                        }
                        echo $this->render('showDetailsSpo', ['model'=>$model,'modReff'=>$modReff,'modDetail'=>$modDetail,'accessible_level'=>$accessible_level,'modSpo'=>$modSpo]);
                        break;
                }
                ?>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php // $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-ui/jquery-ui.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>
<?php // $this->registerJs(" showdetails('".$model->approval_id."'); ", yii\web\View::POS_READY); ?>
<script>
function showdetails(approval_id){
	$('#showdetails').addClass("animation-loading");
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/topmanagement/approvalcenter/showDetails']); ?>',
		type   : 'POST',
		data   : {approval_id:approval_id},
		success: function (data) {
			if(data.html){
				$('#showdetails').html(data.html);
			}else{
				$('#showdetails').html("");
			}
			$('#showdetails').removeClass("animation-loading");
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}
function approve(id){
	openModal('<?= \yii\helpers\Url::toRoute(['/topmanagement/approvalcenter/approveConfirm','id'=>'']); ?>'+id,'modal-global-confirm');
}
function reject(id){
	openModal('<?= \yii\helpers\Url::toRoute(['/topmanagement/approvalcenter/rejectConfirm','id'=>'']); ?>'+id,'modal-global-confirm');
}

function confirmSPB(id,type){
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/topmanagement/approvalcenter/confirmSPB']); ?>',
		type   : 'POST',
		data   : {approval_id:<?= $model->approval_id ?>},
		success: function (data) {
			if(data){
				if(type == 'approve'){
					approve(id);
				}else if(type == 'reject'){
					reject(id);
				}
			}else{
				openModal('<?= \yii\helpers\Url::toRoute(['/topmanagement/approvalcenter/notAllowedSPB','id'=>'']); ?>'+id,'modal-global-info');
			}
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}
</script>