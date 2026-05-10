<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>L — Product</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
  <style>
    body { font-family: 'Montserrat', sans-serif; }
    .font-display { font-family: 'Montserrat', sans-serif; }
    select { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='none' viewBox='0 0 24 24' stroke='%236b7280' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 12px center; }
    .thumb-active { border-color: #111827; box-shadow: 0 0 0 1px #111827 inset; }
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

  <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div id="detailMessage" class="hidden mb-6 rounded-2xl border px-4 py-3 text-sm"></div>

    <section id="detailContent" class="hidden">
      <div class="flex flex-col lg:flex-row gap-8 lg:gap-12 items-start">
        <div class="w-full lg:w-[420px] shrink-0">
          <div class="w-full aspect-square bg-gray-100 rounded-3xl overflow-hidden border border-gray-200">
            <img id="detailImage" src="" alt="Product cover" class="w-full h-full object-cover"/>
          </div>
          <div id="detailGallery" class="grid grid-cols-4 gap-3 mt-4"></div>
        </div>

        <div class="flex-1 w-full pt-1 space-y-5 text-center lg:text-left">
          <div class="space-y-2">
            <div class="flex flex-wrap gap-2 justify-center lg:justify-start">
              <span id="detailCategory" class="inline-flex px-3 py-1 rounded-full bg-gray-100 text-gray-700 text-xs font-medium border border-gray-200">Category</span>
              <span id="detailType" class="inline-flex px-3 py-1 rounded-full bg-sky-50 text-sky-700 text-xs font-medium border border-sky-200">Type</span>
            </div>
            <h1 id="detailName" class="text-3xl font-bold">Product Name</h1>
            <p id="detailDescription" class="text-sm text-gray-500 max-w-2xl mx-auto lg:mx-0">Product description.</p>
          </div>

          <div>
            <span id="detailPrice" class="text-5xl font-bold tracking-tight">€0.00</span>
            <p id="detailStock" class="mt-2 text-sm text-gray-500">0 in stock</p>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1.5">Platform</label>
              <select id="platformSelect" class="w-full px-3 py-2.5 rounded-lg border border-gray-200 text-sm text-gray-600 focus:outline-none focus:border-gray-400 transition-colors pr-8"></select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1.5">Delivery</label>
              <select id="deliverySelect" class="w-full px-3 py-2.5 rounded-lg border border-gray-200 text-sm text-gray-600 focus:outline-none focus:border-gray-400 transition-colors pr-8"></select>
            </div>
          </div>

          <div class="flex flex-col sm:flex-row sm:items-center gap-3">
            <button id="addToCartButton" class="w-full sm:w-auto sm:px-10 py-3.5 bg-gray-900 text-white text-sm font-semibold rounded-xl hover:bg-black transition-colors">
              Add to cart
            </button>
            <a href="shop.html" class="w-full sm:w-auto inline-flex items-center justify-center sm:px-8 py-3.5 rounded-xl border border-gray-200 text-sm font-semibold text-gray-700 hover:border-gray-400 transition-colors">
              Back to shop
            </a>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="border border-gray-200 rounded-2xl p-4">
              <p class="text-xs uppercase tracking-widest text-gray-400">Platforms</p>
              <p id="detailPlatformsSummary" class="mt-2 text-sm font-semibold text-gray-800"></p>
            </div>
            <div class="border border-gray-200 rounded-2xl p-4">
              <p class="text-xs uppercase tracking-widest text-gray-400">Categories</p>
              <p id="detailCategoriesSummary" class="mt-2 text-sm font-semibold text-gray-800"></p>
            </div>
            <div class="border border-gray-200 rounded-2xl p-4">
              <p class="text-xs uppercase tracking-widest text-gray-400">Currency</p>
              <p id="detailCurrencySummary" class="mt-2 text-sm font-semibold text-gray-800"></p>
            </div>
          </div>

          <div class="border border-gray-200 rounded-2xl overflow-hidden">
            <div class="px-4 py-3.5 border-b border-gray-100">
              <span class="text-sm font-semibold">How delivery works</span>
            </div>
            <div class="px-4 py-4">
              <p id="deliveryHint" class="text-sm text-gray-500 leading-relaxed"></p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section id="notFoundState" class="hidden max-w-2xl mx-auto text-center py-20">
      <h1 class="text-3xl font-bold">Product not found</h1>
      <p class="mt-3 text-sm text-gray-500">The product link is invalid or the item is no longer available in the catalog.</p>
      <a href="shop.html" class="inline-flex mt-6 px-6 py-3 rounded-xl bg-gray-900 text-white text-sm font-semibold hover:bg-black transition-colors">Back to shop</a>
    </section>

    <section class="mt-14">
      <h2 class="text-xl font-bold mb-6">Latest reviews</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
        <div class="border border-gray-200 rounded-xl p-5">
          <p class="text-sm font-bold mb-1">Fast digital delivery</p>
          <p class="text-sm text-gray-500 mb-4">Activation key was delivered in under one minute and worked instantly.</p>
          <p class="text-xs text-gray-400">March 2026</p>
        </div>
        <div class="border border-gray-200 rounded-xl p-5">
          <p class="text-sm font-bold mb-1">Good stock and clean checkout</p>
          <p class="text-sm text-gray-500 mb-4">The flow was smooth, the payment options were clear, and confirmation arrived immediately.</p>
          <p class="text-xs text-gray-400">March 2026</p>
        </div>
        <div class="border border-gray-200 rounded-xl p-5">
          <p class="text-sm font-bold mb-1">Will buy again</p>
          <p class="text-sm text-gray-500 mb-4">Reliable store, fair pricing, and instant access to the purchased product.</p>
          <p class="text-xs text-gray-400">March 2026</p>
        </div>
      </div>
    </section>
  </main>

  <script src="store_data.js"></script>
  <script src="/auth_ui.js"></script>
  <script>
    (() => {
      const store = window.StoreMvp;
      if (!store) return;

      const params = new URLSearchParams(window.location.search);
      const slug = params.get('slug');

      const detailContent = document.getElementById('detailContent');
      const notFoundState = document.getElementById('notFoundState');
      const detailMessage = document.getElementById('detailMessage');
      const detailName = document.getElementById('detailName');
      const detailCategory = document.getElementById('detailCategory');
      const detailType = document.getElementById('detailType');
      const detailDescription = document.getElementById('detailDescription');
      const detailPrice = document.getElementById('detailPrice');
      const detailStock = document.getElementById('detailStock');
      const detailImage = document.getElementById('detailImage');
      const detailGallery = document.getElementById('detailGallery');
      const platformSelect = document.getElementById('platformSelect');
      const deliverySelect = document.getElementById('deliverySelect');
      const addToCartButton = document.getElementById('addToCartButton');
      const detailPlatformsSummary = document.getElementById('detailPlatformsSummary');
      const detailCategoriesSummary = document.getElementById('detailCategoriesSummary');
      const detailCurrencySummary = document.getElementById('detailCurrencySummary');
      const deliveryHint = document.getElementById('deliveryHint');
      const cartCountBadge = document.getElementById('cartCountBadge');

      let currentProduct = null;
      let currentImages = [];
      let activeImageIndex = 0;

      const setMessage = (text, type = 'error') => {
        if (!text) {
          detailMessage.textContent = '';
          detailMessage.className = 'hidden mb-6 rounded-2xl border px-4 py-3 text-sm';
          return;
        }

        detailMessage.textContent = text;
        detailMessage.className =
          type === 'info'
            ? 'mb-6 rounded-2xl border border-sky-200 bg-sky-50 px-4 py-3 text-sm text-sky-800'
            : 'mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700';
      };

      const updateBadge = () => {
        const count = store.getCartCount(store.readCart());
        cartCountBadge.textContent = count > 99 ? '99+' : String(count);
        cartCountBadge.classList.toggle('hidden', count === 0);
      };

      const renderMainImage = () => {
        const image = currentImages[activeImageIndex];
        const src = image?.url || image?.path || store.productImage(currentProduct, currentProduct?.name || 'Product');
        detailImage.src = src;
        detailImage.alt = image?.alt || `${currentProduct?.name || 'Product'} cover`;
      };

      const renderGallery = () => {
        if (currentImages.length === 0) {
          detailGallery.innerHTML = `
            <button type="button" class="thumb-active aspect-square rounded-2xl border border-gray-200 overflow-hidden">
              <img src="${store.productImage(currentProduct, currentProduct?.name || 'Product')}" alt="${store.escapeHtml(currentProduct?.name || 'Product')}" class="w-full h-full object-cover"/>
            </button>
          `;
          return;
        }

        detailGallery.innerHTML = currentImages.map((image, index) => `
          <button
            type="button"
            data-image-index="${index}"
            class="aspect-square rounded-2xl border border-gray-200 overflow-hidden ${index === activeImageIndex ? 'thumb-active' : ''}"
          >
            <img src="${image.url || store.productImage(image, currentProduct?.name || 'Product')}" alt="${store.escapeHtml(image.alt || currentProduct?.name || 'Product')}" class="w-full h-full object-cover"/>
          </button>
        `).join('');
      };

      const showNotFound = () => {
        detailContent.classList.add('hidden');
        notFoundState.classList.remove('hidden');
      };

      const renderProduct = (product) => {
        currentProduct = product;
        currentImages = Array.isArray(product.images) ? product.images : [];
        activeImageIndex = 0;

        const platformNames = (product.platforms || []).map((platform) => platform.name);
        const categoryNames = (product.categories || []).map((category) => category.name);
        const isPhysical = product.type === 'PHYSICAL';
        const inStock = Number(product.stock) > 0;

        document.title = `L — ${product.name}`;
        detailName.textContent = product.name;
        detailCategory.textContent = categoryNames[0] || 'Uncategorized';
        detailType.textContent = isPhysical ? 'Physical' : 'Digital';
        detailDescription.textContent = product.description || 'No product description provided.';
        detailPrice.textContent = store.formatMoney(product.price, product.currency);
        detailStock.textContent = inStock ? `${product.stock} in stock` : 'Out of stock';
        detailPlatformsSummary.textContent = platformNames.join(', ') || 'Platform not specified';
        detailCategoriesSummary.textContent = categoryNames.join(', ') || 'No categories';
        detailCurrencySummary.textContent = product.currency || 'EUR';
        deliveryHint.textContent = isPhysical
          ? 'Physical items require a delivery method during checkout. The final shipping price is calculated from the selected option.'
          : 'Digital items are delivered instantly by email after payment confirmation.';

        platformSelect.innerHTML = (product.platforms || []).map((platform) => `
          <option value="${store.escapeHtml(platform.slug)}">${store.escapeHtml(platform.name)}</option>
        `).join('') || '<option>Platform not specified</option>';

        deliverySelect.innerHTML = isPhysical
          ? '<option>Pickup or courier selected at checkout</option>'
          : '<option>Instant email delivery</option>';

        addToCartButton.disabled = !inStock;
        addToCartButton.className = inStock
          ? 'w-full sm:w-auto sm:px-10 py-3.5 bg-gray-900 text-white text-sm font-semibold rounded-xl hover:bg-black transition-colors'
          : 'w-full sm:w-auto sm:px-10 py-3.5 bg-gray-200 text-gray-500 text-sm font-semibold rounded-xl cursor-not-allowed';
        addToCartButton.textContent = inStock ? 'Add to cart' : 'Out of stock';

        renderMainImage();
        renderGallery();
        detailContent.classList.remove('hidden');
        notFoundState.classList.add('hidden');
      };

      detailGallery.addEventListener('click', (event) => {
        const button = event.target.closest('[data-image-index]');
        if (!button) return;

        activeImageIndex = Number(button.dataset.imageIndex || '0');
        renderMainImage();
        renderGallery();
      });

      addToCartButton.addEventListener('click', () => {
        if (!currentProduct || addToCartButton.disabled) return;

        store.addItem(currentProduct.id, 1);
        updateBadge();

        const previous = addToCartButton.textContent;
        addToCartButton.textContent = 'Added';
        addToCartButton.disabled = true;

        window.setTimeout(() => {
          const available = Number(currentProduct.stock) > 0;
          addToCartButton.textContent = available ? 'Add to cart' : previous;
          addToCartButton.disabled = !available;
        }, 700);
      });

      window.addEventListener('cart:updated', updateBadge);

      const boot = async () => {
        updateBadge();

        if (!slug) {
          showNotFound();
          return;
        }

        setMessage('Loading product...', 'info');

        try {
          const payload = await store.fetchCatalogProduct(slug);
          setMessage('');
          renderProduct(payload.data);
        } catch (error) {
          setMessage('');

          if (error.status === 404) {
            showNotFound();
            return;
          }

          showNotFound();
          setMessage(store.firstError(error.payload, 'Unable to load product right now.'));
        }
      };

      boot();
    })();
  </script>
</body>
</html>
