<?php

namespace Hk4w\Member\Form\Auth;

//use Hk4w\Base\Form\Form;

class LoginForm extends Form
{
    protected $translatePrefix = 'member_auth_login_form';

    public function init()
    {
        //  Username
        $this->add(array(
            'name' => 'username',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'focus first-element',
                'placeholder' => $this->getTranslatePrefix() . 'username_placeholder',
                'title' => $this->getTranslatePrefix() . 'username_title',
            ),
            'options' => array(
                'label' => $this->getTranslatePrefix() . 'username_label',
                'label_attributes' => array(
                    'class'  => 'sr-only',
                ),
            ),
        ));

        //  Password
        $this->add(array(
            'name' => 'password',
            'type' => 'Password',
            'attributes' => array(
                'class' => 'last-element',
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

        //  Token
        $this->add(array(
            'name' => 'token',
            'type' => 'Csrf',
            'options' => array(
                'csrf_options' => array(
                    'timeout' => 600,
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