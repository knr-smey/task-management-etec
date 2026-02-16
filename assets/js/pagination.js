// public/assets/js/pagination.js
window.Paginator = function (options) {
  const {
    itemsSelector,
    searchInputSelector,
    paginationSelector,
    itemsPerPage = 10,
    noResultSelector = null,
    onRender = null
  } = options;

  let currentPage = 1;

  const $items = $(itemsSelector);
  const $search = searchInputSelector ? $(searchInputSelector) : null;
  const $pagination = $(paginationSelector);
  const $noResult = noResultSelector ? $(noResultSelector) : null;

  // ✅ detect what display type should be used (tr needs table-row)
  const displayType =
    $items.first().is("tr") ? "table-row" :
    ($items.first().css("display") === "flex" ? "flex" : "block");

  function getFilteredItems() {
    if (!$search || !$search.length) return $items;

    const q = ($search.val() || "").toLowerCase().trim();
    if (!q) return $items;

    return $items.filter(function () {
      return $(this).text().toLowerCase().includes(q);
    });
  }

  function renderPagination(totalPages) {
    $pagination.empty();
    if (totalPages <= 1) return;

    $pagination.addClass("items-center");

    const ellipsis = `<span class="px-1 py-0.5 text-xs text-gray-500">...</span>`;

    function pageBtn(page, label = page) {
      const isActive = page === currentPage;
      const $btn = $(`
        <button type="button"
          class="px-2.5 py-0.5 rounded border text-xs leading-5 ${
            isActive ? "bg-green-600 text-white border-green-600" : "bg-white hover:bg-gray-100"
          }">
          ${label}
        </button>
      `);

      $btn.on("click", function () {
        currentPage = page;
        render();
      });

      return $btn;
    }

    const $prev = $(`<button type="button" class="px-2.5 py-0.5 rounded border text-xs leading-5 bg-white hover:bg-gray-100">Prev</button>`);
    if (currentPage === 1) $prev.prop("disabled", true).addClass("opacity-50 cursor-not-allowed");
    else $prev.on("click", function () { currentPage--; render(); });

    const $next = $(`<button type="button" class="px-2.5 py-0.5 rounded border text-xs leading-5 bg-white hover:bg-gray-100">Next</button>`);
    if (currentPage === totalPages) $next.prop("disabled", true).addClass("opacity-50 cursor-not-allowed");
    else $next.on("click", function () { currentPage++; render(); });

    $pagination.append($prev);

    const pages = new Set([1, totalPages]);
    const windowSize = 2;
    for (let p = currentPage - windowSize; p <= currentPage + windowSize; p++) {
      if (p >= 1 && p <= totalPages) pages.add(p);
    }

    const sorted = Array.from(pages).sort((a, b) => a - b);

    for (let i = 0; i < sorted.length; i++) {
      const p = sorted[i];
      const prevP = sorted[i - 1];
      if (i > 0 && p - prevP > 1) $pagination.append($(ellipsis));
      $pagination.append(pageBtn(p));
    }

    $pagination.append($next);
  }

  function render() {
    const $filtered = getFilteredItems();

    // hide all rows/items
    $items.css("display", "none");

    // hide not found by default
    if ($noResult && $noResult.length) $noResult.css("display", "none");

    // ✅ no result
    if ($filtered.length === 0) {
      $pagination.empty();
      if ($noResult && $noResult.length) $noResult.css("display", "table-row");

      if (typeof onRender === "function") onRender({ currentPage: 1, totalPages: 1, filteredCount: 0 });
      return;
    }

    const totalPages = Math.ceil($filtered.length / itemsPerPage) || 1;
    if (currentPage > totalPages) currentPage = 1;

    const start = (currentPage - 1) * itemsPerPage;
    const end = start + itemsPerPage;

    $filtered.slice(start, end).css("display", displayType);

    renderPagination(totalPages);

    if (typeof onRender === "function") onRender({ currentPage, totalPages, filteredCount: $filtered.length });
  }

  if ($search && $search.length) {
    $search.on("input", function () {
      currentPage = 1;
      render();
    });
  }

  render();

  return {
    render,
    setPage: (p) => { currentPage = p; render(); }
  };
};
