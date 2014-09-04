<?php
// No direct access to this file
defined("_JEXEC") or die('Restricted access');

JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.tooltip');

JFactory::getDocument()->addStyleSheet(JURI::base() . 'media/com_bloodbowl/css/com_bloodbowl.css');

$pop=FALSE;
$dis = ($this->match->disabled ? "disabled" : "");

echo BloodBowlHelper::addMenubar();

if ($this->match->challenge==1) 
{
	if ($this->match->isadmin || $this->match->iscoach)
	{
		//include("challenge.php");
		echo $this->loadTemplate('challenge');
	}
	else
	{
		echo "Denne udfordring er ikke blevet opdateret.";
	}
}
else
{
?>


<?php
//echo "editor:".$this->match->editor." & showedit:".$this->showedit." & Disabled:".$this->match->disabled." &played:".$this->match->date_played;
if ($this->match->editor || $this->showedit) {

?>
<div style="float: right;"><a href="<?php echo JRoute::_('index.php?view=matchdetail&show='. $this->match->match_id .'&toggleedit='. $this->nextedit ); ?>"><img src="media/com_bloodbowl/images/edit.png" border="0px"></a></div>
<?php
} // endif "show edit button"
?>
<form class="form-validate" action="<?php echo JRoute::_('index.php?view=matchdetail&show='. $this->match->match_id .'&toggleedit=0'); ?>" method="post" id="matchdetail" name="matchdetail">
<fieldset>
<DIV align="center">
	<table width="50%" align="center" style="text-align:center;">
		<tr>
			<td><img border="0px" height="60px" width="60px" src="media/com_bloodbowl/images/icons/<?php echo $this->match->team1_icon; ?>"></td>
			<td><h1><?php echo JText::_("COM_BLOODBOWL_MATCH_REPORT"); ?></h1></td>
			<td><img border="0px" height="60px" width="60px" src="media/com_bloodbowl/images/icons/<?php echo $this->match->team2_icon; ?>"></td>
		</tr>
		<tr>
			<td><?php
			$class="";
			if ($this->match->team1_locked==1) $class='red_box';
			echo "<div class=\"$class\"><h3><a href=\"". JRoute::_("index.php?view=teamdetail&show={$this->match->team1_id}" ) ."\">{$this->match->team1_name}</a></h3></div>";
			?></td>
			<td><h4><?php echo JText::_("COM_BLOODBOWL_VERSUS"); ?></h4></td>
			<td><?php
			$class="";
			if ($this->match->team2_locked==1)  $class='red_box';
			echo "<div class=\"$class\"><h3><a href=\"". JRoute::_("index.php?view=teamdetail&show={$this->match->team2_id}" ) ."\">{$this->match->team2_name}</a></h3></div>";
			
			?></td>
		</tr>

		<tr> 
			<td><?php echo ($this->match->editor ? $this->form->getInput('team1_score',null,$this->match->team1_score) : $this->match->team1_score ); ?></td>
			<td><strong><?php echo $this->form->getLabel('team1_score'); ?></strong></td>
			<td><?php echo ($this->match->editor ? $this->form->getInput('team2_score',null,$this->match->team2_score) : $this->match->team2_score ); ?></td>
		</tr>

		<tr>
			<td><?php echo ($this->match->editor ? $this->form->getInput('income1',null,$this->match->income1) : $this->match->income1 ); ?>k</td>
			<td><strong><?php echo $this->form->getLabel('income1'); ?></strong></td>
			<td><?php echo ($this->match->editor ? $this->form->getInput('income2',null,$this->match->income2) : $this->match->income2 ); ?>k</td>
		</tr>
		<tr>
			<td><?php echo ($this->match->editor ? $this->form->getInput('fame1',null,$this->match->fame1) : $this->match->fame1 ); ?></td>
			<td><strong><?php echo $this->form->getLabel('fame1'); ?></strong></td>
			<td><?php echo ($this->match->editor ? $this->form->getInput('fame2',null,$this->match->fame2) : $this->match->fame2 ); ?></td>
		</tr>
		<tr>
			<td><?php echo ($this->match->editor ? $this->form->getInput('ffactor1',null,$this->match->ffactor1) : $this->match->ffactor1 ); ?></td>
			<td><strong><?php echo $this->form->getLabel('ffactor1'); ?></strong></td>
			<td><?php echo ($this->match->editor ? $this->form->getInput('ffactor2',null,$this->match->ffactor2) : $this->match->ffactor2 ); ?></td>
		</tr>
		<?php
		if ( !$pop && $this->match->TV1 > 0)
		{
			//Teamvalues
			echo "<tr><td>". $this->match->TV1/10000 ."</td>\n";
			echo "<td><strong>". JText::_("COM_BLOODBOWL_TEAM_VALUE") ."</strong></td>\n";
			echo "<td>". $this->match->TV2/10000 ."</td>\n";
			echo "</tr>\n";
			echo $this->form->getInput('TV1',null,$this->match->TV1);
			echo $this->form->getInput('TV2',null,$this->match->TV2);
		}
		else
		{
			echo $this->form->getInput('TV1',null,$this->match->team1_teamvalue);
			echo $this->form->getInput('TV2',null,$this->match->team2_teamvalue);
		}
		
		if ( !$pop && ( $this->match->spiral1 > 0 || $this->match->spiral2 > 0))
		{
			//Spiralling expenses
			echo "<tr><td>". $this->match->spiral1 ."</td>\n";
			echo "<td><strong>". JText::_("COM_BLOODBOWL_SPIRAL") ."</strong></td>\n";
			echo "<td>". $this->match->spiral2 ."</td>\n";
			echo "</tr>\n";
			echo $this->form->getInput('spiral1',null,$this->match->spiral1);
			echo $this->form->getInput('spiral2',null,$this->match->spiral2);
		}
		
		if ( !$pop )
		{
			//Coach names
			echo "<tr><td style=\"$styleleft\">". JFactory::getUser($this->match->team1_coach)->name ."</td>\n";
			echo "<td><strong>". JText::_("COM_BLOODBOWL_COACH") ."</strong></td>";
			echo "<td style=\"$styleleft\">". JFactory::getUser($this->match->team2_coach)->name ."</td>\n";
			echo "</tr>\n";
		}
		
		if (!$pop && $this->match->c_rating_1 >0 )
		{
			echo "<tr><td>". $this->match->c_rating_1 ."</td>\n";
			echo "<td>";
			echo ($this->match->c_rat_change > 0 ? "(«&nbsp;". $this->match->c_rat_change .") ":"");
			echo "<strong>". JText::_("COM_BLOODBOWL_C_RATING") ."</strong>";
			echo ($this->match->c_rat_change < 0 ? " (". abs($this->match->c_rat_change) ."&nbsp;»)":"");
			echo "</td>\n";
			echo "<td>". $this->match->c_rating_2 ."</td>\n";
			echo "</tr>\n";
		}
		?>
</table>
</DIV>
<table>
	<tr>
		<td><strong><?php echo $this->form->getLabel('gate'); ?></strong></td>
		<td><?php echo ($this->match->editor ? $this->form->getInput('gate',null,$this->match->gate) : $this->match->gate ); ?></td>
	</tr>
	<tr>
		<td><strong><?php echo $this->form->getLabel('date_played'); ?></strong></td>
		<td>
		<?php
		if ($this->match->date_played == null && $this->match->editor) {
			$date_played = date("Y-m-d H:i:s");
		}else{
			$date_played = $this->match->date_played;
		}
		echo ($date_played == null ? "":( !$this->match->editor ? date("d-m-Y",strtotime($date_played)): $this->form->getInput('date_played',null,$date_played) ));
		echo $this->form->getInput('date_created',null,$this->match->date_created);
		?>
		</td>
	</tr>
	<tr>
		<td><strong><?php echo JText::_("COM_BLOODBOWL_TOUR_NAME"); ?></strong></td>
		<td><a href="<?php echo JRoute::_("index.php?view=tourneydetail&show={$this->match->f_tour_id}"); ?>"><?php echo $this->match->tour_name; ?></a></td>
	</tr>
	<tr>
		<td><strong><?php echo $this->form->getLabel('comment'); ?></strong></td>
		<td><?php 
			if ($this->match->editor)
			{
				//editorArea( 'editor1',  $this->match->comment , 'text', 600, 150, '50', '10' ) ;
				//echo "<textarea name=\"match_comment\" maxlength=\"300\">". $this->match->comment ."</textarea>";
				echo $this->form->getInput('comment',null,$this->match->comment);
			}
			else
			{
				echo $this->match->comment;
			}
		?></td>
	</tr>
</table>
</fieldset>
<fieldset>
<?php
$i=0;
foreach ($this->teams as $team)
{
echo "<h2>". $team->team_name ."</h2><br>";
$i++;
echo $this->form->getInput('team'. $i .'_id',null,$team->team_id);
?>
<table width='90%' align='center' style='text-align:center;'>
	<tr>
		<th width="10"><i>#</i></th>
		<th width="120"><i>Name</i></th>
		<th width="100"><i>Position</i></th>
		<th><i>CP</i></th>
		<th><i>TD</i></th>
		<th><i>Int's</i></th>
		<th><i>BH</i></th>
		<th><i>SI</i></th>
		<th><i>Ki</i></th>
		<th width="10"><i>MVP</i></th>
		<th><i>Inj</i></th>
		<th><i>Buy</i></th>
	</tr>
	<?php
	foreach ( $team->players as $player )
	{
		if ( $player->owned_by_team_id == $team->team_id )
		{
			if ($player->inj=="MNG")
			{
				$MNG=TRUE;
				$MNGO="<strike>";
				$MNGC="</strike>";
				$class="mng";
				$MNGSTYLE="text-decoration: line-through;";
			}
			else 
			{
				$MNGO=$MNGC="";
				$MNG=FALSE;
				$MNGSTYLE="";
				$class="";
			}
			
			//alternate bgcolor
			if ($bgcolor=='WhiteSmoke') 
			{
				$bgcolor='#FEE6BC';
			} 
			else 
			{
				$bgcolor   = 'WhiteSmoke';
			}
			$style = 'background-color:'.$bgcolor.';';
			echo "<tr class=\"$class\" style=\"$style\"><td>". $MNGO . $player->nr . $MNGC ."</td>\n";
			echo "<td>". $MNGO . $player->name ."&nbsp;". $MNGC ."</td>\n";
			echo "<td>". $MNGO . $player->position . $MNGC ."</td>\n";
			
			foreach (array('cp', 'td', 'intcpt', 'bh', 'si', 'ki') as $field) 
			{
                echo "<td>".( !$this->match->editor || $MNG ? ($player->$field==0 ? '&nbsp;':$player->$field):$this->form->getInput($field . $i .'_'. $player->nr, null, $player->$field)) ."&nbsp;</td>\n";
            }
			echo "<td><input ". (!$this->match->editor || $MNG ? 'DISABLED' : '') . " type=\"radio\" name=\"mvp_". $i ."\" value=\"".$player->player_id ."\" ". ($player->mvp==1 ? 'CHECKED' : '' ) ."></td>\n";
			if ($pop>0 || !$this->match->editor || $MNG)
			{
				echo "<td>". str_replace('NONE', '&nbsp;', $player->newinj) ."</td>";
			}
			else 
			{
				echo "<td>";
				echo $this->form->getInput('inj'. $i .'_'. $player->nr, null, $player->newinj);
				/*echo "<select name=\"inj_". $player->player_id ."\" ". ($this->match->disabled || $MNG ? 'DISABLED' : '') .">";
					echo "<option value=\"NONE\" ". ($player->newinj == "NONE" ? 'SELECTED' : '') .">None</option>\n";
					echo "<option value=\"MNG\" ".  ($player->newinj == "MNG" ? 'SELECTED' : '') .">MNG</option>\n";
					echo "<option value=\"NI\" ".   ($player->newinj == "NI" ? 'SELECTED' : '') .">Ni</option>\n";
					echo "<option value=\"MA\" ".   ($player->newinj == "MA" ? 'SELECTED' : '') .">Ma</option>\n";
					echo "<option value=\"AV\" ".   ($player->newinj == "AV" ? 'SELECTED' : '') .">Av</option>\n";
					echo "<option value=\"AG\" ".   ($player->newinj == "AG" ? 'SELECTED' : '') .">Ag</option>\n";
					echo "<option value=\"ST\" ".   ($player->newinj == "ST" ? 'SELECTED' : '') .">St</option>\n";
					echo "<option value=\"DEAD\" ". ($player->newinj == "DEAD" ? 'SELECTED' : '') .">Dead!</option>\n";                                
				echo "</select>";*/
				echo "</td>\n";
			}
			$journey="<input type=\"hidden\" name=\"jform[journey{$i}_{$player->nr}]\" value=\"0\">";
			echo "<td>&nbsp;". ( $this->match->editor && !$pop &&  strpos($player->position,"journey") > 0 ? "$journey". $this->form->getInput('journey'. $i .'_'. $player->nr):"" );
			echo $this->form->getInput('playerid'. $i .'_'. $player->nr, null, $player->player_id);
			echo "</td>\n</tr>\n";
			
		}
		//$playerlist .= $player->player_id;
	}
	?>
</table><br>
<?php
}
?>
</fieldset>
<?php
	if (is_null($this->match->date_played) && ($this->match->team1_locked==1 || $this->match->team2_locked==1))
	{
		echo "<div class=\"red_box\">". JText::_('COM_BLOODBOWL_A_TEAM_IS_LOCKED') .". </div>";
	}
	elseif ($this->match->editor) {
?>
		<fieldset>
			<?php echo $this->form->getInput('match_id',null,$this->match->match_id); ?>
			<?php echo $this->form->getInput('submitter_id',null,JFactory::getUser()->id); ?>
			<input type="hidden" name="option" value="com_bloodbowl" />
			<input type="hidden" name="view" value="matchdetail" />
			<input type="hidden" name="show" value="<?php echo $this->match->match_id; ?>" />
			<input type="hidden" name="task" value="matchdetail.submit" />
			<button type="submit" class="validate button"><?php echo JText::_('Submit'); ?></button>
			<?php echo JHtml::_('form.token'); ?>
        </fieldset>
<?php } /* end if */ ?>
</form><br>


<?php
} // End not challenge
if ($this->match->editor && ($this->match->challenge==1 || ($this->match->isadmin && !$this->match->disabled)))
{
?>
<div style="float: right;">
<form class="form-validate" action="<?php echo JRoute::_('index.php?view=matchdetail&show='. $this->match->match_id .'&toggleedit=0'); ?>" method="post" id="delmatch" name="delmatch">
	<?php echo $this->delform->getInput('match_id',null,$this->match->match_id); ?>
	<?php echo $this->delform->getInput('tour_id',null,$this->match->f_tour_id); ?>
	<input type="hidden" name="option" value="com_bloodbowl" />
	<input type="hidden" name="view" value="matchdetail" />
	<input type="hidden" name="show" value="<?php echo $this->match->match_id; ?>" />
	<input type="hidden" name="task" value="matchdetail.delmatch" />
	<button type="submit" class="button"><?php echo JText::_('COM_BLOODBOWL_DELETE_MATCH'); ?></button>
	<?php echo JHtml::_('form.token'); ?>
</form>
</div><br>
<?php
} // End isadmin can delete
// echo "<td><a href=\"". JRoute::_("index.php?view=tourneydetail&show=". $tourney->tour_id ) ."\">". $tourney->name ."</a></td>\n";
/* <pre><?php print_r($this->match); ?></pre> */
/* <pre><?php print_r($player); ?></pre> */
?>
