show databases;

use dev_test;

CREATE TABLE customers(
-- unsigned = 이 열에 들어가는 데이터는 0이상의 양수여야함
customerID int unsigned not null auto_increment primary key,
name char(50) not null,
address char(100) not null,
city char(30) not null
);

CREATE TABLE orders(
orderID int unsigned not null auto_increment primary key,
customerID int unsigned not null,
amount float not null,
date date not null
);

-- ALTER TABLE BOOKS ADD CustomerID INT NOT NULL;
-- books 테이블에 아이디를 인트형, 빈값 불가 형태로 칼럼 추가
CREATE TABLE books(
isbn char(13) not null primary key,
author char(50),
title char(100),
price float
);

CREATE TABLE order_items(
orderID int unsigned not null,
isbn char(13) not null,
-- tinyint=0~255 사이의 정수값
quantity tinyint unsigned,
primary key(orderID,isbn)
);

CREATE TABLE book_reviews(
isbn char(13) not null primary key,
review text
);
ALTER TABLE customers auto_increment = 3;
insert into customers(name, address, city) VALUE
	('Julie Smith','25 Oak Street', 'Airport West'),
	('Alan Wong','1/47 Haines Avenue','Box Hill'),
	('Michelle Arthur','357 North Road','Yarraville');
select * from customers;

insert into orders(customerID, amount, date) VALUES
	('3', 69.98, '2007-04-02'),
	('1', 49.99, '2007-04-15'),
	('2', 74.98, '2007-04-19'),
	('3', 24.99, '2007-05-01');
select * from orders;

INSERT INTO books(ISBN,author,title,price) VALUES
	('0-672-31697-8', 'Michael Morgan', 'Java 2 for Professional Developers',34.99),
	('0-672-31745-1', 'Thomas Down','Installing GNU/Linux',24.99),
	('0-672-31509-2', 'Pruitt, et al.', 'Teach Yourself GIMP in 24 Hours', 24.99),
	('0-672-31769-9', 'Thomas Schenk','Caldera OpenLinux System Administration Unleashed',49.99);
select * from books;

INSERT INTO order_items(orderID,isbn,quantity) VALUES
	(1,'0-672-31697-8',2),
	(2,'0-672-31769-9',1),
	(3,'0-672-31769-9',1),
	(3,'0-672-31509-2',1),
	(4,'0-672-31745-1',3);
SELECT * FROM order_items; 

insert into book_reviews values
	('0-672-31697-8','The Morgan book is clearly written and goes well beyond most of the basic Java books out there.');
select * from book_reviews; 

/** SELECT = 테이블, 열			DML
	INSERT = 테이블, 열			DML
	UPDATE = 테이블, 열			DML
    DELETE = 테이블				DML
    INDEX = 테이블
    ALTER = 테이블				DDL
    CREATE = 데이터베이스, 테이블	DDL
    DROP = 데이터베이스, 테이블		DDL **/
    
    

-- 테이블에 대한 정보 알고싶을 때 사용
describe books;
-- customers에서 name과 city 내역만 보고싶을 때
select name, city from customers;
-- orders에서 조건절을 사용해 ID가 3인 내역만 불러옴
select * from orders where customerID = 3;
-- CUSTOMER 내의 Julie Smith 행 삭제
DELETE FROM customers WHERE NAME='Julie Smith';
-- 자동증가 숫자 1로 수정
ALTER TABLE customers auto_increment = 1;
-- Julie Smith를 현민으로 바꿈
UPDATE customers set name = '현민' WHERE name='Julie Smith';
-- 아이디가 2와 3인 항목을 다 불러옴
select * from orders where customerID = 3 or CustomerID=2;


-- join ->이렇게 조인할 때 조건 붙여주는 것을 equi-join이라고 한다.
-- 고객 이름= julie smith, 그리고 customers,orders에서 customerID가 같은 부분 조회
select orders.orderID, orders.amount, orders.date
from customers,orders
where customers.name = 'Julie Smith'
and customers.customerID = orders.customerID;



-- 네가지 테이블 이용하여 조회 equi-join
select customers.name
from customers, orders, order_items, books
where customers.customerID = orders.customerID
and orders.orderID = order_items.orderID
and order_items.isbn = books.isbn
and books.title like '%Java%';

-- 별칭 (테이블명 as 별칭)
select c.name
from customers as c, orders as o, order_items as oi, books as b
where c.customerID = o.customerID
and o.orderID = oi.orderID
and oi.isbn = b.isbn
and b.title like '%Java%';

-- LEFT join customer전체에서 조회하는데 customer와 orders에서 customerID가 같은 것들을 모두 조회
select customers.customerID, customers.name, orders.orderID
from customers left join orders
on customers.customerID = orders.customerID;

-- 주문하지 않은 고객 뽑을 때
-- using 두 테이블 모두 customerID를 사용하고 있어야 함을 나타냄
select customers.customerID, customers.name
from customers left join orders
using (customerID)
where orders.orderID is null;

-- name을 기준으로 오름차순
select name, address
from customers
order by name;


-- address를 기준으로 내림차순 asc는 오름차순
select name, address
from customers
order by address asc;


select avg(amount), count(amount), MIN(amount), MAX(amount), sum(amount)
from orders;


-- group by를 했을 때 customerID로 정렬되지 않아 별칭과 order by를 사용하여 정렬 후 그룹화 함
select customerID, avg(amount)
from(select * from orders order by customerID asc limit 100) as a 
group by a.customerID;


-- 평균 50 이상의 값을 가진 사람 찾을 때, 
select customerID, avg(amount)
from orders
group by customerID
having avg(amount) > 50;


-- 0번부터 3개의 행을 리턴
select name
from customers
limit 0,3;


select customerID, amount
from orders
where amount = (select max(amount) from orders);


-- any 서브쿼리에서 조건을 만족하는 값이 하나 이상 있으면 결과 리턴 (or)
-- some 서브쿼리에서 조건을 만족하는 값이 하나 이상 있으면 결과 리턴 (or)
-- all 서브쿼리에서 모든 조건을 만족하는 결과 리턴 (and)
select customerID from customers
where customerID >
all (select customerID from orders);


-- exists 조건에 맞는 열만 추출
-- not exists 조건에 맞지 않는 열 추출
select isbn, title
from books
where exists
(select * from order_items where order_items.isbn=books.isbn);

-- customerID,name을 costomers에서 가져오고 city 이름은 Box Hill, 명칭은 box_hill_customers
select * from (select customerID, name from customers where city='Box Hill')
as box_hill_customers;

update books
set price = price*1.1;

update customers
set address = '250 Olsens Road'
where customerID = '4';


-- 컬럼추가   		alter table 테이블이름 add colum 열 varchar() not null
-- 컬럼이름까지 변경 	alter table 테이블이름 change colum 열 varchar() not null
-- 컬럼 삭제 			alter table 테이블 이름 drop colum 열
-- 테이블 이름 변경		alter table 테이블이름 rename 변경할 테이블 이름
-- 컬럼 수정
alter table customers
modify name char(70) not null;

-- orders에 tax 열을 추가
alter table orders
add tax float(6,2) after amount;
-- tax열을 삭제
alter table orders
drop tax;

-- 권한 GRANT, REVOKE는 단계조절 있음
-- GLOBAL, DATRAVASE, TABLE, COLUMN

-- 비밀번호 mnb123으로 접속하는 fred라는 사용자에게 모든 데이터베이스 권한 허용, fred도 다른 사용자에게 권한 부여 가능 
-- grant all on * to fred identified by 'mnb123' whith grant option;

-- 부여했던 권한 취소할 때 사용
-- revoke all privileges, grant from fred;

-- 권한이 없는 일반적 사용자 설정
-- grant usage on books.* to sally identified by 'magic123';

-- 필요한 권한만 부여 -> 이렇게 되면 sally는 비밀번호 필요 없음
-- grant select, insert, update, delete, index, alter, create, drop on books.* to sally;

-- 일부 취소하고 싶을 때
-- revoke alter, create, drop on books.* from sally;

-- 모든 권한 회수
-- revoke all on books.* from sally
