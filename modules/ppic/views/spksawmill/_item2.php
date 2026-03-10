<?php
if(!empty($model->spk_sawmill_id) && empty($edit)){
    $disabled = true;
    $removebtn = '<center><a class="btn btn-xs grey" disabled="disabled"><i class="fa fa-remove"></i></a></center>';
}else{
    $disabled = false;
    $removebtn = '<center><a class="btn btn-xs red" onclick="cancelItem(this);"><i class="fa fa-remove"></i></a></center>';
}
?>
<tr>
    <td style="vertical-align: middle; text-align: center;" class="td-kecil">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
        <?= yii\helpers\Html::activeHiddenInput($modDetail, "[ii]spk_sawmill_detail_id") ?>
        <span class="no_urut"></span>
    </td>
    <td>
        <?php 
        if($model->spk_sawmill_id){
            $modDetail->size = $modDetail->produk_t . 'x' . $modDetail->produk_l;
        }
        echo \yii\bootstrap\Html::activeDropDownList($modDetail, '[ii]size', \app\models\MDefaultValue::getOptionList('size-sawmill'),['class'=>'form-control select2', 'prompt'=>'','disabled'=>$disabled]); 
        ?>
    </td>
    <td>
        <?php
        if($model->spk_sawmill_id){
            $modPanjang = Yii::$app->db->createCommand("SELECT produk_p FROM t_spk_sawmill_detail 
                                                        WHERE spk_sawmill_id = $model->spk_sawmill_id AND produk_t = $modDetail->produk_t 
                                                        AND produk_l = $modDetail->produk_l AND kategori_ukuran = '$modDetail->kategori_ukuran' 
                                                      ")->queryAll();
            $listPanjang = [];
            foreach ($modPanjang as $row) {
                $listPanjang[] = $row['produk_p'];
            }
            $modDetail->panjang = implode(', ', $listPanjang);
        }
        echo yii\bootstrap\Html::activeTextInput($modDetail, '[ii]panjang', ['class'=>'form-control', 'style'=>'display:inline-block; font-size: 1.2rem;','disabled'=>$disabled]); 
        ?>
    </td>
    <td>
        <?= \yii\bootstrap\Html::activeDropDownList($modDetail, '[ii]kategori_ukuran', \app\models\MDefaultValue::getOptionList('kategori-ukuran'),['class'=>'form-control','style'=>'font-size: 1.2rem;', 'prompt'=>'','disabled'=>$disabled]); ?>
    </td>
    <td style="vertical-align: middle;">
        <?php echo $removebtn; ?>
    </td>
</tr>