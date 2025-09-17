export default () => ({
  products: [],
  backupProducts: [],
  update: false,
  hashProducts: null,
  changedHash: null,
  showComment: true,

  init() {
    this.$watch('products', (value) => {
      if (!this.update) return
      this.changedHash = createHashProducts(value) !== this.hashProducts
    })

    this.events()
  },
  events() {
    window.addEventListener('load-products', (event) => {
      this.hashProducts = createHashProducts(event.detail)
      this.backupProducts = JSON.parse(JSON.stringify(event.detail))
      this.products = event.detail
      this.update = true
    })

    window.addEventListener('reset-cart', (event) => {
        this.products = []
        this.update = false
        this.hashProducts = null
    })

    window.addEventListener('toggle-view', (event) => {
      if (!event.detail) {
        this.products = []
        this.update = false
        this.hashProducts = null
      }
    })

    window.addEventListener('set-product', (event) => {
      this.addProduct(event.detail)
    })

    window.addEventListener('verify-block-order', (event) => {
        this.verifyBlockOrder();
    })

    window.addEventListener('beforeunload', (e) => {
      if (this.changedHash) {
        e.preventDefault()
        const message =
          '¿Estás seguro de que quieres salir de esta página? Los cambios que hiciste no quedaran guardados.'
        e.returnValue = message
        return message
      }
    })
  },
  async verifyBlockOrder() {
    if (this.changedHash) {
      await Swal.fire({
        title: 'Tienes cambios en el pedido',
        text: `Tienes cambios en el pedido si guardar`,
        icon: 'warning',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Aceptar',
      })
    }else{
        this.$dispatch('toggle-view', false)
    }
  },
  restore() {
    this.products = JSON.parse(JSON.stringify(this.backupProducts))
  },
  addProduct(product) {
    this.products.push({
      ...product,
      amount: 1,
      total: product.price,
    })
  },
  getProductName(product) {
    if (Object.keys(product.presentation).length) {
      return product.name + ' (' + product.presentation.name + ')'
    }
    return product.name
  },
  handleAmount(item, action) {
    if (action === 'less' && item.amount >= 2) {
      item.amout = item.amount--
    } else if (action === 'add') {
      item.amout = item.amount++
    }
    this.calcProduct(item)
  },
  calcProduct(item) {
    const amount = parseInt(item.amount)
    if (amount !== NaN && amount <= 99999) {
      item.total = item.price * amount
    } else {
      item.total = item.price * 1
    }
  },
  dropProduct(index) {
    this.products.splice(index, 1)
  },
  store() {
    this.$dispatch('store', this.products);
  },
  storeBill() {
    if (!this.products.length) {
      Livewire.emit('alert', 'Agrega uno o mas productos')
      return
    }

    this.$dispatch('set-change', {
      products: JSON.parse(JSON.stringify(this.products)),
      total: this.total,
    })
  },
  getComment(){
    this.showComment = !this.showComment;
  },
  get total() {
    return this.products.map((item) => item.total).reduce((prev, curr) => prev + curr, 0)
  },
})
