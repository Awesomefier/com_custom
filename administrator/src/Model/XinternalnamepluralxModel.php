<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Custom
 * @author     awesome <accessthegoodies@gmail.com>
 * @copyright  2026 awesome
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Xnamespacex\Component\Custom\Administrator\Model;
// No direct access.
defined('_JEXEC') or die;

use \Joomla\CMS\MVC\Model\ListModel;
use \Joomla\Component\Fields\Administrator\Helper\FieldsHelper;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Language\Text;
use \Joomla\CMS\Helper\TagsHelper;
use \Joomla\Database\ParameterType;
use \Joomla\Utilities\ArrayHelper;
use Xnamespacex\Component\Custom\Administrator\Helper\CustomHelper;

/**
 * Methods supporting a list of Xinternalnamepluralx records.
 *
 * @since  1.0.0
 */
class XinternalnamepluralxModel extends ListModel
{
	/**
	* Constructor.
	*
	* @param   array  $config  An optional associative array of configuration settings.
	*
	* @see        JController
	* @since      1.6
	*/
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.id',
				'state', 'a.state',
				'created_by', 'a.created_by',
				'modified_by', 'a.modified_by',
				'lft', 'a.lft',
				'rgt', 'a.rgt',
				'level', 'a.level',
				'access', 'a.access',
				'path', 'a.path',
				'parent_id', 'a.parent_id',
				'title', 'a.title',
				'alias', 'a.alias',
				'xtextfieldx', 'a.xtextfieldx',
				'xtextareax', 'a.xtextareax',
				'xcheckboxx', 'a.xcheckboxx',
				'xcheckboxesx', 'a.xcheckboxesx',
				'xlistx', 'a.xlistx',
				'xcomboboxx', 'a.xcomboboxx',
				'xgrouplistx', 'a.xgrouplistx',
				'xmediamanagerx', 'a.xmediamanagerx',
				'xaccesiblemediamanagerx', 'a.xaccesiblemediamanagerx',
				'ximagelistx', 'a.ximagelistx',
				'xsqlqueryx', 'a.xsqlqueryx',
			);
		}

		parent::__construct($config);
	}


	

	

	

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   Elements order
	 * @param   string  $direction  Order direction
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// List state information.
		parent::populateState("a.lft", "ASC");

		$context = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $context);

		// Split context into component and optional section
		if (!empty($context))
		{
			$parts = FieldsHelper::extract($context);

			if ($parts)
			{
				$this->setState('filter.component', $parts[0]);
				$this->setState('filter.section', $parts[1]);
			}
		}
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string  $id  A prefix for the store id.
	 *
	 * @return  string A store id.
	 *
	 * @since   1.0.0
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.state');

		
		return parent::getStoreId($id);
		
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return  DatabaseQuery
	 *
	 * @since   1.0.0
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select', 'DISTINCT a.*'
			)
		);
		$query->from('`#__custom_` AS a');
		
		// Join over the users for the checked out user
		$query->select("uc.name AS uEditor");
		$query->join("LEFT", "#__users AS uc ON uc.id=a.checked_out");
			$query->where("a.level <> 0");
		

		// Filter by published state
		$published = $this->getState('filter.state');

		if (is_numeric($published))
		{
			$query->where('a.state = ' . (int) $published);
		}
		elseif (empty($published))
		{
			$query->where('(a.state IN (0, 1))');
		}

		// Filter by search in title
		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('a.id = ' . (int) substr($search, 3));
			}
			else
			{
				$search = $db->Quote('%' . $db->escape($search, true) . '%');
				$query->where('( a.xtextfieldx LIKE ' . $search . '  OR  a.xtextareax LIKE ' . $search . '  OR  a.xcheckboxx LIKE ' . $search . ' )');
			}
		}
		
		// Add the list ordering clause.
		$orderCol  = $this->state->get('list.ordering', "a.lft");
		$orderDirn = $this->state->get('list.direction', "ASC");

		if ($orderCol && $orderDirn)
		{
			$query->order($db->escape($orderCol . ' ' . $orderDirn));
		}

		return $query;
	}

	/**
	 * Get an array of data items
	 *
	 * @return mixed Array of data items on success, false on failure.
	 */
	public function getItems()
	{
		$items = parent::getItems();
		
		foreach ($items as $oneItem)
		{

				// Get the title of every option selected.

				$options = explode(',', $oneItem->xcheckboxesx);

				$options_text = array();

				foreach ((array) $options as $option)
				{
					$options_text[] = Text::_('COM_CUSTOM_XINTERNALNAMEPLURALX_XCHECKBOXESX_OPTION_' . strtoupper(str_replace(' ', '_',$option)));
				}

				$oneItem->xcheckboxesx = !empty($options_text) ? implode(',', $options_text) : $oneItem->xcheckboxesx;

			// Get the title of every option selected.

			$options      = explode(',', $oneItem->xlistx);

			$options_text = array();

			foreach ((array) $options as $option)
			{

				if ($option !== "")
				{
					$options_text[] = Text::_('COM_CUSTOM_XINTERNALNAMEPLURALX_XLISTX_OPTION_' . preg_replace('/[^A-Za-z0-9\_-]/', '',strtoupper(str_replace(' ', '_',$option))));
				}
			}

			$oneItem->xlistx = !empty($options_text) ? implode(', ', $options_text) : $oneItem->xlistx;
					$oneItem->xgrouplistx = !empty($oneItem->xgrouplistx) ? Text::_('COM_CUSTOM_XINTERNALNAMEPLURALX_XGROUPLISTX_OPTION_' . preg_replace('/[^A-Za-z0-9\_-]/', '',strtoupper(str_replace(' ', '_',$oneItem->xgrouplistx)))) : '';

			if (isset($oneItem->xsqlqueryx))
			{
				$values    = explode(',', $oneItem->xsqlqueryx);
				$textValue = array();

				foreach ($values as $value)
				{
					if (!empty($value))
					{
						$db = $this->getDbo();
						$query = "SELECT  FROM  WHERE .xsqlqueryx LIKE '" . $value . "'";
						$db->setQuery($query);
						$results = $db->loadObject();

						if ($results)
						{
							$textValue[] = $results->value;
						}
					}
				}

				$oneItem->xsqlqueryx = !empty($textValue) ? implode(', ', $textValue) : $oneItem->xsqlqueryx;
			}
		}

		return $items;
	}
}
