<select id="TPmrDetail_<?php echo $baris;?>_kayu_id" class="form-control select2" name="TPmrDetail<?php echo $baris;?>[kayu_id]" style="width:100%; padding: 1px; height:25px;" val="">
<?php
foreach ($model as $value) {
    $kayu_id = $value['kayu_id'];
    $kayu_nama = $value['kayu_nama'];
?>
    <option value="<?php echo $kayu_id;?>"><?php echo $kayu_nama;?></option>
<?php
}
?>
</select>
