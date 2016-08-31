# Update

>	修改某個欄位

>		UPDATE table_name SET col1 = 'test' WHERE id = 1;

>	Subquery

>		UPDATE table_name SET col1 = (SELECT MAX(id) FROM table_name) WHERE id = 1;

>	修改排序後的 10 筆資料

>		UPDATE table_name SET col1 = 'test' WHERE type = 1 ORDER BY id LIMIT 10;