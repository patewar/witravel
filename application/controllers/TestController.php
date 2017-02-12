<?php

class TestController extends WiTravelBaseController
{

    public function init()
    {
      $this->getHelper('ViewRenderer')
        ->setNoRender();

        $this->getResponse()
         ->setHeader('Content-Type', 'application/json');
    }

    public function helloAction()
    {


        $res = array(
          'name' => 'venkat',
          'email' => 'venkat@gmail.com',
          'contact' => '99999999'
        );

        echo json_encode($res);
    }


}
