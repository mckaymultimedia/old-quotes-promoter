<?php function mm_opp_activate() {
	add_option('mm_opp_interval',3600);
	add_option('mm_opp_interval_slop',14400);
	add_option('mm_opp_age_limit',30);
	add_option('mm_opp_omit_cats','');
	add_option('mm_opp_show_original_pubdate',1);	
	add_option('mm_opp_pos',0);	
	add_option('mm_opp_give_credit',1);	
	add_option('mm_opp_at_top',0);	
} 

function mm_opp_deactivate() {
	delete_option('mm_opp_interval');
	delete_option('mm_opp_interval_slop');
	delete_option('mm_opp_age_limit');
	delete_option('mm_opp_omit_cats');
	delete_option('mm_opp_show_original_pubdate');
	delete_option('mm_opp_pos');
	delete_option('mm_opp_give_credit');
	delete_option('mm_opp_at_top');
} ?>