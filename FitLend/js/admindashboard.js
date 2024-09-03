// js/admindashboard.js
document.addEventListener('DOMContentLoaded', function () {
    const sections = document.querySelectorAll('.content-section');
    const links = document.querySelectorAll('.sidebar ul li a');

    links.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            sections.forEach(section => section.style.display = 'none');
            document.querySelector(link.getAttribute('href')).style.display = 'block';
        });
    });

    // Default section
    sections[0].style.display = 'block';
});

function editEquipment(id) {
    const form = document.getElementById('edit-form');
    form.style.display = 'block';
    // Populate form with existing equipment details
    fetch('fetch_equipment.php?id=' + id)
        .then(response => response.json())
        .then(data => {
            document.getElementById('edit-equipment-id').value = data.id;
            document.getElementById('edit-name').value = data.name;
            document.getElementById('edit-description').value = data.description;
            document.getElementById('edit-category').value = data.category;
            document.getElementById('edit-available_count').value = data.available_count;
            document.getElementById('edit-daily_fee').value = data.daily_fee;
            document.getElementById('edit-available').value = data.available;
        });
}

function showAddForm() {
    document.getElementById('add-form').style.display = 'block';
}

function closeForm(id) {
    document.getElementById(id).style.display = 'none';
}



// Confirmation before deleting equipment
document.addEventListener('DOMContentLoaded', () => {
    const deleteButtons = document.querySelectorAll('.btn-delete');
    deleteButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            const confirmDelete = confirm('Are you sure you want to delete this item?');
            if (!confirmDelete) {
                e.preventDefault();
            }
        });
    });
});
