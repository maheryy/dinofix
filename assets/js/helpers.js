import $ from 'jquery';
import bindEvents from "./bind";

export const bindRecursive = (element) => {
    bindEvents(element);
}

export const log = (element) => {
    console.log(element);
};

