<?php
$modKayu = app\models\MKayu::findOne($kayu_id);
$modLogKeluar = app\models\TLogKeluar::findOne(['no_barcode'=>$no_barcode]);
$modLog = app\models\HPersediaanLog::findOne(['no_barcode'=>$no_barcode, 'reff_no'=>$modLogKeluar->reff_no]);
$modDetail->kayu_id = $kayu_id;
$modDetail->no_barcode = $no_barcode;
$modDetail->alasan_pengembalian = $alasan;
?>
<tr>
    <td style="vertical-align: middle; text-align: center;" class="">
        <?php echo yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
        <span class="no_urut"></span>
    </td>
    <td>
        <?php echo $modKayu->group_kayu .' - '. $modKayu->kayu_nama ?>
        <?= \yii\bootstrap\Html::activeHiddenInput($modDetail, '[ii]kayu_id', ['class'=>'form-control','style'=>'width:100%']) ?>
    </td>
    <td  class="text-align-center">
        <?php echo $no_barcode .'<br>'. $modLog->no_lap .'<br>'. $modLog->no_grade  .'<br>'. $modLog->no_btg ?>
        <?= \yii\bootstrap\Html::activeHiddenInput($modDetail, '[ii]no_barcode', ['class'=>'form-control','style'=>'width:100%']) ?>
    </td>
    <td class="text-align-center"><?php echo $modLog->fisik_panjang ?></td>
    <td class="text-align-center"><?php echo $modLog->diameter_ujung1 ?></td>
    <td class="text-align-center"><?php echo $modLog->diameter_ujung2 ?></td>
    <td class="text-align-center"><?php echo $modLog->diameter_pangkal1 ?></td>
    <td class="text-align-center"><?php echo $modLog->diameter_pangkal2 ?></td>
    <td class="text-align-center"><?php echo $modLog->fisik_diameter ?></td>
    <td class="text-align-center"><?php echo $modLog->cacat_panjang ?></td>
    <td class="text-align-center"><?php echo $modLog->cacat_gb ?></td>
    <td class="text-align-center"><?php echo $modLog->cacat_gr ?></td>
    <td class="text-align-right vol"><?php echo $modLog->fisik_volume ?></td>
    <td>
        <?php echo $alasan ?>
        <?= \yii\bootstrap\Html::activeHiddenInput($modDetail, '[ii]alasan_pengembalian', ['class'=>'form-control','style'=>'width:100%']) ?>
    </td>
    <td style="vertical-align: middle; text-align: center;" class="td-kecil">
		<?php 
        if(!$edit && $id){
            echo '<a class="btn btn-xs grey"><i class="fa fa-trash-o"></i></a>'; 
        } else {
            if($modDetail->status_penerimaan){
                echo '<a class="btn btn-xs grey"><i class="fa fa-trash-o"></i></a>';
            } else {
                echo '<a class="btn btn-xs red" onclick="hapusItem(this);"><i class="fa fa-trash-o"></i></a>'; 
            }
        }
        ?>
    </td>
</tr>