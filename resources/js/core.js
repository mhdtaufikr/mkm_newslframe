import './bootstrap';

import '@fortawesome/fontawesome-free/css/all.css';
import 'flowbite'

import Alpine from 'alpinejs';
import Swal from 'sweetalert2';

if (!window.Alpine) {
    window.Alpine = Alpine;
    Alpine.start();
}

window.addEventListener('alert', (event) => {
    const type = event?.detail?.type || 'info';
    const message = event?.detail?.message || '';

    if (!message) {
        return;
    }

    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: type,
        title: message,
        timer: 2000,
        timerProgressBar: true,
        showConfirmButton: false,
    });
});

window.addEventListener('confirm', (event) => {
    const details = event?.detail || {};
    const id = details.id;
    const title = details.title || 'Are you sure?';
    const message = details.message || 'This action cannot be undone.';
    const confirmText = details.confirmText || 'Yes, delete it';
    const cancelText = details.cancelText || 'Cancel';
    const type = details.type || 'warning';

    Swal.fire({
        title,
        text: message,
        icon: type,
        showCancelButton: true,
        confirmButtonText: confirmText,
        cancelButtonText: cancelText,
        reverseButtons: true,
    }).then((result) => {
        if (!result.isConfirmed) {
            return;
        }

        if (window.Livewire?.dispatch) {
            window.Livewire.dispatch('deleteConfirmed', { id });
            return;
        }

        if (window.Livewire?.emit) {
            window.Livewire.emit('deleteConfirmed', id);
            return;
        }

        window.dispatchEvent(
            new CustomEvent('alert', {
                detail: { type: 'error', message: 'Livewire not ready' },
            })
        );
    });
});

document.addEventListener(
    'click',
    (event) => {
        const button = event.target.closest('[data-prevent-double]');
        if (!button) {
            return;
        }

        if (button.dataset.processing === '1') {
            event.preventDefault();
            event.stopImmediatePropagation();
            return;
        }

        button.dataset.processing = '1';
        button.setAttribute('disabled', 'disabled');
        button.classList.add('opacity-60', 'cursor-not-allowed');

        window.setTimeout(() => {
            button.dataset.processing = '0';
            button.removeAttribute('disabled');
            button.classList.remove('opacity-60', 'cursor-not-allowed');
        }, 1500);
    },
    true
);
