// Alternância entre abas de login/registro
document.addEventListener("DOMContentLoaded", function () {
    const loginTab = document.getElementById("login-tab");
    const registerTab = document.getElementById("register-tab");
    const loginForm = document.getElementById("tab-login");
    const registerForm = document.getElementById("tab-register");

    loginTab.addEventListener("click", () => {
        loginTab.classList.add("active");
        registerTab.classList.remove("active");
        loginForm.classList.add("active");
        registerForm.classList.remove("active");
    });

    registerTab.addEventListener("click", () => {
        registerTab.classList.add("active");
        loginTab.classList.remove("active");
        registerForm.classList.add("active");
        loginForm.classList.remove("active");
    });

    // Botão loading
    const forms = document.querySelectorAll("form");
    forms.forEach(form => {
        form.addEventListener("submit", function () {
            const btn = form.querySelector("button");
            btn.querySelector(".btn-text").classList.add("d-none");
            btn.querySelector(".spinner-border").classList.remove("d-none");
        });
    });

    // Toast auto hide
    setTimeout(() => {
        const toast = document.getElementById("toast-container");
        if (toast) toast.innerHTML = "";
    }, 4000);
});

    // Alternância menu
document.addEventListener("DOMContentLoaded", function () {
    const tabs = document.querySelectorAll("[data-tab]");
    const panes = document.querySelectorAll(".tab-pane");

    tabs.forEach(tab => {
        tab.addEventListener("click", () => {
            const target = tab.getAttribute("data-tab");

            tabs.forEach(t => t.classList.remove("active"));
            tab.classList.add("active");

            panes.forEach(p => {
                p.classList.remove("active");
                if (p.id === "tab-" + target) {
                    p.classList.add("active");
                }
            });
        });
    });
});

    // # Validação de Senha
document.querySelectorAll("form").forEach(form => {
    form.addEventListener("submit", function (e) {
        const senha = form.querySelector('input[name="password"]');
        if (senha && (senha.value.length < 8 || senha.value.length > 12)) {
            e.preventDefault();
            alert("A senha deve conter entre 8 e 12 caracteres.");
            return false;
        }

        const btn = form.querySelector("button");
        btn.querySelector(".btn-text").classList.add("d-none");
        btn.querySelector(".spinner-border").classList.remove("d-none");
    });
});
