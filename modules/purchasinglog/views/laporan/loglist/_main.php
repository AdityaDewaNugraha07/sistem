<div class="portlet-title">
    <div class="caption">
        <span class="caption-subject bold"><h4><?= Yii::t('app', 'Data Loglist'); ?></h4></span>
    </div>
    <div class="text-right">
    <a class="btn btn-danger" data-original-title="Close" onclick="closeDetail()"><i class="fa fa-times fa-2"></i></a>    
    </div>
</div>
<div class="row">
    <?php $form = \yii\bootstrap\ActiveForm::begin([
        'id' => 'form-transaksi',
        'fieldConfig' => [
            'template' => '{label}<div class="col-md-7">{input} {error}</div>',
            'labelOptions'=>['class'=>'col-md-4 control-label'],
        ],
    ]); echo Yii::$app->controller->renderPartial('@views/apps/partial/_flashAlert'); ?>
    <div class="col-md-6">
        <?= yii\helpers\Html::activeHiddenInput($model, "loglist_id"); ?>
        <?= $form->field($model, 'loglist_kode')->textInput(['style'=>'font-weight:bold','readonly'=>true]); ?>
        <?= $form->field($model, 'tanggal')->textInput(['style'=>'font-weight:bold','readonly'=>true, 'value'=>\app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal)]); ?>
        <?= $form->field($model, 'tongkang')->textInput(['readonly'=>true]); ?>
        
        <div class="form-group" style="margin-top: 10px;">
            <label class="col-md-4 control-label"><?= Yii::t('app', 'Grader'); ?></label>
            <div class="col-md-8">
                <div class="repeater">
                    <div data-repeater-list="TLoglist">
                        <?php
                        if(count($modDkg)>0){
                            foreach($modDkg as $i => $dkg){ 
                                $model->grader_id = $dkg->graderlog_id;
                                $graderlog_id = $model->grader_id;
                                //$sql_grader_nama = "select graderlog_nm from m_graderlog where graderlog_id = ".$graderlog_id."";
                                //$grader_nama = Yii::$app->db->createCommand($sql_grader_nama)->queryScalar();
                                $graderlog = \app\models\MGraderlog::findOne(['graderlog_id'=>$graderlog_id]);
                                $graderlog_nm = $graderlog->graderlog_nm;
                            ?>
                                <div data-repeater-item style="display: block;">
                                    <span class="input-group-btn" style="width: 260px;">
                                        <?php echo \yii\bootstrap\Html::activeTextInput($model, 'grader_id', ['readonly'=>true, 'class'=>'form-control', 'value'=>$graderlog_nm]) ?>
                                    </span>
                                </div>
                            <?php } ?>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <?php
        $pengajuan_pembelianlog = \app\models\TPengajuanPembelianlog::findOne(['pengajuan_pembelianlog_id'=>$model->pengajuan_pembelianlog_id]);
        $log_kontrak = \app\models\TLogKontrak::findOne(['log_kontrak_id'=>$model->log_kontrak_id]);
        ?>
        <?= $form->field($model, 'kode')->textInput(['disabled'=>true, 'value'=>$pengajuan_pembelianlog->kode])->label("Kode Keputusan"); ?>
        <?= $form->field($model, 'kode_po')->textInput(['disabled'=>true, 'value'=>$log_kontrak->kode." - ".\app\components\DeltaFormatter::formatDateTimeForUser2($log_kontrak->tanggal_po)])->label("Kode PO"); ?>
        <?= $form->field($model, 'nomor_kontrak')->textInput(['disabled'=>true, 'value'=>$log_kontrak->nomor])->label("Nomor Kontrak"); ?>
        <?= $form->field($model, 'kode_bajg')->textInput(['disabled'=>true]); ?>
        <?= $form->field($model, 'lokasi_muat')->textInput(['disabled'=>true]); ?>
        <?= $form->field($model, 'model_ukuran_loglist')->textInput(['disabled'=>true]); ?>
        <?= $form->field($model, 'area_pembelian')->textInput(['disabled'=>true]); ?>
    </div>
    <?php \yii\bootstrap\ActiveForm::end(); ?>
</div>
<script>
function lihatLampiran(loglist_id, lampiran){
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/purchasinglog/laporan/lihatLampiran']); ?>',
        type   : 'POST',
        data   : {loglist_id, lampiran},
        success: function (data){
			$('#table-detail > tbody').html("");
			if(data.html){
				$('#table-detail > tbody').html(data.html);
                $('#lampiran').html('<font style="font-weight: bold; background-color: darkorange; color: #fff; font-size: 20px;">L '+lampiran+'</font>');
			}
			reordertable('#table-detail');
            <?php
            //isset($_REQUEST['success']) && $_REQUEST['success'] == 2 ? $_REQUEST['GET'] = 0 : $_REQUEST['GET'] = '';
            ?>
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
    lihatRekap(loglist_id, lampiran);
    console.log(lampiran);
}

function lihatRekap(loglist_id, lampiran) {
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/purchasinglog/laporan/lihatRekap']); ?>',
        type   : 'POST',
        data   : {loglist_id, lampiran},
        success: function (data){
			$('#table-rekap > tbody').html("");
			if(data.html){
				$('#table-rekap > tbody').html(data.html);
			}
			reordertable('#table-rekap');
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}
</script>
