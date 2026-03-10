<?php
/* @var $this yii\web\View */
app\assets\DatatableAsset::register($this);
app\assets\DatepickerAsset::register($this);
?>

<style>
    table {border: solid 1px;}
    th {border: solid 1px;}
    td {border: solid 1px;}
</style>

<table style="width: 750px;">
    <th class='col-md-3 text-left' style='width: 230px;'>Nama</th>
    <th class='col-md-3 text-left' style='width: 200px;'>Department</th>
    <th class='col-md-3 text-left' style='width: 160px;'>Jabatan</th>
    <th class='col-md-3 text-left' style='width: 160px;'>Username</th>        
<?php
foreach ($model as $key => $value) {
    $pegawai_nama = $value['pegawai_nama'];
    $departement_nama = $value['departement_nama'];
    $jabatan_nama = $value['jabatan_nama'];
    $username = $value['username'];
?>
    <tr>
        <td><?php echo $pegawai_nama;?></td>
        <td><?php echo $departement_nama;?></td>
        <td><?php echo $jabatan_nama;?></td>
        <td><?php echo $username;?></td>
    </tr>
<?php
}
?>
</table>
