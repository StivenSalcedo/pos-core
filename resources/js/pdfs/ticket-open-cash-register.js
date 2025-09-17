export default () => ({
  show: false,
  datetime: null,

  init() {
    window.addEventListener('open-cash-register', () => {
      this.show = true
      this.dateTime()
      this.$nextTick(() => {
        setTimeout(() => {
          this.setHeight()
          window.print()
          this.show = false
        }, 300)
      })
    })
  },

  dateTime() {
    let fechaHoraActual = new Date()

    let fechaFormateada =
      (fechaHoraActual.getDate() < 10 ? '0' : '') +
      fechaHoraActual.getDate() +
      '-' +
      (fechaHoraActual.getMonth() + 1 < 10 ? '0' : '') +
      (fechaHoraActual.getMonth() + 1) +
      '-' +
      fechaHoraActual.getFullYear()

    let horaFormateada =
      (fechaHoraActual.getHours() % 12 || 12) +
      ':' +
      (fechaHoraActual.getMinutes() < 10 ? '0' : '') +
      fechaHoraActual.getMinutes() +
      ' ' +
      (fechaHoraActual.getHours() < 12 ? 'AM' : 'PM')

    this.datetime = fechaFormateada + ' ' + horaFormateada
  },
  setHeight() {
    let style = document.getElementById('page-rule')

    style.innerHTML = `@page { size: 80mm 50mm; margin: 0cm;}`
  },
})
