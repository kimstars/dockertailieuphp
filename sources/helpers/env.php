<?php
function env($variable, $default = '')
{
    return isset($_ENV[$variable]) ? $_ENV[$variable] : $default;
}
