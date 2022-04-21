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

export const clearInput = (element) => {
    const input = $($(element).data('target'));
    if (!input.length) {
        return console.error(`function clearInput: target not found (${$(element).data('target')})`)
    }

    $(element).click(() => input.val('').focus());
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
    let dragSrcEl;

    function dragStart(e) {
        dragSrcEl = this;
        e.originalEvent.dataTransfer.effectAllowed = 'move';
        e.originalEvent.dataTransfer.setData('text/html', this.innerHTML);
        $(this).addClass('dragging');
    };

    function dragEnter(e) {
        $(this).addClass('over');
    }

    function dragLeave(e) {
        e.stopPropagation();
        $(this).removeClass('over');
    }

    function dragOver(e) {
        e.preventDefault();
        e.originalEvent.dataTransfer.dropEffect = 'move';
        $(this).addClass('over');
        return false;
    }

    function dragDrop(e) {
        e.stopPropagation();
        if (dragSrcEl !== this) {
            dragSrcEl.innerHTML = this.innerHTML;
            this.innerHTML = e.originalEvent.dataTransfer.getData('text/html');
        }
        return false;
    }

    function dragEnd(e) {
        list.find('li.step-item').removeClass('dragging over').find('.remove').click(removeStep);
        refreshSteps();
    }

    const setEvents = (item) => {
        $(item)
            .on('dragstart', dragStart)
            .on('dragenter', dragEnter)
            .on('dragover', dragOver)
            .on('dragleave', dragLeave)
            .on('drop', dragDrop)
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