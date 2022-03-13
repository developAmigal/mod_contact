<?php
/**
 * @package     mod_contact
 *
 * @copyright   (C) 2010 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// name space
namespace Joomla\Module\Contact\Site\Helper;

\defined('_JEXEC') or die;

use Joomla\CMS\Access\Access;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Date\Date;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Router\Route;
use Joomla\Component\Content\Administrator\Extension\ContentComponent;
use Joomla\Component\Content\Site\Helper\RouteHelper;
use Joomla\String\StringHelper;

/**
 * Helper for mod_contact
 * 
 * @since 4.0
 */
class ContactHelper
{
	/**
	 * Retrieve a list of contacts
	 * 
	 * @param   \Joomla\Registry\Registry  &$params  module parameters
	 * 
	 * @return array Array of selected contacts
	 * 
	 * @since 4,0
	 */
    public static function getList(&$params)
    {
        $app     = Factory::getApplication();
		// get contact component
		$factory = $app->bootComponent('com_contact')->getMVCFactory();
		// get model from contact component
		$contacts = $factory->createModel('Category','Site', ['ignore_request' => true]);
		
		// get parameters from Modul options
		$input     = $app->input;
		$appParams = $app->getParams();

		//perform selected contectfields for for filter options
		    
	    $setParams = $contacts->setState('params', $params);
		$contacts->setState('list.start', 0);
		$contacts->setState('filter.published', ContentComponent::CONDITION_PUBLISHED);
	   
		// Set the filters based on the module params
		$contacts->setState('list.limit', (int) $params->get('count', 0));
		$contacts->setState('load_tags', $params->get('show_tags', 0) || $params->get('contact_grouping', 'none') === 'tags');
		
		$contacts->setState('filter.category_id.include', (bool) $params->get('category_filtering_type', 1));
  		$contacts->setState('filter.tag', $params->get('filter_tag', array()));

		$contacts->setState('filter.featured', $params->get('show_front', 'show'));
		
		
		$access     = !ComponentHelper::getParams('com_contact')->get('show_noauth');
		$authorised = Access::getAuthorisedViewLevels(Factory::getUser()->get('id'));
		$contacts->setState('filter.access', $access);
		

       
		$items = $contacts->getItems(); // get contacts
		    
		$catids = $params->get('catids'); // get categories from option
		if($catids){
		    $_catids=array();
			// get all chikld categories
		    foreach($catids as $_catid){ 
		        $contacts->setState('category.id', $_catid);
		        $_temp = $contacts->getChildren();
		        if($_temp){
		            foreach($_temp as $child){
		            $_catids[] = $child->id; 
		            } 
		        }
		    }
		    $catids = array_merge($catids,$_catids); // merge child categories to selected categories
		}    
		
		$return=array();
		if($catids)
		// get only contacts with selected categories
		foreach($items as $item){
		  if( (int) in_array(strstr($item->catslug,":",true),$catids))
		        $return[] = $item;
		}
		else $return = $items;
		
		return $return;
    }
    
}
?>