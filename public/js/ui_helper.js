/**
 * UI Helper Utility for Modern Modals and Toasts
 */

const UI = {
    /**
     * Show a centered modal with background blur
     * @param {Object} options - { title, message, type: 'error'|'success'|'warning', confirmText, cancelText, onConfirm }
     */
    showModal: function(options) {
        const overlay = $('#modal-overlay');
        const icon = overlay.find('.modal-icon');
        
        // Set content
        overlay.find('.modal-title').text(options.title || 'Notification');
        overlay.find('.modal-body').text(options.message || '');
        
        // Set type
        icon.removeClass('error success warning');
        if (options.type) icon.addClass(options.type);
        
        const iconClass = {
            'success': 'fas fa-check-circle',
            'error': 'fas fa-exclamation-circle',
            'warning': 'fas fa-exclamation-triangle'
        };
        icon.find('i').attr('class', iconClass[options.type] || iconClass['warning']);

        // Set buttons
        const confirmBtn = overlay.find('.btn-confirm');
        const cancelBtn = overlay.find('.btn-cancel-modal');
        
        confirmBtn.text(options.confirmText || 'OK').show().off('click');
        if (options.onConfirm) {
            confirmBtn.on('click', function() {
                UI.hideModal();
                options.onConfirm();
            });
        } else {
            confirmBtn.on('click', function() {
                UI.hideModal();
            });
        }

        if (options.cancelText) {
            cancelBtn.text(options.cancelText).show().off('click').on('click', function() {
                UI.hideModal();
            });
        } else {
            cancelBtn.hide();
        }

        overlay.addClass('active');
    },

    hideModal: function() {
        $('#modal-overlay').removeClass('active');
    },

    /**
     * Show a toast notification at top-right
     * @param {string} title 
     * @param {string} msg 
     * @param {string} type - 'success'|'error'
     * @param {number} duration - ms, default 4000
     */
    showToast: function(title, msg, type = 'success', duration = 4000) {
        const id = 'toast-' + Date.now();
        const icon = type === 'success' ? 'fas fa-check' : 'fas fa-times';
        
        const toastHtml = `
            <div id="${id}" class="toast ${type}">
                <div class="toast-icon ${type}"><i class="${icon}"></i></div>
                <div class="toast-content">
                    <div class="toast-title">${title}</div>
                    <div class="toast-msg">${msg}</div>
                </div>
            </div>
        `;
        
        $('#toast-container').append(toastHtml);
        
        const toast = $('#' + id);
        setTimeout(() => toast.addClass('active'), 10);
        
        setTimeout(() => {
            toast.removeClass('active');
            setTimeout(() => toast.remove(), 400);
        }, duration);
    }
};
