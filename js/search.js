// // 2文字未満 → 予測検索では何も出さない
// // 2〜4文字 → 結果があれば出す、なければ非表示
// // 5文字以上 → 結果がなければ「検索結果が見当たりません」と表示
// // 検索ボタンを押した場合 → 必ず「検索結果が見当たりませんでした」を表示
// let isLoading = false;

// Search & filter プラグインの Ajax ライブ検索を利用して、カテゴリー・タグも検索対象にする（オリジナルのtaxonomyも対象に）
const searchResultsContainer = document.getElementById(
  "keywords-search-results"
);
const clearBtn = document.getElementById("clear-button");
const searchInput = document.getElementById("live-search");

// クリアボタン表示/非表示
function toggleClearButton(value) {
  if (value.length > 0) {
    clearBtn.style.display = "inline-block";
  } else {
    clearBtn.style.display = "none";
  }
}

// クリアボタン機能 (クリックで入力欄と結果をクリア)
clearBtn.addEventListener("click", function () {
  searchInput.value = "";
  searchResultsContainer.innerHTML = "";
  toggleClearButton("");
  // isLoading = false;
});

// キーワード入力時のライブ検索
document.addEventListener("DOMContentLoaded", function () {
  // 初期表示時のクリアボタン表示/非表示
  toggleClearButton(searchInput.value);

  // ライブ検索
  searchInput.addEventListener("input", async function () {
    const keyword = this.value.trim();

    toggleClearButton(keyword);

    // ２文字未満ならライブ検索結果は何も表示しない
    if (keyword.length < 2) {
      searchResultsContainer.innerHTML = "";
      return;
    }

    // AJAXリクエスト用のFormDataを作成
    const formData = new FormData();
    formData.append("action", "live_tax_search");
    formData.append("keyword", keyword);

    //AJAXリクエストを送信
    try {
      const response = await fetch(live_tax_vars.ajax_url, {
        method: "POST",
        body: formData,
      });

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }

      const data = await response.json();

      // 前回の結果をクリア
      searchResultsContainer.innerHTML = "";

      // ライブ検索結果の表示用ULリストを作成
      const resultsUl = document.createElement("ul");
      resultsUl.id = "results-list";

      if (data.length) {
        data.forEach((item) => {
          if (!item.title) return;

          const listItem = document.createElement("li");
          listItem.classList.add("search-result-item");

          const postLink = document.createElement("a");
          postLink.href = item.link;

          const postTitle = document.createElement("h3");
          postTitle.classList.add("result-title");
          postTitle.textContent = item.title;

          const postText = document.createElement("p");
          postText.classList.add("result-text");
          if (item.excerpt) {
            let excerpt = item.excerpt.replace(/(<([^>]+)>)/gi, "");
            postText.textContent =
              excerpt.length > 50 ? excerpt.substring(0, 50) + "…" : excerpt;
          }

          if (item.thumbnail) {
            const postImg = document.createElement("img");
            postImg.classList.add("result-img");
            postImg.src = item.thumbnail;
            postImg.alt = item.title;
            postLink.appendChild(postImg);
          }

          postLink.appendChild(postTitle);
          postLink.appendChild(postText);
          listItem.appendChild(postLink);
          resultsUl.appendChild(listItem);
        });

        searchResultsContainer.appendChild(resultsUl);
        searchResultsContainer.style.display = "block";
      } else {
        const listItem = document.createElement("li");
        listItem.classList.add("search-result-item");
        listItem.innerHTML = `<h3 class="result-title">該当なし</h3>`;
        resultsUl.appendChild(listItem);
        searchResultsContainer.appendChild(resultsUl);
        searchResultsContainer.style.display = "block";
      }
    } catch (error) {
      console.error("Error fetching search results:", error);
      searchResultsContainer.innerHTML = `<li class="search-result-item">検索中にエラーが発生しました</li>`;
    }
  });
});
