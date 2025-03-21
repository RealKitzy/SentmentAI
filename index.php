<!DOCTYPE html>
<html>
<head>
    <title>Painel de Controle</title>
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
            foreach ($latestAnalyses as $analysis) {
                echo "<tr>";
                echo "<td>" . $analysis['text'] . "</td>";
                echo "<td class='" . $analysis['sentiment'] . "'>" . ucfirst($analysis['sentiment']) . "</td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>