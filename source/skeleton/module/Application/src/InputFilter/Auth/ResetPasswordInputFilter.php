<?php

namespace Hk4w\Member\InputFilter\Auth;

use Hk4w\Base\InputFilter\EntityInputFilter;
use Zend\InputFilter\InputFilter;

class ResetPasswordInputFilter extends EntityInputFilter
{
    protected $translatePrefix = 'member_auth_reset_password_validator';

    public function __construct()
    {
        $inputFilter = new InputFilter();

        //  Password
        $inputFilter->add(array(
            'name' => 'password',
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
                            \Zend\Validator\NotEmpty::INVALID => $this->getTranslatePrefix() . 'password_invalid',
                            \Zend\Validator\NotEmpty::IS_EMPTY => $this->getTranslatePrefix() . 'password_is_empty',
                        ),
                    ),
                ),
                array(
                    'name' => 'Hk4w\Base\Validator\RegexCount',
                    'break_chain_on_failure' => true,
                    'options' => array(
                        'pattern' => '/[a-z]/',
                        'min' => 1,
                        'messages' => array(
                            \Hk4w\Base\Validator\RegexCount::TOO_SHORT => $this->getTranslatePrefix() . 'password_to_few_lowercase',
                        ),
                    ),
                ),
                array(
                    'name' => 'Hk4w\Base\Validator\RegexCount',
                    'break_chain_on_failure' => true,
                    'options' => array(
                        'pattern' => '/[A-Z]/',
                        'min' => 1,
                        'messages' => array(
                            \Hk4w\Base\Validator\RegexCount::TOO_SHORT => $this->getTranslatePrefix() . 'password_to_few_uppercase',
                        ),
                    ),
                ),
                array(
                    'name' => 'Hk4w\Base\Validator\RegexCount',
                    'break_chain_on_failure' => true,
                    'options' => array(
                        'pattern' => '/[0-9]/',
                        'min' => 1,
                        'messages' => array(
                            \Hk4w\Base\Validator\RegexCount::TOO_SHORT => $this->getTranslatePrefix() . 'password_to_few_digit',
                        ),
                    ),
                ),
                array(
                    'name' => 'Hk4w\Base\Validator\RegexCount',
                    'break_chain_on_failure' => true,
                    'options' => array(
                        'pattern' => '/[^a-zA-Z0-9]/',
                        'min' => 0,
                        'messages' => array(
                            \Hk4w\Base\Validator\RegexCount::TOO_SHORT => $this->getTranslatePrefix() . 'password_to_few_special',
                        ),
                    ),
                ),
            ),
        ));

        //  Password Confirmation
        $inputFilter->add(array(
            'name' => 'password_confirm',
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
                            \Zend\Validator\NotEmpty::INVALID => $this->getTranslatePrefix() . 'password_confirm_invalid',
                            \Zend\Validator\NotEmpty::IS_EMPTY => $this->getTranslatePrefix() . 'password_confirm_is_empty',
                        ),
                    ),
                ),
                array(
                    'name' => 'identical',
                    'break_chain_on_failure' => true,
                    'options' => array(
                        'token' => 'password',
                        'messages' => array(
                            \Zend\Validator\Identical::MISSING_TOKEN => $this->getTranslatePrefix() . 'password_confirm_missing_token',
                            \Zend\Validator\Identical::NOT_SAME => $this->getTranslatePrefix() . 'password_confirm_not_same',
                        ),
                    ),
                ),
            ),
        ));

        $this->inputFilter = $inputFilter;
    }
}