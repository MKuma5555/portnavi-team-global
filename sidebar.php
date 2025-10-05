<aside class="side-nav" aria-label="サイドバー">
  <ul class="side-nav__tabs">
    <li class="file-tab">
    <button class="side-tab" aria-expanded="false" aria-controls="side-filter-panel" type="button">
      カテゴリー別で探す </button>

      <div id="side-filter-panel" class="index-panel" hidden>
        <?php
        echo do_shortcode('
          [searchandfilter 
            fields="site_type,design_type,color" 
            types="checkbox,checkbox,checkbox" 
            headings="作品タイプ,デザイン,カラー" 
            operators="OR,OR,OR" 
            post_types="post"
            submit_label="絞り込む"
          ]
        ');
        ?>
      </div>
    </li>
  </ul>
</aside>