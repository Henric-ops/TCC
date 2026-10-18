document.addEventListener("DOMContentLoaded", () => {
    const busca = document.getElementById("buscar-aluno");
    const lista = document.getElementById("alunos-lista");
    const semResultado = document.getElementById("alunos-sem-resultado");
    const escola = document.getElementById("escola_id");

    if (!busca || !lista) return;

    const alunos = [...lista.querySelectorAll(".aluno-item")];

    const atualizarAlunos = () => {
        const termo = busca.value.trim().toLocaleLowerCase("pt-BR");
        const escolaId = escola?.value || "";
        let encontrados = 0;

        alunos.forEach((aluno) => {
            const nome = aluno.dataset.nome.toLocaleLowerCase("pt-BR");
            const checkbox = aluno.querySelector('input[name="alunos[]"]');
            const pertenceAEscola = aluno.dataset.escolaId === escolaId;
            const corresponde =
                escolaId !== "" && pertenceAEscola && nome.includes(termo);

            aluno.hidden = !corresponde;
            checkbox.checked = checkbox.checked && pertenceAEscola;

            if (corresponde) encontrados++;
        });

        if (semResultado) {
            semResultado.textContent = escolaId
                ? "Nenhum aluno encontrado."
                : "Selecione uma escola para ver os alunos.";
            semResultado.hidden = encontrados > 0 || alunos.length === 0;
        }
    };

    busca.addEventListener("input", atualizarAlunos);
    escola?.addEventListener("change", atualizarAlunos);
    atualizarAlunos();
});
