<?php
/**
 * AdminProductCommentPlusPendingController.php
 *
 * @author    Samar Al khalil
 * @copyright Copyright (c) Your Year
 * @license   License (if applicable)
 * @category  Controllers
 * @package   AdminControllers
 * @subpackage ProductCommentPlus
 */
require_once(_PS_MODULE_DIR_.'productcommentplus/classes/ProductCommentPlusPendingClass.php');
require_once(_PS_MODULE_DIR_.'productcommentplus/classes/ProductCommentPlusValidatedClass.php');
require_once(_PS_MODULE_DIR_.'productcommentplus/classes/ProductCommentPlusRefusedClass.php');

class AdminProductCommentPlusPendingController extends ModuleAdminController
{
    public function __construct()
    {
        $this->table = ProductCommentPlusPendingClass::$definition['table'];
        $this->className = ProductCommentPlusPendingClass::class;
        $this->module = Module::getInstanceByName('productcommentplus');
        $this->identifier = ProductCommentPlusPendingClass::$definition['primary'];
        $this->_orderBy = ProductCommentPlusPendingClass::$definition['primary'];
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
                'title' => 'Modifier/valider un commentaire',
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
                ],
            ],     
            'submit' => [
                'title' => 'Modifier',
                'class' => 'btn btn-warning',
            ],
            'buttons' => [
                [
                    'title' => 'Valider le commentaire',
                    'name' => 'validate_button',
                    'type' => 'submit',
                    'class' => 'btn btn-secondary',
                ],
                [
                    'title' => 'Refuser le commentaire',
                    'name' => 'refuse_button',
                    'type' => 'submit',
                    'class' => 'btn btn-secondary',
                ],
            ],           
        ];

        $comment = $this->loadObject();  
        if ($comment instanceof ProductCommentPlusPendingClass) {
            $this->fields_value['product_name'] = $comment->product_name;
        }
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

    public function postProcess()
    {
        if (Tools::isSubmit('validate_button')) {
            if (($idObject = Tools::getValue('id_comment')) && ($object = $this->loadObject())) {
                // Retrieve the comment from the pending table
                $pendingComment = new ProductCommentPlusPendingClass($idObject);

                // Create a new comment object for the validated table
                $validatedComment = new ProductCommentPlusValidatedClass();
                $validatedComment->title = $pendingComment->title;
                $validatedComment->comment = $pendingComment->comment;
                $validatedComment->rating = $pendingComment->rating;
                $validatedComment->author = $pendingComment->author;
                $validatedComment->id_product = $pendingComment->id_product;
                $validatedComment->date_add = $pendingComment->date_add;
                $validatedComment->product_name = $pendingComment->product_name;

                // Save the validated comment
                $validatedComment->add();

                // Delete the comment from the pending table
                $pendingComment->delete();

                $redirectUrl = $this->context->link->getAdminLink('AdminModules').'&configure=productcommentplus&token='.Tools::getAdminTokenLite('AdminModules');
                Tools::redirectAdmin($redirectUrl);
            }
        }

        if (Tools::isSubmit('refuse_button')){
            if (($idObject = Tools::getValue('id_comment')) && ($object = $this->loadObject())) {
                // Retrieve the comment from the pending table
                $pendingComment = new ProductCommentPlusPendingClass($idObject);

                // Create a new comment object for the refused table
                $refusedComment = new ProductCommentPlusRefusedClass();
                $refusedComment->title = $pendingComment->title;
                $refusedComment->comment = $pendingComment->comment;
                $refusedComment->rating = $pendingComment->rating;
                $refusedComment->author = $pendingComment->author;
                $refusedComment->id_product = $pendingComment->id_product;
                $refusedComment->date_add = $pendingComment->date_add;
                $refusedComment->product_name = $pendingComment->product_name;

                // Save the refused comment
                $refusedComment->add();

                // Delete the comment from the pending table
                $pendingComment->delete();
                $redirectUrl = $this->context->link->getAdminLink('AdminModules').'&configure=productcommentplus&token='.Tools::getAdminTokenLite('AdminModules');
                Tools::redirectAdmin($redirectUrl);
            }
        }

        return parent::postProcess();
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
