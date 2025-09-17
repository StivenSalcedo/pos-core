export default () => ({
  total: 0,
  cash: '',
  tip: 0,
  alert: '',
  showPercentage: false,
  percentageTip: 0,
  payTip:true,
  paymentMethod: '1',

  init() {
    window.addEventListener('open-change', (event) => {
      this.total = event.detail
      this.percentageTip = this.$store.config.percentageTip
      this.calcTip(this.$store.config.formatPercentageTip)
      this.show = true
      this.$nextTick(() => {
        this.$refs.cash.focus()
        this.cash = ''
        this.alert = ''
      })
    })
  },

  calcTip(percentageTip) {
    if(this.payTip){
      this.tip = custRound(this.total * percentageTip)
    }else{
      this.tip = 0
    }

    this.calculateTotalCash()
  },

  get cambio() {
    this.alert = ''
    let cash = parseInt(this.cash)

    if (this.paymentMethod === '1') {
      if (!Number.isInteger(cash) || cash < 1) {
        return -this.total + -this.tip
      }
      return cash - (this.total + this.tip)
    }

    this.calculateTotalCash()
    return 0
  },

  store() {
    let cash = parseInt(this.cash)

    if (!Number.isInteger(cash) || cash < 1) {
      this.$refs.cash.focus()
      return (this.alert = 'Falta ' + formatToCop(this.cambio))
    }

    if (this.cambio < 0) {
      this.$refs.cash.focus()
      return (this.alert = 'Falta ' + formatToCop(this.cambio))
    }

    this.show = false

    this.$dispatch('store-bill', {cash: this.cash, tip: this.tip, paymentMethod: this.paymentMethod })

    this.cash = ''
    this.alert = ''
    this.paymentMethod = '1'
  },

  disableCashInput() {
    this.paymentMethod !== '1'
      ? this.$refs.cash.disabled = true
      : this.$refs.cash.disabled = false
  },

  calculateTotalCash() {
    this.disableCashInput()
    if (this.paymentMethod !== '1') {
      this.cash = this.total + this.tip
      return
    }

    this.cash = ''
  }
})
