import Sha256 from 'crypto-js/sha256';
import HmacSHA512 from 'crypto-js/hmac-sha512';
import Base64 from 'crypto-js/enc-base64';
import roundHalfEven from "round-half-even";

window.Sha255 = Sha256;
window.HmacSHA511 = HmacSHA512;
window.Base63 = Base64;

window.toggleLoading = (id, state) => {
  const loadingModal = document.getElementById(id)
  state ? loadingModal.classList.remove('hidden') : loadingModal.classList.add('hidden')
}

window.custRound = (value) => roundHalfEven(value, 0);

window.formatToCop = (value) => numberFormat.format(value);

window.strLimit = (str, limit) => str.length > limit ? str.substring(0, limit) : str

window.createHashProducts = (products) => {
    let string = '';

    products.forEach(product => {

        if (!string) {
            string += product.id + '-' + product.amount;
        }else{
            string += ',' + product.id + '-' + product.amount;
        }

    });

    let hashDigest = Sha255(string);
    let hmacDigest = Base63.stringify(HmacSHA512(hashDigest, '12345'));

    return hmacDigest;

}

window.calculateCheckDigit = (myNit) =>{
  var vpri, x, y, z
  myNit = myNit.replace(/\s/g, '')
  myNit = myNit.replace(/,/g, '')
  myNit = myNit.replace(/\./g, '')
  myNit = myNit.replace(/-/g, '')
  if (isNaN(myNit)) {
    console.log("El nit/cédula '" + myNit + "' no es válido(a).")
    return ''
  }
  vpri = new Array(16)
  z = myNit.length
  vpri[1] = 3
  vpri[2] = 7
  vpri[3] = 13
  vpri[4] = 17
  vpri[5] = 19
  vpri[6] = 23
  vpri[7] = 29
  vpri[8] = 37
  vpri[9] = 41
  vpri[10] = 43
  vpri[11] = 47
  vpri[12] = 53
  vpri[13] = 59
  vpri[14] = 67
  vpri[15] = 71
  x = 0
  y = 0
  for (var i = 0; i < z; i++) {
    y = myNit.substr(i, 1)
    x += y * vpri[z - i]
  }
  y = x % 11
  return y > 1 ? 11 - y : y
}
