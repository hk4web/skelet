<?php

namespace Hk4w\Member\Form\Account;

//use Docapost\Base\Form\Form;

class ProfileForm extends Form
{
    protected $translatePrefix = 'member_account_profile_form';
    protected $sites           = array();

    public function init()
    {
        //  Firstname
        $this->add(array(
            'name' => 'firstname',
            'type' => 'Text',
            'attributes' => array(
                'placeholder' => $this->getTranslatePrefix() . 'firstname_placeholder',
                'title' => $this->getTranslatePrefix() . 'firstname_title',
            ),
            'options' => array(
                'label' => $this->getTranslatePrefix() . 'firstname_label',
            ),
        ));

        //  Lastname
        $this->add(array(
            'name' => 'lastname',
            'type' => 'Text',
            'attributes' => array(
                'placeholder' => $this->getTranslatePrefix() . 'lastname_placeholder',
                'title' => $this->getTranslatePrefix() . 'lastname_title',
            ),
            'options' => array(
                'label' => $this->getTranslatePrefix() . 'lastname_label',
            ),
        ));

        //  Email
        $this->add(array(
            'name' => 'email',
            'type' => 'Text',
            'attributes' => array(
                'placeholder' => $this->getTranslatePrefix() . 'email_placeholder',
                'title' => $this->getTranslatePrefix() . 'email_title',
            ),
            'options' => array(
                'label' => $this->getTranslatePrefix() . 'email_label',
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