<?php
/**
 * ProductCommentPlusValidatedClass.php
 *
 * Represents the validated product comments class
 *
 * @author    Samar Al Khalil
 * @copyright Copyright (c)
 * @license   License (if applicable)
 * @category  Classes
 * @package   ProductCommentPlus
 * @subpackage Classes
 */




class ProductCommentPlusValidatedClass extends ObjectModel
{
    public $id_comment;
    public $title;
    public $comment;
    public $rating;
    public $author;
    public $id_product;
    public $date_add;
    public $product_name;

    public static $definition = [
        'table' => 'product_comment_plus_validated',
        'primary' => 'id_comment',
        'multilang' => false,
        'fields' => [
            'title' => ['type' => self::TYPE_STRING, 'validate' => 'isString', 'required' => true],
            'comment' => ['type' => self::TYPE_HTML, 'validate' => 'isCleanHtml', 'required' => true],
            'rating' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true],
            'author' => ['type' => self::TYPE_STRING, 'validate' => 'isString', 'required' => true],
            'id_product' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true],
            'date_add' => ['type' => self::TYPE_DATE, 'validate' => 'isDate', 'required' => true],
            'product_name' => ['type' => self::TYPE_STRING, 'validate' => 'isString'],
        ],
    ];    
    
    
}
