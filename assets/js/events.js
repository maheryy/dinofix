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