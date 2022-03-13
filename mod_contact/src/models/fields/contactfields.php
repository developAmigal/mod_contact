<?php

// get class definition
use Joomla\CMS\Factory;
use Joomla\CMS\Form\FormHelper;
//use Joomla\CMS\HTML\HTMLHelper;

// import calss JFormHelper
jimport('joomla.form.helper');
// get class predefinedlist
JFormHelper::loadFieldClass('predefinedlist');

/**
 * Fields to show for contacts
 * @package mod_contact
 * 
 * @copyright  Per Nielsen @ amigal.dk
 * @license GNU General Public License version 2 or later; see LICENSE.txt
 * 
 *  @since 4.0
 **/


class ContactFormFieldContactFields extends JFormFieldPredefinedList
{
    
	public $type = 'ContactFields'; // type of field
	
	// list of keys to hide
	protected $hidded = array(
	    "access",
	    "catid",
	    "published",
	    "checked_out",
	    "checked_out_time",
	    "user_id",
	    "ordering",
	    "params",
	    "created",
	    "created_by",
	    "modified",
	    "modified_by",
	    "created_by_alias",
	    "sortname1",
	    "sortname2",
	    "sortname3",
	    "metakey",
	    "metadata",
	    "metadesc",
	    "featured",
	    "publish_up",
	    "publish_down",
	    "version",
	    "author",
	    "author_email"
	   );

	
	/**
	 *  Get contact fields
	 * 
	 * @since 4.0
	 * 
	 **/
	
	function getOptions() {
	   
	   // make object of application
	    $app     = Factory::getApplication();
	    // get object factory of Component com_contact
		$factory = $app->bootComponent('com_contact')->getMVCFactory();
		// object contacts created of class CategoryModel from Component com_contact 
		$contacts = $factory->createModel('Category','Site', ['ignore_request' => true]);
		
		// set only 1 row
	    $contacts->setState('list.limit', 1);
	    
	    // get support for language
	    $lang = JFactory::getLanguage();
        $lang->load('mod_contact',JPATH_SITE);

        // get 1 object of contact in array
        $items = $contacts->getItems();
        
        // get public proberties of first contact object
	    $_fields=get_object_vars($items[0]);
	    
	    // define $contactFields as an array
        $contactFields= Array();
        
        /** 
         * if $_fields has items
         * then take each key of items and insert this as key in $contactFields with the value translate string MOD_CONTACT_CONTACT_TABLE_FIELD_$key
         * */
	    if($_fields){
	       foreach(array_keys($_fields) as $field) {
	           if(!in_array($field,$this->hidded))
	             $contactFields[$field]= JTEXT::_("MOD_CONTACT_CONTACT_TABLE_FIELD_".strtoupper($field));
	       }
	    }
	    
	    // return the list of values for the field
    	return $contactFields;
	}
}
?>