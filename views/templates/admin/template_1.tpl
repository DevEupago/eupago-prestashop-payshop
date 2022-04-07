{*
*  20013-2016 euPago, instituição de pagamento LDA
*
*  @author    euPago <suporte@eupago.pt>
*  @copyright 20013-2016 euPago, instituição de pagamento LDA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*}

<div class="panel">
	<div class="row eupago_payshop-header">
		<div class="col-xs-6 col-md-4 text-center">
			<img src="{$module_dir|escape:'html':'UTF-8'}views/img/logolaranja.png" class="col-xs-6 col-md-4 text-center" id="payment-logo" />
		</div>
		<div class="col-xs-6 col-md-4 text-center header2">
			<h4>{l s='euPago - payment solutions' mod='eupago_payshop'}</h4>
			<h4>{l s='payshop payment´s' mod='eupago_payshop'}</h4>
		</div>
		<div class="col-xs-12 col-md-4 text-center header2">
			<a href="http://www.eupago.pt/registo?lang=en&prestashop#registo_form" target="black" class="btn btn-primary" id="create-account-btn">{l s='Create an account now!' mod='eupago_payshop'}</a><br />
			{l s='Already have an account?' mod='eupago_payshop'}<a href="https://eupago.pt/clientes/users/login" target="blank" > {l s='Log in' mod='eupago_payshop'}</a>
		</div>
	</div>

	<hr />
	
	<div class="eupago_payshop-content">
		<div class="row">
			<div class="col-md-6">
				<h5>{l s='euPago Payshop payment offers the following benefits' mod='eupago_payshop'}</h5>
				<dl>
					<dt>&middot; {l s='Increase customer local payment options' mod='eupago_payshop'}</dt>
					<dd>{l s='Payshop is the most used payment method in Portugal' mod='eupago_payshop'}</dd>
					
					<dt>&middot; {l s='Help to improve cash flow' mod='eupago_payshop'}</dt>
					<dd>{l s='Receive funds quickly from the bank of your choice.' mod='eupago_payshop'}</dd>

					<dt>&middot; {l s='Real time callback' mod='eupago_payshop'}</dt>
					<dd>{l s='With our module you will receive the payment notification in real time,' mod='eupago_payshop'}<br>{l s='and the order status is update automatically.' mod='eupago_payshop'}</dd>
				</dl>
			</div>
			
			<div class="col-md-6">
				<h5>{l s='Check our backoffice' mod='eupago_payshop'}</h5>
				<iframe width="100%" height="315" src="https://www.youtube.com/embed/aZ2nrbsU20A" frameborder="0" allowfullscreen></iframe>
			</div>
		</div>

		<hr />
		
		<div class="row">
			<div class="col-md-12">
				<h4>{l s='Accept payments with payshop:' mod='eupago_payshop'}</h4>
				
				<div class="row">
					<img style="max-width:100px;" src="{$module_dir|escape:'html':'UTF-8'}views/img/eupago_payshop.png" class="col-md-6" id="payment-logo" />
					<div class="col-md-6">
						<p class="text-branded">{l s='Call +351 222 061 597 if you have any questions or need more information!' mod='eupago_payshop'}/></br>
						<a class="link" href="https://www.payshop.pt/" target="blank">{l s='What is payshop?' mod='eupago_payshop'}</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
