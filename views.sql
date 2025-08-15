
// View items_view
CREATE OR REPLACE VIEW items_view AS
SELECT items.*, categories.* FROM items
INNER JOIN categories ON items.items_categories = categories.categories_id

// View favorite_items
CREATE OR REPLACE VIEW favorite_items AS
SELECT items.*, favorite.*, users.* FROM favorite
INNER JOIN users ON users.users_id = favorite.favorite_user_id
INNER JOIN items ON items.items_id = favorite.favorite_item_id

// View items cart 
CREATE OR REPLACE VIEW items_cart AS
SELECT 
    SUM(items.items_price - (items.items_price * items.items_discount / 100)) AS total,
    COUNT(cart.cart_items_id) AS count_item,
    MAX(cart.cart_id) AS cart_id,
    cart.cart_users_id,
    cart.cart_items_id,
    MAX(items.items_id) AS items_id,
    MAX(items.items_name) AS items_name,
    MAX(items.items_name_ar) AS items_name_ar,
    MAX(items.items_price) AS items_price,
    MAX(items.items_image) AS items_image,
    MAX(items.items_desc) AS items_desc,
    MAX(items.items_desc_ar) AS items_desc_ar
FROM cart
JOIN items 
    ON cart.cart_items_id = items.items_id
GROUP BY cart.cart_items_id, cart.cart_users_id;
