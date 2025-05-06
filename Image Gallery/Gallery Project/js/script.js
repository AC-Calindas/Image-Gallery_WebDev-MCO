
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.createElement('div');
    modal.className = 'modal';
    modal.innerHTML = `
        <span class="close-modal">&times;</span>
        <div class="modal-content-container">
            <div class="modal-image-container">
                <div class="resolution-info" id="resolutionInfo"></div>
                <img class="modal-content" id="modalImage">
                <div class="zoom-controls">
                    <button class="zoom-btn" id="zoomIn">+</button>
                    <button class="zoom-btn" id="zoomOut">-</button>
                    <button class="zoom-btn" id="zoomReset">↻</button>
                </div>
            </div>
            <div class="modal-info">
                <h3 id="modalTitle"></h3>
                <p id="modalDescription"></p>
                <small id="modalDate"></small>
            </div>
        </div>
    `;
    document.body.appendChild(modal);

    // Zoom and pan variables
    let scale = 1;
    let posX = 0;
    let posY = 0;
    let isDragging = false;
    let startX, startY, translateX, translateY;
    const modalImg = document.getElementById('modalImage');
    const modalImageContainer = document.querySelector('.modal-image-container');

    // Set up gallery item click handlers
    document.querySelectorAll('.gallery-item').forEach(item => {
        item.addEventListener('click', function(e) {
            if (e.target.closest('.delete-btn')) return;
            
            const img = this.querySelector('img');
            const title = this.querySelector('h3').textContent;
            const description = this.querySelector('p').textContent;
            const date = this.querySelector('small').textContent;
            
            // Reset zoom and position for new image
            scale = 1;
            posX = 0;
            posY = 0;
            updateImageTransform();
            
            // Set modal content
            modalImg.src = img.src;
            document.getElementById('modalTitle').textContent = title;
            document.getElementById('modalDescription').textContent = description;
            document.getElementById('modalDate').textContent = date;
            
            // Display modal
            modal.style.display = "block";
            document.body.style.overflow = "hidden";
            
            // Display resolution after image loads
            const showResolution = () => {
                const resolutionInfo = document.getElementById('resolutionInfo');
                resolutionInfo.textContent = `${modalImg.naturalWidth} × ${modalImg.naturalHeight}px`;
            };
            
            if (modalImg.complete) {
                showResolution();
            } else {
                modalImg.onload = showResolution;
            }
        });
    });

    // Zoom functionality
    document.getElementById('zoomIn').addEventListener('click', () => {
        scale *= 1.2;
        updateImageTransform();
    });

    document.getElementById('zoomOut').addEventListener('click', () => {
        scale /= 1.2;
        if (scale < 1) {
            scale = 1;
            posX = 0;
            posY = 0;
        }
        updateImageTransform();
    });

    document.getElementById('zoomReset').addEventListener('click', () => {
        scale = 1;
        posX = 0;
        posY = 0;
        updateImageTransform();
    });

    // Mouse wheel zoom
    modalImageContainer.addEventListener('wheel', (e) => {
        e.preventDefault();
        
        // Get mouse position relative to image
        const rect = modalImageContainer.getBoundingClientRect();
        const mouseX = e.clientX - rect.left;
        const mouseY = e.clientY - rect.top;
        
        // Calculate zoom point before scaling
        const imgX = (mouseX - posX) / scale;
        const imgY = (mouseY - posY) / scale;
        
        // Apply zoom
        if (e.deltaY < 0) {
            scale *= 1.1;
        } else {
            scale /= 1.1;
            if (scale < 1) {
                scale = 1;
                posX = 0;
                posY = 0;
            }
        }
        
        // Calculate new position to zoom at mouse position
        posX = mouseX - imgX * scale;
        posY = mouseY - imgY * scale;
        
        updateImageTransform();
    });

    // Pan functionality
    modalImageContainer.addEventListener('mousedown', (e) => {
        if (scale <= 1) return;
        
        isDragging = true;
        startX = e.clientX;
        startY = e.clientY;
        translateX = posX;
        translateY = posY;
        modalImageContainer.style.cursor = 'grabbing';
    });

    document.addEventListener('mousemove', (e) => {
        if (!isDragging) return;
        
        const dx = e.clientX - startX;
        const dy = e.clientY - startY;
        
        posX = translateX + dx;
        posY = translateY + dy;
        
        updateImageTransform();
    });

    document.addEventListener('mouseup', () => {
        isDragging = false;
        modalImageContainer.style.cursor = 'grab';
    });

    // Touch events for mobile
    let touchStartX, touchStartY, touchStartDistance;
    let initialScale = 1, initialPosX = 0, initialPosY = 0;

    modalImageContainer.addEventListener('touchstart', (e) => {
        if (e.touches.length === 1) {
            // Single touch - prepare for panning
            const touch = e.touches[0];
            touchStartX = touch.clientX;
            touchStartY = touch.clientY;
            initialPosX = posX;
            initialPosY = posY;
        } else if (e.touches.length === 2) {
            // Two touches - prepare for pinch zoom
            const touch1 = e.touches[0];
            const touch2 = e.touches[1];
            touchStartDistance = Math.hypot(
                touch2.clientX - touch1.clientX,
                touch2.clientY - touch1.clientY
            );
            initialScale = scale;
        }
    }, { passive: false });

    modalImageContainer.addEventListener('touchmove', (e) => {
        e.preventDefault();
        
        if (e.touches.length === 1) {
            // Single touch - panning
            const touch = e.touches[0];
            const dx = touch.clientX - touchStartX;
            const dy = touch.clientY - touchStartY;
            
            posX = initialPosX + dx;
            posY = initialPosY + dy;
            
            updateImageTransform();
        } else if (e.touches.length === 2) {
            // Two touches - pinch zoom
            const touch1 = e.touches[0];
            const touch2 = e.touches[1];
            const currentDistance = Math.hypot(
                touch2.clientX - touch1.clientX,
                touch2.clientY - touch1.clientY
            );
            
            scale = initialScale * (currentDistance / touchStartDistance);
            
            // Calculate center point between two fingers
            const centerX = (touch1.clientX + touch2.clientX) / 2;
            const centerY = (touch1.clientY + touch2.clientY) / 2;
            
            const rect = modalImageContainer.getBoundingClientRect();
            const containerCenterX = centerX - rect.left;
            const containerCenterY = centerY - rect.top;
            
            // Adjust position to zoom at center point
            const imgX = (containerCenterX - initialPosX) / initialScale;
            const imgY = (containerCenterY - initialPosY) / initialScale;
            
            posX = containerCenterX - imgX * scale;
            posY = containerCenterY - imgY * scale;
            
            updateImageTransform();
        }
    }, { passive: false });

    function updateImageTransform() {
        modalImg.style.transform = `translate(${posX}px, ${posY}px) scale(${scale})`;
    }

    // Close modal handlers
    document.querySelector('.close-modal').addEventListener('click', closeModal);
    modal.addEventListener('click', function(e) {
        if (e.target === modal) closeModal();
    });

    function closeModal() {
        modal.style.display = "none";
        document.body.style.overflow = "auto";
    }

    // Close with ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === "Escape" && modal.style.display === "block") {
            closeModal();
        }
    });
});


// Client-side validation for password field
document.getElementById('upload-form').addEventListener('submit', function(e) {
    const passwordField = document.getElementById('admin_password');
    const errorMessage = document.getElementById('password-error');
    
    // Simple client-side validation (you still need server-side validation)
    if (passwordField.value.length < 3) {
        e.preventDefault();
        passwordField.classList.add('shake');
        errorMessage.style.display = 'block';
        
        // Remove shake class after animation completes
        setTimeout(() => {
            passwordField.classList.remove('shake');
        }, 500);
    }
});

// Show error message if it exists on page load
document.addEventListener('DOMContentLoaded', function() {
    const errorMessage = document.getElementById('password-error');
    if (errorMessage) {
        errorMessage.style.display = 'block';
    }
});