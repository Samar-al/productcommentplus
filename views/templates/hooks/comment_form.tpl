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
{if !empty($mysuccess)}
    {foreach from=$mysuccess item="success"}
        <div class="alert alert-success">{$success|escape:'htmlall':'UTF-8'}</div>
    {/foreach}
{/if}

{if !empty($myerrors)}
    {foreach from=$myerrors item="error"}
        <div class="alert alert-danger">{$danger|escape:'htmlall':'UTF-8'}</div>
    {/foreach}
{/if}    

<form id="commentForm" method="post" action="#" style="margin-top: 4rem;">
    <h3 style="margin-top: 2rem;">Laisser un commentaire</h3>
    <div class="form-group">
        <label for="comment_title">{l s='Title' mod='productcommentplus'}:</label>
        <input type="text" name="title" id="comment_title" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="comment_content">{l s='Comment' mod='productcommentplus'}:</label>
        <textarea name="comment" id="comment_content" class="form-control" rows="5" required></textarea>
    </div>
    <div class="form-group">
        <label for="comment_rating">{l s='Rating' mod='productcommentplus' }:</label>
        <select name="rating" id="comment_rating" class="form-control" required>
            <option value="5">{l s='5 stars' mod='productcommentplus'}</option>
            <option value="4">{l s='4 stars' mod='productcommentplus'}</option>
            <option value="3">{l s='3 stars' mod='productcommentplus'}</option>
            <option value="2">{l s='2 stars' mod='productcommentplus'}</option>
            <option value="1">{l s='1 star' mod='productcommentplus'}</option>
        </select>
    </div>
    <div class="form-group">
        <label for="comment_author">{l s='Author' mod='productcommentplus'}:</label>
        <input type="text" name="author" id="comment_author" class="form-control" required>
    </div>
     <div class="form-group">
        <input type="hidden" name="product_id" id="product_id" class="form-control" value="{$product->id|escape:'htmlall':'UTF-8'}" required>
    </div>
    <div class="form-group">
        <input type="submit" value="{l s='Submit' mod='productcommentplus'}" class="btn btn-primary" name="valider">
    </div>
</form>
