<?php

namespace Hk4w\Member\Form\Auth;

class ResetPasswordForm //extends \Docapost\Base\Form\Form
{
    protected $translatePrefix = 'member_auth_reset_password_form';

    public function init()
    {
        //  Password
        $this->add(array(
            'name' => 'password',
            'type' => 'Password',
            'attributes' => array(
                'class' => 'focus first-element',
                'placeholder' => $this->getTranslatePrefix() . 'password_placeholder',
                'title' => $this->getTranslatePrefix() . 'password_title',
            ),
            'options' => array(
                'label' => $this->getTranslatePrefix() . 'password_label',
                'label_attributes' => array(
                    'class'  => 'sr-only',
                ),
            ),
        ));

        //  Password Confirmation
        $this->add(array(
            'name' => 'password_confirm',
            'type' => 'Password',
            'attributes' => array(
                'class' => 'last-element',
                'placeholder' => $this->getTranslatePrefix() . 'password_confirm_placeholder',
                'title' => $this->getTranslatePrefix() . 'password_confirm_title',
            ),
            'options' => array(
                'label' => $this->getTranslatePrefix() . 'password_confirm_label',
                'label_attributes' => array(
                    'class'  => 'sr-only',
                ),
            ),
        ));

        //  Token
        $this->add(array(
            'name' => 'token',
            'type' => 'Csrf',
            'options' => array(
                'csrf_options' => array(
                    'timeout' => 900,
                ),
            ),
        ));

        //  Submit
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'class' => 'btn-primary btn-block btn-lg',
                'title' => $this->getTranslatePrefix() . 'submit_title',
            ),
            'options' => array(
                'label' => $this->getTranslatePrefix() . 'submit_label',
            ),
        ));
    }
}