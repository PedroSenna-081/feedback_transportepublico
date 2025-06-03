const form = document.getElementById('feedbackForm');
const mensagem = document.getElementById('mensagemSucesso');

form.addEventListener('submit', async function(e) {
  e.preventDefault();

  const formData = new FormData(form);

  try {
    const response = await fetch('salvar.php', {
      method: 'POST',
      body: formData
    });

    const result = await response.text();
    mensagem.textContent = "Mensagem enviada com sucesso!";
    form.reset();
  } catch (error) {
    mensagem.textContent = "Erro ao enviar feedback.";
    mensagem.style.color = "red";
  }
});
