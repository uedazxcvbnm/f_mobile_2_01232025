<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>禁酒アプリ - グラフ</title>
    <link rel="stylesheet" href="graph.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/date-fns"></script>

    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>
    <style>
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 4px;
        }

        .container {
            display: flex;
        }

        .sidebar {
            width: 20%;
            background-color: #4CAF50;
            padding: 20px;
            box-sizing: border-box;
            height: 100vh;
            color: white;
        }

        .main-content {
            width: 80%;
            padding: 20px;
            box-sizing: border-box;
        }

        h2 {
            margin-top: 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="sidebar">
            <h2>禁酒アプリ</h2>
            <ul>
                <li><a href="#">ホーム</a></li>
                <li><a href="./../calendar/calendar.php">カレンダー</a></li>
                <li><a href="#">？？</a></li>
                <li><a href="#">？？</a></li>
            </ul>
        </div>
        <div class="main-content">
            <button id="prevWeek">先週</button>
            <button id="nextWeek">来週</button>
            <h2>酒を飲んだ量</h2>
            <canvas id="amountChart" width="400" height="200"></canvas>
            <h2>酒に使った金額</h2>
            <canvas id="costChart" width="400" height="200"></canvas>
            <h2>血圧</h2>
            <canvas id="pressureChart" width="400" height="200"></canvas>
        </div>
    </div>

    <script>
        const fetchData = async (startDate, endDate) => {
            try {
                const response = await fetch(`data.php?start=${startDate}&end=${endDate}`);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                const data = await response.json();
                console.log("Fetched Data:", data);
                return data;
            } catch (error) {
                console.error("Error fetching data:", error);
                return [];
            }
        };

        const updateCharts = (data) => {
            console.log("Data for Charts:", data);
            const labels = data.map(row => row.date);
            const amountData = data.map(row => row.account_drunk);
            const costData = data.map(row => row.money);
            const systolicData = data.map(row => row.sBP);
            const diastolicData = data.map(row => row.dBP);

            console.log("Labels:", labels);
            console.log("Amount Data:", amountData);
            console.log("Cost Data:", costData);
            console.log("Systolic Data:", systolicData);
            console.log("Diastolic Data:", diastolicData);

            amountChart.data.labels = labels;
            amountChart.data.datasets[0].data = amountData;
            amountChart.update();

            costChart.data.labels = labels;
            costChart.data.datasets[0].data = costData;
            costChart.update();

            pressureChart.data.labels = labels;
            pressureChart.data.datasets[0].data = systolicData;
            pressureChart.data.datasets[1].data = diastolicData;
            pressureChart.update();
        };

        document.getElementById('prevWeek').addEventListener('click', () => {
            currentStartDate = dateFns.subWeeks(currentStartDate, 1);
            currentEndDate = dateFns.endOfWeek(currentStartDate, {
                weekStartsOn: 1
            });
            const startDate = dateFns.format(currentStartDate, 'yyyy-MM-dd');
            const endDate = dateFns.format(currentEndDate, 'yyyy-MM-dd');
            fetchData(startDate, endDate).then(updateCharts);
        });

        document.getElementById('nextWeek').addEventListener('click', () => {
            currentStartDate = dateFns.addWeeks(currentStartDate, 1);
            currentEndDate = dateFns.endOfWeek(currentStartDate, {
                weekStartsOn: 1
            });
            const startDate = dateFns.format(currentStartDate, 'yyyy-MM-dd');
            const endDate = dateFns.format(currentEndDate, 'yyyy-MM-dd');
            fetchData(startDate, endDate).then(updateCharts);
        });

        let currentStartDate = dateFns.startOfWeek(new Date(), {
            weekStartsOn: 1
        });
        let currentEndDate = dateFns.endOfWeek(new Date(), {
            weekStartsOn: 1
        });
        const initialStartDate = dateFns.format(currentStartDate, 'yyyy-MM-dd');
        const initialEndDate = dateFns.format(currentEndDate, 'yyyy-MM-dd');
        fetchData(initialStartDate, initialEndDate).then(updateCharts);

        const amountCtx = document.getElementById('amountChart').getContext('2d');
        const amountChart = new Chart(amountCtx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: '酒を飲んだ量 (ml)',
                    data: [],
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1,
                    fill: false
                }]
            },
            options: {
                scales: {
                    x: {
                        type: 'time',
                        time: {
                            unit: 'day',
                            tooltipFormat: 'yyyy-MM-dd',
                            displayFormats: {
                                day: 'yyyy-MM-dd'
                            }
                        }
                    },
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
                labels: [],
                datasets: [{
                    label: '酒に使った金額 (円)',
                    data: [],
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1,
                    fill: false
                }]
            },
            options: {
                scales: {
                    x: {
                        type: 'time',
                        time: {
                            unit: 'day',
                            tooltipFormat: 'yyyy-MM-dd',
                            displayFormats: {
                                day: 'yyyy-MM-dd'
                            }
                        }
                    },
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
                labels: [],
                datasets: [{
                        label: '最高血圧',
                        data: [],
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1,
                        fill: false
                    },
                    {
                        label: '最低血圧',
                        data: [],
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1,
                        fill: false
                    }
                ]
            },
            options: {
                scales: {
                    x: {
                        type: 'time',
                        time: {
                            unit: 'day',
                            tooltipFormat: 'yyyy-MM-dd',
                            displayFormats: {
                                day: 'yyyy-MM-dd'
                            }
                        }
                    },
                    y: {
                        beginAtZero: false
                    }
                }
            }
        });
    </script>
</body>

</html>