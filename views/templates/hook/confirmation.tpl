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
<p class="alert alert-success">{l s='Your order on %s is complete.' sprintf=$shop_name mod='eupago_payshop'}</p>
<p>
	<br />- {l s='Amount' mod='eupago_payshop'} : <span class="price"><strong>{$total|escape:'htmlall':'UTF-8'}</strong></span>
	<br />- {l s='Reference' mod='eupago_payshop'} : <span class="reference"><strong>{$reference|escape:'html':'UTF-8'}</strong></span>
	<br /><br />{l s='An email has been sent with this information.' mod='eupago_payshop'}
	<br /><br />{l s='If you have questions, comments or concerns, please contact our' mod='eupago_payshop'} <a href="{$link->getPageLink('contact', true)|escape:'html':'UTF-8'}">{l s='expert customer support team.' mod='eupago_payshop'}</a>
</p>
	<div class="dados_pagamento">
		{include '../front/paymentDataTable.tpl'}
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