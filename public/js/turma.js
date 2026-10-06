document.addEventListener("DOMContentLoaded", () => {
    const escolaSelect = document.getElementById("escola_id");
    const professorLabels = document.querySelectorAll(".usuario-form-checkbox");

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

            // Desabilita somente quem não atua na escola selecionada
            input.disabled = escolaId !== "" && !pertence;
        });
    }

    escolaSelect.addEventListener("change", atualizarProfessoresPorEscola);

    atualizarProfessoresPorEscola();
});
