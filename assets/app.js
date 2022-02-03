/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */
require("bootstrap");
// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

// start the Stimulus application
import './bootstrap';

import { Tooltip, Toast, Popover } from "bootstrap";
const $ = require('jquery');

function handleWatchList() {
    const watchListButton = document.getElementsByClassName('watchListButton');

    for(let button of watchListButton)
    {
        button.addEventListener('click', (e) => {
            e.target.classList.toggle('active');
            const id = e.target.id.split('_')[1]; 
            updateWatchList(id);
        });
    }

}

function updateWatchList(mangaId){
    fetch(`/updateWatchList/${mangaId}`, 
    {
        method: 'GET',
    })
    
}

window.onload = handleWatchList();