export default () => ({
  focus: false,
  current: 0,
  search: '',
  items: [],
  results: [],
  updated: false,

  init() {
    this.items = this.$wire.get('customers')

    window.addEventListener('open-customers', (event) => {
      this.updated = event.detail
      this.showCustomers()
    })
  },

  get filteredItems() {
    this.current = -1

    this.results = this.items.filter((element) => {
      let search = this.search
        .toLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')

      if (
        element.no_identification
          .toLowerCase()
          .normalize('NFD')
          .replace(/[\u0300-\u036f]/g, '')
          .includes(search)
      ) {
        return true
      }

      return element.names
        .toLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .includes(search)
    })

    return this.results.slice(0, 10)
  },

  nextItem() {
    if (this.current < this.results.slice(0, 10).length - 1) {
      this.current++
      this.results[this.current]
    }
  },

  previewItem() {
    if (this.current > 0) {
      this.current--
      this.results[this.current]
    }
  },

  selectItem() {
    if (this.current in this.results.slice(0, 10)) {
      item = this.results[this.current]
      this.setItem(item)
    }
  },

  setItem(item) {
    if (this.updated) {
      this.$dispatch('update-customer', item)
    } else {
      this.$dispatch('set-customer', item)
    }

    this.focus = false
    this.updated = false

    this.$nextTick(() => {
      //siguiente ventana
      this.search = ''
    })

    this.show = false
  },

  showCustomers() {
    this.show = true
    this.$nextTick(() => {
      this.$refs.search.focus()
    })
  },
})
