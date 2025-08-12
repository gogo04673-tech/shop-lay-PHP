
// View items_view
CREATE OR REPLACE VIEW items_viewAS
SELECT items.*, categories.* FROM items
INNER JOIN categories ON items.items_categories = categories.categories_id

// View favorite_items
CREATE OR REPLACE VIEW favorite_items AS
SELECT items.*, favorite.*, users.* FROM favorite
INNER JOIN users ON users.users_id = favorite.favorite_user_id
INNER JOIN items ON items.items_id = favorite.favorite_item_id