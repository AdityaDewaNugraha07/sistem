<?php
if(!empty($model->hasil_orientasi_id)){
	$disabled = true;
}else{
	$disabled = false;
}
?>
<tr style="">
	<td class="td-kecil" style="vertical-align: middle; text-align: center;">
		<?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut']); ?>
		<span class="no_urut"></span>
	</td>
	<td class="td-kecil" style="vertical-align: middle; padding-top: 20px; ">
		<span class="input-group-btn" style="width: 100%">
            <?= yii\bootstrap\Html::activeHiddenInput($model, '[ii]graderlog_id'); ?>
            <?php
            if(!empty($edit) || !empty($model->hasil_orientasi_id)){
                echo yii\bootstrap\Html::activeHiddenInput($model, '[ii]gt_dkg_id',['style'=>'width:50px;']);
                echo \yii\bootstrap\Html::activeTextInput($model, '[ii]gt_dkg_kode',['class'=>'form-control','style'=>'padding: 2px;  font-size:1.2rem;','disabled'=>true]);
            }else{
                echo yii\helpers\Html::activeDropDownList($model, '[ii]gt_dkg_id', app\models\TDkg::getOptionListDkg(),['class'=>'form-control select2','prompt'=>'','style'=>'width:80%; padding: 2px; ','onchange'=>'setGrader(this)','disabled'=>$disabled]);
                echo '<span class="input-group-btn" style="width: 25%">
                        <a class="btn btn-icon-only btn-default tooltips" onclick="masterDkg(this);" data-original-title="Pick OP" style="margin-left: 3px; border-radius: 4px;"'. ($disabled)?"disabled":"".'<i class="fa fa-list"></i></a>
                      </span>';
            }
            ?>
		</span>
	</td>
	<td class="td-kecil" style="vertical-align: middle; text-align: center;">
		<?= \yii\bootstrap\Html::activeTextInput($model, '[ii]gt_tipe_dinas',['class'=>'form-control','style'=>'width:100%; padding: 2px;  font-size:1.2rem;','disabled'=>true]); ?>
	</td>
	<td class="td-kecil" style="vertical-align: middle; text-align: center;">
		<?= \yii\bootstrap\Html::activeTextInput($model, '[ii]gt_nama_grader',['class'=>'form-control','style'=>'width:100%; padding: 2px;  font-size:1.2rem;','disabled'=>true]); ?>
	</td>
	<td class="td-kecil" style="vertical-align: middle; text-align: center;">
		<?= \yii\bootstrap\Html::activeTextInput($model, '[ii]gt_wilayah_dinas',['class'=>'form-control','style'=>'width:100%; padding: 2px;  font-size:1.2rem;','disabled'=>true]); ?>
	</td>
	<td class="td-kecil" style="vertical-align: middle; text-align: center;">
		<a class="btn btn-xs red" onclick="cancelItemGrader(this);"><i class="fa fa-remove"></i></a>
	</td>
</tr>
<?php $this->registerJs(" 
	
", yii\web\View::POS_READY); ?>