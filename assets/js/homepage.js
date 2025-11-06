/**
 * homepage.js
 * Minimal interactive behavior for Aakaari front page.
 * - Carousel left/right
 * - View product (opens modal and fetches content via DOM)
 * - Add to cart (uses AJAX endpoint via wc add_to_cart if available)
 *
 * This script expects the markup produced by front-page.php.
 */

var AakaariHome = (function () {
    "use strict";
  
    // Utility: get track element for a named carousel (featured / arrivals)
    function getTrack(name) {
      var id = name === "featured" ? "featured-track" : (name === "arrivals" ? "arrivals-track" : null);
      return id ? document.getElementById(id) : null;
    }
  
    function shiftTrack(track, offset) {
      if (!track) return;
      // compute current translateX
      var style = window.getComputedStyle(track);
      var matrix = style.transform || style.webkitTransform || "none";
      var current = 0;
      if (matrix && matrix !== "none") {
        var values = matrix.match(/matrix.*\((.+)\)/);
        if (values) {
          var parts = values[1].split(",");
          current = parseFloat(parts[4]) || 0;
        }
      }
  
      var card = track.querySelector(".product-card");
      var gap = 14;
      var step = (card ? card.offsetWidth : 220) + gap;
      var next = current + offset * step;
  
      // basic bounds: do not scroll too far left (0) or too far right (totalWidth - visibleWidth)
      var totalWidth = track.scrollWidth;
      var visible = track.parentElement ? track.parentElement.offsetWidth : track.offsetWidth;
      var maxNegative = Math.min(0, visible - totalWidth - 8); // allow small buffer
      if (next > 0) next = 0;
      if (next < maxNegative) next = maxNegative;
  
      track.style.transform = "translateX(" + next + "px)";
    }
  
    // carousel button handler
    function onCarouselClick(e) {
      var btn = e.target.closest(".carousel-arrow");
      if (!btn) return;
      var target = btn.getAttribute("data-target");
      var action = btn.getAttribute("data-action");
      var track = getTrack(target);
      if (!track) return;
      if (action === "next") shiftTrack(track, -1);
      else shiftTrack(track, 1);
    }
  
    // Open modal with product details (we will read DOM to get content)
    function openModalWithProduct(productId) {
      var modal = document.getElementById("aakaari-product-modal");
      var content = document.getElementById("aakaari-modal-content");
      if (!modal || !content) return;
  
      // find the product card in DOM with same data-product-id
      var card = document.querySelector('[data-product-id="' + productId + '"]');
      if (!card) return;
  
      var title = card.querySelector(".product-title") ? card.querySelector(".product-title").textContent : "";
      var price = card.querySelector(".product-price") ? card.querySelector(".product-price").innerHTML : "";
      var img = card.querySelector(".product-image") ? card.querySelector(".product-image").src : "";
  
      content.innerHTML = "<div style='display:flex; gap:18px; align-items:flex-start;'>" +
        "<img src='" + img + "' alt='" + title + "' style='width:200px; height:200px; object-fit:cover; border-radius:8px;' />" +
        "<div style='flex:1;'>" +
        "<h3 style='margin:0 0 6px;'>" + escapeHtml(title) + "</h3>" +
        "<p style='margin:0 0 8px; color:#666;'>" + price + "</p>" +
        "<p style='margin:0 0 12px;'>" + "" + "</p>" +
        "<div style='display:flex; gap:8px;'>" +
        "<button class='btn primary' data-add='" + productId + "'>Add to cart</button>" +
        "<button class='btn secondary' onclick='AakaariHome.closeModal()'>Close</button>" +
        "</div></div></div>";
  
      modal.classList.remove("hidden");
      modal.setAttribute("aria-hidden", "false");
    }
  
    function closeModal() {
      var modal = document.getElementById("aakaari-product-modal");
      if (!modal) return;
      modal.classList.add("hidden");
      modal.setAttribute("aria-hidden", "true");
    }
  
    // basic add-to-cart: find anchor to product permalink and redirect to add-to-cart if simple product
    function addToCartById(productId) {
      // try to find an add-to-cart button/link inside the product card with WC data
      var card = document.querySelector('[data-product-id="' + productId + '"]');
      if (!card) return alert("Could not add to cart (demo).");
  
      // if WooCommerce's add-to-cart links are present as anchors, simulate click
      var a = card.querySelector('a.add_to_cart_button, a.button.add_to_cart_button');
      if (a) {
        a.click();
        return;
      }
  
      // Fallback: try redirect to single product page
      var view = card.querySelector('.view-product');
      if (view) {
        // read data-product-id and redirect to product permalink if present on the card
        var linkEl = card.querySelector('a');
        if (linkEl && linkEl.href) {
          window.location = linkEl.href;
          return;
        }
      }
  
      // final fallback: show a message
      alert("Added to cart (demo). For full add-to-cart, WooCommerce templates must render add-to-cart anchors/buttons on product cards.");
    }
  
    function escapeHtml(text) {
      return text
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
    }
  
    function bindEvents() {
      // carousel arrows
      document.querySelectorAll(".carousel-arrow").forEach(function (el) {
        el.addEventListener("click", onCarouselClick);
      });
  
      // product view buttons (delegation)
      document.addEventListener("click", function (e) {
        var view = e.target.closest(".view-product");
        if (view && view.dataset.productId) {
          openModalWithProduct(view.dataset.productId);
          return;
        }
        var add = e.target.closest(".add-to-cart");
        if (add && add.dataset.productId) {
          addToCartById(add.dataset.productId);
          return;
        }
        if (e.target.matches("#aakaari-product-modal .btn.primary") && e.target.dataset.add) {
          addToCartById(e.target.dataset.add);
          closeModal();
          return;
        }
      });
  
      // close modal on ESC
      document.addEventListener("keydown", function (e) {
        if (e.key === "Escape") closeModal();
      });
    }
  
    // Public API
    return {
      init: function () {
        bindEvents();
      },
      closeModal: closeModal
    };
  })();
  
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", AakaariHome.init);
  } else {
    AakaariHome.init();
  }
  