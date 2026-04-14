(function () {
  const CART_KEY = "l_store_cart_v2";
  const DEFAULT_CURRENCY = "EUR";
  const DEFAULT_LOCALE = "en-IE";

  let csrfTokenPromise = null;

  const escapeHtml = (value) => {
    return String(value ?? "")
      .replaceAll("&", "&amp;")
      .replaceAll("<", "&lt;")
      .replaceAll(">", "&gt;")
      .replaceAll('"', "&quot;")
      .replaceAll("'", "&#39;");
  };

  const encodeSvg = (svg) => `data:image/svg+xml;charset=UTF-8,${encodeURIComponent(svg)}`;

  const placeholderImage = (label = "Product") => {
    const safeLabel = escapeHtml(label).slice(0, 40);

    return encodeSvg(`
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 480">
        <defs>
          <linearGradient id="bg" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="#f3f4f6" />
            <stop offset="100%" stop-color="#e5e7eb" />
          </linearGradient>
        </defs>
        <rect width="640" height="480" fill="url(#bg)" />
        <circle cx="320" cy="188" r="54" fill="#d1d5db" />
        <path d="M128 376l108-112 72 72 52-52 152 92H128z" fill="#cbd5e1" />
        <text x="320" y="432" text-anchor="middle" fill="#6b7280" font-family="Montserrat, Arial, sans-serif" font-size="26" font-weight="600">${safeLabel}</text>
      </svg>
    `);
  };

  const isPlainObject = (value) => value && typeof value === "object" && !Array.isArray(value);

  const normalizeProductId = (productId) => {
    const normalized = String(productId ?? "").trim();
    return normalized === "" ? null : normalized;
  };

  const clampToQty = (value) => {
    const parsed = Number.parseInt(value, 10);
    if (!Number.isFinite(parsed) || parsed < 0) return 0;
    return parsed;
  };

  const sanitizeCart = (cartLike) => {
    const source = isPlainObject(cartLike) ? cartLike : {};
    const clean = {};

    for (const [rawProductId, qty] of Object.entries(source)) {
      const productId = normalizeProductId(rawProductId);
      const safeQty = clampToQty(qty);

      if (productId && safeQty > 0) {
        clean[productId] = safeQty;
      }
    }

    return clean;
  };

  const readCart = () => {
    try {
      const raw = window.localStorage.getItem(CART_KEY);
      const parsed = raw ? JSON.parse(raw) : {};
      return sanitizeCart(parsed);
    } catch (_error) {
      return {};
    }
  };

  const writeCart = (cartLike) => {
    const cart = sanitizeCart(cartLike);

    try {
      window.localStorage.setItem(CART_KEY, JSON.stringify(cart));
    } catch (_error) {
      return cart;
    }

    window.dispatchEvent(
      new CustomEvent("cart:updated", {
        detail: { cart },
      }),
    );

    return cart;
  };

  const setItemQty = (productId, qty) => {
    const normalizedId = normalizeProductId(productId);
    const nextQty = clampToQty(qty);
    const cart = readCart();

    if (!normalizedId || nextQty <= 0) {
      if (normalizedId) {
        delete cart[normalizedId];
      }

      return writeCart(cart);
    }

    cart[normalizedId] = nextQty;
    return writeCart(cart);
  };

  const addItem = (productId, delta = 1) => {
    const normalizedId = normalizeProductId(productId);
    const safeDelta = clampToQty(delta);

    if (!normalizedId || safeDelta <= 0) {
      return readCart();
    }

    const cart = readCart();
    const current = clampToQty(cart[normalizedId]);
    cart[normalizedId] = current + safeDelta;

    return writeCart(cart);
  };

  const changeItemQty = (productId, delta = 0) => {
    const normalizedId = normalizeProductId(productId);
    const current = clampToQty(readCart()[normalizedId]);
    const next = current + Number.parseInt(delta, 10);

    return setItemQty(normalizedId, next);
  };

  const removeItem = (productId) => {
    return setItemQty(productId, 0);
  };

  const getCartEntries = (cartLike) => {
    const cart = sanitizeCart(cartLike);

    return Object.entries(cart).map(([productId, qty]) => ({
      productId,
      quantity: qty,
    }));
  };

  const getCartCount = (cartLike) => {
    return getCartEntries(cartLike).reduce((total, entry) => total + entry.quantity, 0);
  };

  const formatMoney = (value, currency = DEFAULT_CURRENCY) => {
    return new Intl.NumberFormat(DEFAULT_LOCALE, {
      style: "currency",
      currency: currency || DEFAULT_CURRENCY,
    }).format(Number(value) || 0);
  };

  const productImage = (product, fallbackLabel = "Product") => {
    if (!product || typeof product !== "object") {
      return placeholderImage(fallbackLabel);
    }

    const candidate =
      product.preview_image_url ||
      product.preview_image ||
      product.image ||
      product.url ||
      product.path ||
      product.images?.[0]?.url ||
      product.images?.[0]?.path ||
      null;

    if (!candidate) {
      return placeholderImage(product.name || fallbackLabel);
    }

    if (/^https?:\/\//i.test(candidate) || candidate.startsWith("data:")) {
      return candidate;
    }

    if (candidate.startsWith("/")) {
      return candidate;
    }

    return `/storage/${candidate.replace(/^\/+/, "")}`;
  };

  const productCategoryLabel = (product) => {
    return product?.categories?.[0]?.name || "Uncategorized";
  };

  const productPlatformLabel = (product) => {
    if (!Array.isArray(product?.platforms) || product.platforms.length === 0) {
      return "Platform not specified";
    }

    return product.platforms.map((platform) => platform.name).join(", ");
  };

  const toQueryString = (params = {}) => {
    const searchParams = new URLSearchParams();

    Object.entries(params).forEach(([key, value]) => {
      if (value === null || value === undefined || value === "") {
        return;
      }

      if (Array.isArray(value)) {
        value.forEach((item) => {
          if (item !== null && item !== undefined && item !== "") {
            searchParams.append(`${key}[]`, String(item));
          }
        });

        return;
      }

      searchParams.append(key, String(value));
    });

    return searchParams.toString();
  };

  const createHttpError = (response, payload) => {
    const error = new Error(
      payload?.message ||
        (response.status === 401
          ? "Authentication required."
          : response.status === 403
            ? "You do not have access to this action."
            : response.status === 404
              ? "Requested resource was not found."
              : "Request failed."),
    );

    error.status = response.status;
    error.payload = payload;

    return error;
  };

  const requestJson = async (url, options = {}) => {
    const { headers = {}, body, ...rest } = options;
    const finalHeaders = {
      Accept: "application/json",
      ...headers,
    };

    const isFormData = body instanceof FormData;
    if (body !== undefined && !isFormData && !finalHeaders["Content-Type"]) {
      finalHeaders["Content-Type"] = "application/json";
    }

    const response = await fetch(url, {
      credentials: "same-origin",
      ...rest,
      headers: finalHeaders,
      body,
    });

    if (response.status === 204) {
      return null;
    }

    const contentType = response.headers.get("content-type") || "";
    const payload = contentType.includes("application/json")
      ? await response.json().catch(() => ({}))
      : await response.text().catch(() => "");

    if (!response.ok) {
      throw createHttpError(response, payload);
    }

    return payload;
  };

  const ensureCsrfToken = async () => {
    if (!csrfTokenPromise) {
      csrfTokenPromise = requestJson("/api/auth/csrf-token").then((payload) => payload?.token || "");
    }

    return csrfTokenPromise;
  };

  const requestWithCsrf = async (url, options = {}) => {
    const token = await ensureCsrfToken();
    const headers = {
      ...(options.headers || {}),
      "X-CSRF-TOKEN": token,
    };

    return requestJson(url, {
      ...options,
      headers,
    });
  };

  const firstError = (payload, fallback = "Request failed.") => {
    if (payload && typeof payload.message === "string" && !payload.errors) {
      return payload.message;
    }

    if (!payload || typeof payload !== "object" || !payload.errors) {
      return fallback;
    }

    for (const value of Object.values(payload.errors)) {
      if (Array.isArray(value) && value.length > 0) {
        return value[0];
      }
    }

    return payload.message || fallback;
  };

  const fetchCatalogProducts = async (params = {}) => {
    const query = toQueryString(params);
    const url = query ? `/api/products?${query}` : "/api/products";

    return requestJson(url);
  };

  const fetchCatalogProduct = async (slug) => {
    return requestJson(`/api/products/${encodeURIComponent(slug)}`);
  };

  const fetchCatalogProductsByIds = async (ids) => {
    const normalizedIds = Array.from(
      new Set(
        (Array.isArray(ids) ? ids : [])
          .map((id) => Number.parseInt(id, 10))
          .filter((id) => Number.isInteger(id) && id > 0),
      ),
    );

    if (normalizedIds.length === 0) {
      return [];
    }

    const payload = await fetchCatalogProducts({
      ids: normalizedIds,
      per_page: Math.min(Math.max(normalizedIds.length, 1), 48),
    });

    return Array.isArray(payload?.data) ? payload.data : [];
  };

  const fetchCheckoutOptions = async () => {
    return requestJson("/api/checkout/options");
  };

  const placeOrder = async (payload) => {
    return requestJson("/api/orders", {
      method: "POST",
      body: JSON.stringify(payload),
    });
  };

  const fetchOrder = async (orderNumber) => {
    return requestJson(`/api/orders/${encodeURIComponent(orderNumber)}`);
  };

  const fetchAdminReferences = async () => {
    return requestJson("/api/admin/references");
  };

  const fetchAdminProducts = async (params = {}) => {
    const query = toQueryString(params);
    const url = query ? `/api/admin/products?${query}` : "/api/admin/products";

    return requestJson(url);
  };

  const fetchAdminProduct = async (productId) => {
    return requestJson(`/api/admin/products/${encodeURIComponent(productId)}`);
  };

  const createAdminProduct = async (formData) => {
    return requestWithCsrf("/api/admin/products", {
      method: "POST",
      body: formData,
    });
  };

  const updateAdminProduct = async (productId, formData) => {
    formData.append("_method", "PUT");

    return requestWithCsrf(`/api/admin/products/${encodeURIComponent(productId)}`, {
      method: "POST",
      body: formData,
    });
  };

  const deleteAdminProduct = async (productId) => {
    const formData = new FormData();
    formData.append("_method", "DELETE");

    return requestWithCsrf(`/api/admin/products/${encodeURIComponent(productId)}`, {
      method: "POST",
      body: formData,
    });
  };

  const deleteAdminProductImage = async (productId, imageId) => {
    const formData = new FormData();
    formData.append("_method", "DELETE");

    return requestWithCsrf(
      `/api/admin/products/${encodeURIComponent(productId)}/images/${encodeURIComponent(imageId)}`,
      {
        method: "POST",
        body: formData,
      },
    );
  };

  window.StoreMvp = {
    CART_KEY,
    addItem,
    changeItemQty,
    createAdminProduct,
    deleteAdminProduct,
    deleteAdminProductImage,
    ensureCsrfToken,
    escapeHtml,
    fetchAdminProduct,
    fetchAdminProducts,
    fetchAdminReferences,
    fetchCatalogProduct,
    fetchCatalogProducts,
    fetchCatalogProductsByIds,
    fetchCheckoutOptions,
    fetchOrder,
    firstError,
    formatMoney,
    getCartCount,
    getCartEntries,
    placeholderImage,
    placeOrder,
    productCategoryLabel,
    productImage,
    productPlatformLabel,
    readCart,
    removeItem,
    requestJson,
    requestWithCsrf,
    setItemQty,
    updateAdminProduct,
    writeCart,
  };
})();
