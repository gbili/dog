<?php
namespace Upload;

interface ConfigKeyAwareInterface
{
    public function getConfigKey();
    public function setConfigKey($key);
}
