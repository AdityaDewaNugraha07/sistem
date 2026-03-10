<?php
$listPanjang = [];
foreach ($modPanjang as $row) {
    $listPanjang[] = $row['produk_p'];
} 

?>
<tr>
    <td style="vertical-align: middle; text-align: center;" class="td-kecil">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
        <?= yii\helpers\Html::activeHiddenInput($modDetail, "['.$i.']bandsaw_detail_id") ?>
        <span class="no_urut"></span>
    </td>
    <td>
        <?= \yii\bootstrap\Html::activeDropDownList($modDetail, '['.$i.']nomor_bandsaw', \app\models\MDefaultValue::getOptionList('nomor-bandsaw'),['class'=>'form-control','style'=>'font-size: 1.2rem; width: 45px;', 'prompt'=>'','disabled'=>'']); ?>
    </td>
    <td>
        <?php
        echo \yii\bootstrap\Html::activeDropDownList($modDetail, '['.$i.']size', \app\models\MDefaultValue::getOptionList('size-sawmill'),['class'=>'form-control select2','style'=>'font-size: 1.2rem; width: 80px;', 'prompt'=>'','disabled'=>'']);  
        if(!empty($bandsaw_id) && empty($edit) && count($details)>0){
            $addpjg = '<a class="btn btn-xs grey" style="margin-top: 5px; display: none;"> Panjang</a>';
            $removepjg = '<a class="btn btn-xs grey" style="margin-top: 5px; display: none;"><i class="fa fa-trash-o"></i> Panjang</a>';
        } else {
            $addpjg = '<a class="btn btn-xs blue-hoki btn-outline" style="margin-top: 5px;" onclick="addPjg(this, '.$i.');"><i class="fa fa-plus"></i> Panjang</a>';
            $removepjg = '<a class="btn btn-xs red" style="margin-top: 5px;" onclick="removePjg(this, '.$i.');"><i class="fa fa-trash-o"></i> Panjang</a>';
        }
        echo $addpjg;
        echo $removepjg;
        ?>
    </td>
    <td>
        <?php 
        foreach ($listPanjang as $p => $pjg){ ?>
            <div class="place-panjang-<?= $i ?>" style="display: flex; align-items: center; gap: 5px; margin-bottom: 3px;">
                <?php
                echo yii\bootstrap\Html::activeTextInput($modDetail, '['.$i.']['.$p.']panjang', ['value' => $pjg, 'class' => 'form-control float', 'style' => 'width:60px; font-size:1.2rem;', 'disabled' => '']); 
                if(!empty($bandsaw_id) && empty($edit) && count($details)>0){
                    $addbtn = '<a class="btn btn-xs grey" style="margin-top: 5px; display: none;"><i class="fa fa-plus"></i></a>';
                } else {
                    $addbtn = '<a class="btn btn-xs blue-hoki btn-outline" style="margin-top: 5px;" onclick="hitung(this, '.$i.', '.$p.');"><i class="fa fa-plus"></i></a>';
                }
                echo $addbtn;
                ?>
            </div>
        <?php } ?>
    </td>
    <td>
        <?php 
        foreach ($listPanjang as $p => $pjg){ ?>
            <div class="place-jml-<?= $i ?>" style="display: flex; align-items: center; gap: 5px; margin-bottom: 3px;">
                <?php
                if(!empty($bandsaw_id)){
                    $query = "SELECT qty FROM t_bandsaw_detail WHERE bandsaw_id = $bandsaw_id AND produk_t = $modSpk->produk_t
                                AND produk_l = $modSpk->produk_l AND produk_p = $pjg";
                    $mods = Yii::$app->db->createCommand($query)->queryAll();
                    foreach($mods as $det){
                        $dataTersimpan = $det['qty'];

                        $jumlahSatu = $dataTersimpan % 5;
                        if ($jumlahSatu == 0 && $dataTersimpan > 0) {
                            $jumlahSatu = 5;
                        }

                        $jml = str_repeat('1', $jumlahSatu);
                        $qty = $dataTersimpan - $jumlahSatu;

                        $modDetail->qty = $qty;
                        $modDetail->jml = $jml;
                        $modDetail->qty2 = $dataTersimpan;
                    }
                }
                echo yii\bootstrap\Html::activeTextInput($modDetail, '['.$i.']['.$p.']jml', ['class' => 'form-control', 'style' => 'width:60px; font-size:1.2rem;', 'disabled' => '']);
                echo yii\bootstrap\Html::activeTextInput($modDetail, '['.$i.']['.$p.']qty', ['class' => 'form-control', 'style' => 'width:60px; font-size:1.2rem; text-align: right;', 'disabled' => '']);
                if(!empty($bandsaw_id) && empty($edit) && count($details)>0){
                    $removebtn = '<center><a class="btn btn-xs grey" style="margin-top: 5px; display: none;"><i class="fa fa-minus"></i></a></center>';
                } else {
                    $removebtn = '<center><a class="btn btn-xs red" onclick="remove(this, '.$i.', '.$p.');"><i class="fa fa-minus"></i></a></center>';
                }
                echo $removebtn;
                echo yii\bootstrap\Html::activeTextInput($modDetail, '['.$i.']['.$p.']qty2', ['class' => 'form-control', 'style' => 'width:60px; font-size:1.2rem; text-align: right; color: red; font-weight: bold;', 'disabled' => '']);
                ?>
            </div>
        <?php } ?>
    </td>
</tr>