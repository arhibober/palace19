<?php // no direct access
defined('_JEXEC') or die('Restricted access');
$user = JFactory::getUser();

$uam_jversion = new JVersion();

if ($this->params->get('author_cblink') == 1) {

    // NGA include CB API to be able to link author name to CB user profile later on
    global $_CB_framework, $mainframe;

    if ( defined( 'JPATH_ADMINISTRATOR' ) ) {
        if ( ! file_exists( JPATH_ADMINISTRATOR . '/components/com_comprofiler/plugin.foundation.php' ) ) {
            $this->params->set('found_cb', false);
            echo 'CB not installed!';
        } else {
            $this->params->set('found_cb', true);
            include_once( JPATH_ADMINISTRATOR . '/components/com_comprofiler/plugin.foundation.php' );
        }
    } else {
        if ( ! file_exists( $mainframe->getCfg( 'absolute_path' ) . '/administrator/components/com_comprofiler/plugin.foundation.php' ) ) {
            $this->params->set('found_cb', false);
            echo 'CB not installed!';
        } else {
            $this->params->set('found_cb', true);
            include_once( $mainframe->getCfg( 'absolute_path' ) . '/administrator/components/com_comprofiler/plugin.foundation.php' );
        }
    }

    if ($this->params->get('found_cb')) {
        cbimport( 'cb.html' );
    } else {
        $this->params->set('author_cblink', 0);
    }
}

?>

<div class="componentheading<?php echo $this->params->get('pageclass_sfx'); ?>">
	<?php $this->escape($this->params->get('page_title')); ?>
</div>

<form name="adminForm" id="adminForm" method="post" action="<?php echo $this->action; ?>">

<table width="100%" cellpadding="3" cellspacing="3" border="1" id="table_frontend_user_article_list">
<thead>
	<tr>
		<td colspan="<?php echo $this->total_columns; ?>" align="left" valign="bottom">
			<div style="float:left;">
			<?php
			if ($this->params->get('showsearchfilter') == 1) {
			echo JText::_('COM_UAM_FILTER'); ?>:
			<br />
			<input id="filter_search" type="text" name="filter_search" value="<?php echo $this->escape($this->lists['filter_search']);?>" class="inputbox" /> 
			<button onclick="this.form.submit();"><?php echo JText::_('COM_UAM_GO'); ?></button>
			<button onclick="document.getElementById('filter_search').value=''; document.getElementById('filter_state').value=''; document.getElementById('filter_catid').value='0'; document.getElementById('filter_authorid').value='0'; document.getElementById('filter_lang').value=''; this.form.submit();"><?php echo JText::_('COM_UAM_RESET'); ?></button>
			<?php
			}
			echo '<br />';
			$add_break = 0;
			if ((($this->params->get('useallcategories') == 1) || ($this->params->get('allow_subcategories') == "yes")) && ($this->params->get('showcategoryfilter') == 1)) {
				echo $this->lists['catid'];
				$add_break = 1;
			}
			if (($this->canEditOwnOnly == false) && ($this->params->get('showauthorfilter') == 1)) {
				echo $this->lists['authorid'];
				$add_break = 1;
			}
			if ($this->params->get('showpublishedstatefilter') == 1) {
				echo $this->lists['state'];
				$add_break = 1;
			}
			if ($this->params->get('showlanguagefilter') == 1) {
				echo $this->lists['langs'];
				$add_break = 1;
			}
			if ($add_break == 1) {
				echo '<br /><br />';
			}
			?>
			</div>

			<?php
			if($this->params->get('new_article_button')) {
				$custom_link = trim($this->params->get('link_new_article'));
				//default link
				if($this->params->get('link_new_article_default')
					|| (!$this->params->get('link_new_article_default') && strlen($custom_link) == 0)) {
						$app = JFactory::getApplication();
						$menuid =  $app->getMenu()->getActive()->id;
						$uri = JFactory::getURI();
						if (($this->params->get('useallcategories') == 0) && ($this->params->get('restrict_to_category') == 'yes')) {
							$catid = '&catid='.$this->params->get('mycategory');
						}
						else {
							$catid = '';
						}
                        if ($this->params->get('utf8_url_fix') ) {
						    $ret = base64_encode(urlencode($uri->toString()));
                        } else {
                            $ret = base64_encode($uri->toString());
                        }
						$link_new_article = JRoute::_('index.php?option=com_content&task=article.add&Itemid='.$menuid.$catid.'&return='.$ret);
				}
				else {//custom link
					$link_new_article = $custom_link;
				}
				
				if ($this->params->get('new_article_button_custom')) {
					$button_text = $this->params->get('new_article_button_text');
				}
				else {
					$button_text = JText::_('COM_UAM_NEW_ARTICLE');
				}
				
			?>
				<br />
				<button type="button" id="bt_new_article" onclick="location.href='<?php echo $link_new_article; ?>';">
					<img src="<?php echo 'components/com_uam/assets/images/' . $this->params->get('iconset') . '/ico_article_add.png'; ?>" alt="<?php echo $button_text; ?>" />
					<?php echo $button_text; ?>
				</button>
			<?php
			}
			?>
		</td>
	</tr>

	<tr>
		<?php
		if($this->params->get('id_column')) :
			?>
			<th style="" align="center">
				<?php echo JHTML::_('grid.sort', 'ID', 'c.id', $this->lists['order_Dir'], $this->lists['order']); ?>
			</th>
			<?php
		endif;
		?>
		<?php
		if($this->params->get('title_column')) :
			?>
			<th style="" align="center">
				<?php echo JHTML::_('grid.sort', 'COM_UAM_TITLE', 'c.title', $this->lists['order_Dir'], $this->lists['order']); ?>
			</th>
			<?php
		endif;
		?>
		<?php
		if($this->params->get('published_column')) :
			?>
			<th style="" align="center">
				<?php echo JHTML::_('grid.sort', 'COM_UAM_PUBLISHED_HEADING', 'c.state', $this->lists['order_Dir'], $this->lists['order']); ?>
			</th>
			<?php
		endif;
		?>
		<?php
		if($this->params->get('featured_column')) :
			?>
			<th style="" align="center">
				<?php echo JHTML::_('grid.sort', 'COM_UAM_FEATURED_HEADING', 'c.featured', $this->lists['order_Dir'], $this->lists['order']); ?>
			</th>
			<?php
		endif;
		?>
		<?php
		if($this->params->get('category_column')) :
			?>
			<th style="" align="center">
				<?php echo JHTML::_('grid.sort', 'COM_UAM_CATEGORY', 'category', $this->lists['order_Dir'], $this->lists['order']); ?>
			</th>
			<?php
		endif;
		?>
		<?php
		if($this->params->get('author_column')) :
			?>
			<th style="" align="center">
				<?php echo JHTML::_('grid.sort', 'COM_UAM_AUTHOR', 'author', $this->lists['order_Dir'], $this->lists['order']); ?>
			</th>
			<?php
		endif;
		?>
		<?php
		if($this->params->get('language_column')) :
			?>
			<th style="" align="center">
				<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_LANGUAGE', 'c.language', $this->lists['order_Dir'], $this->lists['order']); ?>
			</th>
			<?php
		endif;
		?>
		<?php
		if($this->params->get('created_date_column')) :
			?>
			<th style="" align="center">
				<?php echo JHTML::_('grid.sort', 'COM_UAM_CREATED_DATE', 'c.created', $this->lists['order_Dir'], $this->lists['order']); ?>
			</th>
			<?php
		endif;
		?>
		<?php
		if($this->params->get('start_publishing_column')) :
			?>
			<th style="" align="center">
				<?php echo JHTML::_('grid.sort', 'COM_UAM_START_PUBLISHING', 'c.publish_up', $this->lists['order_Dir'], $this->lists['order']); ?>
			</th>
			<?php
		endif;
		?>
		<?php
		if($this->params->get('finish_publishing_column')) :
			?>
			<th style="" align="center">
				<?php echo JHTML::_('grid.sort', 'COM_UAM_FINISH_PUBLISHING', 'c.publish_down', $this->lists['order_Dir'], $this->lists['order']); ?>
			</th>
			<?php
		endif;
		?>
		<?php
		if($this->params->get('hits_column')) :
			?>
			<th style="" align="center">
				<?php echo JHTML::_('grid.sort', 'COM_UAM_HITS', 'c.hits', $this->lists['order_Dir'], $this->lists['order']); ?>
			</th>
			<?php
		endif;
		?>
		<?php
		if($this->params->get('edit_alias_column')) :
			?>
			<th style="" align="center"><?php echo JText::_('COM_UAM_EDIT_ALIAS'); ?></th>
			<?php
		endif;
		?>
		<?php
		if($this->params->get('copy_column')) :
			?>
			<th style="" align="center"><?php echo JText::_('COM_UAM_COPY'); ?></th>
			<?php
		endif;
		?>
		<?php
		if($this->params->get('edit_column')) :
			?>
			<th style="" align="center"><?php echo JText::_('COM_UAM_EDIT'); ?></th>
			<?php
		endif;
		?>
		<?php
		if($this->params->get('trash_column')) :
			?>
			<th style="" align="center"><?php echo JText::_('COM_UAM_TRASH'); ?></th>
			<?php
		endif;
		?>
	</tr>
</thead>
<tfoot>
	<tr>
		<td colspan="<?php echo $this->total_columns; ?>" align="center">
			<?php echo $this->pagination->getListFooter(); ?>
		</td>
	</tr>
	<tr>
		<td colspan="<?php echo $this->total_columns; ?>" align="center">
			<br />
			<?php echo "<strong>com_uam_v".$this->params->get('version')."</strong>"; ?>
		</td>
	</tr>
</tfoot>
<tbody>

	<?php
	$count_itens = count($this->itens);

	//without article
	if(!$count_itens) { ?>
		<tr>
			<td colspan="<?php echo $this->total_columns; ?>" align="center">
				<?php echo JText::_('COM_UAM_NO_ARTICLES_FOUND'); ?>
			</td>
		</tr>
	<?php
	}
	else {
		$k = 0;
		for($i=0; $i < $count_itens; $i++) {
			$row = $this->getItem($i, $this->params);

			$asset	= 'com_content.article.'.$row->id;
			$this->access->canCreate = $user->authorise('core.create', 'com_content.category.'.$row->catid);
			// Check general edit permission first.
			$this->access->canPublish = $user->authorise('core.edit.state', $asset);
			// Check general edit permission first.
			$this->access->canEdit = $user->authorise('core.edit', $asset);
			// Now check if edit.own is available.
			$this->access->canEditOwn = $user->authorise('core.edit.own', $asset) && ($this->user->id == $row->created_by);
			?>
	
			<tr class="<?php echo "fual_row$k"; ?>">
				<?php
				if($this->params->get('id_column')) :
					?>
					<td align="center">
						<?php echo $row->id; ?>
					</td>
					<?php
				endif;
				?>
				<?php
				if($this->params->get('title_column')) :
					?>
					<td style="font-weight:bold;">
						<?php
						echo $this->getTitle($row, $row->params, $this->access);
						echo "<input type='hidden' id='fual_{$row->id}_title' value='{$row->title}' />";
						echo "<input type='hidden' id='fual_{$row->id}_alias' value='{$row->alias}' />";
						?>
					</td>
					<?php
				endif;
				?>
				<?php
				if($this->params->get('published_column')) :
					?>
					<td align="center">
						<?php
							echo $this->getPublishedIcon($row, $row->params, $this->access);
						?>
					</td>
					<?php
				endif;
				?>
				<?php
				if($this->params->get('featured_column')) :
					?>
					<td align="center">
						<?php
							$override = false;

							if(($this->access->canEdit || $this->access->canEditOwn) && $this->params->get('user_can_feature'))
							{
								$override = true;
							}
							
							if(($this->access->canPublish && $row->state != -2) ||
								($this->user->id == $row->created_by && $override)) {
								
								$url = "index.php?option=com_uam&view=uam&task=unFeature&cid={$row->id}&Itemid=" . JRequest::getInt('Itemid');
								$link = JRoute::_($url);
								echo "<a href='$link'>";

								$img = $this->baseurl . "/components/com_uam/assets/images/" . $this->params->get('iconset') . "/";
								$img .= ($row->featured > 0) ? "ico_featured.png" : "ico_not_featured.png";
								$alt = ($row->featured > 0) ? JText::_('COM_UAM_FEATURED') : JText::_('COM_UAM_NOT_FEATURED');
							
								echo "<img src='$img' alt='$alt' title='$alt' />";
	
								echo '</a>';
							}
							else {
								$img = $this->baseurl . "/components/com_uam/assets/images/" . $this->params->get('iconset') . "/";
								$img .= ($row->featured > 0) ? "bw_ico_featured.png" : "bw_ico_not_featured.png";
								$alt = ($row->state > 0) ? JText::_('COM_UAM_FEATURED') : JText::_('COM_UAM_NOT_FEATURED');
								echo "<img src='$img' alt='$alt' title='$alt' />";
							}
						?>
					</td>
					<?php
				endif;
				?>
				<?php
				if($this->params->get('category_column')) :
					?>
					<td>
						<a href="<?php echo ContentHelperRoute::getCategoryRoute($row->catid); ?>" style="font-weight:normal;">
							<?php echo $row->category; ?>
						</a>
					</td>
					<?php
				endif;
				?>
				<?php
				if($this->params->get('author_column')) :
					?>
					<td>
						<?php
                            if($this->params->get('author_cblink') && $this->params->get('found_cb')) {
                                echo "<a href=\"" . $_CB_framework->userProfileUrl($row->created_by) . "\" style=\"font-weight:normal;\">";
                            }
						    if((strlen(trim($row->created_by_alias))) && ($this->params->get('show_alias'))) {
							    echo $row->created_by_alias;
							    echo "<br />({$row->author})";
						    }
						    else {
							    echo $row->author;
						    }
                            if($this->params->get('author_cblink') && $this->params->get('found_cb')) {
                                echo "</a>";
                            }
						?>
					</td>
					<?php
				endif;
				?>
				<?php
				if($this->params->get('language_column')) :
					?>
					<td align="center">
					<?php if ($row->language=='*'):?>
						<?php echo JText::alt('JALL','language'); ?>
					<?php else:?>
						<?php echo $row->language_title ? $row->language_title : JText::_('JUNDEFINED'); ?>
					<?php endif;?>
					</td>
					<?php
				endif;
				?>
				<?php
				if($this->params->get('created_date_column')) :
					?>
					<td align="center">
						<?php echo JHTML::_('date', $row->created, JText::_('DATE_FORMAT_LC4')); ?>
					</td>
					<?php
				endif;
				?>
				<?php
				if($this->params->get('start_publishing_column')) :
					?>
					<td align="center">
						<?php echo JHTML::_('date', $row->publish_up, JText::_('DATE_FORMAT_LC4')); ?>
					</td>
					<?php
				endif;
				?>
				<?php
				if($this->params->get('finish_publishing_column')) :
					?>
					<td align="center">
						<?php
						if($row->publish_down == '0000-00-00 00:00:00') {
							echo JText::_('COM_UAM_NEVER');
						}
						else {
							echo JHTML::_('date', $row->publish_down, JText::_('DATE_FORMAT_LC4'));
						}
						?>
					</td>
					<?php
				endif;
				?>
				<?php
				if($this->params->get('hits_column')) :
					?>
					<td align="center">
						<?php echo $row->hits; ?>
					</td>
					<?php
				endif;
				?>
				<?php
				if($this->params->get('edit_alias_column')) :
					?>
					<td align="center">
						<?php
						if($row->state != -2 && ($this->access->canEdit || $this->access->canEditOwn)) {
							$img = $this->baseurl . "/components/com_uam/assets/images/" . $this->params->get('iconset') . "/ico_alias.png";
							$alt = JText::_('COM_UAM_EDIT_ALIAS');
							$title = $alt . ' :: ' . $row->alias;
							echo "<a href='javascript:void(0);' onclick='fualEditAlias({$row->id},event);'>";
							echo "<img src='$img' alt='$alt' title='$title' class='lhasTip' id='img_alias_{$row->id}' />";
							echo "<a/>";
						}
						else {
							$img = $this->baseurl . "/components/com_uam/assets/images/" . $this->params->get('iconset') . "/bw_ico_alias.png";
							$alt = JText::_('COM_UAM_EDIT_ALIAS');
							$title = $alt . ' :: ' . $row->alias;
							echo "<img src='$img' alt='$alt' title='$title' class='lhasTip' id='img_alias_{$row->id}' />";
						}
						?>
					</td>
					<?php
				endif;
				?>
				<?php
				if($this->params->get('copy_column')) :
					?>
					<td align="center">
						<?php
						if($row->state != -2) {
							$img = "<img src=" . $this->baseurl . "/components/com_uam/assets/images/" . $this->params->get('iconset') . "/ico_copy.png />";
//							$text = JHTML::_('image.site', 'ico_copy.png', '/components/com_uam/assets/images/' . $this->params->get('iconset') . '/', NULL, NULL, JText::_('Copy'));
							$url = "index.php?option=com_uam&controller=&task=copy&cid={$row->id}&Itemid=" . JRequest::getInt('Itemid');
							$msg_confirm = JText::_('COM_UAM_WOULD_YOU_LIKE_TO_CREATE_AN_ARTICLE_COPY', true);
							$attr = array('onclick'=>"if(!confirm('$msg_confirm')) { return false; }",
									"title"=>JText::_('COM_UAM_CREATE_A_COPY'));
							echo JHTML::_('link', JRoute::_($url), $img, $attr);
						}
						?>
					</td>
					<?php
				endif;
				?>
				<?php
				if($this->params->get('edit_column')) :
					?>
					<td align="center">
						<?php
							echo $this->getEditIcon($row, $row->params, $this->access);
						?>
					</td>
					<?php
				endif;
				?>
				<?php
				if($this->params->get('trash_column')) :
					?>
					<td align="center">
						<?php
						$override = false;

						if(($this->access->canEdit || $this->access->canEditOwn) && $this->params->get('user_can_trash'))
						{
							$override = true;
						}
							
						if($this->access->canPublish || ($this->user->id == $row->created_by && $override))
						{
							if($row->state == -2) {
								$msg_confirm = JText::_('COM_UAM_RESTORE_CONFIRM', true);
								$img = $this->baseurl . "/components/com_uam/assets/images/" . $this->params->get('iconset') . "/ico_restore.png";
								$alt = JText::_('COM_UAM_RESTORE_FROM_TRASH');
							}
							else {
								$msg_confirm = JText::_('COM_UAM_TRASH_CONFIRM', true);
								$img = $this->baseurl . "/components/com_uam/assets/images/" . $this->params->get('iconset') . "/ico_trash.png";
								$alt = JText::_('COM_UAM_MOVE_TO_TRASH');
							}
							$link = JRoute::_("index.php?option=com_uam&controller=&task=trash&cid={$row->id}&Itemid=" . JRequest::getInt('Itemid'));

							echo "<a href='$link' onclick=\"if(!confirm('$msg_confirm')) { return false; }\">";
							echo "<img src='$img' alt='$alt' title='$alt' />";
							echo "</a>";
						}
						else {
							if($row->state == -2) {
								$msg_confirm = JText::_('COM_UAM_RESTORE_CONFIRM', true);
								$img = $this->baseurl . "/components/com_uam/assets/images/" . $this->params->get('iconset') . "/bw_ico_restore.png";
								$alt = JText::_('COM_UAM_RESTORE_FROM_TRASH');
							}
							else {
								$msg_confirm = JText::_('COM_UAM_TRASH_CONFIRM', true);
								$img = $this->baseurl . "/components/com_uam/assets/images/" . $this->params->get('iconset') . "/bw_ico_trash.png";
								$alt = JText::_('COM_UAM_MOVE_TO_TRASH');
							}
							$link = JRoute::_("index.php?option=com_uam&controller=&task=trash&cid={$row->id}&Itemid=" . JRequest::getInt('Itemid'));

							echo "<img src='$img' alt='$alt' title='$alt' />";
						}
						?>
					</td>
					<?php
				endif;
				?>
			</tr>
			
			<?php
			$k = 1 - $k;
		}
	}
	?>

</tbody>
</table>

<input type="hidden" name="option" value="com_uam" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="view" value="uam" />
<input type="hidden" name="controller" value="" />
<input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid'); ?>" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />

<?php echo JHTML::_('form.token'); ?>
</form>

<?php
//load template form edit alias
echo $this->loadTemplate('edit_alias');
?>
