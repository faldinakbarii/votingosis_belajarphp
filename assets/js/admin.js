
function loadVotes() {
    fetch("../ajax/realTimeVoters.php")
        .then(res => res.json())
        .then(data => {

            let html = "";
            data.forEach(item => {
                html += `
                <div class="candidate-result">
                    <div class="candidate-header">
                        <div class="candidate-name-info">
                            <h3>${item.nama}</h3>
                            <p>${item.kelas}</p>
                        </div>
                        <div class="vote-count">${item.jumlah_suara} Suara</div>
                    </div>
                </div>`;
            });

            document.getElementById("candidateContainer").innerHTML = html;
        })
        .catch(err => console.error(err));
}

setInterval(loadVotes, 500);
loadVotes();

function twiceConfirm() {
    const confirm1 = confirm('Apakah anda yakin ingin mereset SEMUA sistem?');
    if (confirm1) {
        const confirm2 = confirm('Sekali lagi! Apakah anda yakin mereset SEMUA sistem?');
        if (confirm2) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}