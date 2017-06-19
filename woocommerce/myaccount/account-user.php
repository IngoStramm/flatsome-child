<div class="account-user circle">
	<?php 
	 	$current_user = wp_get_current_user();
	 	$user_id = $current_user->ID;
		//echo get_avatar( $user_id, 70 );
	?>
	<span class="user-name inline-block">
		<strong><?php echo $current_user->display_name; ?></strong>
		<?php /* ?><em class="user-id op-5"><?php echo '#'.$user_id;?></em><?php */ ?>
	</span>

	<?php do_action('flatsome_after_account_user'); ?>
</div>