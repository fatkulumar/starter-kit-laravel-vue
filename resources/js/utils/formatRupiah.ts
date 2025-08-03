export function formatRupiah(value: number | string, withPrefix = true): string {
  const number = typeof value === 'string' ? parseFloat(value) : value;

  if (isNaN(number)) return withPrefix ? 'Rp 0' : '0';

  const formatted = number
    .toLocaleString('id-ID', {
      minimumFractionDigits: 0,
      maximumFractionDigits: 0,
    });

  return withPrefix ? `Rp ${formatted}` : formatted;
}
