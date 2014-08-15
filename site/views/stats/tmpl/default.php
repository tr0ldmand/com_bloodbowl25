<?php
// No direct access to this file
defined('_JEXEC') or die;
 
JFactory::getDocument()->addStyleSheet(JURI::base() . 'media/com_bloodbowl/css/com_bloodbowl.css');
?>
 
<h1>TRC Blood Bowl Management Statstik</h1>
<?php
echo BloodBowlHelper::addMenubar();

echo 'alltime_topscorers';
echo $this->loadTemplate('alltime_topscorers');
echo '<br>';
/*echo 'alltime_casualties';
echo $this->loadTemplate('alltime_casualties');
echo '<br>';
echo 'alltime_completions';
echo $this->loadTemplate('alltime_completions');
echo '<br>';
echo 'alltime_kills';
echo $this->loadTemplate('alltime_kills');
echo '<br>';
echo 'alltime_interceptions';
echo $this->loadTemplate('alltime_interceptions');
echo '<br>';
echo 'alltime_starplayer';
echo $this->loadTemplate('alltime_starplayer');
echo '<br>';
echo 'coach_rating';
echo $this->loadTemplate('coach_rating');
echo '<br>';
echo 'player_hall_of_fame';
echo $this->loadTemplate('player_hall_of_fame');
echo '<br>';
echo 'race';
echo $this->loadTemplate('race');
echo '<br>';
*/

?>
