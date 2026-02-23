<div id="pwa-install-banner" class="position-fixed bottom-0 start-50 translate-middle-x mb-5 w-100 px-3"
    style="max-width: 480px; z-index: 9999; display: none;">
    <div class="card border-0 shadow-lg p-1 rounded-4 overflow-hidden" style="background-color: var(--bs-body-bg);">
        <div class="card-body p-3 p-sm-4 d-flex align-items-center gap-3">
            <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-3 flex-shrink-0 d-flex align-items-center justify-content-center"
                style="width: 48px; height: 48px;">
                <i class="bi bi-phone-vibrate fs-4"></i>
            </div>

            <div class="flex-grow-1">
                <h6 class="fw-bold mb-1" style="font-size: 0.95rem;">Install Aplikasi Abdinara</h6>
                <p class="text-body-secondary small mb-2" style="font-size: 0.8rem; line-height: 1.3;">Akses LMS lebih
                    cepat, stabil, tanpa harus buka browser tiap hari!</p>
                <div class="d-flex gap-2 mt-2">
                    <button type="button" id="pwa-install-btn"
                        class="btn btn-primary rounded-pill btn-sm px-4 fw-bold">
                        <i class="bi bi-download me-1"></i> Install
                    </button>
                    <button type="button" id="pwa-close-btn"
                        class="btn btn-outline-secondary rounded-pill btn-sm px-4">
                        Nanti
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        let deferredPrompt;
        const installBanner = document.getElementById('pwa-install-banner');
        const installBtn = document.getElementById('pwa-install-btn');
        const closeBtn = document.getElementById('pwa-close-btn');

        // Check if user previously dismissed
        const isDismissed = localStorage.getItem('pwa_prompt_dismissed') === 'true';

        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            if (!isDismissed) {
                installBanner.style.display = 'block';
            }
        });

        window.addEventListener('appinstalled', () => {
            installBanner.style.display = 'none';
            deferredPrompt = null;
        });

        installBtn.addEventListener('click', async () => {
            if (!deferredPrompt) return;
            deferredPrompt.prompt();
            const {
                outcome
            } = await deferredPrompt.userChoice;
            deferredPrompt = null;
            installBanner.style.display = 'none';
        });

        closeBtn.addEventListener('click', () => {
            installBanner.style.display = 'none';
            localStorage.setItem('pwa_prompt_dismissed', 'true');
        });
    });
</script>
