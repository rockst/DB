# Contraint 條件約束

## 建立

>		CREATE TABLE table_name (
>			id		int(10) NOT NULL,
>			type_id int(10) NOT NULL,
>			CONSTRAINT fk_type_id FOREIGN KEY (type_id) REFERENCES type_table_name (type_id), -- 外部鍵
>			CONSTRAINT pk_id PRIMARY KEY (id) -- 主鍵
>		);



 