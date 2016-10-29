<?php

namespace Docapost\Base\Tool;

use Docapost\Base\Exception\Exception;

class File
{
    /**
     * Create file
     *
     * @param string $filename
     * @param string $content
     * @param array $options
     * @return int
     * @throws Exception
     */
    public static function create($filename, $content, $options = array())
    {
        //  Options - Throw Exceptions
        $throwExceptions = (isset($options['throw_exceptions']) && $options['throw_exceptions']);
        //  Options - Mode
        if (!isset($options['mode']) || !is_int($options['mode'])) {
            $options['mode'] = 0775;
        }
        //  Options - Create Directory
        if (!isset($options['create_dir']) || empty($options['create_dir'])) {
            $options['create_dir'] = false;
        }
        //  Options - Replace File
        if (!isset($options['replace']) || empty($options['replace'])) {
            $options['replace'] = true;
        }

        //  Repository
        if (!file_exists(dirname($filename)) && $options['create_dir']) {
            umask(0);
            if (!mkdir(dirname($filename), $options['mode'], true)) {
                if ($throwExceptions) {
                    throw new Exception("Unable to create directory '" . dirname($filename) . "'");
                } return false;
            }
        }

        //  File
        if (file_exists($filename) && !$options['replace']) {
            if ($throwExceptions) {
                throw new Exception("File '$filename' already exists");
            } return false;
        }
        if (file_exists($filename) && !is_writable($filename)) {
            if ($throwExceptions) {
                throw new Exception("Unable to replace '$filename'");
            } return false;
        }
        if (false === ($size = file_put_contents($filename, $content))) {
            if ($throwExceptions) {
                throw new Exception("Unable to create '$filename'");
            } return false;
        }

        //  File - Change Mode
        if ($options['mode']) {
            if (!chmod($filename, $options['mode'])) {
                if ($throwExceptions) {
                    throw new Exception("Unable to set mode '" . $options['mode'] . "' on file '$filename'");
                } return false;
            }
        }
        //  File - Change Group
        if (isset($options['group']) && $options['group']) {
            if (!chgrp($filename, $options['group'])) {
                if ($throwExceptions) {
                    throw new Exception("Unable to set group '" . $options['group'] . "' on file '$filename'");
                } return false;
            }
        }
        //  File - Change Owner
        if (isset($options['owner']) && $options['owner']) {
            if (!chown($filename, $options['owner'])) {
                if ($throwExceptions) {
                    throw new Exception("Unable to set owner '" . $options['owner'] . "' on file '$filename'");
                } return false;
            }
        }

        return $size;
    }

    public static function rename($oldname, $newname, $options = array())
    {
        //  Options - Throw Exceptions
        $throwExceptions = (isset($options['throw_exceptions']) && $options['throw_exceptions']);

        //  Options - Mode
        if (!isset($options['mode']) || !is_int($options['mode'])) {
            $options['mode'] = 0775;
        }

        //  Old File
        if (!file_exists($oldname)) {
            if ($throwExceptions) {
                throw new Exception("File '$oldname' not exists");
            } return false;
        }
        //  New File
        if (file_exists($newname) && !is_writable($newname)) {
            if ($throwExceptions) {
                throw new Exception("File '$newname' already exists");
            } return false;
        }

        //  Repository
        if (!isset($options['create_dir']) || empty($options['create_dir'])) {
            $options['create_dir'] = false;
        }
        if (!file_exists(dirname($newname)) && $options['create_dir']) {
            umask(0);
            if (!mkdir(dirname($newname), $options['mode'], true)) {
                if ($throwExceptions) {
                    throw new Exception("Unable to create directory '" . dirname($newname) . "'");
                } return false;
            }
        }

        //  New File - Rename
        if (!rename($oldname, $newname)) {
            if ($throwExceptions) {
                throw new Exception("Unable to move '$oldname' to '$newname'");
            } return false;
        }

        //  New File - Change Mode
        if ($options['mode']) {
            if (!chmod($newname, $options['mode'])) {
                if ($throwExceptions) {
                    throw new Exception("Unable to set mode '" . $options['mode'] . "' on file '$newname'");
                } return false;
            }
        }
        //  New File - Change Group
        if (isset($options['group']) && $options['group']) {
            if (!chgrp($newname, $options['group'])) {
                if ($throwExceptions) {
                    throw new Exception("Unable to set group '" . $options['group'] . "' on file '$newname'");
                } return false;
            }
        }
        //  New File - Change Owner
        if (isset($options['owner']) && $options['owner']) {
            if (!chown($newname, $options['owner'])) {
                if ($throwExceptions) {
                    throw new Exception("Unable to set owner '" . $options['owner'] . "' on file '$newname'");
                } return false;
            }
        }

        return true;
    }

    public static function read($filename)
    {
        //  Options - Throw Exceptions
        $throwExceptions = (isset($options['throw_exceptions']) && $options['throw_exceptions']);

        //  File - Exists
        if (!file_exists($filename)) {
            if ($throwExceptions) {
                throw new Exception("File '$filename' not exists");
            } return false;
        }

        //  File - Content
        if (false === ($content = file_get_contents($filename))) {
            if ($throwExceptions) {
                throw new Exception("Unable to read file '$filename'");
            } return false;
        }

        return $content;
    }
}