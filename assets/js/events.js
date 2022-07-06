import $ from 'jquery';
import * as Helper from './helpers';
import * as ElementBuilder from './elements';

/*
    HTML :
        - data-event="myFunction" | the exact function name declared in events.js file
        - data-json='{"json_formatted_array": true}' | json encoded array (via php)

    Javascript : Declare below the event name that matches data-event attributes
        ex : export const myFunction = (element, data) {
            console.log(element, data);
        };
 */

const AUTOCOMPLETE_DELAY = 200;

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
        /* Close dropdowns when user clicks outside a dropdown item */
        if ($('.dropdown-content').hasClass('active') && !$(event.target).closest('.dropdown-item').length) {
            $('.dropdown-content').removeClass('active');
        }

        /* Close autocomplete dropdown when user clicks outside an autocomplete item */
        if ($('.autocomplete-dropdown').hasClass('active') && !$(event.target).closest('.autocomplete-wrapper').length) {
            $('.autocomplete-dropdown').removeClass('active');
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

export const clearInput = (element) => {
    const input = $($(element).data('target'));
    if (!input.length) {
        return console.error(`function clearInput: target not found (${$(element).data('target')})`)
    }

    $(element).click(() => input.val('').focus());
};

export const clearAutocomplete = (element) => {
    const inputs = $(element).data('target');

    $(element).click(() => inputs.forEach(el => {
        $(el).val('');
        $(element).closest('.autocomplete-wrapper').find('.autocomplete-dropdown').remove();
    }));

};

export const showRemainingItems = (element) => {
    const list = $($(element).data('target'));
    if (!list.length) {
        return console.error(`function showRemainingItems: target not found (${$(element).data('target')})`)
    }

    $(element).click(() => {
        if (list.hasClass('minimized')) {
            list.removeClass('minimized');
            $(element).text('Afficher moins');
        } else {
            list.addClass('minimized');
            $(element).text('Afficher plus');
        }
    });
};

export const submitFromOutside = (element) => {
    const form = $($(element).data('target'));
    if (!form.length) {
        return console.error(`function showRemainingItems: target not found (${$(element).data('target')})`)
    }

    $(element).click(() => form.submit());
};

export const stepManagerHandler = () => {
    const list = $('#draggable-step-list');

    const dragStart = function (e) {
        $(this).addClass('dragging');
    };

    const dragEnd = function (e) {
        $(this).removeClass('dragging');
    };

    const dragOver = function (e) {
        e.preventDefault();
        const container = $('#draggable-step-list');
        const afterElement = container
            .find('li.step-item:not(.dragging)')
            .toArray()
            .reduce(function (closest, child) {
                const box = child.getBoundingClientRect();
                const offsetX = e.clientX - box.left - box.width / 2;
                const offsetY = e.clientY - box.top - box.height / 2;

                return offsetY < 0 && offsetY > closest.offsetY
                    ? {offsetY, offsetX, element: child}
                    : closest;

            }, {offsetX: Number.NEGATIVE_INFINITY, offsetY: Number.NEGATIVE_INFINITY}).element;

        afterElement ? $(afterElement).before($('.dragging')) : container.append($('.dragging'));
        refreshSteps();
    };

    const setEvents = (item) => {
        $(item)
            .on('dragstart', dragStart)
            .on('dragover', dragOver)
            .on('dragend', dragEnd)
            .find('.remove')
            .click(removeStep);
    };

    const removeStep = function () {
        $(this).unbind().closest('li.step-item').remove();
        refreshSteps();
    };

    const addStep = (name, description, notify) => {
        if (name === '' || nameExists(name)) {
            return false;
        }

        const element = ElementBuilder.draggableStep(name, description, notify);
        setEvents(element);
        element.insertBefore('#draggable-step-list li:last-child');
        refreshSteps()
    };

    const refreshSteps = () => {
        list.find('li.step-item').each((index, item) => {
            $(item).find('.step .label').text(index + 1);
        });
    };

    const nameExists = name => $('li.step-item .item-step-name').map((i, el) => $(el).val().toLowerCase()).toArray().includes(name.toLowerCase());

    $('#entry-form').submit(e => {
        e.preventDefault();
        addStep($('#step-name').val().trim(), $('#step-description').val().trim(), $('#step-notify').is(':checked'));
        $('#step-name').val('').focus();
        $('#step-description').val('');
        $('#step-notify').prop('checked', false);
    });

    list.find('li.draggable').each((i, el) => setEvents(el));
};

export const setTextEditor = async (element) => {
    const {default: ClassicEditor} = await import('@ckeditor/ckeditor5-build-classic');

    $(element).removeAttr('required');
    ClassicEditor
        .create(element)
        .catch(error => console.error(error));
}

export const locationAutocomplete = (element) => {
    let timeout = null;

    $(element).keyup((e) => {
        clearTimeout(timeout);
        const term = e.target.value.trim();
        if (term === '') {
            if ($(element).next().hasClass('autocomplete-dropdown')) {
                $(element).next().remove();
            }
            return;
        }

        timeout = setTimeout(() => search(term), AUTOCOMPLETE_DELAY);
    });

    $(element).focus((e) => {
        const next = $(element).next();
        if (!next.hasClass('autocomplete-dropdown') || e.target.value === '') {
            return;
        }

        if (!next.hasClass('active')) {
            next.addClass('active');
        }
    });

    const search = (term) => {
        fetch(`https://nominatim.openstreetmap.org/search?q=${term}&format=json&countrycodes=fr&addressdetails=1&limit=10`)
            .then(res => res.json())
            .then(res => {
                const data = res
                    .map(place => {
                        const name = `${place.house_number ?? ''} ${place.road ?? ''}, ${place.postcode ?? ''} ${place.town ?? place.municipality ?? place.state}`;
                        return {...place, alternative_name: name};
                    })
                    .filter(place => place.address.hasOwnProperty('postcode'))
                ;

                if (!data.length) {
                    return $(element).closest('.autocomplete-wrapper').find('.autocomplete-dropdown').remove();
                }

                const ul = $('<ul class="autocomplete-dropdown active"></ul>').append(
                    data.map((place) =>
                        $(`<li class="autocomplete-item"> ${place['display_name'] ?? 'Key not found'}</li>`).data('place', place)
                    )
                );

                if ($(element).next().hasClass('autocomplete-dropdown')) {
                    $(element).next().replaceWith(ul);
                } else {
                    $(ul).insertAfter(element);
                }

                $(ul).find('.autocomplete-item').click(function(e) {
                    const place = $(this).data('place')
                    $(element).val(place.display_name);
                    $('#location_lat').val(place.lat);
                    $('#location_lon').val(place.lon);
                })
            });
    }
};
