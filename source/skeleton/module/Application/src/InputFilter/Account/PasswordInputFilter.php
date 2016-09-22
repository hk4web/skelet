<?php

namespace Hk4w\Member\InputFilter\Account;

use Hk4w\Base\InputFilter\EntityInputFilter;
use Zend\InputFilter\InputFilter;

class PasswordInputFilter extends EntityInputFilter
{
    protected $translatePrefix = 'member_account_password_validator';

    public function __construct()
    {
        $inputFilter = new InputFilter();

        //  Old Password
        $inputFilter->add(array(
            'name' => 'old_password',
            'required' => true,
            'filters' => array(
                array('name' => 'string_trim'),
                array('name' => 'strip_tags'),
            ),
            'validators' => array(
                array(
                    'name' => 'not_empty',
                    'break_chain_on_failure' => true,
                    'options' => array(
                        'messages' => array(
                            \Zend\Validator\NotEmpty::INVALID => $this->getTranslatePrefix() . 'old_password_invalid',
                            \Zend\Validator\NotEmpty::IS_EMPTY => $this->getTranslatePrefix() . 'old_password_is_empty',
                        ),
                    ),
                ),
            ),
        ));

        //  New Password
        $inputFilter->add(array(
            'name' => 'new_password',
            'required' => true,
            'filters' => array(
                array('name' => 'string_trim'),
                array('name' => 'strip_tags'),
            ),
            'validators' => array(
                array(
                    'name' => 'not_empty',
                    'break_chain_on_failure' => true,
                    'options' => array(
                        'messages' => array(
                            \Zend\Validator\NotEmpty::INVALID => $this->getTranslatePrefix() . 'new_password_invalid',
                            \Zend\Validator\NotEmpty::IS_EMPTY => $this->getTranslatePrefix() . 'new_password_is_empty',
                        ),
                    ),
                ),
            ),
        ));

        //  Confirm Password
        $inputFilter->add(array(
            'name' => 'confirm_password',
            'required' => true,
            'filters' => array(
                array('name' => 'string_trim'),
                array('name' => 'strip_tags'),
            ),
            'validators' => array(
                array(
                    'name' => 'not_empty',
                    'break_chain_on_failure' => true,
                    'options' => array(
                        'messages' => array(
                            \Zend\Validator\NotEmpty::INVALID => $this->getTranslatePrefix() . 'confirm_password_invalid',
                            \Zend\Validator\NotEmpty::IS_EMPTY => $this->getTranslatePrefix() . 'confirm_password_is_empty',
                        ),
                    ),
                ),
            ),
        ));

        $this->inputFilter = $inputFilter;
    }
}