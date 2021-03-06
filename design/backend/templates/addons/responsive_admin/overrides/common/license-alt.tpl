{if "ULTIMATE"|fn_allowed_for && $store_mode != "ultimate"}
    <div id="restriction_promo_dialog" title="{__("ultimate_license_required", ["[product]" => $smarty.const.PRODUCT_NAME])}" class="hidden cm-dialog-auto-size">
        {__("text_ultimate_license_required.ru", [
            "[product]" => $smarty.const.PRODUCT_NAME,
            "[ultimate_license_url]" => $config.resources.ultimate_license_url
        ])}
        <div class="restriction-features">
            <div class="restriction-feature restriction-feature_storefronts" style="width: 100%">
                <h2>{__("text_license_required_storefronts-title")}</h2>
                {__("text_license_required_storefronts")}
            </div>
        </div>
        <div class="center clear">
            <a class="restriction-update-btn" href="{$config.resources.ultimate_license_url}" target="_blank">{__("upgrade_license")}</a>
        </div>
    </div>
{/if}