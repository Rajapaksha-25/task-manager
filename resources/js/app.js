import './bootstrap';

const API = "http://127.0.0.1:8000/api";

// ---------- REGISTER ----------
async function register() {
    // Clear previous error messages
    ["name","email","password","password_confirmation"].forEach(id=>{
        const el = document.getElementById(`error-${id}`);
        if(el) el.textContent="";
    });

    const name = document.getElementById("name").value;
    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;
    const password_confirmation = document.getElementById("password_confirmation").value;

    const res = await fetch(API + "/register", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json"
        },
        body: JSON.stringify({ name, email, password, password_confirmation })
    });

    const data = await res.json();

    if (res.ok) {
        localStorage.setItem("token", data.access_token);
        window.location.href = "/dashboard";
    } else {
        // Display Laravel validation errors under each field
        if (data.errors) {
            for (const key in data.errors) {
                const el = document.getElementById(`error-${key}`);
                if (el) el.textContent = data.errors[key][0];
            }
        } else if (data.message) {
            // fallback: show under password field
            const el = document.getElementById("error-password");
            if(el) el.textContent = data.message;
        }
    }
}

// ---------- LOGIN ----------
async function login() {
    ["email","password"].forEach(id=>{
        const el = document.getElementById(`error-${id}`);
        if(el) el.textContent="";
    });

    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;

    const res = await fetch(API + "/login", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json"
        },
        body: JSON.stringify({ email, password })
    });

    const data = await res.json();

    if (res.ok) {
        localStorage.setItem("token", data.access_token);
        window.location.href = "/dashboard";
    } else {
        if (data.errors) {
            for (const key in data.errors) {
                const el = document.getElementById(`error-${key}`);
                if (el) el.textContent = data.errors[key][0];
            }
        } else if (data.message) {
            const el = document.getElementById("error-password");
            if(el) el.textContent = data.message;
        }
    }
}

// ---------- LOGOUT ----------
function logout() {
    localStorage.removeItem("token");
    window.location.href = "/login";
}

// ---------- AUTH CHECK ----------
function checkAuth() {
    if (!localStorage.getItem("token")) {
        window.location.href = "/login";
    }
}

// Make functions global so HTML buttons can call them
window.register = register;
window.login = login;
window.logout = logout;
window.checkAuth = checkAuth;