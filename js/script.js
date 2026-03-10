document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (el) { return new bootstrap.Tooltip(el); });

    document.body.addEventListener('click', function(e){
        if(e.target.matches('.confirm-delete, .confirm-delete *')){
            if(!confirm('¿Está seguro de que desea eliminar este registro?')) e.preventDefault();
        }
    });
});

function validateForm(form){
    let valid = true;
    const required = form.querySelectorAll('[required]');
    required.forEach(field => {
        if(!field.value.trim()){
            field.classList.add('is-invalid');
            valid = false;
        } else {
            field.classList.remove('is-invalid');
        }
        if(field.type === 'email' && field.value){
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if(!re.test(field.value)){
                field.classList.add('is-invalid');
                valid = false;
            }
        }
    });
    return valid;
}