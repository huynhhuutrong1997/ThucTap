{capture name="mainbox"}
    {capture name="tabsbox"}

        {$id = $preset.preset_id|default:0}

        <form action="{""|fn_url}"
              method="post"
              name="import_preset_update_form"
              id="import_preset_update_form"
              enctype="multipart/form-data"
              class="form-horizontal form-edit{if $start_import} cm-ajax cm-comet{/if} import-preset-edit"
              data-ca-advanced-import-element="editor"
              data-ca-advanced-import-preset-id="{$id}"
              data-ca-advanced-import-preset-object-type="{$preset.object_type}"
              data-ca-advanced-import-preset-name="{$preset.preset}"
        >

            <input type="hidden" name="preset_id" value="{$id}"/>
            <input type="hidden" name="result_ids" value="content_{$id}"/>
            <input type="hidden" name="object_type" value="{$preset.object_type}"/>
            {if $start_import}
                <input type="hidden" name="return_url" value="{"import_presets.update&preset_id=`$id`"}"/>
            {/if}

            <div id="content_general">

                {include file="common/subheader.tpl"
                         title=__("advanced_import.general_settings")
                         target="#information"
                }

                <div id="information" class="in collapse">

                    <div class="control-group">
                        <input type="hidden"
                               name="file_type"
                               value="{$preset.file_type|default:("Addons\\AdvancedImport\\PresetFileTypes::LOCAL"|enum)}"
                        />
                        <input type="hidden"
                               name="file"
                               value="{$preset.file|default:""}"
                        />
                        <label class="control-label">{__("file")}:</label>
                        <div class="controls import-preset__fileuploader">
                            {include file="addons/advanced_import/views/import_presets/components/fileuploader.tpl"
                                     var_name="upload[]"
                                     prefix=$id
                                     allowed_ext=["csv", "xml"]
                            }
                        </div>
                    </div>

                    <div class="control-group {$preset.options.target_node.control_group_meta}">
                        <label for="target_node" class="control-label">
                            {strip}
                                {__($preset.options.target_node.title)}
                                {if $preset.options.target_node.description}
                                    {include file="common/tooltip.tpl" tooltip=__($preset.options.target_node.description)}
                                {/if}
                                :
                            {/strip}
                        </label>
                        <div class="controls">
                            <input class="input-large"
                                   type="text"
                                   name="options[target_node]"
                                   id="target_node"
                                   size="55"
                                   value="{$preset.options.target_node.selected_value|default:$preset.options.target_node.default_value}"
                            />
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="elm_preset" class="control-label cm-required">{__("name")}:</label>
                        <div class="controls">
                            <input class="input-large"
                                   type="text"
                                   name="preset"
                                   id="elm_preset"
                                   size="55"
                                   value="{$preset.preset}"
                            />
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="images_path" class="control-label">{__("images_directory")}{strip}
                        {include file="common/tooltip.tpl" tooltip=__($preset.options.images_path.description)}
                        :{/strip}</label>
                        <div class="controls">
                            {include file="addons/advanced_import/views/import_presets/components/options/images_path.tpl"
                                option=$preset.options.images_path
                                option_id="images_path"
                                field_name_prefix="options"
                            }
                            <p class="muted">{$preset.options.images_path.notes nofilter}</p>
                        </div>
                    </div>

                    {include file="views/companies/components/company_field.tpl"
                             name="company_id"
                             id="elm_company_id"
                             selected=$preset.company_id
                             js_action="$.ceAdvancedImport('change_company_id');"
                    }
                </div>

                {include file="common/subheader.tpl"
                         title=__("advanced_import.additional_settings")
                         target="#import_file"
                         meta="collapsed"
                }

                <div id="import_file" class="out collapse">

                    <div class="control-group">
                        <label class="control-label">{__("csv_delimiter")}:</label>
                        <div class="controls" data-ca-advanced-import-element="delimiter_container">
                            {include file="views/exim/components/csv_delimiters.tpl"
                                    name="{$field_name_prefix}[delimiter]"
                                    value="{$options.delimiter|default:"S"}"
                            }
                        </div>
                    </div>

                    {include file="addons/advanced_import/views/import_presets/components/options.tpl"
                             options=$preset.options|default:[]
                             field_name_prefix="options"
                             display=true
                             tab="general"
                    }

                    {capture name="buttons"}
                        {if $start_import}
                            {include file="buttons/button.tpl"
                                     but_text=__("import")
                                     but_role="action"
                                     but_id="advanced_import_start_import"
                                     but_meta="cm-submit hidden cm-advanced-import-start-import"
                                     but_target_form="import_preset_update_form"
                                     but_name="dispatch[advanced_import.import]"
                            }
                        {/if}
                        {include file="buttons/button.tpl"
                                 but_text="{__("import")}"
                                 but_role="action"
                                 but_id="advanced_import_save_and_import"
                                 but_name="dispatch[import_presets.update.import]"
                                 but_target_form="import_preset_update_form"
                                 but_meta="cm-submit btn-primary{if !$id} hidden{/if}"
                        }
                        {include file="buttons/button.tpl"
                                 but_text="{if $id}{__("save")}{else}{__("create")}{/if}"
                                 but_role="action"
                                 but_name="dispatch[import_presets.update]"
                                 but_target_form="import_preset_update_form"
                                 but_meta="cm-submit{if !$id} btn-primary{/if}"
                        }
                    {/capture}
                </div>

            <!--content_general--></div>

            <div class="hidden" id="content_fields">
            <!--content_fields--></div>

            <div class="hidden" id="content_options">

                {include file="common/subheader.tpl"
                         title=__("advanced_import.general_settings")
                         target="#settings_general"
                }

                <div id="settings_general" class="out">
                    {include file="addons/advanced_import/views/import_presets/components/options.tpl"
                             options=$preset.options|default:[]
                             field_name_prefix="options"
                             display=true
                             tab="settings"
                             section="general"
                    }
                </div>

                {include file="common/subheader.tpl"
                         title=__("advanced_import.additional_settings")
                         target="#settings_additional"
                         meta="collapsed"
                }

                <div id="settings_additional" class="out collapse">
                    {include file="addons/advanced_import/views/import_presets/components/options.tpl"
                             options=$preset.options|default:[]
                             field_name_prefix="options"
                             display=true
                             tab="settings"
                             section="additional"
                    }
                </div>
            <!--content_options--></div>

        </form>
    {/capture}

    {include file="common/tabsbox.tpl" content=$smarty.capture.tabsbox active_tab="general"}
{/capture}

{capture name="mainbox_title"}
    {if $preset.preset_id|default:0}
        {__("advanced_import.editing_preset", ["[preset]" => $preset.preset])}
    {else}
        {__("advanced_import.new_preset")}
    {/if}
{/capture}

{include file="common/mainbox.tpl"
         title=$smarty.capture.mainbox_title
         content=$smarty.capture.mainbox
         buttons=$smarty.capture.buttons
}