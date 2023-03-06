## Minimum Requirements

- PHP 8.0+
- Composer 2.0+
- MySQL 5.7+
- Laravel 9.0

## How to install
- Step 1: Clone the repository
https://github.com/spysabbirproject/ecommerce-project-two.git

- - Step 2: Install Laravel
 i) run command "composer install"
 ii) Copy .env.example file and remame .env file
 iii) run command "php artisan key:generate"

- Step 3: Edit database details 
    i) Create ecommerce_project_two tables in your database
    ii) Database details contact .env file in database feld
    iii) Import ecommerce_project_two tables

- Step 4: To add payment gateway
 For SSl - Add your Store_Id and Store_Password
 
- Step 5: Mail details contact .env file in mail feld
(follow the order)
MAIL_MAILER=Your mail mailer
MAIL_HOST=Your mail host
MAIL_PORT=Your mail port
MAIL_USERNAME= Your mail address
MAIL_PASSWORD=Your mail password
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS= Your mail address
MAIL_FROM_NAME="${APP_NAME}"

## About This Project

This is a web-based ecommerce project. From here buyer can buy products. Any user can buy any products. We have an dashboard panel to control this site. Our system has a search box by using this you can search any product. Another important feature in our system is using a valid and unique email address. This means you can not be able to register in our system if you are already registered with existing mail address.
 
## Feature Admin Panel: 
Admin panel three roles which is:
- Super Admin
- Admin
- Warehouse

-   Super admin can change user role at any time. Warehouse and Admin have an limit that they can not be able to change Super Admins role.
-   Admin who posted a product to sell they can be able to update and delete their posted product at any time.
-   Admin can see stock product at any time.
-   Super admin can download stock product report at any time.

Warehouse, Admin & Super Admin panel login details:- 
Super Admin Email: superadmin@gmail.com
Admin Email: admin@gmail.com
Warehouse Admin Email: dhakawarehouse@gmail.com
Password : 123456789

## Feature Frontend Panel: 
They can purchase any product from this site and also can add any product to cart and also can select product to wishlist so thay can purchase in future.
Customer Email: customer@gmail.com
Password : 123456789

## Warehouse, Admin & Super Admin: 
- Customer see all category, brand and all product 
- Customer can added product in cart
- Customer can added product in wishlist
- If customers face any question or any problem than send contact message
- Using this site you can pay payment. We have three types of payment category which is:
    i) Cash on delivery
    ii) Ssl Getway Payment

## Software Requirement
I have used those languages to create this project.

Front-End
- HTML
- CSS
- Java Scripts

Back-End: 
- PHP
- Laravel-Framework : 9
- Mysql
- Ajax
