import $ from 'jquery';
import * as Events from "./events";

const bindEvents = (rootElement = null) => {
    $(rootElement ?? 'body').find('[data-event]').each((index, element) => {
        const event = $(element).data('event');
        const data = $(element).data('json');
        if (Events[event] === undefined) {
            throw new Error(`Unknown event "${event}" from data-event attribute`);
        }
        Events[event](element, data ?? null);
    });
};

export default bindEvents;