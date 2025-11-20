function togglePassword(fieldId) {
    const passwordInput = document.getElementById(fieldId);
    const toggleIcon = passwordInput.nextElementSibling;
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.textContent = '🙈';
    } else {
        passwordInput.type = 'password';
        toggleIcon.textContent = '👁️';
    }
}

function checkPasswordStrength() {
    const password = document.getElementById('password').value;
    const strengthBar = document.getElementById('passwordStrengthBar');
    
    let strength = 0;
    
    if (password.length >= 8) strength++;
    if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
    if (password.match(/[0-9]/)) strength++;
    if (password.match(/[^a-zA-Z0-9]/)) strength++;
    
    strengthBar.className = 'password-strength-bar';
    
    if (strength <= 1) {
        strengthBar.classList.add('weak');
    } else if (strength <= 3) {
        strengthBar.classList.add('medium');
    } else {
        strengthBar.classList.add('strong');
    }
}

function showTerms(event) {
    event.preventDefault();
    alert('📋 Syarat & Ketentuan\n\n1. Admin bertanggung jawab atas keamanan akun\n2. Data pemilih harus dijaga kerahasiaannya\n3. Tidak boleh memanipulasi hasil voting\n4. Harus menjaga integritas sistem\n5. Melaporkan jika ada masalah teknis');
}

function showPrivacy(event) {
    event.preventDefault();
    alert('🔒 Kebijakan Privasi\n\n1. Data admin akan dijaga kerahasiaannya\n2. Informasi tidak akan dibagikan ke pihak ketiga\n3. Data dienkripsi dengan aman\n4. Kami menghormati privasi Anda');
}

function goToLogin(event) {
    event.preventDefault();
    if(confirm('Pergi ke halaman login?')) {
        alert('Anda akan diarahkan ke halaman login...');
        // window.location.href = 'login.html';
    }
}

// Auto focus ke nama depan saat halaman load
window.onload = function() {
    document.getElementById('namaDepan').focus();
}