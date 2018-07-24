<?php 

namespace Voting;

use Voting\Infrastructure\Router;
use Voting\Controller\AwardController;
use Voting\Controller\StatsController;
use Voting\Controller\CategoryController;
use Voting\Controller\AwardCategoryController;
use Voting\Controller\AwardFinalistsController;
use Voting\Controller\NominationsController;
use Voting\Controller\WinnersController;
use Voting\Controller\DownloadsController;

class Routes
{

    public static function init()
    {
        $router = new Router('v1');
        $awardController = new AwardController;
        $categoryController = new CategoryController;
        $awardCategoryController = new AwardCategoryController;
        $awardFinalistsController = new AwardFinalistsController;
        $nominationController = new NominationsController;
        $winnerController = new WinnersController;
        $statsController = new StatsController;
        $downloadsController = new DownloadsController;

        // Award Requests
        $router->get('/awards', [$awardController, 'all']);
        $router->get('/award/(?P<id>\d+)', [$awardController, 'get']);

        // Stats Requests
        $router->get('/award/(?P<id>\d+)/statistics', [$statsController, 'getStatsByAwardId'], 'edit_others_posts');
        $router->get('/award/statistics', [$statsController, 'getStats'], 'edit_others_posts');

        // Category Requests
        $router->get('/category/(?P<id>\d+)', [$categoryController, 'get']);
        $router->get('/categories', [$categoryController, 'all']);
        $router->get('/award/(?P<award_id>\d+)/categories', [$awardCategoryController, 'all']);

        // Download Requests
        $router->get('/award/(?P<award_id>\d+)/nominations', [$downloadsController, 'nominations'], 'edit_others_posts');
        $router->get('/award/(?P<award_id>\d+)/votes', [$downloadsController, 'votes'], 'edit_others_posts');

        // Finalist Requests
        $router->get('/finalists', [$awardFinalistsController, 'all']);
        $router->get('/finalist/(?P<id>\d+)', [$awardFinalistsController, 'find']);
        $router->get('/award/(?P<id>\d+)/finalists', [$awardFinalistsController, 'allByAward']);

        // Winner Requests
        $router->get('/award/(?P<award_id>\d+)/winners', [$winnerController, 'allByAward']);

    }
}