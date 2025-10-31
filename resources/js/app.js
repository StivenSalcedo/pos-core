import './bootstrap'
import './alerts'
import './forms'
import './bills/bill'
import './helpers'
import orders from './quick-sales/orders.js'
import products from './quick-sales/products.js'
import presentations from './quick-sales/presentations.js'
import cart from './quick-sales/cart.js'
import change from './quick-sales/change.js'
import customers from './quick-sales/customers.js'
import modalTables from './quick-sales/modal-tables.js'
import ticketBill from './pdfs/ticket-bill.js'
import ticketOpenCashRegister from './pdfs/ticket-open-cash-register.js'
import ticketPreBill from './pdfs/ticket-pre-bill.js'
import ticketCommandBill from './pdfs/ticket-command-bill.js'
import ticketFinancePaid from './pdfs/ticket-finance-paid.js'
import ticketService from './pdfs/ticket-service.js'
import labelService from './pdfs/label-service.js'
import config from './config.js'
import Alpine from 'alpinejs' //..

Alpine.data('alpineOrders', orders)
Alpine.data('alpineProducts', products)
Alpine.data('alpineCart', cart)
Alpine.data('alpinePresentations', presentations)
Alpine.data('alpineChange', change)
Alpine.data('alpineCustomers', customers)
Alpine.data('alpineModalTables', modalTables)
Alpine.data('alpineTicketBill', ticketBill)
Alpine.data('alpineTicketOpenCashRegister', ticketOpenCashRegister)
Alpine.data('alpineTicketPreBill', ticketPreBill)
Alpine.data('alpineTicketCommandBill', ticketCommandBill)
Alpine.data('alpineTicketFinancePaid', ticketFinancePaid)
Alpine.data('alpineTicketService', ticketService)
Alpine.data('alpineLabelService', labelService)
Alpine.store('config', config())

window.Alpine = Alpine

Alpine.start()

import './alpine'
