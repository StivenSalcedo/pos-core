export const calTax = (products, tribute) => {

    if (products.length === 0) return 0

    let formatProducts = getFormatData(products);

    let productsIva = formatProducts.filter(item => item.tribute === tribute);

    const uniqueRates = getUniqueRates(productsIva);

    const productsByRate = getProductsByRate(uniqueRates, productsIva);

    const totalsByRate = getTotalsByRate(uniqueRates, productsByRate);

    const ivaByRate = getIvaByRate(totalsByRate);

    const total = getTotalIva(ivaByRate);

    return total;
}

function getFormatData(products) {
    let data = products.map((value) => {
        return {
            'tribute': value.tribute,
            'tax': value.tax,
            'price': value.price,
            'rate': value.rate,
            'total': value.total
        }
    });

    return data;
}

function getUniqueRates(products) {
    const uniqueRates = new Set();

    products.forEach(value => {
        uniqueRates.add(value.rate);
    });

    return Array.from(uniqueRates);
}

function getProductsByRate(rates, products) {
    const productsByRate = [];
    rates.forEach(value => {
        productsByRate[value] = products.filter(item => item.rate === value);
    });

    return productsByRate;
}

function getTotalsByRate(rates, productsByRate) {
    const totalsByRate = [];
    rates.forEach(value => {
        totalsByRate[value] = productsByRate[value].map(item => item.total).reduce((prev, curr) => prev + curr, 0);
    })

    return totalsByRate;
}

function getIvaByRate(totalsByRate) {
    const ivaByRate = [];

    for (let key in totalsByRate) {

        const total = totalsByRate[key];
        let rate = (key / 100) + 1;
        ivaByRate[key] = custRound(total - (total / rate), 0)

    };

    return ivaByRate;
}

function getTotalIva(ivaByRate) {
    const array = Object.values(ivaByRate);
    const value = array.reduce((prev, curr) => prev + curr, 0);
    return value
}
