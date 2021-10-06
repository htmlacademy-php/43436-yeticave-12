use auctionshop;

-- Insert data into 'categories' table
INSERT INTO categories (id, name, technical_name)
VALUES ('1', 'Доски и лыжи', 'boards'),
       ('2', 'Крепления', 'attachment'),
       ('3', 'Ботинки', 'boots'),
       ('4', 'Одежда', 'clothing'),
       ('5', 'Инструменты', 'tools'),
       ('6', 'Разное', 'other');

-- Insert data into 'users' table
INSERT INTO users (id, registered_at, email, password, phone, name)
VALUES ('1', NOW(), 'kate.sheleh@gmail.com', PASSWORD('1111'), '222 222 222 222', 'Katia Sheleh'),
       ('2', NOW(), 'kate220485@gmail.com', PASSWORD('2222'), '123 123 123 123', 'Kate Sh');

-- Insert data into 'lots' table
INSERT INTO lots (id, created_at, name, description, image_url, start_price, expiration_at, rate_step, category_id, author_id)
VALUES
(
    '1',
    NOW(),
    '2014 Rossignol District Snowboard',
    'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua',
    'lot-1.jpg',
    10999.45,
    '2021-09-29',
    100,
    '1',
    '1'
  ),
  (
    '2',
    NOW(),
    'DC Ply Mens 2016/2017 Snowboard',
    'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua',
    'lot-2.jpg',
    159999,
    '2021-10-20',
    100,
    '1',
    '1'
  ),
  (
    '3',
    NOW(),
    'Крепления Union Contact Pro 2015 года размер L/XL',
    'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua',
    'lot-3.jpg',
    8000,
    '2021-11-01',
    100,
    '2',
    '1'
  ),
  (
    '4',
    NOW(),
    'Ботинки для сноуборда DC Mutiny Charocal',
    'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua',
    'lot-4.jpg',
    124,
    '2021-10-30',
    100,
    '3',
    '2'
  ),
  (
    '5',
    NOW(),
    'Куртка для сноуборда DC Mutiny Charocal',
    'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua',
    'lot-5.jpg',
    124,
    '2022-01-26',
    100,
    '4',
    '2'
  ),
  (
    '6',
    NOW(),
    'Маска Oakley Canopy',
    'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua',
    'lot-6.jpg',
    999.99,
    '2021-12-22',
    100,
    '6',
    '2'
  );

-- Insert data into 'rates' table
INSERT INTO rates (created_at, price, user_id, lot_id)
VALUES (NOW(), 8210, '1', '5'),
       (NOW(), 18987, '2', '4'),
       (NOW(), 987, '2', '4'),
       (NOW(), 34932, '1', '4'),
       (NOW(), 65, '1', '5'),
       (NOW(), 6435, '1', '2'),
       (NOW(), 64354, '2', '2');


-- get all categories
SELECT id, name, technical_name FROM categories;


-- get the newest, open lots.
-- Result should include title, starting price, image link, price, category name
SELECT l.name, start_price, image_url, c.name as category_name, l.expiration_at
FROM lots l
INNER JOIN categories c ON category_id = c.id
WHERE expiration_at > NOW()
ORDER BY created_at DESC
LIMIT 6;


-- show lot by its IDs. Get the category name to which the lot belongs;
SELECT l.name as lot_name, c.name as category_name
FROM lots l
INNER JOIN categories c ON category_id = c.id
WHERE l.if = 6;

-- update the name of the lot by its ID
UPDATE lots SET name = "Test Name" WHERE id = 1;


-- The list of bids where === its ID, sorted by date.
SELECT u.email as user_email, l.name as lot_name, r.price, r.created_at, r.lot_id
FROM rates r
INNER JOIN users u ON user_id = u.id
INNER JOIN lots l ON lot_id = l.id
WHERE r.lot_id = 4
ORDER BY created_at DESC;
