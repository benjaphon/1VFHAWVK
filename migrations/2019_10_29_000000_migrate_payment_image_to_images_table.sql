INSERT INTO images (ref_id, filename, filetype)
SELECT id, url_picture, 'payment'
FROM payments
WHERE url_picture != 'ecimage.jpg'