document.addEventListener("DOMContentLoaded", () => {
    const escolaSelect = document.getElementById("escola_id");
    const professorLabels = document.querySelectorAll(".usuario-form-checkbox");
    const permiteProfessoresDeOutrasEscolas = document.querySelector(
        "[data-professores-sem-restricao-escola]",
    );

    if (!escolaSelect) {
        return;
    }

    function atualizarProfessoresPorEscola() {
        const escolaId = escolaSelect.value;

        professorLabels.forEach((label) => {
            const input = label.querySelector('input[type="checkbox"]');

            const escolasDoProfessor = label.dataset.escolas
                ? label.dataset.escolas.split(",")
                : [];

            const pertence = escolasDoProfessor.includes(escolaId);

            // Não esconde ninguém
            label.style.display = "";

            // Mantém vinculados disponíveis para que possam ser desmarcados.
            input.disabled =
                !permiteProfessoresDeOutrasEscolas &&
                escolaId !== "" &&
                !pertence;
        });
    }

    escolaSelect.addEventListener("change", atualizarProfessoresPorEscola);
    professorLabels.forEach((label) => {
        label
            .querySelector('input[type="checkbox"]')
            .addEventListener("change", atualizarProfessoresPorEscola);
    });

    atualizarProfessoresPorEscola();

    const busca = document.getElementById("buscar-professor");
    const professores = document.querySelectorAll(".professor-item");

    if (!busca) {
        return;
    }

    busca.addEventListener("input", () => {
        const termo = busca.value.toLowerCase().trim();

        professores.forEach((professor) => {
            const nome = professor.dataset.nome;

            professor.hidden = !nome.includes(termo);
        });
    });
});
