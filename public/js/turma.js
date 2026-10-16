document.addEventListener("DOMContentLoaded", () => {
    const dropdown = document.getElementById("professores-dropdown");
    if (!dropdown) return;

    const escola = document.getElementById("escola_id");
    const trigger = document.getElementById("professores-trigger");
    const painel = document.getElementById("professores-painel");
    const busca = document.getElementById("buscar-professor");
    const resumo = document.getElementById("professores-resumo");
    const semResultado = document.getElementById("professores-sem-resultado");

    const permiteOutrasEscolas = document.querySelector(
        "[data-professores-sem-restricao-escola]",
    );

    const itens = [...dropdown.querySelectorAll(".professor-item")];

    function atualizarResumo() {
        const selecionados = itens.filter(
            (item) => item.querySelector('input[type="checkbox"]').checked,
        );

        const total = selecionados.length;

        if (total === 0) {
            resumo.textContent = "Selecione";
        } else {
            resumo.textContent = `${total} selecionado${total !== 1 ? "s" : ""}`;
        }
    }

    function atualizarProfessores() {
        const termo = busca.value.toLocaleLowerCase("pt-BR").trim();
        const escolaId = escola?.value || "";

        let encontrados = 0;

        itens.forEach((item) => {
            const checkbox = item.querySelector('input[type="checkbox"]');

            const nome = item.dataset.nome.toLocaleLowerCase("pt-BR");

            const escolas = (item.dataset.escolas || "")
                .split(",")
                .filter(Boolean);

            const pertenceEscola = escolas.includes(escolaId);

            const restricaoAtiva = !permiteOutrasEscolas && escolaId !== "";

            // Mantém professores já selecionados habilitados
            checkbox.disabled =
                restricaoAtiva && !pertenceEscola && !checkbox.checked;

            // Pesquisa somente pelo nome
            const correspondeBusca = nome.includes(termo);

            item.hidden = !correspondeBusca;

            if (correspondeBusca) {
                encontrados++;
            }
        });

        semResultado.hidden = encontrados > 0;
    }

    function abrirDropdown() {
        painel.hidden = false;
        trigger.setAttribute("aria-expanded", "true");
        dropdown.classList.add("aberto");

        atualizarProfessores();
        busca.focus();
    }

    function fecharDropdown() {
        painel.hidden = true;
        trigger.setAttribute("aria-expanded", "false");
        dropdown.classList.remove("aberto");
    }

    trigger.addEventListener("click", () => {
        if (painel.hidden) {
            abrirDropdown();
        } else {
            fecharDropdown();
        }
    });

    busca.addEventListener("input", atualizarProfessores);

    itens.forEach((item) => {
        const checkbox = item.querySelector('input[type="checkbox"]');

        checkbox.addEventListener("change", () => {
            atualizarResumo();
            atualizarProfessores();
        });
    });

    escola?.addEventListener("change", () => {
        atualizarProfessores();
        atualizarResumo();
    });

    // Fecha ao clicar fora
    document.addEventListener("click", (event) => {
        if (!dropdown.contains(event.target)) {
            fecharDropdown();
        }
    });

    // Fecha com Escape
    dropdown.addEventListener("keydown", (event) => {
        if (event.key === "Escape") {
            fecharDropdown();
            trigger.focus();
        }
    });

    // Inicialização
    atualizarResumo();
    atualizarProfessores();
});
