<?php
// No direct access to this file
defined('_JEXEC') or die;
 
jimport('joomla.form.helper');
 
JFormHelper::loadFieldClass('list');
 
class JFormFieldBloodBowl extends JFormFieldList
{
        protected $type = 'BloodBowl';
 
        protected function getOptions()
        {
                $db = JFactory::getDBO();
                $query = $db->getQuery(true);
                $query->select('id,name');
                $query->from('#__bb_teams');
                $db->setQuery((string)$query);
                $messages = $db->loadObjectList();
                $options = array();
                if($messages){
                        foreach($messages as $message){
                                $options[] = JHtml::_('select.option', $message->id, $message->name);
                        }
                }
                $options = array_merge(parent::getOptions(), $options);
                return $options;
        }
}

?>