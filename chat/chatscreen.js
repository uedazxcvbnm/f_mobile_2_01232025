document.addEventListener("DOMContentLoaded", function () {
    // 戻るボタンのクリックイベント
    document.getElementById("back-button").addEventListener("click", function () {
        // 仮の画面推移先への遷移
        window.location.href = "test.html";
    });

    // 仮のデータ
    const groupName = "サンプルグループ";
    const groupMembers = 5;

    // HTML要素にデータをセット
    document.getElementById("group-name").textContent = groupName;
    document.getElementById("group-members").textContent = `(${groupMembers})`;
});
