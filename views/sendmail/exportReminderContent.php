<p>
    Kepada Yth,<br>
    Bapak/Ibu Pimpinan PT. Cipta Wijaya Mandiri<br>
    Dengan hormat,
</p>
<center style="font-size: 1.2rem; color: #7C7C7C"><b>Pemberitahuan Ke : <?= $params['xkali'] ?></b></center><br>
<table style="width: 100%;">
    <tr>
    </tr>
</table>
<p style="margin-bottom: 0px;">Kami Informasikan bahwa Penjualan Export :</p>
<table style="width: 100%; margin-left: 20px;">
    <tr>
        <td style="width: 120px;">A/n Buyer</td>
        <td style="width: 10px;">:</td>
        <td><b><?= $params['xd']['customer_nm'] ?></b></td>
    </tr>
    <tr>
        <td>Invoice No.</td>
        <td>:</td>
        <td><b><?= $params['xd']['customer_invoice'] ?></b></td>
    </tr>
    <tr>
        <td>Tanggal Stuffing</td>
        <td>:</td>
        <td><b><?= $params['xd']['staffing'] ?></b></td>
    </tr>
    <tr>
        <td>ETD</td>
        <td>:</td>
        <td><b><?= $params['xd']['etd'] ?></b></td>
    </tr>
    <tr>
        <td>Amount</td>
        <td>:</td>
        <td><b><?= app\components\DeltaFormatter::formatNumberForUserFloat($params['xd']['customer_invoice_jml'])." USD" ?></b></td>
    </tr>
    <tr>
        <td>Rencana Bayar</td>
        <td>:</td>
        <td><b><?= $params['xd']['bayar'] ?></b></td>
    </tr>
    <tr>
        <td>Jenis Dokumen</td>
        <td>:</td>
        <td><b><?= $params['xd']['emaildoc_jns'] ?></b></td>
    </tr>
</table>
<?php
echo "<span style='padding-left: 23px;'>".
        (!empty($params['DocKet'])?$params['DocKet']."<br>":"").
        (!empty($params['Keterlambatan'])?$params['Keterlambatan']."<br>":"").
        (!empty($params['Dpmasuk'])?$params['Dpmasuk']."<br>":"").
    "<span>";
?>
Untuk itu agar segara dilakukan tindaklanjut (follow-up) mengenai : <br><u><strong> <?= $params['a']['alert_nm'] ?></strong></u><br><br>
Demikian informasi yang dapat kami sampaikan, agar dapat dimengerti dan segera ditindaklanjuti.<br>
Terima kasih, <br>
<b>IT CIPTANA</b><br>		
<center><small><i>(Mohon tidak membalas email ini.)</i></small></center>