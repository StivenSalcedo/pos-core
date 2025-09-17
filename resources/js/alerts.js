import sweet from 'sweetalert2'

window.Swal = sweet

const Toast = Swal.mixin({
  toast: true,
  position: 'bottom-end',
  showConfirmButton: false,
  timer: 2000,
  timerProgressBar: true,
  customClass: 'no-print',
  didOpen: (toast) => {
    toast.addEventListener('mouseenter', Swal.stopTimer)
    toast.addEventListener('mouseleave', Swal.resumeTimer)
  },
})

window.confirmDelete = (message, callBack) => {
  Swal.fire({
    title: '¿Estas seguro?',
    text: message,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Sí, aceptar',
    cancelButtonText: 'Cancelar',
    showLoaderOnConfirm: true,
    preConfirm: callBack,
    allowOutsideClick: () => !Swal.isLoading(),
  })
}

document.addEventListener('livewire:load', function () {
  Livewire.on('success', (message) => {
    Toast.fire({
      icon: 'success',
      title: message,
      customClass: 'no-print',
    })
  })

  Livewire.on('static-success', (msj) => {
    Swal.fire({
      icon: 'success',
      title: 'Acción exiotosa',
      text: msj,
    })
  })

  Livewire.on('error', (msj) => {
    Swal.fire({
      icon: 'error',
      title: 'Oops...',
      text: msj,
    })
  })

  Livewire.on('alert', (msj) => {
    Swal.fire({
      icon: 'warning',
      title: 'Oops...',
      text: msj,
    })
  })
})
