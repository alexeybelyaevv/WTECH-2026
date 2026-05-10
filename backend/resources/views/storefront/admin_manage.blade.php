<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>L — Manage Products</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
  <style>
    body { font-family: 'Montserrat', sans-serif; }
    .font-display { font-family: 'Montserrat', sans-serif; }
  </style>
</head>
<body class="bg-white text-gray-900 antialiased">

  <nav class="flex items-center justify-between px-8 py-4 border-b border-gray-100 sticky top-0 bg-white/90 backdrop-blur z-50">
    <span class="text-2xl font-display font-bold tracking-tight">
      <a href="index.html">L</a>
    </span>
    <div class="flex items-center gap-3">
      <a href="shop.html">
        <button class="px-4 py-1.5 text-sm rounded border border-gray-300 hover:border-gray-700 transition-colors">Storefront</button>
      </a>
      <a href="admin_manage.html">
        <button class="px-4 py-1.5 text-sm rounded bg-gray-900 text-white hover:bg-black transition-colors">Products</button>
      </a>
      <a href="admin_new.html">
        <button class="px-4 py-1.5 text-sm rounded border border-gray-300 hover:border-gray-700 transition-colors">New product</button>
      </a>
    </div>
  </nav>

  <div class="max-w-6xl mx-auto px-6 py-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
      <div>
        <h1 class="text-2xl font-bold">Product management</h1>
        <p class="text-sm text-gray-500 mt-1">Live admin list from `/api/admin/products`.</p>
      </div>
      <a href="admin_new.html" class="inline-flex items-center justify-center px-5 py-3 rounded-xl bg-gray-900 text-white text-sm font-semibold hover:bg-black transition-colors">
        Create product
      </a>
    </div>

    <div id="adminMessage" class="hidden mb-5 rounded-2xl border px-4 py-3 text-sm"></div>

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 mb-6">
      <div class="flex items-center border border-gray-200 rounded-full px-4 py-2 gap-2 w-full md:w-80">
        <input id="searchInput" type="text" placeholder="Search by name, slug or description" class="text-sm outline-none w-full placeholder-gray-400"/>
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 111 11a6 6 0 0116 0z"/>
        </svg>
      </div>
      <p id="adminSummary" class="text-sm text-gray-500">Loading products...</p>
    </div>

    <div class="border border-gray-200 rounded-3xl overflow-hidden">
      <div id="productList" class="divide-y divide-gray-100"></div>
    </div>

    <div id="adminEmpty" class="hidden mt-6 border border-dashed border-gray-300 rounded-2xl p-10 text-center text-sm text-gray-500">
      No products found.
    </div>

    <div id="pagination" class="flex items-center justify-center gap-1.5 py-10 flex-wrap"></div>
  </div>

  <script src="store_data.js"></script>
  <script>
    (() => {
      const store = window.StoreMvp;
      if (!store) return;

      const searchInput = document.getElementById('searchInput');
      const adminSummary = document.getElementById('adminSummary');
      const adminMessage = document.getElementById('adminMessage');
      const productList = document.getElementById('productList');
      const adminEmpty = document.getElementById('adminEmpty');
      const pagination = document.getElementById('pagination');

      const state = {
        page: 1,
        q: '',
      };

      let searchTimeout = null;

      const setMessage = (text, type = 'error') => {
        if (!text) {
          adminMessage.textContent = '';
          adminMessage.className = 'hidden mb-5 rounded-2xl border px-4 py-3 text-sm';
          return;
        }

        adminMessage.textContent = text;
        adminMessage.className =
          type === 'info'
            ? 'mb-5 rounded-2xl border border-sky-200 bg-sky-50 px-4 py-3 text-sm text-sky-800'
            : 'mb-5 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700';
      };

      const renderPagination = (meta) => {
        const currentPage = Number(meta?.current_page || 1);
        const lastPage = Number(meta?.last_page || 1);

        if (lastPage <= 1) {
          pagination.innerHTML = '';
          return;
        }

        const pages = [];
        const start = Math.max(1, currentPage - 2);
        const end = Math.min(lastPage, currentPage + 2);

        if (start > 1) pages.push(1);
        if (start > 2) pages.push('ellipsis-left');
        for (let page = start; page <= end; page += 1) pages.push(page);
        if (end < lastPage - 1) pages.push('ellipsis-right');
        if (end < lastPage) pages.push(lastPage);

        pagination.innerHTML = pages.map((item) => {
          if (String(item).startsWith('ellipsis')) {
            return '<span class="w-8 h-8 flex items-center justify-center text-gray-400 text-sm">…</span>';
          }

          const page = Number(item);
          const isActive = page === currentPage;

          return `
            <button
              type="button"
              data-page="${page}"
              class="w-9 h-9 rounded-full text-sm flex items-center justify-center transition-colors ${
                isActive
                  ? 'bg-gray-900 text-white font-semibold'
                  : 'text-gray-600 hover:bg-gray-100'
              }"
            >
              ${page}
            </button>
          `;
        }).join('');
      };

      const renderProducts = (products) => {
        if (!Array.isArray(products) || products.length === 0) {
          productList.innerHTML = '';
          adminEmpty.classList.remove('hidden');
          return;
        }

        adminEmpty.classList.add('hidden');

        productList.innerHTML = products.map((product) => `
          <article class="p-5 flex flex-col md:flex-row md:items-center gap-4">
            <div class="w-28 h-24 rounded-2xl bg-gray-100 overflow-hidden shrink-0">
              <img src="${store.productImage(product, product.name)}" alt="${store.escapeHtml(product.name)} cover" class="w-full h-full object-cover" loading="lazy"/>
            </div>
            <div class="flex-1 min-w-0">
              <div class="flex flex-wrap items-center gap-2">
                <p class="text-base font-semibold">${store.escapeHtml(product.name)}</p>
                <span class="px-2 py-0.5 rounded-full text-[11px] font-medium border ${product.is_active ? 'border-emerald-200 bg-emerald-50 text-emerald-700' : 'border-gray-200 bg-gray-50 text-gray-500'}">
                  ${product.is_active ? 'Active' : 'Hidden'}
                </span>
                <span class="px-2 py-0.5 rounded-full text-[11px] font-medium border border-sky-200 bg-sky-50 text-sky-700">
                  ${store.escapeHtml(product.type)}
                </span>
              </div>
              <p class="text-xs text-gray-400 mt-1">${store.escapeHtml(product.slug)}</p>
              <p class="text-sm text-gray-500 mt-2 line-clamp-2">${store.escapeHtml(product.description || 'No description provided.')}</p>
              <div class="flex flex-wrap gap-2 mt-3 text-xs text-gray-500">
                <span>Categories: ${store.escapeHtml((product.categories || []).map((item) => item.name).join(', ') || 'None')}</span>
                <span>Platforms: ${store.escapeHtml((product.platforms || []).map((item) => item.name).join(', ') || 'None')}</span>
              </div>
            </div>
            <div class="shrink-0 md:text-right">
              <p class="text-sm font-semibold">${store.formatMoney(product.price, product.currency)}</p>
              <p class="text-xs text-gray-400 mt-1">Stock: ${product.stock}</p>
              <div class="flex items-center gap-2 mt-4 md:justify-end">
                <a href="admin_edit.html?id=${product.id}" class="px-3 py-2 rounded-lg border border-gray-200 text-sm font-medium text-gray-700 hover:border-gray-400 transition-colors">Edit</a>
                <button data-delete-id="${product.id}" class="px-3 py-2 rounded-lg bg-gray-900 text-white text-sm font-medium hover:bg-black transition-colors">Delete</button>
              </div>
            </div>
          </article>
        `).join('');
      };

      const loadProducts = async () => {
        setMessage('Loading products...', 'info');

        try {
          const payload = await store.fetchAdminProducts({
            page: state.page,
            q: state.q,
          });

          const products = Array.isArray(payload?.data) ? payload.data : [];
          const meta = payload?.meta || {};
          const from = meta.from || 0;
          const to = meta.to || 0;
          const total = meta.total || products.length;

          renderProducts(products);
          renderPagination(meta);
          adminSummary.textContent = total > 0
            ? `Showing ${from}-${to} of ${total} product${total === 1 ? '' : 's'}`
            : 'No products found';
          setMessage('');
        } catch (error) {
          renderProducts([]);
          renderPagination(null);
          adminSummary.textContent = 'Admin list unavailable';
          setMessage(store.firstError(error.payload, 'Unable to load admin products right now.'));
        }
      };

      pagination.addEventListener('click', (event) => {
        const pageButton = event.target.closest('[data-page]');
        if (!pageButton) return;

        state.page = Number(pageButton.dataset.page || '1');
        loadProducts();
      });

      searchInput.addEventListener('input', () => {
        window.clearTimeout(searchTimeout);
        searchTimeout = window.setTimeout(() => {
          state.q = searchInput.value.trim();
          state.page = 1;
          loadProducts();
        }, 250);
      });

      productList.addEventListener('click', async (event) => {
        const deleteButton = event.target.closest('[data-delete-id]');
        if (!deleteButton) return;

        const productId = deleteButton.dataset.deleteId;
        if (!window.confirm('Delete this product? This cannot be undone.')) {
          return;
        }

        deleteButton.disabled = true;
        deleteButton.textContent = 'Deleting...';

        try {
          await store.deleteAdminProduct(productId);
          await loadProducts();
        } catch (error) {
          setMessage(store.firstError(error.payload, 'Unable to delete product.'));
          deleteButton.disabled = false;
          deleteButton.textContent = 'Delete';
        }
      });

      loadProducts();
    })();
  </script>

</body>
</html>
