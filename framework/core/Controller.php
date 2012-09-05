<?php
abstract class Controller
{
    protected $controllerId;

    public function __construct($controllerId)
    {
        $this->controllerId = $controllerId;
    }

    public function render($view, $data)
    {
        $viewPath = USP::app()->getPathByAlias('application.view') . DIRECTORY_SEPARATOR
            . $this->controllerId . DIRECTORY_SEPARATOR . $view;
        extract($data);
        include($viewPath);
    }
}
