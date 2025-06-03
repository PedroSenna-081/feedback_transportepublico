<?php
$msg = "";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $host = "localhost";
    $db = "feedback_site";
    $user = "root";
    $pass = "";

    $conn = new mysqli($host, $user, $pass, $db);

    if ($conn->connect_error) {
        $msg = "❌ Erro na conexão: " . $conn->connect_error;
    } else {
        $nome = $_POST['nome'] ?? '';
        $email = $_POST['email'] ?? '';
        $genero = $_POST['genero'] ?? '';
        $faixa_etaria = $_POST['faixa_etaria'] ?? '';
        $telefone = $_POST['telefone'] ?? '';
        $linha = $_POST['linha'] ?? '';
        $datahora = $_POST['datahora'] ?? '';
        $comentario = $_POST['comentario'] ?? '';

        $datahora = str_replace("T", " ", $datahora);

        $stmt = $conn->prepare("INSERT INTO feedback (nome, email, genero,faixa_etaria, telefone, linha, datahora, comentario) VALUES (?, ?, ?, ?,?, ?, ?, ?)");

        if (!$stmt) {
            $msg = "❌ Erro ao preparar: " . $conn->error;
        } else {
            $stmt->bind_param("ssssssss", $nome, $email, $genero,$faixa_etaria, $telefone, $linha, $datahora, $comentario);

            if ($stmt->execute()) {
                $msg = "✅ Feedback salvo com sucesso!";
            } else {
                $msg = "❌ Erro ao salvar feedback: " . $stmt->error;
            }

            $stmt->close();
        }

        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <title>BusSmart - Deixe seu Feedback</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <!-- Leaflet CSS (mapa) -->
  <link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-sA+ePHQyClBvXZK29T5H6DsC0WXWpE2Sx2f5p8aXy0w="
    crossorigin=""
  />

  <style>
    body {
      background: #f4f6f9;
    }
    .container {
      max-width: 600px;
      margin-top: 40px;
      margin-bottom: 60px;
    }
    .card {
      border: none;
      box-shadow: 0 4px 12px rgba(0,0,0,0.12);
      padding: 2rem;
      border-radius: 12px;
      background: white;
    }
    .brand-title {
      font-size: 2rem;
      font-weight: 700;
      color: #0d6efd;
      text-align: center;
      margin-bottom: 1rem;
    }
    .brand-logo {
      display: block;
      margin: 0 auto 1rem auto;
      width: 100px;
      height: auto;
    }
   
    
  </style>
</head>
<body>

<div class="container">
  <div class="card">
    <!-- Logo -->
   <!-- <img src="logo.png" alt="Logo BusSmart" class="brand-logo" /> -->

    <div class="brand-title">🚌 BusSmart</div>
    <p class="text-center text-muted mb-4">
      Envie sugestões, elogios ou reclamações sobre o transporte público
    </p>

  <?php
 
    if ($_SERVER["REQUEST_METHOD"] === "POST" && $msg) {
    $alertClass = str_contains($msg, '✅') ? 'alert-success' : 'alert-danger';
    echo '<div id="mensagem-feedback" class="alert ' . $alertClass . '" role="alert">';
    echo htmlspecialchars($msg);
    echo '</div>';
}
?>
 


    <form method="POST" action="">
      <div class="mb-3 input-group">
        <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
        <input
          type="text"
          class="form-control"
          id="nome"
          name="nome"
          required
          placeholder="Nome completo"
          aria-label="Nome completo"
        />
      </div>

      <div class="mb-3 input-group">
        <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
        <input
          type="email"
          class="form-control"
          id="email"
          name="email"
          required
          placeholder="exemplo@email.com"
          aria-label="E-mail"
        />
      </div>

      <div class="mb-3 input-group">
        <label class="input-group-text" for="genero"><i class="bi bi-gender-ambiguous"></i></label>
        <select
          class="form-select"
          id="genero"
          name="genero"
          required
          aria-label="Gênero"
        >
          <option value="" selected>Selecione seu gênero</option>
          <option value="Masculino">Masculino</option>
          <option value="Feminino">Feminino</option>
          <option value="Outro">Outro</option>
        </select>
      
      </div>
      <div class="mb-3 input-group">
      <label class="input-group-text" for="faixa_etaria"><i class="bi bi-person-badge-fill"></i></label>
        <select class="form-select" id="faixa_etaria" name="faixa_etaria" required>
          <option value="">Faixa etária</option>
          <option value="Menor de 18">Menor de 18</option>
          <option value="18 a 25">18 a 25</option>
          <option value="26 a 35">26 a 35</option>
          <option value="36 a 50">36 a 50</option>
          <option value="Acima de 50">Acima de 50</option>
        </select>
      </div>


      <div class="mb-3 input-group">
        <span class="input-group-text"><i class="bi bi-telephone-fill"></i></span>
        <input
          type="tel"
          class="form-control"
          id="telefone"
          name="telefone"
          placeholder="(00) 00000-0000"
          aria-label="Telefone"
        />
      </div>

      <div class="mb-3 input-group">
        <span class="input-group-text"><i class="bi bi-bus-front-fill"></i></span>
        <input
          type="text"
          class="form-control"
          id="linha"
          name="linha"
          required
          placeholder="Ex: 820 - Terminal / Centro"
          aria-label="Linha de Transporte"
        />
      </div>

      <div class="mb-3 input-group">
        <span class="input-group-text"><i class="bi bi-calendar-event-fill"></i></span>
        <input
          type="datetime-local"
          class="form-control"
          id="datahora"
          name="datahora"
          required
          aria-label="Data e Hora do Ocorrido"
        />
      </div>

      <div class="mb-3">
        <label for="comentario" class="form-label"><i class="bi bi-chat-left-text-fill"></i> Comentário</label>
        <textarea
          class="form-control"
          id="comentario"
          name="comentario"
          rows="4"
          required
          placeholder="Descreva sua experiência..."
          aria-label="Comentário"
        ></textarea>
      </div>

      <button type="submit" class="btn btn-primary w-100">Enviar Feedback</button>
    </form>

    <!-- Mapa -->
    <div id="map" class="mt-4"></div>
  </div>
</div>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Leaflet JS -->
<script
  src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
  integrity="sha256-o9N1j+I6xw1Dy2FQdUOW7vYxvpC74paFk6I9RmNlnz8="
  crossorigin=""
></script>

<script>
  // Inicializa mapa Leaflet
  const map = L.map('map').setView([-23.55052, -46.633308], 12); // São Paulo por padrão

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution:
      '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
  }).addTo(map);

  // Tenta pegar localização do usuário
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
      (pos) => {
        const lat = pos.coords.latitude;
        const lng = pos.coords.longitude;
        map.setView([lat, lng], 14);
        L.marker([lat, lng]).addTo(map).bindPopup('Você está aqui').openPopup();
      },
      (err) => {
        console.warn('Erro ao obter localização:', err.message);
      }
    );
  }
</script>
<script>
  const mensagem = document.getElementById("mensagem-feedback");
  if (mensagem) {
    setTimeout(() => {
      mensagem.style.display = "none";
    }, 1000); // 5000ms = 5 segundos
  }
</script>




</body>
</html>
