<?php
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 */
 require_once(_PS_MODULE_DIR_.'productcommentplus/classes/ProductCommentPlusPendingClass.php');
 require_once(_PS_MODULE_DIR_.'productcommentplus/classes/ProductCommentPlusValidatedClass.php');
 require_once(_PS_MODULE_DIR_.'productcommentplus/classes/ProductCommentPlusRefusedClass.php');
 require_once(_PS_MODULE_DIR_.'productcommentplus/controllers/admin/AdminProductCommentPlusPendingController.php');

 if(!defined('_PS_VERSION_')){
    exit;
}

class ProductCommentPlus extends Module
{
    public function __construct()
    {
        $this->name ='productcommentplus';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'samar Alkhalil';
        $this->ps_versions_compliancy = [
            'min' => '1.6',
            'max' => _PS_VERSION_
        ];

        parent::__construct();

        $this->bootstrap = true;
        $this->displayName = $this->l("Product Comment Plus");
        $this->description = $this->l('Récupération et affichage des avis clients');
        $this->confirmUninstall = $this->l('Êtes-vous sur de vouloir supprimer ce module');
    }

    public function install()
    {
    if (!parent::install() ||
        !$this->registerHook('displayShoppingCartFooter') ||
        !$this->registerHook('displayLeftColumn') ||
        !$this->registerHook('displayReassurance') ||
        !$this->registerHook('displayAdminProductsExtra') ||
        !$this->registerHook('displayHome') ||
        !$this->registerHook('displayOrderConfirmation2') ||
        !$this->registerHook('displayContentWrapperTop') ||
        !$this->installTab('AdminProductCommentPlusPending', 'Pending table', 'IMPROVE') ||
        !$this->installTab('AdminProductCommentPlusValidated', 'Validated table', 'IMPROVE') ||
        !$this->installTab('AdminProductCommentPlusRefused', 'Refused table', 'IMPROVE') ||
        !$this->createReviewTable() ||
        !$this->createPendingTable() ||
        !$this->createValidatedTable() ||
        !Configuration::updateValue('PRODUCT_COMMENT_PLUS_VALIDATION', true) ||
        !Configuration::updateValue('PRODUCT_COMMENT_PLUS_ALLOW_VISITORS', true) ||
        !Configuration::updateValue('PRODUCT_COMMENT_PLUS_ANONYMIZE_NAME', true) ||
        !Configuration::updateValue('PRODUCT_COMMENT_PLUS_COMMENTS_PER_PAGE', 10) ||
        !Configuration::updateValue('PRODUCT_COMMENT_PLUS_DISPLAY_CAROUSEL', true) ||
        !Configuration::updateValue('PRODUCT_COMMENT_PLUS_MAIL_DAYS', 4)
    ) {
        return false;
    }

    Db::getInstance()->execute('ALTER TABLE `'._DB_PREFIX_.'product_comment_plus_pending` ADD COLUMN `product_name` VARCHAR(255)');
    Db::getInstance()->execute('ALTER TABLE `'._DB_PREFIX_.'product_comment_plus_validated` ADD COLUMN `product_name` VARCHAR(255)');
    Db::getInstance()->execute('ALTER TABLE `'._DB_PREFIX_.'product_comment_plus_refused` ADD COLUMN `product_name` VARCHAR(255)');

    // Update the column value
    $this->updateProductNameColumn('product_comment_plus_pending');
    $this->updateProductNameColumn('product_comment_plus_validated');
    $this->updateProductNameColumn('product_comment_plus_refused');

    return true;
}

    public function uninstall()
    {
        if (!parent::uninstall() ||
            !$this->unregisterHook('displayShoppingCartFooter') ||
            !$this->unregisterHook('displayLeftColumn') ||
            !$this->unregisterHook('displayReassurance') ||
            !$this->unregisterHook('displayAdminProductsExtra') ||
            !$this->unregisterHook('displayHome') ||
            !$this->unregisterHook('displayOrderConfirmation2') ||
            !$this->unregisterHook('displayContentWrapperTop') ||
            !$this->uninstallTab('AdminProductCommentPlusPending') ||
            !$this->uninstallTab('AdminProductCommentPlusValidated') ||
            !$this->uninstallTab('AdminProductCommentPlusRefused') ||
            !$this->removeReviewTable() ||
            !$this->removePendingTable() ||
            !$this->removeValidatedTable() ||
            !Configuration::deleteByName('PRODUCT_COMMENT_PLUS_VALIDATION') ||
            !Configuration::deleteByName('PRODUCT_COMMENT_PLUS_ALLOW_VISITORS') ||
            !Configuration::deleteByName('PRODUCT_COMMENT_PLUS_ANONYMIZE_NAME') ||
            !Configuration::deleteByName('PRODUCT_COMMENT_PLUS_COMMENTS_PER_PAGE') ||
            !Configuration::deleteByName('PRODUCT_COMMENT_PLUS_MAIL_DAYS') ||
            !Configuration::deleteByName('PRODUCT_COMMENT_PLUS_DISPLAY_CAROUSEL')
        ) {
            return false;
        }

        return true;
    }

    private function updateProductNameColumn($table)
    {
        $comments = Db::getInstance()->executeS('SELECT id_product FROM `'._DB_PREFIX_.$table.'`');
        foreach ($comments as $comment) {
            $product = new Product($comment['id_product']);
            $product_name = $product->name;
            Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.$table.'` SET `product_name` = "'.pSQL($product_name).'" WHERE `id_product` = "'.(int)$comment['id_product'].'"');
        }
    }

    public function enable($force_all = false)
    {
        return parent::enable($force_all)
            && $this->installTab('AdminProductCommentPlusPending', 'Pending table', 'IMPROVE')
            && $this->installTab('AdminProductCommentPlusValidated', 'Validated table', 'IMPROVE')
            && $this->installTab('AdminProductCommentPlusRefused', 'Refused table', 'IMPROVE')
        ;
    }

    public function disable($force_all = false){
        return parent::disable($force_all)
            && $this->uninstallTab('AdminProductCommentPlusPending')
            && $this->uninstallTab('AdminProductCommentPlusValidated')
            && $this->uninstallTab('AdminProductCommentPlusRefused')
        ;
    }

    public function getContent(){
        $output = $this->renderForm();
        $output .= $this->postProcess();
        $output .= $this->renderList();
        return $output;
    }

    public function renderForm(){
        $fieldsForm[0]['form'] =[
            'legend' => [
                'title' => $this->l('Settings')
            ],
            'input' => [
                [
                    'type' => 'switch',
                    'label' => $this->l('Validation des commentaires par un employer'),
                    'name' => 'PRODUCT_COMMENT_PLUS_VALIDATION',
                    'is_bool'=>true,
                    'size' => 20,
                    'values' => array(
                        [
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Activé')
                        ],
                        [
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Désactivé')
                        ]
                    ),
                    'required' => true,
                ],
                [
                    'type' => 'switch',
                    'label' => $this->l('Autoriser les avis de visiteur sans compte'),
                    'name' => 'PRODUCT_COMMENT_PLUS_ALLOW_VISITORS',
                    'is_bool'=>true,
                    'size' => 20,
                    'values' => array(
                        [
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Activé')
                        ],
                        [
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Désactivé')
                        ]
                    ),
                    'required' => true,
                ],
                [
                    'type' => 'switch',
                    'label' => $this->l('Anonymiser le nom de famille de l\'utilisateur'),
                    'name' => 'PRODUCT_COMMENT_PLUS_ANONYMIZE_NAME',
                    'is_bool'=>true,
                    'size' => 20,
                    'values' => array(
                        [
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Activé')
                        ],
                        [
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Désactivé')
                        ]
                    ),
                    'required' => true,
                ],
                [
                    'type' => 'switch',
                    'label' => $this->l('Display Carousel row or column'),
                    'name' => 'PRODUCT_COMMENT_PLUS_DISPLAY_CAROUSEL',
                    'is_bool' => true,
                    'values' => [
                        [
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Column')
                        ],
                        [
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Row')
                        ]
                    ],
                    'desc' => $this->l('Display the carousel in row or column'),
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Nombre de commentaires par page'),
                    'name' => 'PRODUCT_COMMENT_PLUS_COMMENTS_PER_PAGE',
                    'size' => 20,
                    'required' => true,
                    'validate' => 'isInt',
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Nombre de jour après commande pour envoyer le mail'),
                    'name' => 'PRODUCT_COMMENT_PLUS_MAIL_DAYS',
                    'size' => 20,
                    'required' => true,
                    'validate' => 'isInt',
                ]
            ],
            'submit' => [
                'title' => $this->l('save'),
                'class' => 'btn btn-primary',
                'name' => 'saving'
            ]
        ];

        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->fields_value['PRODUCT_COMMENT_PLUS_VALIDATION'] = Configuration::get('PRODUCT_COMMENT_PLUS_VALIDATION');
        $helper->fields_value['PRODUCT_COMMENT_PLUS_ALLOW_VISITORS'] = Configuration::get('PRODUCT_COMMENT_PLUS_ALLOW_VISITORS');
        $helper->fields_value['PRODUCT_COMMENT_PLUS_ANONYMIZE_NAME'] = Configuration::get('PRODUCT_COMMENT_PLUS_ANONYMIZE_NAME');
        $helper->fields_value['PRODUCT_COMMENT_PLUS_COMMENTS_PER_PAGE'] = Configuration::get('PRODUCT_COMMENT_PLUS_COMMENTS_PER_PAGE');
        $helper->fields_value['PRODUCT_COMMENT_PLUS_DISPLAY_CAROUSEL'] = Configuration::get('PRODUCT_COMMENT_PLUS_DISPLAY_CAROUSEL');
        $helper->fields_value['PRODUCT_COMMENT_PLUS_MAIL_DAYS'] = Configuration::get('PRODUCT_COMMENT_PLUS_MAIL_DAYS');
        return $helper->generateForm($fieldsForm);
    } 

    public function postProcess(){
        if(Tools::isSubmit('saving')){
            if(empty(Tools::getValue('PRODUCT_COMMENT_PLUS_COMMENTS_PER_PAGE')) || empty(Tools::getValue('PRODUCT_COMMENT_PLUS_MAIL_DAYS'))){
                return $this->displayError('Une valeur est vide');
            }else{
                Configuration::updateValue('PRODUCT_COMMENT_PLUS_VALIDATION', Tools::getValue('PRODUCT_COMMENT_PLUS_VALIDATION'));
                Configuration::updateValue('PRODUCT_COMMENT_PLUS_ALLOW_VISITORS', Tools::getValue('PRODUCT_COMMENT_PLUS_ALLOW_VISITORS'));
                Configuration::updateValue('PRODUCT_COMMENT_PLUS_ANONYMIZE_NAME', Tools::getValue('PRODUCT_COMMENT_PLUS_ANONYMIZE_NAME'));
                Configuration::updateValue('PRODUCT_COMMENT_PLUS_COMMENTS_PER_PAGE', Tools::getValue('PRODUCT_COMMENT_PLUS_COMMENTS_PER_PAGE'));
                Configuration::updateValue('PRODUCT_COMMENT_PLUS_DISPLAY_CAROUSEL', Tools::getValue('PRODUCT_COMMENT_PLUS_DISPLAY_CAROUSEL'));
                Configuration::updateValue('PRODUCT_COMMENT_PLUS_MAIL_DAYS', Tools::getValue('PRODUCT_COMMENT_PLUS_MAIL_DAYS'));
                return $this->displayConfirmation('Sauvegarde réussi');
            }
        }
    } 


    public function createReviewTable(){
        return Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'product_comment_plus_refused` (
            `id_comment` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            `title` VARCHAR(255) NOT NULL,
            `comment` TEXT NOT NULL,
            `rating` TINYINT(1) UNSIGNED NOT NULL,
            `author` VARCHAR(128) NOT NULL,
            `id_product` INT(10) UNSIGNED NOT NULL,
            `date_add` DATETIME NOT NULL,
            PRIMARY KEY (`id_comment`)
        )');
    }

    public function createPendingTable(){
        return Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'product_comment_plus_pending` (
            `id_comment` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            `title` VARCHAR(255) NOT NULL,
            `comment` TEXT NOT NULL,
            `rating` TINYINT(1) UNSIGNED NOT NULL,
            `author` VARCHAR(128) NOT NULL,
            `id_product` INT(10) UNSIGNED NOT NULL,
            `date_add` DATETIME NOT NULL,
            PRIMARY KEY (`id_comment`)
        )');
    }

    public function createValidatedTable(){
        return Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'product_comment_plus_validated` (
            `id_comment` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            `title` VARCHAR(255) NOT NULL,
            `comment` TEXT NOT NULL,
            `rating` TINYINT(1) UNSIGNED NOT NULL,
            `author` VARCHAR(128) NOT NULL,
            `id_product` INT(10) UNSIGNED NOT NULL,
            `date_add` DATETIME NOT NULL,
            PRIMARY KEY (`id_comment`)
        )');
    }

    public function removeReviewTable(){
        return Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'product_comment_plus_refused`');
    }

    public function removePendingTable(){
        return Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'product_comment_plus_pending`');
    }

    public function removeValidatedTable(){
        return Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'product_comment_plus_validated`');
    }

    private function installTab($className, $tabName, $tabParentName){
        $tabId = (int) Tab::getIdFromClassName($className);
        if (!$tabId) {
            $tabId = null;
        }

        $tab = new Tab($tabId);
        $tab->active = 1;
        $tab->class_name = $className;
        // Only since 1.7.7, you can define a route name
        $tab->name = array();
        foreach (Language::getLanguages() as $lang) {
            $tab->name[$lang['id_lang']] = $this->trans($tabName, array(), 'Modules.MyModule.Admin', $lang['locale']);
        }
        $tab->id_parent = (int) Tab::getIdFromClassName($tabParentName);
        $tab->module = $this->name;

        return $tab->save();
    }

    private function uninstallTab($className){
        $tabId = (int) Tab::getIdFromClassName($className);
        if (!$tabId) {
            return true;
        }

        $tab = new Tab($tabId);

        return $tab->delete();
    }

    public function renderList(){

        $tabs = [
            'Pending' => [
                'title' => $this->l('Comments Pending Validation'),
                'table' =>'product_comment_plus_pending',
                'actions' => array('view','edit','delete'),
            ],
            'Validated' => [
                'title' => $this->l('Validated Comments'),
                'table' =>'product_comment_plus_validated',
                'actions' => array('view','edit','delete'),
            ],
            'Refused' => [                
                'title' => $this->l('Refused Comments'),
                'table' =>'product_comment_plus_refused',
                'actions' => array('view','edit','delete'),
            ],
        ];
    
        $output = '';

        foreach ($tabs as $tabKey => $tab) {
            $fields_list = array(
                'id_comment' => ['title' => $this->l('Comment ID')],
                'title' => ['title' => $this->l('Title')],
                'comment' => ['title' => $this->l('Comment')],
                'rating' => ['title' => $this->l('Rating')],
                'author' => ['title' => $this->l('Author')],
                'id_product' => ['title' => $this->l('Product ID')],
                'product_name' => ['title' => $this->l('Product Name')],
                'date_add' => ['title' => $this->l('Date Added')],
               
            );

            $helper = new HelperList();
            $helper->shopLinkType = '';
            $helper->simple_header = false;
            $helper->actions = $tab['actions'];
            $helper->identifier = 'id_comment';
            $helper->show_toolbar = true;
            $helper->toolbar_scroll = true;
            $helper->toolbar_btn = array(
                'delete' => array(
                    'desc' => $this->l('Delete selected'),
                    'href' => $this->context->link->getAdminLink('AdminProductCommentPlus'.$tabKey, true).'&amp;delete'.$this->table.'=1',
                    'confirm' => $this->l('Delete selected items?'),
                ),
                'edit' => array(
                    'desc' => $this->l('edit selected'),
                    'href' => $this->context->link->getAdminLink('AdminProductCommentPlus'.$tabKey, true).'&amp;edit'.$this->table.'=1',
                ),
                
            );
            $helper->title = $tab['title'];
            $helper->table = $tab['table'];
            $helper->currentIndex = $this->context->link->getAdminLink('AdminProductCommentPlus'.$tabKey, false);
            $helper->token = Tools::getAdminTokenLite('AdminProductCommentPlus'.$tabKey);

            $comments = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.$tab['table'].'`');
            $output .= $helper->generateList($comments, $fields_list);
        }
        return $output;
    }

    
    public function getComments()
    {
        $comments = [];
        
        // Retrieve comments from the 'product_comment_plus_validated' table
        $sql = 'SELECT * FROM ' . _DB_PREFIX_ . 'product_comment_plus_validated';
        $results = Db::getInstance()->executeS($sql);

        if ($results && count($results) > 0) {
            foreach ($results as $row) {
                $comment = array(
                    'id_comment' => $row['id_comment'],
                    'title' => $row['title'],
                    'comment' => $row['comment'],
                    'rating' => $row['rating'],
                    'author' => $row['author'],
                    'id_product' => $row['id_product'],
                    'date_add' => $row['date_add'],
                );

                $comments[] = $comment;
            }
        }

        return $comments;
    }

    public static function getCommentsByProductId($id_product, $table)
    {
        $comments = [];
        $sql = new DbQuery();
        $sql->select('*');
        $sql->from($table, 'pcv'); 
        $sql->where('pcv.id_product = '.(int)$id_product);

        $results = Db::getInstance()->executeS($sql);

        if ($results && count($results) > 0) {
            foreach ($results as $row) {
                $comment = array(
                    'id_comment' => $row['id_comment'],
                    'title' => $row['title'],
                    'comment' => $row['comment'],
                    'rating' => $row['rating'],
                    'author' => $row['author'],
                    'id_product' => $row['id_product'],
                    'date_add' => $row['date_add'],
                );

                $comments[] = $comment;
            }
        }

        return $comments;
    }
    public function displayTemplate($comments, $template)
    {   
        
        if ($comments) {
            $tplFile = _PS_MODULE_DIR_.'productcommentplus/views/templates/hooks/'.$template.'.tpl';
            $tpl = $this->context->smarty->createTemplate($tplFile);
            $maxComments = (int) Configuration::get('PRODUCT_COMMENT_PLUS_COMMENTS_PER_PAGE');
            $tpl->assign((
                [
                    'comments' => $comments,
                    'maxComments' => $maxComments,
                ]
                ));

            return $tpl->fetch(); 
        }  
    }

    public function hookDisplayReassurance($params)
    {
        if ($this->context->controller->php_self == 'product') {
            $this->formValidation();
            $productId =(int)Tools::getValue('id_product');
            $comments = $this->getCommentsByProductId($productId, 'product_comment_plus_validated');
           
            $this->context->smarty->assign(['successMessage' => $this->_confirmations, 'errorMessage' => $this->_errors]);
            
            return $this->display(__FILE__,'views/templates/front/_partials/notifications.tpl') . $this->displayTemplate($comments, "comment_by_product" ) . $this->display(__FILE__, 'views/templates/hooks/comment_form.tpl') ;
        }
        if ($this->context->controller->php_self == 'cart') {
            $comments = $this->getComments();
            $comments = $this->getCoverImage($comments);
            
          return $this->displayTemplate($comments, "comments_cart");
        }

    }


    public function formValidation()
    {
       
        if(Tools::isSubmit('valider')){

            if(!$this->context->customer->isLogged() && (Configuration::get('PRODUCT_COMMENT_PLUS_ALLOW_VISITORS')== 0)){
               return $this->_errors[] = "Veuillez vous connecter pour laisser un avis";
            }
            
            
            $title = Tools::getValue('title');
            $commentContent = Tools::getValue('comment');
            $rating = Tools::getValue('rating');
            $author = Tools::getValue('author');
            $product_id = Tools::getValue('id_product');
            


            if( Validate::isGenericName($title) &&
                Validate::isGenericName($commentContent) &&
                Validate::isUnsignedInt($rating) &&
                Validate::isName($author) &&
                Validate::isUnsignedInt($product_id)
              )
            { 
                if(Configuration::get('PRODUCT_COMMENT_PLUS_VALIDATION')==1){
                    $comment = new ProductCommentPlusPendingClass();
                }else{
                    $comment = new ProductCommentPlusValidatedClass();
                }
                    
                $comment->title = $title;
                $comment->comment = $commentContent;
                $comment->rating = $rating;
                if($this->context->customer->isLogged()){
                    if(Configuration::get('PRODUCT_COMMENT_PLUS_ANONYMIZE_NAME')==1){
                        $comment->author = $this->context->customer->firstname . substr($this->context->customer->lastname, 0, 1); 
            
                    }else{
                        $comment->author = $this->context->customer->firstname . $$this->context->customer->lastname; 
                    }
                    
                }else{
                    $comment->author = $author;
                }
                $comment->id_product = $product_id;
                $comment->date_add = date('Y-m-d H:i:s');
                
                if ($comment->add()) {
                    // Set the success message in a session variable
                   
                    $this->_confirmations[] = $this->l('Votre commentaire à été ajouté avec succès !');
                  
                    $table = Configuration::get('PRODUCT_COMMENT_PLUS_VALIDATION') ? 'product_comment_plus_pending' : 'product_comment_plus_validated';
                    $product = new Product($product_id);
                    $product_name = $product->name[1];
                    Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.$table.'` SET `product_name` = "'.pSQL($product_name).'" WHERE `id_comment` = "'.(int)$comment->id.'"');
                } else {
                    // Set the error message in a session variable
                   $this->_errors[] = Context::getContext()->getTranslator()->trans("Une erreur s'est produite lors de la soumission de votre formulaire, veuillez réessayer plus tard");
                }
               // $this->myMethod($this->context->link->getProductLink($product_id));
               // Tools::redirect($this->context->link->getProductLink($product_id));
                
            }       
        }

       

    }

    public function getProductIdsForCategory($categoryId){
        $productsIdForCategory = [];
        $sql = new DbQuery();
        $sql->select('id_product');
        $sql->from('category_product', 'cp'); 
        $sql->where('cp.id_category = '.(int)$categoryId);

        $results = Db::getInstance()->executeS($sql);

        if ($results && count($results) > 0) {
            foreach ($results as $row) {
                $category = [
                    'id_product' => $row['id_product'],
                ];

                $productsIdForCategory[] = $category;
            }
        }

        return $productsIdForCategory;


    }
 
    public function hookDisplayLeftColumn($params)
    {
        // Retrieve the comments and pass them to the template
        $comments = [];
        $categoryId = Tools::getValue('id_category');
        
        $productFromCategory = $this->getProductIdsForCategory($categoryId);
        
        foreach($productFromCategory as $product){
            $comment['id_product'] = $product['id_product'];
            $comment['product_name'] = Product::getProductName($product['id_product']);
            $comment['product_link'] = $this->context->link->getProductLink($product['id_product']);
            $comment['comments'] = $this->getCommentsByProductId($product['id_product'], 'product_comment_plus_validated');
            
            $productInstance = new Product($product['id_product'], false, $this->context->language->id);
            $img = $productInstance->getCover($productInstance->id);
            $link = new Link();
            $img_url = $link->getImageLink($productInstance->link_rewrite, $img['id_image'], ImageType::getFormattedName('cart'));
            $comment['cover_image'] = Tools::getProtocol().$img_url;
            
            $comments[] = $comment;
            
        }    
        $tplFile = _PS_MODULE_DIR_.'productcommentplus/views/templates/hooks/comments_carousel.tpl';
        $tpl = $this->context->smarty->createTemplate($tplFile);
        $tpl->assign((
            [
                'comments' => $comments
            ]
            ));

        return $tpl->fetch();
        
    }


   public function hookDisplayShoppingCartFooter($params)
    {
        
        $comments = $this->getComments();
        $comments = $this->getCoverImage($comments);
        
       return $this->displayTemplate($comments, 'comments_cart');
    }

      public function hookDisplayAdminProductsExtra($params)
    {
    
       
        $productId = (int)$params['id_product'];
        $product = new Product($productId);
    
        // Retrieve comments for the product (assuming $comments is an array of comments)
        $comments = $this->getCommentsByProductId($productId, 'product_comment_plus_validated');
        $commentsRefused = $this->getCommentsByProductId($productId, 'product_comment_plus_refused');
        $commentsPending = $this->getCommentsByProductId($productId, 'product_comment_plus_pending');
        
        if ($comments || $commentsPending || $commentsRefused) {
            $tplFile = _PS_MODULE_DIR_.'productcommentplus/views/templates/hooks/commentaires.tpl';
            $tpl = $this->context->smarty->createTemplate($tplFile);
            $maxComments = (int) Configuration::get('PRODUCT_COMMENT_PLUS_COMMENTS_PER_PAGE');
            $tpl->assign((
                [
                    'comments' => $comments,
                    'maxComments' => $maxComments,
                    'commentsRefused'=>  $commentsRefused,
                    'commentsPending'=> $commentsPending
                ]
                ));

            return $tpl->fetch(); 
        }  
         
    } 

    public function hookDisplayHome($params){
        // Retrieve the comments and pass them to the template
        $comments = $this->getComments();
        $comments = $this->getCoverImage($comments);
        
        // Assign the modified comments array to the template
        $this->context->smarty->assign('comments', $comments);
        
        return $this->displayTemplate($comments, "carousel_home");

    }

    public function hookDisplayOrderConfirmation2($params){
        $comments = $this->getComments();
        $comments = $this->getCoverImage($comments);
        
       return $this->displayTemplate($comments, 'comments_cart');
    }

   public function getCoverImage($comments){
        foreach ($comments as $key => $comment) {
            $product = new Product($comment['id_product'], false, $this->context->language->id);
            $img = $product->getCover($product->id);
            $link = new Link();
            $img_url = $link->getImageLink($product->link_rewrite, $img['id_image'], ImageType::getFormattedName('cart'));
            $comments[$key]['cover_image'] = Tools::getProtocol() . $img_url;
        }
        return $comments;
   }


}    

    