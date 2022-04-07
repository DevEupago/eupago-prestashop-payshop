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

<div>
	<h3>{l s='Redirect your customer' mod='eupago_payshop'}:</h3>
	<ul class="alert alert-info">
			<li>{l s='This action should be used to redirect your customer to the website of your payment processor' mod='eupago_payshop'}.</li>
	</ul>
	
	<div class="alert alert-warning">
		{l s='You can redirect your customer with an error message' mod='eupago_payshop'}:
		<a href="{$link->getModuleLink('eupago_payshop', 'redirect', ['action' => 'error'], true)|escape:'htmlall':'UTF-8'}" title="{l s='Look at the error' mod='eupago_payshop'}">
			<strong>{l s='Look at the error message' mod='eupago_payshop'}</strong>
		</a>
	</div>
	
	<div class="alert alert-success">
		{l s='You can also redirect your customer to the confirmation page' mod='eupago_payshop'}:
		<a href="{$link->getModuleLink('eupago_payshop', 'confirmation', ['cart_id' => $cart_id, 'secure_key' => $secure_key], true)|escape:'htmlall':'UTF-8'}" title="{l s='Confirm' mod='eupago_payshop'}">
			<strong>{l s='Go to the confirmation page' mod='eupago_payshop'}</strong>
		</a>
	</div>
</div>
