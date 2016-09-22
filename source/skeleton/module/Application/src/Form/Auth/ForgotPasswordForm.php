<?php

namespace Hk4w\Member\Form\Auth;

class ForgotPasswordForm //extends \Docapost\Base\Form\Form
{
    protected $translatePrefix = 'member_auth_forgot_password_form';

    public function init()
    {
        //  Email
        $this->add(array(
            'name' => 'identity',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'focus',
                'placeholder' => $this->getTranslatePrefix() . 'identity_placeholder',
                'title' => $this->getTranslatePrefix() . 'identity_title',
            ),
            'options' => array(
                'label' => $this->getTranslatePrefix() . 'identity_label',
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