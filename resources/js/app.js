import Swal from "sweetalert2";

window.Swal = Swal;

Livewire.on("swal", (data) => {
    const { icon, title, text, confirmButtonText, redirect, nextSwal, delay } =
        Array.isArray(data) ? data[0] : data;

    setTimeout(() => {
        Swal.fire({
            title: title ?? "Mensaje",
            text: text ?? "Sin mensaje",
            icon: icon ?? "success",
            confirmButtonText: confirmButtonText ?? "Ok",
        }).then(() => {
            if (nextSwal) {
                Swal.fire({
                    title: nextSwal.title ?? "Mensaje",
                    text: nextSwal.text ?? "Sin mensaje",
                    icon: nextSwal.icon ?? "success",
                    confirmButtonText: nextSwal.confirmButtonText ?? "Ok",
                }).then(() => {
                    if (nextSwal.redirect) {
                        window.location.href = nextSwal.redirect;
                    }
                });
            } else if (redirect) {
                window.location.href = redirect;
            }
        });
    }, delay ?? 50); // Espera 50ms por defecto
});

Livewire.on("solicitarPasswordActivacion", ({ id_empleado }) => {
    Swal.fire({
        title: "Confirmar activación",
        text: "Ingresa tu contraseña para activar este empleado.",
        input: "password",
        inputPlaceholder: "Contraseña",
        showCancelButton: true,
        confirmButtonText: "Activar",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        if (result.isConfirmed && result.value) {
            Livewire.emit("verificarPasswordActivacion", {
                id_empleado: id_empleado,
                password: result.value,
            });
        }
    });
});
