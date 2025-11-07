/**
 * Toast Notification System - Aakaari Brand
 * Beautiful toast notifications with animations
 */
(function() {
  // Create toast container if it doesn't exist
  function getToastContainer() {
    let container = document.querySelector('.toast-container');
    if (!container) {
      container = document.createElement('div');
      container.className = 'toast-container';
      document.body.appendChild(container);
    }
    return container;
  }

  /**
   * Show a toast notification
   * @param {Object} options - Toast options
   * @param {string} options.type - 'success', 'wishlist', 'info'
   * @param {string} options.title - Toast title
   * @param {string} options.message - Toast message
   * @param {Object} options.product - Product details {name, price, image}
   * @param {Array} options.actions - Action buttons [{text, href, primary}]
   * @param {number} options.duration - Auto-dismiss duration (ms), default 3000
   */
  window.showToast = function(options) {
    const {
      type = 'info',
      title = '',
      message = '',
      product = null,
      actions = [],
      duration = 3000
    } = options;

    const container = getToastContainer();

    // Create toast element
    const toast = document.createElement('div');
    toast.className = 'toast';

    // Icon
    let icon = '✓';
    if (type === 'wishlist') icon = '♥';
    if (type === 'info') icon = 'ℹ';

    // Build HTML
    let html = `
      <div class="toast-icon ${type}">
        ${icon}
      </div>
      <div class="toast-content">
        ${title ? `<h4 class="toast-title">${title}</h4>` : ''}
        ${message ? `<p class="toast-message">${message}</p>` : ''}
        ${product ? `
          <div class="toast-product">
            <img src="${product.image}" alt="${product.name}" class="toast-product-image" />
            <div class="toast-product-info">
              <p class="toast-product-name">${product.name}</p>
              <p class="toast-product-price">${product.price}</p>
            </div>
          </div>
        ` : ''}
        ${actions.length > 0 ? `
          <div class="toast-actions">
            ${actions.map(action => `
              <a href="${action.href}" class="toast-action-btn ${action.primary ? 'primary' : ''}">${action.text}</a>
            `).join('')}
          </div>
        ` : ''}
      </div>
      <button class="toast-close" aria-label="Close">×</button>
      <div class="toast-progress">
        <div class="toast-progress-bar ${type}"></div>
      </div>
    `;

    toast.innerHTML = html;

    // Add to container
    container.appendChild(toast);

    // Close button handler
    const closeBtn = toast.querySelector('.toast-close');
    closeBtn.addEventListener('click', () => {
      dismissToast(toast);
    });

    // Auto dismiss
    if (duration > 0) {
      setTimeout(() => {
        dismissToast(toast);
      }, duration);
    }

    return toast;
  };

  /**
   * Dismiss a toast
   */
  function dismissToast(toast) {
    toast.classList.add('hiding');
    setTimeout(() => {
      toast.remove();
    }, 200);
  }

  /**
   * Show success toast
   */
  window.showSuccessToast = function(title, message, productData) {
    return showToast({
      type: 'success',
      title: title,
      message: message,
      product: productData,
      actions: productData ? [
        { text: 'View Cart', href: '/cart', primary: true },
        { text: 'Continue Shopping', href: '/shop' }
      ] : []
    });
  };

  /**
   * Show wishlist toast
   */
  window.showWishlistToast = function(productData) {
    return showToast({
      type: 'wishlist',
      title: 'Added to Wishlist',
      message: 'Item saved for later',
      product: productData,
      actions: [
        { text: 'View Wishlist', href: '/my-account/?tab=wishlist', primary: true }
      ]
    });
  };

  /**
   * Show cart toast
   */
  window.showCartToast = function(productData) {
    return showToast({
      type: 'success',
      title: 'Added to Cart',
      message: 'Item successfully added',
      product: productData,
      actions: [
        { text: 'View Cart', href: '/cart', primary: true },
        { text: 'Checkout', href: '/checkout' }
      ]
    });
  };

  /**
   * Show simple message toast
   */
  window.showMessageToast = function(title, message, type = 'info') {
    return showToast({
      type: type,
      title: title,
      message: message,
      duration: 2000
    });
  };
})();
