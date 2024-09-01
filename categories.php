<?php
define('_VALID', true);
require 'include/config.php';
require 'include/function_smarty.php';
require 'classes/pagination.class.php';

$s      = ( isset($_GET['s']) && ($_GET['s'] == 'a' or $_GET['s'] == 'g') ) ? $_GET['s'] : '';

if ($s == "a") {
	$sql            = "SELECT CID, name, slug FROM album_categories ORDER BY name ASC";
	$rs             = $conn->execute($sql);
	$categories     = $rs->getrows();

	$sql = "SELECT category FROM albums WHERE status = '1'";
	$rs             = $conn->execute($sql);
	$alb		    = $rs->getrows();

	$cat = array();

	foreach ( $alb as $album ) {
		$cat[$album['category']]++;
	}
	foreach ($categories as $k => $v) {
		$categories[$k]['total'] = 0;
		foreach ($cat as $key => $cat_val) {
			if ($key == $v['CID']) {
				$categories[$k]['total'] = $cat_val;
			}
		}
		$sql            = "UPDATE `album_categories` SET `total_albums`=".$categories[$k]['total']." WHERE CID = ".$categories[$k]['CID']."";
		$rs             = $conn->execute($sql);		
	}
	
} else {
	$sql            = "SELECT count(CHID) AS total_categories FROM channel";
	$rsc            = $conn->execute($sql);
	$total          = $rsc->fields['total_categories'];
	$pagination     = new Pagination($config['categories_per_page']);
	$limit          = $pagination->getLimit($total);
	$sql            = "SELECT CHID, name, slug FROM channel ORDER BY name ASC LIMIT " . $limit;
	$rs             = $conn->execute($sql);
	$categories     = $rs->getrows();

	$sql = "SELECT channel FROM video WHERE active = '1'";
	$rs             = $conn->execute($sql);
	$vid		    = $rs->getrows();

	$cat = array();

	foreach ( $vid as $video ) {
		$cat[$video['channel']]++;
	}
	foreach ($categories as $k => $v) {
		$categories[$k]['total'] = 0;		
		foreach ($cat as $key => $cat_val) {
			if ($key == $v['CHID']) {
				$categories[$k]['total'] = $cat_val;
			}
		}
		$sql            = "UPDATE `channel` SET `total_videos`=".$categories[$k]['total']." WHERE CHID = ".$categories[$k]['CHID']."";
		$rs             = $conn->execute($sql);		
	}
}

if ($s == "a") {
	$smarty->assign('section', "a");
} else {
	$page_link      = $pagination->getPagination('categories');
	$smarty->assign('base', 'categories');
	$smarty->assign('section', "v");
}

$start_num      = $pagination->getStartItem();
$end_num        = $pagination->getEndItem();
$smarty->assign('errors',$errors);
$smarty->assign('messages',$messages);
$smarty->assign('menu', 'categories');
$smarty->assign('catgy', true);
$smarty->assign('categories', $categories);
$smarty->assign('categories_total', $total);
$smarty->assign('start_num', $start_num);
$smarty->assign('end_num', $end_num);
$smarty->assign('page_link', $page_link);
$smarty->assign('self_title', $seo['categories_title']);
$smarty->assign('self_description', $seo['categories_desc']);
$smarty->assign('self_keywords', $seo['categories_keywords']);
$smarty->loadFilter('output', 'trimwhitespace');
$smarty->display('header.tpl');
$smarty->display('categories.tpl');
$smarty->display('footer.tpl');
?>
