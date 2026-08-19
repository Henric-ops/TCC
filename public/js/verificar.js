document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-confirm-rejection]').forEach((button) => {
        button.addEventListener('click', (event) => {
            if (!window.confirm('Recusar o cadastro deste usuário?')) {
                event.preventDefault();
            }
        });
    });
});
