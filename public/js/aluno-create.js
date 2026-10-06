document.addEventListener("DOMContentLoaded", function () {
    const escolaSelect = document.getElementById("escola_id");
    const multiselect = document.getElementById("turmaMultiselect");
    const trigger = document.getElementById("turmaTrigger");
    const triggerText = document.getElementById("turmaTriggerText");
    const dropdown = document.getElementById("turmaDropdown");
    const search = document.getElementById("turmaSearch");
    const options = document.querySelectorAll(".turma-option");
    const checkboxes = document.querySelectorAll(".turma-checkbox");
    const count = document.getElementById("turmaCount");
    const empty = document.getElementById("turmaEmpty");

    if (!escolaSelect || !multiselect || !trigger || !dropdown || !search) {
        return;
    }

    function abrirDropdown() {
        if (!escolaSelect.value) {
            escolaSelect.focus();
            return;
        }

        multiselect.classList.add("open");
        dropdown.style.display = "block";

        trigger.setAttribute("aria-expanded", "true");

        search.value = "";

        filtrarPesquisa();

        search.focus();
    }

    function fecharDropdown() {
        multiselect.classList.remove("open");
        dropdown.style.display = "none";

        trigger.setAttribute("aria-expanded", "false");
    }

    function toggleDropdown() {
        if (multiselect.classList.contains("open")) {
            fecharDropdown();
        } else {
            abrirDropdown();
        }
    }

    function filtrarTurmasPorEscola() {
        const escolaId = escolaSelect.value;

        let quantidade = 0;

        options.forEach(function (option) {
            const pertence = option.dataset.escola === escolaId;

            if (escolaId && pertence) {
                option.style.display = "";

                quantidade++;
            } else {
                option.style.display = "none";
            }
        });

        if (!escolaId) {
            triggerText.textContent = "Selecione uma escola primeiro";

            empty.style.display = "none";

            return;
        }

        if (quantidade === 0) {
            triggerText.textContent = "Nenhuma turma disponível";

            empty.style.display = "";
        } else {
            empty.style.display = "none";

            atualizarSelecionadas();
        }
    }

    function filtrarPesquisa() {
        const escolaId = escolaSelect.value;

        const termo = search.value.toLowerCase().trim();

        let encontrados = 0;

        options.forEach(function (option) {
            const pertence = option.dataset.escola === escolaId;

            const texto = option.dataset.search || "";

            const corresponde = texto.includes(termo);

            if (escolaId && pertence && corresponde) {
                option.style.display = "";

                encontrados++;
            } else {
                option.style.display = "none";
            }
        });

        if (encontrados === 0) {
            empty.style.display = "";
        } else {
            empty.style.display = "none";
        }
    }

    function atualizarSelecionadas() {
        const escolaId = escolaSelect.value;

        const selecionadas = Array.from(checkboxes).filter(function (checkbox) {
            const option = checkbox.closest(".turma-option");

            return checkbox.checked && option.dataset.escola === escolaId;
        });

        if (selecionadas.length === 0) {
            if (escolaId) {
                triggerText.textContent = "Selecione uma ou mais turmas";
            } else {
                triggerText.textContent = "Selecione uma escola primeiro";
            }

            count.textContent = "Nenhuma turma selecionada";

            return;
        }

        const nomes = selecionadas.map(function (checkbox) {
            return checkbox
                .closest(".turma-option")
                .querySelector(".turma-option-nome")
                .textContent.trim();
        });

        if (nomes.length <= 2) {
            triggerText.textContent = nomes.join(", ");
        } else {
            triggerText.textContent =
                nomes[0] + ", " + nomes[1] + " +" + (nomes.length - 2);
        }

        if (selecionadas.length === 1) {
            count.textContent = "1 turma selecionada";
        } else {
            count.textContent = selecionadas.length + " turmas selecionadas";
        }
    }

    function limparTurmasSelecionadas() {
        checkboxes.forEach(function (checkbox) {
            checkbox.checked = false;
        });
    }

    trigger.addEventListener("click", function () {
        toggleDropdown();
    });

    escolaSelect.addEventListener("change", function () {
        limparTurmasSelecionadas();

        search.value = "";

        fecharDropdown();

        filtrarTurmasPorEscola();

        atualizarSelecionadas();
    });

    search.addEventListener("input", function () {
        filtrarPesquisa();
    });

    checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener("change", function () {
            atualizarSelecionadas();
        });
    });

    document.addEventListener("click", function (event) {
        if (!multiselect.contains(event.target)) {
            fecharDropdown();
        }
    });

    document.addEventListener("keydown", function (event) {
        if (event.key === "Escape" && multiselect.classList.contains("open")) {
            fecharDropdown();
            trigger.focus();
        }
    });

    dropdown.style.display = "none";

    filtrarTurmasPorEscola();

    atualizarSelecionadas();
});

document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector(".aluno-form-page form");
    const escolaSelect = document.getElementById("escola_id");
    const trigger = document.getElementById("turmaTrigger");

    if (!form || !escolaSelect || !trigger) {
        return;
    }

    form.addEventListener("submit", function (event) {
        const escolaValida = escolaSelect.value !== "";
        const turmaSelecionada = document.querySelector(
            'input[name="turmas[]"]:checked',
        );

        if (!escolaValida || !turmaSelecionada) {
            event.preventDefault();

            if (!escolaValida) {
                escolaSelect.focus();
                escolaSelect.classList.add("is-invalid");
            }

            if (!turmaSelecionada) {
                trigger.style.borderColor = "#dc3545";
                trigger.style.boxShadow =
                    "0 0 0 0.2rem rgba(220, 53, 69, 0.15)";
                trigger.setAttribute("aria-invalid", "true");
                trigger.scrollIntoView({ behavior: "smooth", block: "center" });
            }
        }
    });
});
