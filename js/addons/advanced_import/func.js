(function(_, $) {

    var preset_id, object_type,
        company_selector, file_type_selector,
        file_selector, preset_name_selector;

    var methods = {
        init_preset_editor: function() {

            $.ceEvent('on', 'ce.fileuploader.display_filename', function(id, file_type, file) {
                if (file_type === 'server' || file_type === 'url') {
                    file = $('#file_' + id).val();
                    $.ceAdvancedImport('get_fields', file_type, file);
                    $('#advanced_import_save_and_import, li#fields').removeClass('hidden');
                } else if (file_type === 'local') {
                    $('#advanced_import_save_and_import, li#fields').addClass('hidden');
                }

                var re = /\.([^.]+)?$/,
                    file_extension = re.exec(file),
                    $xml_target_node_wrapper = $('#target_node').closest('.control-group');

                if (!file_extension || file_extension[1] !== 'xml') {
                    $xml_target_node_wrapper.hide();
                } else {
                    $xml_target_node_wrapper.show();
                }

                if (!preset_name_selector.val()) {
                    preset_name_selector.val(file)
                }
            });
        },

        get_fields: function(file_type, file, options) {

            file_type = file_type || file_type_selector.val();
            file = file || file_selector.val();

            var company_id = company_selector.val();

            var data = {
                preset_id: preset_id,
                object_type: object_type,
                company_id: company_id
            };
            if (file_type) {
                data.file_type = file_type;
            }
            if (file) {
                data.file = file;
            }

            $.ceAjax('request', fn_url('import_presets.get_fields'), {
                result_ids: 'content_fields',
                caching: false,
                data: data
            });
        },

        init_related_object_selectors: function(selectors) {
            selectors.change(function() {
                var type_holder = $('#elm_field_related_object_type_' + $(this).data('caAdvancedImportFieldId'));
                var type = $('option:selected', $(this)).data('caAdvancedImportFieldRelatedObjectType');
                type_holder.val(type);
            });

            selectors.each(function() {
                $(this).trigger('change');
            });
        },

        show_fields_preview: function(opener) {
            var params = $.ceDialog('get_params', opener);
            $('#' + opener.data('caTargetId')).ceDialog('open', params);
            if (window.history.replaceState) {
                window.history.replaceState({}, '', _.current_url.replace(/&preview_preset_id=\d+/, ''));
            }
        },

        // FIXME: Dirty hack
        // Pop-up with the fields mapping is destroyed before a Comet request is sent,
        // so fields must be manually transfered to the parent form.
        set_fields_for_import: function(preset_id) {
            var form = $('[data-ca-advanced-import-element="management_form"]');

            form.append('<input type="hidden" name="preset_id" value="' + preset_id + '" />');
            form.append('<input type="hidden" name="dispatch[advanced_import.import]" value="OK" />');

            var fields = form.serializeArray();

            for (var i in fields) {
                var field = fields[i];
                if (/^fields\[/.test(field.name)) {
                    form.append($('<input>', {
                        type: "hidden",
                        name: field.name,
                        value: field.value
                    }));
                }
            }
        },

        change_company_id: function() {
            $.ceAdvancedImport('get_fields');
            $.ceAdvancedImport('get_images_prefix_path');
        },

        get_images_prefix_path: function() {
            var $elem = $('#advanced_import_images_path_prefix'),
                companies_image_directories = $elem.data('companiesImageDirectories'),
                company_id = company_selector.val();

            if ('relative_path' in companies_image_directories[company_id]) {
                $elem.text(companies_image_directories[company_id].relative_path);
            }
        }
    };

    $.extend({
        ceAdvancedImport: function(method) {
            if (methods[method]) {
                return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
            } else {
                $.error('ty.advancedImport: method ' + method + ' does not exist');
            }
        }
    });

    $.ceEvent('on', 'ce.commoninit', function(context) {
        var preset = $('[data-ca-advanced-import-element="editor"]', context);

        if (preset.length) {
            preset_id = preset.data('caAdvancedImportPresetId');
            object_type = preset.data('caAdvancedImportPresetObjectType');
            company_selector = $('#elm_company_id', context);
            file_type_selector = $('[name="type_upload[]"]');
            file_selector = $('[name="file_upload[]"]');
            preset_name_selector = $('#elm_preset', context);

            $.ceAdvancedImport('init_preset_editor');
        }

        var related_object_selectors = $('[data-ca-advanced-import-field-related-object-selector]', context);

        if (related_object_selectors.length) {
            $.ceAdvancedImport('init_related_object_selectors', related_object_selectors);
        }

        var fields_preview_opener = $('.import-preset__preview-fields-mapping', context);

        if (fields_preview_opener.length) {
            $.ceAdvancedImport('show_fields_preview', fields_preview_opener);
        }

        var import_start_button = $('.cm-advanced-import-start-import', context);

        if (import_start_button.length) {
            import_start_button.click();
        }
    });

    $(document).ready(function() {
        $('.advanced-import-file-editor-opener').on('click', function (e) {
            var $target = $(e.target),
                option_id = $target.data('targetInputId'),
                company_id = $target.closest('form').find('#elm_company_id').val(),
                relative_path = $('#' + option_id).val(),
                url = fn_url('import_presets.file_manager&path=' + relative_path + '&company_id=' + company_id),
                $finder_dialog = $('#' + option_id + '_dialog');

            $finder_dialog.ceDialog('destroy');
            $finder_dialog.empty();

            $finder_dialog.ceDialog('open', {
                'href': url,
                'title': _.tr('file_editor')
            });

            return false;
        });
    });
})(Tygh, Tygh.$);