<?php
/**
* @package		EasyBlog
* @copyright	Copyright (C) 2012 Stack Ideas Private Limited. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasyBlog is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*
*
* XMap EasyBlog plugin for Joomla 2.5 and letter
* @copyright Copyright (C) 2012. All rights reserved. MVC platform 11.4 ported by Maidan.
* Vladimir Maidanichenko support@joomlamaster.org.ua  ( http://joomlamaster.org.ua )
* Open source software license http://www.gnu.org/copyleft/gpl.html GNU/GPL and letter
*/
/**
* Xmap plugin for EasyBlog contact component
* created @ 12 Feb 2012
*/

defined( '_JEXEC' ) or die( 'Restricted access.' );

class xmap_com_easyblog {


	function getBlogEntries($type, $typeId, $limit)
	{
		require_once( JPATH_ROOT.DS.'components'.DS.'com_easyblog'.DS.'constants.php' );
		require_once( JPATH_ROOT.DS.'components'.DS.'com_easyblog'.DS.'helpers'.DS.'helper.php' );
	
        $db 		=& JFactory::getDBO();
        $my 		=& JFactory::getUser();
        $config 	=& EasyBlogHelper::getConfig();
        $condition  = array();
        
        
        
	    //get teamblogs id.
	    $teamBlogIds    = '';
	    if( $config->get( 'main_includeteamblogpost' ) )
	    {
	        if( $my->id == 0)
	        {
	            //get team id with access == 3
	            $query  = 'select `id` FROM `#__easyblog_team` where `access` = ' . $db->Quote( '3' );
	            $query  .= ' and `published` = ' . $db->Quote( '1' );
	            $db->setQuery($query);

	            $result 		= $db->loadResultArray();
	            if( count( $result ) > 0 )
	            	$teamBlogIds    = implode( ',' , $result);

	        }
	        else
	        {
	            // get the teamid from this user.
                $query  = 'select distinct `id` from `#__easyblog_team` as t left join `#__easyblog_team_users` as tu on t.id = tu.team_id';
                $query  .= ' where t.`published` = ' . $db->Quote( '1' );
                $query  .= ' and (tu.`user_id` = ' . $db->Quote( $my->id );
                $query  .= ' OR t.`access` = ' . $db->Quote( '3' ) . ')';
                $db->setQuery($query);

	            $result 		= $db->loadResultArray();
	            if( count( $result ) > 0 )
	            	$teamBlogIds    = implode( ',' , $result);
	        }
	    }
	    
		// get all private categories id
		$excludeCats    = '';
		if($my->id == 0)
		{
		    $query	= 	'select a.`id`, a.`private`';
			$query	.=  ' from `#__easyblog_category` as a';
			$query	.=  ' where a.`private` = ' . $db->Quote('1');

		    $db->setQuery($query);
		    $result = $db->loadObjectList();

		    for($i=0; $i < count($result); $i++)
		    {
		        $item   =& $result[$i];
	            $item->childs = null;

	            EasyBlogHelper::buildNestedCategories($item->id, $item);

				$catIds     = array();
				$catIds[]   = $item->id;
				EasyBlogHelper::accessNestedCategoriesId($item, $catIds);

				$excludeCats    = array_merge($excludeCats, $catIds);
		    }
		}
		
		
		// check if integrate with jomsocial privacy or not.
		$queryPrivacy = '';
		$file		= JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'core.php';
		if( $config->get( 'main_jomsocial_privacy' ) && JFile::exists( $file ) && $type != 'teamblog')
		{
			require_once( $file );
			
			$jsFriends	= CFactory::getModel( 'Friends' );
			$friends	= $jsFriends->getFriendIds( $my->id );

			// Insert query here.
			$queryPrivacy	= '(';
			$queryPrivacy	.= ' (a.`private`= 0 ) OR';
			$queryPrivacy	.= ' ( (a.`private` = 20) AND (' . $db->Quote( $my->id ) . ' > 0 ) ) OR';

			if( empty( $friends ) )
			{
				$queryPrivacy	.= ' ( (a.`private` = 30) AND ( 1 = 2 ) ) OR';
			}
			else
			{
				$queryPrivacy	.= ' ( (a.`private` = 30) AND ( a.' . $db->nameQuote( 'created_by' ) . ' IN (' . implode( ',' , $friends ) . ') ) ) OR';
			}

			$queryPrivacy	.= ' ( (a.`private` = 40) AND ( a.' . $db->nameQuote( 'created_by' ) .'=' . $my->id . ') )';
			$queryPrivacy	.= ' )';
		}
		else
		{
			if( $my->id == 0)
			{
				$queryPrivacy .= 'a.`private` = ' . $db->Quote('0');
			}
		}
        
        
        
		$query	= 'SELECT a.* FROM `#__easyblog_post` AS a';
		
		if( $type != 'teamblog')
		{
		    $query  .= ' LEFT JOIN `#__easyblog_team_post` AS b ON a.`id` = b.`post_id`';
		}
		
		
		//filter by type
		switch($type)
		{
		    case 'archive' :
			    $data   = explode('-', $typeId);
			    $month  = $data[0];
			    $year	= $data[1];
		    	$condition[]= '(a.`created` >= ' . $db->Quote($year.'-'.$month.'-01 00:00:00') . ' AND a.`created` <= ' . $db->Quote($year.'-'.$month.'-31 23:59:59') . ')';
		        break;
		
		    case 'teamblog' :
		        $query  .= ' INNER JOIN `#__easyblog_team_post` AS b ON a.`id` = b.`post_id`';
		        $query  .= ' AND b.`team_id` = ' . $db->Quote($typeId);
				break;
		    case 'blogger' :
		        $condition[] = 'a.`created_by` =' . $db->Quote($typeId);
		        break;
		    case 'tags' :
		        $query	.= ' INNER JOIN `#__easyblog_post_tag` AS tp';
		        $query  .= ' ON tp.`post_id` = a.`id`';
		        $query	.= ' INNER JOIN `#__easyblog_tag` AS t';
		        $query  .= ' ON tp.`tag_id` = t.`id`';
		        $query  .= ' AND t.`id` = ' . $db->Quote($typeId);
		        break;
		    case 'category' :
		        $condition[] = 'a.`category_id` =' . $db->Quote($typeId);
				break;
			default:
			    break;
		}
		
		$condition[] = 'a.`published` =' . $db->Quote('1');
		$condition[] = 'a.`ispending` =' . $db->Quote('0');
		
		
		if(! empty($excludeCats))
		{
		    $condition[] = 'a.`category_id` NOT IN (' . implode(',', $excludeCats) . ')';
		}
		
		if( !empty($queryPrivacy))
			$condition[]    = $queryPrivacy;
		
		
		if( $type != 'teamblog' )
		{
		    if( $config->get( 'main_includeteamblogpost' ) && !empty($teamBlogIds))
		    {
				$condition[]	= '(b.team_id IN ('.$teamBlogIds.') OR a.`issitewide` = ' . $db->Quote('1') . ')';
			}
			else
			{
			    $condition[]	= 'a.`issitewide` = ' . $db->Quote('1');
			}
		}
		else
		{
		    $condition[] = 'a.`issitewide` =' . $db->Quote('0');
		}
		
		
		$extra 	= ( count( $condition ) ? ' WHERE ' . implode( ' AND ', $condition ) : '' );
        $query  .= $extra;
		$query	.= ' ORDER BY a.`created`  DESC ';
		$query	.= ' LIMIT ' . $limit;
		
// 		if($type == 'teamblog')
// 			echo '<br><br>'.$query;
		
      	$db->setQuery($query);
      	$rows = $db->loadObjectList();
      	
		return $rows;
	}


    function getEasyBlog( &$xmap, &$parent,&$params )
    {
        $db =& JFactory::getDBO();
        $my =& JFactory::getUser();

		require_once(JPATH_ROOT.DS.'components'.DS.'com_easyblog'.DS.'constants.php');
		require_once(JPATH_ROOT.DS.'components'.DS.'com_easyblog'.DS.'helpers'.DS.'helper.php');
		
		$config         =& EasyBlogHelper::getConfig();


		// latest post
		if ($params['include_latest_post'])
		{
            $xmap->changeLevel(1);
            $node = new stdclass;
            $node->browserNav = $parent->browserNav;
            $node->id = $parent->id;
            $node->uid = $parent->uid.'l';
            $node->priority = $params['latestpost_priority'];
            $node->changefreq = $params['latestpost_changefreq'];
            $node->name = 'Latest Post';
            $node->link = "index.php?option=com_easyblog&view=latest";
            $node->modified = time();
            $node->expandible = true;
            $xmap->printNode($node);
		
        	$rows   = xmap_com_easyblog::getBlogEntries('latest', '', $params['number_of_latest_posts']);
        	
        	if(count($rows) > 0)
        	{
        	    $xmap->changeLevel(1);
        	    foreach($rows as $row)
        	    {
		            $node->browserNav = $parent->browserNav;
		            $node->id = $parent->id;
		            $node->uid = $parent->uid.'p'.$row->id;
		            $node->priority = $params['entry_priority'];
		            $node->changefreq = $params['entry_changefreq'];
		            $node->name = $row->title;
		            $node->link = "index.php?option=com_easyblog&view=entry&id=" . $row->id;
		            $node->modified = intval($row->modified);
		            $node->expandible = false;
		            $xmap->printNode($node);
        	    }//end foreach
        	    $xmap->changeLevel(-1);
        	}//end if
		    $xmap->changeLevel(-1);
		}
		
		// categories post
		if ($params['include_cetegories'])
		{
            $xmap->changeLevel(1);
            $node = new stdclass;
            $node->browserNav = $parent->browserNav;
            $node->id = $parent->id;
            $node->uid = $parent->uid.'c';
            $node->priority = $params['category_priority'];
            $node->changefreq = $params['category_changefreq'];
            $node->name = 'Categories';
            $node->link = "index.php?option=com_easyblog&view=categories";
            $node->modified = time();
            $node->expandible = true;
            $xmap->printNode($node);
            
            //get categories
            $query  = 'select `id`, `title`, `alias`';
			$query  .= ' FROM `#__easyblog_category`';
			$query  .= ' WHERE `published` = ' . $db->Quote('1');
			if($my->id == 0)
				$query  .= ' AND `private` = ' . $db->Quote('0');
			$query .= ' ORDER BY `title`';
			$query .= ' LIMIT ' . $params['number_of_categories'];
				
			$db->setQuery($query);
			$rows   = $db->loadObjectList();
			
			if(count($rows) > 0)
			{
			    $xmap->changeLevel(1);
			    foreach($rows as $row)
			    {
		            $node = new stdclass;
		            $node->browserNav = $parent->browserNav;
		            $node->id = $parent->id;
		            $node->uid = $parent->uid.'c'.$row->alias;
		            $node->priority = $params['category_priority'];
		            $node->changefreq = $params['category_changefreq'];
		            $node->name = $row->title;
		            $node->link = "index.php?option=com_easyblog&view=categories&layout=listings&id=" . $row->id;
		            $node->modified = time();
		            $node->expandible = true;
		            $xmap->printNode($node);
		            
		            if($params['include_category_posts'])
		            {
		            	$items   = xmap_com_easyblog::getBlogEntries('category', $row->id, $params['number_of_posts']);
		            	if(count($items) > 0)
		            	{
                            $xmap->changeLevel(1);
                            foreach($items as $item)
                            {
					            $node->browserNav = $parent->browserNav;
					            $node->id = $parent->id;
					            $node->uid = $parent->uid.'cp'.$item->id;
					            $node->priority = $params['entry_priority'];
					            $node->changefreq = $params['entry_changefreq'];
					            $node->name = $item->title;
					            $node->link = "index.php?option=com_easyblog&view=entry&id=" . $item->id;
					            $node->modified = intval($item->modified);
					            $node->expandible = false;
					            $xmap->printNode($node);
                            }
                            $xmap->changeLevel(-1);
		            	}
					}
			    }
			    $xmap->changeLevel(-1);
			}
			$xmap->changeLevel(-1);
		}
		
		
		// tags cloud
		if ($params['include_tag_clouds'])
		{
            $xmap->changeLevel(1);
            $node = new stdclass;
            $node->browserNav = $parent->browserNav;
            $node->id = $parent->id;
            $node->uid = $parent->uid.'t';
            $node->priority = $params['tag_priority'];
            $node->changefreq = $params['tag_changefreq'];
            $node->name = 'Tags';
            $node->link = "index.php?option=com_easyblog&view=tags";
            $node->modified = time();
            $node->expandible = true;
            $xmap->printNode($node);
            
            //get tags
		    $query  =   'select a.`id`, a.`title`, a.`alias`';
			$query	.=  ' from `#__easyblog_tag` as a';
			$query  .= 	' where a.`published` = ' . $db->Quote('1');
			$query .= ' LIMIT ' . $params['number_of_tags'];

			$db->setQuery($query);
			$rows   = $db->loadObjectList();
			
			if(count($rows) > 0)
			{
			    $xmap->changeLevel(1);
			    foreach($rows as $row)
			    {
		            $node = new stdclass;
		            $node->browserNav = $parent->browserNav;
		            $node->id = $parent->id;
		            $node->uid = $parent->uid.'t'.$row->alias;
		            $node->priority = $params['tag_priority'];
		            $node->changefreq = $params['tag_changefreq'];
		            $node->name = $row->title;
		            $node->link = "index.php?option=com_easyblog&view=tags&layout=tag&id=" . $row->id;
		            $node->modified = time();
		            $node->expandible = true;
		            $xmap->printNode($node);
		            
		            if($params['include_tag_posts'])
		            {
		                $items   = xmap_com_easyblog::getBlogEntries('tags', $row->id, $params['number_of_posts']);
		            	if(count($items) > 0)
		            	{
                        	$xmap->changeLevel(1);
                           	foreach($items as $item)
                            {
					            $node->browserNav = $parent->browserNav;
					            $node->id = $parent->id;
					            $node->uid = $parent->uid.'tp'.$item->id;
					            $node->priority = $params['entry_priority'];
					            $node->changefreq = $params['entry_changefreq'];
					            $node->name = $item->title;
					            $node->link = "index.php?option=com_easyblog&view=entry&id=" . $item->id;
					            $node->modified = intval($item->modified);
					            $node->expandible = false;
					            $xmap->printNode($node);
                            }
                            $xmap->changeLevel(-1);
                        }
		            }
		            
		         }
		         $xmap->changeLevel(-1);
		     }
		    $xmap->changeLevel(-1);
		}
		
		// bloggers
		if ($params['include_bloggers'])
		{
		    $displayname    = $config->get('layout_nameformat');
		    
            $xmap->changeLevel(1);
            $node = new stdclass;
            $node->browserNav = $parent->browserNav;
            $node->id = $parent->id;
            $node->uid = $parent->uid.'b';
            $node->priority = $params['blogger_priority'];
            $node->changefreq = $params['blogger_changefreq'];
            $node->name = 'Bloggers';
            $node->link = "index.php?option=com_easyblog&view=blogger";
            $node->modified = time();
            $node->expandible = true;
            $xmap->printNode($node);
            
			$query	= 'SELECT DISTINCT a.`created_by` AS `blogger_id`,';

    		if($displayname == 'username')
    		    $query  .= ' c.`username` as `title`';
    		else if($displayname == 'name')
    		    $query  .= ' c.`name` as `title`';
    		else if($displayname == 'nickname')
    		    $query  .= ' b.`nickname` as `title`';
    		else 
    		    $query  .= ' b.`name` as `title`';
			$query	.= ' FROM `#__easyblog_post` as a';
			$query	.= '  INNER JOIN `#__easyblog_users` AS b';
			$query	.= '  ON a.`created_by` = b.`id`';
			$query	.= '  INNER JOIN `#__users` AS c';
			$query	.= '  on b.`id` = c.`id`';
			$query  .= ' ORDER BY `title`';
			$query 	.= ' LIMIT ' . $params['number_of_bloggers'];
			
			$db->setQuery($query);
			$rows   = $db->loadObjectList();
			
			if(count($rows) > 0)
			{
			    $xmap->changeLevel(1);
			    foreach($rows as $row)
			    {
		            $node = new stdclass;
		            $node->browserNav = $parent->browserNav;
		            $node->id = $parent->id;
		            $node->uid = $parent->uid.'b'.$row->blogger_id;
		            $node->priority = $params['blogger_priority'];
		            $node->changefreq = $params['blogger_changefreq'];
		            $node->name = $row->title;
		            $node->link = "index.php?option=com_easyblog&view=blogger&layout=listings&id=" . $row->blogger_id;
		            $node->modified = time();
		            $node->expandible = true;
		            $xmap->printNode($node);

		            if($params['include_blogger_posts'])
		            {
		                $items   = xmap_com_easyblog::getBlogEntries('blogger', $row->blogger_id, $params['number_of_posts']);
		            	if(count($items) > 0)
		            	{
                        	$xmap->changeLevel(1);
                           	foreach($items as $item)
                            {
					            $node->browserNav = $parent->browserNav;
					            $node->id = $parent->id;
					            $node->uid = $parent->uid.'bp'.$item->id;
					            $node->priority = $params['entry_priority'];
					            $node->changefreq = $params['entry_changefreq'];
					            $node->name = $item->title;
					            $node->link = "index.php?option=com_easyblog&view=entry&id=" . $item->id;
					            $node->modified = intval($item->modified);
					            $node->expandible = false;
					            $xmap->printNode($node);
                            }
                            $xmap->changeLevel(-1);
                        }
		            }
		            
			    }
			    $xmap->changeLevel(-1);
			}
			$xmap->changeLevel(-1);
		}
		
		if ($params['include_teamblog'])
		{
            $xmap->changeLevel(1);
            $node = new stdclass;
            $node->browserNav = $parent->browserNav;
            $node->id = $parent->id;
            $node->uid = $parent->uid.'m';
            $node->priority = $params['teamblog_priority'];
            $node->changefreq = $params['teamblog_changefreq'];
            $node->name = 'TeamBlog';
            $node->link = "index.php?option=com_easyblog&view=teamblog";
            $node->modified = time();
            $node->expandible = true;
            $xmap->printNode($node);
            
			$query	=  'SELECT a.`id`, a.`title`, a.`alias` FROM `#__easyblog_team` AS a ';
			$query	.= ' LEFT JOIN `#__easyblog_team_users` AS b ON a.`id` = b.`team_id` ';
			$query  .= ' WHERE a.`published` = ' . $db->Quote('1');
			$query	.= ' GROUP BY a.`id` HAVING (count(b.`team_id`) > 0)';
			$query  .= ' ORDER BY a.`title`';
			$query 	.= ' LIMIT ' . $params['number_of_teamblog'];
			
			$db->setQuery($query);
            $rows   = $db->loadObjectList();

            if(count($rows) > 0)
            {
      	 		$xmap->changeLevel(1);
      	 		
			    foreach($rows as $row)
			    {
		            $node = new stdclass;
		            $node->browserNav = $parent->browserNav;
		            $node->id = $parent->id;
		            $node->uid = $parent->uid.'m'.$row->id;
		            $node->priority = $params['teamblog_priority'];
		            $node->changefreq = $params['teamblog_changefreq'];
		            $node->name = $row->title;
		            $node->link = "index.php?option=com_easyblog&view=teamblog&layout=listings&id=" . $row->id;
		            $node->modified = time();
		            $node->expandible = true;
		            $xmap->printNode($node);
		            
		            if($params['include_teamblog_posts'])
		            {
		                $items   = xmap_com_easyblog::getBlogEntries('teamblog', $row->id, $params['number_of_posts']);
		            	if(count($items) > 0)
		            	{
                        	$xmap->changeLevel(1);
                           	foreach($items as $item)
                            {
					            $node->browserNav = $parent->browserNav;
					            $node->id = $parent->id;
					            $node->uid = $parent->uid.'mp'.$item->id;
					            $node->priority = $params['entry_priority'];
					            $node->changefreq = $params['entry_changefreq'];
					            $node->name = $item->title;
					            $node->link = "index.php?option=com_easyblog&view=entry&id=" . $item->id . '&&team=' . $row->id;
					            $node->modified = intval($item->modified);
					            $node->expandible = false;
					            $xmap->printNode($node);
                            }
                            $xmap->changeLevel(-1);
                        }
		            }
			    }//end foreach
			    $xmap->changeLevel(-1);
            }
            $xmap->changeLevel(-1);
		}
		
		if ($params['include_archives'])
		{
		    $data   = explode('-', $params['monthyear_of_archive']);
		    $month  = $data[0];
		    $year	= $data[1];
		
            $xmap->changeLevel(1);
            $node = new stdclass;
            $node->browserNav = $parent->browserNav;
            $node->id = $parent->id;
            $node->uid = $parent->uid.'a';
            $node->priority = $params['archive_priority'];
            $node->changefreq = $params['archive_changefreq'];
            $node->name = 'Archives';
            $node->link = "index.php?option=com_easyblog&view=archive&archiveyear=" . $year . '&archivemonth=' . $month;
            $node->modified = time();
            $node->expandible = true;
            $xmap->printNode($node);

        	$rows   = xmap_com_easyblog::getBlogEntries('archive', $params['monthyear_of_archive'], $params['number_of_posts']);

        	if(count($rows) > 0)
        	{
        	    $xmap->changeLevel(1);
        	    foreach($rows as $row)
        	    {
		            $node->browserNav = $parent->browserNav;
		            $node->id = $parent->id;
		            $node->uid = $parent->uid.'ap'.$row->id;
		            $node->priority = $params['entry_priority'];
		            $node->changefreq = $params['entry_changefreq'];
		            $node->name = $row->title;
		            $node->link = "index.php?option=com_easyblog&view=entry&id=" . $row->id;
		            $node->modified = intval($row->modified);
		            $node->expandible = false;
		            $xmap->printNode($node);
        	    }//end foreach
        	    $xmap->changeLevel(-1);
        	}//end if
		    $xmap->changeLevel(-1);
		}
		
    }

    /**   Get   the   content   tree for this kind of content */
    function getTree( &$xmap, &$parent, &$params   )
    {
		// latest post
        $include_latest_post = JArrayHelper::getValue($params,'include_latest_post',1);
        $include_latest_post = ( $include_latest_post == 1
        || ( $include_latest_post == 2 && $xmap->view == 'xml')
        || ( $include_latest_post == 3 && $xmap->view == 'html')
        ||   $xmap->view == 'navigator');
        $params['include_latest_post'] = $include_latest_post;
        
		// categories
        $include_cetegories = JArrayHelper::getValue($params,'include_cetegories',1);
        $include_cetegories = ( $include_cetegories == 1
        || ( $include_cetegories == 2 && $xmap->view == 'xml')
        || ( $include_cetegories == 3 && $xmap->view == 'html')
        ||   $xmap->view == 'navigator');
        $params['include_cetegories'] = $include_cetegories;
        
        // tags cloud
        $include_tag_clouds = JArrayHelper::getValue($params,'include_tag_clouds',1);
        $include_tag_clouds = ( $include_tag_clouds == 1
        || ( $include_tag_clouds == 2 && $xmap->view == 'xml')
        || ( $include_tag_clouds == 3 && $xmap->view == 'html')
        ||   $xmap->view == 'navigator');
        $params['include_tag_clouds'] = $include_tag_clouds;

        // bloggers
        $include_bloggers = JArrayHelper::getValue($params,'include_bloggers',1);
        $include_bloggers = ( $include_bloggers == 1
        || ( $include_bloggers == 2 && $xmap->view == 'xml')
        || ( $include_bloggers == 3 && $xmap->view == 'html')
        ||   $xmap->view == 'navigator');
        $params['include_bloggers'] = $include_bloggers;
        
        // teamblog
        $include_teamblog = JArrayHelper::getValue($params,'include_teamblog',1);
        $include_teamblog = ( $include_teamblog == 1
        || ( $include_teamblog == 2 && $xmap->view == 'xml')
        || ( $include_teamblog == 3 && $xmap->view == 'html')
        ||   $xmap->view == 'navigator');
        $params['include_teamblog'] = $include_teamblog;
        

        $include_archives = JArrayHelper::getValue($params,'include_archives',1);
        $include_archives = ( $include_archives == 1
        || ( $include_archives == 2 && $xmap->view == 'xml')
        || ( $include_archives == 3 && $xmap->view == 'html')
        ||   $xmap->view == 'navigator');
        $params['include_archives'] = $include_archives;

		// bloggers number of post
        $number_of_bloggers = intval(JArrayHelper::getValue($params,'number_of_bloggers',10));
        $params['number_of_bloggers'] = $number_of_bloggers;

        $include_blogger_posts = JArrayHelper::getValue($params,'include_blogger_posts',1);
        $include_blogger_posts = ( $include_blogger_posts == 1
        || ( $include_blogger_posts == 2 && $xmap->view == 'xml')
        || ( $include_blogger_posts == 3 && $xmap->view == 'html')
        ||   $xmap->view == 'navigator');
        $params['include_blogger_posts'] = $include_blogger_posts;
        
        
        // categories number of post
        $number_of_categories = intval(JArrayHelper::getValue($params,'number_of_categories',10));
        $params['number_of_categories'] = $number_of_categories;

        $include_category_posts = JArrayHelper::getValue($params,'include_category_posts',1);
        $include_category_posts = ( $include_category_posts == 1
        || ( $include_category_posts == 2 && $xmap->view == 'xml')
        || ( $include_category_posts == 3 && $xmap->view == 'html')
        ||   $xmap->view == 'navigator');
        $params['include_category_posts'] = $include_category_posts;
        
        
        // tags number of post
        $number_of_tags = intval(JArrayHelper::getValue($params,'number_of_tags',10));
        $params['number_of_tags'] = $number_of_tags;

        $include_tag_posts = JArrayHelper::getValue($params,'include_tag_posts',0);
        $include_tag_posts = ( $include_tag_posts == 1
        || ( $include_tag_posts == 2 && $xmap->view == 'xml')
        || ( $include_tag_posts == 3 && $xmap->view == 'html')
        ||   $xmap->view == 'navigator');
        $params['include_tag_posts'] = $include_tag_posts;
        
        // teamblog number of post
        $number_of_teamblog = intval(JArrayHelper::getValue($params,'number_of_teamblog',10));
        $params['number_of_teamblog'] = $number_of_teamblog;

        $include_teamblog_posts = JArrayHelper::getValue($params,'include_teamblog_posts',1);
        $include_teamblog_posts = ( $include_teamblog_posts == 1
        || ( $include_teamblog_posts == 2 && $xmap->view == 'xml')
        || ( $include_teamblog_posts == 3 && $xmap->view == 'html')
        ||   $xmap->view == 'navigator');
        $params['include_teamblog_posts'] = $include_teamblog_posts;
        
        // number of blog post display in latest post
        $number_of_latest_posts = intval(JArrayHelper::getValue($params,'number_of_latest_posts',20));
        $params['number_of_latest_posts'] = $number_of_latest_posts;
        
        // getting month and year for archive posts
        $monthyear_of_archive = JArrayHelper::getValue($params,'monthyear_of_archive','');
        $monthyear_of_archive = (empty($monthyear_of_archive)) ? date("m-Y") : $monthyear_of_archive;
        $params['monthyear_of_archive'] = $monthyear_of_archive;
        
        // number of blog post display for each blogger, category,tag, teamblog and archive
        $number_of_posts = intval(JArrayHelper::getValue($params,'number_of_posts',5));
        $params['number_of_posts'] = $number_of_posts;


        //----- Set latestpost_priority and latestpost_changefreq params
        $priority = JArrayHelper::getValue($params,'latestpost_priority',$parent->priority);
        $changefreq = JArrayHelper::getValue($params,'latestpost_changefreq',$parent->changefreq);
        if ($priority  == '-1')
            $priority = $parent->priority;
        if ($changefreq  == '-1')
            $changefreq = $parent->changefreq;

        $params['latestpost_priority'] = $priority;
        $params['latestpost_changefreq'] = $changefreq;
        
        
        //----- Set category_priority and category_changefreq params
        $priority = JArrayHelper::getValue($params,'category_priority',$parent->priority);
        $changefreq = JArrayHelper::getValue($params,'category_changefreq',$parent->changefreq);
        if ($priority  == '-1')
            $priority = $parent->priority;
        if ($changefreq  == '-1')
            $changefreq = $parent->changefreq;

        $params['category_priority'] = $priority;
        $params['category_changefreq'] = $changefreq;
        
        
        //----- Set tag_priority and tag_changefreq params
        $priority = JArrayHelper::getValue($params,'tag_priority',$parent->priority);
        $changefreq = JArrayHelper::getValue($params,'tag_changefreq',$parent->changefreq);
        if ($priority  == '-1')
            $priority = $parent->priority;
        if ($changefreq  == '-1')
            $changefreq = $parent->changefreq;

        $params['tag_priority'] = $priority;
        $params['tag_changefreq'] = $changefreq;


        //----- Set blogger_priority and blogger_changefreq params
        $priority = JArrayHelper::getValue($params,'blogger_priority',$parent->priority);
        $changefreq = JArrayHelper::getValue($params,'blogger_changefreq',$parent->changefreq);
        if ($priority  == '-1')
            $priority = $parent->priority;
        if ($changefreq  == '-1')
            $changefreq = $parent->changefreq;

        $params['blogger_priority'] = $priority;
        $params['blogger_changefreq'] = $changefreq;
        
        
        //----- Set teamblog_priority and teamblog_changefreq params
        $priority = JArrayHelper::getValue($params,'teamblog_priority',$parent->priority);
        $changefreq = JArrayHelper::getValue($params,'teamblog_changefreq',$parent->changefreq);
        if ($priority  == '-1')
            $priority = $parent->priority;
        if ($changefreq  == '-1')
            $changefreq = $parent->changefreq;

        $params['teamblog_priority'] = $priority;
        $params['teamblog_changefreq'] = $changefreq;
        
        
        //----- Set archive_priority and archive_changefreq params
        $priority = JArrayHelper::getValue($params,'archive_priority',$parent->priority);
        $changefreq = JArrayHelper::getValue($params,'archive_changefreq',$parent->changefreq);
        if ($priority  == '-1')
            $priority = $parent->priority;
        if ($changefreq  == '-1')
            $changefreq = $parent->changefreq;

        $params['archive_priority'] = $priority;
        $params['archive_changefreq'] = $changefreq;
        

        //----- Set entry_priority and entry_changefreq params
        $priority = JArrayHelper::getValue($params,'entry_priority',$parent->priority);
        $changefreq = JArrayHelper::getValue($params,'entry_changefreq',$parent->changefreq);
        if ($priority  == '-1')
            $priority = $parent->priority;
        if ($changefreq  == '-1')
            $changefreq = $parent->changefreq;

        $params['entry_priority'] = $priority;
        $params['entry_changefreq'] = $changefreq;

        xmap_com_easyblog::getEasyBlog($xmap,  $parent, $params);
    }

}