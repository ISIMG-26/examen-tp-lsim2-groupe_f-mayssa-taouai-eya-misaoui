// =====================================================
// DigiMarket — Vanilla JS
// ✅ Part 6: DOM selection, manipulation, events
// ✅ Part 7: Client-side validation
// ✅ Part 8: AJAX (fetch)
// =====================================================

document.addEventListener('DOMContentLoaded', () => {

  /* ---------- 1. REGISTER FORM VALIDATION ---------- */
  const regForm = document.getElementById('registerForm');
  if (regForm) {
    regForm.addEventListener('submit', (e) => {
      const errors = [];
      const username = document.getElementById('username').value.trim();
      const email    = document.getElementById('email').value.trim();
      const pass     = document.getElementById('password').value;
      const confirm  = document.getElementById('confirm').value;
      const emailRe  = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

      if (username.length < 3) errors.push('Username must be at least 3 characters.');
      if (!emailRe.test(email)) errors.push('Please enter a valid email.');
      if (pass.length < 6) errors.push('Password must be at least 6 characters.');
      if (!/[A-Za-z]/.test(pass) || !/\d/.test(pass)) errors.push('Password should contain letters and numbers.');
      if (pass !== confirm) errors.push('Passwords do not match.');

      const out = document.getElementById('formErrors');
      if (errors.length) {
        e.preventDefault();
        out.innerHTML = '';
        // dynamic add elements
        const ul = document.createElement('ul');
        errors.forEach(err => {
          const li = document.createElement('li');
          li.textContent = err;
          ul.appendChild(li);
        });
        out.appendChild(ul);
      }
    });
  }

  /* ---------- 2. LOGIN FORM VALIDATION ---------- */
  const loginForm = document.getElementById('loginForm');
  if (loginForm) {
    loginForm.addEventListener('submit', (e) => {
      const email = document.getElementById('loginEmail').value.trim();
      const pass  = document.getElementById('loginPassword').value;
      const out   = document.getElementById('loginErrors');
      const errs  = [];
      if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) errs.push('Invalid email.');
      if (pass.length < 6) errs.push('Password too short.');
      if (errs.length) { e.preventDefault(); out.textContent = errs.join(' '); }
    });
  }

  /* ---------- 3. AJAX EMAIL AVAILABILITY ---------- */
  const emailForm = document.getElementById('emailCheckForm');
  if (emailForm) {
    emailForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      const input  = document.getElementById('emailCheck');
      const result = document.getElementById('emailCheckResult');
      result.className = 'result-msg';
      result.textContent = 'Checking…';

      try {
        const res  = await fetch('/digimarket/api/check_email.php?email=' + encodeURIComponent(input.value));
        const data = await res.json();
        if (!data.ok) {
          result.textContent = '⚠️  Please enter a valid email.';
          result.className = 'result-msg error-text';
        } else if (data.available) {
          result.textContent = '✅  This email is available!';
          result.style.color = 'var(--success)';
        } else {
          result.textContent = '❌  This email is already in use.';
          result.style.color = 'var(--danger)';
        }
      } catch (err) {
        result.textContent = 'Network error.';
        result.className = 'result-msg error-text';
      }
    });
  }

  /* ---------- 4. AJAX LIVE SHOP SEARCH ---------- */
  const liveSearch = document.getElementById('liveSearch');
  const grid       = document.getElementById('productGrid');
  const counter    = document.getElementById('resultCount');
  const catLinks   = document.querySelectorAll('#catList a');
  let currentCat   = 0;
  let debounce;

  // current category from URL
  const urlParams = new URLSearchParams(window.location.search);
  currentCat = parseInt(urlParams.get('category') || '0', 10);

  function renderProducts(items) {
    grid.innerHTML = '';
    if (!items.length) {
      grid.innerHTML = '<p>No products found.</p>';
      counter.textContent = '0 result(s)';
      return;
    }
    counter.textContent = items.length + ' result(s)';
    items.forEach(p => {
      const card = document.createElement('article');
      card.className = 'product-card';
      const hue = (p.id * 47) % 360;
      card.innerHTML = `
        <div class="product-img" style="background:hsl(${hue} 60% 85%);">
          ${p.title.charAt(0).toUpperCase()}
        </div>
        <span class="tag">${p.category_name}</span>
        <h3>${p.title}</h3>
        <p class="desc">${(p.description || '').slice(0, 90)}…</p>
        <p class="price">$${parseFloat(p.price).toFixed(2)}</p>
      `;
      grid.appendChild(card);
    });
  }

  async function fetchProducts() {
    const q = liveSearch ? liveSearch.value.trim() : '';
    try {
      const res  = await fetch(`/digimarket/api/search.php?q=${encodeURIComponent(q)}&category=${currentCat}`);
      const data = await res.json();
      if (data.ok) renderProducts(data.items);
    } catch (e) { console.error(e); }
  }

  if (liveSearch) {
    liveSearch.addEventListener('input', () => {
      clearTimeout(debounce);
      debounce = setTimeout(fetchProducts, 250);
    });
  }

  if (catLinks.length) {
    catLinks.forEach(a => {
      a.addEventListener('click', (e) => {
        e.preventDefault();
        catLinks.forEach(x => x.classList.remove('active'));
        a.classList.add('active');
        currentCat = parseInt(a.dataset.cat, 10);
        fetchProducts();
      });
    });
  }

  /* ---------- 5. ADMIN TABS (interactive feature) ---------- */
  const tabs   = document.querySelectorAll('.tab-btn');
  const panels = document.querySelectorAll('.tab-panel');
  if (tabs.length) {
    tabs.forEach(btn => {
      btn.addEventListener('click', () => {
        tabs.forEach(t => t.classList.remove('active'));
        panels.forEach(p => p.classList.remove('active'));
        btn.classList.add('active');
        document.querySelector(`.tab-panel[data-panel="${btn.dataset.tab}"]`).classList.add('active');
      });
    });
  }
});
