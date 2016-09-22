<?php

namespace Hk4w\Member\InputFilter;

use Hk4w\Base\InputFilter\EntityInputFilter;
use Zend\InputFilter\InputFilter;

class UserInputFilter extends EntityInputFilter
{
    protected $translatePrefix = 'member_user_validator';

    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);

        $inputFilter = new InputFilter();

        //  Username
        $inputFilter->add(array(
            'name' => 'username',
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
                            \Zend\Validator\NotEmpty::INVALID => $this->getTranslatePrefix() . 'username_invalid',
                            \Zend\Validator\NotEmpty::IS_EMPTY => $this->getTranslatePrefix() . 'username_is_empty',
                        ),
                    ),
                ),
                array(
                    'name' => 'string_length',
                    'options' => array(
                        'min' => 3,
                        'max' => 25,
                        'messages' => array(
                            \Zend\Validator\StringLength::INVALID => $this->getTranslatePrefix() . 'username_invalid',
                            \Zend\Validator\StringLength::TOO_LONG => $this->getTranslatePrefix() . 'username_too_long',
                            \Zend\Validator\StringLength::TOO_SHORT => $this->getTranslatePrefix() . 'username_too_short',
                        ),
                    ),
                ),
            ),
        ));

        //  Email
        $inputFilter->add(array(
            'name' => 'email',
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
                            \Zend\Validator\NotEmpty::INVALID => $this->getTranslatePrefix() . 'email_invalid',
                            \Zend\Validator\NotEmpty::IS_EMPTY => $this->getTranslatePrefix() . 'email_is_empty',
                        ),
                    ),
                ),
                array(
                    'name' => 'email_address',
                    'options' => array(
                        'messages' => array(
                            \Zend\Validator\EmailAddress::INVALID => $this->getTranslatePrefix() . 'email_invalid',
                            \Zend\Validator\EmailAddress::INVALID_FORMAT => $this->getTranslatePrefix() . 'email_invalid_format',
                            \Zend\Validator\EmailAddress::INVALID_HOSTNAME => $this->getTranslatePrefix() . 'email_invalid_hostname',
                            \Zend\Validator\EmailAddress::INVALID_MX_RECORD => $this->getTranslatePrefix() . 'email_invalid_mx_record',
                            \Zend\Validator\EmailAddress::INVALID_SEGMENT => $this->getTranslatePrefix() . 'email_invalid_segment',
                            \Zend\Validator\EmailAddress::DOT_ATOM => $this->getTranslatePrefix() . 'email_dot_atom',
                            \Zend\Validator\EmailAddress::QUOTED_STRING => $this->getTranslatePrefix() . 'email_quoted_string',
                            \Zend\Validator\EmailAddress::INVALID_LOCAL_PART => $this->getTranslatePrefix() . 'email_invalid_local_part',
                            \Zend\Validator\EmailAddress::LENGTH_EXCEEDED => $this->getTranslatePrefix() . 'email_length_exceeded',
                        ),
                    ),
                ),
            ),
        ));

        //  Group
        $inputFilter->add(array(
            'name' => 'group',
            'required' => false,
        ));

        //  Site
        $inputFilter->add(array(
            'name' => 'site',
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
                            \Zend\Validator\NotEmpty::INVALID => $this->getTranslatePrefix() . 'site_invalid',
                            \Zend\Validator\NotEmpty::IS_EMPTY => $this->getTranslatePrefix() . 'site_is_empty',
                        ),
                    ),
                ),
            ),
        ));

        $this->inputFilter = $inputFilter;
    }
}