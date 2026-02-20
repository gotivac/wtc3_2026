<div class="navbar navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>

            <!-- Be sure to leave the brand out there if you want it shown -->
            <a class="brand" href="<?php echo Yii::app()->baseUrl; ?>"><img src="<?php echo Yii::app()->theme->baseUrl.'/img/logo.png';?>" /></a>

            <div class="navbar-collapse collapse">



                <?php
                $this->widget('zii.widgets.CMenu', array(
                    'htmlOptions' => array('class' => 'pull-right nav'),
                    'submenuHtmlOptions' => array('class' => 'dropdown-menu'),
                    'itemCssClass' => 'item-test',
                    'encodeLabel' => false,
                    'items' => array(
                        array('label' => Yii::t('app', 'Services'), 'url' => array('/service'), 'visible' => !Yii::app()->user->isGuest),
                        array('label' => Yii::t('app', 'Purchases'), 'url' => array('/purchase'), 'visible' => !Yii::app()->user->isGuest),
                        array('label' => Yii::t('app', 'External Maintenance') . ' <span class="caret"></span>', 'url' => '#', 'itemOptions' => array('class' => 'dropdown', 'tabindex' => "-1"), 'linkOptions' => array('class' => 'dropdown-toggle', 'data-toggle' => "dropdown"),
                            'items' => array(
                                array('label' => Yii::t('app', 'Maintenance Plans'), 'url' => array('/externalMaintenancePlan'), 'visible' => !Yii::app()->user->isGuest && (Yii::app()->user->roles == 'superadministrator' || Yii::app()->user->roles == 'administrator')),
                                array('label' => Yii::t('app', 'External Services'), 'url' => array('/externalMaintenancePlanService'), 'visible' => !Yii::app()->user->isGuest && (Yii::app()->user->roles == 'superadministrator' || Yii::app()->user->roles == 'administrator')),

                            ), 'visible' => !Yii::app()->user->isGuest && (Yii::app()->user->roles == 'superadministrator' || Yii::app()->user->roles == 'administrator')),
                        array('label' => Yii::t('app', 'Facility Check List') . ' <span class="caret"></span>', 'url' => '#', 'itemOptions' => array('class' => 'dropdown', 'tabindex' => "-1"), 'linkOptions' => array('class' => 'dropdown-toggle', 'data-toggle' => "dropdown"),
                            'items' => array(
                                array('label' => Yii::t('app', 'Facility Check List'), 'url' => array('/facilityChecklist'), 'visible' => !Yii::app()->user->isGuest && (Yii::app()->user->roles == 'superadministrator' || Yii::app()->user->roles == 'administrator')),
                                array('label' => Yii::t('app', 'Facility Areas'), 'url' => array('/facilityArea'), 'visible' => !Yii::app()->user->isGuest && (Yii::app()->user->roles == 'superadministrator' || Yii::app()->user->roles == 'administrator')),

                            ), 'visible' => !Yii::app()->user->isGuest && (Yii::app()->user->roles == 'superadministrator' || Yii::app()->user->roles == 'administrator')),


                        array('label' => Yii::t('app', 'Administration') . ' <span class="caret"></span>', 'url' => '#', 'itemOptions' => array('class' => 'dropdown', 'tabindex' => "-1"), 'linkOptions' => array('class' => 'dropdown-toggle', 'data-toggle' => "dropdown"),
                            'items' => array(
                                array('label' => Yii::t('app', 'Lines'), 'url' => array('/line'), 'visible' => !Yii::app()->user->isGuest && (Yii::app()->user->roles == 'superadministrator' || Yii::app()->user->roles == 'administrator')),
                                array('label' => Yii::t('app', 'Machines'), 'url' => array('/machine'), 'visible' => !Yii::app()->user->isGuest && (Yii::app()->user->roles == 'superadministrator' || Yii::app()->user->roles == 'administrator')),
                                // array('label' => Yii::t('app', 'Machine Categories'), 'url' => array('/machineCategory'), 'visible' => !Yii::app()->user->isGuest && Yii::app()->user->roles == 'superadministrator'),
                                array('label' => Yii::t('app', 'Spare Parts'), 'url' => array('/sparePart'), 'visible' => !Yii::app()->user->isGuest && (Yii::app()->user->roles == 'superadministrator' || Yii::app()->user->roles == 'administrator')),
                                array('label' => Yii::t('app', 'Failures'), 'url' => array('/failure'), 'visible' => !Yii::app()->user->isGuest && (Yii::app()->user->roles == 'superadministrator' || Yii::app()->user->roles == 'administrator')),
                                array('label' => Yii::t('app', 'Users'), 'url' => array('/user'), 'visible' => (!Yii::app()->user->isGuest && (Yii::app()->user->roles == 'administrator' || Yii::app()->user->roles == 'superadministrator'))),
                               // array('label' => Yii::t('app', 'Barcodes'), 'url' => array('/site/barcode'), 'visible' => !Yii::app()->user->isGuest && (Yii::app()->user->roles == 'superadministrator')),
                            ), 'visible' => !Yii::app()->user->isGuest && (Yii::app()->user->roles == 'superadministrator' || Yii::app()->user->roles == 'administrator')),
                        
                        array('label' => 'Profil <span class="caret"></span>', 'url' => '#', 'itemOptions' => array('class' => 'dropdown', 'tabindex' => "-1"), 'linkOptions' => array('class' => 'dropdown-toggle', 'data-toggle' => "dropdown"),
                            'items' => array(
                                array('label' => Yii::t('app', 'Change Password'), 'url' => array('/user/passwordself'), 'visible' => (!Yii::app()->user->isGuest)),
                                array('label' => (isset(Yii::app()->user->name)) ? Yii::t('app', 'Logout') . ' (' . Yii::app()->user->name . ')' : '', 'url' => array('/site/logout'), 'visible' => !Yii::app()->user->isGuest),
                            ), 'visible' => !Yii::app()->user->isGuest),
                         array('label' => Yii::t('app', 'Device') . ' <span class="caret"></span>', 'url' => '#', 'itemOptions' => array('class' => 'dropdown', 'tabindex' => "-1"), 'linkOptions' => array('class' => 'dropdown-toggle', 'data-toggle' => "dropdown"),
                            'items' => array(
                                array('label' => Yii::t('app', 'Computer'), 'url' => array('site/setPC'), 'visible' => !Yii::app()->user->isGuest && Yii::app()->user->roles == 'superadministrator'),
                                array('label' => Yii::t('app', 'Scanner'), 'url' => array('site/setScanner'), 'visible' => !Yii::app()->user->isGuest && (Yii::app()->user->roles == 'superadministrator' || Yii::app()->user->roles == 'administrator')),
                            ), 'visible' => !Yii::app()->user->isGuest && (Yii::app()->user->roles == 'superadministrator' || Yii::app()->user->roles == 'administrator')),
                        
                    ),
                ));
                ?>
            </div>
        </div>
    </div>
</div>
