
<?php 
/**
 * First div to show logo, organization name, organization statement
 */
?>

<div class="ui-widget">
<div class="ui-widget-content ui-corner-all" style="padding-left: 15px;">
	<h1><?php

	if (isset ( $_SESSION ['url_domain_org'] ['web_site_logo_file_name'] )) {
		echo '<img src="'.$_SESSION ['url_domain_org'] ['web_site_logo_file_name'].'" style="width:70px; float: left;" />&nbsp;';
	}
	if (isset ( $_SESSION ['url_domain_org'] ['name'] )) {
		echo '<a href="/">' . $_SESSION ['url_domain_org'] ['name'] . '</a>';
	} else {
		echo "OWebP";
	}
	?></h1><h3><?php
	if (isset ( $_SESSION ['url_domain_org'] ['statement'] )) {
		echo $_SESSION ['url_domain_org'] ['statement'];
	} else {
		echo "Best Presence on Web";
	}
	?></h3>
</div></div>

<?php 
/**
 * To show the navigation bar
 */
?>
<div class="ui-widget">
<div class="ui-widget-header ui-corner-all jpmHeaderPadding">
	<?php if (isset($_SESSION['user']) && !empty($_SESSION['user'])) { ?>
		<div style="width:100px;float:left;" >
		<ul id="jpm_home_page_menu" class="ui-corner-all jpmContentPadding" >
			<li>Start<?php echo getMenu();?></li>
		</ul>
		</div>
	<?php } ?>

	<?php if (isset($_SESSION['url_domain_org']['web_site_content_type']) 
		&&  $_SESSION['url_domain_org']['web_site_content_type'] == 'Family Tree'
	) { ?>
		<a title="Family Tree" href="/family_tree/present_all">Family Tree</a>
	<?php } else { ?>
		<a title="Our Products and Services" href="/item_catalog/present_all">Catalog</a>
		<a title="Items seleced for purchase" href="/shopping_cart/present_all">Cart</a>
	<?php } ?>

	<?php if ($_SESSION ['allowed_as'] == 'PUBLIC' || $_SESSION ['allowed_as'] == 'NULL') {
		echo '<a title="'. $_SESSION['allowed_as'] . '" href="/user/login">Login</a>';
	} else {
		echo '<a title="'. $_SESSION['allowed_as'] . '" href="/user/logout">Logout</a>';
	} ?>

	<a title="Join and manage you business" href="/user/join">Join</a>

	<?php
	$_SESSION ['login_person_id'] = '';
	if (isset($_SESSION['person']) && isset($_SESSION['person']['_id'])) {
		$_SESSION ['login_person_id'] = ( string ) $_SESSION ['person'] ['_id'];
	}
	if ($_SESSION ['allowed_as'] != 'PUBLIC' && $_SESSION ['allowed_as'] != 'NULL') {
		$personName = '';
		if ($_SESSION['login_person_id'] == '') { 
			/* person account is not set */
		} else {
			$personClass =  new owebp_public_Person();
			$personClass->record = $_SESSION ['person'];
			$personName = $personClass->getFullName('Official');
		}
		echo '<a href="/user/update?id='.(string)$_SESSION ['user']['_id'] 
			.'">Welcome</a>';
		if ($personName != '') {
			echo ' <a href="/person/update?id='.$_SESSION ['login_person_id']
				.'">'.$personClass->getFullName('Official').'</a>';
		}
	}
	?>		
	<a href="/contact/present_all">Contact Us</a>
</div></div><br />


<?php 
/**
 * To show the page content
 */
echo $jpmContent; 
?>
