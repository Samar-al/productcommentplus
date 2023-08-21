<?php
/**
 * AdminProductCommentPlusRefusedController.php
 *
 * Controller for managing refused product comments
 *
 * @author    Samar Al Khalil
 * @copyright Copyright (c)
 * @license   License (if applicable)
 * @category  Controllers
 * @package   AdminControllers
 * @subpackage ProductCommentPlus
 */



require_once(_PS_MODULE_DIR_.'productcommentplus/classes/ProductCommentPlusRefusedClass.php');

class AdminProductCommentPlusRefusedController extends ModuleAdminController
{
    public function __construct()
    {
        $this->table = ProductCommentPlusRefusedClass::$definition['table'];
        $this->className = ProductCommentPlusRefusedClass::class;
        $this->module = Module::getInstanceByName('productcommentplus');
        $this->identifier = ProductCommentPlusRefusedClass::$definition['primary'];
        $this->_orderBy = ProductCommentPlusRefusedClass::$definition['primary'];
        $this->bootstrap = true;

        parent::__construct();

        $this->fields_list = [
            'id_comment' => [
                'title' => $this->l('ID'),
                'align' => 'center',
                'class' => 'fixed-width-xs',
            ],
            'title' => [
                'title' => $this->l('Titre de l\'avis'),
            ],
            'comment' => [
                'title' => $this->l('L\'avis'),
                'width' => 'auto',
            ],
            'rating' => [
                'title' => $this->l('Note'),
                'align' => 'center',
                'class' => 'fixed-width-xs',
            ],
            'author' => [
                'title' => $this->l('Auteur'),
            ],
            'id_product' => [
                'title' => $this->l('Produit'),
                'align' => 'center',
                'class' => 'fixed-width-lg',
            ],
            'date_add' => [
                'title' => $this->l('Date de publication'),
                'type' => 'datetime',
                'align' => 'center',
                'class' => 'fixed-width-lg',
            ],
            'product_name' => [
                'title' => $this->l('Product Name'),
                'align' => 'center',
                'class' => 'fixed-width-lg',
            ],
        ];
        

        $this->addRowAction('view');
        $this->addRowAction('edit');
        $this->addRowAction('delete');
    }

    public function renderForm()
    {
        $this->fields_form = [
            'legend' => [
                'title' => 'Modifier un commentaire',
                'icon' => 'icon-cog',
            ],
            'input' => [
                [
                    'type' => 'text',
                    'label' => 'Commentaire',
                    'name' => 'comment',
                    'required' => true,
                ],
                [
                    'type' => 'textarea',
                    'label' => 'Titre',
                    'name' => 'title',
                    'required' => true,
                ],
                [
                    'type' => 'text',
                    'label' => 'note',
                    'name' => 'rating',
                    'required' => true,
                ],
                [
                    'type' => 'text',
                    'label' => 'auteur',
                    'name' => 'author',
                    'required' => true,
                ],
                [
                    'type' => 'text',
                    'label' => 'Id du produit',
                    'name' => 'id_product',
                    'required' => true,
                ],
                [
                    'type' => 'text',
                    'label' => 'Nom du produit',
                    'name' => 'product_name', 
                    'readonly' => true, 
                ]
            ],     
            'submit' => [
                'title' => 'valider',
                'class' => 'btn btn-warning',
            ]
        ];

        return parent::renderForm();
    }


    public function renderView()
    {
        $idComment = (int)Tools::getValue('id_comment');
        $sql = new DbQuery();
        $sql->select('*')
            ->from($this->table)
            ->where('id_comment = ' . $idComment);
        $data = Db::getInstance()->executeS($sql);
        $tplFile = _PS_MODULE_DIR_.'productcommentplus/views/templates/admin/view.tpl';
        $tpl = $this->context->smarty->createTemplate($tplFile);
        $tpl->assign([
            'data' => $data[0],
            'backUrl' => $this->context->link->getAdminLink('AdminModules') . '&configure=productcommentplus&token=' . Tools::getAdminTokenLite('AdminModules'),
        ]);
        return $tpl->fetch();
    }

    public function processUpdate()
    {
        parent::processUpdate();
        $redirectUrl = $this->context->link->getAdminLink('AdminModules').'&configure=productcommentplus&token='.Tools::getAdminTokenLite('AdminModules');    
        return Tools::redirectAdmin($redirectUrl);
    }

    public function processDelete()
    {
        parent::processDelete();
        $redirectUrl = $this->context->link->getAdminLink('AdminModules').'&configure=productcommentplus&token='.Tools::getAdminTokenLite('AdminModules');    
        return Tools::redirectAdmin($redirectUrl);
    }


}