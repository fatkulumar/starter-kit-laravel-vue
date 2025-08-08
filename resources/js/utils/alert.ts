import Swal from 'sweetalert2'

const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    showCloseButton: true,
    didOpen: (toast) => {
        toast.onmouseenter = Swal.stopTimer
        toast.onmouseleave = Swal.resumeTimer
    }
})

export const showSuccess = (message: string) => {
    Toast.fire({
        icon: 'success',
        title: message || 'Berhasil'
    })
}

export const showError = (errors: any) => {
    const allMessages = Object.values(errors || {}).flat();

    if (!allMessages.length) return;

    const htmlList = `<ul style="text-align:left;margin:0;padding-left:1.2em;">
    ${allMessages.map(msg => `<li>${msg}</li>`).join('')}
  </ul>`;

    Toast.fire({
        icon: "error",
        title: "Terjadi kesalahan:",
        html: htmlList,
    });
};

