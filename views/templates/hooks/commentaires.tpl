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
<li id="tab_custom" class="nav-item">
    <a href="#custom" role="tab" data-toggle="tab" class="nav-link">
        <div class="container">
            <h2>Commentaires</h2>

            <!-- Display comments -->
            <table class="table">
                <thead>
                    <tr>
                        <th>{'ID'|escape:'htmlall':'UTF-8'}</th>
                        <th>{'Titre de l\'avis'|escape:'htmlall':'UTF-8'}</th>
                        <th>{'L\'avis'|escape:'htmlall':'UTF-8'}</th>
                        <th>{'Note'|escape:'htmlall':'UTF-8'}</th>
                        <th>{'Auteur'|escape:'htmlall':'UTF-8'}</th>
                        <th>{'Produit'|escape:'htmlall':'UTF-8'}</th>
                        <th>{'Date de publication'|escape:'htmlall':'UTF-8'}</th>
                        <th>{'Product Name'|escape:'htmlall':'UTF-8'}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach $comments as $key => $comment}
                    <tr>
                        <td>{$comment.id_comment|escape:'htmlall':'UTF-8'}</td>
                        <td>{$comment.title|escape:'htmlall':'UTF-8'}</td>
                        <td>{$comment.comment|escape:'htmlall':'UTF-8'}</td>
                        <td>{$comment.rating|escape:'htmlall':'UTF-8'}</td>
                        <td>{$comment.author|escape:'htmlall':'UTF-8'}</td>
                        <td>{$comment.id_product|escape:'htmlall':'UTF-8'}</td>
                        <td>{$comment.date_add|escape:'htmlall':'UTF-8'}</td>
                        <td>{Product::getProductName($comment.id_product)|escape:'htmlall':'UTF-8'}</td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>

            <!-- Display commentsRefused if any -->
            {if $commentsRefused}
            <h2>Commentaires refusés</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>{'ID'|escape:'htmlall':'UTF-8'}</th>
                        <th>{'Titre de l\'avis'|escape:'htmlall':'UTF-8'}</th>
                        <th>{'L\'avis'|escape:'htmlall':'UTF-8'}</th>
                        <th>{'Note'|escape:'htmlall':'UTF-8'}</th>
                        <th>{'Auteur'|escape:'htmlall':'UTF-8'}</th>
                        <th>{'Produit'|escape:'htmlall':'UTF-8'}</th>
                        <th>{'Date de publication'|escape:'htmlall':'UTF-8'}</th>
                        <th>{'Product Name'|escape:'htmlall':'UTF-8'}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach $commentsRefused as $key => $commentRefused}
                    <tr>
                        <td>{$commentRefused.id_comment|escape:'htmlall':'UTF-8'}</td>
                        <td>{$commentRefused.title|escape:'htmlall':'UTF-8'}</td>
                        <td>{$commentRefused.comment|escape:'htmlall':'UTF-8'}</td>
                        <td>{$commentRefused.rating|escape:'htmlall':'UTF-8'}</td>
                        <td>{$commentRefused.author|escape:'htmlall':'UTF-8'}</td>
                        <td>{$commentRefused.id_product|escape:'htmlall':'UTF-8'}</td>
                        <td>{$commentRefused.date_add|escape:'htmlall':'UTF-8'}</td>
                        <td>{Product::getProductName($comment.id_product)|escape:'htmlall':'UTF-8'}</td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
            {/if}

            <!-- Display commentsPending if any -->
            {if $commentsPending}
            <h2>Commentaires en attente</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>{'ID'|escape:'htmlall':'UTF-8'}</th>
                        <th>{'Titre de l\'avis'|escape:'htmlall':'UTF-8'}</th>
                        <th>{'L\'avis'|escape:'htmlall':'UTF-8'}</th>
                        <th>{'Note'|escape:'htmlall':'UTF-8'}</th>
                        <th>{'Auteur'|escape:'htmlall':'UTF-8'}</th>
                        <th>{'Produit'|escape:'htmlall':'UTF-8'}</th>
                        <th>{'Date de publication'|escape:'htmlall':'UTF-8'}</th>
                        <th>{'Product Name'|escape:'htmlall':'UTF-8'}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach $commentsPending as $key => $commentPending}
                    <tr>
                        <td>{$commentPending.id_comment|escape:'htmlall':'UTF-8'}</td>
                        <td>{$commentPending.title|escape:'htmlall':'UTF-8'}</td>
                        <td>{$commentPending.comment|escape:'htmlall':'UTF-8'}</td>
                        <td>{$commentPending.rating|escape:'htmlall':'UTF-8'}</td>
                        <td>{$commentPending.author|escape:'htmlall':'UTF-8'}</td>
                        <td>{$commentPending.id_product|escape:'htmlall':'UTF-8'}</td>
                        <td>{$commentPending.date_add|escape:'htmlall':'UTF-8'}</td>
                        <td>{Product::getProductName($comment.id_product)|escape:'htmlall':'UTF-8'}</td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
            {/if}
        </
