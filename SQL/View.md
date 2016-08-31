# View

## ADD

> 		CREATE VIEW view_name AS
> 			SELECT col1 FROM tabl1;

>		CREATE VIEW view_name 
>		(
>			id, name, type
>		)
>		AS
>		SELECT a.id, a.name, a.type FROM table a INNER JOIN table2 b ON (b.id = a.id) WHERE a.type = 1;

## 時機

* 資安
* 資料彙總
* 簡單化資料表聯結
* 聯結資料分割

## 更新 View 條件

* 沒有使用彙總函數 max(), min(), ....
* 沒有使用 group by, having
* 沒有使用 Subquery
* 沒有使用 union, union all 和 distinct



