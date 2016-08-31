#Subquery

##單行單列

>		SELECT col1 FROM tab1 WHERE id = (SELECT max(id) FROM tab2);

##多行單列

>		SELECT col1 FROM tab1 WHERE name IN (SELECT name FROM tab2 WHERE age > 18)
>		SELECT col1 FROM tab1 WHERE name NOT IN (SELECT name FROM tab2 WHERE age > 18)

* ALL 配合 >, <, <>, = 使用

>		SELECT col1 FROM tab1 WHERE name <> ALL (SELECT name FROM tab2 WHERE age > 18)
>		上述等於 NOT IN
>		WHERE price < ALL (SELECT price FROM tab2)

* ANY 等於 IN

##Update or DELETE

>		UPDATE tab1 a
>		SET a.price = (SELECT SUM(price) FROM tab2 WHERE name = 'tool')
>		WHERE a.id = 1;

##JOIN

>		SELECT id
>		FROM tab1 a INNER JOIN
>			(SELECT id FROM tab2) b
>		ON a.id = b.id;

