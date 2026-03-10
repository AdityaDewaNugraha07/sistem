<?php
echo "<br>jp = ".$jp;
?>



<?php
/*

                                                        <label id="label_tanggalx">Tanggal Penetapan Harga : </label>
                                                        <input id="tanggalx" name="tanggalx" class="md-col-3 form-control" onkeydown="return false">

<?php
isset($jp) ? $produk_group = $jp : $produk_group = $model->produk_group;

$sql_tanggalx = "select distinct(to_char(a.harga_tanggal_penetapan, 'YYYY-MM-DD')) as harga_tanggal_penetapan ".
                    "   from h_harga_produk a ".
                    "   left join m_brg_produk b on b.produk_id = a.produk_id ".
                    "   where a.status_approval = 'Not Confirmed' ".
                    "   or a.status_approval = 'REJECTED' ".
                    "   or a.status_approval = 'APPROVED' ".
                    "   and b.produk_group = '".$produk_group."' ".
                    "   "; echo $sql_tanggalx;
$query_tanggalx = Yii::$app->db->createCommand($sql_tanggalx)->queryAll();

$i = 0;
$len = count($query_tanggalx);
$today = date('Y-m-d');
$tanggalx = "['".$today."',";

foreach ($query_tanggalx as $key) {
    
    if ($i == 0) {
        $tanggalx .= "'".$key['harga_tanggal_penetapan']."',";
    } else if ($i == $len - 1) {
        $tanggalx .= "'".$key['harga_tanggal_penetapan']."'";
    }  else {
        $tanggalx .= "'".$key['harga_tanggal_penetapan']."',";
    }
    $i++;
}

$tanggalx .= "]";

$this->registerJs("
    var datesForDisable = $tanggalx;
    $('#tanggalx').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        todayHighlight: true,
        datesDisabled: datesForDisable,
        startDate: '2020-03-12'
    })
", yii\web\View::POS_READY); 
?>

<?php
$this->registerCss("
#table-pricelist thead tr th{
    text-align : center;
}
");
?>
<script>
*/?>