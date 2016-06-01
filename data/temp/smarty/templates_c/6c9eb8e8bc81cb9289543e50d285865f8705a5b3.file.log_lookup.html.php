<?php /* Smarty version Smarty-3.1.17, created on 2016-05-16 17:03:51
         compiled from "D:\wamp\www\jlog\views\jlog\log_lookup.html" */ ?>
<?php /*%%SmartyHeaderCode:2668057398cf76e6cb9-97612771%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6c9eb8e8bc81cb9289543e50d285865f8705a5b3' => 
    array (
      0 => 'D:\\wamp\\www\\jlog\\views\\jlog\\log_lookup.html',
      1 => 1463134498,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2668057398cf76e6cb9-97612771',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'base' => 0,
    'action' => 0,
    'module' => 0,
    'level' => 0,
    'name' => 0,
    'time_start' => 0,
    'time_end' => 0,
    'con' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.17',
  'unifunc' => 'content_57398cf775d750_68514027',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57398cf775d750_68514027')) {function content_57398cf775d750_68514027($_smarty_tpl) {?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <base href="<?php echo $_smarty_tpl->tpl_vars['base']->value;?>
jlog/"></base>
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>LOG LOOKUP</title>
    
</head>
<body>
    
    <form action="<?php echo $_smarty_tpl->tpl_vars['action']->value;?>
" method="get" >
        <input type="hidden" name ="do" value="lookup" />
        <input type="text" name="module" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['module']->value)===null||$tmp==='' ? '' : $tmp);?>
" />填写module <br/>
        <input type="text" name="level" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['level']->value)===null||$tmp==='' ? '' : $tmp);?>
" />填写level  <br/>
        <input type="text" name="name" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['name']->value)===null||$tmp==='' ? '' : $tmp);?>
" />填写name  <br/>
        <input type="text" name="time_start" value="<?php echo $_smarty_tpl->tpl_vars['time_start']->value;?>
" />开始时间  <br/>
        <input type="text" name="time_end" value="<?php echo $_smarty_tpl->tpl_vars['time_end']->value;?>
" />结束时间  <br/>
        <br><br>
        <input type="text" name="con" value ="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['con']->value)===null||$tmp==='' ? '' : $tmp);?>
" /> content内部查询条件,例如 x=234 and b = 12。暂时只支持and
        <br><br><br>
        <input type="submit" name="submit" value="ok" />
    </form>
   
</body>
</html>
<?php }} ?>
