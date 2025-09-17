export default () => ({
  show: false,
  customer: {},
  payment: {},
  products: {},

  init() {
    window.addEventListener('print-ticket', (event) => {
      this.show = true
      this.customer = event.detail.customer
      this.payment = event.detail.payment
      this.products = event.detail.products

      this.$nextTick(() => {
        setTimeout(() => {
          this.setHeight()
          window.print()
          this.show = false
        }, 1000)
      })
    })
  },

  setHeight() {
    let style = document.getElementById('page-rule')
    style.innerHTML = `@page { size: 80mm 155mm; margin: 0cm;}`
  },
})
