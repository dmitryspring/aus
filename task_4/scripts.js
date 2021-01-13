"use strict";
let forms = document.querySelectorAll('form');
for (let form of forms) {
    form.addEventListener('submit', process);
}
function process(e) {

    let formData = new FormData(e.target);
    formData.append('action', e.target.id);

    fetch('./process.php', {
        method: 'POST',
        body: formData
    })
        .then((response) => {
            return response.json();
        })
        .then((data) => {
            if(data.success) {
                alert(data.message);
                e.target.reset();
            } else {
                alert("Some error occurred: " + data.message);
            }
        });

    e.preventDefault();
}