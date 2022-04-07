<?php
/**
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
 */

use PrestaShop\PrestaShop\Core\Payment\PaymentOption;

if (!defined('_PS_VERSION_')) {
    exit;
}

class Eupago_payshop extends PaymentModule
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'eupago_payshop';
        $this->tab = 'payments_gateways';
        $this->version = '1.7.1';
        $this->author = 'euPago';
        $this->need_instance = 1;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('euPago - Payshop');
        $this->description = $this->l('This module is one portuguese payment method that allows your customers pay their orders by payshop.');

        $this->limited_currencies = array('EUR');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        if (extension_loaded('curl') == false) {
            $this->_errors[] = $this->l('You have to enable the cURL extension on your server to install this module');
            return false;
        }

        $this->createOrderState();

        include dirname(__FILE__) . '/sql/install.php';

        return parent::install() &&
        $this->registerHook('header') &&
        $this->registerHook('backOfficeHeader') &&
        $this->registerHook('paymentOptions') &&
        $this->registerHook('paymentReturn') &&
        $this->registerHook('displayOrderDetail') &&
        $this->registerHook('displayAdminOrder') &&
        $this->registerHook('displayPayment') &&
        $this->registerHook('displayPaymentReturn');
    }

    public function uninstall()
    {
        Configuration::deleteByName('EUPAGO_PAYSHOP_LIVE_MODE');

        include dirname(__FILE__) . '/sql/uninstall.php';

        return parent::uninstall();
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        /**
         * If values have been submitted in the form, process.
         */
        if (((bool) Tools::isSubmit('submitEupago_payshopModule')) == true) {
            $this->postProcess();
        }

        $this->context->smarty->assign('module_dir', $this->_path);

        $output = $this->context->smarty->fetch($this->local_path . 'views/templates/admin/configure.tpl');

        return $this->renderForm() . $output;
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitEupago_payshopModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
        . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigForm()));
    }

    /**
     * Create the structure of your form.
     */
    protected function getConfigForm()
    {

        return array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'col' => 7,
                        'type' => 'text',
                        'prefix' => '<i class="icon icon-key"></i>',
                        'desc' => $this->l('This key is provided by euPago if you don´t have it please contact us - www.eupago.pt'),
                        'name' => 'EUPAGO_PAYSHOP_CHAVE_API',
                        'label' => $this->l('Api key'),
                    ),

                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }

    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {
        return array(
            'EUPAGO_PAYSHOP_CHAVE_API' => Configuration::get('EUPAGO_PAYSHOP_CHAVE_API', null),
        );
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();
        // HACK PARA VALIDAR NUMERO DE DIAS
        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be loaded in the BO.
     */
    public function hookBackOfficeHeader()
    {
        if (Tools::getValue('module_name') == $this->name) {
            $this->context->controller->addJS($this->_path . 'views/js/back.js');
            $this->context->controller->addCSS($this->_path . 'views/css/back.css');
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path . '/views/js/front.js');
        $this->context->controller->addCSS($this->_path . '/views/css/front.css');
    }

    /**
     * This method is used to render the payment button in version 1.7,
     * Take care if the button should be displayed or not.
     */
    public function hookPaymentOptions($params)
    {
        $payment_option = array();

        if (!$this->active) {
            return;
        }

        $currency_id = $params['cart']->id_currency;
        $currency = new Currency((int) $currency_id);

        if (in_array($currency->iso_code, $this->limited_currencies) == false) {
            return false;
        }

        $this->smarty->assign('module_dir', $this->_path);
        $this->smarty->assign('cart', $params['cart']);

        $newOption = new PaymentOption();

        $newOption->setModuleName($this->name)
            ->setCallToActionText("Payshop")
            ->setAction($this->context->link->getModuleLink($this->name, 'confirmation', array('cart_id' => Context::getContext()->cart->id, 'secure_key' => Context::getContext()->customer->secure_key), true))
            ->setLogo(Media::getMediaPath(_PS_MODULE_DIR_ . $this->name . '/views/img/eupago_payshop.png'));

        $payment_option[] = $newOption;

        return $payment_option;

    }

    /**
     * This hook is used to display the order confirmation page.
     */
    public function hookPaymentReturn($params)
    {

        if ($this->active == false) {
            return;
        }

        $order = (_PS_VERSION_ >= '1.7' ? $params['order'] : $params['objOrder']);

        $exist = $this->getOrderIdObjectFromEupagoTable($order->id);

        if ($exist) {
            $result = (object) $exist[0];
            $result->estado = 0;
        } else {
            $result = $this->GenerateReference($order);
        }

        if ($result->estado != 0) {
            $history = new OrderHistory();
            $history->id_order = (int) $order->id;
            $erro = "Erro: " . $result->resposta;
            $history->changeIdOrderState((int) Configuration::get('PS_OS_ERROR'), (int) ($order->id));
            $this->smarty->assign('status', 'Nok');
            $this->smarty->assign('erro', $erro);
        } else {
            $this->smarty->assign('status', 'ok');
            $this->smarty->assign('referencia', $result->referencia);
            //

            $this->sendEmailPaymentDetails($order, $result);
        }

        $this->smarty->assign(array(
            'id_order' => $order->id,
            'reference' => $order->reference,
            'params' => $params,
            'total' => Tools::displayPrice($order->total_paid, null, false),
            'module_dir' => $this->_path,
        ));

        return (_PS_VERSION_ >= '1.7' ? $this->fetch('module:' . $this->name . '/views/templates/hook/confirmation_17.tpl') : $this->display(__FILE__, 'views/templates/hook/confirmation.tpl'));
    }

    public function hookDisplayAdminOrder($params)
    {

        $order_id = $params['id_order'];

        $order = new Order($order_id);

        if ($order->payment != $this->displayName) {
            return;
        }

        $dados = $this->getOrderIdObjectFromEupagoTable($order_id);

        $this->context->smarty->assign('module_dir', $this->_path);

        if ($dados) {
            $this->smarty->assign(array(
                'referencia' => $dados[0]['referencia'],
                'total' => $dados[0]['valor'],
            ));
        } else {
            $result = $this->GenerateReference($order);
            $this->sendEmailPaymentDetails($order, $result);
        }

        return $this->display(__FILE__, 'views/templates/admin/adminPaymentDetails.tpl');
    }

    public function hookDisplayOrderDetail($params)
    {

        $order = $params['order'];

        if ($order->payment != $this->displayName) {
            return;
        }

        $dados = $this->getOrderIdObjectFromEupagoTable($order->id);

        if ($dados) {
            $this->smarty->assign(array(
                'modules_dir' => $this->_path,
                'referencia' => $dados[0]['referencia'],
                'total' => $dados[0]['valor'],
            ));
        } else {
            return;
        }

        return $this->display(__FILE__, 'views/templates/front/paymentDetails.tpl');
    }

    /**
     * Faz a chamada soap e gera a referencia
     */
    public function GenerateReference($order)
    {

        //     VAI BUSCAR AS VARIAVEIS CONFIGURADAS NO BACKOFFICE
        $chave_api = Configuration::get('EUPAGO_PAYSHOP_CHAVE_API');

        // PREPARA O URL DA CHAMADA
        $demo = explode("-", $chave_api);
        if ($demo['0'] == 'demo') {
            $url = 'https://sandbox.eupago.pt/replica.eupagov20.wsdl';
        } else {
            $url = 'https://clientes.eupago.pt/eupagov20.wsdl';
        }

        $arraydados = array("chave" => $chave_api, "valor" => $order->total_paid, "id" => $order->id);

        // Verifica se o SOAP está ativo
        if (class_exists("SOAPClient")) {

            $client = new SoapClient($url, array('cache_wsdl' => WSDL_CACHE_NONE));
            $result = $client->gerarReferenciaPS($arraydados);

            //VALIDAÇÕES SOAP
            if (!$client) {
                $result->estado = "Falha no serviço SOAP";
            }

        }

        if ($result->estado == 0) {
            $this->saveResults($result, $order->id);
        }
        return $result;
    }

    /**
     * Save result from euPago server in DB
     */
    public function saveResults($result, $order_id)
    {

        Db::getInstance()->insert('eupago_payshop', array(
            'id_eupago_payshop' => '',
            'order_id' => $order_id,
            'valor' => $result->valor,
            'referencia' => $result->referencia,
            'estadoRef' => 'pendente',
        ));

    }

    /*
     * UPDATE EUPAGO estado
     */
    public function updateStatus_DB($orderId)
    {
        Db::getInstance()->update('eupago_payshop', array(
            'estadoRef' => 'pago'),
            'order_id = ' . $orderId);
    }

    public function updateStatusDb_expirada($orderId)
    {
        Db::getInstance()->update('eupago_payshop', array(
            'estadoRef' => 'expirada'),
            'order_id = ' . $orderId);
    }

    /*
     * GET order validate and update total_paid_real in Orders DB by order and paid value
     */
    public function updateValidateOrder($order_id, $valor)
    {
        $query = "UPDATE `" . _DB_PREFIX_ . "orders` SET total_paid_real=" . $valor . ", valid=1 WHERE id_order = " . $order_id;
        Db::getInstance()->Execute($query);
    }

    /*
     * Check if order id already exist in eupago_payshop DB
     */
    public function getOrderIdObjectFromEupagoTable($order_id)
    {
        $sql = 'SELECT * FROM ' . _DB_PREFIX_ . 'eupago_payshop where order_id = ' . $order_id;
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
    }

    /*
     * GET order in eupago DB by reference
     */
    public function getOrderByReference($referencia, $valor = null)
    {
        $sql = 'SELECT * FROM ' . _DB_PREFIX_ . 'eupago_payshop where referencia = ' . $referencia . ' and valor = ' . $valor;
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
    }

    /**
     * Create a new order state
     */
    public function createOrderState()
    {
        if (!Configuration::get('EUPAGO_A_AGUARDAR_PAGAMENTO_PAYSHOP')) {
            $order_state = new OrderState();
            $order_state->name = array();

            foreach (Language::getLanguages() as $language) {
                if (Tools::strtolower($language['iso_code']) == 'pt') {
                    $order_state->name[$language['id_lang']] = 'euPago - A aguardar pagamento por payshop';
                } else {
                    $order_state->name[$language['id_lang']] = 'euPago - Waiting payshop payment confirmation';
                }

            }

            $order_state->send_email = false;
            $order_state->color = '#ec2e15';
            $order_state->hidden = false;
            $order_state->delivery = false;
            $order_state->logable = false;
            $order_state->invoice = false;

            if ($order_state->add()) {
                $source = dirname(__FILE__) . '/views/img/payshop.gif';
                $destination = dirname(__FILE__) . '/../../img/os/' . (int) $order_state->id . '.gif';
                copy($source, $destination);
            }

            Configuration::updateValue('EUPAGO_A_AGUARDAR_PAGAMENTO_PAYSHOP', (int) $order_state->id);

        }

        if (!Configuration::get('EUPAGO_CONFIRMADO_PAGAMENTO_PAYSHOP')) {
            $order_state = new OrderState();
            $order_state->name = array();

            foreach (Language::getLanguages() as $language) {
                if (Tools::strtolower($language['iso_code']) == 'pt') {
                    $order_state->name[$language['id_lang']] = 'euPago - Confirmado pagamento por payshop';
                } else {
                    $order_state->name[$language['id_lang']] = 'euPago - Accepted payshop payment';
                }

            }

            $order_state->send_email = true;
            $order_state->template = "payment";
            $order_state->color = '#32CD32';
            $order_state->hidden = false;
            $order_state->delivery = false;
            $order_state->logable = false;
            $order_state->invoice = false;

            if ($order_state->add()) {
                $source = dirname(__FILE__) . '/views/img/payshop.gif';
                $destination = dirname(__FILE__) . '/../../img/os/' . (int) $order_state->id . '.gif';
                copy($source, $destination);
            }

            Configuration::updateValue('EUPAGO_CONFIRMADO_PAGAMENTO_PAYSHOP', (int) $order_state->id);

        }

    }

    public function sendEmailPaymentDetails($order, $result)
    {

        if (Validate::isEmail($this->context->customer->email)) {
            $email_tpl_vars = $this->getEmailVars($order, $result);
            $lang = new Language($order->id_lang);
            $subject = ($lang->iso_code == "pt") ? 'Aguardar Pagamento' : 'Waiting for payment';
            Mail::Send((int) $order->id_lang, 'payment_data', Mail::l($subject, (int) $order->id_lang), $email_tpl_vars, $this->context->customer->email, $this->context->customer->firstname . ' ' . $this->context->customer->lastname, null, null, null, null, _PS_MODULE_DIR_ . $this->name . '/emails/', false, (int) $order->id_shop);
        }
    }

    public function getEmailVars($order, $referencia)
    {
        $data = array(
            '{firstname}' => $this->context->customer->firstname,
            '{lastname}' => $this->context->customer->lastname,
            '{email}' => $this->context->customer->email,
            '{order_name}' => $order->getUniqReference(),
            '{referencia}' => $referencia->referencia,
            '{valor}' => Tools::displayPrice($referencia->valor, $this->context->currency, false),
            '{this_path}' => _PS_BASE_URL_ . __PS_BASE_URI__ . '/modules/' . $this->name,
        );
        return $data;
    }

    /**
     * Function to receive payment confirmation
     * The order state will be update to payed
     */
    public function callback($referencia, $valor, $chave, $identificador)
    {
        //global $link;
        $chave_api = Configuration::get('EUPAGO_PAYSHOP_CHAVE_API');
        $context = Context::getContext();
        $context->link = new Link();
        if ($chave == $chave_api) {

            $valor = str_replace(',', '.', $valor);
            $order_byReference = $this->getOrderByReference($referencia, $valor);
            if ($order_byReference[0]['order_id'] != $identificador) {
                return "O identificador e a referencia não correspondem para esta encomenda";
            }
            if ($order_byReference[0]['estadoRef'] == 'pago') {
                return "Referencia Já paga";
            }
            $orderId = $identificador;
            if (!empty($orderId)) {
                $new_history = new OrderHistory();
                $new_history->id_order = $orderId;
                $new_history->changeIdOrderState((int) (Configuration::get('EUPAGO_CONFIRMADO_PAGAMENTO_PAYSHOP')), (int) $orderId);

                $lang = $this->context->language->iso_code;
                $subject = ($lang == "pt") ? 'Pagamento bem sucedido' : 'Successful payment';
                //procurar o email do cliente para enviar lhe a notificação de pagamento bem sucedido
                $sql = "SELECT " . _DB_PREFIX_ . "customer.email, " . _DB_PREFIX_ . "orders.id_lang," .
                _DB_PREFIX_ . "orders.reference FROM " .
                _DB_PREFIX_ . "orders," . _DB_PREFIX_ . "customer WHERE " .
                _DB_PREFIX_ . "orders.id_order=" . (int) $orderId . " and " .
                _DB_PREFIX_ . "orders.id_customer = " . pSQL(_DB_PREFIX_ . "customer.id_customer");
                $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

                Mail::Send(
                    (int) $result[0]['id_lang'], // defaut language id
                    'payment', // email template file to be use
                    $subject, // email subject
                    array(
                        "message" => $subject,
                        "{firstname}" => $this->context->customer->firstname,
                        "{lastname}" => $this->context->customer->lastname,
                        "{order_name}" => $result[0]['reference'],
                    ),
                    $result[0]['email'], // receiver email address
                    null, //receiver name
                    null, //from email address
                    null//from name
                );

                $this->updateStatus_DB($orderId);
                $this->updateValidateOrder($orderId, $valor);
                echo "Atualizada para paga";
                $new_history->addWithemail(true, null, $context);
                return "Atualizada para paga"; //atualizada para paga
            } else {
                return "Referencia não encontrada"; //Já paga
            }
        } else {
            return "Chave de API inválida"; //Chave inválida
        }
    }
    public function callBackExpirada($referencia, $valor, $chave, $tipo_callback)
    {

        if ($tipo_callback != 'expirada') {
            return "tipo de calback invalido";
        }

        $chaveReg = Configuration::get('EUPAGO_PAYSHOP_CHAVE_API');

        $context = Context::getContext();
        $context->link = new Link();
        if ($chave == $chaveReg) {
            $valor = str_replace(',', '.', $valor);
            $orderId = $this->getEupago_payshopOrderDb($referencia, $valor);
            if (!empty($orderId)) {
                $new_history = new OrderHistory();
                $new_history->id_order = (int) $orderId;

                $new_history->changeIdOrderState((int) 6, $orderId);
                $new_history->addWithemail(true, null, $context);

                $this->updateEupago_payshopOrderDb_expirada($orderId);

                return 1; //atualizada para paga
            } else {
                return 0; //J� paga
            }
        } else {
            return -1; //Chave inv�lida
        }
    }

}
