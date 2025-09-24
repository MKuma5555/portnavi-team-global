// ///////////////////////////////////////////////////////////////////////////////////////////
// 2文字未満 → 予測検索では何も出さない
// 2〜4文字 → 結果があれば出す、なければ非表示
// 5文字以上 → 結果がなければ「検索結果が見当たりません」と表示
// 検索ボタンを押した場合 → 必ず「検索結果が見当たりませんでした」を表示
let isLoading = false;
const searchResultsContainer = document.getElementById(
  "keywords-search-results"
);
const clearBtn = document.getElementById("clear-button");
const keywordsInput = document.getElementById("live-search");

// // // クリアボタン機能 (クリックで入力欄と結果をクリア)
clearBtn.addEventListener("click", function () {
  keywordsInput.value = "";
  searchResultsContainer.style.display = "none";
  searchResultsContainer.innerHTML = "";
  clearBtn.style.display = "none";
  isLoading = false;
});

// fetchして検索結果を取得し、表示する関数
// 1. キーワード検索
async function keywordsQueryCheck(keywordsQuery) {
  const res = await fetch(
    `/wp-json/wp/v2/posts?search=${encodeURIComponent(
      keywordsQuery
    )}&per_page=5&_embed`
  );
  return res.json();
}

// 2. カテゴリー名からカテゴリーIDを取得
async function categoryCheck(keywordsQuery) {
  const catRes = await fetch(
    `/wp-json/wp/v2/categories?search=${encodeURIComponent(keywordsQuery)}`
  );
  const categories = await catRes.json();
  if (categories.length === 0) return [];
  const catId = categories[0].id;
  const postRes = await fetch(
    `/wp-json/wp/v2/posts?categories=${catId}&per_page=5&_embed`
  );
  return postRes.json();
}

// 3. タグ検索(タグ名からタグIDを取得)
async function tagCheck(keywordsQuery) {
  const tagRes = await fetch(
    `/wp-json/wp/v2/tags?search=${encodeURIComponent(keywordsQuery)}`
  );
  const tags = await tagRes.json();
  if (tags.length === 0) return [];
  const tagId = tags[0].id;
  const postRes = await fetch(
    `/wp-json/wp/v2/posts?tags=${tagId}&per_page=5&_embed`
  );
  return postRes.json();
}

async function performSearch(keywordsQuery, isManualSearch = false) {
  // 検索文字が2文字未満ならキャンセル
  if (!keywordsQuery || (keywordsQuery.length < 2 && !isManualSearch)) {
    clearBtn.style.display = "none";
    searchResultsContainer.style.display = "none";
    searchResultsContainer.innerHTML = "";
    return;
  }

  const resultsUl = document.createElement("ul");
  resultsUl.id = "results-list";
  resultsUl.innerHTML = `
        <div style="text-align:center; padding:100px; width:350px;">
   <img src="/wp-content/themes/portnavi/img/icons/loading.gif" alt="Loading..." style="text-align:center; padding:10px; width:50px;"/>
</div>
  `;
  searchResultsContainer.style.display = "block";
  searchResultsContainer.innerHTML = "";
  searchResultsContainer.appendChild(resultsUl);
  clearBtn.style.display = "inline-block";
  isLoading = true;

  // // // クリアボタン表示/非表示
  if (keywordsInput.value.length === 0) {
    clearBtn.style.display = "none";
    return;
  } else if (keywordsInput.value.length >= 1) {
    clearBtn.style.display = "inline-block";
  }
  // 検索結果を取得
  // searchResultsContainer.innerHTML = "";
  // searchResultsContainer.style.display = "inline-block";

  // タイムアウト制御用
  let timeoutId;
  let timeoutTriggered = false;

  try {
    // 2〜4文字 → タイムアウト設定
    if (keywordsQuery.length >= 2 && keywordsQuery.length <= 4) {
      timeoutId = setTimeout(() => {
        timeoutTriggered = true;
        resultsUl.innerHTML = `
          <li style="text-align:center; padding:100px; width:350px;">
            検索結果が見当たりませんでした
          </li>
        `;
        isLoading = false;
      }, 5000);
    }

    // 並列で全部検索
    const [keywordPosts, categoryPosts, tagPosts] = await Promise.all([
      keywordsQueryCheck(keywordsQuery),
      categoryCheck(keywordsQuery),
      tagCheck(keywordsQuery),
    ]);

    if (timeoutTriggered) return; // すでにタイムアウト表示してたら処理しない
    if (timeoutId) clearTimeout(timeoutId);
    console.log("これで確認", keywordPosts, categoryPosts, tagPosts);

    // 3. 取得結果をまとめて重複除去
    const allPosts = [...keywordPosts, ...categoryPosts, ...tagPosts];

    const filteredPosts = Array.from(
      new Map(allPosts.map((post) => [post.id, post])).values()
    );
    console.log("重複除去後", filteredPosts);

    // ローディングを消して結果表示
    searchResultsContainer.innerHTML = "";

    if (filteredPosts.length === 0) {
      if (isManualSearch || keywordsQuery.length >= 5) {
        resultsUl.innerHTML = `
        <li style="text-align:center; padding:100px; width:350px;">検索結果が見当たりませんでした</li>
      `;
      } else {
        isLoading = true;
        searchResultsContainer.style.display = "block";
        resultsUl.innerHTML = `
      <div style="text-align:center; padding:100px; width:350px;">
   <img src="/wp-content/themes/portnavi/img/icons/loading.gif" alt="Loading..." style="text-align:center; padding:10px; width:50px;"/>
</div>
        `;
        setTimeout(() => {
          timeoutTriggered = true;
          resultsUl.innerHTML = `
          <li style="text-align:center; padding:100px; width:350px;">検索結果が見当たりませんでした</li>
        `;
          isLoading = false;
        }, 5000); // 5秒後
      }
    } else {
      searchResultsContainer.innerHTML = "";
      // searchResultsContainer.style.display = "inline-block";
      filteredPosts.forEach((post) => {
        const listItem = document.createElement("li");
        const postLink = document.createElement("a");
        const postTitle = document.createElement("h3");
        const postImg = document.createElement("img");
        const postText = document.createElement("p");

        postTitle.classList.add("result-title");
        postText.classList.add("result-text");
        postImg.classList.add("result-img");

        postLink.href = post.link;
        postTitle.textContent = post.title.rendered;

        // アイキャッチ画像
        if (post._embedded && post._embedded["wp:featuredmedia"]) {
          postImg.src = post._embedded["wp:featuredmedia"][0].source_url;
        } else {
          postImg.src = "https://via.placeholder.com/80";
        }
        postImg.alt = post.title.rendered;
        postLink.appendChild(postImg);

        // 抜粋50文字
        let excerpt = post.excerpt.rendered.replace(/(<([^>]+)>)/gi, "");
        postText.textContent =
          excerpt.length > 50 ? excerpt.substring(0, 50) + "…" : excerpt;

        postLink.appendChild(postTitle);
        postLink.appendChild(postText);

        listItem.appendChild(postLink);
        resultsUl.appendChild(listItem);
      });
    }
    searchResultsContainer.appendChild(resultsUl);
  } catch (error) {
    console.error("検索中にエラーが発生しました:", error);
    searchResultsContainer.innerHTML = "<li>エラーが発生しました</li>";
  } finally {
    isLoading = false;
  }
  return;
}

// 入力時のライブ検索
keywordsInput.addEventListener("input", async function () {
  await performSearch(this.value, false);
});

// 検索ボタン押下時
document
  .getElementById("header-keywords-submit")
  .addEventListener("click", function (e) {
    e.preventDefault();
    const query = keywordsInput.value.trim();
    if (!query) return;
    window.location.href = `/?s=${encodeURIComponent(query)}`;
  });

// Enter押下時
document
  .querySelector(".search_container")
  .addEventListener("submit", function (e) {
    e.preventDefault();
    const query = keywordsInput.value.trim();
    if (!query) return;
    window.location.href = `/?s=${encodeURIComponent(query)}`;
  });
