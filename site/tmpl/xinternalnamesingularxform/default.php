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
use \Xnamespacex\Component\Custom\Site\Helper\CustomHelper;

$wa = $this->document->getWebAssetManager();
$wa->useScript('keepalive')
	->useScript('form.validate');
HTMLHelper::_('bootstrap.tooltip');

// Load admin language file
$lang = Factory::getLanguage();
$lang->load('com_custom', JPATH_SITE);

$user    = Factory::getApplication()->getIdentity();
$canEdit = CustomHelper::canUserEdit($this->item, $user);


?>

<div class="xinternalnamesingularx-edit front-end-edit">

<?php if ($this->params->get('show_page_heading')) : ?>
    <div class="page-header">
        <h1> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
    </div>
    <?php endif;?>
	<?php if (!$canEdit) : ?>
		<h3>
		<?php throw new \Exception(Text::_('COM_CUSTOM_ERROR_MESSAGE_NOT_AUTHORISED'), 403); ?>
		</h3>
	<?php else : ?>
		<?php if (!empty($this->item->id)): ?>
			<h1><?php echo Text::sprintf('COM_CUSTOM_EDIT_ITEM_TITLE', $this->item->id); ?></h1>
		<?php else: ?>
			<h1><?php echo Text::_('COM_CUSTOM_ADD_ITEM_TITLE'); ?></h1>
		<?php endif; ?>

		<form id="form-xinternalnamesingularx"
			  action="<?php echo Route::_('index.php?option=com_custom&task=xinternalnamesingularxform.save'); ?>"
			  method="post" class="form-validate form-horizontal" enctype="multipart/form-data">
			
	<input type="hidden" name="jform[id]" value="<?php echo isset($this->item->id) ? $this->item->id : ''; ?>" />

	<input type="hidden" name="jform[state]" value="<?php echo isset($this->item->state) ? $this->item->state : ''; ?>" />

				<?php echo $this->form->getInput('created_by'); ?>
				<?php echo $this->form->getInput('modified_by'); ?>
	<?php echo HTMLHelper::_('uitab.startTabSet', 'myTab', array('active' => 'xinternalnamesingularx')); ?>
	<?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'xinternalnamesingularx', Text::_('COM_CUSTOM_TAB_XINTERNALNAMESINGULARX', true)); ?>
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

	<?php echo HTMLHelper::_('uitab.endTab'); ?>
			<div class="control-group">
				<div class="controls">

					<?php if ($this->canSave): ?>
						<button type="submit" class="validate btn btn-primary">
							<span class="fas fa-check" aria-hidden="true"></span>
							<?php echo Text::_('JSUBMIT'); ?>
						</button>
					<?php endif; ?>
					<a class="btn btn-danger"
					   href="<?php echo Route::_('index.php?option=com_custom&task=xinternalnamesingularxform.cancel'); ?>"
					   title="<?php echo Text::_('JCANCEL'); ?>">
					   <span class="fas fa-times" aria-hidden="true"></span>
						<?php echo Text::_('JCANCEL'); ?>
					</a>
				</div>
			</div>

			<input type="hidden" name="option" value="com_custom"/>
			<input type="hidden" name="task"
				   value="xinternalnamesingularxform.save"/>
			<?php echo HTMLHelper::_('form.token'); ?>
		</form>
	<?php endif; ?>
</div>
