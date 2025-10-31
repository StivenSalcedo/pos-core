export default () => ({
  show: false,
  company: {},
  service: {},
  customer:{},
  init() {
    window.addEventListener('print-ticket', (event) => {
      this.show = true
      this.getService(`/administrador/servicios/informacion/${event.detail}`).then(() => {
        this.$nextTick(() => {
         this.setHeight()
          window.print()
          this.show = false
        })
      })
    })
  },

  getService(url) {
    return fetch(url)
      .then((response) => {
        if (!response.ok) {
          throw new Error(`Error de red: ${response.status}`)
        }
        return response.json()
      })
      .then((data) => {
        this.company = data.data.company
        this.service = data.data.service
        this.customer = data.data.service.customer
      })
      .catch((error) => {
        console.error('Error al obtener datos:', error)
      })
  },

  setHeight() {
    let style = document.getElementById('page-rule')

    let oneLine = 0
    let twoLine = 0

    this.service.products.forEach((element) => {
      if (element.product.name.length <= 31) {
        oneLine++
      } else {
        twoLine++
      }
    })

    let height = 0;

    
      height += 50
    

    height += 182 + oneLine * 4.2 + twoLine * 7.7

    const width = this.$store.config.widthTicket

    style.innerHTML = `@page { size: ${width}mm ${height}mm; margin: 0cm;}`
  },
})
