<body>
    <div class="container">
      <h1 style="text-align:center;">Agenda da Barbearia 💈</h1>
      <div id="calendario" class="calendario"></div>
    </div>
</body>

<style>
  body {
    font-family: Arial, sans-serif;
    background: #f4f4f4;
    margin: 0;
    padding: 20px;
  }

  .calendario {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 10px;
    margin-top: 30px;
  }

  .dia {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    padding: 12px;
    text-align: center;
    transition: 0.3s;
  }

  .dia:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
  }

  .dia h3 {
    margin: 0 0 10px 0;
    font-size: 1rem;
    color: #444;
  }

  .horario {
    background: #eaf8ff;
    border: 1px solid #90caf9;
    padding: 8px;
    border-radius: 8px;
    margin: 6px 0;
    font-size: 0.9rem;
  }

  .horario strong {
    display: block;
    color: #333;
  }

  .sem-agendamentos {
    color: #aaa;
    font-style: italic;
  }
</style>

<script>
  // === Passa os dados do Laravel pro JavaScript ===
  const response = @json($response);

  const diasSemana = ["segunda", "terca", "quarta", "quinta", "sexta", "sabado", "domingo"];
  const calendario = document.getElementById("calendario");
  const dados = response;

  diasSemana.forEach(dia => {
    const data = dados.referencia_semanal[dia];
    const divDia = document.createElement("div");
    divDia.className = "dia";
    divDia.innerHTML = `<h3>${dia.charAt(0).toUpperCase() + dia.slice(1)}<br><small>${data}</small></h3>`;

    let temAgendamento = false;

    dados.profissionais.forEach(prof => {
      (prof.agendamentos_confirmados || []).forEach(ag => {
        if (ag.data === data) {
          temAgendamento = true;
          const divHorario = document.createElement("div");
          divHorario.className = "horario";
          divHorario.innerHTML = `
            💈 <strong>${prof.profissional}</strong>
            🕒 ${ag.horario.slice(0,5)}<br>
            👤 ${ag.cliente}<br>
            ✂️ ${ag.servico}
          `;
          divDia.appendChild(divHorario);
        }
      });
    });

    if (!temAgendamento) {
      divDia.innerHTML += `<p class="sem-agendamentos">📭 Nenhum horário agendado</p>`;
    }

    calendario.appendChild(divDia);
  });
</script>

