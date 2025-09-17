export default () => ({
  show: false,
  customer: {},
  bill: {},
  range: {},
  products: {},

  init() {
    window.addEventListener('print-command-bill', (event) => {
      if (!this.$store.config.print) return
      this.show = true
      this.getBill(event.detail)
      this.$nextTick(() => {
        this.setHeight()
        window.print()
        this.products = {}
        this.show = false
      })
    })
  },

  getBill(bill) {
        this.customer = bill.customer
        this.bill = bill.bill
        this.range = bill.range
        this.products = bill.products
  },

  setHeight() {
    let style = document.getElementById('page-rule')

    let oneLine = 0
    let twoLine = 0

    this.products.forEach((element) => {
      if (element.name.length <= 31) {
        oneLine++
      } else {
        twoLine++
      }
    })

    let height = 158 + oneLine * 4.2 + twoLine * 7.7
    const width = this.$store.config.widthTicket

    style.innerHTML = `@page { size: ${width}mm ${height}mm; margin: 0cm;}`
  },
})
