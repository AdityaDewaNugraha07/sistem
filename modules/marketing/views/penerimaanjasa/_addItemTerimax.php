<?php
$item_produk_jasa = [];
$sql = "SELECT m_produk_jasa.*  ".
        "   FROM t_op_ko_detail  ".
        "   JOIN m_produk_jasa ON m_produk_jasa.produk_jasa_id = t_op_ko_detail.produk_id  ".
        "   WHERE op_ko_id = ".$modOp->op_ko_id." ".
        "   ORDER by m_produk_jasa.nama ASC ".
        "   ";
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
    <td style="vertical-align: middle; width: 80px;" class="td-kecil">
        <div class="input-group date date-picker" >
            <?= \yii\bootstrap\Html::activeTextInput($modDetail, '[ii]tanggal',['class'=>'form-control','style'=>'width:100%; font-size:1.3rem; padding:2px; height:30px;','readonly'=>'readonly','placeholder'=>'Pilih Tanggal']); ?>
            <span class="input-group-btn">
                <button class="btn default" type="button" style="padding: 5px; font-size: 12px;">
                    <i class="fa fa-calendar"></i>
                </button>
            </span>
        </div>
    </td>
    <td class="td-kecil" style="vertical-align: middle; width: 60px; height: 20px;">
		<?= \yii\helpers\Html::activeTextInput($modDetail, '[ii]nopol', ['class'=>'form-control','style'=>'width:100%; line-height: 25px; font-size:1.3rem; padding:2px; height: 25px; text-align:center']); ?>
    </td>
    <td class="td-kecil" style="vertical-align: middle; width: 150px; height: 20px;">
		<?= \yii\bootstrap\Html::activeDropDownList($modDetail, '[ii]produk_jasa_id', $item_produk_jasa,['class'=>'form-control produk_jasa','style'=>'width:100%; line-height: 25px; font-size:1.1rem; padding:1px; height:25px; text-align:center','onchange'=>'setMeterKubikTerima(this);','prompt'=>'', 'title'=>'produk_jasa_id']); ?>
    </td>
    <td class="td-kecil" style="vertical-align: middle; width: 30px; height: 20px;">
		<?= \yii\helpers\Html::activeTextInput($modDetail, '[ii]nomor_palet', ['class'=>'form-control','style'=>'width:50px; line-height: 25px; font-size:1.3rem; padding:2px; height:25px; text-align:center', 'onblur'=>'setMeterKubikTerima(this)', 'title'=>'detail_qty_besar']); ?>
    </td>
    <td style="vertical-align: middle; width: 80px;" class="td-kecil">
        <span class="input-group-btn" style="width: 30px;">
            <?= \yii\helpers\Html::activeTextInput($modDetail, '[ii]t', ['class'=>'form-control float','style'=>'width:100%; font-size:1.1rem; padding:1px; height:25px; text-align:right', 'onblur'=>'setMeterKubikTerima(this)', 'title'=>'t']); ?>
        </span>
        <span class="input-group-btn" style="width: 50px;">
            <?= \yii\bootstrap\Html::activeDropDownList($modDetail, '[ii]t_satuan', \app\models\MDefaultValue::getOptionList('produk-satuan-dimensi'),['class'=>'form-control','style'=>'width:100%; font-size:1.1rem; padding:1px; height:25px; text-align:center','onchange'=>'setMeterKubikTerima(this);']); ?>
        </span>
    </td>
    <td style="vertical-align: middle; width: 80px;" class="td-kecil">
        <span class="input-group-btn" style="width: 30px;">
            <?= \yii\helpers\Html::activeTextInput($modDetail, '[ii]l', ['class'=>'form-control float','style'=>'width:100%; font-size:1.1rem; padding:1px; height:25px; text-align:right','onblur'=>'setMeterKubikTerima(this)']); ?>
        </span>
        <span class="input-group-btn" style="width: 50px;">
            <?= \yii\bootstrap\Html::activeDropDownList($modDetail, '[ii]l_satuan', \app\models\MDefaultValue::getOptionList('produk-satuan-dimensi'),['class'=>'form-control','style'=>'width:100%; font-size:1.1rem; padding:1px; height:25px; text-align:center','onchange'=>'setMeterKubikTerima(this);']); ?>
        </span>
    </td>
    <td style="vertical-align: middle; width: 80px;" class="td-kecil">
        <span class="input-group-btn" style="width: 30px;">
            <?= \yii\helpers\Html::activeTextInput($modDetail, '[ii]p', ['class'=>'form-control float','style'=>'width:100%; font-size:1.1rem; padding:1px; height:25px; text-align:right','onblur'=>'setMeterKubikTerima(this)']); ?>
        </span>
        <span class="input-group-btn" style="width: 50px;">
            <?= \yii\bootstrap\Html::activeDropDownList($modDetail, '[ii]p_satuan', \app\models\MDefaultValue::getOptionList('produk-satuan-dimensi'),['class'=>'form-control','style'=>'width:100%; font-size:1.1rem; padding:1px; height:25px; text-align:center','onchange'=>'setMeterKubikTerima(this);']); ?>
        </span>
    </td>
    <td style="vertical-align: middle;" class="td-kecil">
		<?= \yii\helpers\Html::activeTextInput($modDetail, '[ii]qty_kecil', ['class'=>'form-control float detail_qty_kecil','style'=>'width:100%; font-size:1.3rem; padding:2px; height:30px; text-align:right;','onblur'=>'$(this).parents("tr").find(`input[name*="[qty_kecil_actual]"]`).val($(this).val()); return setMeterKubikTerima(this);', 'title'=>'detail_qty_kecil']); ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil">
		<?= \yii\helpers\Html::activeTextInput($modDetail, '[ii]kubikasi', ['class'=>'form-control float detail_kubikasi','style'=>'width:100%; font-size:1.3rem; padding:2px; height:30px; text-align:right','disabled'=>true, 'title'=>'detail_kubikasi']); ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil">
		<?= \yii\helpers\Html::activeTextInput($modDetail, '[ii]qty_kecil_actual', ['class'=>'form-control float detail_qty_kecil','style'=>'width:100%; font-size:1.3rem; padding:2px; height:30px; text-align:right;','onblur'=>'setMeterKubikTerima(this);', 'title'=>'detail_qty_kecil_actual']); ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil">
		<?= \yii\helpers\Html::activeTextInput($modDetail, '[ii]kubikasi_actual', ['class'=>'form-control float detail_kubikasi','style'=>'width:100%; font-size:1.3rem; padding:2px; height:30px; text-align:right','disabled'=>true, 'title'=>'detail_kubikasi_actual']); ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil">
		<?= \yii\helpers\Html::activeTextInput($modDetail, '[ii]keterangan', ['class'=>'form-control','style'=>'width:100%; font-size:1.3rem; padding:2px; height:30px; text-align:left']); ?>
    </td>
    <td style="vertical-align: middle; text-align: center;" class="td-kecil">
    <?php
    if (isset($_GET['edit']) || isset($_POST['edit']) || $edit == 1) {
    ?>
    <?php echo '<center><a class="btn btn-xs red" onclick="cancelItemTerima(this,\'totalTerima()\');"><i class="fa fa-remove"></i></a></center>'; ?>
    <?php
    }
    ?>
    </td>
</tr>
