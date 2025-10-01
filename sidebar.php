<aside class="side-nav" aria-label="サイドバー">
  <ul class="side-nav__tabs">
    <li class="file-tab is-active" role="presentation" tabindex="0">
      <a href="javascript:void(0);" role="tab" aria-selected="true">カテゴリー別で探す</a>

      <div class="index-panel" role="tabpanel" aria-hidden="false">
        <?php
        echo do_shortcode('
          [searchandfilter 
            fields="site_type,design_type,color" 
            types="checkbox,checkbox,checkbox" 
            headings="WEBサイト,デザイン,カラー" 
            operators="OR,OR,OR" 
            post_types="post"
            submit_label="絞り込む"
          ]
        ');
        ?>
      </div>
    </li>
  </ul>
  <!-- モバイル表示時に使うオーバーレイ -->
  <div class="side-nav__overlay" hidden></div>
</aside>