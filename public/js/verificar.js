document.addEventListener("DOMContentLoaded", () => {
    const campoBuscaAlunos = document.querySelector("[data-aluno-search]");
    const opcoesAlunos = Array.from(
        document.querySelectorAll("[data-aluno-option]"),
    );
    const mensagemSemAlunos = document.querySelector("[data-aluno-empty]");

    if (campoBuscaAlunos) {
        const normalizar = (texto) =>
            texto
                .normalize("NFD")
                .replace(/[\u0300-\u036f]/g, "")
                .toLowerCase();

        const filtrarAlunos = () => {
            const termo = normalizar(campoBuscaAlunos.value.trim());
            let quantidadeVisivel = 0;

            opcoesAlunos.forEach((opcao) => {
                const corresponde = normalizar(opcao.textContent).includes(
                    termo,
                );
                opcao.classList.toggle("d-none", !corresponde);
                quantidadeVisivel += corresponde ? 1 : 0;
            });

            mensagemSemAlunos?.classList.toggle(
                "d-none",
                quantidadeVisivel > 0,
            );
        };

        campoBuscaAlunos.addEventListener("input", filtrarAlunos);
        filtrarAlunos();
    }

    const campoBuscaTurmas = document.querySelector("[data-turma-search]");
    const filtroEscolaTurmas = document.querySelector(
        "[data-turma-school-filter]",
    );
    const opcoesTurmas = Array.from(
        document.querySelectorAll("[data-turma-option]"),
    );
    const mensagemSemTurmas = document.querySelector("[data-turma-empty]");

    if (campoBuscaTurmas || filtroEscolaTurmas) {
        const normalizar = (texto) =>
            texto
                .normalize("NFD")
                .replace(/[\u0300-\u036f]/g, "")
                .toLowerCase();

        const filtrarTurmas = () => {
            const termo = normalizar(campoBuscaTurmas?.value.trim() ?? "");
            const escolaId = filtroEscolaTurmas?.value ?? "";
            let quantidadeVisivel = 0;

            opcoesTurmas.forEach((opcao) => {
                const correspondeEscola =
                    !escolaId || opcao.dataset.schoolId === escolaId;
                const correspondeNome = normalizar(
                    opcao.textContent,
                ).includes(termo);
                const corresponde = correspondeEscola && correspondeNome;

                opcao.classList.toggle("d-none", !corresponde);
                quantidadeVisivel += corresponde ? 1 : 0;
            });

            mensagemSemTurmas?.classList.toggle(
                "d-none",
                quantidadeVisivel > 0,
            );
        };

        campoBuscaTurmas?.addEventListener("input", filtrarTurmas);
        filtroEscolaTurmas?.addEventListener("change", filtrarTurmas);
        filtrarTurmas();
    }

    document.querySelectorAll("[data-confirm-rejection]").forEach((button) => {
        button.addEventListener("click", (event) => {
            if (!window.confirm("Recusar o cadastro deste usuário?")) {
                event.preventDefault();
            }
        });
    });
});
