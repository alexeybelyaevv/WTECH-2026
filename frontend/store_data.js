(function () {
  const CATALOG = [
    {
      id: "steam_elden_ring",
      name: "Elden Ring (Steam Key)",
      category: "Game Key",
      platform: "PC / Steam",
      description: "Global key. Instant delivery to email.",
      price: 39.99,
      oldPrice: 59.99,
      rating: 4.9,
      tag: "Top Seller",
      thumb: "ER",
      image: "https://cdn.akamai.steamstatic.com/steam/apps/1245620/header.jpg"
    },
    {
      id: "steam_cyberpunk_2077",
      name: "Cyberpunk 2077 Ultimate (Steam Key)",
      category: "Game Key",
      platform: "PC / Steam",
      description: "Includes base game + Phantom Liberty.",
      price: 44.99,
      oldPrice: 79.99,
      rating: 4.7,
      tag: "-44%",
      thumb: "CP77",
      image: "https://cdn.akamai.steamstatic.com/steam/apps/1091500/header.jpg"
    },
    {
      id: "rockstar_gta_v_premium",
      name: "GTA V Premium Online Edition",
      category: "Game Key",
      platform: "PC / Rockstar",
      description: "Activation key for Rockstar launcher.",
      price: 13.49,
      oldPrice: 29.99,
      rating: 4.8,
      tag: "Budget",
      thumb: "GTA",
      image: "https://cdn.akamai.steamstatic.com/steam/apps/271590/header.jpg"
    },
    {
      id: "gamepass_ultimate_3m",
      name: "Xbox Game Pass Ultimate 3 Months",
      category: "Subscription",
      platform: "Xbox / PC",
      description: "Region free trial-safe code. Auto delivery.",
      price: 27.99,
      oldPrice: 39.99,
      rating: 4.8,
      tag: "Subscription",
      thumb: "XGP",
      image: "https://cdn.akamai.steamstatic.com/steam/apps/1716740/header.jpg"
    },
    {
      id: "wow_60_days",
      name: "World of Warcraft 60 Days Time Card",
      category: "Subscription",
      platform: "Battle.net",
      description: "Adds 60 days of active game time.",
      price: 24.99,
      oldPrice: 29.99,
      rating: 4.6,
      tag: "MMO",
      thumb: "WOW",
      image: "https://cdn.akamai.steamstatic.com/steam/apps/2344520/header.jpg"
    },
    {
      id: "ea_play_pro_1m",
      name: "EA Play Pro 1 Month",
      category: "Subscription",
      platform: "EA app",
      description: "Full access to EA premium catalog.",
      price: 14.99,
      oldPrice: 16.99,
      rating: 4.4,
      tag: "EA",
      thumb: "EA",
      image: "https://cdn.akamai.steamstatic.com/steam/apps/2195250/header.jpg"
    },
    {
      id: "fortnite_2800_vbucks",
      name: "Fortnite 2,800 V-Bucks",
      category: "In-game Currency",
      platform: "Epic Games",
      description: "Digital code for your Epic account.",
      price: 21.99,
      oldPrice: 24.99,
      rating: 4.9,
      tag: "Currency",
      thumb: "VB",
      image: "https://cdn.akamai.steamstatic.com/steam/apps/1172470/header.jpg"
    },
    {
      id: "valorant_2050_vp",
      name: "Valorant 2,050 VP",
      category: "In-game Currency",
      platform: "Riot",
      description: "Top up points for skins and battle pass.",
      price: 19.99,
      oldPrice: 21.99,
      rating: 4.8,
      tag: "Riot",
      thumb: "VP",
      image: "https://cdn.akamai.steamstatic.com/steam/apps/730/header.jpg"
    },
    {
      id: "steam_wallet_50",
      name: "Steam Wallet Gift Card $50",
      category: "In-game Currency",
      platform: "Steam",
      description: "US wallet top-up code for Steam account.",
      price: 50.0,
      oldPrice: 50.0,
      rating: 4.9,
      tag: "Gift Card",
      thumb: "$50",
      image: "https://cdn.akamai.steamstatic.com/steam/apps/753/header.jpg"
    }
  ];

  const CATALOG_MAP = CATALOG.reduce((map, product) => {
    map[product.id] = product;
    return map;
  }, {});

  const CART_KEY = "l_store_cart_v1";
  const DEMO_CART = {
    steam_elden_ring: 1,
    gamepass_ultimate_3m: 1,
    fortnite_2800_vbucks: 2
  };

  const clampToQty = (value) => {
    const parsed = Number.parseInt(value, 10);
    if (!Number.isFinite(parsed) || parsed < 0) return 0;
    return parsed;
  };

  const sanitizeCart = (cartLike) => {
    const source = cartLike && typeof cartLike === "object" ? cartLike : {};
    const clean = {};

    for (const [productId, qty] of Object.entries(source)) {
      const safeQty = clampToQty(qty);
      if (safeQty > 0 && CATALOG_MAP[productId]) {
        clean[productId] = safeQty;
      }
    }

    return clean;
  };

  const readCart = (options = {}) => {
    const { withDemo = false } = options;

    try {
      const raw = window.localStorage.getItem(CART_KEY);
      const hasStoredValue = raw !== null;
      const parsed = raw ? JSON.parse(raw) : {};
      let cart = sanitizeCart(parsed);

      if (withDemo && !hasStoredValue && Object.keys(cart).length === 0) {
        cart = { ...DEMO_CART };
        window.localStorage.setItem(CART_KEY, JSON.stringify(cart));
      }

      return cart;
    } catch (_) {
      return withDemo ? { ...DEMO_CART } : {};
    }
  };

  const writeCart = (cartLike) => {
    const cart = sanitizeCart(cartLike);

    try {
      window.localStorage.setItem(CART_KEY, JSON.stringify(cart));
    } catch (_) {
      return cart;
    }

    window.dispatchEvent(
      new CustomEvent("cart:updated", {
        detail: { cart }
      })
    );

    return cart;
  };

  const setItemQty = (productId, qty) => {
    const cart = readCart();
    const nextQty = clampToQty(qty);

    if (!CATALOG_MAP[productId] || nextQty <= 0) {
      delete cart[productId];
    } else {
      cart[productId] = nextQty;
    }

    return writeCart(cart);
  };

  const addItem = (productId, delta = 1) => {
    const cart = readCart();
    const safeDelta = clampToQty(delta);
    const current = clampToQty(cart[productId]);

    if (!CATALOG_MAP[productId] || safeDelta <= 0) {
      return cart;
    }

    cart[productId] = current + safeDelta;
    return writeCart(cart);
  };

  const changeItemQty = (productId, delta = 0) => {
    const cart = readCart();
    const current = clampToQty(cart[productId]);
    const next = current + Number.parseInt(delta, 10);

    return setItemQty(productId, next);
  };

  const removeItem = (productId) => {
    return setItemQty(productId, 0);
  };

  const getCartItems = (cartLike) => {
    const cart = sanitizeCart(cartLike);

    return Object.entries(cart)
      .filter(([productId]) => Boolean(CATALOG_MAP[productId]))
      .map(([productId, qty]) => {
        return {
          product: CATALOG_MAP[productId],
          qty
        };
      });
  };

  const getCartCount = (cartLike) => {
    return getCartItems(cartLike).reduce((total, item) => total + item.qty, 0);
  };

  const getSubtotal = (cartLike) => {
    return getCartItems(cartLike).reduce((total, item) => {
      return total + item.product.price * item.qty;
    }, 0);
  };

  const formatMoney = (value) => {
    return new Intl.NumberFormat("en-US", {
      style: "currency",
      currency: "USD"
    }).format(value);
  };

  window.StoreMvp = {
    CATALOG,
    CART_KEY,
    DEMO_CART,
    readCart,
    writeCart,
    addItem,
    setItemQty,
    changeItemQty,
    removeItem,
    getCartItems,
    getCartCount,
    getSubtotal,
    formatMoney
  };
})();
