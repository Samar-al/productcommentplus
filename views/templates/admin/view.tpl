{**
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
 *}
<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title">Customer Details</h3>
    </div>
    <div class="panel-body">
        <table class="table">
            <tr>
                <th>Title</th>
                <td>{$data.title|escape:'htmlall':'UTF-8'}</td>
            </tr>
            <tr>
                <th>Commentaire</th>
                <td>{$data.comment|escape:'htmlall':'UTF-8'}</td>
            </tr>
            <tr>
                <th>Auteur</th>
                <td>{$data.author|escape:'htmlall':'UTF-8'}</td>
            </tr>
            <tr>
                <th>Note</th>
                <td>{$data.rating|escape:'htmlall':'UTF-8'}</td>
            </tr>
            <tr>
                <th>Produit</th>
                <td>{$data.product_name|escape:'htmlall':'UTF-8'}</td>
            </tr>
            <tr>
                <th>Id du produit</th>
                <td>{$data.id_product|escape:'htmlall':'UTF-8'}</td>
            </tr>
        </table>
    </div>

    <a class="btn btn-default" href="{$backUrl|escape:'htmlall':'UTF-8'}">
        <i class="icon-chevron-left"></i> {l s='Back to Configuration' mod='productcommentplus' d='productcommentplus'}
    </a>
</div>
