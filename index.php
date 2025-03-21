<!DOCTYPE html>
<html>
<head>
    <title>Painel de Controle com Chat</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Painel Principal</h1>

    <h2>Gráfico de Sentimentos</h2>
    <div class="sentiment-chart">
        <?php
        include 'data.php';

        $total = array_sum($sentimentData);
        foreach ($sentimentData as $sentiment => $value) {
            $percentage = ($value / $total) * 100;
            echo "<div class='$sentiment' style='width: " . $percentage . "%; height: 100%;'></div>";
        }
        ?>
    </div>

    <h2>Últimas Análises</h2>
    <div class="latest-analyses">
        <table>
            <tr>
                <th>Texto</th>
                <th>Sentimento</th>
                <th>Feedback</th>
            </tr>
            <?php
            session_start(); // Inicia a sessão

            if (!isset($_SESSION['latestAnalyses'])) {
                $_SESSION['latestAnalyses'] = $latestAnalyses;
            }

            foreach ($_SESSION['latestAnalyses'] as $key => $analysis) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($analysis['text']) . "</td>";
                echo "<td class='" . $analysis['sentiment'] . "'>" . ucfirst($analysis['sentiment']) . "</td>";
                echo "<td>";
                echo "<form method='post'>";
                echo "<input type='hidden' name='feedbackIndex' value='" . $key . "'>";
                echo "<button type='submit' name='feedback' value='positivo'></button>";
                echo "<button type='submit' name='feedback' value='negativo'></button>";
                echo "</form>";
                echo "</td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>

    <h2>Chat para Análise</h2>
    <div class="chat">
        <div class="chat-messages" id="chat-messages">
            <?php
            if (isset($_SESSION['chatMessages'])) {
                foreach ($_SESSION['chatMessages'] as $chatMessage) {
                    echo "<p><strong>Usuário:</strong> " . htmlspecialchars($chatMessage['text']) . " (Sentimento: <span class='" . $chatMessage['sentiment'] . "'>" . ucfirst($chatMessage['sentiment']) . "</span>)</p>";
                }
            }
            ?>
        </div>
        <form method="post">
            <input type="text" name="message" placeholder="Digite seu comentário...">
            <button type="submit" name="analyze">Analisar</button>
            <button type="submit" name="clearHistory">Limpar Histórico</button>
        </form>
    </div>

    <?php
    if (isset($_POST['analyze'])) {
        $message = $_POST['message'];
        $sentiment = simulateSentimentAnalysis($message);

        // Adiciona a mensagem ao chat
        if (!isset($_SESSION['chatMessages'])) {
            $_SESSION['chatMessages'] = [];
        }
        $_SESSION['chatMessages'][] = ['text' => $message, 'sentiment' => $sentiment];

        // Adiciona a análise às últimas análises
        if (!isset($_SESSION['latestAnalyses'])) {
            $_SESSION['latestAnalyses'] = $latestAnalyses;
        }

        array_unshift($_SESSION['latestAnalyses'], ['text' => $message, 'sentiment' => $sentiment]);
        $_SESSION['latestAnalyses'] = array_slice($_SESSION['latestAnalyses'], 0, 10);
    }

    if (isset($_POST['clearHistory'])) {
        // Limpa o histórico do chat
        unset($_SESSION['chatMessages']);

        // Limpa o histórico das últimas análises
        unset($_SESSION['latestAnalyses']);
    }

    if (isset($_POST['feedback'])) {
        $feedbackIndex = $_POST['feedbackIndex'];
        $feedback = $_POST['feedback'];

        // Simula o aprendizado ajustando as probabilidades de classificação
        // (Isso é uma simplificação; um sistema real usaria aprendizado de máquina)
        if ($feedback === 'positivo') {
            // Aumenta a probabilidade do sentimento correto
            // (Você precisaria implementar uma lógica mais complexa aqui)
        } else if ($feedback === 'negativo') {
            // Diminui a probabilidade do sentimento incorreto
            // (Você precisaria implementar uma lógica mais complexa aqui)
        }
    }

    function simulateSentimentAnalysis($text) {
        $random = mt_rand(0, 2);
        switch ($random) {
            case 0:
                return 'negativo';
            case 1:
                return 'neutro';
            default:
                return 'positivo';
        }
    }
    ?>
</body>
</html>