<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>

    <!-- WRAPPER -->
    <div id="wrapper">

        <!--
                ASIDE
                Keep it outside of #wrapper (responsive purpose)
        -->
        <aside id="aside">
            <!--
                    Always open:
                    <li class="active alays-open">

                    LABELS:
                            <span class="label label-danger pull-right">1</span>
                            <span class="label label-default pull-right">1</span>
                            <span class="label label-warning pull-right">1</span>
                            <span class="label label-success pull-right">1</span>
                            <span class="label label-info pull-right">1</span>
            -->
            <nav id="sideNav"><!-- MAIN MENU -->
                <ul class="nav nav-list">
                    <li><!-- dashboard -->
                        <a class="dashboard" href="/"><!-- warning - url used by default by ajax (if eneabled) -->
                            <i class="main-icon fa fa-dashboard"></i> <span>Dashboard</span>
                        </a>
                    </li>
                    <?php if ($this->userAccess('timeSlot')): ?>
                        <li<?php echo ($this->getId() == 'timeSlot') ? ' class="active"' : ''; ?>>
                            <a href="<?php echo Yii::app()->createUrl('timeSlot'); ?>">
                                <i class="main-icon fa fa-calendar"></i><span><?php echo Yii::t('app', 'Time Slots'); ?></span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if ($this->userAccess('order')): ?>
                        <li<?php echo ($this->getId() == 'order' || $this->getId() == 'orderProduct') ? ' class="active"' : ''; ?>>
                            <a href="<?php echo Yii::app()->createUrl('order'); ?>">
                                <i class="main-icon fa fa-puzzle-piece"></i><span><?php echo Yii::t('app', 'Orders'); ?></span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if ($this->userAccess('activity')): ?>
                        <li<?php echo ($this->getId() == 'activity' || $this->getId() == 'activityOrderProduct') ? ' class="active"' : ''; ?>>
                            <a href="<?php echo Yii::app()->createUrl('activity'); ?>">
                                <i class="main-icon fa fa-truck"></i><span><?php echo Yii::t('app', 'Activities'); ?></span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if ($this->userAccess('webOrder')): ?>
                        <li>
                            <a href="#">
                                <i class="fa fa-menu-arrow pull-right"></i>
                                <i class="main-icon fa fa-globe"></i>
                                <span><?php echo Yii::t('app', 'Web Sale'); ?></span>
                            </a>
                            <ul>
                                <li<?php echo ($this->getId() == 'webOrder') ? ' class="active"' : ''; ?>><a
                                            href="<?php echo Yii::app()->createUrl('webOrder'); ?>"><?php echo Yii::t('app', 'Orders'); ?></a>
                                </li>
                                <li<?php echo ($this->getId() == 'slocHasProduct') ? ' class="active"' : ''; ?>><a
                                            href="<?php echo Yii::app()->createUrl('slocHasProduct'); ?>"><?php echo Yii::t('app', 'Stock'); ?></a>
                                </li>
                            </ul>
                        </li>

                    <?php endif; ?>


                    <?php if ($this->userAccess('mheActivity') || $this->userAccess('mheFailureNotice')): ?>

                    <li>
                        <a href="#">
                            <i class="fa fa-menu-arrow pull-right"></i>
                            <i class="main-icon glyphicon glyphicon-bell"></i>
                            <span><?php echo Yii::t('app', 'MHE'); ?></span>
                        </a>
                        <ul>
                            <li<?php echo ($this->getId() == 'mheActivity') ? ' class="active"' : ''; ?>><a
                                        href="<?php echo Yii::app()->createUrl('mheActivity'); ?>"><?php echo Yii::t('app', 'Activities'); ?></a>
                            </li>
                            <li<?php echo ($this->getId() == 'mheFailureNotice') ? ' class="active"' : ''; ?>><a
                                        href="<?php echo Yii::app()->createUrl('mheFailureNotice'); ?>"><?php echo Yii::t('app', 'Failure Notices'); ?></a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <?php endif; ?>
                        <?php if ($this->userAccess('slocHasActivityPalett')): ?>
                        <a href="#">
                            <i class="fa fa-menu-arrow pull-right"></i>
                            <i class="main-icon glyphicon glyphicon-eye-open"></i>
                            <span><?php echo Yii::t('app', 'Views'); ?></span>
                        </a>
                        <ul>
                            <li<?php echo ($this->getId() == 'slocHasActivityPalett') ? ' class="active"' : ''; ?>><a
                                        href="<?php echo Yii::app()->createUrl('slocHasActivityPalett'); ?>"><?php echo Yii::t('app', 'Occupied SLOCs'); ?></a>
                            </li>
                        </ul>
                        <ul>
                            <li<?php echo ($this->getId() == 'slocHasActivityPalett') ? ' class="active"' : ''; ?>><a
                                        href="<?php echo Yii::app()->createUrl('slocHasActivityPalett/resEmptySSCC'); ?>"><?php echo Yii::t('app', 'Empty Paletts'); ?></a>
                            </li>
                        </ul>
                        <ul>
                            <li<?php echo ($this->getId() == 'slocHasActivityPalett') ? ' class="active"' : ''; ?>><a
                                        href="<?php echo Yii::app()->createUrl('slocHasActivityPalett/resGateIn'); ?>"><?php echo Yii::t('app', 'Gate IN'); ?></a>
                            </li>
                        </ul>
                        <ul>
                            <li<?php echo ($this->getId() == 'slocHasActivityPalett') ? ' class="active"' : ''; ?>><a
                                        href="<?php echo Yii::app()->createUrl('slocHasActivityPalett/resGateOut'); ?>"><?php echo Yii::t('app', 'Gate OUT'); ?></a>
                            </li>
                        </ul>
                        <ul>
                            <li<?php echo ($this->getId() == 'slocHasActivityPalett') ? ' class="active"' : ''; ?>><a
                                        href="<?php echo Yii::app()->createUrl('slocHasActivityPalett/resProductHistory'); ?>"><?php echo Yii::t('app', 'Product History'); ?></a>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>

                    <?php if (Yii::app()->user->roles == 'superadministrator' || Yii::app()->user->roles == 'administrator'): ?>

                        <li<?= ($this->module && $this->module->name == 'systemSettings') ? ' class="menu-open"' : ''; ?>>
                            <a href="#">
                                <i class="fa fa-menu-arrow pull-right"></i>
                                <i class="main-icon fa fa-gear"></i>
                                <span><?php echo Yii::t('app', 'System Settings'); ?></span>
                            </a>

                            <ul>
                                <li<?php echo ($this->getId() == 'client') ? ' class="active"' : ''; ?>><a
                                            href="<?php echo Yii::app()->createUrl('systemSettings/client'); ?>"><?php echo Yii::t('app', 'Clients'); ?></a>
                                </li>
                                <li<?php echo ($this->getId() == 'unloadingLevel') ? ' class="active"' : ''; ?>><a
                                            href="<?php echo Yii::app()->createUrl('systemSettings/unloadingLevel'); ?>"><?php echo Yii::t('app', 'Unloading Levels'); ?></a>
                                </li>

                                <li<?php echo ($this->getId() == 'location') ? ' class="active"' : ''; ?>><a
                                            href="<?php echo Yii::app()->createUrl('systemSettings/location'); ?>"><?php echo Yii::t('app', 'Locations'); ?></a>
                                </li>
                                <li<?php echo ($this->getId() == 'section') ? ' class="active"' : ''; ?>><a
                                            href="<?php echo Yii::app()->createUrl('systemSettings/section'); ?>"><?php echo Yii::t('app', 'Sections'); ?></a>
                                </li>
                                <li>
                                    <a href="#">
                                        <i class="fa fa-menu-arrow pull-right"></i>
                                        <!-- <i class="main-icon fa fa-gear"></i> -->
                                        <span><?php echo Yii::t('app', 'SLOC'); ?></span>
                                    </a>
                                    <ul>
                                        <li<?php echo ($this->getId() == 'slocType') ? ' class="active"' : ''; ?>><a
                                                    href="<?php echo Yii::app()->createUrl('systemSettings/slocType'); ?>"><?php echo Yii::t('app', 'Sloc Types'); ?></a>
                                        </li>
                                        <li<?php echo ($this->getId() == 'sloc') ? ' class="active"' : ''; ?>><a
                                                    href="<?php echo Yii::app()->createUrl('systemSettings/sloc'); ?>"><?php echo Yii::t('app', 'Sloc'); ?></a>
                                        </li>
                                    </ul>
                                </li>
                                <li<?php echo ($this->getId() == 'gateType') ? ' class="active"' : ''; ?>><a
                                            href="<?php echo Yii::app()->createUrl('systemSettings/gateType'); ?>"><?php echo Yii::t('app', 'Gate Types'); ?></a>
                                </li>
                                <li<?php echo ($this->getId() == 'gate') ? ' class="active"' : ''; ?>><a
                                            href="<?php echo Yii::app()->createUrl('systemSettings/gate'); ?>"><?php echo Yii::t('app', 'Gates'); ?></a>
                                </li>
                                <li<?php echo ($this->getId() == 'truckType') ? ' class="active"' : ''; ?>><a
                                            href="<?php echo Yii::app()->createUrl('systemSettings/truckType'); ?>"><?php echo Yii::t('app', 'Truck Types'); ?></a>
                                </li>
                                <li<?php echo ($this->getId() == 'storageType') ? ' class="active"' : ''; ?>><a
                                            href="<?php echo Yii::app()->createUrl('systemSettings/storageType'); ?>"><?php echo Yii::t('app', 'Storage Types'); ?></a>
                                </li>
                                <li<?php echo ($this->getId() == 'workplace') ? ' class="active"' : ''; ?>><a
                                            href="<?php echo Yii::app()->createUrl('systemSettings/workplace'); ?>"><?php echo Yii::t('app', 'Workplaces'); ?></a>
                                </li>
                                <li<?php echo ($this->getId() == 'worker') ? ' class="active"' : ''; ?>><a
                                            href="<?php echo Yii::app()->createUrl('systemSettings/worker'); ?>"><?php echo Yii::t('app', 'Workers'); ?></a>
                                </li>
                                <!--
                                <li<?php echo ($this->getId() == 'default') ? ' class="active"' : ''; ?>><a
                                            href="<?php echo Yii::app()->createUrl('systemSettings/default/timeSlotSettings'); ?>"><?php echo Yii::t('app', 'Time Slot Settings'); ?></a>
                                </li>
                                -->
                                <li<?php echo ($this->getId() == 'activityType') ? ' class="active"' : ''; ?>><a
                                            href="<?php echo Yii::app()->createUrl('systemSettings/activityType'); ?>"><?php echo Yii::t('app', 'Activity Types'); ?></a>
                                </li>

                                <li<?php echo ($this->getId() == 'emailSchedule') ? ' class="active"' : ''; ?>><a
                                            href="<?php echo Yii::app()->createUrl('systemSettings/emailSchedule'); ?>"><?php echo Yii::t('app', 'Email Reports'); ?></a>
                                </li>
                                <li>
                                    <a href="#">
                                        <i class="fa fa-menu-arrow pull-right"></i>
                                        <!-- <i class="main-icon fa fa-gear"></i> -->
                                        <span><?php echo Yii::t('app', 'MHE'); ?></span>
                                    </a>
                                    <ul>
                                        <li<?php echo ($this->getId() == 'mheType') ? ' class="active"' : ''; ?>><a
                                                    href="<?php echo Yii::app()->createUrl('systemSettings/mheType'); ?>"><?php echo Yii::t('app', 'MHE Types'); ?></a>
                                        </li>
                                        <li<?php echo ($this->getId() == 'mheActivityType') ? ' class="active"' : ''; ?>>
                                            <a
                                                    href="<?php echo Yii::app()->createUrl('systemSettings/mheActivityType'); ?>"><?php echo Yii::t('app', 'MHE Activity Types'); ?></a>
                                        </li>
                                        <li<?php echo ($this->getId() == 'mheLocation') ? ' class="active"' : ''; ?>><a
                                                    href="<?php echo Yii::app()->createUrl('systemSettings/mheLocation'); ?>"><?php echo Yii::t('app', 'MHE Locations'); ?></a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="#">
                                        <i class="fa fa-menu-arrow pull-right"></i>
                                        <!-- <i class="main-icon fa fa-gear"></i> -->
                                        <span><?php echo Yii::t('app', 'Products'); ?></span>
                                    </a>
                                    <ul>
                                        <li<?php echo ($this->getId() == 'product') ? ' class="active"' : ''; ?>><a
                                                    href="<?php echo Yii::app()->createUrl('systemSettings/product'); ?>"><?php echo Yii::t('app', 'Products'); ?></a>
                                        </li>
                                        <li<?php echo ($this->getId() == 'productType') ? ' class="active"' : ''; ?>><a
                                                    href="<?php echo Yii::app()->createUrl('systemSettings/productType'); ?>"><?php echo Yii::t('app', 'Product Types'); ?></a>
                                        </li>
                                        <li<?php echo ($this->getId() == 'package') ? ' class="active"' : ''; ?>><a
                                                    href="<?php echo Yii::app()->createUrl('systemSettings/package'); ?>"><?php echo Yii::t('app', 'Packages'); ?></a>
                                        </li>
                                        <li<?php echo ($this->getId() == 'loadCarrier') ? ' class="active"' : ''; ?>><a
                                                    href="<?php echo Yii::app()->createUrl('systemSettings/loadCarrier'); ?>"><?php echo Yii::t('app', 'Load Carriers'); ?></a>
                                        </li>
                                    </ul>
                                </li>


                            </ul>
                        </li>
<?php endif;?>
                    <?php if (Yii::app()->user->roles == 'superadministrator'): ?>
                        <li>
                            <a href="#">
                                <i class="fa fa-menu-arrow pull-right"></i>
                                <i class="main-icon fa fa-key"></i>
                                <span><?php echo Yii::t('app', 'Access Rights'); ?></span>
                            </a>
                            <ul>
                                <li<?php echo ($this->getId() == 'user') ? ' class="active"' : ''; ?>>
                                    <a href="<?php echo Yii::app()->createUrl('rbac/user'); ?>">

                                        <span><?php echo Yii::t('app', 'Users'); ?></span>
                                    </a>

                                </li>
                                <li<?php echo ($this->getId() == 'authRole') ? ' class="active"' : ''; ?>><a
                                            href="<?php echo Yii::app()->createUrl('rbac/authRole'); ?>"><?php echo Yii::t('app', 'Roles'); ?></a>
                                </li>
                                <li<?php echo ($this->getId() == 'authController') ? ' class="active"' : ''; ?>><a
                                            href="<?php echo Yii::app()->createUrl('rbac/authController'); ?>"><?php echo Yii::t('app', 'Controllers'); ?></a>
                                </li>


                            </ul>
                        </li>

                    <?php endif; ?>

                </ul>


            </nav>

            <span id="asidebg"><!-- aside fixed background --></span>
        </aside>
        <!-- /ASIDE -->


        <!-- HEADER -->
        <header id="header">

            <!-- Mobile Button -->
            <button id="mobileMenuBtn"></button>

            <!-- Logo -->
            <span class="logo pull-left">
            <img src="<?php echo Yii::app()->theme->baseUrl . '/img/logo.png'; ?>" alt="admin panel" height="30"/>
        </span>


            <div class="nav-collapse">

                <!-- OPTIONS LIST -->
                <ul class="nav pull-right">

                    <!-- USER OPTIONS -->
                    <li class="dropdown pull-right">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img class="user-avatar" alt="" src="<?php
                            echo Yii::app()->theme->baseUrl;;
                            ?>/images/noavatar.jpg" height="34"/>
                            <span class="user-name">
                            <span class="hidden-xs">
<?php echo Yii::app()->user->name; ?> <i class="fa fa-angle-down"></i>
                            </span>
                        </span>
                        </a>
                        <ul class="dropdown-menu hold-on-click">

                            <li><!-- lockscreen -->
                                <a href="<?php echo Yii::app()->createUrl('rbac/user/passwordself'); ?>"><i
                                            class="fa fa-lock"></i> <?php echo Yii::t('app', 'Change Password'); ?></a>
                            </li>
                            <li><!-- logout -->
                                <a href="<?php echo Yii::app()->createUrl('site/logout'); ?>"><i
                                            class="fa fa-power-off"></i> <?php echo Yii::t('app', 'Log Out'); ?></a>
                            </li>
                        </ul>
                    </li>
                    <!-- /USER OPTIONS -->

                </ul>
                <!-- /OPTIONS LIST -->

            </div>

        </header>
        <!-- /HEADER -->

        <!-- Include content pages -->
        <section id="middle">
            <?php if (isset($this->breadcrumbs) && isset(Yii::app()->session['authenticated'])): ?>
                <?php
                $this->widget('zii.widgets.CBreadcrumbs', array(
                    'links' => $this->breadcrumbs,
                    'homeLink' => CHtml::link((Yii::app()->session['location']) ? strtoupper(Yii::app()->session['location']->title) : 'GLOBAL', Yii::app()->createUrl('/')),
                    'htmlOptions' => array('class' => 'breadcrumb')
                ));
                ?><!-- breadcrumbs -->
            <?php endif ?>
            <?php
            $this->widget('booster.widgets.TbMenu', array(
                /* 'type'=>'list', */
                //'encodeLabel'=>false,
                'type' => 'pills',
                'items' => $this->menu,
            ));
            ?>

            <?php echo $content; ?>
        </section>
    </div><!--/WRAPPER-->


<?php $this->endContent(); ?>