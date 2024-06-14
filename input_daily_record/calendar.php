<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>禁酒アプリ - カレンダー</title>
    <link rel="stylesheet" href="calendar.css">
</head>

<body>
    <div class="container">
        <div class="sidebar">
            <h2>禁酒アプリ</h2>
            <ul>
                <li><a href="#">ホーム</a></li>
                <li><a href="#">カレンダー</a></li>
                <li><a href="#">？？</a></li>
                <li><a href="#">？？</a></li>
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
                        
                        // 毎日の記録が入力されていたらスタンプが表示される
                        calendarHTML += `<td class="${isToday ? 'today' : ''}">${dayCount}</td>`;
                        // 一時的にコメントアウト
                        // calendarHTML += `<td class="${isToday ? 'today' : ''}" onclick="handleDayClick(${dayCount})">${dayCount}</td>`;
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
    <?php
        require_once __DIR__.'/daily_record_classes/daily_record_method.php';
        $dailydata = new dailyData();
        $datearray = $dailydata->get_date();
    ?>
</body>

</html>