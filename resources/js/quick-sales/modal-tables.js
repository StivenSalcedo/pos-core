export default () => ({
  orders: [],
  order: [],
  view: null,
  wireOrders: null,

  init() {
    this.events()
    this.wireOrders = document.getElementById('wire-orders').__livewire
  },
  events() {
    window.addEventListener('open-modal-tables', (event) => {
      this.view = event.detail.view
      this.order = JSON.parse(JSON.stringify(event.detail.order))
      this.getOrders()
    })
  },
  async getOrders() {
    toggleLoading('load-panel', true)
    await this.wireOrders.call('getOrders').then((result) => {
      toggleLoading('load-panel', false)
      this.orders = result
      this.show = true
    })
  },

  updateTable(order) {
    if (!order.is_available || order.id === this.order.id) return
    this.show = false
    this.$dispatch('update-table', { fromOrder: this.order, toOrder: order, view: this.view })
  },
})
