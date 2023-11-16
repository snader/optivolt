"use strict";
var ignoreClass = 'not-lazy';
var ignoredClasses = ['lazyload', 'lazyloading', 'lazyloaded'];
var ignoreSelector = ':not([src*=".svg"]):not([src*=".eps"]):not([src*=".gif"]):not(.' + ignoreClass + ')';

/**
 * Check elements for images, add lazyload class
 */
document.addEventListener("DOMContentLoaded", function () {
    var images = document.querySelectorAll('img' + ignoreSelector + ', div[style*="background-image"]' + ignoreSelector);

    for (var i = 0; i < images.length; i++) {
        images[i].className += ' lazyload';
    }
});

/**
 * Catch lazyload event, and handle creation of blurred image
 */
window.addEventListener('lazybeforeunveil', function (e) {
    var element = e.target;

    if (e.detail.instance !== lazySizes) {
        return;
    }
    createBlurred(element);
});

/**
 * Get all loaded elements and start execute un blurring process
 */
window.addEventListener('lazyloaded', function (e) {
    var element = e.target;

    if (e.detail.instance !== lazySizes) {
        return;
    }
    unBlurClone(element);
});

/**
 * Get the element, create a clone and inject into DOM
 *
 * @param element
 */
function createBlurred(element) {
    var clone = element.cloneNode(true);
    var classes = [];
    var parent = element.parentNode;
    var wrapper = document.createElement('DIV');

    clone.style.backgroundImage = element.style.backgroundImage;

    for (var i = 0; i < element.classList.length; i++) {
        if (ignoredClasses.indexOf(element.classList[i]) >= 0) {
            continue;
        }
        classes.push(element.classList[i]);
    }

    // wrapper.style.position = 'relative';
    clone.className = classes.join(' ');
    clone.style.position = 'absolute';
    clone.style.zIndex = element.style.zIndex + 1;
    clone.style.opacity = 1;
    clone.style.transition = 'opacity .6s ease-out';
    clone.style.width = (clone.getAttribute('width') ? clone.getAttribute('width') : element.offsetWidth) + 'px';
    clone.style.height = (clone.getAttribute('height') ? clone.getAttribute('height') : element.offsetHeight) + 'px';

    wrapper.appendChild(clone);
    parent.insertBefore(wrapper, element);
}

/**
 * Get the element, fade out the blurred element and remove afterwards
 *
 * @param element
 */
function unBlurClone(element) {
    var blurred = element.previousSibling.firstChild;
    setTimeout(function () {
        blurred.style.opacity = 0;
    }, 200);

    setTimeout(function () {
        $(blurred).parent().remove();
    }, 600);
}