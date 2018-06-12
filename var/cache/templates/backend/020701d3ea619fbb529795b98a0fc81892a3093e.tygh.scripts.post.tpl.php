<?php /* Smarty version Smarty-3.1.21, created on 2018-06-12 16:39:02
         compiled from "C:\xampp\htdocs\ThucTap\design\backend\templates\addons\pingpp\hooks\index\scripts.post.tpl" */ ?>
<?php /*%%SmartyHeaderCode:19003292335b1fccf6f0f070-31621140%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '020701d3ea619fbb529795b98a0fc81892a3093e' => 
    array (
      0 => 'C:\\xampp\\htdocs\\ThucTap\\design\\backend\\templates\\addons\\pingpp\\hooks\\index\\scripts.post.tpl',
      1 => 1525668008,
      2 => 'tygh',
    ),
  ),
  'nocache_hash' => '19003292335b1fccf6f0f070-31621140',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.21',
  'unifunc' => 'content_5b1fccf6f14af5_16198517',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5b1fccf6f14af5_16198517')) {function content_5b1fccf6f14af5_16198517($_smarty_tpl) {?><?php if (!is_callable('smarty_function_script')) include 'C:/xampp/htdocs/ThucTap/app/functions/smarty_plugins\\function.script.php';
?><?php echo smarty_function_script(array('src'=>"js/addons/pingpp/config.js"),$_smarty_tpl);?>

<?php echo smarty_function_script(array('src'=>"js/addons/pingpp/payment.js"),$_smarty_tpl);?>
<?php }} ?>
