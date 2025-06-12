document.addEventListener('DOMContentLoaded', function() {
    // Atualiza o contador do carrinho no navbar
    updateCartCount();

    // Adiciona evento de clique para os botões "Adicionar ao Carrinho"
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form');
            addToCart(form);
        });
    });

    // Validação do formulário de checkout
    const checkoutForm = document.getElementById('checkout-form');
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function(e) {
            if (!validateCheckoutForm()) {
                e.preventDefault();
            }
        });
    }

    // Máscara para telefone
    const phoneInput = document.getElementById('telefone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '')
                .replace(/^(\d{2})(\d)/g, '($1) $2')
                .replace(/(\d)(\d{4})$/, '$1-$2');
        });
    }

    // Atualização dinâmica de quantidade no carrinho
    document.querySelectorAll('.cart-quantity').forEach(input => {
        input.addEventListener('change', function() {
            updateCartItem(this);
        });
    });
});

// Função para atualizar o contador do carrinho
function updateCartCount() {
    fetch('api/cart_count.php')
        .then(response => response.json())
        .then(data => {
            const cartCountElements = document.querySelectorAll('.cart-count');
            cartCountElements.forEach(element => {
                element.textContent = data.count;
                element.style.display = data.count > 0 ? 'inline-block' : 'none';
            });
        })
        .catch(error => console.error('Error:', error));
}

// Função para adicionar item ao carrinho via AJAX
function addToCart(form) {
    const formData = new FormData(form);
    
    fetch('carrinho.php?action=add', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(() => {
        updateCartCount();
        showToast('Produto adicionado ao carrinho!');
    })
    .catch(error => console.error('Error:', error));
}

// Função para atualizar item no carrinho via AJAX
function updateCartItem(input) {
    const form = input.closest('form');
    const formData = new FormData(form);
    
    fetch('carrinho.php?action=update', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateCartCount();
            // Atualiza o subtotal na linha
            const row = input.closest('tr');
            row.querySelector('.subtotal').textContent = 'R$ ' + data.subtotal.toFixed(2).replace('.', ',');
            // Atualiza o total
            document.querySelector('.cart-total').textContent = 'R$ ' + data.total.toFixed(2).replace('.', ',');
            showToast('Carrinho atualizado!');
        }
    })
    .catch(error => console.error('Error:', error));
}

// Validação do formulário de checkout
function validateCheckoutForm() {
    let isValid = true;
    const form = document.getElementById('checkout-form');
    
    // Valida nome
    const nome = form.querySelector('#nome');
    if (nome.value.trim() === '') {
        showError(nome, 'Por favor, informe seu nome completo');
        isValid = false;
    } else {
        clearError(nome);
    }
    
    // Valida email
    const email = form.querySelector('#email');
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email.value)) {
        showError(email, 'Por favor, informe um e-mail válido');
        isValid = false;
    } else {
        clearError(email);
    }
    
    // Valida telefone
    const telefone = form.querySelector('#telefone');
    const telefoneDigits = telefone.value.replace(/\D/g, '');
    if (telefoneDigits.length < 10 || telefoneDigits.length > 11) {
        showError(telefone, 'Por favor, informe um telefone válido');
        isValid = false;
    } else {
        clearError(telefone);
    }
    
    // Valida endereço
    const endereco = form.querySelector('#endereco');
    if (endereco.value.trim() === '') {
        showError(endereco, 'Por favor, informe seu endereço completo');
        isValid = false;
    } else {
        clearError(endereco);
    }
    
    // Valida método de pagamento
    const metodoPagamento = form.querySelector('input[name="metodo_pagamento"]:checked');
    if (!metodoPagamento) {
        const firstRadio = form.querySelector('input[name="metodo_pagamento"]');
        showError(firstRadio.closest('.form-group'), 'Por favor, selecione um método de pagamento');
        isValid = false;
    } else {
        clearError(metodoPagamento.closest('.form-group'));
    }
    
    return isValid;
}

// Mostra mensagem de erro
function showError(element, message) {
    const formGroup = element.closest('.form-group');
    let errorElement = formGroup.querySelector('.error-message');
    
    if (!errorElement) {
        errorElement = document.createElement('div');
        errorElement.className = 'error-message text-danger small';
        formGroup.appendChild(errorElement);
    }
    
    errorElement.textContent = message;
    element.classList.add('is-invalid');
}

// Remove mensagem de erro
function clearError(element) {
    const formGroup = element.closest('.form-group');
    const errorElement = formGroup.querySelector('.error-message');
    
    if (errorElement) {
        errorElement.remove();
    }
    
    element.classList.remove('is-invalid');
}

// Mostra toast notification
function showToast(message) {
    // Cria o elemento toast se não existir
    let toastContainer = document.querySelector('.toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.className = 'toast-container position-fixed bottom-0 end-0 p-3';
        document.body.appendChild(toastContainer);
    }
    
    const toast = document.createElement('div');
    toast.className = 'toast show align-items-center text-white bg-success';
    toast.setAttribute('role', 'alert');
    toast.setAttribute('aria-live', 'assertive');
    toast.setAttribute('aria-atomic', 'true');
    
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">${message}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    `;
    
    toastContainer.appendChild(toast);
    
    // Remove o toast após 3 segundos
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Função para alternar a exibição do carrinho (mobile)
function toggleCart() {
    const cart = document.querySelector('.cart-sidebar');
    cart.classList.toggle('show');
}