{styles}
	{style src="addons/responsive_admin/ui/jqueryui.css"}
	{style src="addons/responsive_admin/lib/select2/select2.min.css"}
    {hook name="index:styles"}
        {style src="addons/responsive_admin/styles.less"}
        {style src="addons/responsive_admin/glyphs.css"}

        {include file="views/statuses/components/styles.tpl" type=$smarty.const.STATUSES_ORDER}

        {if $language_direction == 'rtl'}
            {style src="addons/responsive_admin/rtl.less"}
        {/if}
    {/hook}
    {style src="addons/responsive_admin/font-awesome.css"}
{/styles}