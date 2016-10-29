<?php

namespace Docapost\Base\Service;

interface CrudInterface
{
    public function create();
    public function read();
    public function update();
    public function delete();
}