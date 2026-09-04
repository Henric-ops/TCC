document.addEventListener("DOMContentLoaded", function () {
    const alunoSelect = document.getElementById("aluno_id");
    const responsavelSelect = document.getElementById("destinatario_id");
    const responsavelInfo = document.getElementById("responsavelInfo");
    const responsavelEmail = document.getElementById("responsavelEmail");
    const textarea = document.getElementById("conteudo");
    const characterCount = document.getElementById("characterCount");
    const form = document.getElementById("emailForm");
    const btnEnviar = document.getElementById("btnEnviar");

    // Verifica se os campos principais existem
    if (!alunoSelect || !responsavelSelect) {
        return;
    }

    // Dados enviados pela Blade
    const alunos = window.alunosEmail || [];
    const oldResponsavel = window.oldResponsavelEmail || null;

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
        form.addEventListener("submit", function () {
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

    if (alunoSelect.value) {
        alunoSelect.dispatchEvent(new Event("change"));
    }
});
