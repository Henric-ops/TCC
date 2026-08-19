document.addEventListener('DOMContentLoaded', () => {
    const perfilSelect = document.getElementById('perfil');
    const escolaSelect = document.getElementById('escola_id');
    const alunosSelect = document.getElementById('alunos');
    const parentescoSelect = document.getElementById('parentesco');
    const blocoResponsavel = document.getElementById('bloco-responsavel');

    if (!perfilSelect || !blocoResponsavel) {
        return;
    }

    const atualizarBlocoResponsavel = () => {
        const responsavelSelecionado = perfilSelect.value === 'responsavel';
        blocoResponsavel.hidden = !responsavelSelecionado;

        if (alunosSelect) {
            alunosSelect.disabled = !responsavelSelecionado;
        }

        if (parentescoSelect) {
            parentescoSelect.disabled = !responsavelSelecionado;
        }
    };

    perfilSelect.addEventListener('change', atualizarBlocoResponsavel);
    atualizarBlocoResponsavel();

    if (escolaSelect && alunosSelect) {
        const atualizarAlunos = () => {
            const escolaSelecionada = escolaSelect.value;

            Array.from(alunosSelect.options).forEach((option) => {
                const pertenceAEscola = option.dataset.escolaId === escolaSelecionada;
                option.hidden = !escolaSelecionada || !pertenceAEscola;

                if (!pertenceAEscola) {
                    option.selected = false;
                }
            });
        };

        escolaSelect.addEventListener('change', atualizarAlunos);
        atualizarAlunos();
    }
});
