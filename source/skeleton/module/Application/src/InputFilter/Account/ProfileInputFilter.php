<?php

namespace Hk4w\Member\InputFilter\Account;

use Hk4w\Base\InputFilter\EntityInputFilter;
use Zend\InputFilter\InputFilter;

class ProfileInputFilter extends EntityInputFilter
{
    protected $translatePrefix = 'member_account_profile_validator';

    public function __construct()
    {
        $inputFilter = new InputFilter();

        //  Username
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
                    'name' => 'regex',
                    'break_chain_on_failure' => true,
                    'options' => array(
                        'pattern' => '/@docapost.fr$/',
                        'messages' => array(
                            \Zend\Validator\Regex::INVALID => $this->getTranslatePrefix() . 'email_invalid',
                            \Zend\Validator\Regex::NOT_MATCH => $this->getTranslatePrefix() . 'email_invalid_hostname',
                            \Zend\Validator\Regex::ERROROUS => $this->getTranslatePrefix() . 'email_invalid',
                        ),
                    ),
                ),
                array(
                    'name' => 'email_address',
                    'options' => array(
                        'messages' => array(
                            \Zend\Validator\EmailAddress::DOT_ATOM => $this->getTranslatePrefix() . 'email_dot_atom',
                            \Zend\Validator\EmailAddress::INVALID => $this->getTranslatePrefix() . 'email_invalid',
                            \Zend\Validator\EmailAddress::INVALID_FORMAT => $this->getTranslatePrefix() . 'email_invalid_format',
                            \Zend\Validator\EmailAddress::INVALID_HOSTNAME => $this->getTranslatePrefix() . 'email_invalid_hostname',
                            \Zend\Validator\EmailAddress::INVALID_LOCAL_PART => $this->getTranslatePrefix() . 'email_invalid_local_part',
                            \Zend\Validator\EmailAddress::INVALID_MX_RECORD => $this->getTranslatePrefix() . 'email_invalid_mx_record',
                            \Zend\Validator\EmailAddress::INVALID_SEGMENT => $this->getTranslatePrefix() . 'email_invalid_segment',
                            \Zend\Validator\EmailAddress::LENGTH_EXCEEDED => $this->getTranslatePrefix() . 'email_length_exceeded',
                            \Zend\Validator\EmailAddress::QUOTED_STRING => $this->getTranslatePrefix() . 'email_quoted_string',
                        ),
                    ),
                ),
            ),
        ));

        $this->inputFilter = $inputFilter;
    }
}