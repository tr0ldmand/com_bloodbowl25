<?php
// No direct access to this file
defined('_JEXEC') or die;

// require helper file 
JLoader::register('BloodBowlHelper', dirname(__FILE__) . DS . 'helpers' . DS . 'bloodbowl.php');

// import joomla controller library
jimport('joomla.application.component.controller');
 
//JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables');

// Get an instance of the controller prefixed by BloodBowl
$controller = JController::getInstance('BloodBowl');
 
// Perform the Request task
$controller->execute(JFactory::getApplication()->input->getCmd('task'));
 
// Redirect if set by the controller
$controller->redirect();
?>