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
<div class="multi-carousel vertical" style="max-width: 20rem; position: relative;">
    <div class="multi-carousel-inner">
        {foreach $comments as $key => $comment}
        {if $key < $maxComments}
        <div class="multi-carousel-item slide-up" style="display: none;">
            <div class="list-group-item d-flex justify-content-between align-items-center" style="margin-top: 2rem; margin-bottom: 2rem;">
                <div class="d-flex justify-content-center" style="line-height: 1.5rem;">
                    <p><a href="{$link->getProductLink($comment.id_product)|escape:'htmlall':'UTF-8'}">{Product::getProductName($comment.id_product)|escape:'htmlall':'UTF-8'}</a></p>
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
            </div>
        </div>
        {/if}
        {/foreach}
    </div>
    <button class="carousel-control-prev" type="button" tabindex="0" data-mdb-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    </button>
    <button class="carousel-control-next" type="button" tabindex="0" data-mdb-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
    </button>
</div>

<style>
    .slide-up {
        animation: slideUp 0.5s ease-in-out forwards;
    }

    @keyframes slideUp {
        0% {
            opacity: 0;
            transform: translateY(100%);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .carousel-control-prev,
    .carousel-control-next {
        position: absolute;
        width: 40px;
        height: 40px;
        background-color: #fff;
        border: none;
        border-radius: 50%;
        opacity: 0.8;
        transition: opacity 0.3s ease-in-out;
    }

    .carousel-control-prev:hover,
    .carousel-control-next:hover {
        opacity: 1;
    }

    .carousel-control-prev {
        top: -20px;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .carousel-control-next {
        bottom: -20px;
        left: 50%;
        transform: translate(-50%, 50%);
    }

    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        display: inline-block;
        width: 20px;
        height: 20px;
        background-size: cover;
        background-repeat: no-repeat;
        margin: 0 auto;
    }

    .carousel-control-prev-icon {
        background-image: url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.6.1/icons/chevron-up.svg");
    }

    .carousel-control-next-icon {
        background-image: url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.6.1/icons/chevron-down.svg");
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const carouselItems = document.querySelectorAll(".multi-carousel-item");
        let currentIndex = 0;
        const intervalTime = 3000; // 3 seconds

        function showCurrentItem() {
            for (let i = 0; i < carouselItems.length; i++) {
                carouselItems[i].style.display = "none";
            }
            carouselItems[currentIndex].style.display = "block";
            carouselItems[currentIndex].classList.add("slide-up");
        }

        function nextItem() {
            currentIndex++;
            if (currentIndex >= carouselItems.length) {
                currentIndex = 0;
            }
            showCurrentItem();
        }

        function prevItem() {
            currentIndex--;
            if (currentIndex < 0) {
                currentIndex = carouselItems.length - 1;
            }
            showCurrentItem();
        }

        const prevButton = document.querySelector(".carousel-control-prev");
        const nextButton = document.querySelector(".carousel-control-next");

        prevButton.addEventListener("click", prevItem);
        nextButton.addEventListener("click", nextItem);

        showCurrentItem();

        // Automatically slide to the next item
        setInterval(nextItem, intervalTime);
    });
</script>
