1. คิวรี่เช็คจำนวนสินค้ารวมทั้งหมด ที่ขายไป ตาม id และช่วงเวลาที่กำหนด

SELECT DISTINCT

p.id AS product_id, 
IF(p.parent_product_id IS NULL, p.name, CONCAT(pp.name, ' ', p.name)) AS product_name, 
IFNULL(SUM(od.quantity),0) AS sell_quantity 

FROM products AS p
LEFT JOIN products AS pp ON pp.id = p.parent_product_id
LEFT JOIN order_details AS od ON p.id = od.product_id
LEFT JOIN orders AS o ON od.order_id = o.id and date(o.created_at) > '2019-12-01'

WHERE p.id in (
9364,
9365,
9366,
9367,
9368,
9369
)

GROUP BY p.id, product_name

ORDER BY p.id

