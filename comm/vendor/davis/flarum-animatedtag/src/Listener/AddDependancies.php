<?php namespace davis\animatedtag\Listener;

use Flarum\Event\ConfigureClientView;
use Illuminate\Contracts\Events\Dispatcher;
class AddDependancies
{
    public function subscribe(Dispatcher $events)
    {
        $events->listen(ConfigureClientView::class, [$this, 'addAssets']);
    }
    public function addAssets(ConfigureClientView $event)
    {
        if($event->isForum()) {
                $js = file_get_contents(realpath(__DIR__ . '/../../assets/js/depend.js'));
                $event->view->addHeadString($js);
        }
    }
}