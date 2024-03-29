{if $errors}
	{foreach from=$errors key=error item=error_data}
	    <p class="error">
			{if $error == 'NOT_IMPLEMENTED'}[[There is something missing in the code]]<br />{/if}
			{if $error == 'INVOICE_ID_IS_NOT_SET'}[[Callback parameters are missing required payment information.]]<br />{/if}
			{if $error == 'NONEXISTED_INVOICE_ID_SPECIFIED'}[[System is unable to identify the payment processed.]]<br />{/if}
			{if $error == 'INVOICE_IS_NOT_PENDING'}[[The invoice that you are requesting to process has already been processed before.]]<br />{/if}
			{if $error == 'INVOICE_STATUS_NOT_VERIFIED'}[[Invoice is not verified]]<br />{/if}
			{if $error == 'INVOICE_IS_NOT_UNPAID'}[[Invoice already paid]]<br />{/if}
	    </p>
	{/foreach}
{elseif $message}
	<p class="message">[[Your payment was successfully completed. Please wait for product/service activation.]]</p>
{/if}
