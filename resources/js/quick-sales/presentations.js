export default () => ({
  show_presentations: false,
  body: null,
  presentations: {},
  product: {},

  init() {
    this.body = document.body

    this.$watch('show_presentations', (value) => {
      if (value) {
        this.body.classList.add('overflow-hidden')
      } else {
        this.body.classList.remove('overflow-hidden')
      }
    })

    window.addEventListener('show-presentations', (event) => {
      this.product = event.detail
      this.presentations = this.product.presentations
      this.show_presentations = true
    })
  },

  setPresentation(item) {
    this.product.price = item.price
    this.product.presentation = item
    this.$dispatch('set-product', this.product)
    this.show_presentations = false
  },
})
