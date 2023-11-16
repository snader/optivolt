<div class="box social-share">
    <span><?= _e(SiteTranslations::get('social_share')) ?>:</span>
    <a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?= getCurrentUrl() ?>" rel="nofollow" class="icon facebook">
        <i class="fab fa-facebook"></i>
    </a>
    <a target="_blank" href="https://twitter.com/home?status=<?= getCurrentUrl() ?>" rel="nofollow" class="icon twitter">
        <i class="fab fa-twitter"></i>
    </a>
    <a target="_blank" href="https://www.linkedin.com/shareArticle?mini=true&url=<?= getCurrentUrl() ?>&title=&summary=&source=" rel="nofollow" class="icon linkedin">
        <i class="fab fa-linkedin"></i>
    </a>
    <a href="whatsapp://send?text=<?= getCurrentUrl() ?>" data-action="share/whatsapp/share" rel="nofollow" class="icon whatsapp">
        <i class="fab fa-whatsapp"></i>
    </a>
</div>