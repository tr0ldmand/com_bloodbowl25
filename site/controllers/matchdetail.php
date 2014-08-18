<?php
 
// No direct access.
defined('_JEXEC') or die;
 
// Include dependancy of the main controllerform class
jimport('joomla.application.component.controllerform');
 
class BloodBowlControllerMatchDetail extends JControllerForm
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
                $app    = JFactory::getApplication();
                $model  = $this->getModel('matchdetail');
				$jinput =& $app->input;
 
                // Get the data from the form POST
                //$data = JRequest::getVar('jform', array(), 'post', 'array');
				$data = $jinput->getVar('jform', array(), 'post', 'array');
				$data['mvp_1'] = $jinput->get('mvp_1','0','int');
				$data['mvp_2'] = $jinput->get('mvp_2','0','int');
				
				$match = new stdClass;
				$match->match_id     = $data['match_id'];
				$match->submitter_id = $data['submitter_id'];
				$match->gate         = $data['gate'];
				$match->ffactor1     = $data['ffactor1'];
				$match->ffactor2     = $data['ffactor2'];
				$match->fame1        = $data['fame1'];
				$match->fame2        = $data['fame2'];
				$match->income1      = $data['income1'];
				$match->income2      = $data['income2'];
				if (empty($data['date_created']))
					$match->date_created = date("Y-m-d H:i:s");
				$match->date_played  = $data['date_played'];
				$match->date_modified= date("Y-m-d H:i:s");
				$match->team1_score  = $data['team1_score'];
				$match->team2_score  = $data['team2_score'];
				$match->comment      = $data['comment'];
				$match->team1_id     = $data['team1_id'];
				$match->team2_id     = $data['team2_id'];
				$match->TV1          = $data['TV1'];
				$match->TV2          = $data['TV2'];
				
				$tds=0;
				$mvps=0;
				$msg ="";
				for ($j=1;$j<3;$j++)
				{
					//$teams[$j] = array();
					//$teams[$j]['id'] = $data['team'. $j .'_id'];
					for ($i=1;$i<17;$i++)
					{
						if (array_key_exists('playerid'. $j .'_'.$i,$data))
						{
							$player = new stdClass;
							$player->f_coach_id = 'NULL';
							$player->f_player_id = $data['playerid'. $j .'_'.$i];
							$player->f_match_id = $data['match_id'];
							$player->f_team_id = $data['team'. $j .'_id'];
							$player->mvp = 0;
							$player->cp = (int)$data['cp'. $j .'_'.$i];
							$player->td = (int)$data['td'. $j .'_'.$i];
								$tds += $player->td;
							$player->intcpt = (int)$data['intcpt'. $j .'_'.$i];
							$player->bh = (int)$data['bh'. $j .'_'.$i];
							$player->si = (int)$data['si'. $j .'_'.$i];
							$player->ki = (int)$data['ki'. $j .'_'.$i];
							$player->inj = ($data['inj'. $j .'_'.$i]<>'' ? $data['inj'. $j .'_'.$i] : 'NONE');
							if ($player->inj == 'DEAD') $player->sell = true;
							if ($data['mvp_'.$j] == $data['playerid'. $j .'_'.$i]) $player->mvp=1;
								$mvps += $player->mvp;
							//$teams[$j][] = $player;
							if (array_key_exists('journey'. $j .'_'.$i,$data))
							{
								JFactory::getApplication()->enqueueMessage("Vi er inde i journey. ", 'notice');
								if ($data['journey'. $j .'_'.$i]==1) $player->buy=true;
								else $player->sell = true;
							}
							$players[] = $player;
							
						}
					}
				}
				$failure=FALSE;
				// Validate match data
				if ($tds!=($match->team1_score+$match->team2_score))
				{
					$msg .= "Scoren stemmer ikke overens med antallet af TDs. ";
					$failure=TRUE;
				}
				if ($mvps!=2)
				{
					$msg .= "Begge hold skal tildeles en MVP. ";
					$failure=TRUE;
				}
				if ($failure)
				{
					JFactory::getApplication()->enqueueMessage("OBS! $msg", 'notice');
					//return FALSE;
				}
	 
				// Now update the loaded data to the database via a function in the model
				$upditem = $model->updMatch($match, $players);
 
				$msg = ""; //"<br>". print_r($data,true) ."<br>". print_r($players,true) ."<br>";
				// check if ok and display appropriate message.  This can also have a redirect if desired.
				if ($upditem) 
				{
					JFactory::getApplication()->enqueueMessage('Update succeeded'.$msg, 'message');
					$this->setRedirect( JRoute::_( 'index.php?option=com_bloodbowl&view=matchdetail&show='. $data['match_id'] ) );
				} else 
				{
					JFactory::getApplication()->enqueueMessage('Update failed'.$msg, 'notice');
					$this->setRedirect( JRoute::_( 'index.php?option=com_bloodbowl&view=matchdetail&show='. $data['match_id'] .'&toggleedit=1' ) );
				}
				
                return true;
        }
		
		public function updchallenge()
		{
			// Check for request forgeries.
               JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
 
            // Initialise variables.
            //$app    = JFactory::getApplication();
            $model  = $this->getModel('matchdetail');
			$jinput = JFactory::getApplication()->input;
 
            // Get the data from the form POST
            //$data = JRequest::getVar('jform', array(), 'post', 'array');
			$matchdata = new stdClass;
			$matchdata->match_id = $jinput->get('show','0','int');
			$matchdata->team1_id = $jinput->get('team1','0','int');
			$matchdata->team2_id = $jinput->get('team2','0','int');
			$matchdata->challenge = 0;
			$matchdata->submitter_id = JFactory::getUser()->id;
			
			
			$msg = 'Du skal vælge to hold. ';
			if ($matchdata->team1_id>0 && $matchdata->team2_id>0 && $matchdata->match_id>0)
			{
				// Now update the loaded data to the database via a function in the model
				if ($model->updChallenge($matchdata))
				{
					$msg = '';
					JFactory::getApplication()->enqueueMessage('Update succeeded. '.$msg, 'message');
					$this->setRedirect( JRoute::_( 'index.php?option=com_bloodbowl&view=matchdetail&show='. $matchdata->match_id .'&toggleedit=0' ) );
					return true;
				}
				$msg = 'Noget gik galt med databasen. ';
			}
			// $msg .= print_r($matchdata, true);
			JFactory::getApplication()->enqueueMessage('Update failed. '.$msg, 'notice');
			$this->setRedirect( JRoute::_( 'index.php?option=com_bloodbowl&view=matchdetail&show='. $matchdata->match_id .'&toggleedit=0' ) );
			return false;
		}
		
		public function delmatch()
		{
			// Check for request forgeries.
               JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
 
            // Initialise variables.
            //$app    = JFactory::getApplication();
            $model  = $this->getModel('matchdetail');
			$jinput = JFactory::getApplication()->input;
 
            // Get the data from the form POST
            $data = JRequest::getVar('jform', array(), 'post', 'array');

			if ($data['match_id']>0)
			{
				// Now update the loaded data to the database via a function in the model
				if ($model->delmatch($data['match_id']))
				{
					// $msg .= print_r($data,true);
					JFactory::getApplication()->enqueueMessage('Kamp slettet. '.$msg, 'message');
					$this->setRedirect( JRoute::_( 'index.php?option=com_bloodbowl&view=tourneydetail&show='. $data['tour_id'] ) );
					return true;
				}
				// $msg = 'Noget gik galt med databasen. ';
			}
			// $msg .= print_r($matchdata, true);
			JFactory::getApplication()->enqueueMessage('Update failed. '.$msg, 'notice');
			$this->setRedirect( JRoute::_( 'index.php?option=com_bloodbowl&view=matchdetail&show='. $data['match_id'] .'&toggleedit=0' ) );
			return false;
		}
 
}