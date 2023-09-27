<?php

namespace fjwccy\content;

interface ContentInterface
{
    public function toArray();
    public function formatData();
    public function validateData();
}
