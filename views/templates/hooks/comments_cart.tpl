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
<ol class="list-group list-group-numbered">
    <h2>Commentaires</h2>
   {foreach $comments as $key => $comment } 
    {if $key < $maxComments}
      <li class="list-group-item d-flex justify-content-between align-items-center">
        <div class="d-flex justify-content-center" style="line-height: 1.5rem;">
        <p><a href="{$link->getProductLink($comment.id_product)|escape:'htmlall':'UTF-8'}">{Product::getProductName($comment.id_product)|escape:'htmlall':'UTF-8'}</a></p>
         {if $comment.cover_image}
              <a href="{$link->getProductLink($comment.id_product)|escape:'htmlall':'UTF-8'}"><img src="{$comment.cover_image|escape:'htmlall':'UTF-8'}" alt="Cover Image" style="width: 50px;"></a> 
          {/if}
          <div class="rating" style="text-align: center;">
              {assign var="stars" value=$comment.rating}
              {for $i = 1 to 5}
                  {if $i <= $stars}
                      <span class="star filled text-warning">&#9733;</span>
                  {else}
                      <span class="star text-warning">&#9734;</span>
                  {/if}
              {/for}
            </div>
          
            <div class="text-center" style="text-align: center;">
              {$comment.comment|escape:'htmlall':'UTF-8'}
            </div>
            <p class="mb-0" style="text-align: center;">-{$comment.author|escape:'htmlall':'UTF-8'}</p>   
        </div>
      </li>
    {/if}
  {/foreach}
</ol>