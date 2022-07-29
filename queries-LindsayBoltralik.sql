-- D532 Project - Part II
-- Hop Shop Queries
-- Author: Lindsay Boltralik

-- Author: Lindsay Boltralik
-- Main display query
select Product_ID, Category, Brand, Product_name, Unit_price, concat(Unit_size, ' ', Unit_of_measure) as Size, Inventory, Out_of_stock
from products_table join category_table using(Category_ID) join brand_table using (Brand_ID) 
join uom_table using (U_ID) join price_table on products_table.Product_ID = price_table.P_ID 
join inv_table on products_table.Product_ID = inv_table.P_ID
where inv_table.Out_of_stock = 'N';

-- Author: Lindsay Boltralik
-- Create view for products out of stock
create or replace view Out_of_stock as
select p.Product_ID, p.Product_name, u.Unit_size, u.Unit_of_measure, i.Out_of_stock
from products_table as p join UOM_table as u
on p.U_ID = u.U_ID
join inv_table as i on p.Product_ID = i.P_ID
where i.Out_of_stock = 'Y';

-- Author: Lindsay Boltralik
-- Inventory Analysis Queries
	-- High inventory
select b.Brand, p.Product_name, i.Inventory
from products_table as p join brand_table as b using(Brand_ID)
join inv_table as i on p.Product_ID = i.P_ID
where i.Inventory = (select max(Inventory) from inv_table);

-- Author: Lindsay Boltralik
	-- Low inventory, but not out of stock
select b.Brand, p.Product_name, i.Inventory
from products_table as p join brand_table as b using(Brand_ID)
join inv_table as i on p.Product_ID = i.P_ID
where i.Inventory between 1 and 3
order by i.Inventory;

-- Author: Lindsay Boltralik
	-- Average inventory total
select round(avg(Inventory),1) as Average_Inventory
from inv_table;

-- Author: Lindsay Boltralik
	-- Average inventory by Brand
select b.Brand, round(avg(i.Inventory),1) as Average
from products_table as p join brand_table as b using(Brand_ID)
join inv_table as i on p.Product_ID = i.P_ID
group by b.Brand;

-- Adapted from https://ubiq.co/database-blog/create-histogram-mysql/
-- Pareto chart setup for Inventory
select floor(Inventory/2)*2 as bin_floor, count(*) as count, rpad('', floor(count(*)/10), '*') as bar
from inv_table
group by bin_floor
order by bin_floor;

-- Author: Lindsay Boltralik
-- Out of sock/lead time analysis
select Product_name, concat(Unit_size, ' ', Unit_of_measure) as Size, Lead_time
from Out_of_stock join lt_table on Out_of_stock.Product_ID = lt_table.P_ID
order by Lead_time desc;