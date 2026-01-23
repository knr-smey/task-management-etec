/**
 * Show alert message
 * @param {string} selector  Element selector (#loginAlert)
 * @param {string} message   Alert text
 * @param {boolean} success  true = green, false = red
 */
window.showAlert = function (selector, message, success = false) {
  const el = document.querySelector(selector);
  if (!el) return;

  el.textContent = message;
  el.classList.remove("hidden");

  // reset
  el.classList.remove(
    "border-red-300", "text-red-700", "bg-red-50",
    "border-green-300", "text-green-700", "bg-green-50"
  );

  if (success) {
    el.classList.add("border-green-300", "text-green-700", "bg-green-50");
  } else {
    el.classList.add("border-red-300", "text-red-700", "bg-red-50");
  }
};

/**
 * Hide alert
 */
window.hideAlert = function (selector) {
  const el = document.querySelector(selector);
  if (!el) return;
  el.classList.add("hidden");
};
