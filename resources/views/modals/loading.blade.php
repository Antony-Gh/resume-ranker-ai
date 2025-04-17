<!-- Loading Modal Backdrop -->
<div class="modal-backdrop" id="loading-backdrop" role="presentation" aria-hidden="true" style="display: none;"></div>

<!-- Loading Modal Dialog -->
<dialog id="loading-modal" aria-label="Loading Modal" aria-modal="false" class="modal-dialog loading-dialog"
    style="display: none;">
    <!-- Modal Content -->
    <div class="modal-container">
        <div class="modal-group container-xs loading-content">
            <!-- Loading Spinner -->
            <div class="loading-spinner" aria-live="polite" aria-busy="true">
                <svg class="animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    width="48" height="48">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
            </div>

            <!-- Loading Message -->
            <h2 class="loading-message ui heading size-text2xl">Processing your request...</h2>
        </div>
    </div>
</dialog>
