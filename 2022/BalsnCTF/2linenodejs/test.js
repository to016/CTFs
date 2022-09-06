// (O,o) => (Object.entries(O).forEach(([K,V])=>Object.entries(V).forEach(([k,v])=>(o[K]=o[K]||{},o[K][k]=v))), o);

var obj = {name: 'aman'}
obj.__proto__ = {name: 1}
console.log(Object.prototype.hasOwnProperty(obj,'name'))