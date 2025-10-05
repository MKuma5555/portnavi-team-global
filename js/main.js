// 変数定義
const CLASS = "-active";
let flg = false;
let accordionFlg = false;

let hamburger = document.getElementById("js-hamburger");
let focusTrap = document.getElementById("js-focus-trap");
// let menu = document.querySelector(".js-nav-area");
let menu = document.querySelector(".header__nav-area");
let accordionTrigger = document.querySelectorAll(".js-sp-accordion-trigger");
let accordion = document.querySelectorAll(".js-sp-accordion");

// メニュー開閉制御
hamburger.addEventListener("click", (e) => {
  console.log("clicked");
  //ハンバーガーボタンが選択されたら
  e.currentTarget.classList.toggle(CLASS);
  menu.classList.toggle(CLASS);
  if (flg) {
    // flgの状態で制御内容を切り替え
    // backgroundFix(false);
    hamburger.setAttribute("aria-expanded", "false");
    hamburger.focus();
    flg = false;
  } else {
    // backgroundFix(true);
    hamburger.setAttribute("aria-expanded", "true");
    flg = true;
  }
});
window.addEventListener("keydown", () => {
  //escキー押下でメニューを閉じられるように
  if (event.key === "Escape") {
    hamburger.classList.remove(CLASS);
    menu.classList.remove(CLASS);

    // backgroundFix(false);
    hamburger.focus();
    hamburger.setAttribute("aria-expanded", "false");
    flg = false;
  }
});

// メニュー内アコーディオン制御
accordionTrigger.forEach((item) => {
  item.addEventListener("click", (e) => {
    e.currentTarget.classList.toggle(CLASS);
    e.currentTarget.nextElementSibling.classList.toggle(CLASS);
    if (accordionFlg) {
      e.currentTarget.setAttribute("aria-expanded", "false");
      accordionFlg = false;
    } else {
      e.currentTarget.setAttribute("aria-expanded", "true");
      accordionFlg = true;
    }
  });
});

// フォーカストラップ制御
focusTrap.addEventListener("focus", (e) => {
  hamburger.focus();
});

// Detail Page: コメントドロワー（右寄せ＆左幅自動調整）
(function () {
  const openBtn = document.querySelector('[data-action="open-comments"]');
  const drawer = document.getElementById("commentDrawer");
  const closeBtn = drawer ? drawer.querySelector(".drawer-close") : null;

  if (!openBtn || !drawer || !closeBtn) return;

  // 現在のドロワー実幅（境界線込み）を CSS 変数に反映
  function setDrawerWidthVar() {
    const w = Math.round(drawer.getBoundingClientRect().width);
    document.documentElement.style.setProperty("--drawer-w", w + "px");
  }

  function openDrawer(e) {
    if (e) e.preventDefault();
    setDrawerWidthVar(); // ← 開く直前に実測
    drawer.classList.add("is-open");
    document.body.classList.add("drawer-open", "is-locked");
    drawer.setAttribute("aria-hidden", "false");
    openBtn.setAttribute("aria-expanded", "true");
    setTimeout(() => closeBtn.focus(), 0);
  }
  let lastTrigger = null; // 直近で押したトリガー（フォーカス返却用）

  function openDrawer(trigger) {
    setDrawerWidthVar && setDrawerWidthVar(); // 幅を実測（関数がある前提）
    drawer.classList.add("is-open");
    document.body.classList.add("drawer-open", "is-locked");
    drawer.setAttribute("aria-hidden", "false");
    (trigger || openBtn).setAttribute("aria-expanded", "true");
    lastTrigger = trigger || openBtn;
    setTimeout(() => closeBtn.focus(), 0);
  }

  function closeDrawer(returnTo) {
    drawer.classList.remove("is-open");
    document.body.classList.remove("drawer-open", "is-locked");
    drawer.setAttribute("aria-hidden", "true");
    // 直近トリガーのaria-expandedを戻す
    (returnTo || lastTrigger || openBtn)?.setAttribute(
      "aria-expanded",
      "false"
    );
    // フォーカスも元のトリガーへ返す
    (returnTo || lastTrigger || openBtn)?.focus();
    lastTrigger = null;
  }

  // ▼ トグル化：同じボタンで開/閉
  const openBtns = document.querySelectorAll('[data-action="open-comments"]');
  openBtns.forEach((btn) => {
    btn.addEventListener("click", (e) => {
      e.preventDefault();
      if (drawer.classList.contains("is-open")) {
        closeDrawer(btn); // 押したボタンへフォーカス返す
      } else {
        openDrawer(btn); // 押したボタンを opener として記録
      }
    });
  });

  // ×ボタン
  closeBtn.addEventListener("click", () => closeDrawer());

  // Esc で閉じる（フォーカスは opener へ戻す）
  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape" && drawer.classList.contains("is-open")) {
      closeDrawer();
    }
  });

  // 画面幅が変わったら、開いている時だけ再計測
  window.addEventListener("resize", () => {
    if (drawer.classList.contains("is-open")) setDrawerWidthVar();
  });

  // コメントのコメントを畳んでおいて開けるように（安全版）
  function wireToggles(scope) {
    const root = scope || document;
    const toggles = root.querySelectorAll(".comment-actions .toggle-replies");

    toggles.forEach((btn) => {
      const li = btn.closest("li");
      if (!li) return;

      // :scope 非対応ブラウザでも動くフォールバック
      let children = li.querySelector(":scope > ul.children");
      if (!children) {
        children = Array.from(li.children).find(
          (el) => el && el.matches && el.matches("ul.children")
        );
      }
      if (!children) {
        btn.hidden = true; // 子が無いならボタン非表示
        return;
      }

      // 件数は data-count 優先 → なければ DOM の子要素数で代替
      const count =
        btn.dataset.count ?? (children.children ? children.children.length : 0);
      const countPart = count ? `（${count}）` : "";

      // ラベル設定関数（undefinedが出ないよう一箇所で組み立て）
      const setLabel = (isOpen) => {
        btn.textContent = (isOpen ? "返信を非表示" : "返信を表示") + countPart;
        btn.setAttribute("aria-expanded", isOpen ? "true" : "false");
      };

      // 初期は閉じる
      children.classList.remove("is-open");
      setLabel(false);

      // クリックで開閉
      btn.addEventListener("click", () => {
        const open = children.classList.toggle("is-open");
        setLabel(open);
      });
    });
  }

  // 初期化：ページ読み込み時（ドロワー内だけ対象に）
  document.addEventListener("DOMContentLoaded", () => {
    const drawer = document.getElementById("commentDrawer");
    wireToggles(drawer || document);
  });

  // ページのシェア機能の追加
  // ===== Share modal =====
  document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("shareModal");
    if (!modal) return;

    const overlay = modal.querySelector(".share-modal__overlay");
    const closeBtn = modal.querySelector(".share-modal__close");
    const copyBtn = document.getElementById("copyBtn");
    const linkInp = document.getElementById("shareLink");
    const icons = modal.querySelectorAll(".share-icon");

    // 開くトリガー
    document.body.addEventListener("click", (e) => {
      const trigger = e.target.closest(
        '[data-action="open-share"], .share-open'
      );
      if (!trigger) return;

      e.preventDefault();

      // data属性優先で反映
      const url = trigger.dataset.shareUrl || window.location.href;
      const title = trigger.dataset.shareTitle || document.title;

      linkInp.value = url;
      modal.setAttribute("aria-hidden", "false");
    });

    // 閉じる
    [overlay, closeBtn].forEach((el) =>
      el.addEventListener("click", () => {
        modal.setAttribute("aria-hidden", "true");
      })
    );
    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape" && modal.getAttribute("aria-hidden") === "false") {
        modal.setAttribute("aria-hidden", "true");
      }
    });

    // コピー（大ボタン）
    copyBtn.addEventListener("click", () => {
      copyToClipboard(linkInp.value).then(() => {
        copyBtn.textContent = "コピー済み！";
        setTimeout(() => (copyBtn.textContent = "コピー"), 1200);
      });
    });

    // アイコンの動作
    icons.forEach((a) => {
      a.addEventListener("click", async (e) => {
        e.preventDefault();
        const rawUrl = linkInp.value || window.location.href;
        const url = encodeURIComponent(rawUrl);
        const title = encodeURIComponent(document.title);

        switch (a.dataset.share) {
          case "line":
            popup(
              `https://social-plugins.line.me/lineit/share?url=${url}`,
              "line"
            );
            break;

          case "fb":
            popup(`https://www.facebook.com/sharer/sharer.php?u=${url}`, "fb");
            break;

          case "x":
            // X (Twitter)
            popup(
              `https://twitter.com/intent/tweet?url=${url}&text=${title}`,
              "x"
            );
            break;

          case "ig":
            // まずURLをコピー → アプリ（またはWeb）を開く
            await copyToClipboard(rawUrl);
            // アプリを試みる（失敗時はWebへ）
            const appScheme = "instagram://app";
            const webURL = "https://www.instagram.com/";
            openDeepLink(appScheme, webURL);
            break;

          case "copy":
            await copyToClipboard(rawUrl);
            alert("リンクをコピーしました！");
            break;
        }
      });
    });

    function popup(u, name) {
      const w = 620,
        h = 600;
      const y = window.top.outerHeight / 2 + window.top.screenY - h / 2;
      const x = window.top.outerWidth / 2 + window.top.screenX - w / 2;
      window.open(
        u,
        name,
        `width=${w},height=${h},left=${x},top=${y},resizable,scrollbars`
      );
    }

    function copyToClipboard(text) {
      if (navigator.clipboard && window.isSecureContext) {
        return navigator.clipboard.writeText(text);
      }
      // フォールバック
      const ta = document.createElement("textarea");
      ta.value = text;
      document.body.appendChild(ta);
      ta.select();
      document.execCommand("copy");
      document.body.removeChild(ta);
      return Promise.resolve();
    }

    // アプリ深リンク → 失敗時 Web を開く
    function openDeepLink(appUrl, fallbackUrl) {
      const timeout = setTimeout(() => {
        window.open(fallbackUrl, "_blank");
      }, 800);
      // iOS/Android での挙動をカバー
      window.location.href = appUrl;
      // 一部ブラウザでの二重遷移防止
      window.addEventListener("pagehide", () => clearTimeout(timeout), {
        once: true,
      });
    }
  });

  // ===== WP ULike をあなたのカスタムアイコンで操作する =====
  document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".like-proxy").forEach((proxy) => {
      const hiddenId = proxy.dataset.likeTarget;
      const hiddenWrap = document.getElementById(hiddenId);
      if (!hiddenWrap) return;

      const realBtn = hiddenWrap.querySelector(".wp_ulike_btn"); // WP ULike の実ボタン
      const realCountEl = hiddenWrap.querySelector(".count-box"); // WP ULike のカウント表示
      const countEl = proxy.querySelector(".like-count");

      // 初期の liked 状態を CSS に反映（任意）
      syncState();

      proxy.addEventListener("click", (e) => {
        e.preventDefault();
        if (!realBtn) return;

        // 本物ボタンをクリック（Ajaxはプラグインが処理）
        realBtn.click();

        // Ajax反映後にカウントを同期（少し待つ）
        setTimeout(syncState, 500);
      });

      // DOM 変化を監視して自動で同期（より確実）
      if (realCountEl) {
        const mo = new MutationObserver(syncState);
        mo.observe(realCountEl, {
          childList: true,
          subtree: true,
          characterData: true,
        });
      }

      function syncState() {
        // カウント同期
        if (realCountEl && countEl) {
          const num = parseInt(realCountEl.textContent.replace(/\D/g, ""), 10);
          if (!isNaN(num)) countEl.textContent = num;
        }
        // liked クラスを反映（ボタンのクラスに liked / unliked が付与される）
        if (realBtn) {
          const liked =
            realBtn.classList.contains("wp_ulike_btn_is_active") ||
            realBtn.classList.contains("wp_ulike_btn_liked");
          proxy.classList.toggle("is-liked", !!liked);
        }
      }
    });
  });

// ==========================
// サイドナビ開閉制御
// ==========================
$(function () {
  const $sideNav = $('.side-nav');
  const $button = $sideNav.find('.side-tab');
  const $panel = $sideNav.find('.index-panel');
  const $navArea = $('#navigation'); // ← ハンバーガー領域

  // 初期状態を閉じる
  $button.attr('aria-expanded', 'false');
  $panel.attr('hidden', true);

  // クリックで開閉
  $button.on('click', function (e) {
    e.stopPropagation();

    // ハンバーガーが開いている間は反応しない
    if ($navArea.hasClass('-active')) return;

    const isOpen = $(this).attr('aria-expanded') === 'true';
    $(this).attr('aria-expanded', String(!isOpen));
    $panel.attr('hidden', isOpen);
  });

  // 欄外クリックで閉じる
  $(document).on('click', function (e) {
    if (!$(e.target).closest('.side-nav').length) {
      $button.attr('aria-expanded', 'false');
      $panel.attr('hidden', true);
    }
  });
});


})();

/////////////////////////// To TOP-Bottom 上へスクロール　この呼び出しを一番下に持ってきました。/////////////////////////////////
$(function () {
  let pagetop = $(".to-top");
  pagetop.hide(); // 初期は非表示

  // スクロール時
  $(window).scroll(function () {
    if ($(this).scrollTop() > 500) {
      pagetop.fadeIn();
    } else {
      pagetop.fadeOut();
    }
  });

  // クリック時
  pagetop.click(function () {
    $("html, body").animate({ scrollTop: 0 }, 500);
    return false;
  });
});

const toTopBtn = document
  .querySelector(".to-top")
  .addEventListener("click", handleBackToTop);

function handleBackToTop() {
  console.log(`back to top btn clicked `);
  if (toTopBtn) {
    window.scrollTo({ top: 0 }, 500);
    console.log(`back to top btn clicked 123`);
  }
}
