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
            </tr>
            <?php
            session_start(); // Inicia a sessão

            if (!isset($_SESSION['latestAnalyses'])) {
                $_SESSION['latestAnalyses'] = $latestAnalyses;
            }

            foreach ($_SESSION['latestAnalyses'] as $analysis) {
                echo "<tr>";
                echo "<td>" . $analysis['text'] . "</td>";
                echo "<td class='" . $analysis['sentiment'] . "'>" . ucfirst($analysis['sentiment']) . "</td>";
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
            <button type="submit">Analisar</button>
            <button type="submit" name="clearHistory">Limpar Histórico</button>
        </form>
    </div>

    <?php
    if (isset($_POST['clearHistory'])) {
        // Limpa o histórico do chat
        unset($_SESSION['chatMessages']);

        // Limpa o histórico das últimas análises
        unset($_SESSION['latestAnalyses']);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
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