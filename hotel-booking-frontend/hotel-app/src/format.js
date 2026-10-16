// Shows a number as Ghana cedis, e.g. 1250 becomes GH₵1,250.00
export const money = (n) =>
  'GH₵' + Number(n).toLocaleString('en-GH', { minimumFractionDigits: 2, maximumFractionDigits: 2 })