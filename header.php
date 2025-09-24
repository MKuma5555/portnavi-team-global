<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8" />
    <title>PortNavi</title>
    <meta
      name="description"
      content="テキストテキストテキストテキストテキストテキストテキストテキス"
    />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
<?php wp_head();?>

  </head>

  <body>
    <header class="">
      <div class="site-title">
        <h1 class="site-title monoton-regular">
          <a href="<?php echo esc_url(home_url());?>">PortNavi</a>
        </h1>
      </div>

      <div class="navbar-container">
        <nav>
          <ul class="nav-menu">
            <li><a href="<?php echo esc_url(home_url());?>">Home</a></li>
            <li><a href="<?php echo esc_url(home_url('/category.html'));?>">Category</a></li>
            <?php $page = get_page_by_path('event'); if ($page) :?>
              <li><a href="<?php echo esc_url(get_permalink($page->ID)); ?>">Event</a></li>
            <?php endif; ?>

          </ul>
        </nav>


        <!-- 検索フォーム1 search&filter　これは参考後で消す-->
        <!-- <?php echo do_shortcode('[searchwp_form id="1"]'); ?> -->
        <!-- <?php echo do_shortcode('[searchandfilter  fields="search,category,post_tag" headings=",Categories,Tags" types="search" post_types="post"]'); ?> -->
         
        
        <!-- 検索フォーム 本番用-->
        <form method="get"action="<?php echo esc_url( home_url( '/' ) ); ?>" class="search_container">
        <input type="text" id="live-search" size="25" name="s" placeholder="キーワード検索" value=""/>
            <button id="clear-button" type="button">×</button>
            <button id="header-keywords-submit" type="submit">
              検索
            </button>
        </form>
      </div>



      <div id="keywords-search-results">
      </div>

<!-- SP版　レスポンシブ　ハンバーガーメニュー -->
      <div class="responsive-nav">
        <div class="header__inner">
          <button
            id="js-hamburger"
            type="button"
            class="hamburger"
            aria-controls="navigation"
            aria-expanded="false"
            aria-label="メニューを開く"
          >
            <span class="hamburger__line"></span>
            <span class="hamburger__text"></span>
          </button>
          <div class="header__nav-area js-nav-area" id="navigation">
            <nav id="js-global-navigation" class="global-navigation sp">
              <ul class="global-navigation__list">
                <li>
                  <a href="<?php echo esc_url(home_url());?>" class="global-navigation__link"> メニュー </a>
                </li>
                <li>
                  <button
                    type="button"
                    class="global-navigation__link -accordion js-sp-accordion-trigger"
                    aria-expanded="false"
                    aria-controls="accordion1"
                  >
                    親メニュー
                  </button>
                  <div id="accordion1" class="accordion js-accordion">
                    <ul class="accordion__list">
                      <li>
                        <a href="<?php echo esc_url(home_url());?>" class="accordion__link"> 子メニュー </a>
                      </li>
                      <li>
                        <a href="<?php echo esc_url(home_url());?>" class="accordion__link"> 子メニュー </a>
                      </li>
                      <li>
                        <a href="<?php echo esc_url(home_url());?>" class="accordion__link"> 子メニュー </a>
                      </li>
                    </ul>
                  </div>
                </li>
                <li>
                  <a href="<?php echo esc_url(home_url());?>" class="global-navigation__link"> メニュー </a>
                </li>
              </ul>
              <div id="js-focus-trap" tabindex="0"></div>
            </nav>
          </div>
        </div>
      </div>
      
    </header>
