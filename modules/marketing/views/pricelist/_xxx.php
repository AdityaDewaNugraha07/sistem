<div id="xxx" style="margin-top: 3px;">
    <a class="btn blue btn-outline" id="btn-update-price" href="#"><i class="fa fa-edit"></i> <?= Yii::t('app',''); ?></a>
    <a class="btn red btn-outline" id="btn-delete" onclick="hapusPricelist();" style=""><i class="fa fa-trash-o"></i> <?= Yii::t('app', ''); ?></a>

    <a class="btn red btn-outline ciptana-spin-btn ladda-button" id="btn-cancel" onclick="unsetUpdateForm()" data-original-title="Batalkan Perubahan" style="display: none;"><i class="fa fa-remove"></i> <?= Yii::t('app', 'Cancel'); ?></a>
    &nbsp;&nbsp;
</div>
<?php
$this->registerJs("
    $('#btn-update-price').click(function(){
        var kodes = $('select option:selected').text();
        var kodes = kodes.replace('(','');
        var kodes = kodes.replace(')','');
        var kode = kodes.split(' ');
        var jp = $('#mbrgproduk-produk_group').val();
        var tp = $('#harga_tanggal_penetapan').val();
        var kode = kode[kode.length - 1];
        window.location = 'edit?jp='+jp+'&tp='+tp+'&kode='+kode;
    });

    var status = $('select option:selected').text();
    var status = status.replace('(','');
    var status = status.replace(')','');
    var sa = status.split(' ');
    var sa = sa[sa.length - 3];
    
    if (sa == 'Confirmed') {
        $('#xxx').show();
    } else {
        $('#xxx').hide();
    }

", yii\web\View::POS_READY); 
?>