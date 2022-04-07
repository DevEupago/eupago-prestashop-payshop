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
<div id="eupago_payshopAdminInfo" class="panel">
	<div class="row">
		<div class="col-lg-12">
			<div class="panel-heading">{l s='Payshop payment info' mod='eupago_payshop'}</div>
			<div class="tab-content">
				<div class="hidden-sm-down col-xs-3 col-sm-2 col-md-2 col-lg-1"><img src="{$module_dir|escape:'htmlall':'UTF-8'}views/img/PayShop.png" alt="euPago" /></div>
				<div style="padding:10px;"class="col-sm-6">
					<table style="font-family: Verdana,sans-serif; font-size: 11px; color: black; width: 278px;">
						<tbody>
							<tr>
							  <td style="font-weight: bold; text-align: left;">{l s='Reference' mod='eupago_payshop'}:</td>
							  <td style="text-align: left;">{$referencia|escape:'htmlall':'UTF-8'}</td>
							</tr>
							<tr>
							  <td style="font-weight: bold; text-align: left;">{l s='Amount' mod='eupago_payshop'}:</td>
							  <td style="text-align: left;">{convertPrice price=$total}</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>