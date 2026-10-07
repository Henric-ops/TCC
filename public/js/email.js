document.addEventListener("DOMContentLoaded", function () {
    const alunoSelect = document.getElementById("aluno_id");
    const campoBuscaAluno = document.querySelector("[data-student-search]");
    const resultadoBuscaAluno = document.getElementById("resultadoBuscaAluno");
    const listaSugestoes = document.getElementById("alunoSugestoes");
    const responsavelSelect = document.getElementById("destinatario_id");
    const responsavelInfo = document.getElementById("responsavelInfo");
    const responsavelEmail = document.getElementById("responsavelEmail");
    const textarea = document.getElementById("conteudo");
    const characterCount = document.getElementById("characterCount");
    const form = document.getElementById("emailForm");
    const btnEnviar = document.getElementById("btnEnviar");

    // Verifica se os campos principais existem
    if (
        !alunoSelect ||
        !responsavelSelect ||
        !campoBuscaAluno ||
        !listaSugestoes
    ) {
        return;
    }

    // Dados enviados pela Blade
    const alunos = window.alunosEmail || [];
    const oldAluno = window.oldAlunoEmail || null;
    const oldResponsavel = window.oldResponsavelEmail || null;

    const normalizar = (texto) =>
        texto
            .normalize("NFD")
            .replace(/[\u0300-\u036f]/g, "")
            .toLowerCase();

    const autocompleteWrapper = campoBuscaAluno.closest(
        ".autocomplete-wrapper",
    );
    let encontrados = [];
    let indiceAtivo = -1;

    function fecharSugestoes() {
        listaSugestoes.hidden = true;
        campoBuscaAluno.setAttribute("aria-expanded", "false");
        campoBuscaAluno.removeAttribute("aria-activedescendant");
        indiceAtivo = -1;
    }

    function marcarSugestaoAtiva(indice) {
        const opcoes = Array.from(
            listaSugestoes.querySelectorAll("[data-student-id]"),
        );

        if (!opcoes.length) return;

        indiceAtivo = (indice + opcoes.length) % opcoes.length;
        opcoes.forEach((opcao, index) => {
            const ativa = index === indiceAtivo;
            opcao.classList.toggle("is-active", ativa);
            opcao.setAttribute("aria-selected", String(ativa));
        });

        campoBuscaAluno.setAttribute(
            "aria-activedescendant",
            opcoes[indiceAtivo].id,
        );
    }

    function selecionarAluno(aluno) {
        campoBuscaAluno.value = aluno.escola
            ? `${aluno.nome} - ${aluno.escola}`
            : aluno.nome;
        alunoSelect.value = String(aluno.id);
        campoBuscaAluno.setCustomValidity("");
        fecharSugestoes();

        if (resultadoBuscaAluno) {
            resultadoBuscaAluno.textContent = `Aluno selecionado: ${aluno.nome}.`;
        }

        alunoSelect.dispatchEvent(new Event("change"));
    }

    function atualizarSugestoes() {
        const termo = normalizar(campoBuscaAluno.value.trim());
        alunoSelect.value = "";
        alunoSelect.dispatchEvent(new Event("change"));
        listaSugestoes.replaceChildren();
        encontrados = termo
            ? alunos.filter((aluno) => normalizar(aluno.nome).includes(termo))
            : [];
        indiceAtivo = -1;

        if (!termo) {
            fecharSugestoes();
            if (resultadoBuscaAluno) {
                resultadoBuscaAluno.textContent =
                    "Digite para buscar e selecione um aluno nas sugestões.";
            }
            return;
        }

        if (!encontrados.length) {
            const vazio = document.createElement("div");
            vazio.className = "student-suggestion-empty";
            vazio.setAttribute("role", "option");
            vazio.setAttribute("aria-disabled", "true");
            vazio.textContent = "Nenhum aluno encontrado.";
            listaSugestoes.appendChild(vazio);
        } else {
            encontrados.forEach((aluno, index) => {
                const opcao = document.createElement("button");
                opcao.type = "button";
                opcao.id = `aluno-sugestao-${aluno.id}`;
                opcao.className = "student-suggestion";
                opcao.setAttribute("role", "option");
                opcao.setAttribute("aria-selected", "false");
                opcao.dataset.studentId = aluno.id;
                opcao.dataset.index = index;

                const nome = document.createElement("span");
                nome.className = "student-suggestion-name";
                nome.textContent = aluno.nome;
                opcao.appendChild(nome);

                if (aluno.escola) {
                    const escola = document.createElement("span");
                    escola.className = "student-suggestion-school";
                    escola.textContent = aluno.escola;
                    opcao.appendChild(escola);
                }

                listaSugestoes.appendChild(opcao);
            });
        }

        listaSugestoes.hidden = false;
        campoBuscaAluno.setAttribute("aria-expanded", "true");

        if (resultadoBuscaAluno) {
            resultadoBuscaAluno.textContent = encontrados.length
                ? `${encontrados.length} aluno(s) encontrado(s).`
                : "Nenhum aluno encontrado. Confira o nome e tente novamente.";
        }
    }

    campoBuscaAluno.addEventListener("input", function () {
        campoBuscaAluno.setCustomValidity("");
        atualizarSugestoes();
    });

    campoBuscaAluno.addEventListener("keydown", function (event) {
        const opcoes = listaSugestoes.querySelectorAll("[data-student-id]");

        if (event.key === "ArrowDown" && opcoes.length) {
            event.preventDefault();
            marcarSugestaoAtiva(indiceAtivo + 1);
        } else if (event.key === "ArrowUp" && opcoes.length) {
            event.preventDefault();
            marcarSugestaoAtiva(
                indiceAtivo <= 0 ? opcoes.length - 1 : indiceAtivo - 1,
            );
        } else if (
            event.key === "Enter" &&
            !listaSugestoes.hidden &&
            opcoes.length
        ) {
            event.preventDefault();
            const indice = indiceAtivo < 0 ? 0 : indiceAtivo;
            selecionarAluno(encontrados[indice]);
        } else if (event.key === "Escape") {
            fecharSugestoes();
        }
    });

    listaSugestoes.addEventListener("mousedown", (event) => {
        event.preventDefault();
    });

    listaSugestoes.addEventListener("click", (event) => {
        const opcao = event.target.closest("[data-student-id]");
        const aluno = encontrados.find(
            (item) => String(item.id) === opcao?.dataset.studentId,
        );

        if (aluno) selecionarAluno(aluno);
    });

    document.addEventListener("click", (event) => {
        if (!autocompleteWrapper?.contains(event.target)) fecharSugestoes();
    });

    /*
    |--------------------------------------------------------------------------
    | Seleção do aluno
    |--------------------------------------------------------------------------
    */

    alunoSelect.addEventListener("change", function () {
        const alunoId = this.value;

        // Limpa o select de responsáveis
        responsavelSelect.innerHTML = "";

        // Limpa informações anteriores
        if (responsavelInfo) {
            responsavelInfo.style.display = "none";
        }

        if (responsavelEmail) {
            responsavelEmail.textContent = "";
        }

        // Nenhum aluno selecionado
        if (!alunoId) {
            responsavelSelect.disabled = true;

            const option = document.createElement("option");
            option.value = "";
            option.textContent = "Primeiro selecione um aluno";

            responsavelSelect.appendChild(option);

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Procura o aluno
        |--------------------------------------------------------------------------
        */

        const aluno = alunos.find(function (item) {
            return String(item.id) === String(alunoId);
        });

        if (!aluno) {
            responsavelSelect.disabled = true;

            const option = document.createElement("option");
            option.value = "";
            option.textContent = "Aluno não encontrado";

            responsavelSelect.appendChild(option);

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Verifica responsáveis
        |--------------------------------------------------------------------------
        */

        if (!aluno.responsaveis || aluno.responsaveis.length === 0) {
            responsavelSelect.disabled = true;

            const option = document.createElement("option");
            option.value = "";
            option.textContent = "Nenhum responsável vinculado";

            responsavelSelect.appendChild(option);

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Existem responsáveis
        |--------------------------------------------------------------------------
        */

        responsavelSelect.disabled = false;

        const defaultOption = document.createElement("option");

        defaultOption.value = "";
        defaultOption.textContent = "Selecione o responsável";

        responsavelSelect.appendChild(defaultOption);

        /*
        |--------------------------------------------------------------------------
        | Adiciona os responsáveis
        |--------------------------------------------------------------------------
        */

        aluno.responsaveis.forEach(function (responsavel) {
            const option = document.createElement("option");

            option.value = responsavel.id;
            option.textContent = responsavel.nome;
            option.dataset.email = responsavel.email || "";

            // Restaura seleção após erro de validação
            if (
                oldResponsavel &&
                String(oldResponsavel) === String(responsavel.id)
            ) {
                option.selected = true;
            }

            responsavelSelect.appendChild(option);
        });

        /*
        |--------------------------------------------------------------------------
        | Atualiza informações do responsável
        |--------------------------------------------------------------------------
        */

        responsavelSelect.dispatchEvent(new Event("change"));
    });

    /*
    |--------------------------------------------------------------------------
    | Exibir e-mail do responsável
    |--------------------------------------------------------------------------
    */

    responsavelSelect.addEventListener("change", function () {
        const selectedOption = this.options[this.selectedIndex];

        if (
            selectedOption &&
            selectedOption.value &&
            selectedOption.dataset.email
        ) {
            if (responsavelEmail) {
                responsavelEmail.textContent = selectedOption.dataset.email;
            }

            if (responsavelInfo) {
                responsavelInfo.style.display = "flex";
            }
        } else {
            if (responsavelInfo) {
                responsavelInfo.style.display = "none";
            }

            if (responsavelEmail) {
                responsavelEmail.textContent = "";
            }
        }
    });

    /*
    |--------------------------------------------------------------------------
    | Contador de caracteres
    |--------------------------------------------------------------------------
    */

    if (textarea && characterCount) {
        function updateCharacterCount() {
            characterCount.textContent = textarea.value.length;
        }

        textarea.addEventListener("input", updateCharacterCount);

        updateCharacterCount();
    }

    /*
    |--------------------------------------------------------------------------
    | Evita duplo envio
    |--------------------------------------------------------------------------
    */

    if (form && btnEnviar) {
        form.addEventListener("submit", function (event) {
            if (!alunoSelect.value) {
                event.preventDefault();
                campoBuscaAluno.setCustomValidity(
                    "Selecione um aluno nas sugestões.",
                );
                campoBuscaAluno.reportValidity();
                return;
            }

            btnEnviar.disabled = true;

            btnEnviar.innerHTML = `
                <span class="spinner-border spinner-border-sm me-2"></span>
                <span>Enviando...</span>
            `;
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Restaura os dados após erro de validação
    |--------------------------------------------------------------------------
    */

    const alunoAnterior = alunos.find(
        (aluno) => String(aluno.id) === String(oldAluno),
    );

    if (alunoAnterior) {
        selecionarAluno(alunoAnterior);
    }
});
