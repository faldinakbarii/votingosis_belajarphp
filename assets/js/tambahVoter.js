function resetForm() {
    if (confirm('Reset semua isian form?')) {
        document.getElementById('voterForm').reset();
    }
}


const keyword = document.getElementById('keyword');
const list = document.getElementById('votersList');

keyword.addEventListener('keyup', function () {

    const xhr = new XMLHttpRequest();

    // cek ajax
    xhr.onreadystatechange = function () {
        if (xhr.status == 200 && xhr.readyState == 4) {
            list.innerHTML = xhr.responseText;
        }
    }

    xhr.open('GET', '../ajax/searchDataVoters.php?keyword=' + keyword.value, true);
    xhr.send();

})

function twiceConfirm() {
    const confirm1 = confirm('Apakah anda yakin ingin menghapus SEMUA data voters?');
    if (confirm1) {
        const confirm2 = confirm('Sekali lagi! Apakah anda yakin menghapus SEMUA data voters?');
        if (confirm2) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}