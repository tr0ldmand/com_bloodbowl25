<?php
// No direct access to this file
defined('_JEXEC') or die;
JFactory::getDocument()->addStyleSheet(JURI::base() . 'media/com_bloodbowl/css/com_bloodbowl_playoff.css');

$this->loadHelper('class_knockout');

$i=0; $j=0;

$KO = new KnockoutGD($this->competitors);
foreach (array_reverse($this->matches) as $match)
{
	if (!is_null($match->date_played))
	{
		$KO->setResByCompets($match->team1_id, $match->team2_id, (int)$match->team1_score, (int)$match->team2_score);
	}
}
$bracket = $KO->getBracket();

?>


<p><div id="info" style="width: 100%;"><dl>
<dt>Playoff træ</dt>

<?php
$ulclass = array(1=>"first", 2=>"second", 3=>"third");
foreach ($bracket as $round=>$contenders)
{
	echo "<dd><ul class=\"". $ulclass[$round] ."\">";
	foreach ($bracket[$round] as $key=>$first)
	{
		$class = ($class == "odd" ? "even" : "odd");
		if ($KO->isMatchPlayed($key, $round) && $first['s1']>$first['s2'])
		{
			echo  "<li class=\"$class\"><a href=\"". JRoute::_('index.php?view=teamdetail&show='. $first['c1'] ) ."\">". $this->teams[$first['c1']]->team_name ."</a> (". $first['s1'] .")</li>";
			echo  "<li class=\"$class\"><a id=\"loser\" href=\"". JRoute::_('index.php?view=teamdetail&show='. $first['c2'] ) ."\">". $this->teams[$first['c2']]->team_name ."</a> (". $first['s2'] .")</li>";
		}
		elseif ($KO->isMatchPlayed($key, $round) && $first['s1']<$first['s2'])
		{
			echo  "<li class=\"$class\"><a id=\"loser\" href=\"". JRoute::_('index.php?view=teamdetail&show='. $first['c1'] ) ."\">". $this->teams[$first['c1']]->team_name ."</a> (". $first['s1'] .")</li>";
			echo  "<li class=\"$class\"><a href=\"". JRoute::_('index.php?view=teamdetail&show='. $first['c2'] ) ."\">". $this->teams[$first['c2']]->team_name ."</a> (". $first['s2'] .")</li>";
		}
		elseif ($KO->isMatchCreated($key, $round))
		{
			echo  "<li class=\"$class\"><a href=\"". JRoute::_('index.php?view=teamdetail&show='. $first['c1'] ) ."\">". $this->teams[$first['c1']]->team_name ."</a> ". ($KO->isMatchPlayed($key, $round) ? '('. $first['s1'] .')' : '') ."</li>";
			echo  "<li class=\"$class\" id=\"bot\"><a href=\"". JRoute::_('index.php?view=teamdetail&show='. $first['c2'] ) ."\">". $this->teams[$first['c2']]->team_name ."</a> ". ($KO->isMatchPlayed($key, $round) ? '('. $first['s2'] .')' : '') ."</li>";
		}
		else
		{
			echo  "<li class=\"$class\">-1</li>";
			echo  "<li class=\"$class\">-1</li>";
		}
	}
	echo "</ul></dd>";
}
?>
</dl></div><div style="clear: both;"> </div></p>
