<?php
// No direct access to this file
defined('_JEXEC') or die;
 
JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.tooltip');

JFactory::getDocument()->addStyleSheet(JURI::base() . 'media/com_bloodbowl/css/com_bloodbowl.css');
echo BloodBowlHelper::addMenubar();
?>
<?php
if ($this->isadmin) {
?>
<div style="float: right;">
	<?php echo $this->editbutton; ?>
</div>
<?php
} // endif "show edit button"
?>
<h1><?php echo $this->tourney->name; ?></h1>
<p><table>
	<tr>
		<th><?php echo JText::_('COM_BLOODBOWL_TOURNEY_TYPE'); ?></th>
		<th><?php echo JText::_('COM_BLOODBOWL_DATE_BEGIN'); ?></th>
		<th><?php echo JText::_('COM_BLOODBOWL_DATE_END'); ?></th>
		<th><?php echo JText::_('COM_BLOODBOWL_ADMINS'); ?></th>
		<?php
		if (!is_null($this->tourney->parent_id))
		{
			echo "<th>". JText::_('COM_BLOODBOWL_PARENT_TOUR') ."</th>";
		}
		if (!empty($this->tourney->children))
		{
			echo "<th>". JText::_('COM_BLOODBOWL_CHILD_TOUR') ."</th>";
		}
		?>
	</tr>
	<tr>
		<td><?php echo $this->tourney->type; ?></td>
		<td><?php echo $this->tourney->created; ?></td>
		<td><?php echo ( $this->tourney->finished > 0 ? $this->tourney->finished : ''); ?></td>
		<td><?php echo $this->admins; ?></td>
		<?php
		if (!is_null($this->tourney->parent_id))
		{
			echo "<td><a href=\"".JRoute::_('index.php?view=tourneydetail&show='. $this->tourney->parent_id )."\">". $this->tourney->parent_name ."</a></td>";
		}
		if (is_array($this->tourney->children))
		{
			echo "<td>";
			foreach ($this->tourney->children as $child)
			{
				echo "<a href=\"".JRoute::_('index.php?view=tourneydetail&show='. $child->tour_id )."\">". $child->name ."</a><br>";
			}
			echo "</td>";
		}
		?>
	</tr>
</table></p>

<?php
	if ($this->edit==1)
	{
		//include("admin.php");
		echo $this->loadTemplate('admin');
	}
	else
	{ ?>
<h2><?php echo JText::_('COM_BLOODBOWL_NEWS'); ?></h2>
<p><?php echo $this->tourney->news; ?></p>


<h2><?php echo JText::_('COM_BLOODBOWL_RULES'); ?></h2>
<p><?php echo $this->tourney->rules; ?></p>
<?php } ?>


<h2><?php echo JText::_('COM_BLOODBOWL_STANDING'); ?></h2>
<?php 
	if ($this->tourney->type=="rating") 
	{
		//include("rating.php");
		echo $this->loadTemplate('rating');
	}
	elseif ($this->tourney->type=="playoff") 
	{
		//include("playoff.php");
		echo $this->loadTemplate('playoff');
	}
	else // ($this->tourney->type=="round robin") 
	{
		//include("roundrobin.php");
		echo $this->loadTemplate('roundrobin');
	}
?>


<?php
if( !empty($this->matches) ) 
{ 
	echo BloodBowlHelper::showMatchHistory($this->matches, $this->tourney->tour_id, $this->matchstart);
}
?>
