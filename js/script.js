function alternarAba(idAba, elementoBtn) {
  document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
  document.querySelectorAll('.nav-item').forEach(b => b.classList.remove('active'));

  document.getElementById('aba-' + idAba).classList.add('active');
  elementoBtn.classList.add('active');

  if (idAba === 'prontuario') {
    carregarHistoricoMedico();
  } else if (idAba === 'resumo') {
    carregarResumoPareceres();
  }
}

function carregarHistoricoMedico() {
  const container = document.getElementById('tabelaHistoricoMedico');
  container.innerHTML = '<p style="font-size: 0.8rem; color: #64748b;">Buscando eventos da pulseira...</p>';

  fetch('api/listar_historico.php')
    .then(res => res.json())
    .then(dados => {
      if (!dados || dados.length === 0) {
        container.innerHTML = '<p style="font-size: 0.8rem; color: #64748b;">Nenhum alerta recente recebido.</p>';
        return;
      }

      container.innerHTML = '';
      dados.forEach(item => {
        container.innerHTML += `
          <div class="med-item">
            <div><b>${item.tipo_evento}</b> (${item.bpm} BPM | SpO₂: ${item.spo2}%)</div>
            <div style="color: #64748b; margin: 2px 0;">${item.descricao}</div>
            <div class="med-time">${item.data_hora}</div>
          </div>
        `;
        document.getElementById('vitasResumo').innerText = `${item.bpm} BPM | SpO₂ ${item.spo2}%`;
        document.getElementById('teleBpm').innerText = `${item.bpm} BPM`;
        document.getElementById('teleSpo2').innerText = `${item.spo2}%`;
      });
    })
    .catch(() => container.innerHTML = '<p style="font-size: 0.8rem; color: #f43f5e;">Erro ao conectar com a API de alertas.</p>');
}

function salvarParecer(event) {
  event.preventDefault();
  const texto = document.getElementById('textoParecer').value;
  const msgFeedback = document.getElementById('msgParecer');

  if (!texto.trim()) {
    alert("Digite uma observação válida.");
    return;
  }

  fetch('api/salvar_parecer.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ texto })
  })
  .then(res => res.json())
  .then(data => {
    if (data.status === 'sucesso') {
      msgFeedback.innerText = "✓ Parecer salvo e sincronizado com o App!";
      document.getElementById('textoParecer').value = '';
      setTimeout(() => msgFeedback.innerText = '', 4000);
      carregarResumoPareceres();
    } else {
      alert("Erro ao salvar.");
    }
  })
  .catch(() => alert("Erro na requisição."));
}

function carregarResumoPareceres() {
  const container = document.getElementById('listaResumoPareceres');
  if (!container) return;
  container.innerHTML = '<p style="font-size: 0.8rem; color: #64748b;">Carregando notas...</p>';

  fetch('api/listar_pareceres.php')
    .then(res => res.json())
    .then(dados => {
      if (!dados || dados.length === 0) {
        container.innerHTML = '<p style="font-size: 0.8rem; color: #64748b;">Nenhuma anotação registrada no prontuário.</p>';
        return;
      }
      container.innerHTML = '';
      dados.forEach(item => {
        container.innerHTML += `
          <div class="med-item" style="border-left: 4px solid #0284c7;">
            <div><b>Conduta Médica:</b> ${item.texto}</div>
            <div class="med-time">${item.data_hora}</div>
          </div>
        `;
      });
    })
    .catch(() => container.innerHTML = '<p style="font-size: 0.8rem; color: #f43f5e;">Erro ao carregar pareceres.</p>');
}

document.addEventListener('DOMContentLoaded', () => {
  carregarHistoricoMedico();
});