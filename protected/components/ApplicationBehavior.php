<?php

class ApplicationBehavior extends CBehavior
{
    private $_owner;

    public function events()
    {
        return array(
            'onBeginRequest' => 'denyEverything',
        );
    }

    public function denyEverything()
    {

        $owner = $this->getOwner();
        $currentUrl = Yii::app()->request->url;

        if (stripos($currentUrl, '/api/') > 0 || stripos($currentUrl, 'ws') > 0) {

            // Do nothing; just allow acces for API requests. -- Gotivac 05.02.2021
        } else {
            if ($owner->user->getIsGuest()) {
                $owner->catchAllRequest = array("site/login");
            }
        }
    }
}

?>