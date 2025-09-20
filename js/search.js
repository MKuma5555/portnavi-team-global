// 2文字未満 → 予測検索では何も出さない
// 2〜4文字 → 結果があれば出す、なければ非表示
// 5文字以上 → 結果がなければ「検索結果が見当たりません」と表示
// 検索ボタンを押した場合 → 必ず「検索結果が見当たりませんでした」を表示
const searchResultsContainer = document.getElementById(
  "keywords-search-results"
);

async function performSearch(keywordsQuery, isManualSearch) {
  searchResultsContainer.innerHTML = "";
  searchResultsContainer.style.display = "inline-block";
  const resultsUl = document.createElement("ul");
  resultsUl.id = "results-list";

  // クリアボタン表示/非表示
  const clearBtn = document.getElementById("clear-button");
  if (keywordsQuery.length === 0) {
    clearBtn.style.display = "none";
  } else if (keywordsQuery.length >= 1) {
    clearBtn.style.display = "inline-block";
  }

  // クリアボタン機能 (クリックで入力欄と結果をクリア)
  clearBtn.addEventListener("click", function () {
    document.getElementById("live-search").value = "";
    searchResultsContainer.style.display = "none";
    searchResultsContainer.innerHTML = "";
    clearBtn.style.display = "none";
  });

  // 検索文字が2文字未満なら検索結果を消す
  if (!keywordsQuery || (keywordsQuery.length < 2 && !isManualSearch)) {
    searchResultsContainer.style.display = "none";
    searchResultsContainer.innerHTML = "";
    return [];
  }

  // ローディング表示

  //   resultsUl.innerHTML = "<li>ローディング中...</li>";

  // 検索処理///
  // 1. キーワード検索
  const keywordRes = await fetch(
    `/wp-json/wp/v2/posts?search=${encodeURIComponent(
      keywordsQuery
    )}&per_page=5&_embed`
  );
  const keywordPosts = await keywordRes.json();

  // 2. カテゴリー名からカテゴリーIDを取得
  const catRes = await fetch(
    `/wp-json/wp/v2/categories?search=${encodeURIComponent(keywordsQuery)}`
  );
  const categoryRes = await catRes.json();

  let categoryPosts = [];
  if (categoryRes.length > 0) {
    const categoryId = categoryRes[0].id;
    const categoryPostRes = await fetch(
      `/wp-json/wp/v2/posts?categories=${categoryId}&per_page=5&_embed`
    );
    categoryPosts = await categoryPostRes.json();
  }

  // 3. タグ検索(タグ名からタグIDを取得)
  const tagRes = await fetch(
    `/wp-json/wp/v2/tags?search=${encodeURIComponent(keywordsQuery)}`
  );
  const tags = await tagRes.json();
  let tagPosts = [];

  if (tags.length > 0) {
    const tagId = tags[0].id;
    const tagPostRes = await fetch(
      `/wp-json/wp/v2/posts?tags=${tagId}&per_page=5&_embed`
    );
    tagPosts = await tagPostRes.json();
  }
  // 結果表示処理///
  // 両方の結果をマージ & 重複除去
  const allPosts = [...keywordPosts, ...categoryPosts, ...tagPosts];

  const filteredPosts = Array.from(
    new Map(allPosts.map((post) => [post.id, post])).values()
  );

  // まず既存のULをクリア
  resultsUl.innerHTML = "";

  // 投稿がなければ非表示にして終了
  if (filteredPosts.length === 0) {
    if (isManualSearch) {
      resultsUl.innerHTML = "<li>検索結果が見当たりませんでした</li>";
      //   searchResultsContainer.appendChild(resultsUl);
    } else if (keywordsQuery.length >= 5) {
      resultsUl.innerHTML = "<li>検索結果が見当たりません</li>";
      //   searchResultsContainer.appendChild(resultsUl);
    } else {
      // それ以外（まだ文字数少ない）→ 何も出さない
      searchResultsContainer.style.display = "none";
      return;
    }
  } else {
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
  //   return filteredPosts;
}

// 「入力時」の予測表示
document
  .getElementById("live-search")
  .addEventListener("input", async function () {
    const keywordsQuery = this.value;
    await performSearch(keywordsQuery, (isManualSearch = false)); // 入力されている文字列で検索
  });

document
  .getElementById("header-keywords-submit")
  .addEventListener("click", function (e) {
    e.preventDefault();
    const query = document.getElementById("live-search").value;
    if (query.trim() === "") return;
    // 固定ページにクエリを付けて遷移
    window.location.href = `/search-result?query=${encodeURIComponent(query)}`;
  });

// Enter押下時
document
  .querySelector(".search_container")
  .addEventListener("submit", function (e) {
    e.preventDefault();
    const query = document.getElementById("live-search").value;
    if (query.trim() === "") return;
    window.location.href = `/search-result?query=${encodeURIComponent(query)}`;
  });
