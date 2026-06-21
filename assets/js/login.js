const urlParams = new URLSearchParams(window.location.search);
if (urlParams.get('expired') === '1') {
    const el = document.getElementById('loginError');
    el.textContent = 'Session expired. Please login again.';
    el.className = 'mb-4 p-3 rounded-lg border border-amber-500/30 bg-amber-500/10 text-amber-400 text-sm';
}

let loginRole = 'admin';

function togglePass(btn) {
    const inp = btn.parentElement.querySelector('input');
    const isPw = inp.type === 'password';
    inp.type = isPw ? 'text' : 'password';
    btn.querySelectorAll('.eye-icon').forEach(el => el.classList.toggle('hidden'));
}

function setRole(role) {
    loginRole = role;
    document.getElementById('roleAdmin').className = 'flex-1 py-2 text-sm font-medium rounded-md transition ' + (role === 'admin' ? 'bg-purple-600 text-white' : 'text-slate-400 hover:text-white');
    document.getElementById('roleStaff').className = 'flex-1 py-2 text-sm font-medium rounded-md transition ' + (role === 'staff' ? 'bg-purple-600 text-white' : 'text-slate-400 hover:text-white');
    document.getElementById('loginHeading').textContent = role === 'admin' ? 'Admin Login' : 'Staff Login';
    document.getElementById('businessCodeGroup').classList.toggle('hidden', role !== 'staff');
    if (role === 'staff') {
        document.getElementById('business_code').required = true;
    } else {
        document.getElementById('business_code').required = false;
    }
}

document.getElementById('loginForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    const formData = new FormData(this);
    formData.set('role', loginRole);
    const errorEl = document.getElementById('loginError');
    const btn = document.getElementById('loginBtn');
    const btnText = document.getElementById('loginBtnText');
    const spinner = document.getElementById('loginSpinner');

    errorEl.classList.add('hidden');
    btn.disabled = true;
    btnText.textContent = 'Signing in...';
    spinner.classList.remove('hidden');

    try {
        const res = await fetch('api/auth.php', {
            method: 'POST',
            body: formData,
        });

        const data = await res.json();

        if (data.success) {
            window.location.href = data.redirect;
        } else {
            errorEl.textContent = data.message || 'Login failed';
            errorEl.classList.remove('hidden');
            btn.disabled = false;
            btnText.textContent = 'Sign In';
            spinner.classList.add('hidden');
        }
    } catch (err) {
        errorEl.textContent = 'Network error. Please try again.';
        errorEl.classList.remove('hidden');
        btn.disabled = false;
        btnText.textContent = 'Sign In';
        spinner.classList.add('hidden');
    }
});
