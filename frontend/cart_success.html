<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>L - Order Confirmed</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
  <style>
    body { font-family: 'Montserrat', sans-serif; }
    .font-display { font-family: 'Montserrat', sans-serif; }
  </style>
</head>
<body class="min-h-screen bg-[radial-gradient(circle_at_top_right,_#dbeafe_0%,_#f8fafc_42%,_#ffffff_100%)] text-gray-900 antialiased flex flex-col">

  <nav class="flex items-center justify-between px-4 sm:px-6 lg:px-8 py-4 border-b border-gray-100 sticky top-0 bg-white/85 backdrop-blur z-50">
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

  <main class="flex-1 flex items-center justify-center px-4 sm:px-6 py-10">
    <section class="w-full max-w-4xl bg-white border border-gray-200 rounded-3xl overflow-hidden shadow-[0_25px_70px_-40px_rgba(15,23,42,0.45)]">
      <header class="bg-gradient-to-r from-emerald-500 via-emerald-600 to-cyan-600 text-white px-6 sm:px-8 py-8">
        <div class="w-14 h-14 rounded-2xl bg-white/15 ring-1 ring-white/30 flex items-center justify-center mb-5">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.3">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
          </svg>
        </div>
        <h1 class="text-2xl sm:text-3xl font-bold tracking-tight">Thank you, order confirmed</h1>
        <p class="mt-2 text-sm text-emerald-50/95">Your order was created by the live checkout endpoint.</p>
      </header>

      <div id="successMessage" class="hidden mx-6 sm:mx-8 mt-6 rounded-2xl border px-4 py-3 text-sm"></div>

      <div id="orderContent" class="p-6 sm:p-8 grid grid-cols-1 md:grid-cols-[1fr_0.95fr] gap-6">
        <div class="space-y-3">
          <div class="rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3">
            <p class="text-[11px] uppercase tracking-[0.14em] text-gray-500">Order Number</p>
            <p id="orderNumber" class="mt-1 text-base font-bold text-gray-900">Loading...</p>
          </div>
          <div class="rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3">
            <p class="text-[11px] uppercase tracking-[0.14em] text-gray-500">Delivery Email</p>
            <p id="deliveryEmail" class="mt-1 text-base font-semibold text-gray-900 break-all">Loading...</p>
          </div>
          <div class="rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3">
            <p class="text-[11px] uppercase tracking-[0.14em] text-gray-500">Payment / Shipping</p>
            <p id="orderMethods" class="mt-1 text-base font-semibold text-gray-900">Loading...</p>
          </div>
          <div class="rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3">
            <p class="text-[11px] uppercase tracking-[0.14em] text-gray-500">Grand Total</p>
            <p id="orderTotal" class="mt-1 text-base font-semibold text-gray-900">€0.00</p>
          </div>
        </div>

        <aside class="rounded-2xl border border-gray-200 px-4 py-4 bg-white">
          <h2 class="text-sm font-semibold text-gray-900">Order items</h2>
          <div id="orderItems" class="mt-3 space-y-2 text-sm text-gray-600"></div>

          <div class="mt-5 grid grid-cols-1 gap-2">
            <a href="shop.html" class="inline-flex items-center justify-center w-full py-2.5 rounded-xl bg-gray-900 text-white text-sm font-semibold hover:bg-black transition-colors">Back to shop</a>
            <a href="index.html" class="inline-flex items-center justify-center w-full py-2.5 rounded-xl border border-gray-200 text-sm font-semibold text-gray-700 hover:border-gray-400 hover:text-gray-900 transition-colors">Go to home</a>
          </div>
        </aside>
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
      const orderNumberParam = params.get('order');

      const badge = document.getElementById('cartCountBadge');
      const successMessage = document.getElementById('successMessage');
      const orderNumber = document.getElementById('orderNumber');
      const deliveryEmail = document.getElementById('deliveryEmail');
      const orderMethods = document.getElementById('orderMethods');
      const orderTotal = document.getElementById('orderTotal');
      const orderItems = document.getElementById('orderItems');

      const setMessage = (text, type = 'error') => {
        if (!text) {
          successMessage.textContent = '';
          successMessage.className = 'hidden mx-6 sm:mx-8 mt-6 rounded-2xl border px-4 py-3 text-sm';
          return;
        }

        successMessage.textContent = text;
        successMessage.className =
          type === 'info'
            ? 'mx-6 sm:mx-8 mt-6 rounded-2xl border border-sky-200 bg-sky-50 px-4 py-3 text-sm text-sky-800'
            : 'mx-6 sm:mx-8 mt-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700';
      };

      const paymentLabels = {
        CARD: 'Card',
        CRYPTO: 'Crypto',
        BANK_TRANSFER: 'Bank transfer',
        CASH_ON_DELIVERY: 'Cash on delivery',
      };

      const shippingLabels = {
        EMAIL: 'Email delivery',
        PICKUP: 'Pickup',
        COURIER: 'Courier',
        ALZABOX: 'AlzaBox',
        POST_OFFICE: 'Post office',
        PACKETA: 'Packeta',
      };

      const count = store.getCartCount(store.readCart());
      badge.textContent = count > 99 ? '99+' : String(count);
      badge.classList.toggle('hidden', count === 0);

      const renderOrder = (order) => {
        orderNumber.textContent = order.order_number;
        deliveryEmail.textContent = order.customer?.email || 'Not provided';
        orderMethods.textContent = `${paymentLabels[order.payment_method] || order.payment_method} · ${shippingLabels[order.shipping_method] || order.shipping_method}`;
        orderTotal.textContent = store.formatMoney(order.grand_total, order.currency);

        orderItems.innerHTML = (order.items || []).map((item) => `
          <div class="flex items-center justify-between gap-3 rounded-xl border border-gray-200 px-3 py-2.5">
            <div class="min-w-0">
              <p class="text-sm font-semibold text-gray-800 truncate">${store.escapeHtml(item.product_name)}</p>
              <p class="text-xs text-gray-500">Qty ${item.quantity}</p>
            </div>
            <span class="text-sm font-semibold text-gray-800">${store.formatMoney(item.line_total, order.currency)}</span>
          </div>
        `).join('') || '<p class="text-sm text-gray-500">No order items found.</p>';
      };

      const boot = async () => {
        if (!orderNumberParam) {
          setMessage('Order number is missing in the URL.');
          orderNumber.textContent = 'Unavailable';
          deliveryEmail.textContent = 'Unavailable';
          orderMethods.textContent = 'Unavailable';
          orderItems.innerHTML = '<p class="text-sm text-gray-500">Unable to load order items.</p>';
          return;
        }

        setMessage('Loading order details...', 'info');

        try {
          const payload = await store.fetchOrder(orderNumberParam);
          renderOrder(payload.data);
          setMessage('');
        } catch (error) {
          setMessage(store.firstError(error.payload, 'Unable to load order details right now.'));
          orderNumber.textContent = orderNumberParam;
          deliveryEmail.textContent = 'Unavailable';
          orderMethods.textContent = 'Unavailable';
          orderItems.innerHTML = '<p class="text-sm text-gray-500">Unable to load order items.</p>';
        }
      };

      boot();
    })();
  </script>

</body>
</html>
