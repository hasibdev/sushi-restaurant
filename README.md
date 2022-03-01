IMPROVE ERROR HANDLING OF API

sudo chown -R www-data:www-data public/
permission for public folder

TODO:

- [x] Store settings data in database
- [x] Allow user to add new locations and contact info
- [x] Allow user change landing page and contact page data
- [x] Order management
- [x] Manage users
- [x] Multilingual support
- [x] Dashboard
- [x] Automatically close store for selected time periods (e.g. 10:00 - 18:00)
- [x] Store language preferences to cookie
- [x] Remove nazox logo from login page

- [ ] Bug fixes
  - [x] Video is too big to send with json
  - [x] Fix offer edit page multiselect option
  - [x] Make the order page calculation more accurate
  - [x] Make the pos order and website order same to same
  - [x] Make the free product part of product list
- [x] Make the notification work
- [x] Send email to user and admin when order is placed
- [x] Ask for user location when starting a new session
- [x] Currency symbol is not displayed according to settings
- [x] In browser order notification
- [x] Add POS system
  - [x] Make the search bar work
  - [x] Remove description from product
  - [x] Add total to pos order
  - [x] Add sound to pos clicks
  - [x] Add sound to order notification
  - [x] Add payment method selection
  - [x] Add customer info to order
  - [x] Thermal printer template for pos
  - [x] Create user if not exists [random password]
  - [x] If user exists, assign user to order
- [ ] Self service system

RELATIONS

- [x] Category - has many - Product
  > check if category has products, if does not delete category [DONE]
- [x] Product - has many - Options
  > If a option is deleted, ignore it from all products [DONE]
- [x] Product - has many - Additionals
  > If a additionals is deleted, ignore it from all products [DONE]
- [x] Category - has many - Options
  > If a category is deleted, nothing to do [DONE]
