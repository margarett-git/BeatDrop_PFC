(() => {
    const root = document.querySelector('[data-admin-root]');
    if (!root) return;

    const viewSections = Array.from(document.querySelectorAll('[data-view-section]'));
    const viewLinks = Array.from(document.querySelectorAll('[data-view-link]'));

    function showView(view) {
        for (const section of viewSections) {
            const isActive = section.getAttribute('data-view-section') === view;
            section.classList.toggle('is-active', isActive);
        }
        for (const link of viewLinks) {
            const isActive = link.getAttribute('data-view-link') === view;
            link.classList.toggle('is-active', isActive);
        }
        try {
            localStorage.setItem('beatdrop_admin_view', view);
        } catch (_) {}
    }

    function viewFromHash() {
        const hash = (window.location.hash || '').replace('#', '').trim();
        if (!hash) return null;
        return hash;
    }

    const initial = viewFromHash()
        || (() => {
            try { return localStorage.getItem('beatdrop_admin_view'); } catch (_) { return null; }
        })()
        || 'dashboard';
    showView(initial);

    window.addEventListener('hashchange', () => {
        const v = viewFromHash();
        if (v) showView(v);
    });

    document.addEventListener('click', (e) => {
        const target = e.target.closest('[data-view-link]');
        if (!target) return;
        const view = target.getAttribute('data-view-link');
        if (!view) return;
        e.preventDefault();
        window.location.hash = view;
        showView(view);
    });

    document.addEventListener('click', (e) => {
        const btn = e.target.closest('[data-action="go-view"]');
        if (!btn) return;
        const view = btn.getAttribute('data-target') || 'dashboard';
        window.location.hash = view;
        showView(view);
    });

    // Modal (Productos)
    const modal = document.getElementById('productModal');
    const form = document.getElementById('productForm');
    const modalTitle = document.getElementById('productModalTitle');
    const submitBtn = document.getElementById('productSubmit');
    const deleteForm = document.getElementById('productDeleteForm');

    const fieldIdCategoria = document.getElementById('fieldIdCategoria');
    const fieldNombre = document.getElementById('fieldNombre');
    const fieldImagen = document.getElementById('fieldImagen');
    const fieldDescripcion = document.getElementById('fieldDescripcion');
    const fieldPrecio = document.getElementById('fieldPrecio');
    const fieldStock = document.getElementById('fieldStock');
    const fieldGenero = document.getElementById('fieldGenero');
    const fieldFormato = document.getElementById('fieldFormato');

    function openModal() {
        if (!modal) return;
        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('modal-open');
        setTimeout(() => fieldNombre?.focus(), 0);
    }

    function closeModal() {
        if (!modal) return;
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('modal-open');
    }

    function setModeCreate() {
        modalTitle.textContent = 'Añadir producto';
        submitBtn.textContent = 'Guardar';
        form.setAttribute('action', '/admin/productos/crear');
        deleteForm?.classList.add('is-hidden');
        if (deleteForm) deleteForm.setAttribute('action', '#');
        fieldNombre.value = '';
        fieldImagen.value = '';
        fieldDescripcion.value = '';
        fieldPrecio.value = '0';
        fieldStock.value = '0';
        fieldGenero.value = '';
        fieldFormato.value = '';
    }

    function setModeEdit(product) {
        modalTitle.textContent = 'Editar producto';
        submitBtn.textContent = 'Guardar cambios';
        form.setAttribute('action', `/admin/productos/editar/${product.id_producto}`);
        deleteForm?.classList.remove('is-hidden');
        if (deleteForm) deleteForm.setAttribute('action', `/admin/productos/eliminar/${product.id_producto}`);

        fieldNombre.value = product.nombre || '';
        fieldImagen.value = product.imagen_url || '';
        fieldDescripcion.value = product.descripcion || '';
        fieldPrecio.value = String(product.precio ?? 0);
        fieldStock.value = String(product.stock ?? 0);
        fieldGenero.value = product.genero || '';
        fieldFormato.value = product.formato || '';
    }


    document.addEventListener('click', (e) => {
        const openBtn = e.target.closest('[data-action="open-product-modal"]');
        if (!openBtn) return;
        setModeCreate();
        openModal();
    });

    document.addEventListener('click', (e) => {
        const editBtn = e.target.closest('[data-action="edit-product"]');
        if (!editBtn) return;
        const raw = editBtn.getAttribute('data-product') || '{}';
        let product;
        try {
            product = JSON.parse(raw);
        } catch (_) {
            product = {};
        }
        setModeEdit(product);
        openModal();
    });

    document.addEventListener('click', (e) => {
        const closeBtn = e.target.closest('[data-action="close-product-modal"]');
        if (closeBtn) closeModal();
    });

    modal?.addEventListener('click', (e) => {
        if (e.target === modal) closeModal();
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && modal?.classList.contains('is-open')) closeModal();
    });

    // Stock stepper (modal)
    document.addEventListener('click', (e) => {
        const stepBtn = e.target.closest('[data-step]');
        if (!stepBtn) return;
        const delta = parseInt(stepBtn.getAttribute('data-step'), 10) || 0;
        const current = parseInt(fieldStock.value || '0', 10) || 0;
        const next = Math.max(0, current + delta);
        fieldStock.value = String(next);
        fieldStock.focus();
    });

    // Stock buttons in table (real-time update)
    document.addEventListener('click', async (e) => {
        const stockBtn = e.target.closest('[data-action="stock-change"]');
        if (!stockBtn) return;
        
        const row = stockBtn.closest('[data-product-id]');
        if (!row) return;
        
        const productId = row.getAttribute('data-product-id');
        const delta = parseInt(stockBtn.getAttribute('data-delta'), 10) || 0;
        
        if (delta === 0) return;
        
        const stockSpan = row.querySelector('.stock-value');
        if (!stockSpan) return;
        
        const currentStock = parseInt(stockSpan.textContent.trim(), 10) || 0;
        const newStock = Math.max(0, currentStock + delta);
        
        // Update UI immediately
        stockSpan.textContent = String(newStock);
        
        // Update classes
        stockSpan.classList.remove('stock-ok', 'stock-low', 'stock-out');
        if (newStock <= 0) stockSpan.classList.add('stock-out');
        else if (newStock <= 3) stockSpan.classList.add('stock-low');
        else stockSpan.classList.add('stock-ok');
        
        // Send update to server - use 'cantidad' (delta) as expected by controller
        try {
            const response = await fetch('/admin/productos/agregarStock/' + productId, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    cantidad: delta
                })
            });
            
            const result = await response.json();
            
            if (!response.ok || result.error) {
                // Revert on error
                stockSpan.textContent = String(currentStock);
                stockSpan.classList.remove('stock-ok', 'stock-low', 'stock-out');
                if (currentStock <= 0) stockSpan.classList.add('stock-out');
                else if (currentStock <= 3) stockSpan.classList.add('stock-low');
                else stockSpan.classList.add('stock-ok');
                alert(result.error || 'Error al actualizar el stock');
            }
        } catch (error) {
            // Revert on error
            stockSpan.textContent = String(currentStock);
            stockSpan.classList.remove('stock-ok', 'stock-low', 'stock-out');
            if (currentStock <= 0) stockSpan.classList.add('stock-out');
            else if (currentStock <= 3) stockSpan.classList.add('stock-low');
            else stockSpan.classList.add('stock-ok');
            console.error('Error updating stock:', error);
            alert('Error de conexión al actualizar el stock');
        }
    });

    // Delete product with confirm
    document.addEventListener('click', (e) => {
        const deleteBtn = e.target.closest('[data-action="delete-product"]');
        if (!deleteBtn) return;
        
        const productId = deleteBtn.getAttribute('data-product-id');
        if (!productId) return;
        
        if (confirm('¿Estás seguro de que deseas eliminar este producto? Esta acción no se puede deshacer.')) {
            // Create a form and submit it (standard PHP form submission)
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/admin/productos/eliminar/' + productId;
            
            // Add CSRF token if available
            const csrfInput = document.querySelector('meta[name="csrf-token"]');
            if (csrfInput) {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'csrf_token';
                hiddenInput.value = csrfInput.getAttribute('content');
                form.appendChild(hiddenInput);
            }
            
            document.body.appendChild(form);
            form.submit();
        }
    });

    // Table sorting logic
    document.querySelectorAll('th.sortable').forEach(th => {
        th.addEventListener('click', () => {
            const table = th.closest('table');
            const tbody = table.querySelector('tbody');
            const rows = Array.from(tbody.querySelectorAll('tr[data-product-id]'));
            if (!rows.length) return;
            
            const type = th.dataset.sort;
            
            // Toggle direction
            const isAsc = th.classList.contains('asc');
            table.querySelectorAll('th.sortable').forEach(h => h.classList.remove('asc', 'desc'));
            th.classList.add(isAsc ? 'desc' : 'asc');
            const direction = isAsc ? -1 : 1;
            
            rows.sort((a, b) => {
                let valA, valB;
                
                // Parse values based on sort type
                switch(type) {
                    case 'id':
                        valA = parseInt(a.querySelector('td:nth-child(1)').textContent.replace(/\/|\#/g, ''));
                        valB = parseInt(b.querySelector('td:nth-child(1)').textContent.replace(/\/|\#/g, ''));
                        break;
                    case 'nombre':
                        valA = a.querySelector('td:nth-child(2)').textContent.trim().toLowerCase();
                        valB = b.querySelector('td:nth-child(2)').textContent.trim().toLowerCase();
                        break;
                    case 'categoria':
                        valA = a.querySelector('td:nth-child(3)').textContent.trim().toLowerCase();
                        valB = b.querySelector('td:nth-child(3)').textContent.trim().toLowerCase();
                        break;
                    case 'precio':
                        valA = parseFloat(a.querySelector('td:nth-child(4)').textContent.replace(/[^\d.,]/g, '').replace(',', '.'));
                        valB = parseFloat(b.querySelector('td:nth-child(4)').textContent.replace(/[^\d.,]/g, '').replace(',', '.'));
                        break;
                    case 'stock':
                        valA = parseInt(a.querySelector('.stock-value').textContent.replace(/[^\d]/g, ''));
                        valB = parseInt(b.querySelector('.stock-value').textContent.replace(/[^\d]/g, ''));
                        break;
                    default:
                        return 0;
                }
                
                if (valA < valB) return -1 * direction;
                if (valA > valB) return 1 * direction;
                return 0;
            });
            
            // Re-append rows in new order
            rows.forEach(row => tbody.appendChild(row));
        });
    });

    // Pedido state colors
    function syncStatusSelect(select) {
        const v = select.value;
        select.setAttribute('data-state', v);
    }
    const statusSelects = Array.from(document.querySelectorAll('[data-status]'));
    for (const s of statusSelects) syncStatusSelect(s);
    document.addEventListener('change', (e) => {
        const sel = e.target.closest('[data-status]');
        if (!sel) return;
        syncStatusSelect(sel);
    });
})();
