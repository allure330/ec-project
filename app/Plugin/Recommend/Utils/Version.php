<?php
/*
 * This file is part of the Recommend plugin
 *
 * Copyright (C) 2016 LOCKON CO.,LTD. All Rights Reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\Recommend\Utils;

use Eccube\Common\Constant;

/**
 * Class Version.
 * Util to check version
 */
class Version
{
    /**
     * Check version to support get instance function. (monolog, new style, ...)
     *
     * @return bool
     */
    public static function isSupportGetInstanceFunction()
    {
        return version_compare(Constant::VERSION, '3.0.9', '>=');
    }

    /**
     * Check version to support new log function.
     *
     * @return bool
     */
    public static function isSupportLogFunction()
    {
        return version_compare(Constant::VERSION, '3.0.12', '>=');
    }
}
