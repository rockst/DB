# 資料產生、轉換和操作

## 字串

* '' 產生字串
* CHAR 255
* VARCHAR 65535
* TEXT (tinytext,text,mediumtext,longtext) ~ 4G

* 如果 Column 字串長度不夠輸入的資料寫入會產生下面訊息

>	ERROR 1406(22001): Data too long for column 'column_name' at row 1

>	因為 MySQL 預設是 'strict' 嚴格的模式，可使用 SET 來修改

>		SELECT @@session.sql_mode;

>		SET sql_mode='ansi';

* 處理資料中有單引號的問題

>	'This string doesn''t work' -- 加上另一個單引號

>	'This string doesn\'t work' -- 加上反斜線

>	SELECT quote(col1) -- 產生脫逸自元

* 特殊字元處理：CHAR(ASCII碼1,ASCII碼2), ASSCII('')

* CONCAT() 串連字元

>		SELECT CONCAT(col1, '123', '456');

>		SET col1 = CONCAT(col1, 'add char')

* INSERT() 插入字元

>		INSERT('原來的字串', 9, 0, '取代字串')


* SUBSTRING 截取字串
* LENGTH() 字串長度
* POSITION('rock' IN col1) 尋找字串位置
* LOCATE('rock', col1, 3) -- 從第3個位置開始找
* STRCMP() 字串比較

## 數字

* MOD(10, 4) = 2 餘數
* POW(2, 3) = 8 2的3次方
* CEIL(72.4) = 73 向上進位
* FLOOR(72.4) = 72 向下捨去
* ROUND(72.4) = 72 四捨五入 ROUND(72.45, 1) = 72.5
* TRUNCATE(72.45, 1) = 72.4

## 日期時間

* GMT 格林威治時間
* UTC 國際標準時間

>		SELECT @@global.time_zone, @@session.time_zone;

>		system 表示使用資料庫所在地來使用時區設定

>		SET time_zone = '';

>		SELECT CURRENT_TIMESTAMP();

* Date YYYY-MM-DD
* Datetime YYYY-MM-DD HH:MI:SS
* Timestamp YYYY-MM-DD HH:MI:SS
* Time HH:MI:SS

### date_add() 增加日期

>		SELECT DATE_ADD(CURRENT_DATE(), INTERVAL 1 DAY); -- 增加一天
>			Second 秒
>			Minute 分
>			Hour 時
>			Day 天
>			Month 月
>			Year 年
>			Minute_second 分和秒 INTERVAL '10:01' Minute_second
>			Hour_second 時和秒
>			Year_month 年和月 INTERVAL '1-1' Year_month

* last_day() 當月最後一天
* convert_tz() 轉換時區

>		SELECT CONVERT_TZ(CURRENT_TIMESTAMP(), 'US/Eastern', 'UTC');

* dayname() 星期幾
* extract() 截取值

>		SELECT EXTRACT(YEAR FROM '2016-01-01'); -- 2016

* datediff(date1, date2) 比較兩日期的天數



