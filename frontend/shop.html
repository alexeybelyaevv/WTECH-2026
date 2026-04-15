<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>L — Shop</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
  <style>
    body { font-family: 'Montserrat', sans-serif; }
    .font-display { font-family: 'Montserrat', sans-serif; }
    .card { transition: box-shadow 0.2s ease, transform 0.2s ease; }
    .card:hover { box-shadow: 0 14px 40px rgba(15, 23, 42, 0.08); transform: translateY(-2px); }
  </style>
</head>
<body class="bg-white text-gray-900 antialiased">

  <nav class="flex items-center justify-between px-4 sm:px-6 lg:px-8 py-4 border-b border-gray-100 sticky top-0 bg-white/90 backdrop-blur z-50">
    <span class="text-2xl font-display font-bold tracking-tight">
      <a href="index.html">L</a>
    </span>
    <div class="flex items-center gap-3">
      <a href="login.html" title="Sign in">
        <button class="px-4 py-1.5 text-sm rounded border border-gray-300 hover:border-gray-700 transition-colors">Sign in</button>
      </a>
      <a href="register.html" title="Register">
        <button class="px-4 py-1.5 text-sm rounded bg-gray-900 text-white hover:bg-black transition-colors">Register</button>
      </a>
      <a href="cart.html" title="Cart">
        <button class="relative ml-1 p-1.5 rounded hover:bg-gray-100 transition-colors">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 9m12-9l2 9M9 21a1 1 0 100-2 1 1 0 000 2zm6 0a1 1 0 100-2 1 1 0 000 2z"/>
          </svg>
          <span id="cartCountBadge" class="hidden absolute -top-1 -right-1 bg-gray-900 text-white text-[10px] min-w-4 h-4 px-1 rounded-full flex items-center justify-center font-medium">0</span>
        </button>
      </a>
    </div>
  </nav>

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-6">
      <div>
        <h1 class="text-2xl font-display font-bold tracking-tight">PC Game Store</h1>
        <p class="text-sm text-gray-500 mt-1">Live catalog from Laravel API.</p>
      </div>
      <button id="openFilters" class="md:hidden inline-flex items-center gap-2 px-4 py-2 rounded-full border border-gray-200 text-sm font-medium bg-white shadow-sm hover:border-gray-400 transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h10M4 18h7"/>
        </svg>
        Filters
      </button>
    </div>

    <div class="lg:flex gap-6 relative">
      <div id="filtersOverlay" class="hidden fixed inset-0 bg-black/40 z-30 md:hidden"></div>

      <aside id="filtersPanel" class="hidden md:block fixed inset-y-0 left-0 z-40 w-80 max-w-[92%] bg-white shadow-2xl transform -translate-x-full transition-transform duration-200 md:static md:w-64 md:shrink-0 md:bg-transparent md:shadow-none md:transform-none md:translate-x-0">
        <div class="h-full overflow-y-auto pt-20 pb-8 px-5 md:pt-0 md:px-0">
          <button data-close-filters class="md:hidden absolute top-3 right-3 w-9 h-9 rounded-full bg-gray-900 text-white flex items-center justify-center hover:bg-black transition-colors" title="Close filters">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>

          <div class="border border-gray-200 rounded-2xl p-5 space-y-5">
            <div>
              <label for="searchInput" class="text-sm font-medium text-gray-800">Search</label>
              <div class="mt-3 flex items-center border border-gray-200 rounded-full px-4 py-2 gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 111 11a6 6 0 0116 0z"/>
                </svg>
                <input id="searchInput" type="text" placeholder="Search products" class="text-sm outline-none w-full placeholder-gray-400"/>
              </div>
            </div>

            <div class="border-t border-gray-100 pt-5">
              <p class="text-sm font-medium text-gray-800">Product type</p>
              <div class="mt-3 space-y-2">
                <label class="flex items-center gap-2.5 cursor-pointer">
                  <input type="radio" name="typeFilter" value="" checked class="w-4 h-4 accent-gray-900"/>
                  <span class="text-sm">All</span>
                </label>
                <label class="flex items-center gap-2.5 cursor-pointer">
                  <input type="radio" name="typeFilter" value="DIGITAL" class="w-4 h-4 accent-gray-900"/>
                  <span class="text-sm">Digital</span>
                </label>
                <label class="flex items-center gap-2.5 cursor-pointer">
                  <input type="radio" name="typeFilter" value="PHYSICAL" class="w-4 h-4 accent-gray-900"/>
                  <span class="text-sm">Physical</span>
                </label>
              </div>
            </div>

            <div class="border-t border-gray-100 pt-5">
              <p class="text-sm font-medium text-gray-800">Categories</p>
              <div id="categoryFilters" class="mt-3 space-y-2 text-sm text-gray-600"></div>
            </div>

            <div class="border-t border-gray-100 pt-5">
              <p class="text-sm font-medium text-gray-800">Platforms</p>
              <div id="platformFilters" class="mt-3 space-y-2 text-sm text-gray-600"></div>
            </div>
          </div>

          <div class="md:hidden mt-6">
            <button data-close-filters class="w-full py-2.5 rounded-lg bg-gray-900 text-white text-sm font-semibold hover:bg-black transition-colors">Apply filters</button>
          </div>
        </div>
      </aside>

      <div class="flex-1 min-w-0">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-3">
          <div class="text-sm text-gray-500">
            <span id="catalogSummary">Loading products...</span>
          </div>
          <div class="flex items-center gap-2 flex-wrap">
            <button data-sort="" class="sort-btn px-4 py-1.5 rounded-full border border-gray-900 bg-gray-900 text-white text-sm font-medium">Newest</button>
            <button data-sort="oldest" class="sort-btn px-4 py-1.5 rounded-full border border-gray-200 text-sm text-gray-600 hover:border-gray-400 transition-colors">Oldest</button>
            <button data-sort="price_asc" class="sort-btn px-4 py-1.5 rounded-full border border-gray-200 text-sm text-gray-600 hover:border-gray-400 transition-colors">Price ascending</button>
            <button data-sort="price_desc" class="sort-btn px-4 py-1.5 rounded-full border border-gray-200 text-sm text-gray-600 hover:border-gray-400 transition-colors">Price descending</button>
          </div>
        </div>

        <div id="catalogMessage" class="hidden mb-5 rounded-2xl border px-4 py-3 text-sm"></div>
        <div id="productGrid" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4"></div>
        <div id="catalogEmpty" class="hidden mt-8 border border-dashed border-gray-300 rounded-2xl p-10 text-center text-sm text-gray-500">
          No products match the current filters.
        </div>

        <div id="pagination" class="mt-10 flex items-center justify-center gap-1.5 flex-wrap"></div>
      </div>
    </div>
  </div>

  <script src="store_data.js"></script>
  <script src="/auth_ui.js"></script>
  <script>
    (() => {
      const store = window.StoreMvp;
      if (!store) return;

      const openBtn = document.getElementById('openFilters');
      const panel = document.getElementById('filtersPanel');
      const overlay = document.getElementById('filtersOverlay');
      const closers = document.querySelectorAll('[data-close-filters]');
      const mqDesktop = window.matchMedia('(min-width: 768px)');

      const open = () => {
        panel.classList.remove('hidden');
        overlay.classList.remove('hidden');
        requestAnimationFrame(() => panel.classList.remove('-translate-x-full'));
        document.body.classList.add('overflow-hidden');
      };

      const close = () => {
        panel.classList.add('-translate-x-full');
        document.body.classList.remove('overflow-hidden');

        window.setTimeout(() => {
          if (!mqDesktop.matches) {
            panel.classList.add('hidden');
            overlay.classList.add('hidden');
          }
        }, 200);
      };

      openBtn?.addEventListener('click', open);
      overlay?.addEventListener('click', close);
      closers.forEach((button) => button.addEventListener('click', close));

      mqDesktop.addEventListener('change', (event) => {
        if (event.matches) {
          panel.classList.remove('hidden', '-translate-x-full');
          overlay.classList.add('hidden');
          document.body.classList.remove('overflow-hidden');
        } else {
          panel.classList.add('hidden', '-translate-x-full');
        }
      });
    })();

    (() => {
      const store = window.StoreMvp;
      if (!store) return;

      const searchInput = document.getElementById('searchInput');
      const categoryFilters = document.getElementById('categoryFilters');
      const platformFilters = document.getElementById('platformFilters');
      const productGrid = document.getElementById('productGrid');
      const pagination = document.getElementById('pagination');
      const catalogSummary = document.getElementById('catalogSummary');
      const catalogMessage = document.getElementById('catalogMessage');
      const catalogEmpty = document.getElementById('catalogEmpty');
      const cartCountBadge = document.getElementById('cartCountBadge');
      const sortButtons = Array.from(document.querySelectorAll('.sort-btn'));
      const typeInputs = Array.from(document.querySelectorAll('input[name="typeFilter"]'));

      const state = {
        page: 1,
        q: '',
        sort: '',
        type: '',
        categories: [],
        platforms: [],
        ready: false,
      };

      let searchTimeout = null;

      const setMessage = (text, type = 'error') => {
        if (!text) {
          catalogMessage.textContent = '';
          catalogMessage.className = 'hidden mb-5 rounded-2xl border px-4 py-3 text-sm';
          return;
        }

        catalogMessage.textContent = text;
        catalogMessage.className =
          type === 'info'
            ? 'mb-5 rounded-2xl border border-sky-200 bg-sky-50 px-4 py-3 text-sm text-sky-800'
            : 'mb-5 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700';
      };

      const updateBadge = () => {
        const count = store.getCartCount(store.readCart());
        cartCountBadge.textContent = count > 99 ? '99+' : String(count);
        cartCountBadge.classList.toggle('hidden', count === 0);
      };

      const updateSortStyles = () => {
        sortButtons.forEach((button) => {
          const isActive = button.dataset.sort === state.sort;
          button.classList.toggle('bg-gray-900', isActive);
          button.classList.toggle('text-white', isActive);
          button.classList.toggle('border-gray-900', isActive);
          button.classList.toggle('border-gray-200', !isActive);
          button.classList.toggle('text-gray-600', !isActive);
        });
      };

      const renderCheckboxGroup = (container, items, type) => {
        if (items.length === 0) {
          container.innerHTML = '<p class="text-sm text-gray-400">No options available.</p>';
          return;
        }

        container.innerHTML = items.map((item) => `
          <label class="flex items-center gap-2.5 cursor-pointer">
            <input
              type="checkbox"
              class="w-4 h-4 accent-gray-900"
              data-filter-type="${type}"
              value="${store.escapeHtml(item.slug)}"
            />
            <span>${store.escapeHtml(item.name)}</span>
          </label>
        `).join('');
      };

      const collectFilterValues = (selector) => {
        return Array.from(document.querySelectorAll(selector))
          .filter((input) => input.checked)
          .map((input) => input.value)
          .filter(Boolean);
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
          productGrid.innerHTML = '';
          catalogEmpty.classList.remove('hidden');
          return;
        }

        catalogEmpty.classList.add('hidden');

        productGrid.innerHTML = products.map((product) => {
          const category = store.productCategoryLabel(product);
          const platform = store.productPlatformLabel(product);
          const isOutOfStock = Number(product.stock) <= 0;

          return `
            <article class="card border border-gray-200 rounded-2xl overflow-hidden flex flex-col bg-white">
              <a href="product_details.html?slug=${encodeURIComponent(product.slug)}" class="block">
                <div class="bg-gradient-to-br from-gray-50 to-gray-100 aspect-[4/3] overflow-hidden">
                  <img src="${store.productImage(product, product.name)}" alt="${store.escapeHtml(product.name)} cover" class="w-full h-full object-cover" loading="lazy"/>
                </div>
              </a>
              <div class="p-4 flex flex-col gap-2 flex-1">
                <div class="flex items-center justify-between gap-2">
                  <span class="px-2 py-0.5 rounded-full text-[11px] font-medium border border-gray-200 bg-gray-50 text-gray-700">${store.escapeHtml(category)}</span>
                  <span class="text-xs text-gray-500">${store.escapeHtml(product.type)}</span>
                </div>
                <a href="product_details.html?slug=${encodeURIComponent(product.slug)}" class="block">
                  <h3 class="text-sm font-semibold leading-snug text-gray-900">${store.escapeHtml(product.name)}</h3>
                </a>
                <p class="text-xs text-gray-400">${store.escapeHtml(platform)}</p>
                <p class="text-xs text-gray-500 leading-relaxed line-clamp-2">${store.escapeHtml(product.description || 'Instant digital delivery and verified activation.')}</p>
                <div class="mt-auto flex items-end justify-between gap-3 pt-2">
                  <div>
                    <p class="text-sm font-semibold text-gray-900">${store.formatMoney(product.price, product.currency)}</p>
                    <p class="text-xs text-gray-400">${isOutOfStock ? 'Out of stock' : `${product.stock} in stock`}</p>
                  </div>
                  <button
                    data-add-id="${product.id}"
                    class="px-3 py-2 rounded-lg text-xs font-semibold transition-colors ${
                      isOutOfStock
                        ? 'bg-gray-200 text-gray-500 cursor-not-allowed'
                        : 'bg-gray-900 text-white hover:bg-black'
                    }"
                    ${isOutOfStock ? 'disabled' : ''}
                  >
                    ${isOutOfStock ? 'Unavailable' : 'Add to cart'}
                  </button>
                </div>
              </div>
            </article>
          `;
        }).join('');
      };

      const loadReferences = async () => {
        const payload = await store.fetchCatalogProducts({ per_page: 48 });
        const products = Array.isArray(payload?.data) ? payload.data : [];

        const categories = Array.from(
          new Map(
            products.flatMap((product) => product.categories || []).map((category) => [category.slug, category]),
          ).values(),
        ).sort((left, right) => left.name.localeCompare(right.name));

        const platforms = Array.from(
          new Map(
            products.flatMap((product) => product.platforms || []).map((platform) => [platform.slug, platform]),
          ).values(),
        ).sort((left, right) => left.name.localeCompare(right.name));

        renderCheckboxGroup(categoryFilters, categories, 'category');
        renderCheckboxGroup(platformFilters, platforms, 'platform');
      };

      const loadProducts = async () => {
        setMessage('', 'info');
        catalogSummary.textContent = 'Loading products...';
        productGrid.innerHTML = '';
        catalogEmpty.classList.add('hidden');

        try {
          const payload = await store.fetchCatalogProducts({
            page: state.page,
            per_page: 12,
            q: state.q,
            sort: state.sort,
            type: state.type,
            categories: state.categories,
            platforms: state.platforms,
          });

          const products = Array.isArray(payload?.data) ? payload.data : [];
          const meta = payload?.meta || {};
          const from = meta.from || 0;
          const to = meta.to || 0;
          const total = meta.total || products.length;

          renderProducts(products);
          renderPagination(meta);

          catalogSummary.textContent = total > 0
            ? `Showing ${from}-${to} of ${total} product${total === 1 ? '' : 's'}`
            : 'No products found';
        } catch (error) {
          renderProducts([]);
          renderPagination(null);
          catalogSummary.textContent = 'Catalog unavailable';
          setMessage(store.firstError(error.payload, 'Unable to load products right now.'));
        }
      };

      const syncStateFromInputs = () => {
        state.categories = collectFilterValues('[data-filter-type="category"]');
        state.platforms = collectFilterValues('[data-filter-type="platform"]');
        state.type = typeInputs.find((input) => input.checked)?.value || '';
      };

      productGrid.addEventListener('click', (event) => {
        const button = event.target.closest('[data-add-id]');
        if (!button || button.disabled) return;

        store.addItem(button.dataset.addId, 1);
        updateBadge();

        const original = button.textContent;
        button.textContent = 'Added';
        button.disabled = true;

        window.setTimeout(() => {
          button.textContent = original;
          button.disabled = false;
        }, 700);
      });

      pagination.addEventListener('click', (event) => {
        const pageButton = event.target.closest('[data-page]');
        if (!pageButton) return;

        state.page = Number(pageButton.dataset.page || '1');
        loadProducts();
      });

      sortButtons.forEach((button) => {
        button.addEventListener('click', () => {
          state.sort = button.dataset.sort || '';
          state.page = 1;
          updateSortStyles();
          loadProducts();
        });
      });

      typeInputs.forEach((input) => {
        input.addEventListener('change', () => {
          syncStateFromInputs();
          state.page = 1;
          loadProducts();
        });
      });

      document.addEventListener('change', (event) => {
        const filter = event.target.closest('[data-filter-type]');
        if (!filter) return;

        syncStateFromInputs();
        state.page = 1;
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

      window.addEventListener('cart:updated', updateBadge);

      const boot = async () => {
        updateBadge();
        updateSortStyles();

        try {
          await loadReferences();
        } catch (_error) {
          categoryFilters.innerHTML = '<p class="text-sm text-gray-400">Filters unavailable.</p>';
          platformFilters.innerHTML = '<p class="text-sm text-gray-400">Filters unavailable.</p>';
        }

        syncStateFromInputs();
        state.ready = true;
        loadProducts();
      };

      boot();
    })();
  </script>

</body>
</html>
