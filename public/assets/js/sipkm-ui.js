document.addEventListener('DOMContentLoaded', () => {
  // 1. Lazy Load Poster dengan Penghapusan Skeleton Shimmer
  document.querySelectorAll('img.lazy-img').forEach((img) => {
    const onImageReady = () => {
      img.classList.add('loaded');
      const wrapper = img.closest('.poster-wrapper');
      if (wrapper) wrapper.classList.remove('skeleton-shimmer');
    };
    if (img.complete) {
      onImageReady();
    } else {
      img.addEventListener('load', onImageReady);
      img.addEventListener('error', () => {
        img.src = 'https://placehold.co/600x360/0f294a/ffffff?text=Poster+Lomba+DKKA+UBL';
        onImageReady();
      });
    }
  });

  // 2. Anti Double-Submit & Button Loading State
  document.querySelectorAll('form.sipkm-form').forEach((form) => {
    form.addEventListener('submit', function (e) {
      if (!form.checkValidity()) {
        e.preventDefault();
        form.querySelectorAll(':invalid').forEach((el) => {
          el.classList.add('input-shake');
          setTimeout(() => el.classList.remove('input-shake'), 350);
        });
        showSipkmToast('Mohon lengkapi semua kolom dengan format yang benar.', 'warning');
        return;
      }
      const submitBtn = form.querySelector('button[type="submit"]');
      if (submitBtn && !submitBtn.disabled) {
        const loadingText = submitBtn.getAttribute('data-loading-text') || 'Memproses...';
        submitBtn.disabled = true;
        submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>${loadingText}`;
      }
    });
  });

  // 3. Real-time Validasi Link Google Drive / Docs
  document.querySelectorAll('input[data-validate-drive="true"]').forEach((input) => {
    input.addEventListener('input', () => {
      const val = input.value.trim();
      const isValid = /^https:\/\/(drive|docs)\.google\.com\/.+/.test(val);
      input.classList.toggle('is-valid', isValid && val.length > 0);
      input.classList.toggle('is-invalid', !isValid && val.length > 0);
    });
  });
});

// 4. Copy-to-Clipboard Helper + Toast
function copySipkmText(btnElement, textToCopy) {
  if (!textToCopy) {
    showSipkmToast('Teks masih kosong, tidak ada yang disalin.', 'warning');
    return;
  }
  navigator.clipboard.writeText(textToCopy).then(() => {
    const originalHTML = btnElement.innerHTML;
    btnElement.innerHTML = '<i class="bi bi-check-lg me-1"></i>✓ Tersalin!';
    btnElement.classList.add('btn-success');
    showSipkmToast('Teks berhasil disalin ke clipboard!', 'success');

    setTimeout(() => {
      btnElement.innerHTML = originalHTML;
      btnElement.classList.remove('btn-success');
    }, 2000);
  });
}

// 5. Floating Toast Notification
function showSipkmToast(message, type = 'primary') {
  let container = document.getElementById('sipkm-toast-container');
  if (!container) {
    container = document.createElement('div');
    container.id = 'sipkm-toast-container';
    document.body.appendChild(container);
  }
  const bgMap = {
    success: 'bg-success text-white',
    warning: 'bg-warning text-dark',
    danger: 'bg-danger text-white',
    primary: 'bg-dark text-white'
  };
  const toastEl = document.createElement('div');
  toastEl.className = `toast align-items-center ${bgMap[type] || bgMap.primary} border-0 shadow-lg mb-2 show animate-fade-up`;
  toastEl.innerHTML = `
    <div class="d-flex">
      <div class="toast-body fw-medium">${message}</div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" onclick="this.closest('.toast').remove()"></button>
    </div>`;
  container.appendChild(toastEl);
  setTimeout(() => toastEl.remove(), 3500);
}

// 6. Helper Simulasi Skeleton Shimmer saat Filter / Load
function triggerSkeletonTransition(skeletonId, contentId, delay = 400) {
  const skel = document.getElementById(skeletonId);
  const cont = document.getElementById(contentId);
  if (!skel || !cont) return;
  cont.classList.add('d-none');
  skel.classList.remove('d-none');
  setTimeout(() => {
    skel.classList.add('d-none');
    cont.classList.remove('d-none');
    cont.classList.add('animate-fade-up');
  }, delay);
}
