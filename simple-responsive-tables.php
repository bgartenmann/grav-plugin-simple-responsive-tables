<?php
namespace Grav\Plugin;

use Grav\Common\Plugin;
use RocketTheme\Toolbox\Event\Event;

/**
 * Class SimpleResponsiveTablesPlugin
 * @package Grav\Plugin
 */
class SimpleResponsiveTablesPlugin extends Plugin
{
  /**
   * @return array
   *
   * The getSubscribedEvents() gives the core a list of events
   *     that the plugin wants to listen to. The key of each
   *     array section is the event that the plugin listens to
   *     and the value (in the form of an array) contains the
   *     callable (or function) as well as the priority. The
   *     higher the number the higher the priority.
   */
  public static function getSubscribedEvents()
  {
    return [
      'onPluginsInitialized' => ['onPluginsInitialized', 0]
    ];
  }

  /**
   * Initialize the plugin
   */
  public function onPluginsInitialized()
  {
    // Don't proceed if we are in the admin plugin
    if ($this->isAdmin()) {
        return;
    }

    // Enable the main event we are interested in
    $this->enable([
        'onPageContentProcessed' => ['onPageContentProcessed', 0],
        'onPageInitialized' => ['onPageInitialized', 0]
    ]);
  }

  /**
   * Finds tables in page content and wraps with div
   * @return void
   */
  public function onPageContentProcessed(Event $event)
  {
    $page = $event['page'];
    $buffer = $page->content();
    $url = $page->url();

    // add opening div tag with corresponding class for styling
    $buffer = preg_replace("/<table[^>]*>/", "<div class='simple-responsive-table'><div>$0", $buffer);
    // add closing div tag after table
    $buffer = preg_replace("/<\/table>/", "$0</div></div>", $buffer);

    $page->setRawContent($buffer);
  }

  /**
   * Add assets
   *
   */
  public function onPageInitialized()
  {
    $assets = $this->grav['assets'];
    // Add custom styles
    $assets->addCss('plugin://simple-responsive-tables/assets/css/simple-responsive-tables.css', 110);
    // Add custom javascript
    $assets->addJs('plugin://simple-responsive-tables/assets/js/simple-responsive-tables.js', 110);
  }
}
