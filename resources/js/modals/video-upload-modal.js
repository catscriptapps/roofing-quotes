// /resources/js/modals/video-upload-modal.js

import { showToast } from '../ui/toast.js';

/**
 * Specialized Video Upload Modal 🍊
 * Handles large file streaming and progress tracking.
 */
export const videoUploadModal = (() => {
    let modalEl = null;

    function ensureModal() {
        if (modalEl) return modalEl;

        modalEl = document.createElement('div');
        modalEl.id = 'video-upload-modal';
        modalEl.className = 'fixed inset-0 z-[60] hidden';

        modalEl.innerHTML = `
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" data-backdrop></div>
            <div class="relative mx-auto mt-20 w-full max-w-xl px-4">
                <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl flex flex-col overflow-hidden border border-gray-200 dark:border-gray-800">
                    <header class="p-4 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between bg-gray-50/50 dark:bg-gray-800/50">
                        <div>
                            <h2 class="font-bold text-lg text-gray-800 dark:text-gray-100 font-sans">Upload Video</h2>
                            <p class="text-[10px] text-gray-500 uppercase tracking-widest font-sans">Max 50MB • MP4, MOV, WEBM</p>
                        </div>
                        <button class="p-2 text-gray-400 hover:text-red-500 transition-colors" data-close>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </header>

                    <div class="p-8">
                        <div data-drop-zone class="group border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-2xl p-10 flex flex-col items-center justify-center cursor-pointer hover:border-primary-500 hover:bg-primary-50/30 transition-all">
                            <div class="w-16 h-16 bg-primary-100 dark:bg-primary-900/30 text-primary-600 rounded-full flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                            </div>
                            <p class="text-gray-600 dark:text-gray-300 font-medium font-sans">Click to select video</p>
                            <input type="file" accept="video/*" class="hidden" data-file-input />
                        </div>

                        <div data-progress-container class="hidden mt-6 space-y-3">
                            <div class="flex justify-between text-xs font-sans">
                                <span class="text-gray-500 dark:text-gray-400" data-filename>video.mp4</span>
                                <span class="font-bold text-primary-600" data-percent>0%</span>
                            </div>
                            <div class="w-full bg-gray-100 dark:bg-gray-800 h-2 rounded-full overflow-hidden">
                                <div data-progress-bar class="bg-primary-500 h-full w-0 transition-all duration-300"></div>
                            </div>
                            <p class="text-[10px] text-center text-gray-400 font-sans animate-pulse">Uploading to server... please wait</p>
                        </div>
                    </div>
                </div>
            </div>
        `;

        document.body.appendChild(modalEl);
        return modalEl;
    }

    return {
        open: () => {
            const el = ensureModal();
            el.classList.remove('hidden');
            // Reset state on open
            el.querySelector('[data-drop-zone]').classList.remove('hidden');
            el.querySelector('[data-progress-container]').classList.add('hidden');
            el.querySelector('[data-progress-bar]').style.width = '0%';
        },
        close: () => modalEl?.classList.add('hidden'),
        getElement: () => ensureModal()
    };
})();

/**
 * The Video Upload Engine 🍊
 */
export function createVideoUploadHandler(endpointUrl, onComplete) {
    const modal = videoUploadModal.getElement();
    const input = modal.querySelector('[data-file-input]');
    const dropZone = modal.querySelector('[data-drop-zone]');
    const progressCont = modal.querySelector('[data-progress-container]');
    const progressBar = modal.querySelector('[data-progress-bar]');
    const percentText = modal.querySelector('[data-percent]');
    const filenameText = modal.querySelector('[data-filename]');

    // UI Trigger
    dropZone.onclick = () => input.click();

    input.onchange = async (e) => {
        const file = e.target.files[0];
        if (!file) return;

        // 🍊 Client-side pre-flight check
        const MAX_SIZE = 50 * 1024 * 1024; // 50MB
        if (file.size > MAX_SIZE) {
            showToast('Video too large (Max 50MB)', 'error');
            input.value = '';
            return;
        }

        // UI Transition to Progress Mode
        dropZone.classList.add('hidden');
        progressCont.classList.remove('hidden');
        filenameText.textContent = file.name;

        // XHR for Progress Tracking
        const fd = new FormData();
        fd.append('video', file); 

        const xhr = new XMLHttpRequest();
        xhr.open('POST', endpointUrl);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

        xhr.upload.onprogress = (event) => {
            if (event.lengthComputable) {
                const percent = Math.round((event.loaded / event.total) * 100);
                progressBar.style.width = percent + '%';
                percentText.textContent = percent + '%';
            }
        };

        xhr.onload = () => {
            try {
                const resp = JSON.parse(xhr.responseText);
                if (resp.success) {
                    const uploadedFiles = resp.files || resp.uploadedFiles || [];
                    
                    // Brief delay so the user sees the 100% completion 🍊
                    setTimeout(() => {
                        onComplete(uploadedFiles);
                        videoUploadModal.close();
                        showToast('Video ready!', 'success');
                    }, 500);
                } else {
                    showToast(resp.message || 'Upload failed', 'error');
                    resetModalUI();
                }
            } catch (err) {
                console.error("Server Error:", xhr.responseText);
                showToast('Server error during upload', 'error');
                resetModalUI();
            }
        };

        xhr.onerror = () => {
            showToast('Network error', 'error');
            resetModalUI();
        };

        xhr.send(fd);
    };

    function resetModalUI() {
        dropZone.classList.remove('hidden');
        progressCont.classList.add('hidden');
        input.value = '';
    }

    // Backdrop/Close setup
    modal.querySelector('[data-close]').onclick = () => videoUploadModal.close();
    modal.querySelector('[data-backdrop]').onclick = () => videoUploadModal.close();
}