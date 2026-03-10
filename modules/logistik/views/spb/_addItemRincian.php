<tr>
    <td style="vertical-align: middle; text-align: center;">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
        <span class="no_urut"></span>
    </td>
    <td style="vertical-align: middle; width: 30%;" id="item-detail">
        <?php //echo yii\helpers\Html::activeDropDownList($modTerimaBhpSub, '[ii]bhp_id', $arraymap,['class'=>'form-control','id'=>'bhp_id','onchange'=>'setItemRincian(this); setBhpNama(this); setBpbd(this, '.$model->bpb_id.')','prompt'=>'','style'=>'width:90%']); ?>
        <?php echo yii\helpers\Html::activeDropDownList($modTerimaBhpSub, '[ii]bpbd_id', $arraymap,['class'=>'form-control','id'=>'bpbd_id','onchange'=>'setItemRincian(this); setBhpNama(this); setBhp(this, '.$model->bpb_id.')','prompt'=>'','style'=>'width:90%']); ?>
        <?= \yii\helpers\Html::activeHiddenInput($modTerimaBhpSub, '[ii]harga_peritem', ['class'=>'form-control float', 'disabled'=>'disabled']); ?>
        <?= \yii\helpers\Html::activeHiddenInput($modTerimaBhpSub, '[ii]bhp_nm', ['class'=>'form-control', 'disabled'=>'disabled']); ?>
        <?php //echo \yii\helpers\Html::activeHiddenInput($modTerimaBhpSub, '[ii]bpbd_id', ['class'=>'form-control', 'disabled'=>'disabled']); ?>
        <?= \yii\helpers\Html::activeHiddenInput($modTerimaBhpSub, '[ii]bhp_id', ['class'=>'form-control', 'disabled'=>'disabled']); ?>
    </td>
    <td style="vertical-align: middle;">
        <?php echo yii\helpers\Html::activeDropDownList($modTerimaBhpSub, '[ii]target_plan', \app\models\MDefaultValue::getOptionList('plan-part-bhp'),['class'=>'form-control','prompt'=>'']); ?>
    </td>
    <td style="vertical-align: middle;">
        <?php 
            $dep = Yii::$app->user->identity->pegawai->departement_id; 
            $peruntukan = 'peruntukan-'.$dep;
        ?>
        <?php echo yii\helpers\Html::activeDropDownList($modTerimaBhpSub, '[ii]target_peruntukan', \app\models\MDefaultValue::getOptionList($peruntukan),['class'=>'form-control','prompt'=>'']); ?>
    </td>
    <td style="vertical-align: middle; width: 10%;">
        <?= \yii\helpers\Html::activeTextInput($modTerimaBhpSub, '[ii]qty', ['id'=>'qty', 'class'=>'form-control float','placeholder'=>'Qty']); ?>
    </td>
    <td style="vertical-align: middle; width: 25%;">
        <?= \yii\helpers\Html::activeTextarea($modTerimaBhpSub, '[ii]keterangan', ['class'=>'form-control','style'=>'width:100%; height: 55px; font-size:1.1rem; padding:5px;','placeholder'=>'Keterangan']); ?>
    </td>
    <td style="vertical-align: middle; text-align: center;">
        <a class="btn btn-xs red" onclick="cancelItemRincian(this);"><i class="fa fa-remove"></i></a>
    </td>
</tr>
<script>
    
</script>