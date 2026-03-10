<?php
$ukuranganrange = \app\models\MDefaultValue::getOptionList('log-sengon-panjang');
?>
<div class="modal fade" id="modal-sethargasengon" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Set Harga Log Sengon'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row" style="margin-bottom: 10px;">
                    <?php 
                    foreach($ukuranganrange as $i => $panjang){
                        if($panjang==100){
                    ?>
                        <div class="col-md-4">
                            <h4>Log Sengon Panjang : <?= $panjang ?>cm (Luar Jateng)</h4>
                            <table id="table-<?= $panjang ?>-luar_jateng" class="table table-striped table-bordered table-advance table-hover">
                                <thead>
                                    <th class="td-kecil font-grey-gallery text-align-center" style="width: 60px;">No</th>
                                    <th class="td-kecil font-grey-gallery text-align-center" style="width: 150px;">Range Diameter (cm)</th>
                                    <th class="td-kecil font-grey-gallery text-align-center">Harga / M<sup>3</sup></th>
                                    <th class="td-kecil font-grey-gallery text-align-center" style="width: 50px;"></th>
                                </thead>
                                <tbody></tbody>
                            </table>
                            <a class="btn btn-xs blue" id="btn-add-item" onclick="addItemHarga('<?= $panjang ?>-luar_jateng')"><i class="fa fa-plus"></i> Add Price</a>
                        </div>
                        <div class="col-md-4">
                            <h4>Log Sengon Panjang : <?= $panjang ?>cm (Jateng)</h4>
                            <table id="table-<?= $panjang ?>-jateng" class="table table-striped table-bordered table-advance table-hover">
                                <thead>
                                    <th class="td-kecil font-grey-gallery text-align-center" style="width: 60px;">No</th>
                                    <th class="td-kecil font-grey-gallery text-align-center" style="width: 150px;">Range Diameter (cm)</th>
                                    <th class="td-kecil font-grey-gallery text-align-center">Harga / M<sup>3</sup></th>
                                    <th class="td-kecil font-grey-gallery text-align-center" style="width: 50px;"></th>
                                </thead>
                                <tbody></tbody>
                            </table>
                            <a class="btn btn-xs blue" id="btn-add-item" onclick="addItemHarga('<?= $panjang ?>-jateng')"><i class="fa fa-plus"></i> Add Price</a>
                        </div>
                    <?php }else{ ?>
                        <div class="col-md-4">
                            <h4>Log Sengon Panjang : <?= $panjang ?>cm</h4>
                            <table id="table-<?= $panjang ?>" class="table table-striped table-bordered table-advance table-hover">
                                <thead>
                                    <th class="td-kecil font-grey-gallery text-align-center" style="width: 60px;">No</th>
                                    <th class="td-kecil font-grey-gallery text-align-center" style="width: 150px;">Range Diameter (cm)</th>
                                    <th class="td-kecil font-grey-gallery text-align-center">Harga / M<sup>3</sup></th>
                                    <th class="td-kecil font-grey-gallery text-align-center" style="width: 50px;"></th>
                                </thead>
                                <tbody></tbody>
                            </table>
                            <a class="btn btn-xs blue" id="btn-add-item" onclick="addItemHarga('<?= $panjang ?>')"><i class="fa fa-plus"></i> Add Price</a>
                        </div>
                    <?php } ?>
                    <?php } ?>
                </div>
            </div>
            <div class="modal-footer" style="text-align: left;">
                <!--<center><a class="btn btn-success hijau btn-outline">Set Harga</a></center>-->
                Data source :
                <?= \yii\helpers\Html::hiddenInput('tr_ke',$no_urut) ?>
                <?= \yii\helpers\Html::activeHiddenInput($model, 'diameter_harga',['style'=>'width:100%;','disabled'=>true]) ?>
                <div id="asdasd"></div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php $this->registerJs(" 
	getItemsHargaFromBottom();
", yii\web\View::POS_READY); ?>
<script>
function getItemsHargaFromBottom(){
    var diameter_harga = $("#<?= \yii\helpers\Html::getInputId($model, "diameter_harga") ?>").val();
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/purchasinglog/posengon/getItemsHargaFromBottom']); ?>',
        type   : 'POST',
        data   : {diameter_harga:diameter_harga},
        success: function (data){
            if(data){
                $("#modal-sethargasengon").find('table').each(function(){
                    var tableid = $(this).attr('id');
                    var zxczxc = tableid.split('-');
                    var panjang = zxczxc[1];
                    var wilayah = (zxczxc[2])?(zxczxc[2]):"all";                    
                    $(data).each(function(i,vals){
                        $(vals).each(function(ii,val){
                            if(val.data.panjang == panjang && val.data.wilayah == wilayah){
                                $("#"+tableid+" > tbody").append(val.html);
                            }
                        });
                    });
                    reordertable("#"+tableid);
                }); 
                getItemsHarga();
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function getItemsHarga(){
    var arr = []; var no_urut = $('#modal-sethargasengon').find('input[name="tr_ke"]').val(); var html = "<table style='width:100%'><tr>";
    $("#modal-sethargasengon").find('table').each(function(){
        var tableid = $(this).attr('id'); var subarr = []; var subhtml = '';
        if($("#"+tableid+" > tbody > tr").length > 0){
            subhtml += "<table style='width:100%;'>";
            var zxczxc = tableid.split('-');
            if(zxczxc[2]){ var daerah = zxczxc[2] }else{ var daerah =""; }
            subhtml += "<tr style='line-height:0.8'><td colspan='2' style='font-size:1rem;'><b>"+zxczxc[1]+" cm "+daerah+"</b></td></tr>";
            $("#"+tableid+" > tbody > tr").each(function(){
                subarr.push({
                        'wilayah':$(this).find('input[name*="[wilayah]"]').val(),
                        'panjang':$(this).find('input[name*="[panjang]"]').val(),
                        'diameter_awal':$(this).find('input[name*="[diameter_awal]"]').val(),
                        'diameter_akhir':$(this).find('input[name*="[diameter_akhir]"]').val(),
                        'harga':$(this).find('input[name*="[harga]"]').val()
                });
                subhtml += "<tr style='line-height:0.8'><td style='font-size:1rem;'>"+$(this).find('input[name*="[diameter_awal]"]').val()+" - "+$(this).find('input[name*="[diameter_akhir]"]').val()+" cm</td>";
                subhtml += "    <td style='font-size:1rem;'>Rp. "+(formatNumberForUser($(this).find('input[name*="[harga]"]').val()))+"</td></tr>";
            });
            subhtml += "</table>";
            arr.push( subarr );
            html += '<td style="33%; vertical-align:top">'+subhtml+'</td>';
        }
    });
    arr = JSON.stringify(arr);
    html += '</tr></table>';
    $("#<?= \yii\helpers\Html::getInputId($model, "diameter_harga") ?>").val(arr);
    $("#asdasd").html(html);
    $("#table-detail > tbody > tr").find("input[name='no_urut'][value='"+no_urut+"']").parents('tr').find("input[name*='[diameter_harga]']").val(arr);
    $("#table-detail > tbody > tr").find("input[name='no_urut'][value='"+no_urut+"']").parents('tr').find("#place-diameterharga").html(html);

}

function addItemHarga(par){
    var setharga_source = $("#<?= \yii\helpers\Html::getInputId($model, "diameter_harga") ?>").val();
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/purchasinglog/posengon/addItemHarga']); ?>',
        type   : 'POST',
        data   : {par:par},
        success: function (data){
            if(data.html){
                $("#table-"+par+" > tbody").append(data.html);
                formconfig(); reordertable("#table-"+par); 
                getItemsHarga();
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}
</script>