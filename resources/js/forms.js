window.options = { style: 'currency', currency: 'USD', minimumFractionDigits: 0 };
window.numberFormat = new Intl.NumberFormat('en-US', options);

window.onlyNumbers = function(event, allowAll=false) {
  if (!allowAll) {
    if(event.charCode >= 48 && event.charCode <= 57) return true;

    event.preventDefault();
    return false;
  }
}

window.validateInteger = (value) => {
    try {
        value = parseInt(value);
        if(!value && !Number.isInteger(value)){
            value = false
        }
    } catch (error) {
        value = false;
    }
    return value
}
