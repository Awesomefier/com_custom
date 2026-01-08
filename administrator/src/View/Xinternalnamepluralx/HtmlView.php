<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Custom
 * @author     awesome <accessthegoodies@gmail.com>
 * @copyright  2026 awesome
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Xnamespacex\Component\Custom\Administrator\View\Xinternalnamepluralx;
// No direct access
defined('_JEXEC') or die;

use \Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use \Xnamespacex\Component\Custom\Administrator\Helper\CustomHelper;
use \Joomla\CMS\Toolbar\Toolbar;
use \Joomla\CMS\Toolbar\ToolbarHelper;
use \Joomla\CMS\Language\Text;
use \Joomla\Component\Content\Administrator\Extension\ContentComponent;
use \Joomla\CMS\Form\Form;
use \Joomla\CMS\HTML\Helpers\Sidebar;
/**
 * View class for a list of Xinternalnamepluralx.
 *
 * @since  1.0.0
 */
class HtmlView extends BaseHtmlView
{
	protected $items;

	protected $pagination;

	protected $state;

	/**
	 * Display the view
	 *
	 * @param   string  $tpl  Template name
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function display($tpl = null)
	{
		$this->state = $this->get('State');
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');
		$this->filterForm = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new \Exception(implode("\n", $errors));
		}

		$this->addToolbar();

		$this->sidebar = Sidebar::render();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 */
	protected function addToolbar()
	{
		$state = $this->get('State');
		$canDo = CustomHelper::getActions();

		ToolbarHelper::title(Text::_('COM_CUSTOM_TITLE_XINTERNALNAMEPLURALX'), "generic");

		$toolbar = Toolbar::getInstance('toolbar');

		// Check if the form exists before showing the add/edit buttons
		$formPath = JPATH_COMPONENT_ADMINISTRATOR . '/src/View/Xinternalnamepluralx';

		if (file_exists($formPath))
		{
			if ($canDo->get('core.create'))
			{
				$toolbar->addNew('xinternalnamesingularx.add');
			}
		}

		if ($canDo->get('core.edit.state'))
		{
			$dropdown = $toolbar->dropdownButton('status-group')
				->text('JTOOLBAR_CHANGE_STATUS')
				->toggleSplit(false)
				->icon('fas fa-ellipsis-h')
				->buttonClass('btn btn-action')
				->listCheck(true);

			$childBar = $dropdown->getChildToolbar();

			if (isset($this->items[0]->state))
			{
				$childBar->publish('xinternalnamepluralx.publish')->listCheck(true);
				$childBar->unpublish('xinternalnamepluralx.unpublish')->listCheck(true);
				$childBar->archive('xinternalnamepluralx.archive')->listCheck(true);
			}
			elseif (isset($this->items[0]))
			{
				// If this component does not use state then show a direct delete button as we can not trash
				$toolbar->delete('xinternalnamepluralx.delete')
				->text('JTOOLBAR_EMPTY_TRASH')
				->message('JGLOBAL_CONFIRM_DELETE')
				->listCheck(true);
			}

			$childBar->standardButton('duplicate')
				->text('JTOOLBAR_DUPLICATE')
				->icon('fas fa-copy')
				->task('xinternalnamepluralx.duplicate')
				->listCheck(true);

			if (isset($this->items[0]->checked_out))
			{
				$childBar->checkin('xinternalnamepluralx.checkin')->listCheck(true);
			}

			if (isset($this->items[0]->state))
			{
				$childBar->trash('xinternalnamepluralx.trash')->listCheck(true);
			}
		}

		if ($canDo->get('core.admin'))
		{
			$toolbar->standardButton('refresh')
				->text('JTOOLBAR_REBUILD')
				->task('xinternalnamepluralx.rebuild');
		}

		// Show trash and delete for components that uses the state field
		if (isset($this->items[0]->state))
		{

			if ($this->state->get('filter.state') == ContentComponent::CONDITION_TRASHED && $canDo->get('core.delete'))
			{
				$toolbar->delete('xinternalnamepluralx.delete')
					->text('JTOOLBAR_EMPTY_TRASH')
					->message('JGLOBAL_CONFIRM_DELETE')
					->listCheck(true);
			}
		}

		if ($canDo->get('core.admin'))
		{
			$toolbar->preferences('com_custom');
		}

		// Set sidebar action
		Sidebar::setAction('index.php?option=com_custom&view=xinternalnamepluralx');
	}
	
	/**
	 * Method to order fields 
	 *
	 * @return void 
	 */
	protected function getSortFields()
	{
		return array(
			'a.`id`' => Text::_('JGRID_HEADING_ID'),
			'a.`state`' => Text::_('JSTATUS'),
			'a.`title`' => Text::_('COM_CUSTOM_XINTERNALNAMEPLURALX_TITLE'),
			'a.`alias`' => Text::_('COM_CUSTOM_XINTERNALNAMEPLURALX_ALIAS'),
			'a.`xtextfieldx`' => Text::_('COM_CUSTOM_XINTERNALNAMEPLURALX_XTEXTFIELDX'),
			'a.`xtextareax`' => Text::_('COM_CUSTOM_XINTERNALNAMEPLURALX_XTEXTAREAX'),
			'a.`xcheckboxx`' => Text::_('COM_CUSTOM_XINTERNALNAMEPLURALX_XCHECKBOXX'),
			'a.`xcheckboxesx`' => Text::_('COM_CUSTOM_XINTERNALNAMEPLURALX_XCHECKBOXESX'),
			'a.`xlistx`' => Text::_('COM_CUSTOM_XINTERNALNAMEPLURALX_XLISTX'),
			'a.`xgrouplistx`' => Text::_('COM_CUSTOM_XINTERNALNAMEPLURALX_XGROUPLISTX'),
			'a.`xaccesiblemediamanagerx`' => Text::_('COM_CUSTOM_XINTERNALNAMEPLURALX_XACCESIBLEMEDIAMANAGERX'),
			'a.`ximagelistx`' => Text::_('COM_CUSTOM_XINTERNALNAMEPLURALX_XIMAGELISTX'),
			'a.`xsqlqueryx`' => Text::_('COM_CUSTOM_XINTERNALNAMEPLURALX_XSQLQUERYX'),
		);
	}

	/**
	 * Check if state is set
	 *
	 * @param   mixed  $state  State
	 *
	 * @return bool
	 */
	public function getState($state)
	{
		return isset($this->state->{$state}) ? $this->state->{$state} : false;
	}
}
