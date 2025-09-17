<aside class="side-nav">
  <ul>
    <!-- === Webサイトカテゴリ === -->
    <li class="file-tab" role="presentation" tabindex="0">
      <a href="javascript:void(0);" role="tab">WEBサイト</a>
      <div class="index-panel" role="tabpanel" aria-hidden="false">
        <?php
        echo do_shortcode('[searchandfilter 
          fields="site_type" 
          types="checkbox"
          operators="OR"
          headings=" "
          taxonomies="site_type"
          post_types="post"
          hide_empty="1"
          order_by="name"
        ]');
        ?>
      </div>
    </li>

    <!-- === デザインカテゴリ === -->
    <li class="file-tab" role="presentation" tabindex="0">
      <a href="javascript:void(0);" role="tab">デザイン</a>
      <div class="index-panel" role="tabpanel" aria-hidden="true">
        <?php
        echo do_shortcode('[searchandfilter 
          fields="design_type" 
          types="checkbox"
          operators="OR"
          headings=" "
          taxonomies="design_type"
          post_types="post"
          hide_empty="1"
          order_by="name"
        ]');
        ?>
      </div>
    </li>

    <!-- === カラー別 === -->
    <li class="file-tab" role="presentation" tabindex="0">
      <a href="javascript:void(0);" role="tab">カラー別</a>
      <div class="index-panel" role="tabpanel" aria-hidden="true">
        <?php
        echo do_shortcode('[searchandfilter 
          fields="color" 
          types="checkbox"
          operators="OR"
          headings=" "
          taxonomies="color"
          post_types="post"
          hide_empty="1"
          order_by="name"
        ]');
        ?>
      </div>
    </li>
  </ul>
</aside>