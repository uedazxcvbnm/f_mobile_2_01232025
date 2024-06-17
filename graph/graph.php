<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>禁酒アプリ - グラフ</title>
    <link rel="stylesheet" href="graph.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <div class="container">
        <div class="sidebar">
            <h2>禁酒アプリ</h2>
            <ul>
                <li><a href="calendar.php">カレンダー</a></li>
                <li><a href="graphs.php">グラフ</a></li>
                <li><a href="#">？？</a></li>
                <li><a href="#">？？</a></li>
            </ul>
        </div>
        <div class="main-content">
            <h2>酒を飲んだ量</h2>
            <canvas id="amountChart"></canvas>
            <h2>酒に使った金額</h2>
            <canvas id="costChart"></canvas>
            <h2>血圧</h2>
            <canvas id="pressureChart"></canvas>
        </div>
    </div>

    <script>
        const days = ['1日目', '2日目', '3日目', '4日目', '5日目', '6日目', '7日目'];

        const amountCtx = document.getElementById('amountChart').getContext('2d');
        const amountChart = new Chart(amountCtx, {
            type: 'line',
            data: {
                labels: days,
                datasets: [{
                    label: '酒を飲んだ量 (リットル)',
                    data: [0.2, 0.3, 0.1, 0.4, 0.0, 0.0, 0.0],
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1,
                    fill: false
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        const costCtx = document.getElementById('costChart').getContext('2d');
        const costChart = new Chart(costCtx, {
            type: 'line',
            data: {
                labels: days,
                datasets: [{
                    label: '酒に使った金額 (円)',
                    data: [500, 800, 200, 1000, 0, 0, 0],
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1,
                    fill: false
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        const pressureCtx = document.getElementById('pressureChart').getContext('2d');
        const pressureChart = new Chart(pressureCtx, {
            type: 'line',
            data: {
                labels: days,
                datasets: [{
                        label: '最高血圧',
                        data: [120, 125, 130, 128, 127, 126, 124],
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1,
                        fill: false
                    },
                    {
                        label: '最低血圧',
                        data: [80, 82, 85, 83, 82, 81, 80],
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1,
                        fill: false
                    }
                ]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: false
                    }
                }
            }
        });
    </script>
</body>

</html>