<?php
$ukuranganrange = \app\models\MDefaultValue::getOptionList('log-sengon-panjang');
$terbitpo = ''; $diameterharga = '';
if(!empty($model->posengon_id)){
	$show = "";
	$input = "none;";
    if(!empty($modApproval)){
        if($modApproval->status == \app\models\TApproval::STATUS_APPROVED){
            $terbitpo = '<a class="btn btn-xs purple btn-outline" onclick="detailPo('.$model->posengon_id.');"><i class="fa fa-print"></i> Terbitkan PO</a>';
        }else{
            $terbitpo = '<a class="btn btn-xs grey btn-outlin tooltips" data-original-title="Not Quailified, coz approval not approved"><i class="fa fa-print"></i> Terbitkan PO</a>';
        }
        if($modApproval->status != \app\models\TApproval::STATUS_NOT_CONFIRMATED){
            $show = "none;";
        }
    }
    $diameterharga = yii\helpers\Json::decode($model->diameter_harga);
    if(!empty($diameterharga)){
        $htmldiahar = "<table style='width:100%'><tr>";
        foreach($diameterharga as $i => $dia_harga){
            $subhtml = "";
            $subhtml .= "<table style='width:100%;'>";
            $subhtml .= "<tr style='line-height:0.8'><td colspan='2' style='font-size:1rem;'><b>".$dia_harga[0]['panjang']." cm ".$dia_harga[0]['wilayah']."</b></td></tr>";
            foreach($dia_harga as $i => $dihar){
                $subhtml .= "<tr style='line-height:0.8'><td style='font-size:1rem;'>".$dihar['diameter_awal']." - ".$dihar['diameter_akhir']." cm</td>     ";
                $subhtml .= "    <td style='font-size:1rem;'>Rp. ". $dihar['harga']."</td></tr>";
            }
            $subhtml .= "</table>";
            $htmldiahar .= '<td style="33%; vertical-align:top">'.$subhtml.'</td>';
        }
        $htmldiahar .= '</tr></table>';
    }
}else{
    $show = "none;";
	$input = "";
}
?>
<tr style="">
	<td class="td-kecil" style="vertical-align: middle; text-align: center;">
		<?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut']); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($model, '[ii]posengon_id',[]); ?>
		<span class="no_urut"></span>
	</td>
	<td class="td-kecil" style="vertical-align: middle; padding-top: 20px; ">
		<?php echo yii\helpers\Html::activeDropDownList($model, '[ii]suplier_id', app\models\MSuplier::getOptionListPo2("'LS','LJ'"),['class'=>'form-control select2','prompt'=>'','style'=>'width:100%; padding: 1px; height:25px;']); ?>
	</td>
	<?php 
	foreach($ukuranganrange as $i => $range){
        if((!empty($model->posengon_id))){
			$sql = "SELECT kuota -> '{$range}' AS kuota FROM t_posengon WHERE posengon_id = {$model->posengon_id}";
			$kuota = Yii::$app->db->createCommand($sql)->queryOne();
	?>
        <td class="td-kecil" style="vertical-align: middle; text-align: center;">
			<?= \yii\bootstrap\Html::activeTextInput($model, '[ii]['.$range.']qty_m3',['class'=>'form-control float col-m3','style'=>'width:100%; padding: 1px; height:25px; font-size:1.2rem;','value'=> is_integer($kuota['kuota'])?number_format($kuota['kuota']):$kuota['kuota'],'onblur'=>"total();"]); ?>
		</td>
    <?php }else{ ?>
		<td class="td-kecil" style="vertical-align: middle; text-align: center;">
			<?= \yii\bootstrap\Html::activeTextInput($model, '[ii]['.$range.']qty_m3',['class'=>'form-control float col-m3','style'=>'width:100%; padding: 1px; height:25px; font-size:1.2rem;','value'=>0,'onblur'=>"total();"]); ?>
		</td>
	<?php } ?>
    <?php } ?>
	<td class="td-kecil" style="vertical-align: middle; text-align: center;">
		<?= \yii\bootstrap\Html::activeTextInput($model, '[ii][total]qty_m3',['class'=>'form-control float','style'=>'width:100%; padding: 1px; height:25px; font-size:1.2rem;','disabled'=>true]); ?>
	</td>
	<td class="td-kecil" style="vertical-align: top; text-align: center;">
        <span id='place-diameterharga' style="text-align: left;"><?= (!empty($htmldiahar))?$htmldiahar:"" ?></span>
        <a class="btn btn-xs blue-steel btn-outline edit-mode" onclick="setHarga(this);" style="display: <?php echo $input ?>;"><i class="fa fa-bar-chart"></i> Set Harga</a>
        <?= yii\bootstrap\Html::activeHiddenInput($model, '[ii]diameter_harga'); ?>
	</td>
	<td class="td-kecil" style="vertical-align: middle; text-align: center;">
        <?= (isset($terbitpo))?$terbitpo:"-"; ?>
	</td>
	<td class="td-kecil" style="vertical-align: middle; text-align: center;">
        <div class="show-mode" style="display: <?php echo $show ?>;">
            <a class="btn btn-xs blue-steel tooltips" data-original-title="Edit PO" style="padding: 2px; margin-right: 0px;" onclick="editPO(this);"><i class="fa fa-edit"></i></a> 
			<a class="btn btn-xs red tooltips" data-original-title="Delete PO" style="padding: 2px;" onclick="deletePO(<?= $model->posengon_id ?>);"><i class="fa fa-trash-o"></i></a>
		</div>
		<div class="input-mode" style="display: <?php echo $input ?>;">
            <a class="btn btn-xs hijau tooltips" id="btn-save-po" data-original-title="Save" style="padding: 2px; margin-right: 0px;" onclick="savePO(this);"><i class="fa fa-check"></i></a> 
			<a class="btn btn-xs red tooltips" data-original-title="Remove" style="padding: 2px;" onclick="cancelItem(this);"><i class="fa fa-remove"></i></a>
		</div>
		<div class="edit-mode" style="display: none;">
            <a class="btn btn-xs hijau tooltips" data-original-title="Update" style="padding: 2px; margin-right: 0px;" onclick="savePO(this);"><i class="fa fa-check"></i></a> 
			<a class="btn btn-xs red tooltips" data-original-title="Cancel" style="padding: 2px;" onclick="getItems();"><i class="fa fa-remove"></i></a>
		</div>
	</td>
</tr>
