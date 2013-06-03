<table class="mainTable padTable" border="0" cellspacing="0" cellpadding="0"><!-- Start of docs table -->

<tbody>

<?php
$i = 0;

$style = ($i % 2) ? 'even' : 'odd';
?>

<tr class="<?=$style?>">
<td style="width: 40%;"><strong><?=lang('author')?></strong></td>
<td style="width: 60%;"><?=$author?></td>
</tr>

<?php
$i++;

$style = ($i % 2) ? 'even' : 'odd';
?>

<tr class="<?=$style?>">
<td style="width: 40%;"><strong><?=lang('author_url')?></strong></td>
<td style="width: 60%;"><a href="<?=$author_url?>"><?=$author_url?></a></td>
</tr>

<?php
$i++;

$style = ($i % 2) ? 'even' : 'odd';
?>

<tr class="<?=$style?>">
<td style="width: 40%;"><strong><?=lang('description')?></strong></td>
<td style="width: 60%;"><?=$description?></td>
</tr>

<?php
$i++;

$style = ($i % 2) ? 'even' : 'odd';
?>

<tr class="<?=$style?>">
<td style="width: 40%;"><strong><?=lang('usage')?></strong></td>
<td style="width: 60%;"><?=$usage?></td>
</tr>

</table><!-- End of docs table -->