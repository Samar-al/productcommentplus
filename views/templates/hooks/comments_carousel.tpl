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
<div id="comments-carousel" class="carousel slide" data-ride="carousel">
    <!-- Indicators -->
    <ol class="carousel-indicators">
        {foreach $comments as $index => $comment}
            <li data-target="#comments-carousel" data-slide-to="{$index|escape:'htmlall':'UTF-8'}" {if $index == 0}class="active"{/if}></li>
        {/foreach}
    </ol>

    <!-- Slides -->
    <div class="carousel-inner">
        {assign var="hasComments" value=false}
        {foreach $comments as $index => $comment}
            {if !empty($comment.comments)}
                {assign var="hasComments" value=true}
                <div class="carousel-item {if $index == 0}active{/if}">
                    <div class="comment" style="text-align: center;">
                        <p><a href="{$comment.product_link|escape:'htmlall':'UTF-8'}">{$comment.product_name|escape:'htmlall':'UTF-8'}</a></p>
                        {if $comment.cover_image}
                        <a href="{$comment.product_link|escape:'htmlall':'UTF-8'}"><img src="{$comment.cover_image|escape:'htmlall':'UTF-8'}" alt="Cover Image" style="width: 50px;"></a>
                        {/if}
                        <div class="rating">
                            {if !empty($comment.comments[0])}
                                {assign var="stars" value=$comment.comments[0].rating}
                                {for $i = 1 to 5}
                                    {if $i <= $stars}
                                        <span class="star filled text-warning">&#9733;</span>
                                    {else}
                                        <span class="star text-warning">&#9734;</span>
                                    {/if}
                                {/for}
                                {if !empty($comment.comments[0].comment) && !empty($comment.comments[0].author)}
                                    <p style="margin-top: 1rem; color: black;">"{$comment.comments[0].comment|escape:'htmlall':'UTF-8'}"</p>
                                    <p>-{$comment.comments[0].author|escape:'htmlall':'UTF-8'}</p>
                                    <!-- Add any other comment details you want to display -->    
                                {/if}
                            {/if}
                        </div>
                    </div>
                </div>
            {/if}
        {/foreach}
        {if !$hasComments}
            <div class="carousel-item">
                <p>No comments available.</p>
            </div>
        {/if}
    </div>

    <!-- Controls -->
    <a class="carousel-control-prev" href="#comments-carousel" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#comments-carousel" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>
