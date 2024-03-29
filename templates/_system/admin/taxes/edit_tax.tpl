{breadcrumbs}<a href="{$GLOBALS.site_url}/manage-taxes/">[[Tax Rules]]</a> &#187; [[Edit Tax Rule]]{/breadcrumbs}

<h1><img src="{image}/icons/paperstar32.png" border="0" alt="" class="titleicon"/>[[Edit Tax Rule]]</h1>

{include file='errors.tpl'}

<fieldset >
	<legend>[[Edit Tax Rule]]</legend>
	<form method="post" enctype="multipart/form-data">
	<input type="hidden" name="event" value="save" id="event">
	<input type="hidden" name="sid" value="{$sid}">
	<input type="hidden" name="State" value="">
	<table>
		{foreach from=$form_fields item=form_field}
			<tr>
				<td valign="top" width="20%">[[$form_field.caption]]</td>
				<td valign="top" class="required">{if $form_field.is_required}*{/if}</td>
				<td>
					{input property=$form_field.id}{if $form_field.id == 'tax_rate'}%{/if}{if $form_field.comment}<div><small>{$form_field.comment}</small></div>{/if}
					{if $form_field.id == 'State'}
						<div style="display: none;" id="showProcess">
							<img src="{$GLOBALS.user_site_url}/templates/_system/main/images/ajax_preloader_circular_16.gif" alt="Please wait ..." />
							[[Please wait ...]]
						</div>
					{/if}
				</td>
			</tr>
		{/foreach}
		<tr>
			<td colspan="3">
				<div class="floatRight">
					<input type="submit" id="apply" value="[[Apply]]" class="greenButton"/>
					<input type="submit" value="[[Save]]" class="greenButton" />
				</div>
			</td>
		</tr>
	</table>
	</form>
</fieldset>

{literal}
<script>

	$('#apply').click(
		function(){
			$('#event').attr('value', 'apply_info');
		}
	);

	var img_obj = $("#showProcess");
	$('select[name="State"]').attr("disabled", "disabled");
	function get_regions(country_sid, state_sid){
		if (country_sid > 0) {
			$('select[name="State"]').hide();
			$("#showProcess").show();
			$.get("{/literal}{$GLOBALS.site_url}{literal}/get-states/", {country_sid: country_sid, state_sid: state_sid},function(data){
				$('select[name="State"]').parent().html(data).append('<div style="display: none;" id="showProcess">'+img_obj.html()+'</div>');
			});
		} else {
			$('select[name="State"]').attr("disabled", "disabled");
			$('select[name="State"]').val("");
		}
	}

	$('select[name="Country"]').change(
		function(){
			get_regions($(this).val());
		}
	);

	get_regions($('select[name="Country"]').val(),{/literal}{$state_sid}{literal});

</script>
{/literal}
