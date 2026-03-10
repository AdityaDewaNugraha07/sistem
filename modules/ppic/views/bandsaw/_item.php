<?php
$listPanjang = [];
foreach ($modPanjang as $row) {
    $listPanjang[] = $row['produk_p'];
} 
?>

<tr class="<?= $isFirst ? 'first-row-nobandsaw' : '' ?>" id="tr-<?= $n; ?>-<?= $i; ?>">
    <td style="display: none;">
        <?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[$n]nomor_bandsaw", ['class'=>'form-control', 'value'=>$noban]); ?>
    </td>

    <?php if($isFirst){ ?>
    <!-- <td rowspan="<?php //echo $rowspan ?>" style="vertical-align: top; text-align: center;">
        <?php //echo yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
        <span class="no_urut"></span>
    </td> -->
    <td rowspan="<?= $rowspan ?>">
        <?= \yii\bootstrap\Html::activeDropDownList($modDetail, '['.$n.']nomor_bandsaw', \app\models\MDefaultValue::getOptionList('nomor-bandsaw'),['class'=>'form-control','style'=>'font-size: 1.2rem; width: 55px;', 'prompt'=>'','disabled'=>'', 'value'=>$noban]); ?>
        <?= '<a class="btn btn-xs blue-hoki btn-add-size" style="margin-top: 5px;" onclick="addSize(this, '.$n.', '.$i.');"><i class="fa fa-plus"></i> Size</a>';?>
    </td>
    <?php } ?>
    <td>
        <div class="place-size-<?= $n ?>-<?= $i ?>" style="display: flex; flex-direction: column; gap: 5px; margin-bottom: 3px;">
            <div style="display: flex; align-items: center;">
                <?php //echo '<a class="btn btn-xs red btn-remove-size" title="Hapus size" onclick="removeSize(this, '.$n.', '.$i.');"><i class="fa fa-times"></i></a>';?>
                <?php echo \yii\bootstrap\Html::activeDropDownList($modDetail, '['.$n.']['.$i.']size', \app\models\MDefaultValue::getOptionList('size-sawmill'),['class'=>'form-control select2','style'=>'font-size: 1.2rem; width: 90px;', 'prompt'=>'','disabled'=>'']);   ?>
            </div>
        </div>
    </td>
    <td>
        <?php 
        foreach ($listPanjang as $p => $pjg){ ?>
            <div class="place-panjang-<?= $n ?>-<?= $i ?>-<?= $p ?>" style="display: flex; align-items: center; gap: 5px; margin-bottom: 3px;">
                <?php
                $class = 'form-control float input-pjg ';
                if ($edit) {
                    $class .= 'highlightable';
                }
                echo yii\bootstrap\Html::activeTextInput($modDetail, '['.$n.']['.$i.']['.$p.']panjang', ['value' => $pjg, 'class' => $class, 'style' => 'width:55px; font-size:1.2rem;', 'readonly' => true, 'onclick'=>"hitung(this, $n, $i, $p);"]); 
                if(!empty($bandsaw_id)){
                    $query = "SELECT bandsaw_detail_id FROM t_bandsaw_detail WHERE bandsaw_id = $bandsaw_id AND produk_t = $modSpk->produk_t 
                                AND produk_l = $modSpk->produk_l AND produk_p = $pjg AND nomor_bandsaw = '$noban'";
                    $mods = Yii::$app->db->createCommand($query)->queryAll();
                    foreach($mods as $det){
                        $modDetail->bandsaw_detail_id = $det['bandsaw_detail_id'];
                    }
                }
                // echo '<a class="btn btn-xs red btn-remove-pjg" style="margin-top: 5px;" onclick="removePjg(this, '.$n.', '.$i.', '.$p.');"><i class="fa fa-times"></i></a>';
                echo yii\helpers\Html::activeHiddenInput($modDetail, '['.$n.']['.$i.']['.$p.']bandsaw_detail_id');
                ?>
            </div>
        <?php } ?>
            <div>
                <?php
                echo '<a class="btn btn-xs blue-hoki btn-outline btn-add-pjg" style="margin-top: 5px;" onclick="addPjg(this, '.$n.', '.$i.', '.$p.');"><i class="fa fa-plus"></i></a>';
                ?>
            </div>
    </td>
    <td>
        <?php 
        foreach ($listPanjang as $p => $pjg){ ?>
            <div class="place-jml-<?= $n ?>-<?= $i ?>-<?= $p ?>" style="display: flex; align-items: center; gap: 5px; margin-bottom: 3px;">
                <?php
                if(!empty($bandsaw_id)){
                    $query = "SELECT qty FROM t_bandsaw_detail WHERE bandsaw_id = $bandsaw_id AND produk_t = $modSpk->produk_t
                                AND produk_l = $modSpk->produk_l AND produk_p = $pjg AND nomor_bandsaw = '$noban'";
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
                echo yii\bootstrap\Html::activeTextInput($modDetail, '['.$n.']['.$i.']['.$p.']jml', ['class' => 'form-control', 'style' => 'width:60px; font-size:1.2rem; color: blue;', 'disabled' => '']);
                echo yii\bootstrap\Html::activeTextInput($modDetail, '['.$n.']['.$i.']['.$p.']qty', ['class' => 'form-control', 'style' => 'width:60px; font-size:1.2rem; text-align: right; color: green;', 'disabled' => '']);
                echo '<center><a class="btn btn-xs red btn-remove" onclick="remove(this, '.$n.', '.$i.', '.$p.');"><i class="fa fa-minus"></i></a></center>';
                echo yii\bootstrap\Html::activeTextInput($modDetail, '['.$n.']['.$i.']['.$p.']qty2', ['class' => 'form-control', 'style' => 'width:60px; font-size:1.2rem; text-align: right; color: red; font-weight: bold;', 'disabled' => '']);
                ?>
            </div>
        <?php } ?>
    </td>
</tr>

