<?=form_open($action_url)?>

<input type="hidden" name="<?=TRUE_PREVIEW_ID?>_update_prefs" value="y" />

<?php
$checked_yes = ($display_channel_title == 'y') ? 'checked="checked"' : '';
$checked_no = ($display_channel_title == 'y') ? '' : 'checked="checked"';
?>

<table class="mainTable padTable" border="0" cellspacing="0" cellpadding="0"><!-- Start of channel title question table -->
<tr class="odd">
<td style="width: 40%;">
<?=lang('display_channel_title_question')?>
</td>
<td>
<?=lang('yes')?>&nbsp;&nbsp;<input type="radio" value="y" <?=$checked_yes?> name="display_channel_title">&nbsp;&nbsp;&nbsp;&nbsp;<?=lang('no')?>&nbsp;&nbsp;<input type="radio" value="n" <?=$checked_no?> name="display_channel_title">
</td style="width: 60%;">
</tr>
</table><!-- End of channel title question table -->

<table class="mainTable padTable" border="0" cellspacing="0" cellpadding="0"><!-- Start of Settings table -->

<thead><!-- Table header -->
<tr>
<th style="width: 40%;"><?=lang('channel_heading')?></th>
<th style="width: 60%;"><?=lang('template_heading')?></th>
</tr>
</thead>

<?php
$i = 0;
for ($j = 0; $j < count($channels); $j++)
{
$style = ($i % 2) ? 'even' : 'odd';
?>

<tr class="<?=$style?>">
<td><?=$channels[$j]['channel_title']?></td>
<td><input type="text" name="template_group_and_name_channel_<?=$channels[$j]['channel_id']?>" value="<?=$channels[$j]['template']?>" maxlength="50" style="width: 98%;" /></td>
</tr>

<?php
$i++;
}
?>

</table><!-- End of Settings table -->

<?=form_submit('submit', lang('submit'), 'class="submit"'); ?>

<?=form_close()?>