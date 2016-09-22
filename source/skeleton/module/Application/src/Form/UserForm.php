<?php

namespace Hk4w\Member\Form;

//use Hk4w\Base\Form\Form;

class UserForm extends Form
{
    protected $translatePrefix = 'member_user_form';
    protected $groups = array();
    protected $sites  = array();

    public function init()
    {
        //  Username
        $this->add(array(
            'name' => 'username',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'focus',
                'placeholder' => $this->getTranslatePrefix() . 'username_placeholder',
                'title' => $this->getTranslatePrefix() . 'username_title',
            ),
            'options' => array(
                'label' => $this->getTranslatePrefix() . 'username_label',
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

        //  Group
        $this->add(array(
            'name' => 'group',
            'type' => 'Select',
            'options' => array(
                'label' => $this->getTranslatePrefix() . 'group_label',
                'empty_option' => $this->getTranslatePrefix() . 'group_empty_option',
            ),
        ));

        //  Site
        $this->add(array(
            'name' => 'site',
            'type' => 'Select',
            'options' => array(
                'label' => $this->getTranslatePrefix() . 'site_label',
                'disable_inarray_validator' => true,
                'empty_option' => $this->getTranslatePrefix() . 'site_empty_option',
            ),
        ));

        //  Token
        $this->add(array(
            'name' => 'token',
            'type' => 'Csrf',
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

        //  Cancel
        $this->add(array(
            'name' => 'cancel',
            'type' => 'Button',
            'attributes' => array(
                'class' => 'btn-default go-to-previous',
                'title' => $this->getTranslatePrefix() . 'cancel_title',
            ),
            'options' => array(
                'label' => $this->getTranslatePrefix() . 'cancel_label',
            ),
        ));
    }

    public function getGroups()
    {
        return $this->groups;
    }

    public function setGroups($groups)
    {
        $groupsArray = array();
        foreach ($groups as $group) {
            if ($group instanceof \Hk4w\Member\Entity\Group) {
                $groupsArray[$group->getId()] = $group->getName();
            }
        }

        if ($groupsArray) {
            $groups = $groupsArray;
        }

        //  Update parent element
        if ($this->has('group')) {
            $this->get('group')->setValueOptions($groups);
        }

        $this->groups = $groups;

        return $this;
    }

    public function getSites()
    {
        return $this->sites;
    }

    public function setSites($sites)
    {
        $sitesArray = array();
        foreach ($sites as $site) {
            if ($site instanceof \Hk4w\Member\Entity\Site) {
                $sitesArray[$site->getId()] = $site->getName();
            }
        }

        if ($sitesArray) {
            $sites = $sitesArray;
        }

        //  Update parent element
        if ($this->has('site')) {
            $this->get('site')->setValueOptions($sites);
        }

        $this->sites = $sites;

        return $this;
    }
}