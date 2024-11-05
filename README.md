# Laravel Ticket Ordering System

## Описание

Это проект на Laravel для управления заказами билетов на мероприятия. Он включает в себя функциональность для создания заказов, управления билетами и их подтверждения, а также для работы с различными типами билетов.

## Установка

### Требования

- PHP 8.0^
- Laravel 10.0
- MySQL 5.7 (или другой поддерживаемый драйвер базы данных)

### Установка

1. Клонируйте репозиторий:

   ```
   git clone https://github.com/castus24/booking-test-project.git

2. Установите зависимости с помощью Composer:

   ```bash
   composer install

3. Скопируйте файл .env.example в .env и настройте параметры подключения к базе данных:

   ```
   .env.example .env
   ```

   ```bash
    php artisan key:generate
   ```
4. Запустите миграции для создания необходимых таблиц:

   ```bash
   php artisan migrate
   
5. Заполните таблицу типов билетов с помощью готовой команды:
   (Также чтобы изменить, добавить или удалить какой-либо из типов, просто сделайте это в TicketTypeEnum в директории проекта и затем введите команду)
    
   ```bash
   php artisan ticket-types:fill

6. Запустите сидер для заполнения ивентов:

   ```bash
   php artisan migrate --seed

7. Установите пакет для enum:

   ```bash
    composer require bensampo/laravel-enum

## Использование
### Создание заказа

### В Headers указать Accept-Language: en либо ru для корректного отображения ответов.
    

#### Для создания заказа отправьте POST-запрос на /api/orders с JSON-телом:

   ```bash
   {
    "event_id": 1,
    "event_date": "2024-11-05 19:00:00",
    "user_id": 1,
    "tickets": [
        {
            "ticket_type": "adult",
            "quantity": 2,
            "price": 1500
        },
        {
            "ticket_type": "kid",
            "quantity": 1,
            "price": 750
        }
      ]
   }
   ```

### Ответ на запрос

#### Успешный ответ будет выглядеть следующим образом:

   ```bash
   {
    "message": "Order created successfully",
    "order": {
        "id": 1,
        "event_id": 1,
        "event_date": "2024-11-05 19:00:00",
        "user_id": 1,
        "equal_price": 3750,
        "barcode": "000000000001",
        "tickets": [
            {
                "id": 1,
                "ticket_type_id": 1,
                "price": 1500,
                "barcode": "000000000002"
            },
            {
                "id": 2,
                "ticket_type_id": 2,
                "price": 750,
                "barcode": "000000000003"
            }
        ]
    }
  }
   ```


