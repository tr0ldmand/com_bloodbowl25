<?php
// No direct access to this file
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class BloodBowlViewTeamDetail extends JView

{

        function display($tpl = null) 
        {
                //$user &= JFactory::getUser();
				$jinput = JFactory::getApplication()->input;
				
				$this->showteam = $jinput->get('show','120','int');
				$this->edit = $jinput->get('toggleedit','0','int');
				$this->pop = $jinput->get('pop',false,'bool');

				
				// Assign data to the view
				//$this->limit = 6;
				$this->team = $this->get('team');
				$this->players = $this->get('players');
				$this->matches = $this->get('matches');
				$this->stats = $this->get('stats');
				$this->skills = $this->get('skills');
				$this->roster = $this->get('roster');
				$this->form = $this->get('Form');
				$this->delform = $this->get('FormDelTeam');
				$this->owner=FALSE;
				$this->iscoach=FALSE;
				$this->nextedit = (-1*$this->edit)+1;
				
				if ($this->team->coach == JFactory::getUser()->id || JFactory::getUser()->id == 65)
				{
					$this->owner=(bool)$this->edit;
					$this->iscoach=true;
				}
				if ($this->pop==false && $this->edit==0)
				{
					if ($this->get('unlockteam'))
					{
						JFactory::getApplication()->enqueueMessage("Holdet er opdateret, og klar til kamp.", 'message');
						$this->team->locked=false;
					}
					elseif ($this->iscoach)
					{
						JFactory::getApplication()->enqueueMessage("Holdet skal opdateres før det kan spille.", 'notice');
					}
				}
				
				$url = "index.php?view=teamdetail&show={$this->showteam}&pop=1&toggleedit=0&tmpl=component&layout=print";
				$status = 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=720,height=640,directories=no,location=no';
				$text = JHtml::_('image', 'system/printButton.png', JText::_('JGLOBAL_PRINT'), NULL, true);
				$attribs['title']	= JText::_('JGLOBAL_PRINT');
				$attribs['onclick'] = "window.open(this.href,'win2','".$status."'); return false;";
				$attribs['rel']		= 'nofollow';
				$this->printbutton = JHtml::_('link', JRoute::_($url), $text, $attribs);
				
				$url = "index.php?view=teamdetail&show={$this->showteam}&pop=0&toggleedit={$this->nextedit}";
				$text = JHtml::_('image', 'system/edit.png', JText::_('JGLOBAL_EDIT'), NULL, true);
				$this->editbutton = JHtml::_('link', JRoute::_($url), $text, null);
				
                // Display the view
                parent::display($tpl);
        }

}

?>