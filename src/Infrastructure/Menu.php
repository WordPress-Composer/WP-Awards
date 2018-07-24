<?php 
namespace Voting\Infrastructure;
use Exception;

/**
 * Menus controller
 * @todo Only load javascript and styles on required pages
 * @author Gemma Black <gblackuk@gmail.com>
 */
class Menu
{
    private $options;
    private $page;

    public function __construct(MenuOptions $options)
    {
        $this->validate($options);
        $this->options = $options;
        
        add_action('admin_init', [$this, 'registerStyles']);
        add_action('admin_init', [$this, 'registerScripts']);
        add_action('admin_menu', [$this, 'registerSettings']);
    }

    /**
     * Checks if it's one of our plugin pages
     *
     * @return boolean
     */
    private function isVotingPage()
    {
        return isset($_GET['page']) && strrpos($_GET['page'], 'voting-') !== false;
    }

    /**
     * Adds a new menu to the WordPress admin
     *
     * @param MenuOptions $options
     * @return void
     */
    public static function add(MenuOptions $options)
    {
        new self($options);
    }

    /**
     * Registers the admin styles
     *
     * @return void
     */
    public function registerStyles()
    {
        if (!$this->isVotingPage()) {
            return;
        }
        
        //Webpack needs configuring to extract this
        //wp_register_style('voting-admin-css', plugin_dir_url(dirname(__FILE__)) . '/../../dist/client.css');
    }

    /**
     * Registers the admin scripts
     *
     * @return void
     */
    public function registerScripts()
    {
        if (!$this->isVotingPage()) {
            return;
        }

        $cacheBust = '';

        $manifestFile = __DIR__ . '/../../dist/manifest.json';

        if (file_exists($manifestFile)) {
            $file = file_get_contents($manifestFile);
            $manifest = json_decode($file);
            $cacheBust = !empty($manifest->version) ? '?version=' . $manifest->version : '';
        } else {
            error_log('Voting plugin cannot find manifest.json: ' . $manifestFile);
        }
        
        wp_register_script(
            'voting-client-js', 
            plugin_dir_url(dirname(__FILE__)) . '/../../dist/client.js' . $cacheBust, 
            [], 
            null, 
            false 
        );
    }

    /**
     * Registers the admin settings
     *
     * @return void
     */
    public function registerSettings()
    {
        if (isset($this->options->parentSlug)) {
            $this->page = add_submenu_page(
                $this->options->parentSlug,
                $this->options->title,
                $this->options->title, 
                $this->options->capability, 
                $this->options->slug, 
                [$this, 'view']
            );
        } else {
            $this->page = add_menu_page(
                $this->options->title,
                $this->options->title, 
                $this->options->capability, 
                $this->options->slug, 
                [$this, 'view'],
                $this->options->iconURL,
                $this->options->position
            );
        }
        add_action("admin_print_styles-{$this->page}", [$this, 'enqueueStyles']);
        add_action("admin_enqueue_scripts", [$this, 'enqueueScripts']);
    }

    /**
     * Enqueues the styles
     *
     * @return void
     */
    public function enqueueStyles()
    {
        wp_enqueue_style('voting-admin-css');
    }

    /**
     * Enqueues scripts
     *
     * @return void
     */
    public function enqueueScripts()
    {
        wp_enqueue_media();
        wp_enqueue_script('voting-client-js');
    }

    /**
     * View helper
     *
     * @return void
     */
    public function view()
    {   
        ob_start();
        include __DIR__ . '/../../view/'. $this->options->templateName . '.php';
        $view = ob_get_contents();
        ob_end_clean();
        echo $view;
    }

    /**
     * Validates the options entered
     *
     * @param MenuOptions $options
     * @return void
     */
    private function validate(MenuOptions $options)
    {
        if (!isset($options->title)) {
            throw new Exception('Menu must have a title');
        }
        if (!isset($options->slug)) {
            throw new Exception('Menu must have a slug');
        }
        if (!isset($options->templateName)) {
            throw new Exception('Menu must reference a template name');
        }
    }
}