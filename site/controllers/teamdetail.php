<?php
 
// No direct access.
defined('_JEXEC') or die;
 
// Include dependancy of the main controllerform class
jimport('joomla.application.component.controllerform');
 
class BloodBowlControllerTeamDetail extends JControllerForm
{
 
        public function getModel($name = '', $prefix = '', $config = array('ignore_request' => true))
        {
                return parent::getModel($name, $prefix, array('ignore_request' => false));
        }
 
        public function submit()
        {
				// Check for request forgeries.
                JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
 
                // Initialise variables.
                //$app    = JFactory::getApplication();
                $model  = $this->getModel('teamdetail');
				$jinput = JFactory::getApplication()->input;
				$msg ="";
				$failure=FALSE;
				
                // Get the data from the form POST
                $data = $jinput->getVar('jform', array(), 'post', 'array');
				
				$this->setRedirect( JRoute::_( 'index.php?option=com_bloodbowl&view=teamdetail&show='. $data['team_id'] ) );
				if (JFactory::getUser()->guest==1) return false;


				// Get data from the model needed for validation
				$oldteam = $model->getTeam();
				$oldplayers = $model->getPlayers();
				$roster = $model->getRoster();
				$has_played = $model->getHasPlayed();
				
				$newteam = array();
				$newteam['id']          = $data['team_id'];
				$newteam['race']        = $data['race'];
				$newteam['RR']          = $data['RR'];
				$newteam['A_Coach']     = $data['A_Coach'];
				$newteam['CheerLeader'] = $data['CheerLeader'];
				$newteam['Apoth']       = $data['Apoth'];
				$newteam['TreasuryChange'] = 0;
				$newteam['teamvalue']   = $data['teamvalue'];
				$newteam['name']        = $data['team_name'];
				$newteam['comment']     = $data['comment'];
				
				$seats=array(1=>false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false);
				$newplayers = array();
				for ($i=1; $i<17; $i++)
				{
					unset($player);
					if (array_key_exists("player$i", $data))
					{
						if ($data["name$i"]!= $oldplayers[$i]->name || !empty($data["skill$i"]) || $data["del$i"]==1)
						{
							$player=new stdClass;
							$player->player_id = $data["player$i"];
							$player->name = $data["name$i"]; 
							if (!empty($data["skill$i"]))
							{
								$player->ach_skills = (strlen($oldplayers[$i]->ach_skills)>0 ? "{$oldplayers[$i]->ach_skills}, " : "") . $data["skill$i"];
							}
							if ($data["del$i"]==1)
							{
								$player->date_sold = date("Y-m-d H:i:s");
								if ($oldteam->matches==0) {
									$newteam['TreasuryChange'] += $oldplayers[$i]->cost;
								}
							}
							$newplayers[] = $player;
						}
					}
					if (array_key_exists("new$i", $data))
					{
						if ($data["new$i"]!="")
						{
							$addplayer=true;
							$player=new stdClass;
							$player->player_id = 'NULL';
							if (substr($data["new$i"],-1)=='j')
							{
								if ($oldplayers[0]->activeplayers<11)
								{
									$player->roster_id = substr($data["new$i"],0,-1);
									$player->position = $roster[$player->roster_id]->position ." journeyman";
									$player->date_bought = date("Y-m-d H:i:s");
									$oldplayers[0]->activeplayers++;
								}
								else
								{
									$msg .= "Du kan ikke tilføje journeymen til holdet når der er 11 eller flere aktive spillere. ";
									$addplayer=false;
								}
							}
							else
							{
								$player->roster_id = $data["new$i"];
								$player->position = $roster[$player->roster_id]->position;
								$player->date_bought = date("Y-m-d H:i:s");
								$newteam['TreasuryChange'] -= $roster[$player->roster_id]->cost;
							}
							$player->name = $data["name$i"];
							$player->nr = $i;
							$player->owned_by_team_id = $newteam['id'];
							
							// Check that it is legal to add a player of that type(position)
							if ($addplayer)
							{
								if ($oldplayers[0]->positions[$player->roster_id] < $roster[$player->roster_id]->max)
								{
									$newplayers[] = $player;
									$oldplayers[0]->positions[$player->roster_id]++;
								}
								else
								{
									$msg .= "Du kan kun have {$roster[$player->roster_id]->max} stk. {$roster[$player->roster_id]->position} på holdet. ";
								}
							}
						}
					}
				}
				
				
				// Validate team data
				if (JFactory::getUser()->id != $oldteam->coach && JFactory::getUser()->id != 65)
				{
					$msg .= "Only teamowners can edit a team. ";
					$failure=TRUE;
				}
				if ($newteam['id'] != $oldteam->id)
				{
					$msg .= "Team ID does not match. ";
					$failure=TRUE;
				}
				if ($newteam['race'] != $oldteam->race)
				{
					$msg .= "Race does not match. ";
					$failure=TRUE;
				}
				if ($newteam['name']=="")
				{
					$msg .= "The team must have a name. ";
					$failure=TRUE;
				}
				
				if (($oldteam->matches==0) || ($newteam['RR'] > $oldteam->RR))
				{
					if ($oldteam->matches==0)
					{
						$newteam['TreasuryChange'] -= ($newteam['RR']-$oldteam->RR)*$oldteam->RRcost;
					}
					else
					{
						$newteam['TreasuryChange'] -= ($newteam['RR']-$oldteam->RR)*2*$oldteam->RRcost;
					}
				}
				
				if ($oldteam->matches==0) 
				{
					$newteam['FF'] = $data['FF'];
					$newteam['TreasuryChange'] -= ($newteam['FF']-$oldteam->FF)*10000;
				}
				
				if (($oldteam->matches==0) || ($newteam['A_Coach'] > $oldteam->A_Coach))
				{
					$newteam['TreasuryChange'] -= ($newteam['A_Coach']-$oldteam->A_Coach)*10000;
				}
				
				if (($oldteam->matches==0) || ($newteam['CheerLeader'] > $oldteam->CheerLeader))
				{
					$newteam['TreasuryChange'] -= ($newteam['CheerLeader']-$oldteam->CheerLeader)*10000;
				}
				
				if ($newteam['Apoth'] > 1) $newteam['Apoth'] = 1;
				if (($oldteam->matches==0) || ($newteam['Apoth'] > $oldteam->Apoth))
				{
					$newteam['TreasuryChange'] -= ($newteam['Apoth']-$oldteam->Apoth)*50000;
					//JFactory::getApplication()->enqueueMessage("OBS! ". print_r($newteam['Apoth'],true) ." ". print_r($oldteam->Apoth,true), 'notice');
				}
				
				if ($newteam['TreasuryChange'] + $oldteam->Treasury < 0 )
				{
					$msg .= "Du har brugt flere penge end du har til rådighed. ";
					$failure = TRUE;
				}
				
				
				
				//$msg .= print_r($newteam, true) ."<br>". print_r($newplayers, true);
				if ($failure==TRUE)
				{
					JFactory::getApplication()->enqueueMessage("OBS! $msg", 'notice');
					return FALSE;
				}
	 
				// Now update the loaded data to the database via a function in the model
				$upditem = $model->updTeam($newteam, $newplayers);
 
				//$msg .= "<br>". print_r($newteam,true) ."<br>". print_r($oldteam,true) ."<br>";
				// check if ok and display appropriate message.  This can also have a redirect if desired.
				if (!in_array(false, $upditem)) 
				{
					JFactory::getApplication()->enqueueMessage('Update succeeded. '.$msg, 'message');
					//$this->setRedirect( JRoute::_( 'index.php?option=com_bloodbowl&view=teamdetail&show='. $data['team_id'] ) );
				} else 
				{
					$msg .= print_r($upditem, true);
					JFactory::getApplication()->enqueueMessage('Update failed. '.$msg, 'notice');
					$this->setRedirect( JRoute::_( 'index.php?option=com_bloodbowl&view=teamdetail&show='. $data['team_id'] .'&toggleedit=1' ) );
				}
				
                return true;
        }
		
		public function retireteam()
		{
			// Check for request forgeries.
               JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
 
            // Initialise variables.
            //$app    = JFactory::getApplication();
            $model  = $this->getModel('teamdetail');
			$jinput = JFactory::getApplication()->input;
 
            // Get the data from the form POST
            $data = JRequest::getVar('jform', array(), 'post', 'array');
			
			$teamdata = array();
			$teamdata['id'] = $data['team_id'];
			$teamdata['name'] = $data['team_name'] ."(R.I.P.)";
			$teamdata['retired'] = 2;

			if ($data['delteam']==1 && ($data['submitter_id']==JFactory::getUser()->id || $data['submitter_id']==65))
			{
				// Now update the loaded data to the database via a function in the model
				$upditem = $model->updTeam($teamdata);
				if (!in_array(false, $upditem))
				{
					$msg = '';
					JFactory::getApplication()->enqueueMessage('Update succeeded. '.$msg, 'message');
					$this->setRedirect( JRoute::_( 'index.php?option=com_bloodbowl&view=teamslist&show='. $data['submitter_id'] ) );
					return true;
				}
				$msg = 'Noget gik galt med databasen. ';
			}
			//$msg .= print_r($teamdata, true);
			//$msg .= "Data: ". print_r($data, true);
			JFactory::getApplication()->enqueueMessage('Update failed. '.$msg, 'notice');
			$this->setRedirect( JRoute::_( 'index.php?option=com_bloodbowl&view=teamdetail&show='. $teamdata['id'] ) );
			return false;
		}
		
		public function newteam()
		{
				// Check for request forgeries.
                JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
 
                // Initialise variables.
                //$app    = JFactory::getApplication();
                $model  = $this->getModel('teamdetail');
				$jinput = JFactory::getApplication()->input;
				$msg ="";
				$failure=FALSE;
				
                // Get the data from the form POST
                $data = $jinput->getVar('jform', array(), 'post', 'array');
				
				if (JFactory::getUser()->guest==1 || JFactory::getUser()->id!=$data['submitter_id']) return false;
				
				if ($data['race']>0 && !empty($data['name']) && $data['startval']>500000)
				{
					$upditem = $model->newTeam($data['race'], $data['name'], $data['startval'], $data['submitter_id']);
				}
				
				if ($upditem>0)
				{
					$this->setRedirect( JRoute::_( 'index.php?option=com_bloodbowl&view=teamdetail&show='. $upditem .'&toggleedit=1') );
					return true;
				}
				$this->setRedirect( JRoute::_( 'index.php?option=com_bloodbowl&view=teamslist&show='. $data['submitter_id'] ) );
				return false;
		}
 
}