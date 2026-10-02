document.addEventListener("DOMContentLoaded", function () {
    const escolaSelect = document.getElementById("escola_id");
    const turmaSelect = document.getElementById("turma_id");
    const alunoSelect = document.getElementById("aluno_id");

    if (!escolaSelect || !turmaSelect || !alunoSelect) {
        return;
    }

    const data = window.frequenciaFiltrosData || {
        turmasPorEscola: {},
        alunosPorTurma: {},
        todasTurmas: [],
        turmaAtual: "",
        alunoAtual: "",
    };

    function addOption(select, value, text, selected = false) {
        const option = document.createElement("option");
        option.value = value;
        option.textContent = text;
        option.selected = selected;
        select.appendChild(option);
    }

    function refreshTurmas() {
        const escolaId = escolaSelect.value;
        const turmaAtual =
            turmaSelect.dataset.selected || data.turmaAtual || "";

        turmaSelect.innerHTML = '<option value="">Todas</option>';

        let turmas = data.todasTurmas;
        if (escolaId) {
            turmas = data.turmasPorEscola[escolaId] || [];
        }

        turmas.forEach(function (turma) {
            const selected = String(turmaAtual) === String(turma.id);
            addOption(turmaSelect, turma.id, turma.nome, selected);
        });

        if (
            turmaAtual &&
            !Array.from(turmaSelect.options).some(
                (option) => option.value === turmaAtual,
            )
        ) {
            turmaSelect.value = "";
        } else if (turmaAtual) {
            turmaSelect.value = turmaAtual;
        }

        turmaSelect.dataset.selected = turmaSelect.value || "";
        refreshAlunos();
    }

    function refreshAlunos() {
        const turmaId = turmaSelect.value;
        const escolaId = escolaSelect.value;
        const alunoAtual =
            alunoSelect.dataset.selected || data.alunoAtual || "";

        alunoSelect.innerHTML = '<option value="">Todos (ver por dia)</option>';

        const turmasFiltradas = turmaId
            ? data.alunosPorTurma[turmaId]
                ? [
                      {
                          id: turmaId,
                          nome:
                              turmaSelect.options[turmaSelect.selectedIndex]
                                  ?.text || "",
                      },
                  ]
                : []
            : escolaId
              ? data.turmasPorEscola[escolaId] || []
              : data.todasTurmas;

        const idsTurmas = new Set(turmasFiltradas.map((t) => String(t.id)));
        const alunos = [];

        Object.entries(data.alunosPorTurma).forEach(([idTurma, lista]) => {
            if (idsTurmas.has(String(idTurma))) {
                lista.forEach((aluno) => alunos.push(aluno));
            }
        });

        alunos.forEach(function (aluno) {
            const selected = String(alunoAtual) === String(aluno.id);
            addOption(alunoSelect, aluno.id, aluno.nome, selected);
        });

        if (
            alunoAtual &&
            !Array.from(alunoSelect.options).some(
                (option) => option.value === alunoAtual,
            )
        ) {
            alunoSelect.value = "";
        } else if (alunoAtual) {
            alunoSelect.value = alunoAtual;
        }

        alunoSelect.dataset.selected = alunoSelect.value || "";
    }

    escolaSelect.addEventListener("change", function () {
        turmaSelect.dataset.selected = "";
        alunoSelect.dataset.selected = "";
        refreshTurmas();
    });

    turmaSelect.addEventListener("change", function () {
        alunoSelect.dataset.selected = "";
        refreshAlunos();
    });

    turmaSelect.dataset.selected = data.turmaAtual || "";
    alunoSelect.dataset.selected = data.alunoAtual || "";
    refreshTurmas();
});
