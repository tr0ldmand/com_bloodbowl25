<?php
 
// No direct access.
defined('_JEXEC') or die;
 
// Include dependancy of the main controllerform class
jimport('joomla.application.component.controllerform');
 
class BloodBowlControllerTourneyDetail extends JControllerForm
{
 
        public function getModel($name = '', $prefix = '', $config = array('ignore_request' => true))
        {
                return parent::getModel($name, $prefix, array('ignore_request' => false));
        }
 
        public function admin()
		{
				// Check for request forgeries.
                JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
 
                // Initialise variables.
                $app    = JFactory::getApplication();
                $model  = $this->getModel('matchdetail');
				$jinput =& $app->input;
				
				// Get the data from the form POST
				$data = $jinput->getVar('jform', array(), 'post', 'array');
				
				// Validate input data
				if (strlen($data['news'])>30)
				{
					$news = $data['news'];
				}
				if (strlen($data['news'])>30)
				{
					$rules = $data['rules'];
				}
				if (isset($news) || isset($rules))
				{
					$tour = new stdClass;
					$tour->tour_id = $data['tour_id'];
					$tour->news = $news;
					$tour->newsdate = date('Y-m-d H:i:s');
					$tour->rules = $rules;
					$upditem = $model->updNewsRules($tour);
					//$upditem = false;
					if ($upditem)
					{
						JFactory::getApplication()->enqueueMessage('News/Rules update succeeded'.$msg, 'message');
						$failure = false;
					}
				}
				if ($data['match1']>0 && $data['match2']>0 && $data['match1']!=$data['match2'] && $data['tour_id']>0)
				{
					$match = new stdClass;
					$match->match_id   = NULL;
					$match->f_tour_id    = $data['tour_id'];
					$match->challenge    = 0;
					$match->submitter_id = $data['submitter_id'];
					//$match->gate         = 'NULL';
					$match->team1_id     = $data['match1'];
					$match->team2_id     = $data['match2'];
					$upditem = $model->updChallenge($match);
					if ($upditem)
					{
						//$msg .= "upditem: ".print_r($upditem, true);
						JFactory::getApplication()->enqueueMessage('Match creation succeeded'.$msg, 'message');
						$failure = false;
					}
				}
				if (is_array($data['teams']))
				{
					foreach ($data['teams'] AS $new)
					{
						if ($new>0) $newteams[] = $new;
					}
					$upditem = $this->getModel('tourneydetail')->addTeams($newteams, $data['tour_id']);
					if ($upditem)
					{
						JFactory::getApplication()->enqueueMessage('Team(s) successfully added. '.$msg, 'message');
						$failure = false;
					}
				}
				if (is_array($data['tours']))
				{
					foreach ($data['tours'] AS $new)
					{
						if ($new>0) $newtours[] = $new;
					}
					$upditem = $this->getModel('tourneydetail')->addTours($newtours, $data['tour_id']);
					if ($upditem)
					{
						JFactory::getApplication()->enqueueMessage('Child tourney(s) successfully added. '.$msg, 'message');
						$failure = false;
					}
				}
				
				
				// Now update the loaded data to the database via a function in the model
				//$upditem = $model->updMatch($match);
				
				
				//$msg .= print_r($data, true);
				// check if ok and display appropriate message.  This can also have a redirect if desired.
				if ($failure) 
				{
					JFactory::getApplication()->enqueueMessage('Update failed'.$msg, 'notice');
				} 
				$this->setRedirect( JRoute::_( 'index.php?option=com_bloodbowl&view=tourneydetail&show='. $data['tour_id'] ) );
                return true;
				
		}
		
		public function newchallenge()
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
				
				$match = new stdClass;
				$match->match_id   = NULL;
				$match->f_tour_id    = $data['tour_id'];
				$match->challenge    = 1;
				$match->submitter_id = $data['coach_id'];
				//$match->gate         = NULL;
				$match->team1_id     = $data['teamA'];
				$match->team2_id     = $data['teamB'];
				//$match->date_created = 'NOW';
				
				// Now update the loaded data to the database via a function in the model
				$upditem = $model->updMatch($match);
 
				//$msg = "<br>". print_r($match,true); // ."<br>". print_r($upditem,true) ."<br>";
				// check if ok and display appropriate message.  This can also have a redirect if desired.
				if ($upditem>0) 
				{
					JFactory::getApplication()->enqueueMessage('Update succeeded'.$msg, 'message');
					$this->setRedirect( JRoute::_( 'index.php?option=com_bloodbowl&view=matchdetail&show='. $upditem ) );
				} 
				else 
				{
					JFactory::getApplication()->enqueueMessage('Update failed'.$msg, 'notice');
					$this->setRedirect( JRoute::_( 'index.php?option=com_bloodbowl&view=tourneydetail&show='. $data['tour_id'] ) );
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
			if ($matchdata->team1_id>0 && $matchdata->team2_id>0 && $matchdata->team1_id!=$matchdata->team2_id && $matchdata->match_id>0)
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