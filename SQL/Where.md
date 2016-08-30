# Where

* 使用小括號

>		WHERE col1 = 1 AND (col2 = 1 OR col3 = 0)

* not 運算子，結果與上列相反

>		WHERE NOT (col1 = 1 AND (col2 = 1 OR col3 = 0)

* = , <> or !=

>		WHERE col1 = 'rock'
>		WHERE col1 != 'rock' OR col2 <> 'F'

* BETWEEN 範圍條件

>		WHERE start_date < '2016-01-01' AND start_date > '2016-12-31'
>		WHERE start_date BETWEEN '2016-01-01' AND '2016-12-31'
>		WHERE price BETWEEN 100 AND 500;
>		WHERE see_id BETWEEN '500-00-000' AND '900-00-000';

* IN 成員條件

>		WHERE name IN ('rock', 'peter');

* IN + subquery

>		WHERE name IN (SELECT stu_name FROM student WHERE gender = 'M')

* 比對條件 like

>	\_ 正好一個字元 ex. '_ock' = 'rock' or 'Lock'

>	% 任意數目 ex. '%ock'

>		WHERE LEFT(name, 1) = 'R';
>		WHERE name LIKE '_ock';
>		WHERE name LIKE 'R%';

* 比對條件使用正規表式示

>		WHERE name REGEXP '^[rR]ock';

* NULL

>		WHERE stu_desc IS NULL;
>		WHERE stu_desc IS NOT NULL;


 