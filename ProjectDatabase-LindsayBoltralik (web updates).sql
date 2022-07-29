-- D532 Project - Part II
-- Hop Shop Database
-- Author (for all sections below): Lindsay Boltralik

-- create database
create database HopShop;

-- create table to load data into
create table products_table (
		Product_ID int not null auto_increment Primary Key,
        Category varchar(100) not null,
        Brand_name varchar(100),
        Product_name varchar(250),
        Unit_size int not null,
        Unit_of_measure varchar(3) not null,
        Wholsale_price decimal(10,2) not null,
        Profit decimal(3,2) not null,
        Unit_price decimal(10,2) not null,
        Inventory int,
        Out_of_stock varchar(3) CHECK(Out_of_stock IN ('Y', 'N')),
        Lead_time int
);
-- load data via csv file using Table Data Import Wizard


-- Data Normalization
-- First Normal Form already met; each attribute holds only one value
-- Second Normal Form; no partial dependency (reduce redundant data)
	-- Category is a non-prime attribute, create new category table
create table category_table (
		Category_ID int not null auto_increment Primary Key,
        Category varchar(100) not null
);
	-- Insert values and replace in products table
insert into category_table (Category)
select distinct Category
from products_table;
        
alter table products_table
add (Category_ID int);

update products_table
inner join category_table
on products_table.Category = category_table.Category
set products_table.Category_ID = category_table.Category_ID;

alter table products_table
drop column Category;

alter table products_table
add constraint fk_category
foreign key (Category_ID)
references category_table(Category_ID);

	-- Brand is a non-prime attribute, create new brand table
create table brand_table (
		Brand_ID int not null auto_increment Primary Key,
        Brand varchar(100) not null
);
	-- Insert values and replace in products table
insert into brand_table (Brand)
select distinct Brand_name
from products_table;

alter table products_table
add (Brand_ID int);

update products_table
inner join brand_table
on products_table.Brand_name = brand_table.Brand
set products_table.Brand_ID = brand_table.Brand_ID;

alter table products_table
drop column Brand_name;

alter table products_table
add constraint fk_brand
foreign key (Brand_ID)
references brand_table(Brand_ID);

	-- Unit size and measure are non-prime attributes, create new UOM table
create table UOM_table (
		U_ID int not null auto_increment Primary Key,
		Unit_size int,
        Unit_of_measure varchar(3)
);
	-- Insert values into UOM table and replace in Products table
insert into UOM_table (Unit_size, Unit_of_measure)
select distinct Unit_size, Unit_of_measure
from products_table;

alter table products_table
add (U_ID int);

update products_table
inner join UOM_table
on products_table.Unit_size = UOM_table.Unit_size
and products_table.Unit_of_measure = UOM_table.Unit_of_measure
set products_table.U_ID = UOM_table.U_ID;

alter table products_table
add constraint fk_uom
foreign key (U_ID)
references UOM_table(U_ID);

alter table products_table
drop column Unit_size;
alter table products_table
drop column Unit_of_measure;

	-- Profit is a non-prime attributes, create new profit table
create table profit_table (
		ID int not null auto_increment Primary Key,
		Profit decimal(3,2) not null
);
	-- Insert values into profit table and remove from products table
insert into profit_table (Profit)
select distinct Profit
from products_table;

alter table products_table
drop column Profit;

-- Third Normal Form; no transitive dependency (create independence)
	-- Inventory info is indepedent of product info, create new Inventory table
create table inv_table (
		P_ID int not null,
        U_ID int not null,
		Inventory int,
		Out_of_stock varchar(3) CHECK(Out_of_stock IN ('Y', 'N')),
        constraint fk_inventory foreign key (P_ID) references products_table(Product_ID),
        constraint fk_inventory2 foreign key (U_ID) references UOM_table(U_ID)
);
	-- Insert values into Inventory table and remove from Products table
insert into inv_table (P_ID, U_ID, Inventory, Out_of_stock)
select distinct Product_ID, U_ID, Inventory, Out_of_stock
from products_table;

alter table products_table
drop column Inventory;
alter table products_table
drop column Out_of_stock;

	-- Lead time is indepedent of product, create new Lead Time table
create table lt_table (
		P_ID int not null,
        U_ID int not null,
		Lead_time int,
        constraint fk_lt foreign key (P_ID) references products_table(Product_ID),
        constraint fk_lt2 foreign key (U_ID) references UOM_table(U_ID)
);
	-- Insert values into Lead Time table and remove from Products table
insert into lt_table (P_ID, U_ID, Lead_time)
select distinct Product_ID, U_ID, Lead_time
from products_table;

alter table products_table
drop column Lead_time;

	-- Price info is indepedent of product, create new Price table
create table price_table (
		P_ID int not null,
        Wholesale_price decimal(10,2) not null,
        Unit_price decimal(10,2) not null,
        constraint fk_price foreign key (P_ID) references products_table(Product_ID)
);
	-- Insert values into Price table and remove from Products table
insert into price_table (P_ID, Wholesale_price, Unit_price)
select distinct Product_ID, Wholsale_price, Unit_price
from products_table;

alter table products_table
drop column Wholsale_price;
alter table products_table
drop column Unit_price;

-- Alter price table to calculate Unit price using Wholesale price and Profit
update price_table
join profit_table
set Unit_price = round(Wholesale_price * (1+profit),2);

-- CRUD Functions
-- CREATE
-- Category
delimiter $$
create procedure insert_category (val1 varchar(100))
begin
	insert into category_table(Category)
    values (val1);
end; $$
delimiter ;

-- Brand
delimiter $$
create procedure insert_brand (val1 varchar(100))
begin
	insert into brand_table(Brand)
    values (val1);
end; $$
delimiter ;

-- Unit size
delimiter $$
create procedure insert_unit (val1 int, val2 varchar(3))
begin
	insert into uom_table(Unit_size, Unit_of_measure)
    values (val1, val2);
end; $$
delimiter ;

-- Product
delimiter $$
create procedure insert_product (val1 varchar(250), val2 varchar(100), val3 varchar(100), val4 int, val5 varchar(3))
begin
	declare x, y, z int;
    if val2 in (select Category from category_table) then
		set x = (select Category_ID from category_table where val2 = category_table.Category);
	else call insert_category(val2);
		set x = (select Category_ID from category_table where val2 = category_table.Category);
    end if;
    if val3 in (select Brand from brand_table) then
		set y = (select Brand_ID from brand_table where val3 = brand_table.Brand);
	else call insert_brand(val3);
		set y = (select Brand_ID from brand_table where val3 = brand_table.Brand);
	end if;
    if concat(val4, val5) in (select concat(Unit_size, Unit_of_measure) from uom_table) then 
		set z = (select U_ID from uom_table where val4 = uom_table.Unit_size and val5 = uom_table.Unit_of_measure);
	else call insert_unit(val4, val5);
		set z = (select U_ID from uom_table where val4 = uom_table.Unit_size and val5 = uom_table.Unit_of_measure);
	end if;
	insert into products_table(Product_name, Category_ID, Brand_ID, U_ID)
    values (val1, x, y, z);
end; $$
delimiter ;

-- On insert trigger addition to inventory, lead time, and price table
delimiter $$
create procedure insert_inventory(val1 varchar(250), val2 varchar(100), val3 varchar(100), val4 int, val5 varchar(3))
begin
	insert into inv_table(P_ID, U_ID, Inventory, Out_of_stock)
    select Product_ID, U_ID, 0 as Inventory, 'Y' as Out_of_stock
    from products_table join uom_table using (U_ID) join category_table using (Category_ID) join brand_table using (Brand_ID) 
    where val1 = products_table.Product_name and val2 = category_table.Category and val3 = brand_table.Brand
    and val4 = uom_table.Unit_size and val5 = uom_table.Unit_of_measure;
end$$
delimiter ;

delimiter $$
create procedure insert_leadtime(val1 varchar(250), val2 varchar(100), val3 varchar(100), val4 int, val5 varchar(3))
begin
	insert into lt_table(P_ID, U_ID, Lead_time)
	select Product_ID, U_ID, null as Lead_time
    from products_table join uom_table using (U_ID) join category_table using (Category_ID) join brand_table using (Brand_ID) 
    where val1 = products_table.Product_name and val2 = category_table.Category and val3 = brand_table.Brand
    and val4 = uom_table.Unit_size and val5 = uom_table.Unit_of_measure;
end$$
delimiter;

delimiter $$
create procedure insert_price(val1 varchar(250), val2 varchar(100), val3 varchar(100), val4 int, val5 varchar(3))
begin
	insert into price_table(P_ID, Wholesale_price, Unit_price)
	select Product_ID, 0.00 as Wholesale_price, 0.00 as Unit_price
    from products_table join uom_table using (U_ID) join category_table using (Category_ID) join brand_table using (Brand_ID) 
    where val1 = products_table.Product_name and val2 = category_table.Category and val3 = brand_table.Brand
    and val4 = uom_table.Unit_size and val5 = uom_table.Unit_of_measure;
end$$
delimiter;

-- READ
delimiter $$
create procedure search_product (IN val1 varchar(100))
begin
	select Product_ID, Category, Brand, Product_name, Unit_price, concat(Unit_size, ' ', Unit_of_measure) as Size, Inventory, Out_of_stock
	from products_table join category_table using(Category_ID) join brand_table using (Brand_ID) 
    join uom_table using (U_ID) join price_table on products_table.Product_ID = price_table.P_ID 
    join inv_table on products_table.Product_ID = inv_table.P_ID
    where Product_name like concat('%',val1,'%') or Category like concat('%',val1,'%') or Brand like concat('%',val1,'%')
    order by Product_ID;
end; $$
delimiter ;

delimiter $$
create procedure search_updateinfo (IN val1 varchar(100))
begin
	select Product_ID, Product_name, Wholesale_price, Lead_time, Inventory
	from products_table join price_table on products_table.Product_ID = price_table.P_ID join lt_table
    on products_table.Product_ID = lt_table.P_ID join inv_table on products_table.Product_ID = inv_table.P_ID
    where Product_name like concat('%',val1,'%') or Product_ID like concat('%',val1,'%') or concat(Product_ID, " ", Product_name) like concat('%',val1,'%')
    order by Product_ID;
end; $$
delimiter ;

-- UPDATE
-- Wholesale price
delimiter $$
create procedure update_wholesale (val1 int, val2 decimal(10,2))
begin
	update price_table
	set wholesale_price = val2
    where P_ID = val1;
end; $$
delimiter ;

-- Lead time
delimiter $$
create procedure update_leadtime (val1 int, val2 int)
begin
	update lt_table
	set lead_time = val2
    where P_ID = val1;
end; $$
delimiter ;

-- Profit
delimiter $$
create procedure update_profit (val1 decimal(3,2))
begin
	update profit_table
	set Profit = val1;
end; $$
delimiter ;

-- Inventory
delimiter $$
create procedure update_inv (val1 int, val2 int)
begin
	if val2 > 0 then
		update inv_table
		set Inventory = val2, Out_of_stock = 'N'
		where P_ID = val1;
	else
		update inv_table
		set Inventory = val2, Out_of_stock = 'Y'
		where P_ID = val1;
	end if;
end; $$
delimiter ;

-- Update Final Price after profit is updated or when wholesale price is updated
delimiter $$
create procedure profit_update()
begin
update price_table
join profit_table
set Unit_price = round(Wholesale_price * (1+profit),2);
end$$
delimiter;

delimiter $$
create trigger price_update
before update
on price_table for each row
begin
	if (New.Wholesale_price <> Old.Wholesale_price) then
		set New.Unit_price = round(New.Wholesale_price * (1+ 
        (select profit from profit_table)),2);
	end if;
end$$
delimiter ;

-- DELETE
-- Delete a product; cascade deletion from Inventory, Lead time, and Price tables
delimiter $$
create procedure delete_product (val1 int)
begin
	delete from products_table
    where Product_ID = val1;
end; $$
delimiter ;

-- cascade delete/update on inventory
alter table inv_table
drop foreign key fk_inventory;
alter table inv_table
add constraint fk_inventory
foreign key (P_ID)
references products_table(Product_ID)
on delete cascade
on update cascade;

-- cascade delete/update on lead time
alter table lt_table
drop foreign key fk_lt;
alter table lt_table
add constraint fk_lt
foreign key (P_ID)
references products_table(Product_ID)
on delete cascade
on update cascade;

-- cascade delete/update on price table
alter table price_table
drop foreign key fk_price;
alter table price_table
add constraint fk_price
foreign key (P_ID)
references products_table(Product_ID)
on delete cascade
on update cascade;

-- Create admin table
create table admin_table (
		User_ID int not null auto_increment Primary Key,
        Username varchar(100) not null,
        Pword varchar(100) not null
);

	-- insert into admin table
insert into admin_table (Username, Pword)
values ('lboltralik', '1208');

	-- create procedure to check username and password
delimiter $$
create procedure checkuser (val1 varchar(100), val2 varchar(100))
begin
select Username
from admin_table
where Username = val1 and Pword = val2;
end; $$
delimiter ;