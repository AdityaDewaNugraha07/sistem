<tr>
    <td style="vertical-align: middle; text-align: center;" class="td-kecil">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
        <?= yii\helpers\Html::activeHiddenInput($modDetail, "[ii]pemakaian_bhpsub_detail_id") ?>
        <span class="no_urut"></span>
    </td>
    <td style="vertical-align: middle;width:20%;" id="item-detail" class="td-kecil">
        <span class="input-group-btn" style="width: 100%">
			<?php
            $itemproduk = [];
            $namaItem = "";
            if(!empty($edit)){

                $itemproduk = [$modDetail->bhp_id=>$modDetail->bhpId->bhp_nm];
            }
			?>
            <?= \yii\helpers\Html::activeHiddenInput($modDetail, '[ii]terima_bhp_sub_id', ['class'=>'form-control','onchange'=>'setItem(this)','style'=>'width:100%; font-size:1.2rem; padding:5px;','readonly'=>'readonly']); ?>
			<?php echo yii\helpers\Html::activeDropDownList($modDetail, '[ii]bhp_id',$itemproduk,['class'=>'form-control select2','prompt'=>'','style'=>'width:100%;']); ?>
		</span>
        <span class="input-group-btn" style="width: 10%">
			<a class="btn btn-icon-only btn-default tooltips" onclick="masterProduk(this)" data-original-title="Cari Pilihan" style="margin-left: 3px; border-radius: 4px;"><i class="fa fa-list"></i></a>
		</span>
    </td>
    
    <td style="vertical-align: middle;width:5%;" class="td-kecil">
        <?= \yii\helpers\Html::activeTextInput($modBhp,'[ii]bhp_satuan',['class' => 'form-control','style' => 'width:100%; font-size:1.2rem; padding:5px;','readonly' => 'readonly']); ?>
    </td>
    <td style="vertical-align: middle;width:10%;" class="td-kecil">
        <?= \yii\helpers\Html::activeTextInput($modTerimaBhpsub,'[ii]target_plan',['class' => 'form-control','style' => 'width:100%; font-size:1.2rem; padding:5px;','readonly' => 'readonly']); ?>
    </td>
    <td style="vertical-align: middle;width:10%;" class="td-kecil">  
        <?= \yii\helpers\Html::activeTextInput($modTerimaBhpsub,'[ii]target_peruntukan',['class' => 'form-control','style' => 'width:100%; font-size:1.2rem; padding:5px;','readonly' => 'readonly']); ?>
    </td>
    <td style="vertical-align: middle; font-size: 1.1rem !important;width:5%;" class="td-kecil text-align-center" id="place-availablestock">
        <?= ($modStock !== null) ? \yii\helpers\Html::activeTextInput($modStock, '[ii]jumlah', ['class'=>'form-control float','style'=>'width:100%; font-size:1.2rem; padding:5px;','readonly'=>'readonly']) : '' ?>
        <?= \yii\helpers\Html::activeHiddenInput($modDetail, '[ii]harga_peritem', ['class'=>'form-control float','style'=>'width:100%; font-size:1.2rem; padding:5px;', 'readonly'=>'readonly']); ?>
    </td>
    <td style="vertical-align: middle;width:5%;" class="td-kecil">
        <?= \yii\helpers\Html::activeTextInput($modDetail, '[ii]qty', ['class'=>'form-control float','style'=>'width:100%; font-size:1.2rem; padding:5px;', 'title'=>'QTY']); ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil">
        <?= \yii\helpers\Html::activeDropDownList($modDetail, '[ii]dept_peruntukan', app\models\ViewDepartement::getOptionList(), ['class' => 'form-control select2', 'prompt' => '','onchange'=>'setInventaris(this)','style' => 'width:100%;']); ?>
    </td>	
    <td style="vertical-align: middle;" class="td-kecil">
        <?= \yii\helpers\Html::activeDropDownList($modDetail, '[ii]asset_peruntukan', [], ['class' => 'form-control select2','prompt' => '','style' => 'width:100%;']); ?>
    </td>
	<td style="vertical-align: middle;" class="td-kecil">
        <?= \yii\helpers\Html::activeTextInput($modDetail, '[ii]reff_no', ['class'=>'form-control','style'=>'width:100%; font-size:1.2rem; padding:5px;','placeholder'=>'nomor referensi']); ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil">
        <?= \yii\helpers\Html::activeTextarea($modDetail, '[ii]keterangan', ['class'=>'form-control','style'=>'width:100%; height: 55px; font-size:1.1rem; padding:5px;','placeholder'=>'Keterangan']); ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil">
        <?php echo '<center><a class="btn btn-xs red" onclick="cancelItem(this);"><i class="fa fa-remove"></i></a></center>'; ?>
    </td>    
</tr>