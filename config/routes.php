<?php
/**
 * Routes configuration.
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * It's loaded within the context of `Application::routes()` method which
 * receives a `RouteBuilder` instance `$routes` as method argument.
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;

/*
 * This file is loaded in the context of the `Application` class.
 * So you can use `$this` to reference the application class instance
 * if required.
 */
return function (RouteBuilder $routes): void {
    /*
     * The default class to use for all routes
     *
     * The following route classes are supplied with CakePHP and are appropriate
     * to set as the default:
     *
     * - Route
     * - InflectedRoute
     * - DashedRoute
     *
     * If no call is made to `Router::defaultRouteClass()`, the class used is
     * `Route` (`Cake\Routing\Route\Route`)
     *
     * Note that `Route` does not do any inflections on URLs which will result in
     * inconsistently cased URLs when used with `{plugin}`, `{controller}` and
     * `{action}` markers.
     */
    $routes->setRouteClass(DashedRoute::class);

    $routes->scope('/', function (RouteBuilder $builder): void {

        $builder->connect('/', ['controller' => 'Pages', 'action' => 'display', 'home']);

        $builder->connect('/pages/*', 'Pages::display');

        // Jewelry shop routes
        $builder->connect('/jewelry', ['controller' => 'Jewelry', 'action' => 'index']);
        $builder->connect('/jewelry/view/:id', ['controller' => 'Jewelry', 'action' => 'view'])
            ->setPass(['id']);
        $builder->connect('/jewelry/add-to-cart', ['controller' => 'Jewelry', 'action' => 'addToCart']);
        $builder->connect('/cart', ['controller' => 'Jewelry', 'action' => 'cart']);
        $builder->connect('/cart/remove', ['controller' => 'Jewelry', 'action' => 'removeFromCart']);
        $builder->connect('/checkout', ['controller' => 'Jewelry', 'action' => 'checkout']);
        $builder->connect('/checkout/create-session', ['controller' => 'Jewelry', 'action' => 'createCheckoutSession']);
        $builder->connect('/checkout/success', ['controller' => 'Jewelry', 'action' => 'success']);
        $builder->connect('/checkout/cancel', ['controller' => 'Jewelry', 'action' => 'cancel']);
        $builder->connect('/stripe/webhook', ['controller' => 'Jewelry', 'action' => 'webhook']);


        //Contact Form Route
        $builder->connect('/contact', ['controller' => 'ContactSubmissions', 'action' => 'add']);

        //Login Route
        $builder->connect('/auth/login', ['controller' => 'Auth', 'action' => 'login']);
        $builder->connect('/auth/logout', ['controller' => 'Auth', 'action' => 'logout']);
        $builder->connect('/auth/forgot-password', ['controller' => 'Auth', 'action' => 'forgotPassword']);
        $builder->connect('/auth/change-password', ['controller' => 'Auth', 'action' => 'changePassword']);

        //Dashboard Route
        $builder->connect('/dashboard', ['controller' => 'Users', 'action' => 'dashboard']);

        //Logout Route
        $builder->connect('/logout', ['controller' => 'Users', 'action' => 'logout']);

        // Profile routes (customer)
        $builder->connect('/profile', ['controller' => 'Profile', 'action' => 'index']);
        $builder->connect('/profile/edit', ['controller' => 'Profile', 'action' => 'edit']);
        $builder->connect('/profile/change-password', ['controller' => 'Profile', 'action' => 'changePassword']);
        $builder->connect('/profile/orders', ['controller' => 'Profile', 'action' => 'orders']);
        $builder->connect('/profile/orders/:id', ['controller' => 'Profile', 'action' => 'orderDetail'])
            ->setPass(['id']);

        //Chat bot Route
        $builder->connect('/chat/message', ['controller' => 'Chat', 'action' => 'message']);

        $builder->fallbacks();
    });

    /*
     * If you need a different set of middleware or none at all,
     * open new scope and define routes there.
     *
     * ```
     * $routes->scope('/api', function (RouteBuilder $builder): void {
     *     // No $builder->applyMiddleware() here.
     *
     *     // Parse specified extensions from URLs
     *     // $builder->setExtensions(['json', 'xml']);
     *
     *     // Connect API actions here.
     * });
     * ```
     */
};
