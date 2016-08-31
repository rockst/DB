# SELECT Query

* 查詢基本語法

>		SELECT VERSION(), USER(), DATABASE(); 
>		SELECT coulums FROM table_name;
>		SELECT col1 as 'alias1' FROM table_name;

* 去除重覆

>		SELECT DISTINCT col1 FROM table_name;
>		SELECT col1 FROM table_name GROUP BY col1;

## Subquery

* Subquery 產生暫存 Table e

>		SELECT e.col1 FROM (SELECT col2 FROM tab2) e;

## Join

>		SELECT col1
>		FROM tab1 INNER JOIN tab2 ON (col1 = col2)

* INNER JOIN : 必需要有關聯才會取出

>		FROM tab1 a INNER JOIN tab2 b ON (a.id = b.id)
>		FROM tab1 a JOIN tab2 b ON (a.id = b.id) -- 預設使用 INNER JOIN
>		FROM tab1 INNER JOIN tab2 USING (id) -- USING 取代 ON

* INNER JOIN + Subquery

>		FROM account a 
>			INNER JOIN
>				(SELECT emp_id, branch_id FROM employee WHERE start_date > '2016-01-01') e
>			ON a.emp_id = e.emp_id
>			INNER JOIN
>				(SELECT branch_id FROM branch WHERE name = 'abc') b
>			ON e.branch_id = b.branch_id


* LEFT OUTER JOIN : 有關聯和沒關聯都會取出

>		FROM tab1 a
>			LEFT OUTER JOIN tab2 b
>			ON (a.id = b.id)

* RIGHT OUTER JOIN

>		FROM tab2 b
>			RIGHT OUTER JOIN tab1 a
>			ON (b.id = a.id)

* CROSS JOIN : 交叉聯結
* NATURAL JOIN : 自然聯結

## Group by

* 計算至少傳回 2 個員工數的部門

>		SELECT d.name as 'dep_name', count(e.emp_id) as 'emp_cnt'
>		FROM department d INNER JOIN employee e ON (d.dep_id = e.dep_id)
>		GROUP BY d.name
>		HAVING count(e.emp_id) > 2

* MAX() 最大值
* MIN() 最小值
* AVG() 平均值
* SUM() 總和
* COUNT() 個數

>		SELECT COUNT(DISTINCT id) FROM table; -- 不重覆



## Order by

>		ORDER BY col1;
>		ORDER BY col1 DESC; 

* 根據運算式

>		fed_id
>			111-11
>			04-111
>			22-222
>		ORDER BY RIGHT(fed_id, 3);

* 預留位置 col1 = 1, col2 = 2

>		SELECT col1, col2 FROM tab1 ORDER BY 1, 2;

## 集合

* UNION : 聯集, 組合多個資料集，去除重覆項目，並進行排序

>		SELECT col1 FROM tab1
>		UNION
>		SELECT col2 FROM tab2

* UNION ALL: 聯集, 組合多個資料集，保留重覆項目，並進行排序

* INTERSECT 交集

>	MySQL 6.0 尚未實作

>		SELECT emp_id FROM employee1 WHERE gender = 'M'
>		INTERSECT
>		SELECT emp_id FROM employee2 WHERE age > 18

* except 差集

>	MySQL 6.0 尚未實作

## 條件邏輯

>		SELECT id,
>			CASE
>				WHEN type = '1' THEN 'type1'
>				WHEN type = '2' THEN 'type2'
>				ELSE 'Unknown'
>			END type_name
>		FROM table;

* +Subquery

>		SELECT id,
>			CASE
>				WHEN type = '1' THEN (SELECT name1 FROM tab2 WHERE type = 1)
>				WHEN type = '2' THEN 'type2'
>				ELSE 'Unknown'
>			END type_name
>		FROM table;


* 某個欄位

>		SELECT
>			CASE `col_type`
>				WHEN '1' THEN 'type1'
>				WHEN '2' THEN 'type2'
>				ELSE 'Unknown'
>			END
>		FROM table;

* EXISTS

>		SELECT
>			CASE
>				WHEN EXISTS (SELECT 1 FROM tab2 WHERE name = 'chk') THEN 'Y' 
>				ELSE 'N'
>			END chk_status
>		FROM table;

* Update

>		UPDATE table
>			SET last_price = (
>				SELECT 
>					CASE
>						WHEN price > 1000 THEN 1000
>						ELSE 0
>					END final_price
>				FROM tab2);  

