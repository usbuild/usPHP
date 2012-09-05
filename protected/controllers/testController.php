<?php
class TestController extends Controller {
    public function indexAction(){
        $test = new Test();
        $data = $test->findAllByAttributes(array());
        var_dump($data);
        $this->render("index.php", array());
    }
}
