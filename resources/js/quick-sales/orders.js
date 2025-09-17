export default () => ({
  orders: [],
  order: [],
  config: {},
  view: null,
  orderEmty: {
    customer: [],
    is_available: true,
    name: 'Venta en caja',
    products: [],
    total: 0,
  },

  init() {
    this.events()
    this.getOrders()
    this.config = this.$store.config
  },
  events() {
    window.addEventListener('store', (event) => {
      this.order.products = event.detail
      if (this.order.is_available) {
        this.store()
      } else {
        this.update()
      }
    })

    window.addEventListener('store-bill', ({ detail }) => {
      this.storeBill(detail.cash, detail.tip, detail.paymentMethod)
    })

    window.addEventListener('set-change', (event) => {
      this.order.products = event.detail.products
      this.order.total = event.detail.total
      this.change(this.order, 'order')
    })

    window.addEventListener('update-customer', (event) => {
      this.updateCustomer(event.detail)
    })

    window.addEventListener('update-table', (event) => {
      this.updateTable(event.detail)
    })
  },
  async getOrders() {
    await this.$wire.getOrders().then((result) => (this.orders = result))
  },
  loadOrder(order = null) {
    this.order = JSON.parse(JSON.stringify(order === null ? this.orderEmty : order))
    if (!this.order.is_available) {
      this.$dispatch('load-products', this.order.products)
    } else {
      this.order.customer = this.config.customer
    }

    this.$dispatch('current-order', this.order)
    this.$dispatch('toggle-view', true)
  },
  showCustomers(order) {
    this.order = JSON.parse(JSON.stringify(order))
    this.$dispatch('open-customers', true)
  },
  updateCustomer(customer) {
    this.order.customer = customer

    this.$wire.updateCustomer(this.order).then((result) => {
      if (result === 'success') {
        this.order = []
        this.getOrders()
      }
    })
  },
  updateTable({ fromOrder, toOrder, view }) {
    if (fromOrder.is_available) return this.loadOrder(toOrder)
    if (view === 'order') toggleLoading('load-panel', true)

    this.$wire.updateTable(fromOrder, toOrder).then(async (result) => {
      if (result === 'success') {
        await this.getOrders()
        if (view === 'order') {
          const order = this.orders.find((value) => toOrder.id === value.id)
          this.loadOrder(order)
        }
      }
      toggleLoading('load-panel', false)
    })
  },
  store() {
    toggleLoading('load-panel', true)

    this.$wire.store(this.order).then((result) => {
      toggleLoading('load-panel', false)
      if (result === 'success') {
        this.order = []
        this.getOrders()
        this.$dispatch('toggle-view', false)
      }
    })
  },
  update() {
    toggleLoading('load-panel', true)

    this.$wire.update(this.order).then((result) => {
      toggleLoading('load-panel', false)
      if (result === 'success') {
        this.order = []
        this.getOrders()
        this.$dispatch('toggle-view', false)
      }
    })
  },
  deleteOrder(order) {
    const message = `¿Quieres eliminar la orden ${order.name} ?`
    const callBack = async () => {
      await this.$wire.destroy(order.id).then((result) => {
        this.getOrders()
      })
    }
    confirmDelete(message, callBack)
  },
  change(order, view) {
    this.view = view
    this.order = order
    if (this.config.change) {
      this.$dispatch('open-change', this.order.total)
    } else {
      this.storeBill(this.order.total)
    }
  },
  storeBill(cash, tip = 0, paymentMethod = '1') {
    if (this.view === 'order') toggleLoading('load-panel', true)
    this.$wire.storeBill(this.order, cash, tip, paymentMethod).then((result) => {
      toggleLoading('load-panel', false)
      if (result === 'success') {
        if (this.order.id) {
          this.order = []
          this.getOrders()
          this.$dispatch('toggle-view', false)
        } else {
          this.$dispatch('reset-cart')
          this.loadOrder()
        }
      }
    })
  },
  printPreBill(item, isCommand = false) {
    this.$wire.createBillOnlyPrint(item, isCommand)
  }
})
