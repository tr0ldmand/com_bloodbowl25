<?php

// No direct access to this file

defined('_JEXEC') or die('Restricted access');

 

jimport('joomla.application.component.modelitem');

 

class BloodBowlModelTourney extends JModelItem

{
        protected $item;
		var $navn;
		protected $tourneys;

        /**
         * Get the message
         * @return string The message to be displayed to the user
         */
        public function getItem() 
        {
                if (!isset($this->item)) {
                        $id = JRequest::getInt('id');
                        // Get a TableTourney instance
                        $table = $this->getTable('Tourney', 'BloodBowlTable');
 
                        // Load the message
                        $table->load($id);
 
                        // Assign the message
                        $this->item = $table->Navn;
                }
                return $this->item;
        }
		
		public function getNavn() 
        {
                if (!isset($this->navn)) {
                        $id = JFactory::getUser()->id;
						//$ida = JFactory::getApplication()->input->get('id','$temp','int');
                        // Get a TableHelloWorld instance
                        $table = $this->getTable('Tourney', 'BloodBowlTable');
 
                        // Load the message
                        $mynavn = $table->getBruger($id);
 
                        // Assign the message
                        $this->navn = "INFO: ". print_r($mynavn, true);
                }
                return $this->navn;
        }
		
		public function getTourneyList($limit=30)
		{
			if (!isset($this->tourneys))
			{
				$table = $this->getTable('Tourney','BloodBowlTable');
				
				$this->tourneys = $table->getTourneyList($limit);
			}
			return $this->tourneys;
		}
}

?>