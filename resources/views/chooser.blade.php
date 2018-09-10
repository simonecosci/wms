<table class='chooser-container'>
    <tr>
        <td>
            <input class="{{ $field }}_display chooser-display k-textbox" readonly="readonly" onclick="$(this).parent().parent().find('td.chooser-buttons button.k-button.chooser-toggler.{{ $field }}_chooser_toggler').trigger('click');" />
        </td>
        <td class='{{ $field }}-chooser-buttons chooser-buttons'>
            <span class='{{ $field }}_chooser_view_button' data-bind="text: {{ $field }}"></span>
            <button type="button" class="k-button chooser-toggler {{ $field }}_chooser_toggler">...</button>
        </td>
    </tr>
</table>
<input type="hidden" name="{{ $field }}" data-bind="value: {{ $field }}">
<div class="{{ $field }}_chooser chooser-selector k-content" style="display:none;"><div></div></div>