<?php
// No direct access to this file
defined('_JEXEC') or die;
 
?>
<p>
<form method="POST">

<?php echo JText::_('COM_BLOODBOWL_LIMIT_SHOWING_TO'); ?>: <select name="show"><?php echo $this->ekstraoption; ?><option value="all"><?php echo JText::_( 'COM_BLOODBOWL_ALL_TEAMS'); ?></option><option value="own"><?php echo JText::_( 'COM_BLOODBOWL_OWN_TEAMS'); ?></option></select>&nbsp;
<select name="races"><option value="all"><?php echo JText::_( 'COM_BLOODBOWL_ALL_RACES'); ?></option><?php
	foreach ($this->races as $race)
	{
		echo "<option value=\"". $race->name ."\" ". ($this->showraces == $race->name ? 'selected' : '') .">". $race->name ."</option>\n";
	}
?></select> 

<?php echo JText::_('COM_BLOODBOWL_SORT_BY'); ?>: <select name="order">
	<option value=<?php echo ($this->showorder=="name" ? "\"name\" selected" : "\"name\""); ?>><?php echo JText::_( 'COM_BLOODBOWL_TEAMS_ORDERBY_NAME' ); ?></option>
	<option value=<?php echo ($this->showorder=="race" ? "\"raced\" selected" : "\"race\""); ?>><?php echo JText::_( 'COM_BLOODBOWL_TEAMS_ORDERBY_RACE' ); ?></option>
	<option value=<?php echo ($this->showorder=="teamvalue" ? "\"teamvalue\" selected" : "\"teamvalue\""); ?>><?php echo JText::_( 'COM_BLOODBOWL_TEAMS_ORDERBY_TV_ASC' ); ?></option>
	<option value=<?php echo ($this->showorder=="teamvalued" ? "\"teamvalued\" selected" : "\"teamvalued\""); ?>><?php echo JText::_( 'COM_BLOODBOWL_TEAMS_ORDERBY_TV_DESC' ); ?></option>
	<option value=<?php echo ($this->showorder=="date" ? "\"dated\" selected" : "\"date\""); ?>><?php echo JText::_( 'COM_BLOODBOWL_TEAMS_ORDERBY_DATE' ); ?></option>
</select> 
<?php echo JText::_('COM_BLOODBOWL_PAGE') ?> <select name="start"><?php
for ($i=0; $i<($this->numberofpages); $i++)
{
	echo "<option value=\"$i\"". ($this->showstart==$i ? ' selected' : '') .">". ($i+1) ."</option>";
}
?></select> af <?php echo $this->numberofpages; ?>.
<input type="submit">
</form>
</p>