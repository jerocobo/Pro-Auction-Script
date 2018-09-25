<?php
/*******************************************************************************
 *   copyright				: (C) 2014 - 2018 Pro-Auction-Script
 *   site					: https://www.pro-auction-script.com
 *   Script License			: https://www.pro-auction-script.com/contents.php?show=free_license
 *******************************************************************************/
define('InAdmin', 1);
include 'adminCommon.php';
$catscontrol = new MPTTcategories();

function search_cats($parent_id, $level)
{
	global $DBPrefix, $catscontrol;
	$catstr = '';
	$root = $catscontrol->get_virtual_root();
	$tree = $catscontrol->display_tree($root['left_id'], $root['right_id'], '|___');
	foreach ($tree as $k => $v)
	{
		$catstr .= ",\n" . $k . " => '" . $v . "'";
	}
	return $catstr;
}

function rebuild_cat_file()
{
	global $system, $DBPrefix, $db;
	$query = "SELECT cat_id, cat_name, parent_id FROM " . $DBPrefix . "categories ORDER BY cat_name";
	$db->direct_query($query);
	$cats = array();
	while ($catarr = $db->result())
	{
		$cats[$catarr['cat_id']] = $catarr['cat_name'];
		$allcats[] = $catarr;
	}

	$output = "<?php\n";
	$output.= "$" . "category_names = array(\n";

	$num_rows = count($cats);

	$i = 0;
	foreach ($cats as $k => $v)
	{
		$output .= "$k => '$v'";
		$i++;
		if ($i < $num_rows)
			$output .= ",\n";
		else
			$output .= "\n";
	}

	$output .= ");\n\n";

	$output .= "$" . "category_plain = array(\n0 => ''";

	$output .= search_cats(0, 0);

	$output .= ");\n?>";

	$handle = fopen (LANGUAGE_PATH . $system->SETTINGS['defaultlanguage'] . '/categories.inc.php', 'w');
	fputs($handle, $output);
}

if (isset($_POST['action']))
{
	if ($_POST['action'] == $MSG['089'] || $_POST['action'] == $MSG['518'])
	{
		//update all categories that arnt being deleted
		if (isset($_POST['categories']) && is_array($_POST['categories']))
		{
			foreach ($_POST['categories'] as $k => $v)
			{
				if (!isset($_POST['delete'][$k]))
				{
					$query = "UPDATE " . $DBPrefix . "categories SET cat_name = :name, cat_color = :color, cat_image = :image WHERE cat_id = :id";
					$params = array();
					$params[] = array(':name', $system->cleanvars($_POST['categories'][$k]), 'str');
					$params[] = array(':color', $_POST['color'][$k], 'str');
					$params[] = array(':image', $_POST['image'][$k], 'str');
					$params[] = array(':id', intval($k), 'int');
					$db->query($query, $params);
				}
			}
		}
		//add category if need be
		if (!empty($_POST['new_category']) && isset($_POST['parent']))
		{
			$add_data = array(
				'cat_name' => $system->cleanvars($_POST['new_category']),
				'cat_color' => $_POST['cat_color'],
				'cat_image' => $_POST['cat_image']
				);
			$catscontrol->add($_POST['parent'], 0, $add_data);
		}
		if (!empty($_POST['mass_add']) && isset($_POST['parent']))
		{
			$add = explode("\n", $_POST['mass_add']);
			if (is_array($add))
			{
				foreach ($add as $v)
				{
					$add_data = array('cat_name' => $system->cleanvars($v));
					$catscontrol->add($_POST['parent'], 0, $add_data);
				}
			}
		}
		if (isset($_POST['delete']) && is_array($_POST['delete']))
		{
			// Get data from the database
			$query = "SELECT COUNT(a.id) as COUNT, c.* FROM " . $DBPrefix . "categories c
						LEFT JOIN " . $DBPrefix . "auctions a ON ( a.category = c.cat_id )
						WHERE c.cat_id IN (" . implode(',', $_POST['delete']) . ")
						GROUP BY c.cat_id ORDER BY cat_name";
			$db->direct_query($query);
			
			$message = $MSG['843'] . '<table cellpadding="0" cellspacing="0">';
			$names = array();
			$counter = 0;
			while ($row = $db->result())
			{
				if ($row['COUNT'] > 0 || $row['left_id'] != ($row['right_id'] - 1))
				{
					$names[] = $row['cat_name'];
					$message .= '<tr>';
					$message .= '<td>' . $row['cat_name'] . '</td><td>';
					$message .= '<select name="delete[' . $row['cat_id'] . ']">';
					$message .= '<option value="delete">' . $MSG['008'] . '</option>';
					$message .= '<option value="move">' . $MSG['840'] . ': </option>';
					$message .= '</select>';
					$message .= '</td>';
					$message .= '<td><input type="text" size="5" name="moveid[' . $row['cat_id'] . ']"></td>';
					$message .= '</tr>';
					$counter++;
				}
				else
				{
					$names[] = $row['cat_name'] . '<input type="hidden" name="delete[' . $row['cat_id'] . ']" value="delete">';
				}
			}
			$message .= '</table>';
			// build message
			$template->assign_vars(array(
					'ERROR' => (isset($ERROR)) ? $ERROR : '',
					'ID' => '',
					'MESSAGE' => (($counter > 0) ? $message : '') . '<p>' . $MSG['838'] . implode(', ', $names) . '</p>',
					'TYPE' => 1
					));
			include 'adminHeader.php';
			$template->set_filenames(array(
					'body' => 'confirm.tpl'
					));
			$template->display('body');
			include 'adminFooter.php';
			exit;
		}
		rebuild_cat_file();
		include 'util_cc1.php';
	}

	if ($_POST['action'] == $MSG['030'])
	{
		//delete categories that are selected
		if (isset($_POST['delete']) && is_array($_POST['delete']))
		{
			foreach ($_POST['delete'] as $k => $v)
			{
				$k = intval($k);
				if ($v == 'delete')
				{
					//never delete categories without using this function it will mess up your database big time
					$catscontrol->delete($k);
				}
				elseif ($v == 'move')
				{
					if (isset($_POST['moveid'][$k]) && !empty($_POST['moveid'][$k])
						&& is_numeric($_POST['moveid'][$k]) && $catscontrol->check_category($_POST['moveid'][$k]))
					{
						// first move the parent
						$catscontrol->move($k, $_POST['moveid'][$k]);
						// remove the parent and raise the children up a level
						$catscontrol->delete($k, true);
						$query = "UPDATE " . $DBPrefix . "auctions SET category = :cat WHERE category = :id";
						$params = array();
						$params[] = array(':cat', $_POST['moveid'][$k], 'str');
						$params[] = array(':id', $k, 'int');
						$db->query($query, $params);
					}
					else
					{
						$ERROR = $MSG['844'];
					}
				}
			}
		}
		rebuild_cat_file();
		include 'util_cc1.php';
	}
}

//show the page... 
if (!isset($_GET['parent']))
{
	$query = "SELECT left_id, right_id, level, cat_id FROM " . $DBPrefix . "categories WHERE parent_id = :id";
	$params = array();
	$params[] = array(':id', -1, 'int');
}
else
{
	$parent = intval($_GET['parent']);
	$query = "SELECT left_id, right_id, level FROM " . $DBPrefix . "categories WHERE cat_id = :id";
	$params = array();
	$params[] = array(':id', intval($_GET['parent']), 'int');
}
$db->query($query, $params);
$parent_node = $db->result();

if (!isset($_GET['parent']))
{
	$parent = $parent_node['cat_id'];
}

$crumb_string = '';
if ($parent != 0)
{
	$crumbs = $catscontrol->get_bread_crumbs($parent_node['left_id'], $parent_node['right_id']);
	for ($i = 0; $i < count($crumbs); $i++)
	{
		if ($i > 0)
		{
			$crumb_string .= ' > ';
		}
		$crumb_string .= '<a href="categories.php?parent=' . $crumbs[$i]['cat_id'] . '">' . $crumbs[$i]['cat_name'] . '</a>';
	}
}

$children = $catscontrol->get_children($parent_node['left_id'], $parent_node['right_id'], $parent_node['level']);
for ($i = 0; $i < count($children); $i++)
{
	$child = $children[$i];
	$template->assign_block_vars('cats', array(
			'CAT_ID' => $child['cat_id'],
			'CAT_NAME' => $system->uncleanvars($child['cat_name']),
			'CAT_COLOR' => $child['cat_color'],
			'CAT_IMAGE' => $child['cat_image'],

			'B_SUBCATS' => ($child['left_id'] != ($child['right_id'] - 1)),
			'B_AUCTIONS' => ($child['counter'] > 0)
			));
}

$template->assign_vars(array(
	'CRUMBS' => $crumb_string,
	'PARENT' => $parent,
	'ERROR' => (isset($ERROR)) ? $ERROR : '',
	'PAGENAME' => '<a href="https://www.pro-auction-script.com/wiki/doku.php?id=Pro-Auction-Script_categories_table" target="_blank">' . $MSG['078'] . '</a>',
	'PAGETITLE' => $MSG['078']
));
include 'adminHeader.php';
$template->set_filenames(array(
		'body' => 'categories.tpl'
		));
$template->display('body');
include 'adminFooter.php';