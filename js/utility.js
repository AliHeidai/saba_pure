var ut = {
    hideModal(id) {
       
       console.log(id);
        document.getElementById(id).remove();
    },
    showModal(options) {
        const defaults = {
            id: 'dynamicModal',
            size: 'md',
            backdrop: false,
            keyboard: false,
            footer: '',
            onShow: null,
            onShown: null,
            onHide: null,
            onHidden: null
        };
        const config = { ...defaults, ...options };
        const existingModal = document.getElementById(config.id);
        if (existingModal) {
            existingModal.remove();
        }
        const modalHTML = `
            <div style="background:rgba(128,128,128,0.5);backdrop-filter: blur(5px)" class="modal fade" id="${config.id}" tabindex="-1" aria-labelledby="${config.id}Label" aria-hidden="true">
                <div style="max-width:700px" class="modal-dialog ${config.size ? 'modal-' + config.size : ''}">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="${config.id}Label">${config.title}</h5>
                            <button type="button" id="${config.id}_close" class="btn btn text-danger btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
                        </div>
                        <div class="modal-body">
                            ${config.body}
                        </div>
                        ${config.footer ? `
                        <div class="modal-footer">
                            ${config.footer}
                        </div>
                        ` : ''}
                    </div>
                </div>
            </div>
        `;
        document.body.insertAdjacentHTML('beforeend', modalHTML);
        
        // Get modal element
        const modalElement = document.getElementById(config.id);
        const modal = new bootstrap.Modal(modalElement, {
            backdrop: config.backdrop,
            keyboard: config.keyboard
        });
        
        // Add event listeners
        if (config.onShow) {
            modalElement.addEventListener('show.bs.modal', config.onShow);
        }
        if (config.onShown) {
            modalElement.addEventListener('shown.bs.modal', config.onShown);
        }
        if (config.onHide) {
            modalElement.addEventListener('hide.bs.modal', config.onHide);
        }
        if (config.onHidden) {
            modalElement.addEventListener('hidden.bs.modal', config.onHidden);
        }
        
        // استفاده از Arrow Function برای حفظ this
        modalElement.addEventListener('click', (event) => {
            if (event.target.id === `${config.id}_close`) {
                this.hideModal(`${config.id}`); // حالا this به ut اشاره می‌کند
            }
        });
        
        // Show modal
        modal.show();
        return modal;
    },
    
};