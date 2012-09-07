<?php
abstract class Controller
{
    protected $controllerId;

    public function __construct($controllerId)
    {
        $this->controllerId = $controllerId;
    }

    public function render($view, $data, $layout = null)
    {
        if($layout == null) {
            $layout = USP::app()->getPathByAlias('application.view.layouts').DIRECTORY_SEPARATOR.'main.php';
        } else {
            $layout = USP::app()->getPathByAlias('application.view').DIRECTORY_SEPARATOR.$layout;
        }
        ob_start();
        $this->renderPartial($view, $data);
        $data['content'] = ob_get_contents();
        ob_end_clean();
        $this->renderFile($layout, $data);
    }
    public  function renderFile($file, $data) {
        extract($data);
        include($file);
    }
    public  function renderPartial($view, $data) {
        $viewPath = USP::app()->getPathByAlias('application.view') . DIRECTORY_SEPARATOR
            . $this->controllerId . DIRECTORY_SEPARATOR . $view;
        extract($data);
        include($viewPath);
    }
}
