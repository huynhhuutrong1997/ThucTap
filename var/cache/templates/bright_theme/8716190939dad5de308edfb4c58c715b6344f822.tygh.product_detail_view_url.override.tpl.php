<?php /* Smarty version Smarty-3.1.21, created on 2018-06-12 16:39:32
         compiled from "C:\xampp\htdocs\ThucTap\design\themes\responsive\templates\addons\wishlist\hooks\products\product_detail_view_url.override.tpl" */ ?>
<?php /*%%SmartyHeaderCode:20701715775b1fcd14b80a96-91737409%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '8716190939dad5de308edfb4c58c715b6344f822' => 
    array (
      0 => 'C:\\xampp\\htdocs\\ThucTap\\design\\themes\\responsive\\templates\\addons\\wishlist\\hooks\\products\\product_detail_view_url.override.tpl',
      1 => 1528810596,
      2 => 'tygh',
    ),
  ),
  'nocache_hash' => '20701715775b1fcd14b80a96-91737409',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'runtime' => 0,
    'product' => 0,
    'auth' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.21',
  'unifunc' => 'content_5b1fcd14baf723_82111467',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5b1fcd14baf723_82111467')) {function content_5b1fcd14baf723_82111467($_smarty_tpl) {?><?php if (!is_callable('smarty_function_set_id')) include 'C:/xampp/htdocs/ThucTap/app/functions/smarty_plugins\\function.set_id.php';
?><?php if ($_smarty_tpl->tpl_vars['runtime']->value['customization_mode']['design']=="Y"&&@constant('AREA')=="C") {
$_smarty_tpl->_capture_stack[0][] = array("template_content", null, null); ob_start();
$_smarty_tpl->tpl_vars['product_detail_view_url'] = new Smarty_variable("products.view?product_id=".((string)$_smarty_tpl->tpl_vars['product']->value['product_id']), null, 1);
if ($_smarty_tpl->parent != null) $_smarty_tpl->parent->tpl_vars['product_detail_view_url'] = clone $_smarty_tpl->tpl_vars['product_detail_view_url'];?>
<?php if ($_smarty_tpl->tpl_vars['product']->value['combination']) {?>
    <?php $_smarty_tpl->tpl_vars['product_detail_view_url'] = new Smarty_variable("products.view?product_id=".((string)$_smarty_tpl->tpl_vars['product']->value['product_id'])."&combination=".((string)$_smarty_tpl->tpl_vars['product']->value['combination']), null, 1);
if ($_smarty_tpl->parent != null) $_smarty_tpl->parent->tpl_vars['product_detail_view_url'] = clone $_smarty_tpl->tpl_vars['product_detail_view_url'];?>
<?php }
list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();
if (trim(Smarty::$_smarty_vars['capture']['template_content'])) {
if ($_smarty_tpl->tpl_vars['auth']->value['area']=="A") {?><span class="cm-template-box template-box" data-ca-te-template="addons/wishlist/hooks/products/product_detail_view_url.override.tpl" id="<?php echo smarty_function_set_id(array('name'=>"addons/wishlist/hooks/products/product_detail_view_url.override.tpl"),$_smarty_tpl);?>
"><div class="cm-template-icon icon-edit ty-icon-edit hidden"></div><?php echo Smarty::$_smarty_vars['capture']['template_content'];?>
<!--[/tpl_id]--></span><?php } else {
echo Smarty::$_smarty_vars['capture']['template_content'];
}
}
} else {
$_smarty_tpl->tpl_vars['product_detail_view_url'] = new Smarty_variable("products.view?product_id=".((string)$_smarty_tpl->tpl_vars['product']->value['product_id']), null, 1);
if ($_smarty_tpl->parent != null) $_smarty_tpl->parent->tpl_vars['product_detail_view_url'] = clone $_smarty_tpl->tpl_vars['product_detail_view_url'];?>
<?php if ($_smarty_tpl->tpl_vars['product']->value['combination']) {?>
    <?php $_smarty_tpl->tpl_vars['product_detail_view_url'] = new Smarty_variable("products.view?product_id=".((string)$_smarty_tpl->tpl_vars['product']->value['product_id'])."&combination=".((string)$_smarty_tpl->tpl_vars['product']->value['combination']), null, 1);
if ($_smarty_tpl->parent != null) $_smarty_tpl->parent->tpl_vars['product_detail_view_url'] = clone $_smarty_tpl->tpl_vars['product_detail_view_url'];?>
<?php }
}?><?php }} ?>
