# 動態產生 SQL

>		SET @qry = 'SELECT * FROM table_name';
>		PREPARE dynsql1 FROM @qry;
>		EXECUTE dynsql1;

## 預留位置

>		SET @qry = 'SELECT * FROM table_name WHERE id = ?';
>		PREPARE dynsql2 FROM @qry;
>		SET @id = 1;
>		EXECUTE dynsql2 USING @id;

## 刪除

>		DEALLOCATE PREPARE dynsql2;