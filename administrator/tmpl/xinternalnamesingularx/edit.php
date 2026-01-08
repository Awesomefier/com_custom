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

$wa = $this->document->getWebAssetManager();
$wa->useScript('keepalive')
	->useScript('form.validate');
HTMLHelper::_('bootstrap.tooltip');
?>

<form
	action="<?php echo Route::_('index.php?option=com_custom&layout=edit&id=' . (int) $this->item->id); ?>"
	method="post" enctype="multipart/form-data" name="adminForm" id="xinternalnamesingularx-form" class="form-validate form-horizontal">

	
	<?php echo HTMLHelper::_('uitab.startTabSet', 'myTab', array('active' => 'xinternalnamesingularx')); ?>
	<?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'xinternalnamesingularx', Text::_('COM_CUSTOM_TAB_XINTERNALNAMESINGULARX', true)); ?>
	<div class="row-fluid">
		<div class="col-md-12 form-horizontal">
			<fieldset class="adminform">
				<legend><?php echo Text::_('COM_CUSTOM_FIELDSET_XINTERNALNAMESINGULARX'); ?></legend>
				<?php echo $this->form->renderField('parent_id'); ?>
				<?php echo $this->form->renderField('title'); ?>
				<?php echo $this->form->renderField('alias'); ?>
				<?php echo $this->form->renderField('xtextfieldx'); ?>
				<?php echo $this->form->renderField('xtextareax'); ?>
				<?php echo $this->form->renderField('xcheckboxx'); ?>
				<?php echo $this->form->renderField('xcheckboxesx'); ?>
				<?php echo $this->form->renderField('xlistx'); ?>
				<?php echo $this->form->renderField('xcomboboxx'); ?>
				<?php echo $this->form->renderField('xgrouplistx'); ?>
				<?php echo $this->form->renderField('xmediamanagerx'); ?>
				<?php echo $this->form->renderField('xaccesiblemediamanagerx'); ?>
				<?php echo $this->form->renderField('ximagelistx'); ?>
				<?php echo $this->form->renderField('xsqlqueryx'); ?>
				<?php if ($this->state->params->get('save_history', 1)) : ?>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('version_note'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('version_note'); ?></div>
					</div>
				<?php endif; ?>
			</fieldset>
		</div>
	</div>
	<?php echo HTMLHelper::_('uitab.endTab'); ?>
	<input type="hidden" name="jform[id]" value="<?php echo isset($this->item->id) ? $this->item->id : ''; ?>" />

	<input type="hidden" name="jform[state]" value="<?php echo isset($this->item->state) ? $this->item->state : ''; ?>" />

	<?php echo $this->form->renderField('created_by'); ?>
	<?php echo $this->form->renderField('modified_by'); ?>

	<?php if (Factory::getApplication()->getIdentity()->authorise('core.admin','custom')) : ?>
	<?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'permissions', Text::_('JGLOBAL_ACTION_PERMISSIONS_LABEL', true)); ?>
		<?php echo $this->form->getInput('rules'); ?>
	<?php echo HTMLHelper::_('uitab.endTab'); ?>
<?php endif; ?>
	<?php echo HTMLHelper::_('uitab.endTabSet'); ?>

	<input type="hidden" name="task" value=""/>
	<?php echo HTMLHelper::_('form.token'); ?>

</form>
