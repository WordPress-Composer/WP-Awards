<?php

use Voting\Controller\CategoryController;
use Voting\Controller\NominationsController;
use Voting\Controller\AwardFinalistsController;
use Voting\Controller\AwardController;
use Voting\Controller\WinnersController;
use Voting\Controller\VotesController;
use Voting\Controller\AwardCategoryController;

use Voting\Infrastructure\Migrate;

use Voting\Routes;

use Voting\Infrastructure\Menu;
use Voting\Infrastructure\MenuOptions;
use Voting\Infrastructure\RouteHandler;
use Voting\Infrastructure\RequestObject;

function plugin_activate() {
    Migrate::tables();
}
register_activation_hook( __DIR__ . '/voting.php', 'plugin_activate' );

/**
 * WP AJAX Routes
 */

function saveNomination()
{
	$nominationsController = new NominationsController();
	$nominationsController->saveNomination();
}
add_action('wp_ajax_nopriv_save_nomination', 'saveNomination');
add_action('wp_ajax_save_nomination', 'saveNomination');

function patchAwardFinalist()
{
    $controller = new AwardFinalistsController;
    $handler = new RouteHandler([$controller, 'update'], [$_REQUEST]);
}
add_action('wp_ajax_patch_award_finalist', 'patchAwardFinalist');

function createAwardFinalist()
{
    $controller = new AwardFinalistsController;
    $handler = new RouteHandler([$controller, 'create'], [$_REQUEST]);
}
add_action('wp_ajax_create_award_finalist', 'createAwardFinalist');

function deleteAwardFinalist()
{
    $controller = new AwardFinalistsController;
    $handler = new RouteHandler([$controller, 'delete'], [$_REQUEST]);
}
add_action('wp_ajax_delete_award_finalist', 'deleteAwardFinalist');

function createWinner()
{
    $controller = new WinnersController;
    $handler = new RouteHandler([$controller, 'updateOrCreate'], [$_REQUEST]);
}
add_action('wp_ajax_create_winner', 'createWinner');

function deleteAwardWinners()
{
    $controller = new WinnersController;
    $handler = new RouteHandler([$controller, 'delete'], [$_REQUEST]);
}
add_action('wp_ajax_delete_winner', 'deleteAwardWinners');

function archiveAward()
{
    $controller = new AwardController;
    $handler = new RouteHandler([$controller, 'archive'], [$_REQUEST]);
}
add_action('wp_ajax_archive_award', 'archiveAward');

function goLiveAward()
{
    $controller = new AwardController;
    $handler = new RouteHandler([$controller, 'goLive'], [$_REQUEST]);
}
add_action('wp_ajax_go_live_with_award', 'goLiveAward');

function unpublishAward()
{
    $controller = new AwardController;
    $handler = new RouteHandler([$controller, 'unpublish'], [$_REQUEST]);
}
add_action('wp_ajax_unpublish_award', 'unpublishAward');

function publishWinners()
{
    $controller = new AwardController;
    $handler = new RouteHandler([$controller, 'publishWinners'], [$_REQUEST]);
}
add_action('wp_ajax_publish_winners', 'publishWinners');

function unpublishWinners()
{
    $controller = new AwardController;
    $handler = new RouteHandler([$controller, 'unpublishWinners'], [$_REQUEST]);
}
add_action('wp_ajax_unpublish_winners', 'unpublishWinners');

function saveVote()
{
    $controller = new VotesController;
    $handler = new RouteHandler([$controller, 'create'], [$_REQUEST]);
}
add_action('wp_ajax_save_vote', 'saveVote');
add_action('wp_ajax_nopriv_save_vote', 'saveVote');

function createCategory()
{
    $controller = new CategoryController;
    $handler = new RouteHandler([$controller, 'create'], [$_REQUEST]);
}
add_action('wp_ajax_create_category', 'createCategory');

function updateCategory()
{
    $controller = new CategoryController;
    $handler = new RouteHandler([$controller, 'update'], [$_REQUEST]);
}
add_action('wp_ajax_patch_category', 'updateCategory');

function deleteCategory()
{
    $controller = new CategoryController;
    $handler = new RouteHandler([$controller, 'delete'], [$_REQUEST]);
}
add_action('wp_ajax_delete_category', 'deleteCategory');

function createAwardCategory()
{
    $controller = new AwardCategoryController;
    $handler = new RouteHandler([$controller, 'create'], [$_REQUEST]);
}
add_action('wp_ajax_create_award_category', 'createAwardCategory');

function deleteAwardCategory()
{
    $controller = new AwardCategoryController;
    $handler = new RouteHandler([$controller, 'delete'], [$_REQUEST]);
}
add_action('wp_ajax_delete_award_category', 'deleteAwardCategory');

function createAward()
{
    $controller = new AwardController;
    $handler = new RouteHandler([$controller, 'create'], [$_REQUEST]);
}
add_action('wp_ajax_create_award', 'createAward');

function patchAward()
{
    $controller = new AwardController;
    $handler = new RouteHandler([$controller, 'update'], [$_REQUEST]);
}
add_action('wp_ajax_patch_award', 'patchAward');



/**
 * Initialise Routes
 * Only readable routes
 */
Routes::init();

/**
 * Menus
 */
Menu::add(new MenuOptions([
	'title' => 'Awards',
    'capability' => 'edit_others_pages',
    'slug' => 'voting-awards',
    'templateName' => 'awards',
]));

Menu::add(new MenuOptions([
	'title' => 'Categories',
    'capability' => 'edit_others_pages',
    'slug' => 'voting-categories',
    'templateName' => 'categories',
	'parentSlug' => 'voting-awards'
]));

add_action('admin_init', function() {
    wp_localize_script('voting-client-js', 'wpApiSettings', array(
        'root' => esc_url_raw(rest_url()),
        'nonce' => wp_create_nonce('wp_rest')
    ));
});
