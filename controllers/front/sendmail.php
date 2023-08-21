<?php
/**
 * sendmail.php
 *
 * Controller for sending emails
 *
 * @author    Samar Al Khalil
 * @copyright Copyright (c)
 * @license   License (if applicable)
 * @category  Controllers
 * @package   FrontControllers
 * @subpackage SendMail
 */
use MJML\MJML;
class ProductCommentPlusSendmailModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        parent::initContent();



        $date = ((new DateTime())->modify('-' . Configuration::get('PRODUCT_COMMENT_PLUS_MAIL_DAYS') . ' days'))->format('Y-m-d');
        $orders = Order::getOrdersIdByDate($date, (new DateTime())->format('Y-m-d'));
        
        if ($orders) {
            foreach ($orders as $orderId) {
                $order = new Order($orderId);
                $products= [];
                foreach($order->getProducts() as $orderProduct){
                    $product = new Product($orderProduct['id_product'], false, $this->context->language->id);
                    $img = $product->getCover($product->id);
                    $link = new Link();
                    $img_url = $link->getImageLink($product->link_rewrite, $img['id_image'], ImageType::getFormattedName('cart'));
                    $products[] = [
                        'name' => $orderProduct['product_name'],
                        'cover' => Tools::getProtocol() . $img_url
                    ];
                    
                }
               
                $shopName = Configuration::get('PS_SHOP_NAME');
                
               
                //dump($productsName);    
                $orderHistory = $order->getHistory($this->context->language->id, true);
                $hasPaymentAccepted = false;

                foreach ($orderHistory as $history) {
                    if ($history['id_order_state'] == 2) {
                        $hasPaymentAccepted = true;
                        break;
                    }
                }
                // Load the customer information
                $customer = new Customer($order->id_customer);
                
         
                // Send the review request email
                if ($hasPaymentAccepted) {
                    // Send the review request email
                    $this->sendReviewEmail($customer->email, $orderId, $customer, $products, $shopName);
                }
            }
        }

        return $this->setTemplate('module:productcommentplus/views/templates/front/sendmail.tpl');
    }

    public function sendReviewEmail($customerEmail, $orderId, $customer, $products, $shopName)
    {
        $logoPath = Media::getMediaPath(_PS_IMG_.Configuration::get('PS_LOGO'));
        $mjmlContent = file_get_contents(_PS_MODULE_DIR_ . 'productcommentplus/mails/fr/index.mjml');
        
        // Use the Mail::Send() method to send the email
        $templateVars =[
            '{customer_email}' => $customerEmail,
            '{order_id}' => $orderId,
            '{customer_firstname}' => $customer->firstname,
            '{customer_lastname}' => $customer->lastname,
            '{shop_name}' => $shopName,
            '{products}' => $products,
            '{logo}' => $logoPath
        ];

        Mail::Send(
            (int)(Configuration::get('PS_LANG_DEFAULT')),
            'review_request',
            $this->l('Please give us a review'),
            $templateVars,
            $customerEmail,
            "{$customer->firstname} {$customer->lastname}",
            [$products],
            $shopName,
            $logoPath,
            Configuration::get('PS_SHOP_EMAIL'),
            null,
            null,
            null,
            _PS_MODULE_DIR_ . 'productcommentplus/mails'
        );
    }
}
