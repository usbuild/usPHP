<?php
class TestController extends Controller
{
    public $dm;
    public function init() {
        $this->dm = new Distmem();
        $this->dm->connect("localhost", 4327);
        $this->dm->use("test");
    }
    public function indexAction()
    {
        $before = array("data", 12, 19.5);
        $this->dm->set("data", $before);
        $after = $this->dm->get("data");
        $this->render("index.php", array("befor"=>$before, "after"=>$after));
    }
}
