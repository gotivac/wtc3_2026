<div class="padding-15">

    <div class="login-box">

        <!-- login form -->
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'login-form',
            
            'enableClientValidation' => true,
            'clientOptions' => array(
                'validateOnSubmit' => true,
            ),
            'htmlOptions' => array(
                'class' => 'sky-form boxed',
            )
        ));
        ?>
        <header>Warehouse Transit Control v3.0</header>

        <!--
        <div class="alert alert-danger noborder text-center weight-400 nomargin noradius">
                Invalid Email or Password!
        </div>

        <div class="alert alert-warning noborder text-center weight-400 nomargin noradius">
                Account Inactive!
        </div>

        <div class="alert alert-default noborder text-center weight-400 nomargin noradius">
                <strong>Too many failures!</strong> <br />
                Please wait: <span class="inlineCountdown" data-seconds="180"></span>
        </div>
        -->

        <fieldset>	

            <section>
                <label class="label"><?php echo Yii::t('app','Email');?></label>
                <label class="input">
                    <i class="icon-append fa fa-envelope"></i>
                    <?php echo $form->textField($model, 'email'); ?>
                    <span class="tooltip tooltip-top-right">Email adresa</span>
                </label>
            </section>

            <section>
                <label class="label"><?php echo Yii::t('app','Password');?></label>
                <label class="input">
                    <i class="icon-append fa fa-lock"></i>
                    <?php echo $form->passwordField($model, 'password'); ?>
                    <b class="tooltip tooltip-top-right">Unesite lozinku</b>
                </label>
                <label class="checkbox"><input type="checkbox" name="LoginForm[rememberMe]" checked><i></i><?=Yii::t('app','Remember Me');?></label>
            </section>

        </fieldset>

        <footer>
            <button type="submit" class="btn btn-primary pull-right">Sign In</button>

        </footer>
        <?php $this->endWidget(); ?>
        <!-- /login form -->



    </div>

</div>