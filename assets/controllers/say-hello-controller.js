/**
 * Exemple stimulus import the html in a twig template wheere you want to use it!!!!!
 *
 * @see more information
 * https://stimulus.hotwired.dev/handbook/introduction
 * https://symfony.com/doc/current/frontend/ux.html
 * https://symfony.com/doc/current/frontend/ux.html#ux-packages-list
 * https://github.com/symfony/stimulus-bridge
 */

import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['name', 'output']

    greet() {
        this.outputTarget.textContent = `Hello, ${this.nameTarget.value}!`
    }
}


/**
<div {{ stimulus_controller('say-hello') }}>
    <input type="text" {{ stimulus_target('say-hello', 'name') }}>

        <button {{ stimulus_action('say-hello', 'greet') }}>
            Greet
        </button>

        <div {{ stimulus_target('say-hello', 'output') }}></div>
</div>
 **/