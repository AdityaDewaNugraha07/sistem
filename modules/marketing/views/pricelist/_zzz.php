<?php
foreach($t_approval as $kolom) {
    $assigned_to = $kolom['assigned_to'];
        $m_pegawai = \app\models\MPegawai::find()->where(['pegawai_id'=>$assigned_to])->one();
            $pegawai_nama = $m_pegawai->pegawai_nama;
    $tanggal_approve = $kolom['tanggal_approve'];
    $updated_at = $kolom['updated_at'];
    $kode = $kolom['reff_no'];
    $status = $kolom['status'];
    if ($status == "APPROVED") {
        $btn_status = "btn-success";
        $updated_at = \app\components\DeltaFormatter::formatDateTimeForUser2($kolom['updated_at']);

        $sql_reasonx = "select approve_reason from m_harga_produk where kode = '".$kode."' ";
        $reasonsx = json_decode(Yii::$app->db->createCommand($sql_reasonx)->queryScalar());
        $approve_reason = '';
        $reject_reason = '';
        foreach ($reasonsx as $reasonx) {
            if ($assigned_to == $reasonx->by) {
                $approve_reason .= $reasonx->reason;
            }
        }
    } else if ($status == "REJECTED") {
        $btn_status = "btn-danger";
        $updated_at = \app\components\DeltaFormatter::formatDateTimeForUser2($kolom['updated_at']);

        $sql_reasonx = "select reject_reason from m_harga_produk where kode = '".$kode."' ";
        $reasonsx = json_decode(Yii::$app->db->createCommand($sql_reasonx)->queryScalar());
        $reject_reason = '';
        $approve_reason = '';
        foreach ($reasonsx as $reasonx) {
            if ($assigned_to == $reasonx->by) {
                $reject_reason .= $reasonx->reason;
            }
        }
    } else {
        $btn_status = 'btn-default';
        $approve_reason = '';
        $reject_reason = '';
    }
    
?>
<button type="button" class="btn <?php echo $btn_status;?> btn-xs" style="font-size: 10px;">
    <?php echo $pegawai_nama;?>
    <br><?php echo $updated_at;?>
    <br><?php echo $approve_reason."".$reject_reason;?>
</button>
<?php
}
?>
