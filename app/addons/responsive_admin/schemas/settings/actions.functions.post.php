<?php
/***************************************************************************
*                                                                          *
*   (c) 2004 Vladimir V. Kalynyak, Alexey V. Vinokurov, Ilya M. Shalnev    *
*                                                                          *
* This  is  commercial  software,  only  users  who have purchased a valid *
* license  and  accept  to the terms of the  License Agreement can install *
* and use this program.                                                    *
*                                                                          *
****************************************************************************
* PLEASE READ THE FULL TEXT  OF THE SOFTWARE  LICENSE   AGREEMENT  IN  THE *
* "copyright.txt" FILE PROVIDED WITH THIS DISTRIBUTION PACKAGE.            *
****************************************************************************/

use Tygh\Http;
use Tygh\Registry;

/**
 * Clear cache and reload addons.manage page
 */
function fn_settings_actions_addons_responsive_admin(&$new_value, $old_value)
{

    fn_clear_cache();
    fn_clear_cache('static');
    fn_clear_template_cache();

    Tygh::$app['ajax']->assign('force_redirection', fn_url('addons.manage'));

    return true;
}