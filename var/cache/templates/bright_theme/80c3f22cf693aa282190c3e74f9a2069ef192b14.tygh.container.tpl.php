<?php /* Smarty version Smarty-3.1.21, created on 2018-06-12 16:39:16
         compiled from "C:\xampp\htdocs\ThucTap\design\themes\responsive\templates\views\block_manager\render\container.tpl" */ ?>
<?php /*%%SmartyHeaderCode:10392283855b1fcd04d2ec89-65119765%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '80c3f22cf693aa282190c3e74f9a2069ef192b14' => 
    array (
      0 => 'C:\\xampp\\htdocs\\ThucTap\\design\\themes\\responsive\\templates\\views\\block_manager\\render\\container.tpl',
      1 => 1528810582,
      2 => 'tygh',
    ),
  ),
  'nocache_hash' => '10392283855b1fcd04d2ec89-65119765',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'layout_data' => 0,
    'container' => 0,
    'content' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.21',
  'unifunc' => 'content_5b1fcd04d34609_00145951',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5b1fcd04d34609_00145951')) {function content_5b1fcd04d34609_00145951($_smarty_tpl) {?><div class="<?php if ($_smarty_tpl->tpl_vars['layout_data']->value['layout_width']!="fixed") {?>container-fluid <?php } else { ?>container<?php }?> <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['container']->value['user_class'], ENT_QUOTES, 'UTF-8');?>
">
    <?php echo $_smarty_tpl->tpl_vars['content']->value;?>

</div><?php }} ?>
