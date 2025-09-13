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
            <li><a href="<?php echo esc_url(home_url('/event.html'));?>">Event</a></li>
          </ul>
        </nav>
      
        
  <!-- 検索フォーム -->
  <form method="get" action="#" class="search_container">
          <input type="text" size="25" placeholder="キーワード検索" />
          <button type="submit">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              xmlns:xlink="http://www.w3.org/1999/xlink"
              version="1.1"
              id="_x32_"
              x="0px"
              y="0px"
              width="48px"
              height="48px"
              viewBox="0 0 512 512"
              style="width: 28px; height: 28px; opacity: 1"
              xml:space="preserve"
            >
              <style type="text/css">
                .st0 {
                  fill: #4a2b00;
                }
              </style>
              <g>
                <path
                  class="st0"
                  d="M499.516,439.313l-79.547-79.563l-60.219,60.219l79.547,79.563c8.047,8.031,18.734,12.469,30.125,12.469   c11.359,0,22.047-4.438,30.094-12.469c8.063-8.047,12.484-18.75,12.484-30.125S507.563,447.344,499.516,439.313z"
                />
                <path
                  class="st0"
                  d="M399.391,362.313L358,320.906c0.063-0.094,0.063-0.188,0.125-0.25c26.563-34.719,41.156-77.688,41.125-121.031   c0.047-53.281-20.703-103.438-58.469-141.156C303.109,20.766,253.063,0,199.375,0C146.172,0,96.141,20.766,58.469,58.469   C20.703,96.188-0.063,146.344,0,199.641c-0.047,53.297,20.719,103.422,58.453,141.141c37.688,37.719,87.766,58.469,141.188,58.469   h0.188c43.234,0,86.141-14.594,120.828-41.125c0.078-0.063,0.156-0.094,0.234-0.125l41.406,41.406L399.391,362.313z    M294.688,294.688c-25.391,25.344-59.125,39.344-95.078,39.406c-35.922-0.063-69.672-14.063-95.047-39.406   c-25.359-25.359-39.344-59.125-39.391-95.063c0.047-35.938,14.031-69.688,39.375-95.063c25.375-25.344,59.125-39.313,95.063-39.391   c0.016-0.016,0.031,0,0.031,0c35.922,0.078,69.672,14.047,95.047,39.391c25.344,25.359,39.328,59.125,39.391,95.094   C334.016,235.578,320.031,269.344,294.688,294.688z"
                />
              </g>
            </svg>
          </button>
        </form>
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
