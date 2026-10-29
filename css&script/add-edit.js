const photoDrop = document.getElementById('photoDrop');
const uploadInput = document.getElementById('uploadfile');
const previewImg = document.getElementById('previewImg');
const dropLabel = document.getElementById('dropLabel');

// Clicking anywhere on the box opens the file picker
photoDrop.addEventListener('click', () => uploadInput.click());

uploadInput.addEventListener('change', () => {
    const file = uploadInput.files && uploadInput.files[0];
    if (!file) {
        previewImg.style.display = 'none';
        previewImg.src = '';
        dropLabel.textContent = 'choose a photo';
        return;
    }

    const reader = new FileReader();
    reader.onload = (e) => {
        previewImg.src = e.target.result;
        previewImg.style.display = 'block';
    };
    reader.readAsDataURL(file);

    dropLabel.textContent = file.name;
});