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
        /* Hide any dropdown when user clicks anywhere */
        if (!event.target.matches('.dropdown-title')) {
            $('.dropdown-content').removeClass('active');
        }
    });
};

export const dropdownItemOnClick = (element) => {
    $(element).click(() => {
        const dropdownContent = $(element).find('.dropdown-content');
        $('.dropdown-content').not(dropdownContent).removeClass('active');
        dropdownContent.toggleClass('active');
    });
};