export default () => ({
    widthTicket: '',
    print: '',
    percentageTip: 0,
    change: '0',
    formatPercentageTip: 0,
    customer: 0,

    set(config){
        this.widthTicket = config.width_ticket
        this.print = config.print
        this.percentageTip = config.percentage_tip
        this.change = config.change
        this.formatPercentageTip = config.format_percentage_tip
        this.customer = config.customer
    },

    getAll(){
      return{
        widthTicket: this.widthTicket,
        print: this.print,
        percentageTip: this.percentageTip,
        change: this.change,
        formatPercentageTip: this.formatPercentageTip,
        customer: this.customer,
      }
    }

})
