export default () => ({
  items: [],
  presentations: [],
  categories: [],
  search: '',
  results: [],
  category_id: null,

  init() {
    this.items = this.$wire.get('products')
    this.presentations = this.$wire.get('presentations')
    this.categories = this.$wire.get('categories')
  },

  get filteredItems() {
    let search = this.search
      .toLowerCase()
      .normalize('NFD')
      .replace(/[\u0300-\u036f]/g, '')

    this.results = this.items.filter((element) => {
      if (this.category_id !== null && element.category_id != this.category_id) return false

      if (!search) return true

      if (
        element.reference
          .toLowerCase()
          .normalize('NFD')
          .replace(/[\u0300-\u036f]/g, '')
          .includes(search)
      )
        return true
      return element.name
        .toLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .includes(search)
    })

    return this.results.slice(0, 100)
  },

  setItem(item) {
    if (!item.has_stock) return

    let product = {
      ...item,
      presentation: {},
      comment: '',
      presentations: this.presentations.filter((value) => value['product_id'] === item.id),
    }

    if (product.presentations.length) {
      this.$dispatch('show-presentations', product)
    } else {
      this.$dispatch('set-product', product)
    }

    this.$nextTick(() => {
      this.search = ''
    })
  },

  setCategory($category_id) {
    this.category_id = $category_id
  },
})
