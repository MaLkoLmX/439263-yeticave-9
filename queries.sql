/* Заполняем таблицу categories */
INSERT INTO categories (name, code) VALUES
    ("Доски и лыжи", "boards"),
    ("Крепления", "attachment"),
    ("Ботинки", "boots"),
    ("Одежда", "clothing"),
    ("Инструменты", "tools"),
    ("Разное", "other");

/*Добавили список пользователей в user*/
INSERT INTO user (date_reg, email, name, password, avatar, contact) VALUES
    ("2019-02-23 19:17", "benfa@gmail.com", "Бен", "4pok51Q", "1.jpg", "+79041246944"),
    ("2019-02-27 10:56", "mattbe@gmail.com", "Мэт", "xthnjd1Naf", "123.jpg", "+79201246777"),
    ("2019-02-27 10:56", "donaldkal@gmail.com", "Дональд", "gjRfqc9", "4132.jpg", "+79199467741");

/*Заполнили lot*/
INSERT INTO lot (date_creation, name, description, image, price, date_finish, step_price, id_user, id_winner, id_category) VALUES
   (
      "2019-02-23 19:17",
      "2014 Rossignol District Snowboard",
      "Легкий маневренный сноуборд, готовый дать жару в любом парке, растопив снег мощным щелчкоми четкими дугами",
      "img/lot-1.jpg",
      10999,
      "2019-04-23 21:22",
      1000,
      1,
      1,
      3
    ),
    (
      "2019-02-27 10:56",
      "DC Ply Mens 2016/2017 Snowboard",
      "Легкий маневренный сноуборд, готовый дать жару в любом парке, растопив снег мощным щелчкоми четкими дугами",
      "img/lot-2.jpg",
      159999,
      "2019-04-23 21:22",
      1000,
      3,
      5,
      3
    ),
    (
      "2019-02-27 10:56",
      "Крепления Union Contact Pro 2015 года размер L/XL",
      "Легкий маневренный сноуборд, готовый дать жару в любом парке, растопив снег мощным щелчкоми четкими дугами",
      "img/lot-3.jpg",
      8000,
      "2019-04-23 21:22",
      1000,
      3,
      3,
      6
    ),
    (
      "2019-02-27 10:56",
      "Ботинки для сноуборда DC Mutiny Charocal",
      "Легкий маневренный сноуборд, готовый дать жару в любом парке, растопив снег мощным щелчкоми четкими дугами",
      "img/lot-4.jpg",
      10999,
      "2019-04-23 21:22",
      1000,
      2,
      4,
      2
    ),
    (
      "2019-02-27 10:56",
      "Куртка для сноуборда DC Mutiny Charocal",
      "Легкий маневренный сноуборд, готовый дать жару в любом парке, растопив снег мощным щелчкоми четкими дугами",
      "img/lot-5.jpg",
      7500,
      "2019-04-23 21:22",
      1000,
      1,
      6,
      4
    ),
    (
      "2019-02-27 10:56",
      "Маска Oakley Canopy",
      "Легкий маневренный сноуборд, готовый дать жару в любом парке, растопив снег мощным щелчкоми четкими дугами",
      "img/lot-6.jpg",
      5400,
      "2019-04-23 21:22",
      1000,
      5,
      6,
      2
    );

/*Добавили ставки*/
INSERT INTO rate (date_rate, amount, id_user, id_lot) VALUES
    ("2019-04-23 21:22", 32000, 1, 1),
    ("2019-04-23 22:54", 7500, 1, 2),
    ("2019-04-24 14:11", 9999, 1, 3);

/*получить все категории*/
SELECT * FROM categories;

/*получить самые новые, открытые лоты. Каждый лот должен включать название, стартовую цену, ссылку на изображение, цену, название категории*/
SELECT l.name, price, image, MAX(r.amount), c.name FROM lot l
JOIN categories c ON l.id_category= c.id
JOIN rate r ON r.id_lot = l.id
WHERE date_finish < NOW()
GROUP BY r.id
ORDER BY date_creation DESC;

/*показать лот по его id. Получите также название категории, к которой принадлежит лот*/
SELECT l.name, description, image, price, c.name FROM lot l
JOIN categories c ON l.id_category = c.id
WHERE l.id = 1;

/*обновить название лота по его идентификатору*/
UPDATE lot SET name = "Да" WHERE id = 1;

/*получить список самых свежих ставок для лота по его идентификатору*/
SELECT date_rate, amount FROM lot l
JOIN rate r ON l.id = r.id_lot
WHERE l.id = 1
ORDER BY date_rate DESC;
