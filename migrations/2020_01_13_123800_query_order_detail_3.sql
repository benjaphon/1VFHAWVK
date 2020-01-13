3. คิวรี่แสดงสินค้าที่ขายไปได้ในเดือนนี้มีกี่รายการอะไรบ้าง

SELECT DISTINCT

date(o.modified_at) AS sell_date, 
o.id AS order_id, 
IF(p.parent_product_id IS NULL, p.name, CONCAT(pp.name, ' ', p.name)) AS product_name,
od.quantity, 
od.price, 
od.quantity*od.price AS price_product, 
o.ship_price

FROM order_details AS od
LEFT JOIN products AS p ON od.product_id = p.id
LEFT JOIN products AS pp ON p.parent_product_id = pp.id
LEFT JOIN orders As o ON od.order_id = o.id

WHERE  
month(o.modified_at) = '1' AND 
o.order_status in ('F','S') AND 
p.vat_rate = 'Y'

ORDER BY o.modified_at