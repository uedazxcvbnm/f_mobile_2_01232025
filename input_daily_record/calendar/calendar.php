<?php
session_start();
// ログインしていないときの処理
if (!isset($_SESSION['user_id'])){
    header('Location: ./../../login/login_display.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <?php
        require_once __DIR__ . '/../header_graph.php';
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>禁酒アプリ - カレンダー</title>
    <link rel="stylesheet" href="calendar.css">
</head>

<body>
    <?php
        $user_id = $_SESSION['user_id'];

        // カレンダーの日付を取得（記録をしたor）
        require_once __DIR__.'/../classes/daily_record_method.php';
        $dailydata = new dailyData();
        $datearray = $dailydata->get_date($user_id);
        $datearray_json = json_encode($datearray);

        require_once __DIR__.'/../classes/daily_record_method.php';
        $dailydata = new dailyData();
        $alccountarray = $dailydata->get_alccount_calendar($user_id);
        $alccountarray_json = json_encode($alccountarray);
        
    ?>
    <div class="container">
        <div class="sidebar">
            <h2>禁酒アプリ</h2>
            <ul>
                <!-- <li><a href="#">ホーム</a></li> -->
                <li><a href="./../graph/graph_count.php">グラフを見る</a></li>
            </ul>
        </div>
        <div class="main-content">
            <div class="calendar-controls">
                <button id="prev-month" class="month-btn">&lt;</button>
                <span id="month-year"></span>
                <button id="next-month" class="month-btn">&gt;</button>
            </div>
            <div id="calendar" class="calendar-wrap"></div>
        </div>
    </div>

    <script>
        const date = new Date();
        let currentYear = date.getFullYear();
        let currentMonth = date.getMonth();

        const datearray = JSON.parse('<?php echo $datearray_json; ?>');
        const alccountarray = JSON.parse('<?php echo $alccountarray_json; ?>');


        function createCalendar(year, month) {
            const monthDays = ["日", "月", "火", "水", "木", "金", "土"];
            let calendarHTML = '<table class="calendar"><thead><tr>';

            for (let i = 0; i < 7; i++) {
                if (i === 0) {
                    calendarHTML += `<th class="sun">${monthDays[i]}</th>`;
                } else if (i === 6) {
                    calendarHTML += `<th class="sat">${monthDays[i]}</th>`;
                } else {
                    calendarHTML += `<th>${monthDays[i]}</th>`;
                }
            }

            calendarHTML += '</tr></thead><tbody>';

            const daysInMonth = new Date(year, month + 1, 0).getDate();
            // console.log(daysInMonth);
            const firstDay = new Date(year, month, 1).getDay();
            const daysInPrevMonth = new Date(year, month, 0).getDate();

            let dayCount = 1;
            let prevDayCount = daysInPrevMonth - firstDay + 1;

            for (let i = 0; i < 6; i++) {
                calendarHTML += '<tr>';

                for (let j = 0; j < 7; j++) {
                    if (i === 0 && j < firstDay) {
                        calendarHTML += `<td class="mute">${prevDayCount}</td>`;
                        prevDayCount++;
                    } else if (dayCount > daysInMonth) {
                        let nextMonthDayCount = dayCount - daysInMonth;
                        calendarHTML += `<td class="mute">${nextMonthDayCount}</td>`;
                        dayCount++;
                    } else {
                        const today = new Date();
                        const isToday = dayCount === today.getDate() && month === today.getMonth() && year === today.getFullYear();
                        
                        // console.log(currentMonth);
                        // datearray.includesでスタンプを表示
                        let currentMonth_comparsion = currentMonth + 1;

                        if(currentMonth_comparsion<10){
                            currentMonth_comparsion = '0'+currentMonth_comparsion;
                        }

                        if (dayCount>=1 && dayCount<10){
                            daycount_comparsion = '0'+dayCount;
                        }
                        else{
                            daycount_comparsion = dayCount;
                        }
                        today_comparsion = currentYear+'-'+currentMonth_comparsion+'-'+daycount_comparsion;
                        console.log(today_comparsion);
                        
                        console.log(datearray);
                        // console.log(today_comparsion);
                        // console.log(alccountarray);
                        // console.log(datearray[alccountarray.indexOf(0)]);

                        // i=0;
                        // i = i+1;
                        // while(i<2){
                            
                        // }

                        // 一時的にコメントアウト
                        // calendarHTML += `<td class="${isToday ? 'today' : ''}" onclick="handleDayClick(${dayCount})">${dayCount}</td>`;
                        

                        // 今日の日付を取得
                        const today_date_calendar = new Date();
                        today_date_calendar.setHours(0, 0, 0, 0);
                        // console.log(today_date_calendar);
                        console.log(today_comparsion > today_date_calendar);

                        calendarHTML += `<td class="${isToday ? 'today' : ''}">${dayCount}<br>

                        ${today_comparsion==datearray[alccountarray.indexOf(0)] ?'<div class="blue_circle"></div>'
                        : datearray.includes(today_comparsion)?'<div class="red_circle"></div>'
                        : new Date(today_comparsion) < today_date_calendar ?'<div class="orange_circle"></div>':''}
                        </td>`
                        dayCount++;
                    }
                }
                calendarHTML += '</tr>';

                if (dayCount - daysInMonth > 7) {
                    break;
                }
            }

            calendarHTML += '</tbody></table>';

            return calendarHTML;
        }

        // console.log(typeof datearray);

        
        // console.log(Object.values(datearray));
        
        
        

        function handleDayClick(day) {
            alert('日付: ' + day);
            // ここにクリックイベントの処理を追加
        }

        function renderCalendar() {
            const calendarContainer = document.getElementById('calendar');
            const monthYearLabel = document.getElementById('month-year');

            monthYearLabel.textContent = `${currentYear}年 ${currentMonth + 1}月`;
            calendarContainer.innerHTML = createCalendar(currentYear, currentMonth);
        }

        document.getElementById('prev-month').addEventListener('click', () => {
            if (currentMonth === 0) {
                currentMonth = 11;
                currentYear--;
            } else {
                currentMonth--;
            }
            renderCalendar();
        });

        document.getElementById('next-month').addEventListener('click', () => {
            if (currentMonth === 11) {
                currentMonth = 0;
                currentYear++;
            } else {
                currentMonth++;
            }
            renderCalendar();
        });

        renderCalendar();
    </script>
    
</body>

</html>