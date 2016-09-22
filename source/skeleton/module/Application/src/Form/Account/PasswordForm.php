<?php

namespace Hk4w\Member\Form\Account;

//use Hk4w\Base\Form\Form;

class PasswordForm extends Form
{
    protected $translatePrefix = 'member_account_password_form';

    public function init()
    {
        //  Old Password
        $this->add(array(
            'name' => 'old_password',
            'type' => 'Password',
            'attributes' => array(
                'placeholder' => $this->getTranslatePrefix() . 'old_password_placeholder',
                'title' => $this->getTranslatePrefix() . 'old_password_title',
            ),
            'options' => array(
                'label' => $this->getTranslatePrefix() . 'old_password_label',
            ),
        ));

        //  New Password
        $this->add(array(
            'name' => 'new_password',
            'type' => 'Password',
            'attributes' => array(
                'placeholder' => $this->getTranslatePrefix() . 'new_password_placeholder',
                'title' => $this->getTranslatePrefix() . 'new_password_title',
            ),
            'options' => array(
                'label' => $this->getTranslatePrefix() . 'new_password_label',
            ),
        ));

        //  Comfirm Password
        $this->add(array(
            'name' => 'confirm_password',
            'type' => 'Password',
            'attributes' => array(
                'placeholder' => $this->getTranslatePrefix() . 'confirm_password_placeholder',
                'title' => $this->getTranslatePrefix() . 'confirm_password_title',
            ),
            'options' => array(
                'label' => $this->getTranslatePrefix() . 'confirm_password_label',
            ),
        ));

        //  Submit
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'class' => 'btn-primary',
                'title' => $this->getTranslatePrefix() . 'submit_title',
            ),
            'options' => array(
                'label' => $this->getTranslatePrefix() . 'submit_label',
            ),
        ));
    }
}