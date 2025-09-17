import { taxRatesByTribute, totalTaxRates } from './tax-rates'

document.addEventListener('alpine:init', () => {
  Alpine.data('alpineBill', () => ({
    customerDefault: {},
    customer: {},
    products: [],
    product: {},
    percent: 0,
    discount: 0,
    amount: 1,
    update: false,
    indexUpdate: null,
    alert: '',
    presentations: [],
    presentation: [],
    presentation_id: '',
    showDropdownPrensentations: false,
    finance: 0,

    inputAmount: {
      ['x-on:focus']() {
        this.$refs.cant.style.borderColor = 'rgb(6 182 212)'
        this.$el.select()
      },
      ['x-on:blur']() {
        this.$refs.cant.style.borderColor = 'rgb(107 114 128)'
      },
    },

    inputDiscount: {
      ['x-on:focus']() {
        this.$refs.desc.style.borderColor = 'rgb(6 182 212)'
        this.$el.select()
      },
      ['x-on:blur']() {
        this.$refs.desc.style.borderColor = 'rgb(107 114 128)'
      },
    },

    init() {
      this.customerDefault = this.$wire.get('customerDefault')

      window.addEventListener('reset-properties-bill', (event) => {
        this.customer = {}
        this.products = []
        this.product = {}
        this.percent = 0
        this.discount = 0
        this.amount = 1
        this.update = false
        this.indexUpdate = null
        this.alert = ''
        this.presentations = []
        this.presentation = []
        this.presentation_id = ''
        this.showDropdownPrensentations = false
        this.finance = 0
      })

      window.addEventListener('set-customer', (event) => {
        this.customer = event.detail
      })

      window.addEventListener('add-product', (event) => {
        this.addProduct()
      })

      window.addEventListener('set-percent', (event) => {
        this.percent = event.detail
        if (event.detail === 0) {
          this.discount = 0
        }
      })

      window.addEventListener('store', (event) => {
        this.store(event.detail)
      })

      window.addEventListener('set-product', (event) => {
        this.presentation = []
        this.product = event.detail.product
        this.presentations = event.detail.presentations

        if (!Object.keys(this.presentation).length) this.$refs.amount.focus()

        this.amount = 1
        this.update = false
      })
    },

    get rates() {
      if (!Object.keys(this.product).length) return ''

      return this.product.tax_rates
        .map((item) => (item.has_percentage ? '% ' + item.rate : '$' + item.value))
        .join(', ')
    },

    get price() {
      if (!Object.keys(this.product).length) return 0

      if (Object.keys(this.presentation).length) return this.presentation.price
      return this.product.price
    },

    get total() {
      let amount = parseInt(this.amount, 10)
      let discount = parseInt(this.discount, 10)

      if (Object.keys(this.product).length) {
        if (!Number.isInteger(discount) || discount < 1) discount = 0

        if (Number.isInteger(amount) && amount > 0) {
          let total = amount * this.price

          if (this.percent) {
            discount = custRound(total * this.percent)
            this.discount = discount
          }

          return total - discount
        }
      }

      return 0
    },

    setPresentation(item) {
      this.presentation = item
      this.presentation_id = item.id
      this.$refs.amount.focus()
      this.showDropdownPrensentations = false
    },

    addProduct() {
      if (this.validateDataForm()) {
        this.products.push(this.getDataForm())
        this.resetForm()
      } else {
        this.$refs.amount.focus()
      }
    },

    validateDataForm() {
      if (this.discount.length === 0) {
        this.discount = 0
      }

      let amount = parseInt(this.amount)
      let discount = parseInt(this.discount)

      if (!Number.isInteger(amount) || amount < 1) {
        this.alert = 'Digita la cantidad'
        return false
      }

      if (!Number.isInteger(discount) || discount < 0) {
        this.alert = 'El descuento debe ser un número entero'
        return false
      }

      if (discount > this.price * amount) {
        this.alert = 'El descuento no puede ser mayor al valor total'
        return false
      }

      this.alert = ''
      return true
    },

    getDataForm() {
      return {
        id: this.product.id,
        reference: this.product.reference,
        name: this.product.name,
        amount: this.amount,
        presentation: this.presentation,
        presentations: this.presentations,
        stock: this.product.stock,
        tax_rates: this.product.tax_rates,
        price: this.price,
        discount: parseInt(this.discount, 10),
        total: parseInt(this.total, 10),
      }
    },

    updateProduct() {
      if (this.validateDataForm()) {
        this.products[this.indexUpdate] = this.getDataForm()
        this.resetForm()
      } else {
        this.$refs.amount.focus()
      }
    },

    deleteProduct(index) {
      this.products.splice(index, 1)
      this.resetForm()
    },

    editProduct(index) {
      this.indexUpdate = index
      this.product = this.products[index]
      this.discount = this.product.discount
      this.amount = this.product.amount
      this.presentations = this.product.presentations
      this.presentation = this.product.presentation
      this.update = true
      this.$refs.amount.focus()
    },

    cancel() {
      this.update = false
      this.indexUpdate = null
      this.resetForm()
    },

    resetForm() {
      this.product = []
      this.discount = 0
      this.amount = 1
      this.update = false
      this.indexUpdate = null
      this.presentations = []
      this.presentation = []
      this.percent = 0
      this.$dispatch('reset-percent')
    },

    get discountT() {
      let value = this.products.map((item) => item.discount).reduce((prev, curr) => prev + curr, 0)
      return value
    },

    get totalT() {
      let value = this.products.map((item) => item.total).reduce((prev, curr) => prev + curr, 0)
      return value
    },

    get subtotalT() {
      return this.totalT - this.totalTaxRatesT
    },

    get taxRatesByTributeT() {
      return taxRatesByTribute(this.products)
    },

    get totalTaxRatesT() {
      return totalTaxRates(this.taxRatesByTributeT)
    },

    openChange() {
      this.$wire.set('finance', this.finance)
      this.$wire.set('customer', this.customer)
      this.$wire.set('products', this.products)
      this.$wire.call('openChange')
    },

    store(cash) {
      this.$wire.set('cash', cash)
      this.$wire.call('store')
    },
  }))
})
