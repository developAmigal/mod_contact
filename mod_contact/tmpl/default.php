<?php
/**
 * @package    mod_contact
 *
 * @author     Per Nielsen <support@amigal.dk>
 * @copyright  [COPYRIGHT]
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @link       [AUTHOR_URL]
 */

// No direct access to this file
defined('_JEXEC') or die;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\Component\Contact\Site\Helper\RouteHelper;
use Joomla\CMS\Layout\LayoutHelper;

$menuItem = $params->get('base'); // get the hook menu for module

$link='index.php?option=com_contact&view=contact&Itemid='.$menuItem; // create the base link


// create the default output for contacts
if( $list)
{
        $contactFields = $params->get('contactfields',array('name','telephone','mobile')); // get the list of contactfields to view
        

        // §html hold the htmlcode to output
    	$html = '
        <table class="table table-striped table-responsive ">
            <thead> 
                <tr scope="row">
            ';

        // get the list of contactskeys for tableheader
        foreach($list[0] as $key => $item){
            // is the the field in the contactfield to show as header
            if(in_array($key,$contactFields ) )
               $html .= '<th scope="col" class="" >'.JTEXT::_("MOD_CONTACT_CONTACT_TABLE_FIELD_".strtoupper($key)).'</th>';
        }
        // end the table
        $html .= '</thead>
            <tbody>
            ';
        
        // get the list of contacts
    foreach( $list as $item)
    {
        $html .= '<tr scope="row">'; 
        foreach($item as $key => $field)
            // is the key in contactsfield, then show
            if(in_array($key,$contactFields)  ){
                switch ($key){
                // is the key name, then show name as link, else just show the content
                case "name":
                  $html .= '<td scope="col" class="pedergriib-contact-name"><a href="'.Route::_(RouteHelper::getContactRoute($item->slug,$item->catid,null).'&Itemid='.$menuItem).'"  itemprop="url">' . $field . ' </a> </td>';
                  list($id,$alias) = explode(":",$item->slug);
                 break;

                 case "image":
                    $html .= '<td scope="col">';
                    if($field)
                    $html .= LayoutHelper::render(
                        'joomla.html.image',
                        [
                            'src'   => $field,
                            'alt'   => '',
                            'class' => 'contact-thumbnail img-thumbnail',
                        ]
                        );
                    $html .= '</td>';
                    break;

                default:
                    $html .= '<td scope="col" class="pedergriib-contact-name">' . $field . ' </td>';
            }
        }
        $html .= '</tr>';
    }

    $html .= '
            </tbody>
        </table>
        ';

    echo $html; // Output the HTML

}
else echo ' Mo items to show'; // If §list is empty, then show message
?>