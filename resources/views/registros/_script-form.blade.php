<script>
    function changeCount(id, delta) {
        const el = document.getElementById(id);
        let val = parseInt(el.textContent) + delta;
        if (val < 0) val = 0;
        el.textContent = val;
        document.getElementById(id + '-input').value = val;
    }

    function atualizarDuracao(inicio, fim, destino) {
        const inicioMinutos = converterMinutos(inicio.value);
        const fimMinutos = converterMinutos(fim.value);
        const elemento = document.getElementById(destino);

        if (inicioMinutos === null || fimMinutos === null || fimMinutos < inicioMinutos) {
            elemento.textContent = '--';
            return;
        }

        const duracao = fimMinutos - inicioMinutos;
        elemento.textContent = `${Math.floor(duracao / 60)}h ${duracao % 60}min`;
    }

    function converterMinutos(valor) {
        if (!valor) return null;
        const [horas, minutos] = valor.split(':').map(Number);
        return (horas * 60) + minutos;
    }

    document.querySelectorAll('.radio input, .sono-btn input').forEach((input) => {
        input.addEventListener('change', () => {
            document.querySelectorAll(`input[name="${input.name}"]`).forEach((item) => {
                item.parentElement.classList.toggle('selected', item.checked);
            });
        });
    });

    const periodos = [
        ['sono_inicio_1', 'sono_fim_1', 'duracao-1'],
        ['sono_inicio_2', 'sono_fim_2', 'duracao-2']
    ];

    periodos.forEach(([inicioNome, fimNome, destino]) => {
        const inicio = document.querySelector(`[name="${inicioNome}"]`);
        const fim = document.querySelector(`[name="${fimNome}"]`);
        const atualizar = () => atualizarDuracao(inicio, fim, destino);
        inicio.addEventListener('input', atualizar);
        fim.addEventListener('input', atualizar);
        atualizar();
    });
</script>