<footer class="wrapper">
  <p class="copyright">&copy; PortNavi</p>

  <!-- Share Modal -->
  <div id="shareModal" class="share-modal" aria-hidden="true" role="dialog" aria-modal="true" aria-label="シェア">
    <div class="share-modal__overlay"></div>
    <div class="share-modal__content" role="document">
      <button class="share-modal__close" aria-label="閉じる">×</button>

      <h2 class="share-modal__title">このページをシェア</h2>

      <div class="share-actions">
        <a href="#" class="share-icon share-line" data-share="line" aria-label="LINEでシェア">
          <img src="<?php echo esc_url(get_template_directory_uri() . '/img/icons/LINE_Brand_icon.png'); ?>" alt="LINE">
        </a>
        <a href="#" class="share-icon share-fb" data-share="fb" aria-label="Facebookでシェア">
          <img src="<?php echo esc_url(get_template_directory_uri() . '/img/icons/Facebook_Logo_Primary.png'); ?>" alt="Facebook">
        </a>
        <a href="#" class="share-icon share-ig" data-share="ig" aria-label="Instagramでシェア">
          <img src="<?php echo esc_url(get_template_directory_uri() . '/img/icons/Instagram_Glyph_Gradient.png'); ?>" alt="Instagram">
        </a>
        <a href="#" class="share-icon share-x" data-share="x" aria-label="Xでシェア">
          <img src="<?php echo esc_url(get_template_directory_uri() . '/img/icons/X _Brand_icon.png'); ?>" alt="X">
        </a>
      </div>

      <div class="share-link-box">
        <input type="text" id="shareLink" readonly value="<?php echo esc_url((is_singular() ? get_permalink() : home_url(add_query_arg(null, null)))); ?>">
        <button id="copyBtn">コピー</button>
      </div>

      <p class="share-note">※ Instagramはリンクコピー推奨です。</p>
    </div>
  </div>

  <!-- トップに戻るボタン -->
  <div class="to-top">↑</div>
</footer>

<?php wp_footer(); ?>

</body>
</html>
