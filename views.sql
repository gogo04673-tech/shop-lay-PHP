
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
WHERE cart.cart_orders = 0
GROUP BY cart.cart_items_id, cart.cart_users_id;

// orders_details_view 
CREATE OR REPLACE VIEW orders_details_view AS
SELECT 
    SUM(items.items_price - (items.items_price * items.items_discount / 100)) AS total,
    COUNT(cart.cart_items_id) AS count_item,
    MAX(cart.cart_id) AS cart_id,
    MAX(cart.cart_orders) AS cart_orders,
    cart.cart_users_id,
    cart.cart_items_id,
    MAX(items.items_id) AS items_id,
    MAX(items.items_name) AS items_name,
    MAX(items.items_name_ar) AS items_name_ar,
    MAX(items.items_price) AS items_price,
    MAX(items.items_image) AS items_image,
    MAX(items.items_desc) AS items_desc,
    MAX(items.items_desc_ar) AS items_desc_ar,
    MAX(orders_view.orders_id) AS orders_id ,
    MAX(orders_view.orders_users_id) AS orders_users_id ,
    MAX(orders_view.orders_payment_method) AS orders_payment_method ,
    MAX(orders_view.orders_address) AS orders_address ,
    MAX(orders_view.orders_type) AS orders_type,
    MAX(orders_view.orders_price_delivery) AS orders_price_delivery,
    MAX(orders_view.orders_price) AS orders_price ,
    MAX(orders_view.orders_total_price) AS orders_total_price ,
    MAX(orders_view.orders_coupon) AS orders_coupon ,
    MAX(orders_view.orders_coupon_discount) AS orders_coupon_discount ,
    MAX(orders_view.orders_date_time) AS orders_date_time,
    MAX(orders_view.orders_status) AS orders_status,
    MAX(orders_view.address_id) AS address_id ,
    MAX(orders_view.address_users_id) AS address_users_id ,
    MAX(orders_view.address_city) AS address_city ,
    MAX(orders_view.address_street) AS address_street ,
    MAX(orders_view.address_lat) AS address_lat,
    MAX(orders_view.address_lang) AS address_long
FROM cart
JOIN items 
    ON cart.cart_items_id = items.items_id
JOIN orders_view 
    ON orders_view.orders_id = cart.cart_orders
WHERE cart.cart_orders != 0
GROUP BY cart.cart_items_id, cart.cart_users_id;


// Orders view
CREATE OR REPLACE VIEW orders_view AS
SELECT orders.*, address.* FROM orders
LEFT JOIN address ON address.address_id = orders.orders_address;

// top Seller

CREATE OR REPLACE VIEW items_top_seller as
SELECT 
COUNT(cart.cart_id) as countItems,
(items.items_price - (items.items_price * items.items_discount / 100)) as items_price_discount, 
ANY_VALUE(cart.cart_id) as cart_id,
ANY_VALUE(cart.cart_items_id) as cart_items_id,
ANY_VALUE(cart.cart_orders) as cart_orders,
ANY_VALUE(items.items_id) as items_id,
ANY_VALUE(items.items_name) as items_name,
ANY_VALUE(items.items_name_ar) as items_name_ar,
ANY_VALUE(items.items_desc) as items_desc,
ANY_VALUE(items.items_desc_ar) as items_desc_ar,
ANY_VALUE(items.items_discount) as items_discount,
ANY_VALUE(items.items_image) as items_image,
ANY_VALUE(items.items_count) as items_count,
ANY_VALUE(items.items_price) as items_price,
ANY_VALUE(items.items_categories) as items_categories,
ANY_VALUE(items.items_date) as items_date,
ANY_VALUE(items.items_active) as items_active
FROM cart
INNER JOIN items on cart.cart_items_id = items.items_id
WHERE cart.cart_orders != 0
GROUP BY cart.cart_items_id;

