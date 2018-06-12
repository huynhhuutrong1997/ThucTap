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

use Tygh\Addons\Gdpr\SchemaManager;

defined('BOOTSTRAP') or die('Access denied');

$is_gdpr_addon = !empty($_REQUEST['addon']) && $_REQUEST['addon'] == 'gdpr';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if ($mode == 'update') {

        if ($mode == 'update'
            && $is_gdpr_addon
            && !empty($_REQUEST['setting_id'])
            && !empty($_REQUEST['gdpr_settings'])
        ) {
            fn_gdpr_update_settings($_REQUEST['setting_id'], $_REQUEST['gdpr_settings']);
        }
    }

    return array(CONTROLLER_STATUS_OK);
}

if ($mode == 'update') {
    if ($is_gdpr_addon) {
        /** @var SchemaManager $schema_manager */
        $schema_manager = Tygh::$app['addons.gdpr.schema_manager'];
        $gdpr_settings = $schema_manager->getSchema('settings');

        $options = (array) Tygh::$app['view']->getTemplateVars('options');
        $settings = fn_gdpr_get_setting_data('gdpr_settings_data', 'gdpr', $options);
        $setting_id = isset($settings['object_id']) ? $settings['object_id'] : null;
        $saved_settings = isset($settings['value']) ? json_decode($settings['value'], true) : array();

        Tygh::$app['view']->assign(array(
            'gdpr_settings'  => $gdpr_settings,
            'saved_settings' => $saved_settings,
            'setting_id'     => $setting_id,
        ));
    }
}
