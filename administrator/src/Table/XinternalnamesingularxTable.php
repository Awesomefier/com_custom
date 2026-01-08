<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Custom
 * @author     awesome <accessthegoodies@gmail.com>
 * @copyright  2026 awesome
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Xnamespacex\Component\Custom\Administrator\Table;
// No direct access
defined('_JEXEC') or die;

use \Joomla\Utilities\ArrayHelper;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Access\Access;
use \Joomla\CMS\Language\Text;
use \Joomla\CMS\Table\Nested as Table;
use \Joomla\CMS\Versioning\VersionableTableInterface;
use Joomla\CMS\Tag\TaggableTableInterface;
use Joomla\CMS\Tag\TaggableTableTrait;
use \Joomla\Database\DatabaseDriver;
use \Joomla\CMS\Filter\OutputFilter;
use \Joomla\CMS\Filesystem\File;
use \Joomla\Registry\Registry;
use \Xnamespacex\Component\Custom\Administrator\Helper\CustomHelper;
use \Joomla\CMS\Helper\ContentHelper;


/**
 * Xinternalnamesingularx table
 *
 * @since 1.0.0
 */
class XinternalnamesingularxTable extends Table implements VersionableTableInterface, TaggableTableInterface
{
	use TaggableTableTrait;

	/**
     * Indicates that columns fully support the NULL value in the database
     *
     * @var    boolean
     * @since  4.0.0
     */
    protected $_supportNullValue = true;

	/**
	 * Check if a field is unique
	 *
	 * @param   string  $field  Name of the field
	 *
	 * @return bool True if unique
	 */
	private function isUnique ($field)
	{
		$db = $this->_db;
		$query = $db->getQuery(true);

		$query
			->select($db->quoteName($field))
			->from($db->quoteName($this->_tbl))
			->where($db->quoteName($field) . ' = ' . $db->quote($this->$field))
			->where($db->quoteName('id') . ' <> ' . (int) $this->{$this->_tbl_key});

		$db->setQuery($query);
		$db->execute();

		return ($db->getNumRows() == 0) ? true : false;
	}

	/**
	 * Constructor
	 *
	 * @param   JDatabase  &$db  A database connector object
	 */
	public function __construct(DatabaseDriver $db)
	{
		$this->typeAlias = 'com_custom.xinternalnamesingularx';
		parent::__construct('#__custom_', 'id', $db);
		$this->setColumnAlias('published', 'state');
		$this->getRootId();
	}

	/**
	 * Get the type alias for the history table
	 *
	 * @return  string  The alias as described above
	 *
	 * @since   1.0.0
	 */
	public function getTypeAlias()
	{
		return $this->typeAlias;
	}

	/**
	 * Overloaded bind function to pre-process the params.
	 *
	 * @param   array  $array   Named array
	 * @param   mixed  $ignore  Optional array or list of parameters to ignore
	 *
	 * @return  boolean  True on success.
	 *
	 * @see     Table:bind
	 * @since   1.0.0
	 * @throws  \InvalidArgumentException
	 */
	public function bind($array, $ignore = '')
	{
		$date = Factory::getDate();
		$task = Factory::getApplication()->input->get('task');
		$user = Factory::getApplication()->getIdentity();
		
		$input = Factory::getApplication()->input;
		$task = $input->getString('task', '');

		if ($array['id'] == 0 && empty($array['created_by']))
		{
			$array['created_by'] = Factory::getUser()->id;
		}

		if ($array['id'] == 0 && empty($array['modified_by']))
		{
			$array['modified_by'] = Factory::getUser()->id;
		}

		if ($task == 'apply' || $task == 'save')
		{
			$array['modified_by'] = Factory::getUser()->id;
		}

		// Support for alias field: alias
		if (empty($array['alias']))
		{
			if (empty($array['title']))
			{
				$array['alias'] = OutputFilter::stringURLSafe(date('Y-m-d H:i:s'));
			}
			else
			{
				if(Factory::getConfig()->get('unicodeslugs') == 1)
				{
					$array['alias'] = OutputFilter::stringURLUnicodeSlug(trim($array['title']));
				}
				else
				{
					$array['alias'] = OutputFilter::stringURLSafe(trim($array['title']));
				}
			}
		}


		// Support for checkbox field: xcheckboxx
		if (!isset($array['xcheckboxx']))
		{
			$array['xcheckboxx'] = 0;
		}

		// Support for multiple field: xlistx
		if (isset($array['xlistx']))
		{
			if (is_array($array['xlistx']))
			{
				$array['xlistx'] = implode(',',$array['xlistx']);
			}
			elseif (strpos($array['xlistx'], ',') != false)
			{
				$array['xlistx'] = explode(',',$array['xlistx']);
			}
			elseif (strlen($array['xlistx']) == 0)
			{
				$array['xlistx'] = '';
			}
		}
		else
		{
			$array['xlistx'] = '';
		}

		// Support for multiple field: xsqlqueryx
		if (isset($array['xsqlqueryx']))
		{
			if (is_array($array['xsqlqueryx']))
			{
				$array['xsqlqueryx'] = implode(',',$array['xsqlqueryx']);
			}
			elseif (strpos($array['xsqlqueryx'], ',') != false)
			{
				$array['xsqlqueryx'] = explode(',',$array['xsqlqueryx']);
			}
			elseif (strlen($array['xsqlqueryx']) == 0)
			{
				$array['xsqlqueryx'] = '';
			}
		}
		else
		{
			$array['xsqlqueryx'] = '';
		}

		if (isset($array['params']) && is_array($array['params']))
		{
			$registry = new Registry;
			$registry->loadArray($array['params']);
			$array['params'] = (string) $registry;
		}

		if (isset($array['metadata']) && is_array($array['metadata']))
		{
			$registry = new Registry;
			$registry->loadArray($array['metadata']);
			$array['metadata'] = (string) $registry;
		}

		if (!$user->authorise('core.admin', 'com_custom.xinternalnamesingularx.' . $array['id']))
		{
			$actions         = Access::getActionsFromFile(
				JPATH_ADMINISTRATOR . '/components/com_custom/access.xml',
				"/access/section[@name='xinternalnamesingularx']/"
			);
			$default_actions = Access::getAssetRules('com_custom.xinternalnamesingularx.' . $array['id'])->getData();
			$array_jaccess   = array();

			foreach ($actions as $action)
			{
				if (key_exists($action->name, $default_actions))
				{
					$array_jaccess[$action->name] = $default_actions[$action->name];
				}
			}

			$array['rules'] = $this->JAccessRulestoArray($array_jaccess);
		}

		// Bind the rules for ACL where supported.
		if (isset($array['rules']) && is_array($array['rules']))
		{
			$this->setRules($array['rules']);
		}

		return parent::bind($array, $ignore);
	}

	/**
	 * Method to store a row in the database from the Table instance properties.
	 *
	 * If a primary key value is set the row with that primary key value will be updated with the instance property values.
	 * If no primary key value is set a new row will be inserted into the database with the properties from the Table instance.
	 *
	 * @param   boolean  $updateNulls  True to update fields even if they are null.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   1.0.0
	 */
	public function store($updateNulls = true)
	{
		
		// Set location if necessary
    // Load original item if we're updating
    if ($this->id > 0)
    {
        $original = new self($this->_db);
        $original->load($this->id);

        if ($original->parent_id != $this->parent_id)
        {
            $this->setLocation((int) $this->parent_id, 'last-child');
        }
    }
    else
    {
        $this->setLocation((int) $this->parent_id, 'last-child');
    }
		return parent::store($updateNulls);
	}

	/**
	 * This function convert an array of Access objects into an rules array.
	 *
	 * @param   array  $jaccessrules  An array of Access objects.
	 *
	 * @return  array
	 */
	private function JAccessRulestoArray($jaccessrules)
	{
		$rules = array();

		foreach ($jaccessrules as $action => $jaccess)
		{
			$actions = array();

			if ($jaccess)
			{
				foreach ($jaccess->getData() as $group => $allow)
				{
					$actions[$group] = ((bool)$allow);
				}
			}

			$rules[$action] = $actions;
		}

		return $rules;
	}

	/**
	 * Overloaded check function
	 *
	 * @return bool
	 */
	public function check()
	{
		// If there is an ordering column and this is a new row then get the next ordering value
		if (property_exists($this, 'ordering') && $this->id == 0)
		{
			$this->ordering = self::getNextOrder();
		}
		
		// Check if alias is unique
		if (!$this->isUnique('alias'))
		{
			$count = 0;
			$currentAlias =  $this->alias;
			while(!$this->isUnique('alias')){
				$this->alias = $currentAlias . '-' . $count++;
			}
		}
		
		// Support for checkboxes field: xcheckboxesx
		$this->xcheckboxesx = implode(',', (array) $this->xcheckboxesx);

		// Support for subform field xaccesiblemediamanagerx
		if (is_array($this->xaccesiblemediamanagerx))
		{
			$this->xaccesiblemediamanagerx = json_encode($this->xaccesiblemediamanagerx, JSON_UNESCAPED_UNICODE);
		}

		return parent::check();
	}

	/**
	 * Define a namespaced asset name for inclusion in the #__assets table
	 *
	 * @return string The asset name
	 *
	 * @see Table::_getAssetName
	 */
	protected function _getAssetName()
	{
		$k = $this->_tbl_key;

		return $this->typeAlias . '.' . (int) $this->$k;
	}

	/**
	 * Returns the parent asset's id. If you have a tree structure, retrieve the parent's id using the external key field
	 *
	 * @param   Table   $table  Table name
	 * @param   integer  $id     Id
	 *
	 * @see Table::_getAssetParentId
	 *
	 * @return mixed The id on success, false on failure.
	 */
	protected function _getAssetParentId($table = null, $id = null)
	{
		// We will retrieve the parent-asset from the Asset-table
		$assetParent = Table::getInstance('Asset');

		// Default: if no asset-parent can be found we take the global asset
		$assetParentId = $assetParent->getRootId();

		// The item has the component as asset-parent
		$assetParent->loadByName('com_custom');

		// Return the found asset-parent-id
		if ($assetParent->id)
		{
			$assetParentId = $assetParent->id;
		}

		return $assetParentId;
	}

	//XXX_CUSTOM_TABLE_FUNCTION

	
    /**
     * Delete a record by id
     *
     * @param  mixed   $pk  Primary key value to delete. Optional
     * @param  boolean  $children  True to delete child nodes, false to move them up a level.
     * @return bool
     */
    public function delete($pk = null, $children = true)
    {
        $result = parent::delete($pk, $children);
        
        return $result;
    }

    /**
     * Add the root node to an empty table.
     *
     * @return    mixed  The id of the new root node or false on error.
     */
    public function addRoot()
    {
        $db = $this->_db;

        $checkQuery = $db->getQuery(true);
        $checkQuery->select('*');
        $checkQuery->from('#__custom_');
        $checkQuery->where('level = 0');

        $db->setQuery($checkQuery);

        if(empty($db->loadAssoc()))
        {
            $query = $db->getQuery(true)
            ->insert('#__custom_')
            ->set('parent_id = 0')
            ->set('lft = 0')
            ->set('rgt = 1')
            ->set('level = 0')
            ->set('title = ' . $db->quote('Root'))
            ->set('alias = ' . $db->quote('root'))
            ->set('access = 1')
            ->set('path = ' . $db->quote(''));
            $db->setQuery($query);

            if(!$db->execute())
            {
                return false;
            }
            return $db->insertid();
        }

        return true;
    }

    /**
     * Get root node id
     *
     * @return    int  The id of the root node.
     */

    public function getRootId() {
        $rootId = parent::getRootId();
        // If root is not set then create it 
        if ($rootId === false)
        {
            $rootId = $this->addRoot();
        } 
        return $rootId; 
    }
}
