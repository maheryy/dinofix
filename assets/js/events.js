import $ from 'jquery';
import * as Helper from './helpers';

/*
    HTML :
        - data-event="myFunction" | the exact function name declared in events.js file
        - data-json='{"json_formatted_array": true}' | json encoded array (via php)

    Javascript : Declare below the event name that matches data-event attributes
        ex : export const myFunction = (element, data) {
            console.log(element, data);
        };
 */

export const test = (element) => {
    Helper.log(element);
};

export const btn = (element, data) => {
    $(element).click((e) => {
        e.preventDefault();
        console.log($(element).text(), data);
    });
};

export const globalAnywhereClickEvent = () => {
    $(document).click((event) => {
        const dropdowns = $('.dropdown-content');
        /* Close dropdowns when user clicks outside a dropdown item */
        if (dropdowns.hasClass('active') && !$(event.target).closest('.dropdown-item').length) {
            dropdowns.removeClass('active');
        }
    });
};

export const dropdownItemOnClick = (element) => {
    $(element).find('.dropdown-title').click((e) => {
        e.preventDefault();
        const dropdown = $(element).find('.dropdown-content');
        $('.dropdown-content').not(dropdown).removeClass('active');
        dropdown.toggleClass('active');
    });
};

export const submitOnSelectedFilter = (element) => {
    $(element).change(() => {
        $(element).closest('form').submit();
    });
};

export const checkableItems = (element) => {
    let checkAll = $(element).find('input.check-all');
    let checkboxes = $(element).find("input[type='checkbox']").not(checkAll);
    const countCheckboxes = checkboxes.length;

    if (checkboxes.filter(':checked').length === countCheckboxes && !checkAll.is(':checked')) {
        checkAll.prop('checked', true);
    }

    checkAll.click(() => {
        checkboxes.prop('checked', checkAll.is(':checked'));
    });

    checkboxes.click(function () {
        const checkAllIsChecked = checkAll.is(':checked');
        const allCheckboxesAreChecked = checkboxes.filter(':checked').length === countCheckboxes;

        if (!allCheckboxesAreChecked && checkAllIsChecked) {
            checkAll.prop('checked', false);
        } else if (allCheckboxesAreChecked && !checkAllIsChecked) {
            checkAll.prop('checked', true);
        }
    });
};

export const clearInput = (element, data) => {
    const input = $(`#${data.target}`);
    if (input.length) {
        $(element).click(() => input.val('').focus());
    }
};

export const showRemainingItems = (element, data) => {
    const list = $(data.target);

    $(element).click(() => {
        if (list.hasClass('minimized')) {
            list.removeClass('minimized');
            $(element).text('Afficher moins');
        } else {
            list.addClass('minimized');
            $(element).text('Afficher plus');
        }
    });
}
