<?php
defined( '_JEXEC' ) or die( 'Restricted access.' );
class xmap_com_zoo {
	function prepareMenuItem(&$node) {
		$link_query = parse_url( $node->link );
		parse_str( html_entity_decode($link_query['query']), $link_vars);
		$component = JArrayHelper::getValue($link_vars, 'option', '');
		$view = JArrayHelper::getValue($link_vars,'view','');
		if ($component == 'com_zoo' && $view == 'frontpage' ) {
			$id = intval(JArrayHelper::getValue($link_vars,'id',0));
			if ( $id != 0 ) {
				$node->uid = 'zoo'.$id;
				$node->expandible = false;
			}
		}
	}
	function getTree( &$xmap, &$parent, &$params) {	
		$link_query = parse_url( $parent->link );
		parse_str( html_entity_decode($link_query['query']), $link_vars );
		$view = JArrayHelper::getValue($link_vars,'view',0);
		$include_categories = JArrayHelper::getValue( $params, 'include_categories',1,'' );
		$include_categories = ( $include_categories == 1
				  || ( $include_categories == 2 && $xmap->view == 'xml')
				  || ( $include_categories == 3 && $xmap->view == 'html')
				  ||   $xmap->view == 'navigator');
		$params['include_categories'] = $include_categories;				
		$include_items = JArrayHelper::getValue( $params, 'include_items',1,'' );
		$include_items = ( $include_items == 1
				  || ( $include_items == 2 && $xmap->view == 'xml')
				  || ( $include_items == 3 && $xmap->view == 'html')
				  ||   $xmap->view == 'navigator');
		$params['include_items'] = $include_items;
		$priority = JArrayHelper::getValue($params,'cat_priority',$parent->priority,'');
		$changefreq = JArrayHelper::getValue($params,'cat_changefreq',$parent->changefreq,'');
		if ($priority  == '-1')
			$priority = $parent->priority;
		if ($changefreq  == '-1')
			$changefreq = $parent->changefreq;
		$params['cat_priority'] = $priority;
		$params['cat_changefreq'] = $changefreq;
		$priority = JArrayHelper::getValue($params,'item_priority',$parent->priority,'');
		$changefreq = JArrayHelper::getValue($params,'item_changefreq',$parent->changefreq,'');
		if ($priority  == '-1')
			$priority = $parent->priority;
		if ($changefreq  == '-1')
			$changefreq = $parent->changefreq;
		$params['item_priority'] = $priority;
		$params['item_changefreq'] = $changefreq;
		xmap_com_zoo::getCategoryTree($xmap, $parent, $params);
	}
	function getCategoryTree ( &$xmap, &$parent, &$params) {
		$db = &JFactory::getDBO();	
		// first we fetch what application we are talking about
		$menu =& JSite::getMenu();
		$menuparams = $menu->getParams($parent->id);
		$appid =  intval($menuparams->get('application', 0));
		// if selected, we print title category
		if ($params['include_categories']) {			
			// we print title if there is any
			if ($params['categories_title'] != "" && $xmap->view == 'html') {
				echo "<".$params['categories_title_tag'].">".$params['categories_title']."</".$params['categories_title_tag'].">";
			}
			// get categories info from database
			$queryc = 'SELECT c.id, c.name '.
					 'FROM #__zoo_category c '.
					 ' WHERE c.application_id = '.$appid.' AND c.published=1 '.
					 ' ORDER by c.ordering';			
			$db->setQuery($queryc);
			$cats = $db->loadObjectList();			
			// now we print categories
			$xmap->changeLevel(1);
			foreach($cats as $cat) {
				$node = new stdclass;
				$node->id   = $parent->id;
				$node->uid  = $parent->uid .'c'.$cat->id;
				$node->name = $cat->name;
				$node->link = 'index.php?option=com_zoo&amp;task=category&amp;category_id='.$cat->id;
				$node->priority   = $params['cat_priority'];
				$node->changefreq = $params['cat_changefreq'];
				$node->expandible = true;
				$xmap->printNode($node);
			}
			$xmap->changeLevel(-1);
		}
		if ($params['include_items'] ){
		
			if ($params['items_title'] != "" && $xmap->view == 'html') {
				echo "<".$params['items_title_tag'].">".$params['items_title']."</".$params['items_title_tag'].">";
			}
			// get items info from database
			// basically it select those items that are published now (publish_up is less then now, meaning it's in past)
			// and not unpublished yet (either not have publish_down date set, or that date is in future)
			$queryi =  'SELECT i.id, i.name, i.publish_up'.
							' FROM #__zoo_item i'.
							' WHERE i.application_id= '.$appid.
							' AND DATEDIFF( i.publish_up, NOW( ) ) <=0'.
							' AND IF( i.publish_down >0, DATEDIFF( i.publish_down, NOW( ) ) >0, true )'.
							' ORDER BY i.publish_up';
			$db->setQuery($queryi);
			$items = $db->loadObjectList();			
			// now we print items
			$xmap->changeLevel(1);
			foreach($items as $item) {
				// if we are making news map, we should ignore items older then 3 days
				if ($xmap->isNews && strtotime($item->publish_up) < ($xmap->now - (3 * 86400))) {
                    continue;
                }
				$node = new stdclass;
				$node->id   = $parent->id;
				$node->uid  = $parent->uid .'i'.$item->id;
				$node->name = $item->name;
				$node->link = 'index.php?option=com_zoo&amp;task=item&amp;item_id='.$item->id;
				$node->priority   = $params['item_priority'];
				$node->changefreq = $params['item_changefreq'];
				$node->expandible = true;
				$node->modified = strtotime($item->publish_up);
				$node->newsItem = 1; // if we are making news map and it get this far, it's news
				$xmap->printNode($node);							
			}
			$xmap->changeLevel(-1);
		}
	}
}