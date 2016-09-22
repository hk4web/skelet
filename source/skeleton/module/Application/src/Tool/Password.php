<?php

namespace Hk4w\Member\Tool;

use Hk4w\Member\Exception\Exception;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

class Password implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    public function hash($password)
    {
        return password_hash($password, PASSWORD_BCRYPT, array(
            'cost' => 10,
        ));
    }

    public function verify($password, $hash)
    {
        return password_verify($password, $hash);
    }

    public function isStrong($password)
    {
        $config = $this->getConfig();

        foreach ($config['char_group'] as $key => $value) {
            if (!isset($config['char_min'][$key]) || !is_int($config['char_min'][$key])) {
                continue;
            }

            $count = 0;
            foreach (count_chars($password, 1) as $char => $nb) {
                if (in_array(chr($char), str_split($value))) {
                    $count += $nb;
                }
            }

            if ($count < $config['char_min'][$key]) {
                return false;
            }
        }

        return true;
    }

    public function generate()
    {
        $config = $this->getConfig();

        if (!isset($config['char_group']) || !is_array($config['char_group'])) {
            throw new Exception("Invalid password configuration : Index 'member/password/char_group' must be an array");
        }

        $ambiguousChars = (isset($config['ambiguous_chars']) && is_string($config['ambiguous_chars'])) ? $config['ambiguous_chars'] : '';

        $password = '';
        $availableChars = '';
        foreach ($config['char_group'] as $key => $value) {
            $value = str_replace(str_split($ambiguousChars), array(), $value);
            if (isset($config['char_min'][$key]) && is_int($config['char_min'][$key])) {
                $min = $config['char_min'][$key];
                $availableChars .= $value;
            } else {
                $min = 0;
            }

            for ($i=0; $i<$min; $i++) {
                $password .= substr(str_shuffle($value), 0, 1);
            }
        }

        //  Completion
        while (strlen($password) < $config['length']['default']) {
            $password .= substr(str_shuffle($availableChars), 0, 1);
        }

        return str_shuffle($password);
    }

    protected function getConfig()
    {
        $config = $this->getServiceLocator()->get('Config');
        $passwordConfig = (isset($config['member']['password']) && is_array($config['member']['password']))
            ? $config['member']['password'] : array();

        return $passwordConfig ?: array(
            'char_min' => array(
                'lowercase' => 1,
                'uppercase' => 1,
                'digit'     => 2,
                'special'   => 1,
            ),
            'length' => array(
                'default'   => 10,
                'min'       => 6,
                'max'       => 20,
            ),
            'char_group' => array(
                'lowercase' => 'abcdefghijklmnopqrstuvwxyz',
                'uppercase' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
                'digit'     => '0123456789',
                'special'   => '~!@#$%^&*()-_=+[]{};:,.<>/?'
            ),
            'default_duration' => 60*60*24*365, //  1 Year
            'ambiguous_chars' => '0O1lI',
        );
    }
}