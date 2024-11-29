<?php
session_start();
// ログインしていないときの処理
if (!isset($_SESSION['user_id'])) {
    header('Location: ./../../login/login_display.php');
    exit();
}
?>

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

    <?php
    require_once __DIR__ . '/../../header/header.php';
    ?>
</head>

<body>

    <div class="container">
        <div class="sidebar">
            <h2>禁酒アプリ</h2>
            <ul>
                <li><a href="./../calendar/calendar.php">カレンダーを見る</a></li>
            </ul>
        </div>
        <div class="main-content">
            <div class="controls">
                <button class="week_change" id="prevWeek">先週</button>
                <button class="week_change" id="nextWeek">来週</button>
            </div>
            <h2>一日に酒を飲んだ回数</h2>
            <canvas id="amountChart"></canvas>
            <?php
            $today_date = date('Y-m-d');
            echo '<textarea id="date_output" readonly>選択された日付がここに表示されます</textarea>';

            echo '<div id="date_select">';
            echo '<button value=1 id="today_button" class="alchol_timelist">今日</button>';
            echo '<button value=2 id="yesterday_button" class="alchol_timelist">昨日</button>';
            echo '<button value=3 id="b_yesterday_button" class="alchol_timelist">おととい</button>';
            echo '</div>';

            $selected_date = $today_date;
            $user_id = $_SESSION['user_id'];
            require_once __DIR__ . '/../classes/daily_record_method.php';
            $dailyData = new dailyData();
            $yesno_items = $dailyData->get_yesno_time($selected_date, $user_id);

            echo '<table id="output_table">';
            echo '<tr>';
            echo '<th>飲酒の有無</th>';
            echo '<th>時刻</th>';
            echo '</tr>';
            foreach ($yesno_items as $yesno_item) {
                echo '<tr>';
                if ($yesno_item['alchol_data'] == 1) {
                    echo '<td>飲酒した</td>';
                } elseif ($yesno_item['alchol_data'] == 2) {
                    echo '<td>飲酒を我慢した</td>';
                }
                echo '<td>' . htmlspecialchars($yesno_item['date_hms'], ENT_QUOTES, 'UTF-8') . '</td>'; // 安全输出
                echo '</tr>';
            }
            echo '</table>';
            ?>
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
            const amountData = data.map(row => row.alchol_count);
            // const costData = data.map(row => row.money);
            // const systolicData = data.map(row => row.sBP);
            // const diastolicData = data.map(row => row.dBP);

            console.log("Labels:", labels);
            console.log("Amount Data:", amountData);
            // console.log("Cost Data:", costData);
            // console.log("Systolic Data:", systolicData);
            // console.log("Diastolic Data:", diastolicData);

            amountChart.data.labels = labels;
            console.log(amountChart);

            amountChart.data.datasets[0].data = amountData;
            amountChart.update();

            // 酒に使った金額
            // costChart.data.labels = labels;
            // costChart.data.datasets[0].data = costData;
            // costChart.update();

            // 高血圧と低血圧
            // pressureChart.data.labels = labels;
            // pressureChart.data.datasets[0].data = systolicData;
            // pressureChart.data.datasets[1].data = diastolicData;
            // pressureChart.update();
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
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1 // グラフの縦軸の刻み幅を1に設定
                        }
                    }
                }
            }
        });

        // jsで今日の日付を取得

        // 
        // const dateInput = document.getElementById('date_select');
        const dateInput = document.getElementById('date_select');
        const dateOutput = document.getElementById('date_output');
        const output_table = document.getElementById('output_table');


        // ボタンを押すと変数に値を格納
        var today_button = document.getElementById('today_button');
        var yesterday_button = document.getElementById('yesterday_button');
        var b_yesterday_button = document.getElementById('b_yesterday_button');

        dateOutput.addEventListener('input', async function() {
            var selectedDate = new Date(this.value);
            // console.log(selectedDate);
            selectedDate.getDate

            var selected_year = selectedDate.getFullYear();
            var selected_month = String(selectedDate.getMonth() + 1).padStart(2, '0'); // 月は0から始まるので1足す
            var selected_day = String(selectedDate.getDate()).padStart(2, '0');
            try {
                var response = await fetch(`realtime_alcholtime.php?date=${selected_year}/${selected_month}/${selected_day}`);
                // console.log(response);
                if (!response.ok) {
                    console.log('232');
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                var selected_data = await response.json(); // PHPからのデータをJSON形式で受け取る
                console.log("取得したデータ:", selected_data);

                output_table.innerHTML = '';

                const header = output_table.createTHead();
                // const alcholHeader = header.insertCell(0);
                // const otherDataHeader = header.insertCell(1);
                const headerRow = header.insertRow(0); // ヘッダーの最初の行を作成
                const alcholHeader = document.createElement('th');
                const dateHeader = document.createElement('th');

                alcholHeader.textContent = '飲酒の有無';
                dateHeader.textContent = '時刻';

                // ヘッダー行に各カラム名を追加
                headerRow.appendChild(alcholHeader);
                headerRow.appendChild(dateHeader);

                // 取得したデータをテーブルに反映
                selected_data.forEach(item => {
                    const row = output_table.insertRow();
                    const dateCell = row.insertCell(0);
                    const alcholCell = row.insertCell(0);
                    const otherDataCell = row.insertCell(1);





                    // dateCell.textContent = item.date; // 日付のプロパティ名に合わせる
                    if (item.alchol_data == 1) {
                        alcholCell.textContent = '飲酒した'; // アルコール量のプロパティ名に合わせる
                    } else if (item.alchol_data == 2) {
                        alcholCell.textContent = '飲酒しなかった'; // その他のデータのプロパティ名に合わせる
                    }
                    otherDataCell.textContent = item.date_hms;
                });

                // 取得したデータをtextareaに反映
                // output_table.value = JSON.stringify(selected_data, null, 2);
            } catch (error) {
                console.error("データ取得エラー:", error);
                output_table.value = "データの取得に失敗しました";
            }
        });

        // document.addEventListener('DOMContentLoaded', function() {
        today_button.addEventListener("click", async function() {
            const today_alcholList = new Date();
            today_alcholList.setHours(0, 0, 0, 0);
            toggleSelected_alcholList(today_button, yesterday_button, b_yesterday_button);
            dateOutput.textContent = today_alcholList;
            // 日付を取得
            const selectedDate = today_alcholList;

            dateOutput.dispatchEvent(new Event('input'));

            // console.log('a');
            // 関数呼び出し　PHPにリクエストを送る
            // dateOutput.addEventListener('DOMContentLoaded', async function() {
            //     // php_send_selectedDate();
            // })
        });

        yesterday_button.addEventListener("click", async function() {
            const today_alcholList = new Date();
            today_alcholList.setHours(0, 0, 0, 0);
            toggleSelected_alcholList(yesterday_button, today_button, b_yesterday_button);
            date_output.textContent = new Date(today_alcholList.setDate(today_alcholList.getDate() - 1));
            // console.log(output.textContent);

            console.log(dateOutput);

            dateOutput.dispatchEvent(new Event('input'));

            // 日付を取得
            // dateOutput.addEventListener('DOMContentLoaded', async function() {
            //     // php_send_selectedDate();
            // })

            // console.log(selectedDate);
            // 関数呼び出し　PHPにリクエストを送る
            // dateOutput.addEventListener('change', async function() {
            //     php_send_selectedDate(selectedDate);
            // })
        });

        b_yesterday_button.addEventListener("click", function() {
            const today_alcholList = new Date();
            today_alcholList.setHours(0, 0, 0, 0);
            toggleSelected_alcholList(b_yesterday_button, today_button, yesterday_button);
            date_output.textContent = new Date(today_alcholList.setDate(today_alcholList.getDate() - 2));

            // console.log(dateOutput);

            console.log(dateOutput);

            dateOutput.dispatchEvent(new Event('input'));
            // const selectedDate = new Date(this.value);
            // console.log(date_output.textContent);
            // 日付を取得
            // dateOutput.addEventListener('change', async function() {

        });
        // })


        // PHPにリクエストを送る
        // async function php_send_selectedDate(){
        //     const selectedDate = new Date(this.value);
        //     console.log(selectedDate);
        //     try {
        //         console.log('a');
        //         const response = await fetch(`realtime_alcholtime.php?date=${selectedDate}`);
        //         if (!response.ok) {
        //             throw new Error(`HTTP error! status: ${response.status}`);
        //         }
        //         const data = await response.json();  // PHPからのデータをJSON形式で受け取る
        //         console.log("取得したデータ:", data);

        //         // 取得したデータを画面に反映
        //         // output.innerHTML = `<pre>${JSON.stringify(data, null, 2)}</pre>`;
        //     } catch (error) {
        //         console.error("データ取得エラー:", error);
        //         // output.innerHTML = "データの取得に失敗しました";
        //     }
        // }


        function toggleSelected_alcholList(selectedButton, otherButton1, otherButton2) {
            selectedButton.classList.add('selected');
            otherButton1.classList.remove('selected');
            otherButton2.classList.remove('selected');
        }

        // ここ以降のjsは使わない
        // ２つ目のグラフ
        // const costCtx = document.getElementById('costChart').getContext('2d');
        // const costChart = new Chart(costCtx, {
        //     type: 'line',
        //     data: {
        //         labels: [],
        //         datasets: [{
        //             label: '酒に使った金額 (円)',
        //             data: [],
        //             borderColor: 'rgba(153, 102, 255, 1)',
        //             borderWidth: 1,
        //             fill: false
        //         }]
        //     },
        //     options: {
        //         scales: {
        //             x: {
        //                 type: 'time',
        //                 time: {
        //                     unit: 'day',
        //                     tooltipFormat: 'yyyy-MM-dd',
        //                     displayFormats: {
        //                         day: 'yyyy-MM-dd'
        //                     }
        //                 }
        //             },
        //             y: {
        //                 beginAtZero: true
        //             }
        //         }
        //     }
        // });

        // // ３つ目のグラフ
        // const pressureCtx = document.getElementById('pressureChart').getContext('2d');
        // const pressureChart = new Chart(pressureCtx, {
        //     type: 'line',
        //     data: {
        //         labels: [],
        //         datasets: [{
        //                 label: '最高血圧',
        //                 data: [],
        //                 borderColor: 'rgba(255, 99, 132, 1)',
        //                 borderWidth: 1,
        //                 fill: false
        //             },
        //             {
        //                 label: '最低血圧',
        //                 data: [],
        //                 borderColor: 'rgba(54, 162, 235, 1)',
        //                 borderWidth: 1,
        //                 fill: false
        //             }
        //         ]
        //     },
        //     options: {
        //         scales: {
        //             x: {
        //                 type: 'time',
        //                 time: {
        //                     unit: 'day',
        //                     tooltipFormat: 'yyyy-MM-dd',
        //                     displayFormats: {
        //                         day: 'yyyy-MM-dd'
        //                     }
        //                 }
        //             },
        //             y: {
        //                 beginAtZero: false
        //             }
        //         }
        //     }
        // });
    </script>
</body>

</html>