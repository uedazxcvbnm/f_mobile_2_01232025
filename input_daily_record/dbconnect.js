const mysql = require('mysql');
 
const connection = mysql.createConnection({
  host: "localhost",
  user: "kobe",
  password: "denshi",
  database: "pbl2"
});
 
connection.connect(function(err) {
  if (err) throw err;
  console.log("Connected to MySQL DB!");
  
  /// 例 : usersテーブルの全レコード取得
  const sql = `SELECT date from input_daily_record`
  connection.query(sql, function (err, result) {
    if (err) throw err;
    /// 全レコード表示
    // console.log("Result: ", result);
    res.render('index.ejs',{items:results});
  });
});

http.createServer(function(req, res) {
  if(req.method === 'GET') {
    res.writeHead(200, {'Content-Type' : 'text/html'});
    res.end(html);
  } 
  else if(req.method === 'POST') {
    var data = '';
    
    //POSTデータを受けとる
    req.on('data', function(chunk) {data += chunk})
        .on('end', function() {

          console.log(data);
          res.end(html);

        })

  }
}).listen(3000);