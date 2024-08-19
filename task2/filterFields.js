let typeSelect = document.querySelector('[name="type_val"]');

function updateFields() {
    let selectedValue = typeSelect.value;
    let allFields = document.querySelectorAll('input');

    allFields.forEach(field => {
        if (field.name.includes(selectedValue)) {
            field.closest('p').style.display = '';
        } else {
            field.closest('p').style.display = 'none';
        }
    });
}

typeSelect.addEventListener('change', updateFields);

updateFields();
