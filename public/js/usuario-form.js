document.addEventListener("DOMContentLoaded", () => {
    const perfilSelect = document.getElementById("perfil");
    const escolaSelect = document.getElementById("escola_id");
    const alunosLista = document.getElementById("alunos-lista");
    const parentescoSelect = document.getElementById("parentesco");
    const blocoResponsavel = document.getElementById("bloco-responsavel");

    if (!perfilSelect || !blocoResponsavel) {
        return;
    }

    const atualizarBlocoResponsavel = () => {
        const responsavelSelecionado = perfilSelect.value === "responsavel";
        blocoResponsavel.hidden = !responsavelSelecionado;

        if (alunosLista) {
            alunosLista
                .querySelectorAll('input[name="alunos[]"]')
                .forEach((checkbox) => {
                    checkbox.disabled = !responsavelSelecionado;
                });
        }

        if (parentescoSelect) {
            parentescoSelect.disabled = !responsavelSelecionado;
        }
    };

    perfilSelect.addEventListener("change", atualizarBlocoResponsavel);
    atualizarBlocoResponsavel();
});
