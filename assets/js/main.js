// Auto-hide notifications
document.addEventListener('DOMContentLoaded', function() {
    if (window.location.search.includes('message=')) {
        let url = new URL(window.location);
        url.searchParams.delete('message');
        window.history.replaceState({}, '', url);
    }
    
    const messages = document.querySelectorAll('.message, .error-container');
    messages.forEach(message => {
        setTimeout(() => {
            message.style.opacity = '0';
            setTimeout(() => {
                message.style.display = 'none';
            }, 300);
        }, 5000);

        const closeBtn = message.querySelector('.close-btn');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                message.style.opacity = '0';
                setTimeout(() => {
                    message.style.display = 'none';
                }, 300);
            });
        }
    });
});

function initializeViewToggle() {
    const gridViewBtn = document.getElementById('grid-view');
    const listViewBtn = document.getElementById('list-view');
    
    if (gridViewBtn && listViewBtn) {
        gridViewBtn.addEventListener('click', function() {
            document.querySelector('.files-container').classList.remove('list');
            document.querySelector('.files-container').classList.add('grid');
            this.classList.add('active');
            listViewBtn.classList.remove('active');
        });
        
        listViewBtn.addEventListener('click', function() {
            document.querySelector('.files-container').classList.remove('grid');
            document.querySelector('.files-container').classList.add('list');
            this.classList.add('active');
            gridViewBtn.classList.remove('active');
        });
    }
}

function initializeDeleteConfirmation() {
    const deleteButtons = document.querySelectorAll('.btn-delete');
    const overlay = document.querySelector('.confirm-overlay');
    
    if (overlay && deleteButtons.length > 0) {
        const messageBox = overlay.querySelector('.confirm-message');
        const cancelBtn = overlay.querySelector('.confirm-btn-cancel');
        const confirmBtn = overlay.querySelector('.confirm-btn-confirm');
        let deleteUrl = null;

        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                messageBox.textContent = this.getAttribute('data-confirm') || 'Are you sure you want to delete this file?';
                deleteUrl = this.getAttribute('href');
                overlay.classList.add('active');
            });
        });

        cancelBtn.onclick = function() {
            overlay.classList.remove('active');
        };
        
        confirmBtn.onclick = function() {
            if (deleteUrl) window.location.href = deleteUrl;
        };
        
        overlay.onclick = function(e) {
            if (e.target === overlay) {
                overlay.classList.remove('active');
            }
        };
    }
}

function initializeCustomConfirmDialog() {
    const confirmOverlay = document.getElementById('confirmOverlay');
    if (confirmOverlay) {
        const confirmMessage = document.getElementById('confirmMessage');
        const confirmCancel = document.getElementById('confirmCancel');
        const confirmOk = document.getElementById('confirmOk');
        let pendingCallback = null;
        
        // Replace all default confirm dialogs with custom one
        document.addEventListener('click', function(e) {
            const deleteLink = e.target.closest('a[data-confirm]');
            if (deleteLink) {
                e.preventDefault();
                const msg = deleteLink.getAttribute('data-confirm');
                showConfirm(msg, function(result) {
                    if (result) {
                        window.location.href = deleteLink.href;
                    }
                });
                return false;
            }
        });
        
        window.showConfirm = function(message, callback) {
            confirmMessage.textContent = message;
            confirmOverlay.classList.add('active');
            pendingCallback = callback;
        };
        
        confirmCancel.addEventListener('click', function() {
            confirmOverlay.classList.remove('active');
            if (pendingCallback) pendingCallback(false);
            pendingCallback = null;
        });
        
        confirmOk.addEventListener('click', function() {
            confirmOverlay.classList.remove('active');
            if (pendingCallback) pendingCallback(true);
            pendingCallback = null;
        });
        
        confirmOverlay.addEventListener('click', function(e) {
            if (e.target === confirmOverlay) {
                confirmOverlay.classList.remove('active');
                if (pendingCallback) pendingCallback(false);
                pendingCallback = null;
            }
        });
    }
}

function initializeCategorySelection() {
    const categorySelect = document.getElementById('category');
    if (categorySelect) {
        const handleCategoryChange = function() {
            const newCategoryGroup = document.querySelector('.new-category-group');
            const newCategoryInput = document.getElementById('new_category');
            
            if (this.value === 'new_category') {
                newCategoryGroup.style.display = 'block';
                newCategoryInput.setAttribute('required', 'required');
            } else {
                newCategoryGroup.style.display = 'none';
                newCategoryInput.removeAttribute('required');
            }
        };

        categorySelect.addEventListener('change', handleCategoryChange);

        if (categorySelect.value === 'new_category') {
            const newCategoryGroup = document.querySelector('.new-category-group');
            const newCategoryInput = document.getElementById('new_category');
            if (newCategoryGroup && newCategoryInput) {
                newCategoryGroup.style.display = 'block';
                newCategoryInput.setAttribute('required', 'required');
            }
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    initializeViewToggle();
    initializeDeleteConfirmation();
    initializeCustomConfirmDialog();
    initializeCategorySelection();
}); 