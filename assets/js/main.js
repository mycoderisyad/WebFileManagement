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
            setTimeout(() => message.style.display = 'none', 300);
        }, 5000);

        const closeBtn = message.querySelector('.close-btn');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                message.style.opacity = '0';
                setTimeout(() => message.style.display = 'none', 300);
            });
        }
    });

    initializeConfirmDialog();
    initializeCategorySelection();
    initializeMobileNavigation();
    setupDeleteConfirmation();
});

function initializeConfirmDialog() {
    const confirmOverlay = document.getElementById('confirmOverlay');
    if (!confirmOverlay) return;

    const confirmMessage = document.getElementById('confirmMessage');
    const confirmCancel = document.getElementById('confirmCancel');
    const confirmOk = document.getElementById('confirmOk');
    let pendingCallback = null;
    
    window.showConfirm = function(message, callback) {
        confirmMessage.textContent = message;
        confirmOverlay.classList.add('active');
        pendingCallback = callback;
    };
    
    const closeDialog = (result = false) => {
        confirmOverlay.classList.remove('active');
        if (pendingCallback) pendingCallback(result);
        pendingCallback = null;
    };
    
    confirmCancel.addEventListener('click', () => closeDialog(false));
    confirmOk.addEventListener('click', () => closeDialog(true));
    confirmOverlay.addEventListener('click', e => {
        if (e.target === confirmOverlay) closeDialog(false);
    });
}

function toggleFileMenu(event, fileId) {
    event.stopPropagation();
    
    document.querySelectorAll('.file-dropdown').forEach(menu => {
        if (menu.id !== `menu-${fileId}`) {
            menu.classList.remove('show');
        }
    });
    
    const menu = document.getElementById(`menu-${fileId}`);
    if (menu) menu.classList.toggle('show');
}

function initializeCategorySelection() {
    const categorySelect = document.getElementById('category');
    if (!categorySelect) return;

    const handleCategoryChange = function() {
        const newCategoryGroup = document.querySelector('.new-category-group');
        const newCategoryInput = document.getElementById('new_category');
        
        if (newCategoryGroup && newCategoryInput) {
            const isNewCategory = this.value === 'new_category';
            newCategoryGroup.style.display = isNewCategory ? 'block' : 'none';
            
            if (isNewCategory) {
                newCategoryInput.setAttribute('required', 'required');
            } else {
                newCategoryInput.removeAttribute('required');
                newCategoryInput.value = '';
            }
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

function initializeMobileNavigation() {
    const navToggle = document.getElementById('navToggle');
    const navMobile = document.getElementById('navMobile');
    
    if (!navToggle || !navMobile) return;
    
    navToggle.addEventListener('click', () => {
        navToggle.classList.toggle('active');
        navMobile.classList.toggle('active');
    });
    
    // Close mobile nav when clicking on links
    navMobile.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => {
            navToggle.classList.remove('active');
            navMobile.classList.remove('active');
        });
    });
}

// Close dropdown menus when clicking outside
document.addEventListener('click', function(event) {
    if (!event.target.closest('.file-menu')) {
        document.querySelectorAll('.file-dropdown').forEach(menu => {
            menu.classList.remove('show');
        });
    }
});

function setupDeleteConfirmation() {
    document.querySelectorAll('a[data-confirm]').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const msg = this.getAttribute('data-confirm');
            
            if (window.showConfirm) {
                window.showConfirm(msg, result => {
                    if (result) {
                        window.location.href = this.href;
                    }
                });
            } else {
                if (confirm(msg)) {
                    window.location.href = this.href;
                }
            }
        });
    });
}