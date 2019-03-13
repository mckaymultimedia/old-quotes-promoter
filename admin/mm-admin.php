<?php
/*  Copyright 2015  Blog Traffic Exchange

Old Quote Promoter is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 2 of the License, or any later version.
 
Old Quote Promoter is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See th GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License along with Old Quote Promoter. If not, see {License URI}.
*/

function mm_opp_menu_setup() {
add_menu_page (
    'OldQuotePromoter',
    'OQP Settings',
    'manage_options',
    'old-quote-promoter',
    'mm_opp_admin_form',
    MMICONURL //,'1'
);
}

function mm_opp_optionselected($opValue, $value) {
	if($opValue==$value) {
		return 'selected="selected"';
	}
	return '';
}

function mm_opp_get_options() {
	global $mm_opp_interval;
	$mm_opp_interval = get_option('mm_opp_interval');		
	if (!(isset($mm_opp_interval) && is_numeric($mm_opp_interval))) {
		$mm_opp_interval = 1;
	}	
}

function mm_opp_admin_form() {
echo('<link rel="stylesheet" href="' . MMCSS . '" type="text/css" media="screen" />');
	$message = null;
	if (!empty($_POST['mm_opp_action'])) {
		if (isset($_POST['mm_opp_interval'])) {
			update_option('mm_opp_interval',$_POST['mm_opp_interval']);
		}
		if (isset($_POST['mm_opp_interval_slop'])) {
			update_option('mm_opp_interval_slop',$_POST['mm_opp_interval_slop']);
		}
		if (isset($_POST['mm_opp_age_limit'])) {
			update_option('mm_opp_age_limit',$_POST['mm_opp_age_limit']);
		}
		if (isset($_POST['mm_opp_show_original_pubdate'])) {
			update_option('mm_opp_show_original_pubdate',$_POST['mm_opp_show_original_pubdate']);
		}
		if (isset($_POST['mm_opp_give_credit'])) {
			update_option('mm_opp_give_credit',$_POST['mm_opp_give_credit']);
		}
		if (isset($_POST['mm_opp_pos'])) {
			update_option('mm_opp_pos',$_POST['mm_opp_pos']);
		}
		if (isset($_POST['mm_opp_at_top'])) {
			update_option('mm_opp_at_top',$_POST['mm_opp_at_top']);
		}
		if (isset($_POST['quote_cat'])) {
			update_option('mm_opp_omit_cats',implode(',',$_POST['quote_cat']));
		}
		else {
			update_option('mm_opp_omit_cats','');			
		}
	}
 
	$mm_opp_interval = get_option('mm_opp_interval');
	if (!(isset($mm_opp_interval) && is_numeric($mm_opp_interval))) {
		$mm_opp_interval = MMHOUR1;
	}	
	$mm_opp_interval_slop = get_option('mm_opp_interval_slop');
	if (!(isset($mm_opp_interval_slop) && is_numeric($mm_opp_interval_slop))) {
		$mm_opp_interval_slop = MMHOUR4;
	}	
	$mm_opp_age_limit = get_option('mm_opp_age_limit');
	if (!(isset($mm_opp_age_limit) && is_numeric($mm_opp_age_limit))) {
		$mm_opp_age_limit = MMAGELIMIT;
	}	
	$mm_opp_pos = get_option('mm_opp_pos');
	if (!(isset($mm_opp_pos) && is_numeric($mm_opp_pos))) {
		$mm_opp_pos = 1;
	}	
	$mm_opp_show_original_pubdate = get_option('mm_opp_show_original_pubdate');
	if (!(isset($mm_opp_show_original_pubdate) && is_numeric($mm_opp_show_original_pubdate))) {
		$mm_opp_show_original_pubdate = 1;
	}
	$mm_opp_at_top = get_option('mm_opp_at_top');
	if (!(isset($mm_opp_at_top) && is_numeric($mm_opp_at_top))) {
		$mm_opp_at_top = 1;
	}	
	$mm_opp_omit_cats = get_option('mm_opp_omit_cats');
	if (!isset($mm_opp_omit_cats)) {
		$mm_opp_omit_cats = MMOMITCATS;
	}
	$mm_opp_give_credit = get_option('mm_opp_give_credit');
	if (!isset($mm_opp_give_credit)) {
		$mm_opp_give_credit = 1;
	}
	
?>
<div class="wrap">
	<? if (!empty($_POST['mm_opp_action'])) {
		print('<div id="message" class="updated fade"><p>'.__('OPP settings successfully updated.', 'OldQuotePromoter').'</p></div>');
	} ?>
	<div class="kc_head">
  <h1>Old Quote Promoter (OPP) Settings</h1>
		<span>Created by <a href="#"><b>Blog Traffic Exchange</b></a></span>
	</div>
	<div class="kc_head kc_top">
		<form id="mm_opp" name="mm_opp_form" action="<?=get_bloginfo('wpurl')?>/wp-admin/admin.php?page=old-quote-promoter" method="post">
			<input type="hidden" name="mm_opp_action" value="mm_opp_update_settings" />
			<table cellpadding="10" cellspacing="0" border="0">
				<tr><td><label for="mm_opp_interval">Minimum Interval between Old Quote Promotions</label></td>
					<td><select name="mm_opp_interval" id="mm_opp_interval">
						<option value="<? echo MMMINUTE15;?>" <?=mm_opp_optionselected(MMMINUTE15,$mm_opp_interval);?>><? echo __('15 Minutes', 'old-quote-promoter');?></option>
						<option value="<? echo MMMINUTE30;?>" <?=mm_opp_optionselected(MMMINUTE30,$mm_opp_interval);?>><? echo __('30 Minutes', 'old-quote-promoter');?></option>
						<option value="<? echo MMHOUR1;?>" <?=mm_opp_optionselected(MMHOUR1,$mm_opp_interval);?>><? echo __('1 Hour', 'old-quote-promoter');?></option>
						<option value="<? echo MMHOUR4;?>" <?=mm_opp_optionselected(MMHOUR4,$mm_opp_interval);?>><? echo __('4 Hours', 'old-quote-promoter');?></option>
						<option value="<? echo MMHOUR6;?>" <?=mm_opp_optionselected(MMHOUR6,$mm_opp_interval);?>><? echo __('6 Hours', 'old-quote-promoter');?></option>
						<option value="<? echo MMHOUR12;?>" <?=mm_opp_optionselected(MMHOUR12,$mm_opp_interval);?>><? echo __('12 Hours', 'old-quote-promoter');?></option>
						<option value="<? echo MMHOUR24;?>" <?=mm_opp_optionselected(MMHOUR24,$mm_opp_interval);?>><? echo __('24 Hours (1 Day)', 'old-quote-promoter');?></option>
						<option value="<? echo MMHOUR48;?>" <?=mm_opp_optionselected(MMHOUR48,$mm_opp_interval);?>><? echo __('48 Hours (2 Days)', 'old-quote-promoter');?></option>
						<option value="<? echo MMHOUR72;?>" <?=mm_opp_optionselected(MMHOUR72,$mm_opp_interval);?>><? echo __('72 Hours (3 Days)', 'old-quote-promoter');?></option>
						<option value="<? echo MMHOUR168;?>" <?=mm_opp_optionselected(MMHOUR168,$mm_opp_interval);?>><? echo __('168 Hours (7 Days)', 'old-quote-promoter');?></option>
					</select></td>				
				</tr>
				<tr><td><label for="mm_opp_interval_slop">Randomness Interval<br><small style="color:#999">(Added to minimum interval)</span></label></td>
					<td><select name="mm_opp_interval_slop" id="mm_opp_interval_slop">
						<option value="<? echo MMHOUR1;?>" <?=mm_opp_optionselected(MMHOUR1,$mm_opp_interval_slop);?>><? echo __('1 Hour', 'old-quote-promoter');?></option>
						<option value="<? echo MMHOUR4;?>" <?=mm_opp_optionselected(MMHOUR4,$mm_opp_interval_slop);?>><? echo __('4 Hours', 'old-quote-promoter');?></option>
						<option value="<? echo MMHOUR6;?>" <?=mm_opp_optionselected(MMHOUR6,$mm_opp_interval_slop);?>><? echo __('6 Hours', 'old-quote-promoter');?></option>
						<option value="<? echo MMHOUR12;?>" <?=mm_opp_optionselected(MMHOUR12,$mm_opp_interval_slop);?>><? echo __('12 Hours', 'old-quote-promoter');?></option>
						<option value="<? echo MMHOUR24;?>" <?=mm_opp_optionselected(MMHOUR24,$mm_opp_interval_slop);?>><? echo __('24 Hours (1 Day)', 'old-quote-promoter');?></option>
					</select></td>
				</tr>
				<tr><td><label for="mm_opp_age_limit">Quote Age before eligible for promotion</label></td>
					<td><select name="mm_opp_age_limit" id="mm_opp_age_limit">
						<option value="30" <?=mm_opp_optionselected(30,$mm_opp_age_limit);?>><? echo __('30 Days', 'old-quote-promoter');?></option>
						<option value="60" <?=mm_opp_optionselected(60,$mm_opp_age_limit);?>><? echo __('60 Days', 'old-quote-promoter');?></option>
						<option value="90" <?=mm_opp_optionselected(90,$mm_opp_age_limit);?>><? echo __('90 Days', 'old-quote-promoter');?></option>
						<option value="120" <?=mm_opp_optionselected(120,$mm_opp_age_limit);?>><? echo __('120 Days', 'old-quote-promoter');?></option>
						<option value="240" <?=mm_opp_optionselected(240,$mm_opp_age_limit);?>><? echo __('240 Days', 'old-quote-promoter');?></option>
						<option value="365" <?=mm_opp_optionselected(365,$mm_opp_age_limit);?>><? echo __('365 Days', 'old-quote-promoter');?></option>
						<option value="730" <?=mm_opp_optionselected(730,$mm_opp_age_limit);?>><? echo __('730 Days', 'old-quote-promoter');?></option>					
					</select></td>				
				</tr>
				<tr><td><label for="mm_opp_pos">Promote Quote to Position<br><small style="color:#999">(Choosing the 2nd position will leave the most recent post in 1st place)</small></label></td>
					<td><select name="mm_opp_pos" id="mm_opp_pos">
						<option value="1" <?=mm_opp_optionselected(1,$mm_opp_pos);?>><? echo __('1st Position', 'old-quote-promoter');?></option>
						<option value="2" <?=mm_opp_optionselected(2,$mm_opp_pos);?>><? echo __('2nd Position', 'old-quote-promoter');?></option>
					</select></td>				
				</tr>
				<tr><td><label for="mm_opp_show_original_pubdate">Show Original Publication Date on the Quote</label></td>
					<td><select name="mm_opp_show_original_pubdate" id="mm_opp_show_original_pubdate">
						<option value="1" <?=mm_opp_optionselected(1,$mm_opp_show_original_pubdate);?>><? echo __('Yes', 'old-quote-promoter');?></option>
						<option value="0" <?=mm_opp_optionselected(0,$mm_opp_show_original_pubdate);?>><? echo __('No', 'old-quote-promoter');?></option>
					</select></td>				
				</tr>				
				<tr><td><label for="mm_opp_at_top">Show Original Publication Date at Top of the Quote</label></td>
					<td><select name="mm_opp_at_top" id="mm_opp_at_top">
						<option value="1" <?=mm_opp_optionselected(1,$mm_opp_at_top);?>><? echo __('Yes', 'old-quote-promoter');?></option>
						<option value="0" <?=mm_opp_optionselected(0,$mm_opp_at_top);?>><? echo __('No', 'old-quote-promoter');?></option>
					</select></td>				
				</tr>
				<tr><td valign="top"><label for="mm_opp_omit_cats">Categories to Omit from Promotion</label></td>
					<td><ul><? wp_category_checklist(0, 0, explode(',',$mm_opp_omit_cats)); ?></ul></td>
				</tr>
				<tr><td><label for="mm_opp_give_credit">Give OPP Credit with Link</label></td>
					<td><select name="mm_opp_give_credit" id="mm_opp_give_credit">
						<option value="1" <?=mm_opp_optionselected(1,$mm_opp_give_credit);?>><? echo __('Yes', 'old-quote-promoter');?></option>
				  </select></td>				
			  </tr>
				
				<tr><td>&nbsp;</td><td><input type="submit" name="submit" value="Update OQP Settings" /></td></tr>
			</table>
		</form>
	</div>
	<div class="kc_head kc_top">
		<h3>Other  <a href="http://www.blogtrafficexchange.com/wordpress-plugins/">Wordpress Plugins</a> Coming up from Blog Traffic Exchange</h3>
		<ol>
		  <li><a href="http://www.blogtrafficexchange.com/related-websites">Related Websites</a></li>
			<li><a href="http://www.blogtrafficexchange.com/related-tweets/">Related Tweets</a></li>
			<li><a href="http://www.blogtrafficexchange.com/wordpress-backup/">Wordpress Backup</a></li>
			<li><a href="http://www.blogtrafficexchange.com/blog-copyright">Blog Copyright</a></li>
			<li><a href="http://www.blogtrafficexchange.com/related-posts">Related Posts</a></li>
	  </ol>
  </div>
</div>
<? } 

function mm_opp_admin_head() {
	//echo('<link rel="stylesheet" href="' . MMCSS . '" type="text/css" media="screen" />');
}
?>
