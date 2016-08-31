# Insert

>	新增一筆資料

>		INSERT INTO table_name 
>		(col1, col2)
>		VALUES
>		('1', 'A');

>	新增多筆資料 (速度比 2 個敘述句快很多)

>		INSERT INTO table_name
>		(col1, col2)
>		VALUES
>		('1', 'A'),
>		('2', 'B');

>	寫入某個表格的資料

>		INSERT INTO tab1
>		SELECT * FROM tab2;

## ERROR 1062 (23000): Duplicate entry '1-5' for key 1

>		INSERT INTO table_name (col1, col2) VALUES ('1', 'A')
>		ON DUPLICATE KEY UPDATE last_mod = now(); -- 發生 Duplicate key 的補救方法



