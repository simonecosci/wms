/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

(function (jQuery) {
    // jQuery addons
    // Serialize form fields recursively into json type object, 
    // so for example <input type="text" name="record[type][x]" value="1"> becomes {type: {x: 1}}

    var
            rbracket = /\[\]$/,
            rarrayKeys = /\[(.*?)\]/g,
            rarrayKeysPrefix = /^(.*?)\[.*/g,
            rCRLF = /\r?\n/g,
            rsubmitterTypes = /^(?:submit|button|image|reset|file)$/i,
            rsubmittable = /^(?:input|select|textarea|keygen)/i,
            rcheckableType = /^(?:checkbox|radio)$/i;

    jQuery.fn.extend({
        serializeObject: function () {
            var items = this.map(function () {

                // Can add propHook for "elements" to filter or add form elements
                var elements = jQuery.prop(this, "elements");
                return elements ? jQuery.makeArray(elements) : this;
            })
                    .filter(function () {
                        var type = this.type;

                        // Use .is( ":disabled" ) so that fieldset[disabled] works
                        return this.name && !jQuery(this).is(":disabled") &&
                                rsubmittable.test(this.nodeName) && !rsubmitterTypes.test(type) &&
                                (this.checked || !rcheckableType.test(type));
                    });

            var keysToArray = function (key, val, match, result) {
                if (match == null) {
                    return result;
                }

                var
                        this_key = match[1],
                        next_match = rarrayKeys.exec(key),
                        next_key = (next_match != null ? next_match[1] : null);

                if (next_match == null) {
                    if (this_key == '') {
                        result.push(val);
                    } else {
                        result[this_key] = val;
                    }

                    return result;
                }

                // Do this again
                if (typeof result[this_key] == 'undefined') {
                    result[this_key] = (next_key == '' ? [] : {});
                }
                keysToArray(key, val, next_match, result[this_key]);

                // Return result
                return result;
            };

            var elementArrayToObject = function (array) {
                var result = {};

                array.each(function (i, elem) {
                    var val = jQuery(this).val();

                    if (val == null) {
                        return;
                    }

                    if (jQuery.isArray(val)) {
                        result[elem.name] = elementArrayToObject(val);
                    }

                    var match = rarrayKeys.exec(elem.name);
                    if (match == null) {
                        result[elem.name] = val.replace(rCRLF, "\r\n");
                    } else {
                        var prefix = elem.name.replace(rarrayKeysPrefix, '$1');
                        if (typeof result[prefix] == 'undefined') {
                            result[prefix] = {};
                        }

                        keysToArray(elem.name, val.replace(rCRLF, "\r\n"), match, result[prefix]);
                    }
                });

                return result;
            };

            return elementArrayToObject(items);
        }
    });
})(jQuery);
