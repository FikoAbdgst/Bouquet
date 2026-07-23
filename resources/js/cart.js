window.CartStorage = {
  KEY: 'cart',

  get() {
    try {
      return JSON.parse(localStorage.getItem(this.KEY)) || [];
    } catch {
      return [];
    }
  },

  save(items) {
    localStorage.setItem(this.KEY, JSON.stringify(items));
    window.dispatchEvent(new Event('cart-updated'));
  },

  addItem(item) {
    const items = this.get();
    const existing = items.find(i => i.id === item.id);
    if (existing) {
      existing.qty += item.qty;
    } else {
      items.push(item);
    }
    this.save(items);
  },

  updateQty(id, qty) {
    const items = this.get().filter(i => {
      if (i.id === id) { i.qty = qty; return qty > 0; }
      return true;
    });
    this.save(items);
  },

  removeItem(id) {
    this.save(this.get().filter(i => i.id !== id));
  },

  clear() {
    localStorage.removeItem(this.KEY);
    window.dispatchEvent(new Event('cart-updated'));
  },

  count() {
    return this.get().reduce((s, i) => s + i.qty, 0);
  },

  total() {
    return this.get().reduce((s, i) => s + i.price * i.qty, 0);
  }
};

window.formatRupiah = function (num) {
  return 'Rp ' + num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
};

window.updateCartBadges = function () {
  const count = window.CartStorage.count();
  document.querySelectorAll('.cart-badge-desktop, .cart-badge-mobile').forEach(el => {
    if (count > 0) {
      el.textContent = count > 99 ? '99+' : count;
      el.classList.remove('hidden');
    } else {
      el.classList.add('hidden');
    }
  });
};

window.addEventListener('cart-updated', window.updateCartBadges);

document.addEventListener('DOMContentLoaded', () => {
  window.updateCartBadges();
});
