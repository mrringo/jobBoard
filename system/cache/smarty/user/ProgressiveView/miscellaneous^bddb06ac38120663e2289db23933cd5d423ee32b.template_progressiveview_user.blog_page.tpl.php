<?php /* Smarty version Smarty-3.1.13, created on 2015-07-09 19:27:53
         compiled from "template_progressiveview_user:templates/_system/miscellaneous/blog_page.tpl" */ ?>
<?php /*%%SmartyHeaderCode:6712559ee75901f7b3-10484436%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'bddb06ac38120663e2289db23933cd5d423ee32b' => 
    array (
      0 => 'template_progressiveview_user:templates/_system/miscellaneous/blog_page.tpl',
      1 => 1387805226,
      2 => 'template_progressiveview_user',
    ),
  ),
  'nocache_hash' => '6712559ee75901f7b3-10484436',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'GLOBALS' => 0,
    'content' => 0,
    'item' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_559ee759056057_42046893',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_559ee759056057_42046893')) {function content_559ee759056057_42046893($_smarty_tpl) {?><?php if ($_smarty_tpl->tpl_vars['GLOBALS']->value['is_ajax']){?>

	<?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['content']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value){
$_smarty_tpl->tpl_vars['item']->_loop = true;
?>
		<div class="blogAuthor"></div>
		<div class="blogPost">
			<a href="<?php echo $_smarty_tpl->tpl_vars['item']->value['link'];?>
" class="blogLink"><?php echo $_smarty_tpl->tpl_vars['item']->value['title'];?>
</a>
			<span class="blogDate"><?php echo $_smarty_tpl->tpl_vars['item']->value['date'];?>
</span>
			<div class="clr"></div>
			<span class="blogtext"><p><?php echo $_smarty_tpl->tpl_vars['item']->value['description'];?>
</p></span>
		</div>
		<div class="blogBottom"></div>
		<div class="clr"><br/></div>
	<?php }
if (!$_smarty_tpl->tpl_vars['item']->_loop) {
?>
		<br/><div class="text-center"><?php $_smarty_tpl->smarty->_tag_stack[] = array('tr', array()); $_block_repeat=true; echo $_smarty_tpl->smarty->registered_plugins['block']['tr'][0][0]->translate(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
There are no blog posts in the system.<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo $_smarty_tpl->smarty->registered_plugins['block']['tr'][0][0]->translate(array(), $_block_content, $_smarty_tpl, $_block_repeat); } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</div><br/>
	<?php } ?>

<?php }else{ ?>

	<div id="innerBlogContentDiv"></div>


	<!-- preloader row here -->
	<div id="ajax_preloader_blog" class="preloader">
		<img src="<?php echo $_smarty_tpl->tpl_vars['GLOBALS']->value['site_url'];?>
/templates/_system/main/images/ajax_preloader_circular_32.gif" />
	</div>


	<script language="javascript" type="text/javascript">
	

		function getBlogContent() {
			$('#ajax_preloader_blog').show();

			var ajaxUrl = "<?php echo $_smarty_tpl->tpl_vars['GLOBALS']->value['site_url'];?>
/ajax/";
			var ajaxParams = {
				'action' : 'get_blog_content',
				'listing_type[equal]' : 'Job'
			};

			$.get(ajaxUrl, ajaxParams, function(data) {
				$('#ajax_preloader_blog').hide();
				$('#innerBlogContentDiv').append(data);
			});
		}

		// make request for blog content after page loads
		$(function() {
			getBlogContent();
		});

	
	</script>

<?php }?><?php }} ?>