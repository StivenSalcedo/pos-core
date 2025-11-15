export default () => ({
  show: false,
  company: {},
  service: {},
  customer:{},
  init() {
    window.addEventListener('print-label', (event) => {
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
    let height = 120;
    const width = this.$store.config.widthTicket
    style.innerHTML = `@page { size: ${width}mm ${height}mm; margin: 0cm;}`
  },
  formatDate(date, format = 'DD/MM/YYYY') {
        const d = new Date(date);
        const pad = n => String(n).padStart(2, '0');
        const map = {
            YYYY: d.getFullYear(),
            MM: pad(d.getMonth() + 1),
            DD: pad(d.getDate()),
            HH: pad(d.getHours()),
            mm: pad(d.getMinutes()),
            ss: pad(d.getSeconds()),
            MMM: d.toLocaleString('es-ES', { month: 'short' }),
            MMMM: d.toLocaleString('es-ES', { month: 'long' }),
        };

        return format.replace(/YYYY|MM|DD|HH|mm|ss|MMM|MMMM/g, matched => map[matched]);
      },
})
