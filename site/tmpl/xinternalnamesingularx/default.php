<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Custom
 * @author     awesome <accessthegoodies@gmail.com>
 * @copyright  2026 awesome
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

use \Joomla\CMS\HTML\HTMLHelper;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Uri\Uri;
use \Joomla\CMS\Router\Route;
use \Joomla\CMS\Language\Text;
use \Joomla\CMS\Session\Session;
use Joomla\Utilities\ArrayHelper;

$canEdit = Factory::getApplication()->getIdentity()->authorise('core.edit', 'com_custom.' . $this->item->id);

if (!$canEdit && Factory::getApplication()->getIdentity()->authorise('core.edit.own', 'com_custom' . $this->item->id))
{
	$canEdit = Factory::getApplication()->getIdentity()->id == $this->item->created_by;
}
?>

<div class="item_fields">
<?php if ($this->params->get('show_page_heading')) : ?>
    <div class="page-header">
        <h1> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
    </div>
    <?php endif;?>
	<table class="table">
		

		<tr>
			<th><?php echo Text::_('COM_CUSTOM_FORM_LBL_XINTERNALNAMESINGULARX_PARENT_ID'); ?></th>
			<td><?php echo $this->item->parent_id; ?></td>
		</tr>

		<tr>
			<th><?php echo Text::_('COM_CUSTOM_FORM_LBL_XINTERNALNAMESINGULARX_TITLE'); ?></th>
			<td><?php echo $this->item->title; ?></td>
		</tr>

		<tr>
			<th><?php echo Text::_('COM_CUSTOM_FORM_LBL_XINTERNALNAMESINGULARX_ALIAS'); ?></th>
			<td><?php echo $this->item->alias; ?></td>
		</tr>

		<tr>
			<th><?php echo Text::_('COM_CUSTOM_FORM_LBL_XINTERNALNAMESINGULARX_XTEXTFIELDX'); ?></th>
			<td><?php echo $this->item->xtextfieldx; ?></td>
		</tr>

		<tr>
			<th><?php echo Text::_('COM_CUSTOM_FORM_LBL_XINTERNALNAMESINGULARX_XTEXTAREAX'); ?></th>
			<td><?php echo nl2br($this->item->xtextareax); ?></td>
		</tr>

		<tr>
			<th><?php echo Text::_('COM_CUSTOM_FORM_LBL_XINTERNALNAMESINGULARX_XCHECKBOXX'); ?></th>
			<td><?php echo $this->item->xcheckboxx; ?></td>
		</tr>

		<tr>
			<th><?php echo Text::_('COM_CUSTOM_FORM_LBL_XINTERNALNAMESINGULARX_XCHECKBOXESX'); ?></th>
			<td><?php echo $this->item->xcheckboxesx; ?></td>
		</tr>

		<tr>
			<th><?php echo Text::_('COM_CUSTOM_FORM_LBL_XINTERNALNAMESINGULARX_XLISTX'); ?></th>
			<td>
			<?php
			// Get the title of every option selected.
			$options      = \is_string($this->item->xlistx) ? explode(",", $this->item->xlistx) : ArrayHelper::fromObject($this->item->xlistx);
			$options_text = array();

			foreach ((array) $options as $option)
			{
				if (!empty($option))
				{
					$options_text[] = Text::_('COM_CUSTOM_XINTERNALNAMEPLURALX_XLISTX_OPTION_' . strtoupper(str_replace(' ', '_',$option)));
				}
			}

			echo !empty($options_text) ? implode(',', $options_text) : $this->item->xlistx;
			?></td>
		</tr>

		<tr>
			<th><?php echo Text::_('COM_CUSTOM_FORM_LBL_XINTERNALNAMESINGULARX_XCOMBOBOXX'); ?></th>
			<td><?php echo $this->item->xcomboboxx; ?></td>
		</tr>

		<tr>
			<th><?php echo Text::_('COM_CUSTOM_FORM_LBL_XINTERNALNAMESINGULARX_XGROUPLISTX'); ?></th>
			<td>
			<?php

			if (!empty($this->item->xgrouplistx) || $this->item->xgrouplistx === 0)
			{
				echo Text::_('COM_CUSTOM_XINTERNALNAMEPLURALX_XGROUPLISTX_OPTION_' . preg_replace('/[^A-Za-z0-9\_-]/', '',strtoupper(str_replace(' ', '_',$this->item->xgrouplistx))));
			}
			?></td>
		</tr>

		<tr>
			<th><?php echo Text::_('COM_CUSTOM_FORM_LBL_XINTERNALNAMESINGULARX_XMEDIAMANAGERX'); ?></th>
			<td>									<img src="<?php echo Uri::root() . $this->item->xmediamanagerx; ?>" alt="Preview" style="max-height: 50px;" />
</td>
		</tr>

		<tr>
			<th><?php echo Text::_('COM_CUSTOM_FORM_LBL_XINTERNALNAMESINGULARX_XACCESIBLEMEDIAMANAGERX'); ?></th>
			<td><?php echo $this->item->xaccesiblemediamanagerx; ?></td>
		</tr>

		<tr>
			<th><?php echo Text::_('COM_CUSTOM_FORM_LBL_XINTERNALNAMESINGULARX_XIMAGELISTX'); ?></th>
			<td><?php echo $this->item->ximagelistx; ?></td>
		</tr>

		<tr>
			<th><?php echo Text::_('COM_CUSTOM_FORM_LBL_XINTERNALNAMESINGULARX_XSQLQUERYX'); ?></th>
			<td><?php echo $this->item->xsqlqueryx; ?></td>
		</tr>

	</table>

</div>

<?php $canCheckin = Factory::getApplication()->getIdentity()->authorise('core.manage', 'com_custom.' . $this->item->id) || $this->item->checked_out == Factory::getApplication()->getIdentity()->id; ?>
	<?php if($canEdit && $this->item->checked_out == 0): ?>

	<a class="btn btn-outline-primary" href="<?php echo Route::_('index.php?option=com_custom&task=xinternalnamesingularx.edit&id='.$this->item->id); ?>"><?php echo Text::_("COM_CUSTOM_EDIT_ITEM"); ?></a>
	<?php elseif($canCheckin && $this->item->checked_out > 0) : ?>
	<a class="btn btn-outline-primary" href="<?php echo Route::_('index.php?option=com_custom&task=xinternalnamesingularx.checkin&id=' . $this->item->id .'&'. Session::getFormToken() .'=1'); ?>"><?php echo Text::_("JLIB_HTML_CHECKIN"); ?></a>

<?php endif; ?>

<?php if (Factory::getApplication()->getIdentity()->authorise('core.delete','com_custom.xinternalnamesingularx.'.$this->item->id)) : ?>

	<a class="btn btn-danger" rel="noopener noreferrer" href="#deleteModal" role="button" data-bs-toggle="modal">
		<?php echo Text::_("COM_CUSTOM_DELETE_ITEM"); ?>
	</a>

	<?php echo HTMLHelper::_(
                                    'bootstrap.renderModal',
                                    'deleteModal',
                                    array(
                                        'title'  => Text::_('COM_CUSTOM_DELETE_ITEM'),
                                        'height' => '50%',
                                        'width'  => '20%',
                                        
                                        'modalWidth'  => '50',
                                        'bodyHeight'  => '100',
                                        'footer' => '<button class="btn btn-outline-primary" data-bs-dismiss="modal">Close</button><a href="' . Route::_('index.php?option=com_custom&task=xinternalnamesingularx.remove&id=' . $this->item->id, false, 2) .'" class="btn btn-danger">' . Text::_('COM_CUSTOM_DELETE_ITEM') .'</a>'
                                    ),
                                    Text::sprintf('COM_CUSTOM_DELETE_CONFIRM', $this->item->id)
                                ); ?>

<?php endif; ?>