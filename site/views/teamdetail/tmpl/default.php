<?php
// No direct access to this file
defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.tooltip');

JFactory::getDocument()->addStyleSheet(JURI::base() . 'media/com_bloodbowl/css/com_bloodbowl.css');

if ($this->pop==false) 
{
	echo BloodBowlHelper::addMenubar();
	?>
	<div style="float: right;">
		<?php if ($this->iscoach==true) echo $this->editbutton;  ?>
		<?php if ($this->team->locked==0) echo $this->printbutton; ?>
	</div>
	<?php
} // endif "show edit button"
?>



<form class="form-validate" action="<?php echo JRoute::_('index.php?view=teamdetail&show='. $this->showteam); ?>" method="post" id="updteam" name="updteam">
<center>
	<?php if( $this->pop==false ) { ?>
		<img height="50px" width="50px" src="media/com_bloodbowl/images/icons/<?php echo $this->team->icon; ?>">
	<?php } ?>
	<h1><?php echo ($this->owner ? $this->form->getInput('team_name',null,$this->team->name) : $this->team->name ); ?></h1>
</center>
<?php if ($this->pop==false) echo ($this->owner ? $this->form->getInput('comment',null,$this->team->comment) : $this->team->comment ); ?>
<table id="roster" border="0" class="teamdetail" style="width: 100%;">
	<tr>
        <th align="center" style="text=align: center; width: 2%;">#</th>
        <th>Name</th>
        <th style="width: 14%;">Position</th>
        <th style="width: 2%;">Ma</th>
        <th style="width: 2%;">St</th>
        <th style="width: 2%;">Ag</th>
        <th style="width: 2%;">Av</th>
        <th>Skills</th>
        <th style="width: 3%;">Inj.</th>
        <th style="width: 2%;">Cp</th>
        <th style="width: 2%;">Td</th>
        <th style="width: 2%;">Int</th>
        <th style="width: 4%;">BH/S/Ki</th>
        <th style="width: 2%;">MVP</th>
        <th style="width: 2%;">SPP</th>
        <th style="width: 5%;">Value</th>
		<?php if ($this->owner==TRUE){?> 
			<th style="width: 2%;">Del</th>
		<?php }?> 
	</tr>
	<?php
		for ($i=1; $i<=16; $i++)
		{
			if ($this->players[$i]->cost==0)
			{
				$MNG=TRUE;
				$MNGSTYLE="mng";
			}
			else 
			{
				$MNG=FALSE;
				$MNGSTYLE="";
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
			$stylecenter = "background-color: $bgcolor; text-align:center;";
			$styleleft = "background-color: $bgcolor; text-align:left;";
			echo "<tr class=\"$MNGSTYLE\"><td style=\"$stylecenter\">".($this->owner ? $this->form->getInput("nr$i", null, "$i"):"$i")."</td>"; 
			echo "<td align=\"center\" style=\"$styleleft\">";
				echo ( $this->owner ?  $this->form->getInput("name$i", null, $this->players[$i]->name):"{$this->players[$i]->name}");
				echo $this->form->getInput("player$i", null, $this->players[$i]->player_id);
				echo " &nbsp;". ($this->debug ? $this->players[$i]->player_id : '');
			echo "</td>\n";
			if (array_key_exists($i,$this->players))
			{
				echo "<td style=\"$styleleft\"><img src=\"media/com_bloodbowl/images/p_icons/{$this->players[$i]->icon}.gif\" alt='player avatar'>{$this->players[$i]->position}</td>\n";
				echo "<td align=\"center\" style=\"$stylecenter\">". ($this->players[$i]->move == $this->players[$i]->orgmo ? $this->players[$i]->move : '<b>'. $this->players[$i]->move .'</b>') ."</td>\n";
				echo "<td align=\"center\" style=\"$stylecenter\">". ($this->players[$i]->strength == $this->players[$i]->orgst ? $this->players[$i]->strength : '<b>'. $this->players[$i]->strength .'</b>') ."</td>\n";
				echo "<td align=\"center\" style=\"$stylecenter\">". ($this->players[$i]->agility == $this->players[$i]->orgag ? $this->players[$i]->agility : '<b>'. $this->players[$i]->agility .'</b>') ."</td>\n";
				echo "<td align=\"center\" style=\"$stylecenter\">". ($this->players[$i]->armor == $this->players[$i]->orgar ? $this->players[$i]->armor : '<b>'. $this->players[$i]->armor .'</b>') ."</td>\n";
				echo "<td style=\"$stylecenter\">&nbsp;{$this->players[$i]->skills}";
				$ach_skills=($this->players[$i]->skills<>'' ? ', ' : '').$this->players[$i]->ach_skills;
				echo "<strong>".($this->players[$i]->ach_skills<>'' ? $ach_skills : '' )."</strong>\n";
				if ($this->players[$i]->newskill==TRUE)
				{
					if ($this->owner)
					{
						echo " <select id=\"jform_skill$i\" name=\"jform[skill$i]\" style=\"max-width: 8em;\">";
						echo "<option value=\"\">". JText::_('COM_BLOODBOWL_NEW_SKILL') ."</option>";
						foreach ($this->skills as $skill)
						{
							echo "<option value=\"{$skill->name}\">{$skill->name}({$skill->type})</option>";
						}
						unset($skill);
						echo "</select>";
					}
					else
					{
						echo '<br><b>'. JText::_('COM_BLOODBOWL_NEW_SKILL') .'</b>';
					}
				}
				echo "</td><td style=\"$stylecenter\">".($this->players[$i]->inj=='' ? '&nbsp;':$this->players[$i]->inj)."</td>\n";
				echo "<td align=\"center\" style=\"$stylecenter\">". $this->players[$i]->cp ."</td>\n";
				echo "<td align=\"center\" style=\"$stylecenter\">". $this->players[$i]->td ."</td>\n";
				echo "<td align=\"center\" style=\"$stylecenter\">". $this->players[$i]->intcpt ."</td>\n";
				echo "<td align=\"center\" style=\"$stylecenter\">". $this->players[$i]->bh ."/".  $this->players[$i]->si ."/".  $this->players[$i]->ki ."</td>\n";
				echo "<td align=\"center\" style=\"$stylecenter\">". $this->players[$i]->mvp ."</td>\n";
				//$spp = $this->players[$i]->cp + $this->players[$i]->td * 3 + $this->players[$i]->intcpt * 2 + $this->players[$i]->mvp * 5 + ( $this->players[$i]->bh + $this->players[$i]->si + $this->players[$i]->ki ) * 2;
				echo "<td align=\"center\" style=\"$stylecenter\">". $this->players[$i]->spp ."</td>\n";
				echo "<td align='right' style=\"$stylecenter\">". $this->players[$i]->cost ."</td>\n";
				if ($this->owner)
				{
					//$sellordel = ($this->players[$i]->bh == '' ? BB_SELLPLAYER:BB_DELPLAYER);
					//echo "<td style=\"$stylecenter\"><input type=button ".($this->owner ? '' : 'disabled=true')." id='". $this->players[$i]->player_id ."' name='". $this->players[$i]->player_id ."' value='".$sellordel."' onclick='delplayer(this.id)'></td>\n";
					echo "<td style=\"$stylecenter\">". $this->form->getInput("del$i") ."</td>\n";
				}
			}
			else
			{
				$x=1;
				if ($this->owner==TRUE)
				{
					$x++;
					echo "<td style=\"$stylecenter\">";
						echo "<select id=\"jform_new$i\" name=\"jform[new$i]\" style=\"max-width: 7em;\">";
							echo "<option value=\"\">". JText::_('COM_BLOODBOWL_NEW_PLAYER') ."</option>";
							foreach ($this->roster as $newplayer)
							{
								echo "<option value=\"{$newplayer->id}\">{$newplayer->position}|". $newplayer->cost/1000 ."K</option>";
								if ($newplayer->max>=12) echo "<option value=\"{$newplayer->id}j\">{$newplayer->position} journeyman|". $newplayer->cost/1000 ."K</option>";
							}
							unset($newplayer);
						echo "</select>";
					echo "</td>";
				}
				while ($x<=14)
				{
					echo "<td style=\"$stylecenter\">&nbsp;</td>";
					$x++;
				}
				if ($this->owner)
				{
					echo "<td style=\"$stylecenter\">&nbsp;</td>\n";
				}
			}
			echo "</tr>";
		}
	?>		
</table></p>
<p>
<table id="other" style="width:100%; padding: 1px;">
	<tr>
		<td width="25%"><strong><?php echo JText::_('COM_BLOODBOWL_COACH') .": <a href=\"". JRoute::_( 'index.php?view=teamslist&show='. $this->team->coach) ."\">". JFactory::getUser($this->team->coach)->name; ?></a></strong></td>
		<td width="25%"><strong><?php echo JText::_('COM_BLOODBOWL_RACE') .": <a href=\"". JRoute::_( 'index.php?view=teamslist&show=0&order=name&races='. $this->team->race) ."\">". $this->team->race; ?></a></strong></td>
		<td width="15%"><?php echo JText::_('COM_BLOODBOWL_REROLLS'); ?>:</td>
		<td width="5%"><?php echo ( $this->owner ? $this->form->getInput('RR', null, $this->team->RR):"{$this->team->RR}" ); ?></td>
		<td width="5%">X</td>
		<td width="15%"><?php echo $this->team->RRcost; ?></td>
		<td width="10%" align='right'><?php echo ($this->team->RR)*($this->team->RRcost); ?></td>
	</tr>
	<tr>
		<td><strong><?php echo JText::_('COM_BLOODBOWL_STARTVAL') .": ". round(($this->team->Startval)/1000000,2); ?>M</strong></td>
		<td><?php echo JText::sprintf('COM_BLOODBOWL_ACTIVE_OF_TOTAL', $this->players[0]->activeplayers , $this->players[0]->numplayers); ?>.</td>
		<td><?php echo JText::_('COM_BLOODBOWL_FANFACTOR') .": "; ?></td>
		<td><?php echo( $this->owner ? $this->form->getInput('FF', null, $this->team->FF) : $this->team->FF ); ?></td>
		<td>X</td>
		<td>10000</td>
		<td align='right'><?php echo ($this->team->FF)*10000; ?></td>
	</tr>
	<input type='hidden' id='FF' name='FF' value='<?php echo $this->FF; ?>'>
	<tr>
		<td><?php echo JText::_('COM_BLOODBOWL_TREASURY'); ?>:</td>
		<td align='left'><?php echo $this->team->Treasury; ?></td>
		<td><?php echo JText::_('COM_BLOODBOWL_A_COACHES'); ?>:</td>
		<td><?php echo ( $this->owner ? $this->form->getInput('A_Coach', null, $this->team->A_Coach) : $this->team->A_Coach ); ?></td>
		<td>X</td>
		<td>10000</td>
		<td align='right'><?php echo ($this->team->A_Coach)*10000; ?></td>
	</tr>
	<tr>
		<td><?php echo JText::_('COM_BLOODBOWL_TEAM_VALUE'); ?>:</td>
		<td align='left'><?php 
			echo $TV=($this->players[0]->playervalue)+($this->team->RR)*($this->team->RRcost)+($this->team->FF)*10000+($this->team->A_Coach)*10000+($this->team->CheerLeader)*10000+($this->team->Apoth)*50000; 
			echo $this->form->getInput('teamvalue', null, $TV);
			?></td>
		<td><?php echo JText::_('COM_BLOODBOWL_CHEERLEADERS'); ?>:</td>
		<td><?php echo ( $this->owner ? $this->form->getInput('CheerLeader', null, $this->team->CheerLeader) : $this->team->CheerLeader ); ?></td>
		<td>X</td>
		<td>10000</td>
		<td align='right'><?php echo ($this->team->CheerLeader)*10000; ?></td>
	</tr>
	<tr>
		<td><?php echo JText::_('COM_BLOODBOWL_INDUCEMENTVAL'); ?>:</td>
		<td align='left'><?php echo $TV=($this->players[0]->playervalue)+($this->team->RR)*($this->team->RRcost)+($this->team->FF)*10000+($this->team->A_Coach)*10000+($this->team->CheerLeader)*10000+($this->team->Apoth)*50000+$this->team->Treasury; ?></td>
		<td><?php echo JText::_('COM_BLOODBOWL_APOTH'); ?>:</td>
		<td><?php echo ( $this->owner ? $this->form->getInput('Apoth', null, $this->team->Apoth) : $this->team->Apoth ); ?></td>
		<td>X</td>
		<td>50000</td>
		<td align='right'><?php echo ($this->team->Apoth)*50000; ?></td>
	</tr>
	<tr>
		<td></td>
		<td><?php echo ($this->pop>0 ? date("d-m-Y",time()):'' ); ?></td>
		<td></td><td></td><td></td><td></td><td></td>
	</tr>
</table>

<?php if ($this->owner) { ?>
	<?php echo $this->form->getInput('team_id',null,$this->showteam); ?>
	<?php echo $this->form->getInput('race',null,$this->team->race); ?>
	<input type="hidden" name="option" value="com_bloodbowl" />
	<input type="hidden" name="view" value="teamdetail" />
	<input type="hidden" name="show" value="<?php echo $this->showteam; ?>" />
	<input type="hidden" name="task" value="teamdetail.submit" />
	<button type="submit" class="validate button"><?php echo JText::_('Submit'); ?></button>
	<?php echo JHtml::_('form.token'); ?>
<?php } /* end if is owner */ ?>
</form>

<?php if( $this->pop==false ) { ?>
<?php if( !empty($this->stats->win) ) { ?>
<p>
	<h2><?php echo JText::_('COM_BLOODBOWL_TEAM_STATS'); ?></h2>
	<table>
		<tr>
			<th><?php echo JText::_('COM_BLOODBOWL_MATCHES'); ?></th>
			<th><?php echo JText::_('COM_BLOODBOWL_WON');  ?></th>
			<th><?php echo JText::_('COM_BLOODBOWL_DRAW');  ?></th>
			<th><?php echo JText::_('COM_BLOODBOWL_LOST'); ?></th>
			<th><?php echo JText::_('COM_BLOODBOWL_SCORE'); ?></th>
			<th><?php echo JText::_('COM_BLOODBOWL_AVG_GATE'); ?></th>
			<th><?php echo JText::_('COM_BLOODBOWL_AVG_INCOME'); ?></th>
		</tr>
		<tr style="background-color: WhiteSmoke;">
			<td><?php echo $this->team->matches; ?></td>
			<td><?php echo $this->stats->win; ?></td>
			<td><?php echo $this->stats->draw; ?></td>
			<td><?php echo $this->stats->loss; ?></td>
			<td><?php echo $this->stats->td_for .":". $this->stats->td_against; ?></td>
			<td><?php echo round(($this->team->gate)/($this->team->matches),-2); ?> </td>
			<td><?php echo round(($this->team->income)/($this->team->matches),-3); ?> </td>
		</tr>
	</table>
	
<?php } /* End if show stats */ ?>

<?php if ($this->owner) { ?>
<fieldset class="newteam">
<legend><?php echo $this->delform->getLabel('delteam'); ?></legend>
<form action="<?php echo JRoute::_('index.php?view=teamslist&show='. JFactory::getUser()->id); ?>" method="post" id="delteam" name="delteam">
<?php echo $this->form->getInput('team_id',null,$this->showteam); ?>
	<?php echo $this->delform->getInput('team_name',null,$this->team->name); ?>
	<?php echo $this->delform->getInput('submitter_id',null,JFactory::getUser()->id); ?>
	<?php echo $this->delform->getInput('delteam'); ?>
	<input type="hidden" name="option" value="com_bloodbowl" />
	<input type="hidden" name="view" value="teamdetail" />
	<input type="hidden" name="show" value="<?php echo $this->showteam; ?>" />
	<input type="hidden" name="task" value="teamdetail.retireteam" />
	<button type="submit" class="button"><?php echo JText::_('Submit'); ?></button>
	<?php echo JHtml::_('form.token'); ?>
</form>
</fieldset>
<?php } /* end if is owner */ ?>

<?php 
if( !empty($this->matches) ) 
{ 
	echo BloodBowlHelper::showMatchHistory($this->matches);
}
 ?>
<?php } /* End if show print (pop) */ ?>


