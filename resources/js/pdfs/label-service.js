export default () => ({
  show: false,
  company: {},
  customer: {},
  bill: {},
  range: {},
  products: {},
  taxes: {},
  electronic_bill: {},
  isElectronic: false,

  init() {
    window.addEventListener('print-label', (event) => {
      this.show = true
      this.getBill(`/administrador/facturas/informacion/${event.detail}`).then(() => {
        this.$nextTick(() => {
          this.setHeight()
          window.print()
          this.products = {}
          this.show = false
        })
      })
    })
  },

  getBill(url) {
    return fetch(url)
      .then((response) => {
        if (!response.ok) {
          throw new Error(`Error de red: ${response.status}`)
        }
        return response.json()
      })
      .then((data) => {
        this.company = data.data.company
        this.customer = data.data.customer
        this.bill = data.data.bill
        this.range = data.data.range
        this.products = data.data.products
        this.taxes = data.data.taxes
        this.electronic_bill = data.data.electronic_bill
        this.isElectronic = data.data.is_electronic
      })
      .catch((error) => {
        console.error('Error al obtener datos:', error)
      })
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

    let height = Object.keys(this.range).length ? 12 : 0

    if (this.isElectronic) {
      height += 50
    }

    height += 182 + oneLine * 4.2 + twoLine * 7.7

    const width = this.$store.config.widthTicket

    style.innerHTML = `@page { size: ${width}mm ${height}mm; margin: 0cm;}`
  },
})
