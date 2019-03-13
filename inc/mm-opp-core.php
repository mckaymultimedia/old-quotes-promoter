<?php
function mm_opp_old_quote_promoter () {
	if (mm_opp_update_time()) {
		update_option('mm_opp_last_update', time());
		mm_opp_promote_old_post();
	}
}

function mm_opp_promote_old_post () {
	global $wpdb;
	$omitCats = get_option('mm_opp_omit_cats');
	$ageLimit = get_option('mm_opp_age_limit');
	if (!isset($omitCats)) {
		$omitCats = MMOMITCATS;
	}
	if (!isset($ageLimit)) {
		$ageLimit = MMAGELIMIT;
	}
	$sql = "SELECT ID
            FROM $wpdb->posts
            WHERE post_type = 'quotes'
                  AND post_status = 'publish'
                  AND post_date < curdate( ) - INTERVAL ".$ageLimit." DAY 
                  ";
    if ($omitCats!='') {
    	$sql = $sql."AND NOT(ID IN (SELECT tr.object_id 
                                    FROM $wpdb->terms  t 
                                          inner join $wpdb->term_taxonomy tax on t.term_id=tax.term_id and tax.taxonomy='quote_cat' 
                                          inner join $wpdb->term_relationships tr on tr.term_taxonomy_id=tax.term_taxonomy_id 
                                    WHERE t.term_id IN (".$omitCats.")))";
    }            
    $sql = $sql."            
            ORDER BY RAND() 
            LIMIT 1 ";
	$oldest_quote = $wpdb->get_var($sql);   
	if (isset($oldest_quote)) {
		mm_opp_update_old_post($oldest_quote);
	}
}

function mm_opp_update_old_post($oldest_quote) {
	global $wpdb;
	$post = get_post($oldest_quote);
	$origPubDate = get_post_meta($oldest_quote, 'mm_opp_original_pub_date', true); 
	if (!(isset($origPubDate) && $origPubDate!='')) {
	    $sql = "SELECT post_date from ".$wpdb->posts." WHERE ID = '$oldest_quote'";
		$origPubDate=$wpdb->get_var($sql);
		add_post_meta($oldest_quote, 'mm_opp_original_pub_date', $origPubDate);
		$origPubDate = get_post_meta($oldest_quote, 'mm_opp_original_pub_date', true); 
	}
	$mm_opp_pos = get_option('mm_opp_pos');
	if (!isset($mm_opp_pos)) {
		$mm_opp_pos = 0;
	}
	if ($mm_opp_pos==1) {
		$new_time = date('Y-m-d H:i:s');
		$gmt_time = get_gmt_from_date($new_time);
	} else {
		$lastposts = get_posts('numberposts=1&offset=1');
		foreach ($lastposts as $lastpost) {
			$post_date = strtotime($lastpost->post_date);
			$new_time = date('Y-m-d H:i:s',mktime(date("H",$post_date),date("i",$post_date),date("s",$post_date)+1,date("m",$post_date),date("d",$post_date),date("Y",$post_date)));
			$gmt_time = get_gmt_from_date($new_time);
		}
	}
	$sql = "UPDATE $wpdb->posts SET post_date = '$new_time',post_date_gmt = '$gmt_time',post_modified = '$new_time',post_modified_gmt = '$gmt_time' WHERE ID = '$oldest_quote'";		
	$wpdb->query($sql);
	if (function_exists('wp_cache_flush')) {
		wp_cache_flush();
	}		
		
	$permalink = get_permalink($oldest_quote);
	
	do_action( 'old_post_promoted', $post ); 
}

function mm_opp_the_content($content) {
	global $post;
	$showPub = get_option('mm_opp_show_original_pubdate');
	if (!isset($showPub)) {
		$showPub = 1;
	}
	$givecredit = get_option('mm_opp_give_credit');
	if (!isset($givecredit)) {
		$givecredit = 1;
	}
	$origPubDate = get_post_meta($post->ID, 'mm_opp_original_pub_date', true);
	$dateline = '';
	if (isset($origPubDate) && $origPubDate!='') {
		if ($showPub || $givecredit) {
			$dateline.='<p id="mm_opp" class="nonexistent hidden"><small>';
			if ($showPub) {
				// $dateline.='Originally posted '.$origPubDate.'. ';
				$dateline.='&nbsp;';
			}
			if ($givecredit) {
				// $dateline.='Republished by  <a href="http://www.blogtrafficexchange.com/">Blog Post Promoter</a>';
				$dateline.='&nbsp;';
			}
			$dateline.='</small></p>';
		}
	}
	$atTop = get_option('mm_opp_at_top');
	if (isset($atTop) && $atTop) {
		$content = $dateline.$content;
	} else {
		$content = $content.$dateline;
	}
	return $content;
}

function mm_opp_update_time () {
	$last = get_option('mm_opp_last_update');		
	$interval = get_option('mm_opp_interval');		
	if (!(isset($interval) && is_numeric($interval))) {
		$interval = MMINTERVAL;
	}
	$slop = get_option('mm_opp_interval_slop');		
	if (!(isset($slop) && is_numeric($slop))) {
		$slop = MMINTERVALSLOP;
	}
	if (false === $last) {
		$ret = 1;
	} else if (is_numeric($last)) { 
		$ret = ( (time() - $last) > ($interval+rand(0,$slop)));
	}
	return $ret;
}
?>