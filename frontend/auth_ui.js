(() => {
  const LOGIN_SELECTOR = 'nav a[href$="login.html"], nav a[href$="/login.html"]';
  const REGISTER_SELECTOR = 'a[href$="register.html"], a[href$="/register.html"]';
  const CART_SELECTOR = 'a[href$="cart.html"], a[href$="/cart.html"]';

  const onReady = (callback) => {
    if (document.readyState === "loading") {
      document.addEventListener("DOMContentLoaded", callback, { once: true });
      return;
    }

    callback();
  };

  const createUserLabel = (name) => {
    const label = document.createElement("span");
    label.className = "hidden text-sm text-gray-500 md:inline";
    label.textContent = name ? `Hi, ${name}` : "Signed in";
    return label;
  };

  const createLogoutButton = () => {
    const button = document.createElement("button");
    button.type = "button";
    button.className =
      "rounded bg-gray-900 px-4 py-1.5 text-sm text-white transition-colors hover:bg-black disabled:cursor-not-allowed disabled:opacity-70";
    button.textContent = "Logout";
    button.title = "Logout";

    button.addEventListener("click", async () => {
      const initialText = button.textContent;
      button.disabled = true;
      button.textContent = "Logging out...";

      try {
        const response = await fetch("/api/auth/logout", {
          method: "POST",
          credentials: "same-origin",
          headers: {
            Accept: "application/json",
          },
        });

        const payload = await response.json().catch(() => ({}));
        window.location.assign(payload.redirect_to || "/index.html");
      } catch (_error) {
        button.disabled = false;
        button.textContent = initialText;
      }
    });

    return button;
  };

  const upgradeHeader = (loginLink, auth) => {
    const container = loginLink.parentElement;
    if (!container || container.dataset.authEnhanced === "true") {
      return;
    }

    const registerLink = container.querySelector(REGISTER_SELECTOR);
    if (!registerLink) {
      return;
    }

    loginLink.remove();
    registerLink.remove();

    const cartLink = container.querySelector(CART_SELECTOR);
    const userLabel = createUserLabel(auth.user?.name);
    const logoutButton = createLogoutButton();

    container.insertBefore(userLabel, cartLink || null);
    container.insertBefore(logoutButton, cartLink || null);
    container.dataset.authEnhanced = "true";
  };

  onReady(async () => {
    const loginLinks = Array.from(document.querySelectorAll(LOGIN_SELECTOR));
    if (loginLinks.length === 0) {
      return;
    }

    try {
      const response = await fetch("/api/auth/me", {
        credentials: "same-origin",
        headers: {
          Accept: "application/json",
        },
      });

      if (!response.ok) {
        return;
      }

      const auth = await response.json();
      if (!auth.authenticated) {
        return;
      }

      loginLinks.forEach((loginLink) => upgradeHeader(loginLink, auth));
    } catch (_error) {
      // Ignore auth UI errors and keep the default header state.
    }
  });
})();
