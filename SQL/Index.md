# Index 索引

## Add Index

>		SHOW INDEX FROM table;
>		ALTER TABLE table_name
>			ADD INDEX index_name_idx (column_name);
>	多行索引

>		ALTER TABLE table_name
>			ADD INDEX index_name_idx (col1_name, col2_name);

## Drop index

>		ALTER TABLE table_name
>			DROP INDEX index_name_idx;

### UNIQUE : 不重覆欄位

>		ALTER TABLE table_name
>			ADD UNIQUE index_name_idex (column_name);

* B-Tree Index default
* 點陣圖 Index

## EXPLAIN

>	要求DB查詢敘述句的規劃

>		EXPLAIN SELECT column_name FROM table_name WHERE id IN (1, 3, 5);

>	rows: 8 -- 可以得知只需要 8 列

## 索引的特性

* 每一個索引都是一張資料表
* 新增、修改、刪除都會去處理索引資料表
* 索引越多會拖慢其速度
* 索引需要磁碟空間

## 時機

* 清楚的需求才會使用
* 外部鍵
* 頻繁的被拿來使用的欄位，如日期