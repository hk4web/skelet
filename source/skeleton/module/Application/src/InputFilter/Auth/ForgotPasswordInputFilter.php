<?php

namespace Hk4w\Member\InputFilter\Auth;

use Hk4w\Base\InputFilter\EntityInputFilter;
use Zend\InputFilter\InputFilter;

class ForgotPasswordInputFilter extends EntityInputFilter
{
    protected $translatePrefix = 'member_auth_forgot_password_validator';

    public function __construct()
    {
        $inputFilter = new InputFilter();

        //  Username
        $inputFilter->add(array(
            'name' => 'identity',
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
                            \Zend\Validator\NotEmpty::INVALID => $this->getTranslatePrefix() . 'identity_invalid',
                            \Zend\Validator\NotEmpty::IS_EMPTY => $this->getTranslatePrefix() . 'identity_is_empty',
                        ),
                    ),
                ),
            ),
        ));

        $this->inputFilter = $inputFilter;
    }
}