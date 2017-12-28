'use strict';

let btnDeleteAction = Array.from(document.querySelectorAll('.btn-delete-action'));

btnDeleteAction.map( (el) => {
    el.addEventListener('click', (e) => {
       e.preventDefault();
       let confirm = window.confirm('Confirmer la suppression ?');
       if(confirm){
           window.location.href = e.target.href;
       }
    });
});