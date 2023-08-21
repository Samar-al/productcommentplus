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
{if !empty($successMessage)}
    {foreach $successMessage as $message}
        <div class="alert alert-success" style="max-width: 400px; position: relative; margin-left: 10px;">
            {$message|escape:'htmlall':'UTF-8'}
            <span class="close-tab" style="position: absolute; top: 5px; right: 5px; padding: 10px; font-size: 20px; cursor: pointer;">&times;</span>
        </div>
    {/foreach}
{/if}

{if !empty($errorMessage)}
    <div class="alert alert-danger" style="max-width: 400px; position: relative; margin-left: 10px;">
        {$errorMessage|escape:'htmlall':'UTF-8'}
        <span class="close-tab" style="position: absolute; top: 5px; right: 5px; padding: 10px; font-size: 20px; cursor: pointer;">&times;</span>
    </div>
{/if}

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var closeTabs = document.getElementsByClassName('close-tab');
        Array.prototype.forEach.call(closeTabs, function(closeTab) {
            closeTab.addEventListener('click', function() {
                this.parentNode.style.display = 'none';
            });
        });
    });
</script>
