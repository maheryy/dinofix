import $ from 'jquery';

export const draggableStep = (name, description, notify) => {
    return $(`
        <li draggable="true" class="step-item">
            <div class="step">
                <span class="label">0</span>
            </div>
            <div class="content">
                <span class="title">
                    ${name}
                    ${notify ? '<i class="fa-solid fa-paper-plane icon-notification pl-0.25"></i>' : ''}
                </span>
                <span class="remove">
                    <img class="cursor-pointer" src="/images/cross.svg" alt="icon-cross">
                </span>
                <input type="hidden" class="item-step-name" name="steps[name][]" value="${name}">
                <input type="hidden" class="item-step-description" name="steps[description][]" value="${description}">
                <input type="hidden" class="item-step-notify" name="steps[notify][]" value="${notify ? '1' : '0'}">
            </div>
        </li>
    `);
};
