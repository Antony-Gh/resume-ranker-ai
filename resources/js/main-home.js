

// Loading Dialog Controller
class LoadingDialog {
    constructor() {
        this.backdrop = document.getElementById('loading-backdrop');
        this.dialog = document.getElementById('loading-modal');
        this.isOpen = false;
    }
    
    show(message = 'Processing your request...') {
        if (this.isOpen) return;
        
        // Set message if provided
        const messageEl = this.dialog.querySelector('.loading-message');
        if (messageEl) {
            messageEl.textContent = message;
        }
        
        // Show dialog
        this.backdrop.style.display = 'block';
        this.dialog.style.display = 'block';
        this.dialog.showModal();
        this.dialog.setAttribute('aria-modal', 'true');
        document.body.style.overflow = 'hidden';
        this.isOpen = true;
    }
    
    hide() {
        if (!this.isOpen) return;
        
        // Hide dialog
        this.dialog.close();
        this.dialog.setAttribute('aria-modal', 'false');
        this.backdrop.style.display = 'none';
        this.dialog.style.display = 'none';
        document.body.style.overflow = '';
        this.isOpen = false;
    }
}

// Initialize loading dialog
const loadingDialog = new LoadingDialog();

const logout = document.getElementById('logout-button');
const baseUrl = document.querySelector('meta[name="base-url"]').content;

// Handle server response
async function handleServerResponse(response) {

    if (response.data.success) {
        localStorage.clear();
        window.location.href = "/";
        return;
    }

    throw new Error('Failed to logout');
}


document.addEventListener("DOMContentLoaded", function () {
    // Form submission
    logout.addEventListener("click", async function (e) {
        loadingDialog.show();
        e.preventDefault();
        // Send request
        try {
            // Send request using axios
            const response = await axios.post(`${baseUrl}/logout`);

            await handleServerResponse(response);
        } catch (error) {
            alert('Failed to logout');
            console.error("Error:", error);
        } finally {
            loadingDialog.hide();
        }
    });
});