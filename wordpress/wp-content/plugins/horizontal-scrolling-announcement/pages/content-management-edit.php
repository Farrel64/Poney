<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<div class="wrap">
<?php
$did = isset($_GET['did']) ? $_GET['did'] : '0';

// First check if ID exist with requested ID
$sSql = $wpdb->prepare(
	"SELECT COUNT(*) AS `count` FROM ".WP_HSA_TABLE."
	WHERE `hsa_id` = %d",
	array($did)
);
$result = '0';
$result = $wpdb->get_var($sSql);

if ($result != '1')
{
	?><div class="error fade"><p><strong>Oops, selected details doesn't exist.</strong></p></div><?php
}
else
{
	$hsa_errors = array();
	$hsa_success = '';
	$hsa_error_found = FALSE;
	
	$sSql = $wpdb->prepare("
		SELECT *
		FROM `".WP_HSA_TABLE."`
		WHERE `hsa_id` = %d
		LIMIT 1
		",
		array($did)
	);
	$data = array();
	$data = $wpdb->get_row($sSql, ARRAY_A);
	
	// Preset the form fields
	$form = array(
		'hsa_id' => $data['hsa_id'],
		'hsa_text' => $data['hsa_text'],
		'hsa_order' => $data['hsa_order'],
		'hsa_status' => $data['hsa_status'],
		'hsa_link' => $data['hsa_link'],
		'hsa_group' => $data['hsa_group'],
		'hsa_dateend' => $data['hsa_dateend'],
		'hsa_datestart' => $data['hsa_datestart'],
		'hsa_target' => $data['hsa_target']
	);
}
// Form submitted, check the data
if (isset($_POST['hsa_form_submit']) && $_POST['hsa_form_submit'] == 'yes')
{
	//	Just security thingy that wordpress offers us
	check_admin_referer('hsa_form_edit');
	
	$form['hsa_text'] = isset($_POST['hsa_text']) ? $_POST['hsa_text'] : '';
	if ($form['hsa_text'] == '')
	{
		$hsa_errors[] = __('Please enter the text.', WP_hsa_UNIQUE_NAME);
		$hsa_error_found = TRUE;
	}

	$form['hsa_order'] = isset($_POST['hsa_order']) ? $_POST['hsa_order'] : '';
	if ($form['hsa_order'] == '')
	{
		$hsa_errors[] = __('Please enter the display order, only number.', WP_hsa_UNIQUE_NAME);
		$hsa_error_found = TRUE;
	}

	$form['hsa_status'] = isset($_POST['hsa_status']) ? $_POST['hsa_status'] : '';
	if ($form['hsa_status'] == '')
	{
		$hsa_errors[] = __('Please select the display status.', WP_hsa_UNIQUE_NAME);
		$hsa_error_found = TRUE;
	}
	
	$form['hsa_link'] = isset($_POST['hsa_link']) ? $_POST['hsa_link'] : '';
	$form['hsa_group'] = isset($_POST['hsa_group']) ? $_POST['hsa_group'] : '';
	$form['hsa_dateend'] = isset($_POST['hsa_dateend']) ? $_POST['hsa_dateend'] : '0000-00-00';
	$form['hsa_datestart'] = isset($_POST['hsa_datestart']) ? $_POST['hsa_datestart'] : '0000-00-00';
	$form['hsa_target'] = isset($_POST['hsa_target']) ? $_POST['hsa_target'] : '_self';

	//	No errors found, we can add this Group to the table
	if ($hsa_error_found == FALSE)
	{	
		$sSql = $wpdb->prepare(
				"UPDATE `".WP_HSA_TABLE."`
				SET `hsa_text` = %s,
				`hsa_order` = %s,
				`hsa_status` = %s,
				`hsa_link` = %s,
				`hsa_group` = %s,
				`hsa_dateend` = %s,
				`hsa_datestart` = %s,
				`hsa_target` = %s
				WHERE hsa_id = %d
				LIMIT 1",
				array($form['hsa_text'], $form['hsa_order'], $form['hsa_status'], $form['hsa_link'], $form['hsa_group'], 
					$form['hsa_dateend'], $form['hsa_datestart'], $form['hsa_target'], $did)
			);
		$wpdb->query($sSql);
		
		$hsa_success = 'Details was successfully updated.';
	}
}

if ($hsa_error_found == TRUE && isset($hsa_errors[0]) == TRUE)
{
	?>
	<div class="error fade">
		<p><strong><?php echo $hsa_errors[0]; ?></strong></p>
	</div>
	<?php
}
if ($hsa_error_found == FALSE && strlen($hsa_success) > 0)
{
	?>
	<div class="updated fade">
		<p><strong><?php echo $hsa_success; ?> 
		<a href="<?php echo get_option('siteurl'); ?>/wp-admin/options-general.php?page=horizontal-scrolling-announcement">Click here</a> to view the details</strong></p>
	</div>
	<?php
}
?>
<script language="JavaScript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/horizontal-scrolling-announcement/pages/setting.js"></script>
<div class="form-wrap">
	<div id="icon-edit" class="icon32 icon32-posts-post"><br></div>
	<h2><?php _e(WP_hsa_TITLE, WP_hsa_UNIQUE_NAME); ?></h2>
	<form name="form_hsa" method="post" action="#" onsubmit="return hsa_submit()"  >
    <h3><?php _e('Update details', WP_hsa_UNIQUE_NAME); ?></h3>
      
	<label for="tag-title"><?php _e('Enter the announcement', WP_hsa_UNIQUE_NAME); ?></label>
	<textarea name="hsa_text" cols="80" rows="6" id="hsa_text"><?php echo esc_html(stripslashes($form['hsa_text'])); ?></textarea>
	<p><?php _e('Please enter your announcement text.', WP_hsa_UNIQUE_NAME); ?></p>
	
	<label for="tag-title"><?php _e('Enter target link', WP_hsa_UNIQUE_NAME); ?></label>
	<input name="hsa_link" type="text" id="hsa_link" size="82" value="<?php echo esc_html(stripslashes($form['hsa_link'])); ?>" maxlength="1024" />
	<p><?php _e('When someone clicks on the announcement, where do you want to send them. URL must start with either http or https.', WP_hsa_UNIQUE_NAME); ?></p>
	
	<label for="tag-target"><?php _e('Select target option', WP_hsa_UNIQUE_NAME); ?></label>
	<select name="hsa_target" id="hsa_target">
		<option value='_self' <?php if($form['hsa_target'] == '_self') { echo "selected='selected'" ; } ?>>Open in same window</option>
		<option value='_blank' <?php if($form['hsa_target'] == '_blank') { echo "selected='selected'" ; } ?>>Open in new window</option>
	</select>
	<p><?php _e('Do you want to open link in new window?', WP_hsa_UNIQUE_NAME); ?></p>
	
	<label for="tag-title"><?php _e('Display status', WP_hsa_UNIQUE_NAME); ?></label>
	<select name="hsa_status" id="hsa_status">
		<option value='YES' <?php if($form['hsa_status'] == 'YES') { echo "selected='selected'" ; } ?>>Yes</option>
		<option value='NO' <?php if($form['hsa_status'] == 'NO') { echo "selected='selected'" ; } ?>>No</option>
	</select>
	<p><?php __('Do you want to show this announcement in your scroll?', WP_hsa_UNIQUE_NAME); ?></p>
	
	<label for="tag-title"><?php _e('Display order', WP_hsa_UNIQUE_NAME); ?></label>
	<input name="hsa_order" type="text" id="hsa_order" value="<?php echo $form['hsa_order']; ?>" maxlength="3" />
	<p><?php _e('What order should this announcement be played in. should it come 1st, 2nd, 3rd, etc..', WP_hsa_UNIQUE_NAME); ?></p>
	
	<label for="tag-title"><?php _e('Announcement group', WP_hsa_UNIQUE_NAME); ?></label>
	<select name="hsa_group" id="hsa_group">
	<option value='Select'>Select</option>
	<?php
	$sSql = "SELECT distinct(hsa_group) as hsa_group FROM `".WP_HSA_TABLE."` order by hsa_group";
	$thisselected = "";
	$myDistinctData = array();
	$arrDistinctDatas = array();
	$myDistinctData = $wpdb->get_results($sSql, ARRAY_A);
	$i = 0;
	foreach ($myDistinctData as $DistinctData)
	{
		$arrDistinctData[$i]["hsa_group"] = strtoupper($DistinctData['hsa_group']);
		$i = $i+1;
	}
	for($j=$i; $j<$i+5; $j++)
	{
		$arrDistinctData[$j]["hsa_group"] = "GROUP" . $j;
	}
	$arrDistinctDatas = array_unique($arrDistinctData, SORT_REGULAR);
	foreach ($arrDistinctDatas as $arrDistinct)
	{
		if(strtoupper($form['hsa_group']) == strtoupper($arrDistinct["hsa_group"])) 
		{ 
			$thisselected = "selected='selected'" ; 
		}
		?><option value='<?php echo strtoupper($arrDistinct["hsa_group"]); ?>' <?php echo $thisselected; ?>><?php echo strtoupper($arrDistinct["hsa_group"]); ?></option><?php
		$thisselected = "";
	}
	?>
	</select>
	<p><?php _e('Please select your announcement group.', WP_hsa_UNIQUE_NAME); ?></p>
	
	<label for="tag-title"><?php _e('Start date', WP_hsa_UNIQUE_NAME); ?></label>
	<input name="hsa_datestart" type="text" id="hsa_datestart" value="<?php echo substr($form['hsa_datestart'],0,10); ?>" maxlength="10" />
	<p><?php _e('Please enter announcement display start date in this format YYYY-MM-DD <br /> 0000-00-00 : Is equal to no start date.', WP_hsa_UNIQUE_NAME); ?></p>
	
	<label for="tag-title"><?php _e('Expiration date', WP_hsa_UNIQUE_NAME); ?></label>
	<input name="hsa_dateend" type="text" id="hsa_dateend" value="<?php echo substr($form['hsa_dateend'],0,10); ?>" maxlength="10" />
	<p><?php _e('Please enter the expiration date in this format YYYY-MM-DD <br /> 9999-12-31 : Is equal to no expire.', WP_hsa_UNIQUE_NAME); ?></p>
	  
	<input name="hsa_id" id="hsa_id" type="hidden" value="<?php echo $form['hsa_id']; ?>">
	<input type="hidden" name="hsa_form_submit" value="yes"/>
	<p class="submit">
		<input name="publish" lang="publish" class="button add-new-h2" value="Submit" type="submit" />
		<input name="publish" lang="publish" class="button add-new-h2" onclick="hsa_redirect()" value="Cancel" type="button" />
		<input name="Help" lang="publish" class="button add-new-h2" onclick="hsa_help()" value="<?php _e('Help', WP_hsa_UNIQUE_NAME); ?>" type="button" />
	</p>
	<?php wp_nonce_field('hsa_form_edit'); ?>
    </form>
</div>
<p class="description"><?php echo WP_hsa_LINK; ?></p>
</div>