import Swal from 'sweetalert2';

const SweetAlert = {
    install(app) {
        app.config.globalProperties.$swal = Swal;
    },
};

export default SweetAlert;
