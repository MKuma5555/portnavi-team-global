const searchResultsContainer = document.getElementById(
  "keywords-search-results"
);
const clearBtn = document.getElementById("clear-button");
const searchInput = document.getElementById("live-search");

let searchTimeout = null; // デバウンス用タイマー

// クリアボタン表示/非表示
function toggleClearButton(value) {
  clearBtn.style.display = value.length > 0 ? "inline-block" : "none";
}

// クリアボタン機能
clearBtn.addEventListener("click", function () {
  searchInput.value = "";
  searchResultsContainer.innerHTML = "";
  toggleClearButton("");
});

// 検索結果レンダリング
function renderResults(data) {
  searchResultsContainer.innerHTML = ""; // 前回の結果をクリア

  const resultsUl = document.createElement("ul");
  resultsUl.id = "results-list";

  if (data.length) {
    // フロント側でも重複チェック
    const seenLinks = new Set();

    data.forEach((item) => {
      if (!item.title || seenLinks.has(item.link)) return;
      seenLinks.add(item.link);

      const listItem = document.createElement("li");
      listItem.classList.add("search-result-item");

      const postLink = document.createElement("a");
      postLink.href = item.link;

      const postTitle = document.createElement("h3");
      postTitle.classList.add("result-title");
      postTitle.textContent = item.title;

      const postText = document.createElement("p");
      postText.classList.add("result-text");

      if (item.overview_raw) {
        let overview = item.overview_raw.replace(/(<([^>]+)>)/gi, "");
        postText.textContent =
          overview.length > 50 ? overview.substring(0, 50) + "…" : overview;
      } else if (item.excerpt) {
        postText.textContent =
          item.excerpt.length > 50
            ? item.excerpt.substring(0, 50) + "…"
            : item.excerpt;
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
    searchResultsContainer.classList.add("is-active");
  } else {
    const listItem = document.createElement("li");
    listItem.classList.add("search-result-item");
    listItem.innerHTML = `<h3 class="result-title">該当なし</h3>`;
    resultsUl.appendChild(listItem);
    searchResultsContainer.appendChild(resultsUl);
    searchResultsContainer.classList.add("is-active");
  }
}

// ライブ検索
document.addEventListener("DOMContentLoaded", function () {
  toggleClearButton(searchInput.value);

  searchInput.addEventListener("input", function () {
    const keyword = this.value.trim();
    toggleClearButton(keyword);

    if (keyword.length < 2) {
      searchResultsContainer.classList.remove("is-active");
      searchResultsContainer.innerHTML = "";
      return;
    }

    // デバウンス: 300ms 入力が止まったら検索
    if (searchTimeout) clearTimeout(searchTimeout);
    searchTimeout = setTimeout(async () => {
      const formData = new FormData();
      formData.append("action", "live_tax_search");
      formData.append("keyword", keyword);

      try {
        const response = await fetch(live_tax_vars.ajax_url, {
          method: "POST",
          body: formData,
        });
        if (!response.ok)
          throw new Error(`HTTP error! status: ${response.status}`);

        const data = await response.json();
        console.log("Response:", data);

        renderResults(data);
      } catch (error) {
        console.error("Error fetching search results:", error);
        searchResultsContainer.innerHTML = `<li class="search-result-item">検索中にエラーが発生しました</li>`;
      }
    }, 300);
  });
});
