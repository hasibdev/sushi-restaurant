<?php

/**
 * App routes 
 * @since 1.0.0
 */

use Jara\App\Controllers\EmailController;
use Jara\Core\Start;
use Jara\App\Views\AdditionView;
use Jara\App\Views\AuthView;
use Jara\App\Views\BlogView;
use Jara\App\Views\CategoryView;
use Jara\App\Views\CheckoutView;
use Jara\App\Views\ContactView;
use Jara\App\Views\DashboardView;
use Jara\App\Views\DiscountView;
use Jara\App\Views\HomeView;
use Jara\App\Views\LocationsView;
use Jara\App\Views\MenuView;
use Jara\App\Views\OfferView;
use Jara\App\Views\OptionsView;
use Jara\App\Views\PageView;
use Jara\App\Views\SettingsView;
use Jara\App\Views\ThemeView;
use Jara\App\Views\UserView;
use Jara\Core\Helpers\Essentials;
use Jara\Core\Helpers\Request;
use Jara\Core\Helpers\Response;
use Jara\Core\Helpers\Session;
use Jara\Core\Helpers\Users;

/**
 * Start the app
 */
$app = new Start();

// Authentication routes
$app->router->add('post', '/signin', [new AuthView(), 'signin']);
$app->router->add('post', '/signup', [new AuthView(), 'signup']);
$app->router->add('post', '/change-password', [new AuthView(), 'change_password']);
$app->router->add('delete', '/signout', [new AuthView(), 'signout']);
$app->router->add('post', '/verify', [new AuthView(), 'verify_otp']);

$app->router->add('post', '/send-otp', [new AuthView(), 'send_otp']);
$app->router->add('post', '/forgot-password', [new AuthView(), 'forgot_password']);


// email route
$app->router->add('get', '/email/:num/?', [new EmailController, 'order_email']);


// user routes
$app->router->add('get', '/users/:num/?', [new UserView(), 'get_user']);
$app->router->add('get', '/user', [new UserView(), 'get_user']);
$app->router->add('get', '/users', [new UserView(), 'get_all_users']);
$app->router->add('post', '/users', [new UserView(), 'add_user']);
$app->router->add('patch', '/users', [new UserView(), 'update_user']);
$app->router->add('delete', '/users', [new UserView(), 'remove_user']);
$app->router->add('get', '/users/search/:any/?', [new UserView(), 'search_user']);

// add settings
$app->router->add('post', '/settings', [new SettingsView(), 'set_settings']);

// get settings
$app->router->add('get', '/settings', [new SettingsView(), 'get_settings']);

// get post themes
$app->router->add('get', '/theme', [new ThemeView(), 'get_themes']);
$app->router->add('post', '/theme', [new ThemeView(), 'set_themes']);


// product routes
$app->router->add('get', '/menu/items/:num/?', [new MenuView(), 'get_item']);
$app->router->add('get', '/menu/items', [new MenuView(), 'get_items']);
$app->router->add('post', '/menu/items', [new MenuView(), 'set_items']);
$app->router->add('patch', '/menu/items', [new MenuView(), 'update_items']);
$app->router->add('delete', '/menu/items', [new MenuView(), 'remove_items']);

// category routes
$app->router->add('get', '/menu/categories/:num/?', [new CategoryView(), 'get_single_category']);
$app->router->add('get', '/menu/categories', [new CategoryView(), 'get_category']);
$app->router->add('post', '/menu/categories', [new CategoryView(), 'set_category']);
$app->router->add('patch', '/menu/categories', [new CategoryView(), 'update_category']);
$app->router->add('delete', '/menu/categories', [new CategoryView(), 'remove_category']);

// additions routes
$app->router->add('get', '/menu/additions/:num/?', [new AdditionView(), 'get_addition']);
$app->router->add('get', '/menu/additions', [new AdditionView(), 'get_additions']);
$app->router->add('post', '/menu/additions', [new AdditionView(), 'set_additions']);
$app->router->add('patch', '/menu/additions', [new AdditionView(), 'update_additions']);
$app->router->add('delete', '/menu/additions', [new AdditionView(), 'remove_additions']);


// options routes
$app->router->add('get', '/menu/options/:num/?', [new OptionsView(), 'get_option']);
$app->router->add('get', '/menu/options', [new OptionsView(), 'get_options']);
$app->router->add('post', '/menu/options', [new OptionsView(), 'set_options']);
$app->router->add('patch', '/menu/options', [new OptionsView(), 'update_options']);
$app->router->add('delete', '/menu/options', [new OptionsView(), 'remove_options']);


// offers routes
$app->router->add('get', '/menu/offers/:num/?', [new OfferView(), 'get_offer']);
$app->router->add('get', '/menu/offers', [new OfferView(), 'get_offers']);
$app->router->add('post', '/menu/offers', [new OfferView(), 'set_offers']);
$app->router->add('patch', '/menu/offers', [new OfferView(), 'update_offers']);
$app->router->add('delete', '/menu/offers', [new OfferView(), 'remove_offers']);

// additions routes
$app->router->add('get', '/checkout/discount/:any/?', [new DiscountView(), 'get_discount']);
$app->router->add('get', '/checkout/discount', [new DiscountView(), 'get_discounts']);
$app->router->add('post', '/checkout/discount', [new DiscountView(), 'set_discounts']);
$app->router->add('patch', '/checkout/discount', [new DiscountView(), 'update_discounts']);
$app->router->add('delete', '/checkout/discount', [new DiscountView(), 'remove_discounts']);


// order
$app->router->add('post', '/checkout/order', [new CheckoutView(), 'set_order']);
$app->router->add('patch', '/checkout/order', [new CheckoutView(), 'update_status']);
$app->router->add('get', '/checkout/order/:num/?', [new CheckoutView(), 'get_order']);
$app->router->add('get', '/checkout/orders/:num/?', [new CheckoutView(), 'get_orders']);
$app->router->add('get', '/checkout/pending/:num/?', [new CheckoutView(), 'get_pending_orders']);
$app->router->add('get', '/checkout/user-orders/:num/?', [new CheckoutView(), 'get_user_orders']);

// dashboard
$app->router->add('get', '/dashboard/overview/:num/?', [new DashboardView(), 'get_overview']);
$app->router->add('get', '/dashboard/report', [new DashboardView(), 'get_report']);
// /dashboard/report/?end=2022-01-16%2000:24:10&start=2022-01-12%2000:02:54

// locations
$app->router->add('get', '/location/:num', [new LocationsView(), 'get_location']);
$app->router->add('get', '/locations', [new LocationsView(), 'get_locations']);
$app->router->add('post', '/locations', [new LocationsView(), 'set_location']);
$app->router->add('patch', '/locations', [new LocationsView(), 'update_location']);
$app->router->add('delete', '/locations', [new LocationsView(), 'delete_location']);

// get blogs
$app->router->add('get', '/blog/', [new BlogView(), 'get_blogs']);
$app->router->add('get', '/blog/:slug/?', [new BlogView(), 'get_blogs']);

// home
$app->router->add('get', '/pages/', [new HomeView(), 'get_home']);
$app->router->add('post', '/pages/home', [new HomeView(), 'set_home']);

// contact
$app->router->add('get', '/pages/contact', [new ContactView(), 'get_contact']);
$app->router->add('post', '/pages/contact', [new ContactView(), 'set_contact']);

// get pages
$app->router->add('get', '/pages/:slug/?', [new PageView(), 'get_page']);

// register push notification token
$app->router->add('post', '/push/register', function () {
   Session::check();
   $request = Request::require('token');
   $old_token = Users::get_meta(Essentials::id(), 'push_token');

   if ($old_token) {
      Users::update_meta(Essentials::id(), 'push_token', $request['token']);
   } else {
      Users::set_meta(Essentials::id(), 'push_token', $request['token']);
   }

   echo Response::new(200);
});

// last route is 404 response as all others failed.
$app->router->add(strtolower($_SERVER['REQUEST_METHOD']), '/:any/:any/:any', function () {
   echo Response::new(404);
});

/**
 * Listen for client pages
 */
$app->router->run();
