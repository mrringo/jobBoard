<input type="text" class="inputInteger {if $complexField}complexField{/if}" value="{$value|escape:'html'}" name="{if $complexField}{$complexField}[{$id}][{$complexStep}]{else}{$id}{/if}" id={if $complexField}{$complexField}[{$id}][{$complexStep}]{else}{$id}{/if} {if $id == 'listings_range_to' || $id == 'listings_range_from' || $id == 'period' || $id == 'featured_period' || $id == 'priority_period'|| $id == 'qty'}style="width: 50px;"{/if}/>