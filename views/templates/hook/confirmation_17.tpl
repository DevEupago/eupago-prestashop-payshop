{*
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    euPago, Instituição de Pagamento Lda <suporte@eupago.pt>
*  @copyright 2016 euPago, Instituição de Pagamento Lda
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{if (isset($status) == true) && ($status == 'ok')}
<br /><br />
<p class="alert alert-success">{l s='Your order is complete.' mod='eupago_payshop'}</p>
<div class="dados_pagamento">
		<table style="width:100%; padding:5px; font-size: 11px; color: #374953; margin:0 auto;">
			<tbody>
			<tr>
				<td style="font-size: 12px; border-top: 0px; border-left: 0px; border-right: 0px; border-bottom: 1px solid #45829F; padding:3px; background-color: #cc0000; color: White; height:25px; line-height:25px" colspan="3"><div align="center">{l s='Payment by Payshop' mod='eupago_payshop'}</div></td>
			</tr>
			<tr style="background-color:#f1f1f1;">
				<td style="padding-top:8px;" rowspan="2"><div align="center"><img src="{$module_dir|escape:'htmlall':'UTF-8'}/views/img/eupago_payshop.png" alt="euPago" /></div></td>
				<td style=" padding:2px; font-weight:bold; text-align:left">{l s='Reference' mod='eupago_payshop'}</td>
				<td style=" padding:2px; text-align:left">{$referencia|escape:'html':'UTF-8'}</td>
			</tr>
			<tr style="background-color:#f1f1f1;">
				<td style="padding:2px; padding-bottom:10px; font-weight:bold; text-align:left">{l s='Amount' mod='eupago_payshop'}</td>
				<td style="padding:2px; padding-bottom:10px; text-align:left">{$total|escape:'html':'UTF-8'}</td>
			</tr>
			<tr>
				<td style="font-size: xx-small; padding:0; border: 0px; text-align:center;" colspan="3">{l s='The ticket privided by payshop agent machine is your payment prove. Please keep it.' mod='eupago_payshop'}</td>
			</tr>
			</tbody>
		</table>
	</div>
{else}
<p class="alert alert-danger">{l s='There was an error when generating the Payshop reference.' sprintf=$shop_name mod='eupago_payshop'}</p>
<p>
	<br />- {l s='Reference' mod='eupago_payshop'} <span class="reference"> <strong>{$reference|escape:'html':'UTF-8'}</strong></span>
	<br /><br />{l s='Please, try to order again, or contact us to get the Payshop reference' mod='eupago_payshop'}
	<br /><br />{l s='If you have questions, comments or concerns, please contact our' mod='eupago_payshop'} <a href="{$link->getPageLink('contact', true)|escape:'html':'UTF-8'}">{l s='expert customer support team.' mod='eupago_payshop'}</a>
</p>
{/if}
<hr />