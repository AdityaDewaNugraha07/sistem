<?php
$item_produk_jasa = [];
$sql = "SELECT m_produk_jasa.* FROM t_op_ko_detail JOIN m_produk_jasa ON m_produk_jasa.produk_jasa_id = t_op_ko_detail.produk_id WHERE op_ko_id = ".$modOp->op_ko_id;
$mod = Yii::$app->db->createCommand($sql)->queryAll();
if(count($mod)>0){
    foreach($mod as $i => $mo){
        $item_produk_jasa[$mo['produk_jasa_id']] = $mo['kode']." - ".$mo["nama"];
    }
}

?>
<tr>
    <td style="vertical-align: middle; text-align: center;" class="td-kecil">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
        <?= yii\helpers\Html::activeHiddenInput($modDetail, "[ii]op_ko_detail_id") ?>
        <?php // echo yii\helpers\Html::activeHiddenInput($modDetail, "[ii]produk_jasa_id") ?>
        <span class="no_urut"></span>
    </td>
    <td style="vertical-align: middle;" class="td-kecil">
        <div class="input-group date date-picker" >
            <?= \yii\bootstrap\Html::activeTextInput($modDetail, '[ii]tanggal',['class'=>'form-control','style'=>'width:100%; font-size:1.3rem; padding:2px; height:30px;','readonly'=>'readonly','placeholder'=>'Pilih Tanggal']); ?>
            <span class="input-group-btn">
                <button class="btn default" type="button" style="padding: 5px; font-size: 12px;">
                    <i class="fa fa-calendar"></i>
                </button>
            </span>
        </div>
    </td>
    <td style="vertical-align: middle;" class="td-kecil">
		<?= \yii\helpers\Html::activeTextInput($modDetail, '[ii]nopol', ['class'=>'form-control','style'=>'width:100%; font-size:1.3rem; padding:2px; height:30px; text-align:center']); ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil">
		<?= \yii\helpers\Html::activeTextInput($modDetail, '[ii]nomor_palet', ['class'=>'form-control','style'=>'width:100%; font-size:1.3rem; padding:2px; height:30px; text-align:center','onchange'=>'setMeterKubikTerima(this)']); ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil">
		<?= \yii\bootstrap\Html::activeDropDownList($modDetail, '[ii]produk_jasa_id', $item_produk_jasa,['class'=>'form-control','style'=>'width:100%; font-size:1.1rem; padding:1px; height:25px; text-align:center','onchange'=>'setMeterKubikTerima(this);','prompt'=>'']); ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil">
        <span class="input-group-btn" style="width: 50%">
            <?= \yii\helpers\Html::activeTextInput($modDetail, '[ii]t', ['class'=>'form-control float','style'=>'width:100%; font-size:1.1rem; padding:1px; height:25px; text-align:right','onblur'=>'setMeterKubikTerima(this)']); ?>
        </span>
        <span class="input-group-btn" style="width: 50%">
            <?= \yii\bootstrap\Html::activeDropDownList($modDetail, '[ii]t_satuan', \app\models\MDefaultValue::getOptionList('produk-satuan-dimensi'),['class'=>'form-control','style'=>'width:100%; font-size:1.1rem; padding:1px; height:25px; text-align:center','onchange'=>'setMeterKubikTerima(this);']); ?>
        </span>
    </td>
    <td style="vertical-align: middle;" class="td-kecil">
        <span class="input-group-btn" style="width: 50%">
            <?= \yii\helpers\Html::activeTextInput($modDetail, '[ii]l', ['class'=>'form-control float','style'=>'width:100%; font-size:1.1rem; padding:1px; height:25px; text-align:right','onblur'=>'setMeterKubikTerima(this)']); ?>
        </span>
        <span class="input-group-btn" style="width: 50%">
            <?= \yii\bootstrap\Html::activeDropDownList($modDetail, '[ii]l_satuan', \app\models\MDefaultValue::getOptionList('produk-satuan-dimensi'),['class'=>'form-control','style'=>'width:100%; font-size:1.1rem; padding:1px; height:25px; text-align:center','onchange'=>'setMeterKubikTerima(this);']); ?>
        </span>
    </td>
    <td style="vertical-align: middle;" class="td-kecil">
        <span class="input-group-btn" style="width: 50%">
            <?= \yii\helpers\Html::activeTextInput($modDetail, '[ii]p', ['class'=>'form-control float','style'=>'width:100%; font-size:1.1rem; padding:1px; height:25px; text-align:right','onblur'=>'setMeterKubikTerima(this)']); ?>
        </span>
        <span class="input-group-btn" style="width: 50%">
            <?= \yii\bootstrap\Html::activeDropDownList($modDetail, '[ii]p_satuan', \app\models\MDefaultValue::getOptionList('produk-satuan-dimensi'),['class'=>'form-control','style'=>'width:100%; font-size:1.1rem; padding:1px; height:25px; text-align:center','onchange'=>'setMeterKubikTerima(this);']); ?>
        </span>
    </td>
    <td style="vertical-align: middle;" class="td-kecil">
		<?= \yii\helpers\Html::activeTextInput($modDetail, '[ii]qty_kecil', ['class'=>'form-control float','style'=>'width:100%; font-size:1.3rem; padding:2px; height:30px; text-align:right;','onblur'=>'setMeterKubikTerima(this)']); ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil">
		<?= \yii\helpers\Html::activeTextInput($modDetail, '[ii]kubikasi', ['class'=>'form-control float','style'=>'width:100%; font-size:1.3rem; padding:2px; height:30px; text-align:right','disabled'=>true]); ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil">
		<?= \yii\helpers\Html::activeTextInput($modDetail, '[ii]keterangan', ['class'=>'form-control','style'=>'width:100%; font-size:1.3rem; padding:2px; height:30px; text-align:left']); ?>
    </td>
    <td style="vertical-align: middle; text-align: center;" class="td-kecil">
		<?php echo '<center><a class="btn btn-xs red" onclick="cancelItemTerima(this,\'totalTerima()\');"><i class="fa fa-remove"></i></a></center>'; ?>
    </td>
</tr>
